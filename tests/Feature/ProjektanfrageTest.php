<?php

namespace Tests\Feature;

use App\Mail\ProjektanfrageBestaetigung;
use App\Mail\ProjektanfrageEingang;
use App\Models\Inquiry;
use App\Models\Service;
use App\Models\ServiceCategory;
use App\Support\Fragebogen;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

/**
 * Der Projektfragebogen (NID-19).
 *
 * Er nennt am Ende einen Preis, und zwar einer Person, die danach entscheidet,
 * ob sie mit uns spricht. Eine falsche Zahl faellt hier niemandem auf – sie
 * steht einfach da. Deshalb sind vor allem die Rechnung und ihr Weg in Mail
 * und Datensatz abgesichert.
 */
class ProjektanfrageTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $gruppe = ServiceCategory::create(['slug' => 'web', 'name' => 'Webseiten', 'position' => 0]);

        // Dieselben slugs wie in der Redaktion: an ihnen haengt die Zuordnung
        // von Antwort zu Preis.
        foreach ([
            ['basic-website', 499, 99, 59, 149],
            ['standard-website', 999, 149, 59, 149],
            ['extended-website', 2999, 429, 119, 149],
            ['shop-integration', 299, 39, 39, null],
            ['ki-chatbot', 799, 129, 49, 149],
        ] as $i => [$slug, $fest, $monat, $danach, $einrichtung]) {
            Service::create([
                'service_category_id' => $gruppe->id,
                'slug' => $slug,
                'name' => 'Leistung '.$slug,
                'description' => 'Beschreibung.',
                'price' => $fest,
                'unit' => 'eur-ab',
                'monthly_price' => $monat,
                'term_months' => 12,
                'renewal_price' => $danach,
                'setup_fee' => $einrichtung,
                'position' => $i,
            ]);
        }
    }

    private function kontakt(array $werte = []): array
    {
        return array_merge([
            'name' => 'Erika Beispiel',
            'firma' => 'Beispiel GmbH',
            'email' => 'erika@example.com',
            'unternehmer' => '1',
        ], $werte);
    }

    public function test_einstieg_zeigt_die_vorhaben_und_ist_indexierbar(): void
    {
        $html = $this->get('/projektanfrage')->assertOk()->getContent();

        foreach (Fragebogen::VORHABEN as $vorhaben) {
            $this->assertStringContainsString($vorhaben['label'], $html);
        }

        $this->assertStringNotContainsString('noindex', $html);
        $this->assertStringNotContainsString('docs.google.com', $html);
    }

    /** Ein spaeterer Schritt ohne die Antworten davor ist eine Sackgasse. */
    public function test_spaetere_schritte_fuehren_ohne_antworten_zum_anfang(): void
    {
        $this->get('/projektanfrage/kontakt')->assertRedirect('/projektanfrage');
        $this->get('/projektanfrage/danke')->assertRedirect('/projektanfrage');

        $this->post('/projektanfrage/vorhaben', ['vorhaben' => 'ki']);
        $this->get('/projektanfrage/rahmen')->assertRedirect('/projektanfrage/details');
    }

    public function test_die_detailfragen_richten_sich_nach_dem_vorhaben(): void
    {
        $this->post('/projektanfrage/vorhaben', ['vorhaben' => 'ki'])
            ->assertRedirect('/projektanfrage/details');

        $this->get('/projektanfrage/details')
            ->assertOk()
            ->assertSee('Welche Arbeit soll wegfallen?')
            ->assertDontSee('Wie umfangreich soll die Website werden?')
            ->assertSee('noindex', false);

        $this->post('/projektanfrage/vorhaben', ['vorhaben' => 'website-neu']);

        $this->get('/projektanfrage/details')
            ->assertSee('Wie umfangreich soll die Website werden?')
            ->assertDontSee('Welche Arbeit soll wegfallen?');
    }

    public function test_unbekannte_antwort_kommt_nicht_durch(): void
    {
        $this->post('/projektanfrage/vorhaben', ['vorhaben' => 'gibt-es-nicht'])
            ->assertSessionHasErrors('vorhaben');
    }

    /*
     * Der ganze Weg einmal: Website mit Shop, im Abo. Erwartet werden die
     * Summen aus der Preisliste, nicht eine Zahl aus dem Code.
     */
    public function test_vollstaendiger_bogen_speichert_die_anfrage_und_nennt_den_abo_preis(): void
    {
        Mail::fake();

        $this->post('/projektanfrage/vorhaben', ['vorhaben' => 'website-neu']);
        $this->post('/projektanfrage/details', [
            'umfang' => 'mittel',
            'ziele' => ['anfragen', 'informieren'],
            'erweiterungen' => ['shop'],
            'inhalte' => 'teilweise',
        ])->assertRedirect('/projektanfrage/rahmen');
        $this->post('/projektanfrage/rahmen', ['zahlweise' => 'abo', 'zeitrahmen' => 'quartal'])
            ->assertRedirect('/projektanfrage/kontakt');
        $this->post('/projektanfrage/kontakt', $this->kontakt(['nachricht' => 'Wir starten im Januar.']))
            ->assertRedirect('/projektanfrage/danke');

        $anfrage = Inquiry::sole();

        $this->assertSame('projektanfrage', $anfrage->type);
        $this->assertSame('neu', $anfrage->status);
        $this->assertSame('Projektanfrage: Neue Website', $anfrage->subject);
        $this->assertStringContainsString('Zwei bis vier Seiten', $anfrage->message);
        $this->assertStringContainsString('Wir starten im Januar.', $anfrage->message);
        $this->assertStringContainsString('Firma: Beispiel GmbH', $anfrage->message);

        // 149 + 39 im Monat, Einrichtung nur vom Hauptpaket, danach 59 + 39.
        $this->assertSame(188, $anfrage->details['preis']['monatlich']);
        $this->assertSame(149, $anfrage->details['preis']['einrichtung']);
        $this->assertSame(98, $anfrage->details['preis']['danach']);
        $this->assertSame(1298, $anfrage->details['preis']['festpreis']);

        $this->get('/projektanfrage/danke')
            ->assertOk()
            ->assertSee('ab 188 € im Monat')
            ->assertSee('149 € Einrichtung einmalig')
            ->assertSee('innerhalb von 24 Stunden')
            ->assertDontSee('ab 1.298 €')
            ->assertSee('noindex', false);

        Mail::assertQueued(ProjektanfrageEingang::class, fn ($mail) => $mail->hasTo(config('mail.from.address')));
        Mail::assertQueued(ProjektanfrageBestaetigung::class, fn ($mail) => $mail->hasTo('erika@example.com'));
    }

    public function test_festpreis_und_offene_zahlweise(): void
    {
        $this->assertSame(
            ['Einmalig zum Festpreis'],
            array_column(Fragebogen::preisZeilen(Fragebogen::preis([
                'vorhaben' => 'ki', 'bereich' => 'chatbot', 'zahlweise' => 'festpreis',
            ])), 'titel'),
        );

        $beide = Fragebogen::preisZeilen(Fragebogen::preis([
            'vorhaben' => 'webanwendung', 'zahlweise' => 'offen',
        ]));

        $this->assertSame(['ab 429 € im Monat', 'ab 2.999 €'], array_column($beide, 'betrag'));
    }

    /** Lieber kein Preis als ein erfundener. */
    public function test_ohne_passende_leistung_wird_kein_preis_genannt(): void
    {
        Mail::fake();

        $this->post('/projektanfrage/vorhaben', ['vorhaben' => 'unsicher']);
        $this->post('/projektanfrage/details', ['beschreibung' => 'Wir verlieren Anfragen im Postfach.']);
        $this->post('/projektanfrage/rahmen', ['zahlweise' => 'offen', 'zeitrahmen' => 'flexibel']);
        $this->post('/projektanfrage/kontakt', $this->kontakt());

        $this->get('/projektanfrage/danke')
            ->assertOk()
            ->assertSee('Den nennen wir dir im Gespräch')
            ->assertDontSee('ab 0 €');

        $this->assertNull(Inquiry::sole()->details['preis']['festpreis']);
    }

    /*
     * Wer das Vorhaben wechselt, darf die Antworten des alten nicht
     * mitnehmen – sonst rechnet der Shop einer verworfenen Website in den
     * Preis der KI-Automatisierung hinein.
     */
    public function test_wechsel_des_vorhabens_verwirft_die_alten_antworten(): void
    {
        $this->post('/projektanfrage/vorhaben', ['vorhaben' => 'website-neu']);
        $this->post('/projektanfrage/details', [
            'umfang' => 'onepager', 'ziele' => ['informieren'], 'erweiterungen' => ['shop'], 'inhalte' => 'vorhanden',
        ]);

        $this->post('/projektanfrage/vorhaben', ['vorhaben' => 'ki']);

        $this->assertSame(['vorhaben' => 'ki'], session('fragebogen'));
    }

    public function test_ohne_bestaetigung_und_mit_honigtopf_wird_nichts_gespeichert(): void
    {
        Mail::fake();

        $this->post('/projektanfrage/vorhaben', ['vorhaben' => 'webanwendung']);
        $this->post('/projektanfrage/details', [
            'nutzer' => 'kunden', 'funktionen' => ['anmeldung'], 'beschreibung' => 'Ein Kundenportal.',
        ]);
        $this->post('/projektanfrage/rahmen', ['zahlweise' => 'festpreis', 'zeitrahmen' => 'sofort']);

        $this->post('/projektanfrage/kontakt', $this->kontakt(['unternehmer' => null]))
            ->assertSessionHasErrors('unternehmer');

        $this->post('/projektanfrage/kontakt', $this->kontakt(['website' => 'https://spam.example']))
            ->assertSessionHasErrors('website');

        $this->assertDatabaseCount('inquiries', 0);
        Mail::assertNothingQueued();
    }

    /** Beide Mails muessen sich wirklich rendern lassen, mit und ohne Preis. */
    public function test_mails_nennen_denselben_preis_wie_die_seite(): void
    {
        $antworten = ['vorhaben' => 'ki', 'bereich' => 'chatbot', 'beschreibung' => 'Fragen beantworten.', 'zahlweise' => 'abo', 'zeitrahmen' => 'sofort'];

        $anfrage = Inquiry::create([
            'type' => 'projektanfrage',
            'name' => 'Erika Beispiel',
            'email' => 'erika@example.com',
            'subject' => 'Projektanfrage: KI-Automatisierung',
            'message' => 'Text',
            'details' => [
                'antworten' => $antworten,
                'zusammenfassung' => Fragebogen::zusammenfassung($antworten),
                'preis' => Fragebogen::preis($antworten),
            ],
        ]);

        (new ProjektanfrageBestaetigung($anfrage))
            ->assertSeeInHtml('ab 129 € im Monat')
            ->assertSeeInHtml('innerhalb')
            ->assertSeeInHtml('Chatbot für Website oder Support');

        (new ProjektanfrageEingang($anfrage))
            ->assertSeeInHtml('ab 129 € im Monat')
            ->assertHasReplyTo('erika@example.com');
    }

    /** Die Redaktion zeigt den genannten Preis – und bricht dabei nicht. */
    public function test_redaktion_zeigt_bogen_und_richtpreis(): void
    {
        $antworten = ['vorhaben' => 'webanwendung', 'nutzer' => 'kunden', 'funktionen' => ['anmeldung'], 'beschreibung' => 'Portal.', 'zahlweise' => 'festpreis', 'zeitrahmen' => 'sofort'];

        $anfrage = Inquiry::create([
            'type' => 'projektanfrage',
            'name' => 'Erika Beispiel',
            'email' => 'erika@example.com',
            'subject' => 'Projektanfrage: Webanwendung oder Kundenportal',
            'message' => 'Wer arbeitet damit?: Unsere Kundinnen und Kunden',
            'details' => [
                'antworten' => $antworten,
                'zusammenfassung' => Fragebogen::zusammenfassung($antworten),
                'preis' => Fragebogen::preis($antworten),
            ],
        ]);

        $this->actingAs(\App\Models\User::create([
            'name' => 'Test', 'email' => 'test@nils-digital.de', 'password' => bcrypt('geheim'),
        ]));

        $this->get('/admin/inquiries')->assertOk()->assertSee('Projektfragebogen');

        \Livewire\Livewire::test(\App\Filament\Resources\Inquiries\Pages\EditInquiry::class, ['record' => $anfrage->id])
            ->assertOk()
            ->assertSchemaStateSet(['richtpreis' => 'Einmalig zum Festpreis: ab 2.999 € – Hosting und Pflege kommen beim Festpreis getrennt dazu.']);
    }
}

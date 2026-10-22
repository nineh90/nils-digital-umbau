<?php

namespace Tests\Feature;

use App\Mail\KontaktAnfrage;
use App\Mail\KontaktBestaetigung;
use App\Mail\ProjektanfrageBestaetigung;
use App\Models\Inquiry;
use App\Support\Fragebogen;
use App\Support\Spamschutz;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

/**
 * Die Fallen aus App\Support\Spamschutz (NID-26).
 *
 * Die Beispiele sind dem Angriff vom Oktober 2026 nachgebaut: Betrugslink im
 * Namen, fremde Adresse, abgeschickt binnen Sekunden nach dem Abruf.
 */
class SpamschutzTest extends TestCase
{
    use RefreshDatabase;

    private function anfrage(array $werte = []): array
    {
        return array_merge([
            'name' => 'Erika Beispiel',
            'email' => 'erika@example.com',
            'subject' => 'Neue Website',
            'message' => 'Wir brauchen eine neue Website für unseren Betrieb.',
            Spamschutz::FELD => Spamschutz::stempel(now()->subMinute()),
        ], $werte);
    }

    public function test_das_formular_traegt_honigtopf_und_zeitstempel(): void
    {
        $this->get('/kontakt')
            ->assertSee('name="website"', false)
            ->assertSee('name="'.Spamschutz::FELD.'"', false);
    }

    /** @return array<string, array{array<string, string>}> */
    public static function roboter(): array
    {
        return [
            'Honigtopf' => [['website' => 'https://spam.example']],
            'zu schnell' => [['zeitstempel' => 'jetzt']],
            'gefälschter Zeitstempel' => [['zeitstempel' => 'eyJpdiI6ImZhbHNjaCJ9']],
            'Link im Namen' => [['name' => 'Вам перевод 186201 руб. забрать тут https://x.workers.dev/abc']],
        ];
    }

    /**
     * Wer in eine Falle tappt, sieht dasselbe wie ein Mensch – es wird aber
     * nichts gespeichert, verschickt oder übergeben.
     */
    #[\PHPUnit\Framework\Attributes\DataProvider('roboter')]
    public function test_roboter_werden_still_verworfen(array $werte): void
    {
        Mail::fake();

        if (($werte['zeitstempel'] ?? null) === 'jetzt') {
            $werte['zeitstempel'] = Spamschutz::stempel();
        }

        $this->post('/kontakt', $this->anfrage($werte))
            ->assertRedirect(route('kontakt'))
            ->assertSessionHas('erfolg')
            ->assertSessionHasNoErrors();

        $this->assertDatabaseCount('inquiries', 0);
        Mail::assertNothingQueued();
    }

    /** Eine vor dem Einbau geöffnete Seite hat keinen Stempel – das ist ein Mensch. */
    public function test_ohne_zeitstempel_gibt_es_eine_sichtbare_bitte(): void
    {
        Mail::fake();

        $werte = $this->anfrage();
        unset($werte[Spamschutz::FELD]);

        $this->post('/kontakt', $werte)->assertSessionHasErrors('message');

        $this->assertDatabaseCount('inquiries', 0);
        Mail::assertNothingQueued();
    }

    public function test_die_bestaetigung_gibt_nichts_aus_dem_formular_wieder(): void
    {
        $mail = (new KontaktBestaetigung)->render();

        $this->assertStringContainsString('ist angekommen', $mail);

        $anfrage = Inquiry::create([
            'type' => 'projektanfrage',
            'name' => 'Spam https://x.workers.dev',
            'email' => 'fremd@example.com',
            'subject' => 'Projektanfrage',
            'message' => 'Text',
            'details' => [
                'zusammenfassung' => [
                    ['frage' => 'Vorhaben', 'antwort' => 'KI-Automatisierung', 'frei' => false],
                    ['frage' => 'Beschreibung', 'antwort' => 'Betrug https://y.workers.dev', 'frei' => true],
                    ['frage' => 'Ältere Anfrage ohne Kennzeichen', 'antwort' => 'Unbekannt'],
                ],
                'preis' => Fragebogen::preis(['vorhaben' => 'ki']),
            ],
        ]);

        (new ProjektanfrageBestaetigung($anfrage))
            ->assertSeeInHtml('KI-Automatisierung')
            ->assertDontSeeInHtml('workers.dev')
            ->assertDontSeeInHtml('Unbekannt');
    }

    /**
     * Kommt doch einmal etwas durch, sind die Bestätigungen gedeckelt. Die
     * Anfragen selbst gehen weiter ein – keine echte geht verloren.
     */
    public function test_bestaetigungen_sind_je_stunde_gedeckelt(): void
    {
        Mail::fake();

        for ($i = 0; $i < Spamschutz::BESTAETIGUNGEN_JE_STUNDE + 3; $i++) {
            // Verschiedene Absender, damit die Drosselung je IP nicht greift.
            $this->withServerVariables(['REMOTE_ADDR' => '10.0.0.'.$i])
                ->post('/kontakt', $this->anfrage(['email' => "person{$i}@example.com"]));
        }

        $this->assertDatabaseCount('inquiries', Spamschutz::BESTAETIGUNGEN_JE_STUNDE + 3);
        Mail::assertQueued(KontaktAnfrage::class, Spamschutz::BESTAETIGUNGEN_JE_STUNDE + 3);
        Mail::assertQueued(KontaktBestaetigung::class, Spamschutz::BESTAETIGUNGEN_JE_STUNDE);
    }
}

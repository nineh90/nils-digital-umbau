<?php

namespace Tests\Feature;

use App\Models\Service;
use App\Models\ServiceCategory;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * Die Leistungsseite führt seit dem Abo zwei Preise für dieselbe Leistung.
 *
 * Der Umschalter dazwischen läuft über :has() in app.css – im Markup stehen
 * beide Preise immer. Genau deshalb ist er hier abgesichert: fiele die
 * CSS-Regel weg, sähe niemand einen Fehler, sondern zwei Preise nebeneinander,
 * und der Kunde suchte sich den falschen aus.
 */
class PreiseTest extends TestCase
{
    use RefreshDatabase;

    // firstOrCreate, weil mehrere Leistungen in dieselbe Gruppe gehoeren –
    // create() liefe beim zweiten Aufruf in den unique-Index auf slug.
    private function gruppe(string $slug = 'web', string $name = 'Webseiten'): ServiceCategory
    {
        return ServiceCategory::firstOrCreate(
            ['slug' => $slug],
            ['name' => $name, 'position' => 0]
        );
    }

    private function leistung(array $werte = []): Service
    {
        return Service::create(array_merge([
            'service_category_id' => $this->gruppe()->id,
            'slug' => 'onepager',
            'name' => 'Basic Website',
            'description' => 'Eine Seite, alles drauf.',
            'price' => 499,
            'unit' => 'eur-ab',
            'monthly_price' => 99,
            'term_months' => 12,
            'renewal_price' => 59,
            'position' => 0,
        ], $werte));
    }

    public function test_das_abo_nennt_laufzeit_und_was_danach_gilt(): void
    {
        $leistung = $this->leistung();

        $this->assertSame('99 € / Monat', $leistung->aboLabel());
        $this->assertSame(
            '12 Monate Mindestlaufzeit, danach 59 € / Monat, monatlich kündbar.',
            $leistung->aboBedingungen()
        );
        $this->assertSame(1188, $leistung->aboGesamt());
    }

    /*
     * Die Einrichtungsgebuehr steht vorn im Satz, weil sie sofort faellig ist –
     * hinten angestellt liest sie sich wie ein Nachschlag.
     */
    public function test_die_einrichtungsgebuehr_steht_vorn_und_zaehlt_in_die_summe(): void
    {
        $leistung = $this->leistung(['setup_fee' => 149]);

        $this->assertSame(1337, $leistung->aboGesamt());
        $this->assertStringStartsWith('149 € Einrichtung einmalig,', (string) $leistung->aboBedingungen());
    }

    /*
     * Hosting hat keinen eigenen Monatspreis, weil es im Abo schon steckt.
     * Stünde dort der Hosting-Preis, sähe der Kunde ihn zweimal.
     */
    public function test_hosting_erscheint_in_der_abo_ansicht_als_enthalten(): void
    {
        $hosting = $this->leistung([
            'slug' => 'hosting-basic',
            'name' => 'Hosting – Basic',
            'price' => 29,
            'unit' => 'eur-pro-monat',
            'monthly_price' => null,
            'term_months' => null,
            'renewal_price' => null,
        ]);

        $this->assertFalse($hosting->hatAbo());
        $this->assertSame('im Abo enthalten', $hosting->aboAnsicht());
        $this->assertSame('29 € / Monat', $hosting->priceLabel());
    }

    /*
     * Stundensätze gelten in beiden Modellen – Arbeit über den enthaltenen
     * Umfang hinaus wird so oder so nach Aufwand abgerechnet.
     */
    public function test_der_stundensatz_steht_in_beiden_ansichten_gleich(): void
    {
        $support = $this->leistung([
            'slug' => 'update-service',
            'name' => 'Updates & Änderungen',
            'price' => 75,
            'unit' => 'eur-pro-stunde',
            'monthly_price' => null,
            'term_months' => null,
            'renewal_price' => null,
        ]);

        $this->assertSame('75 € / Stunde', $support->aboAnsicht());
        $this->assertSame('75 € / Stunde', $support->priceLabel());
    }

    public function test_die_leistungsseite_zeigt_beide_preise_und_den_umschalter(): void
    {
        $this->leistung();

        $antwort = $this->get('/leistungen');

        $antwort->assertOk()
            ->assertSee('99 € / Monat')
            ->assertSee('ab 499 €')
            ->assertSee('12 Monate Mindestlaufzeit, danach 59 € / Monat, monatlich kündbar.')
            // Die beiden Marken, an denen das CSS die Ansicht umschaltet.
            ->assertSee('data-preis="monatlich"', false)
            ->assertSee('data-preis="einmalig"', false)
            // Radios, nicht Knöpfe: der Umschalter muss ohne JavaScript wirken.
            ->assertSee('id="preis-monatlich"', false)
            ->assertSee('id="preis-einmalig"', false);
    }

    /*
     * Ein Angebot mit zwei Preisen wäre für Suchmaschinen widersprüchlich.
     * Deshalb steht jede Leistung mit Abo zweimal im JSON-LD – einmal je
     * Zahlweise.
     */
    public function test_jede_zahlweise_bekommt_ein_eigenes_angebot_im_jsonld(): void
    {
        $this->leistung();
        $this->leistung(['slug' => 'stundensatz', 'name' => 'Änderungen', 'price' => 75,
            'unit' => 'eur-pro-stunde', 'monthly_price' => null, 'term_months' => null,
            'renewal_price' => null, 'position' => 1]);

        $inhalt = $this->get('/leistungen')->getContent();

        preg_match('#<script type="application/ld\+json">(.*?)</script>#s', $inhalt, $treffer);
        $daten = json_decode(html_entity_decode($treffer[1], ENT_QUOTES), true);
        $angebote = $daten['hasOfferCatalog']['itemListElement'];

        // Zwei Leistungen, davon eine mit Abo: drei Angebote.
        $this->assertCount(3, $angebote);

        $monatlich = collect($angebote)->firstWhere('name', 'Basic Website – monatlich');
        $this->assertSame(99, $monatlich['priceSpecification']['price']);
        $this->assertSame('MON', $monatlich['priceSpecification']['referenceQuantity']['unitCode']);

        // Der Einmalpreis darf davon unberührt bleiben.
        $einmalig = collect($angebote)->firstWhere('name', 'Basic Website');
        $this->assertSame(499, $einmalig['price']);
        $this->assertArrayNotHasKey('priceSpecification', $einmalig);
    }

    /*
     * Der Anlass fuer den Gruppenhinweis: eine Erweiterung wird zu einem Paket
     * dazugebucht, ihre Kosten kommen oben drauf. "39 EUR / Monat" allein
     * liest sich wie ein eigenstaendiges Angebot.
     */
    public function test_der_hinweis_einer_gruppe_steht_unter_der_ueberschrift(): void
    {
        $gruppe = $this->gruppe('erweiterungen', 'Erweiterungen');
        $gruppe->update(['note' => 'Werden zu einem bestehenden Paket dazugebucht.']);

        Service::create([
            'service_category_id' => $gruppe->id,
            'slug' => 'shop',
            'name' => 'Shop Integration',
            'description' => 'Onlineshop anbinden.',
            'price' => 299,
            'unit' => 'eur-ab',
            'monthly_price' => 39,
            'term_months' => 12,
            'renewal_price' => 39,
            'position' => 0,
        ]);

        $this->get('/leistungen')
            ->assertOk()
            ->assertSee('Werden zu einem bestehenden Paket dazugebucht.');
    }

    public function test_das_leistungsformular_fuehrt_die_abo_felder(): void
    {
        $leistung = $this->leistung();

        $nils = User::create([
            'name' => 'Test',
            'email' => 'test@nils-digital.de',
            'password' => bcrypt('geheim'),
        ]);

        $this->actingAs($nils)
            ->get("/admin/services/{$leistung->id}/edit")
            ->assertOk()
            ->assertSee('Monatspreis')
            ->assertSee('Mindestlaufzeit')
            ->assertSee('Preis danach')
            ->assertSee('Im Abo enthalten');
    }
}

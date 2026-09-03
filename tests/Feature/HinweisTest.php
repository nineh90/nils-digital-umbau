<?php

namespace Tests\Feature;

use App\Models\Notice;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * Hinweise für Aktionen und Feiertage.
 *
 * Der heikle Teil ist der Zeitraum: ein Hinweis, der zu früh erscheint oder zu
 * spät verschwindet, fällt niemandem auf – am wenigsten Nils, der ihn selbst
 * längst weggeklickt hat.
 */
class HinweisTest extends TestCase
{
    use RefreshDatabase;

    private function hinweis(array $werte = []): Notice
    {
        return Notice::create(array_merge([
            'title' => 'Winteraktion',
            'body' => 'Bis Ende Januar 15 % auf jeden neuen Onepager.',
            'placement' => 'center',
            'scheme' => 'aktion',
            'frequency' => 'once',
            'is_active' => true,
        ], $werte));
    }

    public function test_ein_eingeschalteter_hinweis_steht_auf_jeder_seite(): void
    {
        $this->hinweis();

        foreach (['/', '/leistungen', '/blog'] as $seite) {
            $this->get($seite)->assertSee('Winteraktion');
        }
    }

    public function test_ausgeschaltet_sieht_ihn_niemand(): void
    {
        $this->hinweis(['is_active' => false]);

        $this->get('/')->assertDontSee('Winteraktion');
    }

    public function test_vor_dem_beginn_und_nach_dem_ende_erscheint_er_nicht(): void
    {
        $hinweis = $this->hinweis([
            'starts_at' => now()->addDay(),
            'ends_at' => now()->addWeek(),
        ]);

        $this->get('/')->assertDontSee('Winteraktion');

        $hinweis->update(['starts_at' => now()->subDay(), 'ends_at' => now()->subHour()]);
        $this->get('/')->assertDontSee('Winteraktion');

        $hinweis->update(['starts_at' => now()->subDay(), 'ends_at' => now()->addDay()]);
        $this->get('/')->assertSee('Winteraktion');
    }

    public function test_ein_hinweis_ohne_zeitraum_laeuft_bis_er_abgeschaltet_wird(): void
    {
        $this->hinweis(['starts_at' => null, 'ends_at' => null]);

        $this->get('/')->assertSee('Winteraktion');
    }

    /*
     * Zwei Fenster gleichzeitig sind keine Botschaft mehr, sondern eine
     * Zumutung. Bei mehreren gueltigen entscheidet die Reihenfolge.
     */
    public function test_es_erscheint_immer_nur_einer(): void
    {
        $this->hinweis(['title' => 'Der zweite', 'position' => 5]);
        $this->hinweis(['title' => 'Der erste', 'position' => 1]);

        $this->get('/')
            ->assertSee('Der erste')
            ->assertDontSee('Der zweite');
    }

    /*
     * Die Leiste oben steht vor der Kopfzeile, sonst liegt sie ueber der
     * Navigation. Fenster und Ecke stehen am Ende des Quelltextes.
     */
    public function test_die_leiste_oben_steht_vor_der_kopfzeile(): void
    {
        $this->hinweis(['placement' => 'top']);

        $inhalt = $this->get('/')->getContent();

        $this->assertLessThan(
            strpos($inhalt, '<header'),
            strpos($inhalt, 'hinweis--top'),
            'Die Leiste steht hinter der Kopfzeile und legt sich damit über die Navigation.'
        );
    }

    public function test_fenster_und_ecke_stehen_hinter_dem_inhalt(): void
    {
        $this->hinweis(['placement' => 'corner']);

        $inhalt = $this->get('/')->getContent();

        $this->assertGreaterThan(
            strpos($inhalt, '<footer'),
            strpos($inhalt, 'hinweis--corner'),
            'Der Hinweis schiebt sich im Quelltext vor den Inhalt.'
        );
    }

    /*
     * Ohne JavaScript muss er sich wegklicken lassen: dafuer sorgen die
     * versteckte Checkbox und :has() in app.css.
     */
    public function test_er_laesst_sich_ohne_javascript_schliessen(): void
    {
        $this->hinweis();

        $this->get('/')
            ->assertSee('hinweis__schalter', false)
            ->assertSee('for="hinweis-zu"', false);
    }

    /*
     * Wird ein Hinweis ueberarbeitet, ist er fuer den Besucher ein neuer -
     * sonst bekaeme er die Korrektur nie zu sehen.
     */
    public function test_nach_einer_aenderung_gilt_ein_neuer_schluessel(): void
    {
        $hinweis = $this->hinweis();
        $vorher = $hinweis->speicherSchluessel();

        $this->travel(5)->minutes();
        $hinweis->update(['body' => 'Neuer Text.']);

        $this->assertNotSame($vorher, $hinweis->fresh()->speicherSchluessel());
    }

    /*
     * Der Sinn der Vorschau: einen Hinweis ansehen, bevor er scharf gestellt
     * wird. Sie muss ihn also gerade dann zeigen, wenn er oeffentlich nicht
     * erscheint.
     */
    public function test_die_vorschau_zeigt_auch_einen_ausgeschalteten_hinweis(): void
    {
        $hinweis = $this->hinweis([
            'is_active' => false,
            'starts_at' => now()->addMonth(),
        ]);

        $nils = User::create([
            'name' => 'Nils',
            'email' => 'test@nils-digital.de',
            'password' => bcrypt('geheim'),
        ]);

        // Oeffentlich unsichtbar ...
        $this->get('/')->assertDontSee('Winteraktion');

        // ... in der Vorschau sichtbar, und ohne hidden, damit man ihn sieht,
        // ohne auf das Skript zu warten.
        $this->actingAs($nils)
            ->get(route('hinweis.vorschau', $hinweis))
            ->assertOk()
            ->assertSee('Winteraktion')
            ->assertSee('data-haeufigkeit="always"', false);
    }

    public function test_die_hinweis_vorschau_ist_ohne_anmeldung_verschlossen(): void
    {
        $hinweis = $this->hinweis(['is_active' => false]);

        $this->get(route('hinweis.vorschau', $hinweis))->assertRedirect('/admin/login');
    }

    public function test_das_formular_in_der_redaktion_laesst_sich_oeffnen(): void
    {
        $hinweis = $this->hinweis();

        $nils = User::create([
            'name' => 'Nils',
            'email' => 'test@nils-digital.de',
            'password' => bcrypt('geheim'),
        ]);

        $this->actingAs($nils)
            ->get("/admin/notices/{$hinweis->id}/edit")
            ->assertOk()
            ->assertSee('Darstellung')
            ->assertSee('Farbschema')
            ->assertSee('Häufigkeit');
    }
}

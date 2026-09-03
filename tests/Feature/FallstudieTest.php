<?php

namespace Tests\Feature;

use App\Models\Project;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;
use Tests\TestCase;

/**
 * Fallstudien im eigenen Editor schreiben statt im Browser.
 *
 * Der Umweg über eine Datei ist nur dann etwas wert, wenn der Rückweg
 * verlässlich ist – sonst schreibt jemand eine halbe Stunde Text und er landet
 * nirgends.
 */
class FallstudieTest extends TestCase
{
    use RefreshDatabase;

    private function projekt(array $werte = []): Project
    {
        return Project::create(array_merge([
            'slug' => 'lerndex',
            'title' => 'Lerndex',
            'description' => 'Lernsoftware.',
            'position' => 0,
        ], $werte));
    }

    /*
     * Eigener Pfad, nicht storage/app/fallstudien. Der Test hat beim ersten
     * Lauf den echten Entwurf gelesen und im tearDown geloescht – Testdaten
     * und Arbeitsdateien duerfen sich nicht dasselbe Verzeichnis teilen.
     */
    private string $datei;

    protected function setUp(): void
    {
        parent::setUp();

        $this->datei = storage_path('framework/testing/fallstudie.md');
        File::ensureDirectoryExists(dirname($this->datei));
        File::delete($this->datei);
    }

    protected function tearDown(): void
    {
        File::delete($this->datei);

        parent::tearDown();
    }

    public function test_der_text_geht_heraus_und_kommt_zurueck(): void
    {
        $projekt = $this->projekt(['body' => "## Überschrift\n\nEin Absatz."]);

        Artisan::call('nd:fallstudie', ['projekt' => 'lerndex', '--datei' => $this->datei]);
        $this->assertSame("## Überschrift\n\nEin Absatz.", File::get($this->datei));

        File::put($this->datei, "## Neu\n\nGeändert im Editor.");
        Artisan::call('nd:fallstudie', ['projekt' => 'lerndex', '--datei' => $this->datei, '--zurueckschreiben' => true]);

        $this->assertSame("## Neu\n\nGeändert im Editor.", $projekt->fresh()->body);
    }

    /*
     * Der Sinn des Feldes: ohne Fallstudie steht die Detailseite auf noindex.
     * Der Rueckweg muss diesen Schalter also wirklich umlegen.
     */
    public function test_erst_mit_text_darf_die_seite_in_den_index(): void
    {
        $projekt = $this->projekt();

        $this->get(route('projekte.show', $projekt))->assertSee('noindex', false);

        File::put($this->datei, 'Eine echte Fallstudie mit Inhalt.');
        Artisan::call('nd:fallstudie', ['projekt' => 'lerndex', '--datei' => $this->datei, '--zurueckschreiben' => true]);

        $this->get(route('projekte.show', $projekt))->assertDontSee('noindex', false);
    }

    public function test_eine_unbekannte_kennung_nennt_die_vorhandenen(): void
    {
        $this->projekt();

        $this->assertSame(1, Artisan::call('nd:fallstudie', ['projekt' => 'gibt-es-nicht', '--datei' => $this->datei]));
        $this->assertStringContainsString('lerndex', Artisan::output());
    }
}

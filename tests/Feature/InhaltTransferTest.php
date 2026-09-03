<?php

namespace Tests\Feature;

use App\Models\Post;
use App\Models\Project;
use App\Models\User;
use App\Support\Inhalt;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Tests\TestCase;

/**
 * Der einmalige Weg lokal → Server, den CLAUDE.md als offenen Punkt führt.
 *
 * Getestet wird gegen den echten Altbestand, weil genau dessen Eigenheiten das
 * Verfahren brechen könnten: Verknüpfungen über post_project, legacy_id als
 * Träger der 301-Weiterleitungen, Kategorien mit Fremdschlüsseln.
 */
class InhaltTransferTest extends TestCase
{
    use RefreshDatabase;

    private string $datei;

    protected function setUp(): void
    {
        parent::setUp();

        $this->datei = storage_path('framework/testing/inhalt-test.json');
        Artisan::call('nd:import-legacy', ['--ohne-bilder' => true]);
    }

    protected function tearDown(): void
    {
        File::delete($this->datei);
        File::delete(File::glob(dirname($this->datei).'/vorher-*.json'));

        parent::tearDown();
    }

    public function test_der_stand_ueberlebt_ausgeben_und_einlesen_unveraendert(): void
    {
        $vorher = collect(Inhalt::TABELLEN)
            ->mapWithKeys(fn ($t) => [$t => DB::table($t)->get()->map(fn ($z) => (array) $z)->all()]);

        Artisan::call('nd:inhalt-ausgeben', ['--datei' => $this->datei]);

        // Alles kaputt machen, was der Transfer wiederherstellen soll.
        Post::query()->delete();
        Project::query()->delete();

        Artisan::call('nd:inhalt-einlesen', [
            '--datei' => $this->datei,
            '--force' => true,
            '--ohne-sicherung' => true,
        ]);

        foreach (Inhalt::TABELLEN as $tabelle) {
            $this->assertEquals(
                $vorher[$tabelle],
                DB::table($tabelle)->get()->map(fn ($z) => (array) $z)->all(),
                "Tabelle {$tabelle} kam nicht unverändert zurück."
            );
        }
    }

    /*
     * Der Grund, warum die IDs mitgehen muessen: post_project verknuepft
     * darueber, und legacy_id traegt die 301-Weiterleitungen.
     */
    public function test_verknuepfungen_und_weiterleitungen_bleiben_heil(): void
    {
        $beitrag = Post::whereNotNull('legacy_id')->has('projects')->firstOrFail();
        $alteAdresse = '/pages/blog-post.html?id='.$beitrag->legacy_id;
        $ziel = $this->get($alteAdresse)->headers->get('Location');

        Artisan::call('nd:inhalt-ausgeben', ['--datei' => $this->datei]);
        Artisan::call('nd:inhalt-einlesen', [
            '--datei' => $this->datei,
            '--force' => true,
            '--ohne-sicherung' => true,
        ]);

        $this->assertNotEmpty(DB::table('post_project')->get());
        $this->get($alteAdresse)->assertRedirect($ziel);
    }

    /*
     * Die Falle, die diesen Befehl gefaehrlich machen wuerde: lokal liegt in
     * users der Vorgabewert aus DatabaseSeeder. Ginge die Tabelle mit, schoebe
     * der erste Transfer ein bekanntes Passwort auf einen oeffentlichen Server.
     */
    public function test_zugaenge_gehen_niemals_mit(): void
    {
        User::create([
            'name' => 'Nur hier',
            'email' => 'lokal@nils-digital.de',
            'password' => bcrypt('umbau-lokal'),
        ]);

        Artisan::call('nd:inhalt-ausgeben', ['--datei' => $this->datei]);
        $roh = File::get($this->datei);

        $this->assertStringNotContainsString('lokal@nils-digital.de', $roh);
        $this->assertStringNotContainsString('users', json_encode(array_keys(
            json_decode($roh, true)['tabellen']
        )));

        foreach (Inhalt::NICHT_UEBERTRAGEN as $tabelle) {
            $this->assertNotContains($tabelle, Inhalt::TABELLEN);
        }
    }

    public function test_ein_zugang_auf_dem_ziel_ueberlebt_den_transfer(): void
    {
        $nils = User::create([
            'name' => 'Auf dem Server',
            'email' => 'server@nils-digital.de',
            'password' => bcrypt('etwas-eigenes'),
        ]);

        Artisan::call('nd:inhalt-ausgeben', ['--datei' => $this->datei]);
        Artisan::call('nd:inhalt-einlesen', [
            '--datei' => $this->datei,
            '--force' => true,
            '--ohne-sicherung' => true,
        ]);

        $this->assertDatabaseHas('users', ['id' => $nils->id, 'email' => 'server@nils-digital.de']);
    }

    public function test_die_sicherung_haelt_den_stand_von_vorher_fest(): void
    {
        Artisan::call('nd:inhalt-ausgeben', ['--datei' => $this->datei]);

        $anzahlVorher = Post::count();
        Post::query()->delete();

        Artisan::call('nd:inhalt-einlesen', ['--datei' => $this->datei, '--force' => true]);

        $sicherungen = File::glob(dirname($this->datei).'/vorher-*.json');
        $this->assertCount(1, $sicherungen);

        $gesichert = json_decode(File::get($sicherungen[0]), true);
        $this->assertCount(0, $gesichert['tabellen']['posts'], 'Die Sicherung zeigt nicht den Stand vor dem Einlesen.');
        $this->assertSame($anzahlVorher, Post::count());
    }

    public function test_eine_kaputte_datei_aendert_nichts(): void
    {
        File::put($this->datei, '{"kein":"transfer"}');

        $vorher = Post::count();

        $this->assertSame(
            1,
            Artisan::call('nd:inhalt-einlesen', ['--datei' => $this->datei, '--force' => true])
        );
        $this->assertSame($vorher, Post::count());
    }
}

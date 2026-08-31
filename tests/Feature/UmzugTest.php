<?php

namespace Tests\Feature;

use App\Models\Post;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Artisan;
use Tests\TestCase;

/**
 * Absicherung des Umzugs von der alten statischen Seite.
 *
 * Diese Tests arbeiten bewusst mit dem echten Altbestand aus legacy/, nicht mit
 * erfundenen Daten: Sie sollen genau die Fälle abdecken, die beim Umschalten
 * Rankings kosten könnten.
 *
 * database/legacy/url-map.csv ist die abgestimmte Zuordnung alt zu neu. Weicht
 * ein erzeugter Slug davon ab, schlägt der Test fehl – denn dann zeigt später
 * eine 301 ins Leere.
 */
class UmzugTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        Artisan::call('nd:import-legacy', ['--ohne-bilder' => true]);
    }

    /** @return array<int, array{typ: string, alt: string, neu: string}> */
    private function urlKarte(): array
    {
        $pfad = database_path('legacy/url-map.csv');
        $zeilen = array_map('str_getcsv', file($pfad, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES));
        $kopf = array_shift($zeilen);

        return array_map(fn ($z) => array_combine($kopf, $z), $zeilen);
    }

    public function test_der_altbestand_kommt_vollstaendig_an(): void
    {
        $ausJson = json_decode(file_get_contents(base_path('legacy/assets/data/blog.json')), true);

        $this->assertCount(count($ausJson), Post::all(), 'Beim Import ist ein Beitrag verloren gegangen.');
        $this->assertSame(0, Post::whereNull('category_id')->count());
        $this->assertSame(0, Post::whereNull('legacy_id')->count());
    }

    public function test_jeder_slug_entspricht_der_abgestimmten_url_karte(): void
    {
        $abweichungen = [];

        foreach ($this->urlKarte() as $zeile) {
            if ($zeile['typ'] !== 'beitrag') {
                continue;
            }

            $legacyId = (int) str_replace('/pages/blog-post.html?id=', '', $zeile['alt']);
            $erwartet = str_replace('/blog/', '', $zeile['neu']);
            $tatsaechlich = Post::where('legacy_id', $legacyId)->value('slug');

            if ($tatsaechlich !== $erwartet) {
                $abweichungen[] = "id={$legacyId}: erwartet '{$erwartet}', erzeugt '{$tatsaechlich}'";
            }
        }

        $this->assertSame([], $abweichungen, "Slugs weichen von der URL-Karte ab:\n".implode("\n", $abweichungen));
    }

    public function test_jede_alte_beitragsadresse_leitet_dauerhaft_auf_ihr_ziel(): void
    {
        $fehler = [];

        foreach ($this->urlKarte() as $zeile) {
            if ($zeile['typ'] !== 'beitrag') {
                continue;
            }

            $antwort = $this->get($zeile['alt']);
            $ziel = url($zeile['neu']);

            if ($antwort->getStatusCode() !== 301) {
                $fehler[] = "{$zeile['alt']}: Status {$antwort->getStatusCode()} statt 301";

                continue;
            }

            if ($antwort->headers->get('Location') !== $ziel) {
                $fehler[] = "{$zeile['alt']}: zeigt auf {$antwort->headers->get('Location')} statt {$ziel}";
            }
        }

        $this->assertSame([], $fehler, "Fehlerhafte Weiterleitungen:\n".implode("\n", $fehler));
    }

    /**
     * Keine Weiterleitungsketten: eine alte Adresse muss auf ihr Endziel
     * zeigen, nicht auf eine weitere Weiterleitung.
     */
    public function test_weiterleitungen_zeigen_direkt_auf_das_endziel(): void
    {
        $beitrag = Post::whereNotNull('legacy_id')->first();

        $ziel = $this->get("/pages/blog-post.html?id={$beitrag->legacy_id}")
            ->headers->get('Location');

        $this->get(str_replace(url('/'), '', $ziel))->assertOk();
    }

    public function test_jeder_beitrag_ist_erreichbar_und_traegt_sein_seo(): void
    {
        $fehler = [];

        foreach (Post::published()->get() as $beitrag) {
            $antwort = $this->get(route('blog.show', $beitrag));

            if ($antwort->getStatusCode() !== 200) {
                $fehler[] = "{$beitrag->slug}: Status {$antwort->getStatusCode()}";

                continue;
            }

            $html = $antwort->getContent();

            foreach (['<title>', 'rel="canonical"', 'property="og:title"', '"@type":"BlogPosting"'] as $pflicht) {
                if (! str_contains($html, $pflicht)) {
                    $fehler[] = "{$beitrag->slug}: {$pflicht} fehlt";
                }
            }
        }

        $this->assertSame([], $fehler, "Beiträge mit Problemen:\n".implode("\n", $fehler));
    }
}

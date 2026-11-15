<?php

namespace Tests\Feature;

use App\Support\Markdown;
use App\Support\MedienAblage;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

/**
 * Bilder und Videos im Beitragstext (NID-49).
 */
class MedienImTextTest extends TestCase
{
    private function html(string $markdown): string
    {
        return app(Markdown::class)->toHtml($markdown);
    }

    public function test_bild_auf_eigener_zeile_wird_figur_mit_unterschrift(): void
    {
        $html = $this->html("Davor.\n\n![Startseite des Vereins](/medien/blog/start.jpg \"Die neue Startseite\")\n\nDanach.");

        $this->assertStringContainsString('<figure class="medium medium--bild">', $html);
        $this->assertStringContainsString('<img src="/medien/blog/start.jpg" alt="Startseite des Vereins" loading="lazy"', $html);
        $this->assertStringContainsString('<figcaption>Die neue Startseite</figcaption>', $html);
        // Keine Figur im Absatz – das wäre ungültiges HTML.
        $this->assertStringNotContainsString('<p><figure', $html);
    }

    public function test_bild_mitten_im_satz_bleibt_inline(): void
    {
        $html = $this->html('Ein Symbol ![x](/a.png) im Satz.');

        $this->assertStringContainsString('<p>Ein Symbol <img src="/a.png" alt="x" /> im Satz.</p>', $html);
        $this->assertStringNotContainsString('<figure', $html);
    }

    public function test_eigene_videodatei_wird_abspielbar_ohne_fremdanbieter(): void
    {
        $html = $this->html('![Rundgang](/medien/blog/rundgang.mp4)');

        $this->assertStringContainsString('<figure class="medium medium--video">', $html);
        $this->assertStringContainsString('<video src="/medien/blog/rundgang.mp4" controls playsinline preload="metadata" aria-label="Rundgang">', $html);
    }

    public function test_gleichnamiges_jpg_wird_vorschaubild(): void
    {
        $verzeichnis = public_path('medien/test');
        @mkdir($verzeichnis, 0777, true);
        touch($verzeichnis.'/clip.jpg');

        try {
            $this->assertStringContainsString('poster="/medien/test/clip.jpg"', $this->html('![x](/medien/test/clip.mp4)'));
            $this->assertStringNotContainsString('poster=', $this->html('![x](/medien/test/anderer.mp4)'));
        } finally {
            unlink($verzeichnis.'/clip.jpg');
            rmdir($verzeichnis);
        }
    }

    public function test_youtube_laedt_erst_auf_klick(): void
    {
        $html = $this->html('![Rundgang](https://www.youtube.com/watch?v=dQw4w9WgXcQ)');

        $this->assertStringContainsString('data-einbettung="https://www.youtube-nocookie.com/embed/dQw4w9WgXcQ"', $html);
        $this->assertStringContainsString('data-einbettung-format="quer"', $html);
        $this->assertStringNotContainsString('<iframe', $html);
    }

    public function test_youtube_shorts_und_markierung_sind_hochkant(): void
    {
        $this->assertStringContainsString('data-einbettung-format="hochkant"', $this->html('![a](https://youtube.com/shorts/dQw4w9WgXcQ)'));
        $this->assertStringContainsString('data-einbettung-format="hochkant"', $this->html('![a](https://youtu.be/dQw4w9WgXcQ#hochkant)'));
        $this->assertStringContainsString('medium--hochkant', $this->html('![a](https://youtu.be/dQw4w9WgXcQ#hochkant)'));
    }

    public function test_beschreibung_und_unterschrift_werden_maskiert(): void
    {
        $html = $this->html('![<script>x</script>](/a.jpg "<b>u</b>")');

        $this->assertStringNotContainsString('<script>', $html);
        $this->assertStringNotContainsString('<b>u</b>', $html);
    }

    public function test_javascript_adressen_werden_nicht_zur_quelle(): void
    {
        $this->assertStringNotContainsString('javascript:', $this->html('![x](javascript:alert(1))'));
    }

    public function test_ablage_verkleinert_grosse_fotos_mit_lesbarem_namen(): void
    {
        Storage::fake('inhalte');

        $pfad = MedienAblage::speichern(UploadedFile::fake()->image('Startseite Verein.jpg', 4000, 3000), 'medien/blog');

        $this->assertMatchesRegularExpression('~^medien/blog/startseite-verein-[a-z0-9]{4}\.jpg$~', $pfad);
        [$breite, $hoehe] = getimagesizefromstring(Storage::disk('inhalte')->get($pfad));
        $this->assertSame([1600, 1200], [$breite, $hoehe]);
    }

    public function test_ablage_laesst_videos_unangetastet(): void
    {
        Storage::fake('inhalte');

        $datei = UploadedFile::fake()->createWithContent('Rundgang.mp4', 'videodaten');
        $pfad = MedienAblage::speichern($datei, 'medien/blog');

        $this->assertStringEndsWith('.mp4', $pfad);
        $this->assertSame('videodaten', Storage::disk('inhalte')->get($pfad));
    }
}

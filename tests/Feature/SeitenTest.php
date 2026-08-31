<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\DataProvider;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class SeitenTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        Artisan::call('nd:import-legacy', ['--ohne-bilder' => true]);
    }

    public static function seiten(): array
    {
        return [
            'Startseite' => ['/'],
            'Leistungen' => ['/leistungen'],
            'Über uns' => ['/ueber-uns'],
            'Team' => ['/team'],
            'Kontakt' => ['/kontakt'],
            'Projektanfrage' => ['/projektanfrage'],
            'Termine' => ['/termine'],
            'Impressum' => ['/impressum'],
            'Datenschutz' => ['/datenschutz'],
            'AGB' => ['/agb'],
            'Blog' => ['/blog'],
            'Projekte' => ['/projekte'],
        ];
    }

    /**
     * Jede Seite muss erreichbar sein und ihr eigenes SEO tragen. Eine Seite
     * ohne Title und Canonical ist für Google praktisch nicht vorhanden.
     */
    #[DataProvider('seiten')]
    public function test_seite_ist_erreichbar_und_traegt_seo(string $pfad): void
    {
        $html = $this->get($pfad)->assertOk()->getContent();

        $this->assertStringContainsString('<title>', $html);
        $this->assertStringContainsString('rel="canonical"', $html);
        $this->assertStringContainsString('name="description"', $html);
        $this->assertStringContainsString('property="og:title"', $html);
    }

    #[DataProvider('seiten')]
    public function test_seite_hat_genau_eine_h1(string $pfad): void
    {
        $html = $this->get($pfad)->getContent();

        $this->assertSame(1, substr_count($html, '<h1'), "Auf {$pfad} steht nicht genau eine H1.");
    }


    /**
     * Jede öffentliche Seite muss aus der Kopfzeile erreichbar sein.
     *
     * Genau das ist beim Bau schiefgegangen: "Über uns" und "Das Team" waren
     * fertig, standen aber in keinem Menü – man kam nur über die Fußzeile hin.
     * Eine Seite, die niemand findet, ist keine Seite.
     */
    public function test_jede_seite_ist_ueber_die_kopfzeile_erreichbar(): void
    {
        $html = $this->get('/')->getContent();
        $kopfzeile = substr($html, 0, strpos($html, '</header>') ?: strlen($html));

        // Die Pflichtseiten gehören bewusst in die Fußzeile, nicht ins
        // Hauptmenü – sie werden gesucht, nicht durchgeblättert.
        $nurFusszeile = ['/impressum', '/datenschutz', '/agb'];

        $fehlend = [];

        foreach (self::seiten() as $name => [$pfad]) {
            if ($pfad === '/' || in_array($pfad, $nurFusszeile, true)) {
                continue;
            }

            if (! str_contains($kopfzeile, 'href="'.url($pfad).'"')) {
                $fehlend[] = "{$name} ({$pfad})";
            }
        }

        $this->assertSame([], $fehlend, 'Nicht im Menü verlinkt: '.implode(', ', $fehlend));
    }

    public function test_pflichtseiten_stehen_in_der_fusszeile(): void
    {
        $html = $this->get('/')->getContent();
        $fusszeile = substr($html, strpos($html, '<footer') ?: 0);

        foreach (['/impressum', '/datenschutz', '/agb'] as $pfad) {
            $this->assertStringContainsString('href="'.url($pfad).'"', $fusszeile, "{$pfad} fehlt in der Fußzeile.");
        }
    }

    public function test_alle_alten_seitenadressen_leiten_dauerhaft_um(): void
    {
        $karte = [
            '/index.html' => '/',
            '/pages/webdesign-leistung.html' => '/leistungen',
            '/pages/projekte.html' => '/projekte',
            '/pages/blog.html' => '/blog',
            '/pages/ueber-uns.html' => '/ueber-uns',
            '/pages/team.html' => '/team',
            '/pages/kontakt.html' => '/kontakt',
            '/pages/projektfragebogen.html' => '/projektanfrage',
            '/pages/reservierung.html' => '/termine',
            '/pages/impressum.html' => '/impressum',
            '/pages/datenschutz.html' => '/datenschutz',
            '/pages/agb.html' => '/agb',
            '/pages/sunnycam.html' => '/',
            '/pages/shop.html' => '/',
        ];

        $fehler = [];

        foreach ($karte as $alt => $neu) {
            $antwort = $this->get($alt);

            if ($antwort->getStatusCode() !== 301) {
                $fehler[] = "{$alt}: Status {$antwort->getStatusCode()} statt 301";
            } else {
                // Ein relativer Location-Header ist zulässig, deshalb wird der
                // Pfad verglichen und nicht die vollständige Adresse.
                $ziel = parse_url($antwort->headers->get('Location'), PHP_URL_PATH) ?: '/';

                if ($ziel !== $neu) {
                    $fehler[] = "{$alt}: zeigt auf {$ziel} statt {$neu}";
                }
            }
        }

        $this->assertSame([], $fehler, implode("\n", $fehler));
    }

    public function test_sitemap_enthaelt_beitraege_und_kategorien(): void
    {
        $xml = $this->get('/sitemap.xml')
            ->assertOk()
            ->assertHeader('Content-Type', 'application/xml; charset=UTF-8')
            ->getContent();

        // Der eigentliche Gewinn: die alte Sitemap kannte elf Adressen und
        // keinen einzigen Beitrag.
        $this->assertGreaterThan(50, substr_count($xml, '<loc>'));
        $this->assertStringContainsString('/blog/', $xml);
        $this->assertStringContainsString('/blog/kategorie/', $xml);
    }

    public function test_projekte_ohne_fallstudie_stehen_nicht_in_der_sitemap(): void
    {
        // Kein Altprojekt hat bisher eine Fallstudie. Solche Seiten stehen auf
        // noindex – dann dürfen sie auch nicht in der Sitemap stehen, sonst
        // widersprechen sich beide Signale.
        $this->assertStringNotContainsString('/projekte/lerndex', $this->get('/sitemap.xml')->getContent());
    }

    /**
     * Die Vorschau muss crawlbar bleiben.
     *
     * Ein "Disallow: /" waere hier ein Eigentor: es verbietet das Crawlen,
     * verhindert aber nicht, dass eine verlinkte Adresse im Index landet – und
     * weil Google die Seite dann nicht abrufen darf, saehe es den
     * noindex-Header nie, der genau das verhindern wuerde.
     */
    public function test_robots_sperrt_die_vorschau_nicht_aus(): void
    {
        $inhalt = $this->get('http://neu.nils-digital.de/robots.txt')
            ->assertOk()
            ->getContent();

        $this->assertStringNotContainsString('Disallow: /'.PHP_EOL, $inhalt);
        $this->assertStringContainsString('Allow: /', $inhalt);

        // Aber keine Einladung: die Sitemap gehoert nur zur Live-Fassung.
        $this->assertStringNotContainsString('Sitemap:', $inhalt);
    }

    public function test_robots_gibt_die_live_domain_frei(): void
    {
        $inhalt = $this->get('http://nils-digital.de/robots.txt')->assertOk()->getContent();

        $this->assertStringContainsString('Allow: /', $inhalt);
        $this->assertStringContainsString('Sitemap:', $inhalt);
        $this->assertStringNotContainsString('Disallow: /'.PHP_EOL, $inhalt);
    }

    /**
     * Sitemap und Feed enthalten eine XML-Deklaration. Steht "<?xml" woertlich
     * in der Vorlage, laesst Blade die Zeile bei eingeschaltetem
     * short_open_tag unkompiliert stehen und PHP bricht mit einem
     * Syntaxfehler ab – lokal unauffaellig, auf dem Server ein 500er.
     */
    public function test_xml_ausgaben_beginnen_mit_gueltiger_deklaration(): void
    {
        foreach (['/sitemap.xml', '/blog/feed'] as $pfad) {
            $inhalt = $this->get($pfad)->assertOk()->getContent();

            $this->assertStringStartsWith('<?xml version="1.0" encoding="UTF-8"?>', trim($inhalt), $pfad);
        }
    }

    public function test_404_seite_hilft_weiter(): void
    {
        $this->get('/gibt-es-nicht')
            ->assertNotFound()
            ->assertSee('Diese Seite gibt es nicht')
            ->assertSee('noindex', false);
    }

    public function test_kontaktformular_verschickt_beide_mails(): void
    {
        Mail::fake();

        $this->post('/kontakt', [
            'name' => 'Testkunde',
            'email' => 'kunde@example.com',
            'subject' => 'Neue Website',
            'message' => 'Wir brauchen eine neue Website für unseren Betrieb.',
        ])->assertRedirect(route('kontakt'))->assertSessionHas('erfolg');

        Mail::assertQueued(\App\Mail\KontaktAnfrage::class);
        Mail::assertQueued(\App\Mail\KontaktBestaetigung::class);
    }

    public function test_ausgefuellter_honigtopf_verschickt_nichts(): void
    {
        Mail::fake();

        $this->post('/kontakt', [
            'name' => 'Robot',
            'email' => 'robot@example.com',
            'subject' => 'Werbung',
            'message' => 'Kaufen Sie unsere Produkte, sehr guenstig.',
            'website' => 'https://spam.example',
        ])->assertSessionHasErrors('website');

        Mail::assertNothingQueued();
    }

    public function test_unvollstaendiges_formular_wird_abgewiesen(): void
    {
        Mail::fake();

        $this->post('/kontakt', ['name' => 'Nur ein Name'])
            ->assertSessionHasErrors(['email', 'subject', 'message']);

        Mail::assertNothingQueued();
    }

    /**
     * Google-Einbettungen dürfen erst nach Klick laden. Stünde der iframe im
     * ausgelieferten HTML, flösse die IP-Adresse schon beim Seitenaufruf zu
     * Google – ohne dass jemand zugestimmt hätte.
     */
    public function test_google_einbettungen_laden_nicht_von_allein(): void
    {
        foreach (['/projektanfrage', '/termine'] as $pfad) {
            $html = $this->get($pfad)->assertOk()->getContent();

            $this->assertStringNotContainsString('<iframe', $html, "Auf {$pfad} steht ein iframe im HTML.");
            $this->assertStringContainsString('Inhalt laden', $html);
        }
    }
}

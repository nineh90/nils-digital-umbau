<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Post;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BlogTest extends TestCase
{
    use RefreshDatabase;

    private function beitrag(array $werte = []): Post
    {
        $kategorie = Category::firstOrCreate(
            ['slug' => 'projekte'],
            ['name' => 'Projekte', 'color' => 'rgba(255, 152, 0, 0.75)']
        );

        return Post::create(array_merge([
            'legacy_id' => 47,
            'category_id' => $kategorie->id,
            'slug' => 'ein-beitrag',
            'title' => 'Ein Beitrag über Barrierefreiheit',
            'teaser' => 'Kurzer Anrisstext, der als Meta-Description dient.',
            'content' => "## Überschrift\nZeile eins\nZeile zwei\n\n- Punkt",
            'status' => 'published',
            'published_at' => now()->subDay(),
        ], $werte));
    }

    public function test_uebersicht_zeigt_veroeffentlichte_beitraege(): void
    {
        $this->beitrag();

        $this->get('/blog')
            ->assertOk()
            ->assertSee('Ein Beitrag über Barrierefreiheit', false);
    }

    public function test_entwuerfe_und_zukuenftige_beitraege_bleiben_verborgen(): void
    {
        $this->beitrag(['slug' => 'entwurf', 'legacy_id' => 100, 'status' => 'draft']);
        $this->beitrag(['slug' => 'spaeter', 'legacy_id' => 101, 'published_at' => now()->addWeek()]);

        $this->get('/blog')->assertOk()->assertDontSee('Ein Beitrag über Barrierefreiheit', false);
        $this->get('/blog/entwurf')->assertNotFound();
        $this->get('/blog/spaeter')->assertNotFound();
    }

    /**
     * Der Kern des Umbaus: Titel, Beschreibung, Canonical und og:image müssen
     * IM AUSGELIEFERTEN HTML stehen. Auf der alten Seite setzte das erst
     * JavaScript im Browser – Crawler von WhatsApp und LinkedIn sahen nichts.
     */
    public function test_beitrag_liefert_seo_ohne_javascript(): void
    {
        $beitrag = $this->beitrag(['hero_image' => 'assets/images/blog/bild.png']);
        $html = $this->get('/blog/ein-beitrag')->assertOk()->getContent();

        $this->assertStringContainsString('<title>Ein Beitrag über Barrierefreiheit – ', $html);
        $this->assertStringContainsString('name="description" content="Kurzer Anrisstext', $html);
        $this->assertStringContainsString('rel="canonical" href="'.route('blog.show', $beitrag).'"', $html);
        $this->assertStringContainsString('property="og:type" content="article"', $html);
        $this->assertStringContainsString('assets/images/blog/bild.png', $html);
        $this->assertStringContainsString('"@type":"BlogPosting"', $html);
        $this->assertStringContainsString('"@type":"BreadcrumbList"', $html);
    }

    public function test_markdown_wird_serverseitig_gerendert(): void
    {
        $this->beitrag();
        $html = $this->get('/blog/ein-beitrag')->getContent();

        $this->assertStringContainsString('<h2>Überschrift</h2>', $html);
        // Einfacher Zeilenumbruch bleibt ein Umbruch – wie im alten Parser.
        $this->assertStringContainsString('Zeile eins<br />', $html);
        $this->assertStringContainsString('<li>Punkt</li>', $html);
    }

    public function test_kategorieseite_zeigt_nur_ihre_beitraege(): void
    {
        $this->beitrag();
        $andere = Category::create(['slug' => 'shop', 'name' => 'Shop']);
        $this->beitrag(['slug' => 'shop-beitrag', 'legacy_id' => 102, 'title' => 'Ein Shop-Beitrag', 'category_id' => $andere->id]);

        $this->get('/blog/kategorie/projekte')
            ->assertOk()
            ->assertSee('Ein Beitrag über Barrierefreiheit', false)
            ->assertDontSee('Ein Shop-Beitrag', false);
    }

    public function test_uebersichtsseite_zwei_wird_nicht_indexiert(): void
    {
        for ($i = 1; $i <= 8; $i++) {
            $this->beitrag(['slug' => "beitrag-{$i}", 'legacy_id' => 200 + $i]);
        }

        $this->get('/blog')->assertOk()->assertDontSee('noindex');
        $this->get('/blog?page=2')->assertOk()->assertSee('noindex', false);
    }

    public function test_alte_beitragsadresse_leitet_dauerhaft_um(): void
    {
        $beitrag = $this->beitrag();

        $this->get('/pages/blog-post.html?id=47')
            ->assertStatus(301)
            ->assertRedirect(route('blog.show', $beitrag));
    }

    public function test_unbekannte_alte_id_landet_in_der_uebersicht(): void
    {
        $this->get('/pages/blog-post.html?id=9999')
            ->assertStatus(301)
            ->assertRedirect(route('blog.index'));
    }

    public function test_alte_uebersichtsadresse_leitet_dauerhaft_um(): void
    {
        $this->get('/pages/blog.html')->assertStatus(301)->assertRedirect('/blog');
    }

    public function test_feed_liefert_rss(): void
    {
        $this->beitrag();

        $this->get('/blog/feed')
            ->assertOk()
            // application/xml statt application/rss+xml ist Absicht, sonst
            // wendet Chrome das XSL-Stylesheet nicht an – siehe FeedTest.
            ->assertHeader('Content-Type', 'application/xml; charset=UTF-8')
            ->assertSee('<rss', false)
            ->assertSee('Ein Beitrag über Barrierefreiheit', false);
    }

    public function test_kurzwege_ins_ticketsystem_leiten_um(): void
    {
        $this->get('/kunde')->assertStatus(301)->assertRedirect('https://intern.nils-digital.de/kunde');
        $this->get('/intern')->assertStatus(301)->assertRedirect('https://intern.nils-digital.de');
    }
}

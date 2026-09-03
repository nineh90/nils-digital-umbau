<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Post;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * RSS-Feed und seine Browseransicht.
 *
 * Der Feed selbst ist für Maschinen. Damit er im Browser nicht wie ein Fehler
 * aussieht, hängt ein XSL-Stylesheet daran – und dessen Wirkung hängt an
 * Kleinigkeiten, die man beim Aufräumen versehentlich zurückdreht.
 */
class FeedTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        Post::create([
            'category_id' => Category::create(['slug' => 'projekte', 'name' => 'Projekte'])->id,
            'slug' => 'ein-beitrag',
            'title' => 'Ein Beitrag',
            'teaser' => 'Die Kurzfassung.',
            'content' => 'Der ganze Text, der nicht in den Feed gehört.',
            'status' => 'published',
            'published_at' => now()->subDay(),
        ]);
    }

    public function test_der_feed_ist_gueltiges_xml_mit_dem_beitrag(): void
    {
        $antwort = $this->get('/blog/feed')->assertOk();

        $xml = simplexml_load_string($antwort->getContent());

        $this->assertNotFalse($xml, 'Der Feed ist kein wohlgeformtes XML.');
        $this->assertSame('Ein Beitrag', (string) $xml->channel->item[0]->title);
        $this->assertSame('Die Kurzfassung.', (string) $xml->channel->item[0]->description);
    }

    /*
     * Nur der Teaser, nie der ganze Text: bei Volltext lesen die Leute im
     * Reader und kommen nie auf die Seite.
     */
    public function test_der_feed_traegt_nur_den_teaser(): void
    {
        $this->get('/blog/feed')
            ->assertSee('Die Kurzfassung.')
            ->assertDontSee('Der ganze Text, der nicht in den Feed gehört.');
    }

    /*
     * Der Fall, der uns eine halbe Stunde gekostet hat: Chrome wendet das
     * Stylesheet auf application/rss+xml NICHT an und zeigt weiter Quelltext.
     * Wer den Typ hier "korrigiert", macht die Browseransicht kaputt, ohne
     * dass irgendwo ein Fehler auftaucht.
     */
    public function test_der_feed_wird_als_application_xml_ausgeliefert(): void
    {
        $this->get('/blog/feed')
            ->assertHeader('Content-Type', 'application/xml; charset=UTF-8');
    }

    public function test_der_feed_verweist_auf_das_stylesheet(): void
    {
        $this->get('/blog/feed')
            ->assertSee('<?xml-stylesheet type="text/xsl"', false)
            ->assertSee(route('blog.feed.stylesheet'), false);
    }

    /*
     * Browser wenden ein Stylesheet nur an, wenn es als XML-Typ ausgeliefert
     * wird – deshalb eine Route statt einer Datei unter public/, wo die
     * Kopfzeile an Apaches MIME-Tabelle hinge.
     */
    public function test_das_stylesheet_kommt_als_xsl(): void
    {
        $this->get('/blog/feed.xsl')
            ->assertOk()
            ->assertHeader('Content-Type', 'text/xsl; charset=UTF-8')
            ->assertSee('xsl:stylesheet', false)
            ->assertSee('Das hier ist ein RSS-Feed');
    }

    public function test_jede_seite_verweist_auf_den_feed(): void
    {
        $this->get('/blog')
            ->assertSee('rel="alternate" type="application/rss+xml"', false);
    }
}

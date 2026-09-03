<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Post;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * Die Redaktion trägt deutsche Uhrzeiten ein.
 *
 * Der Fall, der das ausgelöst hat: Ein Beitrag wurde um 23:43 auf
 * "veröffentlicht" gestellt und blieb unsichtbar. Die Anwendung lief auf UTC,
 * dort war es erst 21:43 – aus der Veröffentlichung war unbemerkt eine
 * Terminierung zwei Stunden in der Zukunft geworden. Nirgends stand ein
 * Fehler, die Seite gab schlicht 404.
 */
class ZeitzoneTest extends TestCase
{
    use RefreshDatabase;

    public function test_die_anwendung_rechnet_in_deutscher_zeit(): void
    {
        $this->assertSame('Europe/Berlin', config('app.timezone'));
        $this->assertSame('Europe/Berlin', now()->timezone->getName());
    }

    /*
     * Der eigentliche Test: was die Redaktion als Uhrzeit eintippt, meint sie
     * in ihrer eigenen Zeit. Ein Datum wenige Minuten in der Vergangenheit muss
     * den Beitrag sichtbar machen – nicht erst zwei Stunden später.
     */
    public function test_eben_veroeffentlicht_heisst_sofort_sichtbar(): void
    {
        $beitrag = Post::create([
            'category_id' => Category::create(['slug' => 'projekte', 'name' => 'Projekte'])->id,
            'slug' => 'gerade-eben',
            'title' => 'Gerade eben',
            'teaser' => 'Kurzfassung.',
            'content' => 'Text.',
            'status' => 'published',
            'published_at' => now()->subMinutes(2),
        ]);

        $this->assertTrue(Post::published()->whereKey($beitrag->id)->exists());
        $this->get('/blog/gerade-eben')->assertOk();
        $this->get('/blog')->assertSee('Gerade eben');
    }

    public function test_ein_termin_in_der_zukunft_bleibt_verborgen(): void
    {
        Post::create([
            'category_id' => Category::create(['slug' => 'projekte', 'name' => 'Projekte'])->id,
            'slug' => 'spaeter',
            'title' => 'Später',
            'teaser' => 'Kurzfassung.',
            'content' => 'Text.',
            'status' => 'published',
            'published_at' => now()->addHour(),
        ]);

        $this->get('/blog/spaeter')->assertNotFound();
    }
}

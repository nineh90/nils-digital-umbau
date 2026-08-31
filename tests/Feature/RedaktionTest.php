<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Post;
use App\Models\Project;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * Rauchtest für die Redaktionsoberfläche.
 *
 * Filament-Seiten brechen gern erst beim Rendern – ein Tippfehler in einer
 * Feldkonfiguration fällt beim Aufruf von artisan nicht auf. Diese Tests rufen
 * die Seiten deshalb wirklich ab.
 */
class RedaktionTest extends TestCase
{
    use RefreshDatabase;

    private function angemeldet(): User
    {
        return User::create([
            'name' => 'Test',
            'email' => 'test@nils-digital.de',
            'password' => bcrypt('geheim'),
        ]);
    }

    public function test_ohne_anmeldung_fuehrt_die_redaktion_zur_anmeldeseite(): void
    {
        $this->get('/admin/posts')->assertRedirect('/admin/login');
    }

    public function test_beitragsuebersicht_rendert(): void
    {
        $kategorie = Category::create(['slug' => 'projekte', 'name' => 'Projekte']);

        Post::create([
            'legacy_id' => 47,
            'category_id' => $kategorie->id,
            'slug' => 'ein-beitrag',
            'title' => 'Ein Beitrag',
            'teaser' => 'Kurzer Anrisstext.',
            'content' => '## Überschrift',
            'status' => 'published',
            'published_at' => now(),
        ]);

        $this->actingAs($this->angemeldet())
            ->get('/admin/posts')
            ->assertOk()
            ->assertSee('Ein Beitrag');
    }

    public function test_beitragsformular_rendert(): void
    {
        Category::create(['slug' => 'projekte', 'name' => 'Projekte']);

        $this->actingAs($this->angemeldet())
            ->get('/admin/posts/create')
            ->assertOk();
    }

    public function test_projektuebersicht_rendert(): void
    {
        Project::create([
            'slug' => 'fahrlehrerin-sarah',
            'title' => 'Fahrlehrerin Sarah',
            'description' => 'Eine Website.',
        ]);

        $this->actingAs($this->angemeldet())
            ->get('/admin/projects')
            ->assertOk()
            ->assertSee('Fahrlehrerin Sarah');
    }
}

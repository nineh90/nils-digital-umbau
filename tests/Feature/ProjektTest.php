<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Post;
use App\Models\Project;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProjektTest extends TestCase
{
    use RefreshDatabase;

    private function projekt(array $werte = []): Project
    {
        return Project::create(array_merge([
            'slug' => 'fahrlehrerin-sarah',
            'title' => 'Fahrlehrerin Sarah',
            'type' => 'Kundenprojekt',
            'status' => 'live',
            'description' => 'Website für die Führerschein-Ausbildung von Menschen mit Handicap.',
            'link' => 'https://fahrlehrerinsarah.de/',
            'tags' => ['Webentwicklung', 'Barrierefreiheit'],
        ], $werte));
    }

    public function test_uebersicht_zeigt_projekte(): void
    {
        $this->projekt();

        $this->get('/projekte')
            ->assertOk()
            ->assertSee('Fahrlehrerin Sarah')
            ->assertSee('Barrierefreiheit');
    }

    public function test_detailseite_zeigt_projekt_und_seo(): void
    {
        $projekt = $this->projekt();
        $html = $this->get(route('projekte.show', $projekt))->assertOk()->getContent();

        $this->assertStringContainsString('<title>Fahrlehrerin Sarah – ', $html);
        $this->assertStringContainsString('rel="canonical" href="'.route('projekte.show', $projekt).'"', $html);
        $this->assertStringContainsString('"@type":"CreativeWork"', $html);
        $this->assertStringContainsString('"@type":"BreadcrumbList"', $html);
        $this->assertStringContainsString('https://fahrlehrerinsarah.de/', $html);
    }

    /**
     * Ohne Fallstudie ist die Seite dünn. Dünne Seiten schaden in der Suche,
     * deshalb bleiben sie erreichbar, aber unindexiert.
     */
    public function test_projekt_ohne_fallstudie_wird_nicht_indexiert(): void
    {
        $projekt = $this->projekt();

        $this->get(route('projekte.show', $projekt))
            ->assertOk()
            ->assertSee('noindex', false);
    }

    public function test_projekt_mit_fallstudie_wird_indexiert(): void
    {
        $projekt = $this->projekt(['body' => "## Ausgangslage\nEs war einmal."]);

        $antwort = $this->get(route('projekte.show', $projekt))->assertOk();

        $antwort->assertDontSee('noindex');
        $this->assertStringContainsString('<h2>Ausgangslage</h2>', $antwort->getContent());
    }

    public function test_verknuepfte_beitraege_erscheinen_am_projekt(): void
    {
        $projekt = $this->projekt();
        $kategorie = Category::create(['slug' => 'projekte', 'name' => 'Projekte']);

        $beitrag = Post::create([
            'legacy_id' => 47,
            'category_id' => $kategorie->id,
            'slug' => 'ein-beitrag',
            'title' => 'Ein Beitrag über Sarah',
            'teaser' => 'Anriss.',
            'content' => 'Text.',
            'status' => 'published',
            'published_at' => now()->subDay(),
        ]);

        $projekt->posts()->attach($beitrag);

        $this->get(route('projekte.show', $projekt))
            ->assertOk()
            ->assertSee('Aus dem Blog')
            ->assertSee('Ein Beitrag über Sarah', false);
    }

    public function test_alte_projektadresse_leitet_dauerhaft_um(): void
    {
        $this->get('/pages/projekte.html')->assertStatus(301)->assertRedirect('/projekte');
    }

    public function test_leistungen_lassen_sich_in_der_redaktion_pflegen(): void
    {
        $nutzer = User::create([
            'name' => 'Test',
            'email' => 'test@nils-digital.de',
            'password' => bcrypt('geheim'),
        ]);

        $this->actingAs($nutzer)->get('/admin/services')->assertOk();
        $this->actingAs($nutzer)->get('/admin/services/create')->assertOk();
        $this->actingAs($nutzer)->get('/admin/service-categories')->assertOk();
    }
}

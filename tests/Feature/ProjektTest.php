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

    public function test_fruehere_projekte_stehen_gesondert_und_ohne_link(): void
    {
        $this->projekt();
        $frueher = $this->projekt([
            'slug' => 'altes-projekt',
            'title' => 'Altes Projekt',
            'status' => Project::FRUEHER,
            'link' => 'https://alte-seite.example/',
        ]);

        $html = $this->get('/projekte')->assertOk()->getContent();

        $this->assertStringContainsString('Frühere Projekte', $html);
        // Aktuelle stehen vor dem Abschnitt, frühere dahinter.
        $abschnitt = strpos($html, 'id="fruehere-projekte"');
        $this->assertLessThan($abschnitt, strrpos($html, 'Fahrlehrerin Sarah'));
        $this->assertGreaterThan($abschnitt, strrpos($html, 'Altes Projekt'));

        $this->get(route('projekte.show', $frueher))
            ->assertOk()
            ->assertSee('Früheres Projekt')
            ->assertDontSee('alte-seite.example')
            ->assertDontSee('Projekt ansehen');
    }

    public function test_ohne_fruehere_projekte_kein_leerer_abschnitt(): void
    {
        $this->projekt();

        $this->get('/projekte')->assertOk()->assertDontSee('Frühere Projekte');
    }

    /** Auch mit stehengebliebenem Schalter: nie auf die Startseite. */
    public function test_fruehere_projekte_nie_auf_der_startseite(): void
    {
        $this->projekt(['is_featured' => true, 'image' => 'assets/a.jpg']);
        $this->projekt([
            'slug' => 'altes-projekt',
            'title' => 'Altes Projekt',
            'status' => Project::FRUEHER,
            'is_featured' => true,
            'image' => 'assets/b.jpg',
        ]);
        $this->projekt(['slug' => 'ohne-stand', 'title' => 'Ohne Stand', 'status' => null, 'is_featured' => true]);

        $this->assertEqualsCanonicalizing(
            ['fahrlehrerin-sarah', 'ohne-stand'],
            Project::featured()->pluck('slug')->all(),
        );
        $this->get('/')->assertOk()->assertDontSee('Altes Projekt');
    }

    public function test_beitrag_verliert_knopf_zur_seite_eines_frueheren_projekts(): void
    {
        $projekt = $this->projekt([
            'status' => Project::FRUEHER,
            'link' => 'https://www.alte-seite.example',
        ]);
        $kategorie = Category::create(['slug' => 'projekte', 'name' => 'Projekte']);
        $beitrag = Post::create([
            'category_id' => $kategorie->id,
            'slug' => 'ueber-die-alte-seite',
            'title' => 'Über die alte Seite',
            'teaser' => 'Anriss.',
            'content' => 'Text.',
            'status' => 'published',
            'published_at' => now()->subDay(),
        ]);
        $beitrag->links()->create(['url' => 'https://alte-seite.example/de', 'label' => 'Zur alten Seite', 'position' => 1]);
        $beitrag->links()->create(['url' => 'https://anderswo.example', 'label' => 'Woanders hin', 'position' => 2]);
        $projekt->posts()->attach($beitrag);

        $this->get(route('blog.show', $beitrag))
            ->assertOk()
            ->assertDontSee('Zur alten Seite')
            ->assertSee('Woanders hin');

        // Solange das Projekt aktuell ist, bleibt der Knopf.
        $projekt->update(['status' => 'live']);
        $this->get(route('blog.show', $beitrag))->assertSee('Zur alten Seite');
    }

    /** Beim Speichern als früheres Projekt geht der Startseiten-Schalter aus. */
    public function test_redaktion_schaltet_startseite_bei_frueherem_projekt_aus(): void
    {
        $projekt = $this->projekt(['is_featured' => true]);
        $this->actingAs(User::create([
            'name' => 'Test',
            'email' => 'test@nils-digital.de',
            'password' => bcrypt('geheim'),
        ]));

        $this->get("/admin/projects/{$projekt->getRouteKey()}/edit")->assertOk();

        \Livewire\Livewire::test(\App\Filament\Resources\Projects\Pages\EditProject::class, ['record' => $projekt->getRouteKey()])
            ->fillForm(['status' => Project::FRUEHER])
            ->call('save')
            ->assertHasNoFormErrors();

        $projekt->refresh();
        $this->assertSame(Project::FRUEHER, $projekt->status);
        $this->assertFalse($projekt->is_featured);
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

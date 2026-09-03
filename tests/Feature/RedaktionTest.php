<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Post;
use App\Models\Project;
use App\Models\Review;
use App\Models\Service;
use App\Models\ServiceCategory;
use App\Models\TeamMember;
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

    public function test_teamuebersicht_rendert(): void
    {
        $this->actingAs($this->angemeldet())
            ->get('/admin/team-members')
            ->assertOk()
            ->assertSee('Nils Nehring');
    }

    public function test_teamformular_rendert(): void
    {
        $person = TeamMember::where('name', 'Nils Nehring')->firstOrFail();

        $this->actingAs($this->angemeldet())
            ->get("/admin/team-members/{$person->id}/edit")
            ->assertOk()
            ->assertSee('Vorstellungstext');
    }

    /*
     * Das Foto liegt als Pfad im Datensatz, nicht als Upload der Redaktion –
     * Nils' Bild kommt noch aus public/assets. Beim Speichern ueber das
     * Formular darf es nicht verloren gehen, nur weil das Upload-Feld es nicht
     * selbst hochgeladen hat.
     */
    public function test_speichern_behaelt_ein_vorhandenes_foto(): void
    {
        $person = TeamMember::where('name', 'Nils Nehring')->firstOrFail();
        $bild = $person->image;

        $this->assertNotNull($bild);

        $this->actingAs($this->angemeldet());

        \Livewire\Livewire::test(
            \App\Filament\Resources\TeamMembers\Pages\EditTeamMember::class,
            ['record' => $person->id]
        )
            ->fillForm(['role' => 'Gründer & Entwickler'])
            ->call('save')
            ->assertHasNoFormErrors();

        $person->refresh();

        $this->assertSame('Gründer & Entwickler', $person->role);
        $this->assertSame($bild, $person->image);
    }

    /*
     * Das Symbolfeld ist eine Auswahl aus App\Support\Symbole. Verschwindet
     * dort ein Name oder bricht die Klasse, faellt das sonst erst auf, wenn
     * jemand eine Leistung bearbeiten will.
     */
    public function test_leistungsformular_bietet_die_symbole_zur_auswahl(): void
    {
        $gruppe = ServiceCategory::create(['slug' => 'web', 'name' => 'Web', 'position' => 1]);

        $leistung = Service::create([
            'service_category_id' => $gruppe->id,
            'slug' => 'eine-leistung',
            'name' => 'Eine Leistung',
            'description' => 'Beschreibung.',
            'icon' => 'blitz',
            'position' => 1,
        ]);

        $this->assertArrayHasKey('blitz', \App\Support\Symbole::auswahl());

        $this->actingAs($this->angemeldet())
            ->get("/admin/services/{$leistung->id}/edit")
            ->assertOk()
            ->assertSee('Symbol');
    }

    /*
     * Der Fall, der Nils gemeldet hat: ProjectForm bot image_fit als
     * Datei-Upload an und dazu als Pflichtfeld – in einer Spalte, die "cover"
     * oder "contain" enthält. Beim Speichern verlangte das Formular deshalb
     * ein Bild, das es dort gar nicht geben kann.
     */
    public function test_bestehendes_projekt_laesst_sich_ohne_neues_bild_speichern(): void
    {
        $projekt = Project::create([
            'slug' => 'ein-projekt',
            'title' => 'Ein Projekt',
            'description' => 'Beschreibung.',
            'image' => 'assets/images/projekte/lerndex.png',
            'image_fit' => 'cover',
            'position' => 1,
        ]);

        $this->actingAs($this->angemeldet());

        \Livewire\Livewire::test(
            \App\Filament\Resources\Projects\Pages\EditProject::class,
            // Project löst über den Slug auf, nicht über die ID.
            ['record' => $projekt->slug]
        )
            ->fillForm(['title' => 'Anderer Titel'])
            ->call('save')
            ->assertHasNoFormErrors();

        $projekt->refresh();

        $this->assertSame('Anderer Titel', $projekt->title);
        $this->assertSame('assets/images/projekte/lerndex.png', $projekt->image);
    }

    /*
     * Bewertungen ohne Text sind der Normalfall – bei Google vergibt man oft
     * nur Sterne. Das Formular verlangte den Text und die Spalte stand auf
     * NOT NULL; eine reine Sternebewertung liess sich gar nicht anlegen.
     */
    public function test_kundenstimme_ohne_text_laesst_sich_anlegen(): void
    {
        $this->actingAs($this->angemeldet());

        \Livewire\Livewire::test(\App\Filament\Resources\Reviews\Pages\CreateReview::class)
            ->fillForm(['name' => 'Nur Sterne', 'rating' => 5, 'position' => 1])
            ->call('create')
            ->assertHasNoFormErrors();

        $stimme = Review::where('name', 'Nur Sterne')->first();

        $this->assertNotNull($stimme);
        $this->assertNull($stimme->text);

        // Zählt für den Schnitt, erscheint aber nicht als Zitat.
        $this->assertTrue(Review::visible()->get()->contains($stimme));
        $this->assertFalse(Review::vorzeigbar()->get()->contains($stimme));
    }

    /** Jede Liste und jedes Formular muss sich überhaupt aufbauen lassen. */
    public function test_alle_uebersichten_rendern(): void
    {
        $this->actingAs($this->angemeldet());

        foreach (['posts', 'projects', 'reviews', 'categories', 'services', 'service-categories', 'team-members'] as $pfad) {
            $this->get("/admin/{$pfad}")->assertOk();
            $this->get("/admin/{$pfad}/create")->assertOk();
        }
    }
}

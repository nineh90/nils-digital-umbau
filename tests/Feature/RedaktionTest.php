<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Post;
use App\Models\Project;
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
}

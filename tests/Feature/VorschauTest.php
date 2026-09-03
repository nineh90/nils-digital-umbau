<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Post;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * Vorschau auf Entwürfe aus der Redaktion.
 *
 * Der heikle Teil ist nicht, dass sie funktioniert, sondern dass sie nichts
 * aufmacht: ein Entwurf darf ohne Anmeldung nirgends erreichbar sein, auch
 * nicht über die Vorschau-Adresse.
 */
class VorschauTest extends TestCase
{
    use RefreshDatabase;

    private function entwurf(array $werte = []): Post
    {
        return Post::create(array_merge([
            'category_id' => Category::create(['slug' => 'projekte', 'name' => 'Projekte'])->id,
            'slug' => 'ein-entwurf',
            'title' => 'Ein Entwurf',
            'teaser' => 'Kurzfassung.',
            'content' => 'Der geheime Text des Entwurfs.',
            'status' => 'draft',
            'published_at' => null,
        ], $werte));
    }

    private function angemeldet(): User
    {
        return User::create([
            'name' => 'Nils',
            'email' => 'test@nils-digital.de',
            'password' => bcrypt('geheim'),
        ]);
    }

    public function test_ohne_anmeldung_ist_die_vorschau_verschlossen(): void
    {
        $entwurf = $this->entwurf();

        $this->get(route('blog.vorschau', $entwurf))->assertRedirect('/admin/login');
        $this->get('/blog/ein-entwurf')->assertNotFound();
        $this->get('/blog')->assertDontSee('Ein Entwurf');
    }

    public function test_angemeldet_zeigt_die_vorschau_den_entwurf(): void
    {
        $entwurf = $this->entwurf();

        $this->actingAs($this->angemeldet())
            ->get(route('blog.vorschau', $entwurf))
            ->assertOk()
            ->assertSee('Der geheime Text des Entwurfs.')
            ->assertSee('Vorschau')
            ->assertSee('Entwurf – öffentlich noch nicht erreichbar.', false);
    }

    /*
     * Ein weitergegebener Vorschau-Link soll nicht im Index landen – deshalb
     * traegt die Seite noindex, obwohl sie ohnehin hinter der Anmeldung liegt.
     */
    public function test_die_vorschau_traegt_noindex(): void
    {
        $entwurf = $this->entwurf();

        $this->actingAs($this->angemeldet())
            ->get(route('blog.vorschau', $entwurf))
            ->assertSee('noindex, nofollow', false);
    }

    public function test_ein_geplanter_beitrag_nennt_sein_datum(): void
    {
        $geplant = $this->entwurf([
            'status' => 'published',
            'published_at' => now()->addWeek(),
        ]);

        $this->actingAs($this->angemeldet())
            ->get(route('blog.vorschau', $geplant))
            ->assertOk()
            ->assertSee('Geplant für '.now()->addWeek()->translatedFormat('d. F Y'), false);

        // Öffentlich bleibt er bis dahin unerreichbar.
        $this->get('/blog/ein-entwurf')->assertNotFound();
    }

    public function test_die_oeffentliche_seite_traegt_kein_noindex(): void
    {
        $offen = $this->entwurf(['status' => 'published', 'published_at' => now()->subDay()]);

        $this->get(route('blog.show', $offen))
            ->assertOk()
            ->assertDontSee('noindex', false)
            ->assertDontSee('Vorschau');
    }
}

<?php

namespace Tests\Feature;

use App\Models\TeamMember;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TeamTest extends TestCase
{
    use RefreshDatabase;

    /*
     * Die beiden Personen legt die Migration an, nicht der Seeder – auf dem
     * Server laeuft ausschliesslich migrate. Geht das verloren, steht die
     * Teamseite nach dem Deploy leer da, und zwar ohne Fehler.
     */
    public function test_migration_bringt_die_bestehenden_personen_mit(): void
    {
        $this->assertSame(2, TeamMember::count());
        $this->assertNotNull(TeamMember::where('name', 'Nils Nehring')->first());
    }

    public function test_seite_zeigt_die_personen_aus_der_datenbank(): void
    {
        TeamMember::query()->delete();

        TeamMember::create([
            'name' => 'Neue Person',
            'role' => 'Testrolle',
            'bio' => 'Ein Vorstellungstext.',
            'skills' => ['Schlagwort A', 'Schlagwort B'],
            'highlight_label' => 'Arbeitsweise',
            'highlight_text' => 'Ein hervorgehobener Satz.',
            'position' => 1,
        ]);

        $this->get('/team')
            ->assertOk()
            ->assertSee('Neue Person')
            ->assertSee('Testrolle')
            ->assertSee('Schlagwort A')
            ->assertSee('Ein hervorgehobener Satz.');
    }

    public function test_unsichtbare_person_erscheint_nicht(): void
    {
        TeamMember::query()->delete();

        TeamMember::create([
            'name' => 'Versteckte Person',
            'role' => 'Rolle',
            'bio' => 'Text.',
            'is_visible' => false,
        ]);

        $this->get('/team')
            ->assertOk()
            ->assertDontSee('Versteckte Person');
    }

    public function test_position_bestimmt_die_reihenfolge(): void
    {
        TeamMember::query()->delete();

        TeamMember::create(['name' => 'Zweite', 'role' => 'R', 'bio' => 'T', 'position' => 2]);
        TeamMember::create(['name' => 'Erste', 'role' => 'R', 'bio' => 'T', 'position' => 1]);

        $this->get('/team')
            ->assertOk()
            ->assertSeeInOrder(['Erste', 'Zweite']);
    }

    /*
     * Der Schema.org-Block wird aus derselben Sammlung gebaut wie die Karten.
     * Frueher stand das Team zweimal im Blade – die Auszeichnung lief deshalb
     * auseinander, sobald jemand nur an einer Stelle nachtrug.
     */
    public function test_person_steht_auch_in_der_auszeichnung(): void
    {
        TeamMember::query()->delete();

        TeamMember::create(['name' => 'Auszeichnung Test', 'role' => 'Entwicklerin', 'bio' => 'T']);

        $this->get('/team')
            ->assertOk()
            ->assertSee('"jobTitle":"Entwicklerin"', false);
    }

    /*
     * Kevins Verweis kommt aus der Migration, aus demselben Grund wie die
     * Personen selbst: auf dem Server laeuft kein Seeder.
     */
    public function test_migration_traegt_kevins_seite_ein(): void
    {
        $this->assertSame(
            'https://kevins-werkstatt.nils-digital.de',
            TeamMember::where('name', 'Kevin')->value('website_url'),
        );
    }

    public function test_eigene_seite_wird_verlinkt_und_ausgezeichnet(): void
    {
        TeamMember::query()->delete();

        TeamMember::create([
            'name' => 'Mit Seite',
            'role' => 'R',
            'bio' => 'T',
            'website_url' => 'https://beispiel.test',
            'website_label' => 'Meine Werkstatt',
        ]);

        $this->get('/team')
            ->assertOk()
            ->assertSee('href="https://beispiel.test"', false)
            ->assertSee('Meine Werkstatt')
            ->assertSee('"url":"https://beispiel.test"', false);
    }

    /** Ohne Adresse kein Verweis – auch kein leerer. */
    public function test_ohne_adresse_erscheint_kein_verweis(): void
    {
        TeamMember::query()->delete();

        TeamMember::create(['name' => 'Ohne Seite', 'role' => 'R', 'bio' => 'T']);

        $this->get('/team')
            ->assertOk()
            ->assertDontSee('Zur eigenen Seite')
            ->assertDontSee('"url":null', false);
    }

    /** Ohne Foto steht der Anfangsbuchstabe in der Kachel. */
    public function test_ohne_foto_erscheint_das_monogramm(): void
    {
        TeamMember::query()->delete();

        $person = TeamMember::create(['name' => 'Kevin', 'role' => 'R', 'bio' => 'T']);

        $this->assertSame('K', $person->monogramm());

        $this->get('/team')->assertOk();
    }
}

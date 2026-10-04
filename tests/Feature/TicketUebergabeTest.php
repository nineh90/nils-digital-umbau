<?php

namespace Tests\Feature;

use App\Jobs\AnfrageUebergeben;
use App\Models\Inquiry;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Client\RequestException;
use Illuminate\Http\Client\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Queue;
use Tests\TestCase;

/**
 * Anfragen werden Tickets.
 *
 * Abgesichert ist vor allem, was still schiefgehen würde: ein Formular, das
 * ohne Ticketsystem nicht mehr durchgeht, ein zweites Ticket für dieselbe
 * Anfrage, und ein Link, der auf den Namen des Containers zeigt.
 */
class TicketUebergabeTest extends TestCase
{
    use RefreshDatabase;

    private function einrichten(): void
    {
        config([
            'services.ticketsystem.url' => 'http://ticketsystem',
            'services.ticketsystem.token' => 'geheim',
            'services.ticketsystem.projekt' => 'anfragen',
            'services.ticketsystem.adresse' => 'https://intern.nils-digital.de',
        ]);
    }

    private function anfrage(): Inquiry
    {
        return Inquiry::create([
            'type' => 'kontakt',
            'name' => 'Erika Beispiel',
            'email' => 'erika@example.de',
            'subject' => 'Neue Website',
            'message' => 'Wir brauchen eine neue Seite.',
        ]);
    }

    private function antwort(): array
    {
        return ['ticket' => [
            'id' => 6,
            'kennung' => 'ANF-1',
            'url' => 'https://ticketsystem/tickets/anf-1-neue-website',
        ], 'neu' => true];
    }

    public function test_das_kontaktformular_stellt_die_uebergabe_in_die_warteschlange(): void
    {
        Mail::fake();
        Queue::fake();

        $this->post('/kontakt', [
            'name' => 'Erika Beispiel',
            'email' => 'erika@example.de',
            'subject' => 'Neue Website',
            'message' => 'Wir brauchen eine neue Seite, am liebsten bis Januar.',
        ])->assertRedirect('/kontakt');

        Queue::assertPushed(AnfrageUebergeben::class, fn ($job) => $job->anfrage->is(Inquiry::sole()));
    }

    public function test_ohne_eingerichtetes_ticketsystem_wird_nichts_aufgerufen(): void
    {
        Http::fake();

        AnfrageUebergeben::dispatchSync($anfrage = $this->anfrage());

        Http::assertNothingSent();
        $this->assertNull($anfrage->refresh()->handed_over_at);
    }

    public function test_die_anfrage_merkt_sich_ihr_ticket_mit_oeffentlicher_adresse(): void
    {
        $this->einrichten();
        Http::fake(['ticketsystem/*' => Http::response($this->antwort(), 201)]);

        AnfrageUebergeben::dispatchSync($anfrage = $this->anfrage());

        Http::assertSent(fn (Request $aufruf) => $aufruf->url() === 'http://ticketsystem/api/v1/tickets'
            && $aufruf->hasHeader('Authorization', 'Bearer geheim')
            && $aufruf['projekt'] === 'anfragen'
            && $aufruf['titel'] === 'Neue Website – Erika Beispiel'
            && $aufruf['absender_name'] === 'Erika Beispiel'
            && $aufruf['absender_email'] === 'erika@example.de'
            && $aufruf['external_ref'] === 'website-anfrage-'.$anfrage->id
            && str_contains($aufruf['beschreibung'], 'Wir brauchen eine neue Seite.'));

        $anfrage->refresh();

        $this->assertSame('ANF-1', $anfrage->ticket_ref);
        $this->assertSame('https://intern.nils-digital.de/tickets/anf-1-neue-website', $anfrage->ticket_url);
        $this->assertNotNull($anfrage->handed_over_at);
    }

    public function test_eine_uebergebene_anfrage_wird_nicht_noch_einmal_uebergeben(): void
    {
        $this->einrichten();
        Http::fake(['ticketsystem/*' => Http::response($this->antwort(), 201)]);

        $anfrage = $this->anfrage();

        AnfrageUebergeben::dispatchSync($anfrage);
        AnfrageUebergeben::dispatchSync($anfrage->refresh());

        Http::assertSentCount(1);
    }

    public function test_antwortet_das_ticketsystem_nicht_bleibt_die_anfrage_offen(): void
    {
        $this->einrichten();
        Http::fake(['ticketsystem/*' => Http::response(['fehler' => 'Kein gültiger Token.'], 401)]);

        $anfrage = $this->anfrage();

        try {
            AnfrageUebergeben::dispatchSync($anfrage);
            $this->fail('Ein Fehler muss den Auftrag scheitern lassen, sonst wird er nie wiederholt.');
        } catch (RequestException) {
        }

        $this->assertNull($anfrage->refresh()->handed_over_at);
    }
}

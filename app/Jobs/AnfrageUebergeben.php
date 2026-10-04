<?php

namespace App\Jobs;

use App\Filament\Resources\Inquiries\InquiryResource;
use App\Models\Inquiry;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

/**
 * Legt zu einer Anfrage ein Ticket im Ticketsystem an.
 *
 * Das Ticketsystem ist eine eigene Anwendung und kann stehen, während diese
 * Seite läuft. Deshalb über die Warteschlange und nie im Formularaufruf
 * selbst: die Anfrage ist zu dem Zeitpunkt längst gespeichert, die Mail an
 * Nils unterwegs – das Ticket ist der dritte Weg, nicht der einzige.
 *
 * Wiederholen ist gefahrlos. Die Schnittstelle drüben erkennt external_ref
 * wieder und gibt das bestehende Ticket zurück, statt ein zweites anzulegen.
 */
class AnfrageUebergeben implements ShouldQueue
{
    use Queueable;

    public int $tries = 3;

    /** Wachsende Pause: ein Neustart des Ticketsystems dauert eher Minuten als Sekunden. */
    public array $backoff = [60, 600];

    public function __construct(public Inquiry $anfrage) {}

    public function handle(): void
    {
        // Ohne Adresse und Token ist die Übergabe ausgeschaltet – lokal der
        // Normalfall, auf dem Server der Schalter zum Scharfstellen.
        if (! Inquiry::uebergabeEingerichtet() || $this->anfrage->handed_over_at !== null) {
            return;
        }

        $ticket = Http::withToken(config('services.ticketsystem.token'))
            ->acceptJson()
            ->timeout(10)
            ->post(rtrim(config('services.ticketsystem.url'), '/').'/api/v1/tickets', [
                'projekt' => config('services.ticketsystem.projekt'),
                'titel' => Str::limit($this->titel(), 250),
                'beschreibung' => $this->beschreibung(),
                'absender_name' => $this->anfrage->name,
                'absender_email' => $this->anfrage->email,
                'external_ref' => 'website-anfrage-'.$this->anfrage->id,
            ])
            ->throw()
            ->json('ticket');

        $this->anfrage->update([
            'ticket_ref' => $ticket['kennung'],
            'ticket_url' => $this->oeffentlicheAdresse($ticket['url']),
            'handed_over_at' => now(),
        ]);
    }

    private function titel(): string
    {
        return ($this->anfrage->subject ?: 'Anfrage über die Website').' – '.$this->anfrage->name;
    }

    /**
     * Ohne den Namen: den setzt das Ticketsystem aus absender_name und
     * absender_email selbst als erste Zeile davor.
     */
    private function beschreibung(): string
    {
        return implode("\n", [
            'Über: '.(Inquiry::HERKUNFT[$this->anfrage->type] ?? $this->anfrage->type),
            '',
            $this->anfrage->message,
            '',
            'In der Redaktion: '.InquiryResource::getUrl('edit', ['record' => $this->anfrage], panel: 'admin'),
        ]);
    }

    /**
     * Das Ticketsystem baut seine Adresse aus dem Aufruf. Der kommt hier über
     * das Docker-Netz (http://ticketsystem), und was dabei zurückkommt, lässt
     * sich in keinem Browser öffnen. Übernommen wird deshalb nur der Pfad.
     */
    private function oeffentlicheAdresse(string $adresse): string
    {
        return rtrim(config('services.ticketsystem.adresse'), '/').parse_url($adresse, PHP_URL_PATH);
    }
}

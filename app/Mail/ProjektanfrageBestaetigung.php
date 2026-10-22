<?php

namespace App\Mail;

use App\Models\Inquiry;
use App\Support\Fragebogen;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

/**
 * Bestätigung an die anfragende Person, mit Richtpreis und ihren Angaben.
 *
 * Nur die ausgewählten Antworten, nichts Selbstgeschriebenes und auch nicht
 * der Name – die Adresse ist ungeprüft, wie beim Kontaktformular. Siehe
 * KontaktBestaetigung und App\Support\Spamschutz.
 */
class ProjektanfrageBestaetigung extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public Inquiry $anfrage) {}

    public function envelope(): Envelope
    {
        return new Envelope(subject: 'Deine Projektanfrage bei Nils-Digital');
    }

    public function content(): Content
    {
        return new Content(markdown: 'mails.projektanfrage-bestaetigung', with: [
            'preisZeilen' => Fragebogen::preisZeilen($this->anfrage->details['preis']),
            // Ältere Anfragen kennen "frei" noch nicht; im Zweifel weglassen.
            'angaben' => array_filter($this->anfrage->details['zusammenfassung'], fn ($zeile) => ! ($zeile['frei'] ?? true)),
        ]);
    }
}

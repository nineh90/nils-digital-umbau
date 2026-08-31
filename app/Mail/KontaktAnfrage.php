<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

/** Benachrichtigung an Nils über eine neue Anfrage. */
class KontaktAnfrage extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public string $absenderName,
        public string $absenderMail,
        public string $betreff,
        public string $nachricht,
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: "Anfrage über die Website: {$this->betreff}",
            // Antworten geht direkt an den Kunden, nicht an die eigene Adresse.
            replyTo: [new Address($this->absenderMail, $this->absenderName)],
        );
    }

    public function content(): Content
    {
        return new Content(markdown: 'mails.kontakt-anfrage');
    }
}

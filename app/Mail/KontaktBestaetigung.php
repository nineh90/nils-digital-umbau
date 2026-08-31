<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

/** Automatische Eingangsbestätigung an den Absender. */
class KontaktBestaetigung extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public string $absenderName,
        public string $betreff,
        public string $nachricht,
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(subject: 'Deine Anfrage ist angekommen');
    }

    public function content(): Content
    {
        return new Content(markdown: 'mails.kontakt-bestaetigung');
    }
}

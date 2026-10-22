<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

/**
 * Automatische Eingangsbestätigung an den Absender.
 *
 * Bewusst ohne ein Wort aus dem Formular, auch ohne den Namen: die Adresse
 * ist ungeprüft, und eine Bestätigung, die Eingaben wiedergibt, trägt
 * beliebigen Text an beliebige Empfänger – genau dafür hat ein Roboter das
 * Formular im Oktober 2026 benutzt. Siehe App\Support\Spamschutz.
 */
class KontaktBestaetigung extends Mailable
{
    use Queueable, SerializesModels;

    public function envelope(): Envelope
    {
        return new Envelope(subject: 'Deine Anfrage ist angekommen');
    }

    public function content(): Content
    {
        return new Content(markdown: 'mails.kontakt-bestaetigung');
    }
}

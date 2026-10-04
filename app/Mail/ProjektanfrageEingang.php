<?php

namespace App\Mail;

use App\Models\Inquiry;
use App\Support\Fragebogen;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

/** Benachrichtigung an Nils: ein ausgefüllter Projektfragebogen ist da. */
class ProjektanfrageEingang extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public Inquiry $anfrage) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: $this->anfrage->subject.' – '.$this->anfrage->name,
            replyTo: [new Address($this->anfrage->email, $this->anfrage->name)],
        );
    }

    public function content(): Content
    {
        return new Content(markdown: 'mails.projektanfrage-eingang', with: [
            'preisZeilen' => Fragebogen::preisZeilen($this->anfrage->details['preis']),
        ]);
    }
}

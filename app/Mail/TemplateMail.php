<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Queue\SerializesModels;

class TemplateMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        private readonly string  $mailSubject,
        private readonly string  $bodyHtml,
        private readonly ?string $bodyText,
        private readonly string  $fromEmail,
        private readonly string  $fromName,
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            from: new Address($this->fromEmail, $this->fromName),
            subject: $this->mailSubject,
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.template-body',
            text: 'emails.template-body-text',
            with: [
                'bodyHtml' => $this->bodyHtml,
                'bodyText' => $this->bodyText,
            ],
        );
    }
}

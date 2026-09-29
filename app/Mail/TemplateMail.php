<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Symfony\Component\Mime\Email;
use Symfony\Component\Mime\Part\DataPart;

class TemplateMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        private readonly string  $mailSubject,
        private readonly string  $bodyHtml,
        private readonly ?string $bodyText,
        private readonly string  $fromEmail,
        private readonly string  $fromName,
        private readonly ?string $bannerBytes = null,
    ) {}

    public function build(): static
    {
        $this->from($this->fromEmail, $this->fromName)
             ->subject($this->mailSubject)
             ->html($this->bodyHtml);

        $bodyText    = $this->bodyText;
        $bannerBytes = $this->bannerBytes;

        $this->withSymfonyMessage(function (Email $message) use ($bodyText, $bannerBytes) {
            if ($bodyText) {
                $message->text($bodyText);
            }

            if ($bannerBytes !== null) {
                $part = (new DataPart($bannerBytes, 'banner.png', 'image/png'))->asInline();
                $cid  = $part->getContentId();
                $message->addPart($part);

                $html = $message->getHtmlBody() ?? '';
                $html = str_replace('cid:pivot_banner', 'cid:' . $cid, $html);
                $message->html($html);
            }
        });

        return $this;
    }
}

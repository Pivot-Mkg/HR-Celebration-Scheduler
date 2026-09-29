<?php

namespace App\Providers;

use Illuminate\Support\Facades\Mail;
use Illuminate\Support\ServiceProvider;
use Symfony\Component\Mailer\Transport\Dsn;
use Symfony\Component\Mailer\Transport\Smtp\EsmtpTransportFactory;
use Symfony\Component\Mailer\Transport\Smtp\Stream\SocketStream;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void {}

    public function boot(): void
    {
        // Apply stream SSL options from config/mail.php smtp.stream key.
        // Laravel's MailManager ignores the 'stream' key — we apply it here.
        $streamOptions = config('mail.mailers.smtp.stream');
        if (empty($streamOptions)) {
            return;
        }

        Mail::extend('smtp', function (array $config) use ($streamOptions) {
            $factory = new EsmtpTransportFactory();

            $scheme = $config['scheme'] ?? null;
            if (! $scheme) {
                $scheme = ($config['port'] == 465) ? 'smtps' : 'smtp';
            }

            $transport = $factory->create(new Dsn(
                $scheme,
                $config['host'],
                $config['username'] ?? null,
                $config['password'] ?? null,
                $config['port'] ?? null,
                $config
            ));

            $stream = $transport->getStream();
            if ($stream instanceof SocketStream) {
                $stream->setStreamOptions($streamOptions);
            }

            return $transport;
        });
    }
}

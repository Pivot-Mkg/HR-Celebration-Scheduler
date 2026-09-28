<?php

namespace App\Mail;

use App\Models\Employee;
use App\Services\AnniversaryService;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Queue\SerializesModels;

class AnniversaryMail extends Mailable
{
    use Queueable, SerializesModels;

    public readonly int $completedYears;

    public function __construct(
        public readonly Employee $employee,
        public readonly array $config = []
    ) {
        $this->completedYears = (new AnniversaryService())->getCompletedYears($employee);
    }

    public function envelope(): Envelope
    {
        $from = $this->config['from_address'] ?? config('mail.from.address');
        $fromName = $this->config['from_name'] ?? config('mail.from.name');

        return new Envelope(
            from: new Address($from, $fromName),
            subject: "Happy Work Anniversary, {$this->employee->employee_name}! 🎉",
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.anniversary',
            with: [
                'employee'      => $this->employee,
                'completedYears' => $this->completedYears,
                'companyName'   => config('app.name'),
                'currentDate'   => Carbon::today()->format('F j, Y'),
            ]
        );
    }
}

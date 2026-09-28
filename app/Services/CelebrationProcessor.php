<?php

namespace App\Services;

use App\Mail\TemplateMail;
use App\Models\EmailLog;
use App\Models\EmailTemplate;
use App\Models\Employee;
use App\Models\Setting;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Throwable;

class CelebrationProcessor
{
    public function __construct(
        private BirthdayService $birthdayService,
        private AnniversaryService $anniversaryService,
        private TemplateRenderer $renderer,
        private EmailService $emailService,
    ) {}

    /**
     * Process all celebrations for a given date.
     * Returns summary array.
     */
    public function process(?Carbon $date = null, bool $dryRun = false): array
    {
        $date = $date ?? Carbon::now();
        $dateStr = $date->toDateString();
        $config = Setting::getMailConfig();

        $birthdays     = $this->birthdayService->getTodaysBirthdays($date);
        $anniversaries = $this->anniversaryService->getTodaysAnniversaries($date);

        $results = [
            'date'          => $dateStr,
            'dry_run'       => $dryRun,
            'birthdays'     => $birthdays->count(),
            'anniversaries' => $anniversaries->count(),
            'sent'          => 0,
            'failed'        => 0,
            'skipped'       => 0,
            'would_send'    => 0,
            'details'       => [],
        ];

        foreach ($birthdays as $employee) {
            $result = $this->processEvent($employee, 'birthday', $date, $config, $dryRun);
            $results['details'][] = $result;
            $results[$result['status']]++;
        }

        foreach ($anniversaries as $employee) {
            $result = $this->processEvent($employee, 'anniversary', $date, $config, $dryRun);
            $results['details'][] = $result;
            $results[$result['status']]++;
        }

        return $results;
    }

    private function processEvent(Employee $employee, string $eventType, Carbon $date, array $config, bool $dryRun): array
    {
        $dateStr = $date->toDateString();

        // Duplicate protection
        if (EmailLog::alreadySent($employee->id, $eventType, $dateStr)) {
            $this->logSkipped($employee, $eventType, $dateStr, $config);
            return ['employee' => $employee->employee_name, 'event' => $eventType, 'status' => 'skipped', 'reason' => 'Already processed'];
        }

        $template = EmailTemplate::getDefault($eventType);
        if (!$template) {
            $this->writeLog($employee, null, $eventType, $dateStr, $config, 'skipped', 'No active default template found');
            return ['employee' => $employee->employee_name, 'event' => $eventType, 'status' => 'skipped', 'reason' => 'No template'];
        }

        $rendered = $this->renderer->renderTemplate($template, $employee, $eventType, $date);

        if ($dryRun) {
            return [
                'employee' => $employee->employee_name,
                'event'    => $eventType,
                'status'   => 'would_send',
                'to'       => $employee->email,
                'cc'       => $config['cc'] ?? '',
                'subject'  => $rendered['subject'],
                'template' => $template->name,
            ];
        }

        try {
            $fromEmail = $template->from_email ?: ($config['from_address'] ?? config('mail.from.address'));
            $fromName  = $template->from_name  ?: ($config['from_name']    ?? config('mail.from.name'));
            $cc        = $template->cc_addresses  ?: ($config['cc'] ?? '');
            $bcc       = $template->bcc_addresses ?: ($config['bcc'] ?? '');

            $mailable = new TemplateMail(
                mailSubject: $rendered['subject'],
                bodyHtml:    $rendered['body_html'],
                bodyText:    $rendered['body_text'],
                fromEmail:   $fromEmail,
                fromName:    $fromName,
            );

            $mailSend = Mail::to($employee->email);
            $ccList   = $this->emailService->parseAddresses($cc);
            $bccList  = $this->emailService->parseAddresses($bcc);
            if ($ccList)  $mailSend->cc($ccList);
            if ($bccList) $mailSend->bcc($bccList);
            $mailSend->send($mailable);

            $this->writeLog($employee, $template->id, $eventType, $dateStr, $config, 'sent', null,
                $rendered['subject'], $fromEmail, $fromName,
                $employee->email, implode(',', $ccList), implode(',', $bccList)
            );

            return ['employee' => $employee->employee_name, 'event' => $eventType, 'status' => 'sent'];
        } catch (Throwable $e) {
            Log::error("Celebration email failed [{$eventType}] [{$employee->employee_code}]: " . $e->getMessage());
            $this->writeLog($employee, $template->id, $eventType, $dateStr, $config, 'failed', $e->getMessage());
            return ['employee' => $employee->employee_name, 'event' => $eventType, 'status' => 'failed', 'error' => $e->getMessage()];
        }
    }

    private function logSkipped(Employee $employee, string $eventType, string $dateStr, array $config): void
    {
        // Only insert if no log exists yet for this entry
        $exists = EmailLog::where('employee_id', $employee->id)
                          ->where('event_type', $eventType)
                          ->whereDate('event_date', $dateStr)
                          ->exists();
        if (!$exists) {
            $this->writeLog($employee, null, $eventType, $dateStr, $config, 'skipped', 'Duplicate run');
        }
    }

    private function writeLog(
        Employee $employee, ?int $templateId, string $eventType, string $dateStr,
        array $config, string $status, ?string $error = null,
        ?string $subject = null, ?string $from = null, ?string $fromName = null,
        ?string $to = null, ?string $cc = null, ?string $bcc = null
    ): void {
        try {
            EmailLog::updateOrCreate(
                ['employee_id' => $employee->id, 'event_type' => $eventType, 'event_date' => $dateStr],
                [
                    'template_id'  => $templateId,
                    'from_email'   => $from   ?? ($config['from_address'] ?? ''),
                    'from_name'    => $fromName ?? ($config['from_name'] ?? ''),
                    'to_email'     => $to ?? $employee->email,
                    'cc_email'     => $cc,
                    'bcc_email'    => $bcc,
                    'subject'      => $subject,
                    'status'       => $status,
                    'error_message' => $error,
                    'sent_at'      => $status === 'sent' ? now() : null,
                ]
            );
        } catch (Throwable $e) {
            Log::error('Failed to write email log: ' . $e->getMessage());
        }
    }
}

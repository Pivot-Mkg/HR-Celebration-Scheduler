<?php

namespace App\Services;

use App\Mail\BirthdayMail;
use App\Mail\AnniversaryMail;
use App\Models\Employee;
use App\Models\Setting;
use Carbon\Carbon;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use Throwable;

class EmailService
{
    public function sendBirthdayEmail(Employee $employee, bool $isTest = false, ?string $testAddress = null): bool
    {
        try {
            $config = Setting::getMailConfig();
            $mailable = new BirthdayMail($employee, $config);

            if ($isTest) {
                $to = $testAddress ?: $config['test_email'];
                if (!$to) {
                    throw new \RuntimeException('No test email address configured.');
                }
                Mail::to($to)->send($mailable);
            } else {
                $this->sendWithCcBcc($mailable, $employee->email, $config);
            }

            return true;
        } catch (Throwable $e) {
            Log::error('Birthday email failed for employee ' . $employee->employee_code . ': ' . $e->getMessage());
            return false;
        }
    }

    public function sendAnniversaryEmail(Employee $employee, bool $isTest = false, ?string $testAddress = null): bool
    {
        try {
            $config = Setting::getMailConfig();
            $mailable = new AnniversaryMail($employee, $config);

            if ($isTest) {
                $to = $testAddress ?: $config['test_email'];
                if (!$to) {
                    throw new \RuntimeException('No test email address configured.');
                }
                Mail::to($to)->send($mailable);
            } else {
                $this->sendWithCcBcc($mailable, $employee->email, $config);
            }

            return true;
        } catch (Throwable $e) {
            Log::error('Anniversary email failed for employee ' . $employee->employee_code . ': ' . $e->getMessage());
            return false;
        }
    }

    private function sendWithCcBcc($mailable, string $to, array $config): void
    {
        $mail = Mail::to($to);

        $ccAddresses = $this->parseAddresses($config['cc'] ?? '');
        if (!empty($ccAddresses)) {
            $mail->cc($ccAddresses);
        }

        $bccAddresses = $this->parseAddresses($config['bcc'] ?? '');
        if (!empty($bccAddresses)) {
            $mail->bcc($bccAddresses);
        }

        $mail->send($mailable);
    }

    public function parseAddresses(string $addresses): array
    {
        return array_values(array_filter(
            array_map('trim', explode(',', $addresses)),
            fn($addr) => filter_var($addr, FILTER_VALIDATE_EMAIL)
        ));
    }

    public function validateEmailAddress(string $email): bool
    {
        return (bool) filter_var($email, FILTER_VALIDATE_EMAIL);
    }
}

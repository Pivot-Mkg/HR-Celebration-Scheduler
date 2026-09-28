<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Mail\BirthdayMail;
use App\Mail\AnniversaryMail;
use App\Models\Setting;
use App\Services\EmailService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class EmailController extends Controller
{
    public function __construct(private EmailService $emailService) {}

    public function previewBirthday(Employee $employee)
    {
        $config = Setting::getMailConfig();
        $mailable = new BirthdayMail($employee, $config);

        return view('email.preview', [
            'employee'  => $employee,
            'type'      => 'birthday',
            'from'      => $config['from_address'] ?? config('mail.from.address'),
            'fromName'  => $config['from_name'] ?? config('mail.from.name'),
            'to'        => $employee->email,
            'cc'        => $config['cc'] ?? '',
            'bcc'       => $config['bcc'] ?? '',
            'subject'   => "Happy Birthday, {$employee->employee_name}! 🎉",
            'html'      => view('emails.birthday', [
                'employee'    => $employee,
                'companyName' => config('app.name'),
                'currentDate' => now()->format('F j, Y'),
            ])->render(),
        ]);
    }

    public function previewAnniversary(Employee $employee)
    {
        $config = Setting::getMailConfig();
        $anniversaryService = new \App\Services\AnniversaryService();
        $years = $anniversaryService->getCompletedYears($employee);

        return view('email.preview', [
            'employee'  => $employee,
            'type'      => 'anniversary',
            'from'      => $config['from_address'] ?? config('mail.from.address'),
            'fromName'  => $config['from_name'] ?? config('mail.from.name'),
            'to'        => $employee->email,
            'cc'        => $config['cc'] ?? '',
            'bcc'       => $config['bcc'] ?? '',
            'subject'   => "Happy Work Anniversary, {$employee->employee_name}! 🎉",
            'html'      => view('emails.anniversary', [
                'employee'       => $employee,
                'completedYears' => $years,
                'companyName'    => config('app.name'),
                'currentDate'    => now()->format('F j, Y'),
            ])->render(),
        ]);
    }

    public function sendTest(Request $request)
    {
        $request->validate([
            'employee_id' => 'required|exists:employees,id',
            'type'        => 'required|in:birthday,anniversary',
            'test_email'  => 'required|email',
        ]);

        $employee = Employee::findOrFail($request->employee_id);
        $success = false;

        if ($request->type === 'birthday') {
            $success = $this->emailService->sendBirthdayEmail($employee, true, $request->test_email);
        } else {
            $success = $this->emailService->sendAnniversaryEmail($employee, true, $request->test_email);
        }

        if ($success) {
            return back()->with('success', "Test {$request->type} email sent to {$request->test_email}.");
        }

        return back()->with('error', 'Failed to send test email. Check mail configuration and logs.');
    }

    public function testForm()
    {
        $employees = Employee::active()->orderBy('employee_name')->get();
        $testEmail = Setting::get('mail_test_address', '');
        return view('email.test', compact('employees', 'testEmail'));
    }
}

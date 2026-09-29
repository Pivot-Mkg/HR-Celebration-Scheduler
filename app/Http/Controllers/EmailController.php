<?php

namespace App\Http\Controllers;

use App\Mail\TemplateMail;
use App\Models\Employee;
use App\Models\EmailTemplate;
use App\Models\Setting;
use App\Services\BannerImageService;
use App\Services\TemplateRenderer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class EmailController extends Controller
{
    public function __construct(
        private TemplateRenderer $renderer,
        private BannerImageService $banner,
    ) {}

    public function sendTest(Request $request)
    {
        $request->validate([
            'employee_id' => 'required|exists:employees,id',
            'type'        => 'required|in:birthday,anniversary,founding_day',
            'test_email'  => 'required|email',
        ]);

        $employee  = Employee::findOrFail($request->employee_id);
        $eventType = $request->type;

        $template = EmailTemplate::where('event_type', $eventType)
            ->where('is_active', true)
            ->where('is_default', true)
            ->first();

        if (! $template) {
            return back()->with('error', "No active default template found for event type '{$eventType}'. Please create one under Templates.");
        }

        // Generate personalised banner PNG bytes; render HTML with CID placeholder
        $bannerBytes = $this->banner->generateBytes($eventType, $employee);
        $extras      = ['banner_image' => 'cid:pivot_banner'];
        $rendered    = $this->renderer->renderTemplate($template, $employee, $eventType, null, $extras);

        $fromEmail = $template->from_email ?: config('mail.from.address');
        $fromName  = $template->from_name  ?: config('mail.from.name');

        $ccList = $this->buildCcList($template, $eventType);

        try {
            $mailable = new TemplateMail(
                mailSubject:  $rendered['subject'],
                bodyHtml:     $rendered['body_html'],
                bodyText:     $rendered['body_text'],
                fromEmail:    $fromEmail,
                fromName:     $fromName,
                bannerBytes:  $bannerBytes,
            );

            $send = Mail::to($request->test_email);
            foreach ($ccList as $cc) {
                $send->cc($cc);
            }
            $send->send($mailable);

            $typeLabel = match($eventType) {
                'birthday'     => 'birthday',
                'anniversary'  => 'work anniversary',
                'founding_day' => 'founding day',
                default        => $eventType,
            };

            return back()->with('success', "Test {$typeLabel} email sent to {$request->test_email}.");
        } catch (\Exception $e) {
            Log::error("Test email failed for {$eventType}: " . $e->getMessage());
            return back()->with('error', 'Failed to send test email: ' . $e->getMessage());
        }
    }

    public function testForm()
    {
        $employees = Employee::active()->orderBy('employee_name')->get();
        $testEmail = Setting::get('mail_test_address', '');
        return view('email.test', compact('employees', 'testEmail'));
    }

    private function buildCcList(EmailTemplate $template, string $eventType): array
    {
        if ($eventType === 'founding_day') {
            return Employee::active()
                ->whereNotNull('email')
                ->where('email', '!=', '')
                ->pluck('email')
                ->toArray();
        }

        if (! $template->cc_addresses) {
            return [];
        }

        return array_filter(
            array_map('trim', explode(',', $template->cc_addresses)),
            fn($e) => filter_var($e, FILTER_VALIDATE_EMAIL)
        );
    }
}

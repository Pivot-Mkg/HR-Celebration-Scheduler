<?php

namespace App\Http\Controllers;

use App\Models\EmailTemplate;
use App\Models\Setting;
use Illuminate\Support\Facades\DB;

class HealthController extends Controller
{
    public function index()
    {
        $checks = [];

        // DB check
        try {
            DB::connection()->getPdo();
            $checks['database'] = ['status' => 'ok', 'message' => 'Connected'];
        } catch (\Throwable $e) {
            $checks['database'] = ['status' => 'error', 'message' => 'Connection failed'];
        }

        // Mail config
        $config = Setting::getMailConfig();
        $mailOk = !empty($config['from_address']) && !empty(config('mail.mailers.smtp.host'));
        $checks['mail'] = [
            'status'  => $mailOk ? 'ok' : 'warning',
            'message' => $mailOk ? "From: {$config['from_address']}" : 'SMTP not fully configured',
        ];

        // Templates
        $bdTemplate  = EmailTemplate::active()->forEvent('birthday')->where('is_default', true)->exists();
        $annTemplate = EmailTemplate::active()->forEvent('anniversary')->where('is_default', true)->exists();
        $fdTemplate  = EmailTemplate::active()->forEvent('founding_day')->where('is_default', true)->exists();
        $allOk = $bdTemplate && $annTemplate && $fdTemplate;
        $checks['templates'] = [
            'status'  => $allOk ? 'ok' : 'warning',
            'message' => 'Birthday: ' . ($bdTemplate ? '✓' : '✗')
                       . '  Anniversary: ' . ($annTemplate ? '✓' : '✗')
                       . '  Founding Day: ' . ($fdTemplate ? '✓' : '✗'),
        ];

        // Scheduler
        $checks['scheduler'] = [
            'status'  => 'info',
            'message' => 'Runs daily at 08:00 ' . config('app.timezone') . ' via cron',
        ];

        return view('health.index', compact('checks'));
    }
}

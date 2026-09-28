<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Seeder;

class SettingsSeeder extends Seeder
{
    public function run(): void
    {
        Setting::set('mail_host', env('MAIL_HOST', 'smtp.mailtrap.io'));
        Setting::set('mail_port', env('MAIL_PORT', '587'));
        Setting::set('mail_username', env('MAIL_USERNAME', ''));
        Setting::set('mail_encryption', env('MAIL_ENCRYPTION', 'tls'));
        Setting::set('mail_from_address', env('MAIL_FROM_ADDRESS', 'hr@pivotmkg.com'));
        Setting::set('mail_from_name', env('MAIL_FROM_NAME', 'HR Team'));
        Setting::set('mail_default_cc', 'hr@pivotmkg.com');
        Setting::set('mail_default_bcc', '');
        Setting::set('mail_test_address', '');
    }
}

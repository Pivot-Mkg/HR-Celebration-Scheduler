<?php

namespace Database\Seeders;

use App\Models\EmailTemplate;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PivotTemplateSeeder extends Seeder
{
    public function run(): void
    {
        // Remove all existing templates (hard delete — SoftDeletes, so use forceDelete)
        DB::table('email_templates')->delete();

        $baseUrl = rtrim(config('app.url', 'http://localhost:8000'), '/');

        // ── Birthday Template ─────────────────────────────────────────────────
        $birthdayHtml = <<<HTML
<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<style>
  body { font-family: Arial, sans-serif; background: #f0f0f0; margin: 0; padding: 0; }
  .wrapper { max-width: 600px; margin: 30px auto; background: #ffffff; border-radius: 10px; overflow: hidden; box-shadow: 0 2px 12px rgba(0,0,0,0.12); }
  .banner { width: 100%; display: block; }
  .body { padding: 32px 30px 24px; color: #2d2d2d; line-height: 1.7; font-size: 15px; }
  .body p { margin: 0 0 16px; }
  .highlight { background: #fff5f5; border-left: 4px solid #e53e3e; padding: 14px 18px; border-radius: 4px; margin: 20px 0; font-size: 15px; }
  .footer { background: #f8f8f8; padding: 18px 30px; text-align: center; color: #999; font-size: 12px; border-top: 1px solid #eee; }
</style>
</head>
<body>
<div class="wrapper">
  <img src="{$baseUrl}/images/birthday-banner.webp" alt="Happy Birthday" class="banner">
  <div class="body">
    <p>Dear <strong>{{ employee_name }}</strong>,</p>
    <div class="highlight">
      🎂 Wish you a very Happy Birthday! We truly appreciate all your hard work, dedication, and the unique energy you bring to our team every day. We hope your special day is filled with joy and celebration. Wishing you a fantastic day and a wonderful year ahead!
    </div>
    <p>Best regards,<br><strong>Team Pivot</strong></p>
  </div>
  <div class="footer">{{ current_date }} · HR Celebration Scheduler · {{ company_name }}</div>
</div>
</body>
</html>
HTML;

        $birthday = EmailTemplate::create([
            'name'         => 'Pivot Birthday Greeting',
            'event_type'   => 'birthday',
            'subject'      => 'Happy Birthday from Team Pivot! 🎂',
            'body_html'    => $birthdayHtml,
            'body_text'    => "Dear {{ employee_name }},\n\nWish you a very Happy Birthday! We truly appreciate all your hard work, dedication, and the unique energy you bring to our team every day.\n\nBest regards,\nTeam Pivot",
            'from_name'    => 'Team Pivot',
            'from_email'   => null,
            'cc_addresses' => 'vidhya@pivotmkg.com, aashish@pivotmkg.com, rthomas@pivotmkg.com',
            'is_active'    => true,
            'is_default'   => true,
        ]);

        // ── Founding Day Template ─────────────────────────────────────────────
        $foundingHtml = <<<HTML
<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<style>
  body { font-family: Arial, sans-serif; background: #f0f0f0; margin: 0; padding: 0; }
  .wrapper { max-width: 600px; margin: 30px auto; background: #ffffff; border-radius: 10px; overflow: hidden; box-shadow: 0 2px 12px rgba(0,0,0,0.12); }
  .banner { width: 100%; display: block; }
  .body { padding: 32px 30px 24px; color: #2d2d2d; line-height: 1.7; font-size: 15px; }
  .body p { margin: 0 0 16px; }
  .highlight { background: #eff6ff; border-left: 4px solid #1d4ed8; padding: 14px 18px; border-radius: 4px; margin: 20px 0; font-size: 15px; }
  .footer { background: #f8f8f8; padding: 18px 30px; text-align: center; color: #999; font-size: 12px; border-top: 1px solid #eee; }
</style>
</head>
<body>
<div class="wrapper">
  <img src="{$baseUrl}/images/founding-day-banner.webp" alt="Happy Founding Day Pivot" class="banner">
  <div class="body">
    <div class="highlight">
      Celebrating another year of ideas, innovation, and impact. ✨<br><br>
      Happy Founding Day to Pivot!<br><br>
      From bold ideas to meaningful partnerships, every milestone has been shaped by the passion, creativity, and dedication of our incredible team.<br><br>
      Here's to the journey so far, the challenges that made us stronger, and the opportunities that lie ahead.<br><br>
      Cheers to creating, growing, and transforming together. 🚀
    </div>
    <p>With pride,<br><strong>Team Pivot</strong></p>
  </div>
  <div class="footer">{{ current_date }} · Pivot founding year: {{ founding_year }} · Year {{ years_since_founding }}</div>
</div>
</body>
</html>
HTML;

        EmailTemplate::create([
            'name'         => 'Pivot Founding Day',
            'event_type'   => 'founding_day',
            'subject'      => 'Happy Founding Day, Pivot! 🏢🎉',
            'body_html'    => $foundingHtml,
            'body_text'    => "Celebrating another year of ideas, innovation, and impact. ✨\n\nHappy Founding Day to Pivot!\n\nFrom bold ideas to meaningful partnerships, every milestone has been shaped by the passion, creativity, and dedication of our incredible team.\n\nCheers to creating, growing, and transforming together. 🚀\n\nWith pride,\nTeam Pivot",
            'from_name'    => 'Team Pivot',
            'from_email'   => null,
            'cc_addresses' => 'vidhya@pivotmkg.com, aashish@pivotmkg.com, rthomas@pivotmkg.com',
            'is_active'    => true,
            'is_default'   => true,
        ]);

        // ── Work Anniversary Template ─────────────────────────────────────────
        $anniversaryHtml = <<<HTML
<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<style>
  body { font-family: Arial, sans-serif; background: #f0f0f0; margin: 0; padding: 0; }
  .wrapper { max-width: 600px; margin: 30px auto; background: #ffffff; border-radius: 10px; overflow: hidden; box-shadow: 0 2px 12px rgba(0,0,0,0.12); }
  .banner { width: 100%; display: block; }
  .body { padding: 32px 30px 24px; color: #2d2d2d; line-height: 1.7; font-size: 15px; }
  .body p { margin: 0 0 16px; }
  .highlight { background: #f5f3ff; border-left: 4px solid #7c3aed; padding: 14px 18px; border-radius: 4px; margin: 20px 0; font-size: 15px; }
  .footer { background: #f8f8f8; padding: 18px 30px; text-align: center; color: #999; font-size: 12px; border-top: 1px solid #eee; }
</style>
</head>
<body>
<div class="wrapper">
  <img src="{$baseUrl}/images/anniversary-banner.webp" alt="Happy Work Anniversary" class="banner">
  <div class="body">
    <p>Dear <strong>{{ employee_name }}</strong>,</p>
    <div class="highlight">
      Your journey with Pivot has been filled with meaningful contributions, creative ideas, and moments that have helped shape our collective success.<br><br>
      Thank you for being an integral part of our story and for bringing your energy, expertise, and commitment to everything you do.<br><br>
      Here's to celebrating the journey so far and creating many more milestones together. 🚀
    </div>
    <p>With appreciation,<br><strong>Team Pivot</strong></p>
  </div>
  <div class="footer">{{ current_date }} · HR Celebration Scheduler · {{ company_name }}</div>
</div>
</body>
</html>
HTML;

        EmailTemplate::create([
            'name'         => 'Pivot Work Anniversary',
            'event_type'   => 'anniversary',
            'subject'      => 'Happy Work Anniversary {{ employee_name }}! 🌟',
            'body_html'    => $anniversaryHtml,
            'body_text'    => "Happy Work Anniversary {{ employee_name }}!\n\nYour journey with Pivot has been filled with meaningful contributions, creative ideas, and moments that have helped shape our collective success.\n\nThank you for being an integral part of our story and for bringing your energy, expertise, and commitment to everything you do.\n\nHere's to celebrating the journey so far and creating many more milestones together. 🚀\n\nWith appreciation,\nTeam Pivot",
            'from_name'    => 'Team Pivot',
            'from_email'   => null,
            'cc_addresses' => 'vidhya@pivotmkg.com, aashish@pivotmkg.com, rthomas@pivotmkg.com',
            'is_active'    => true,
            'is_default'   => true,
        ]);

        $this->command->info('✓ Inserted 3 Pivot email templates (Birthday, Founding Day, Work Anniversary).');
    }
}

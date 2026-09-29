<?php

namespace Database\Seeders;

use App\Models\EmailTemplate;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PivotTemplateSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('email_templates')->delete();

        // ── Birthday Template ─────────────────────────────────────────────────
        $birthdayHtml = <<<'HTML'
<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<style>
  body { font-family: Arial, sans-serif; background: #ffffff; margin: 0; padding: 30px 15px; }
  .wrapper { max-width: 600px; margin: 0 auto; }
  .text { font-size: 16px; line-height: 1.6; color: #222222; margin-bottom: 15px; }
  .banner { width: 100%; max-width: 600px; height: auto; display: block; border: 0; margin-top: 10px; }
</style>
</head>
<body>
<table width="100%" cellpadding="0" cellspacing="0" border="0">
<tr><td align="center">
<table width="600" cellpadding="0" cellspacing="0" border="0" style="max-width:600px;width:100%;">
  <tr><td class="text">Dear <strong>{{ employee_name }}</strong>,</td></tr>
  <tr><td class="text">
    Wish you a very Happy Birthday! We truly appreciate all your hard work, dedication, and the unique energy you bring to our team every day.
    <br><br>
    We hope your special day is filled with joy and celebration. Wishing you a fantastic day and a wonderful year ahead!
  </td></tr>
  <tr><td class="text">Best regards,<br><strong>Team Pivot</strong></td></tr>
  <tr><td align="center">
    <img src="{{ banner_image }}" alt="Happy Birthday from Team Pivot" width="600" class="banner">
  </td></tr>
</table>
</td></tr>
</table>
</body>
</html>
HTML;

        EmailTemplate::create([
            'name'         => 'Pivot Birthday Greeting',
            'event_type'   => 'birthday',
            'subject'      => 'Happy Birthday {{ employee_name }}! 🎂',
            'body_html'    => $birthdayHtml,
            'body_text'    => "Dear {{ employee_name }},\n\nWish you a very Happy Birthday! We truly appreciate all your hard work, dedication, and the unique energy you bring to our team every day.\n\nBest regards,\nTeam Pivot",
            'from_name'    => 'Team Pivot',
            'from_email'   => null,
            'cc_addresses' => 'vidhya@pivotmkg.com, aashish@pivotmkg.com, rthomas@pivotmkg.com',
            'is_active'    => true,
            'is_default'   => true,
        ]);

        // ── Founding Day Template ─────────────────────────────────────────────
        $foundingHtml = <<<'HTML'
<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<style>
  body { font-family: Arial, sans-serif; background: #ffffff; margin: 0; padding: 30px 15px; }
  .text { font-size: 16px; line-height: 1.6; color: #222222; margin-bottom: 25px; }
  .banner { width: 100%; max-width: 600px; height: auto; display: block; border: 0; margin-top: 10px; }
</style>
</head>
<body>
<table width="100%" cellpadding="0" cellspacing="0" border="0">
<tr><td align="center">
<table width="600" cellpadding="0" cellspacing="0" border="0" style="max-width:600px;width:100%;">
  <tr><td class="text">
    Celebrating another year of ideas, innovation, and impact. ✨
    <br><br>
    Happy Founding Day to Pivot!
    <br><br>
    From bold ideas to meaningful partnerships, every milestone has been shaped by the passion, creativity, and dedication of our incredible team.
    <br><br>
    Here's to the journey so far, the challenges that made us stronger, and the opportunities that lie ahead.
    <br><br>
    Cheers to creating, growing, and transforming together. 🚀
    <br><br>
    Best regards,<br><strong>Team Pivot</strong>
  </td></tr>
  <tr><td align="center">
    <img src="{{ banner_image }}" alt="Happy Founding Day Pivot" width="600" class="banner">
  </td></tr>
</table>
</td></tr>
</table>
</body>
</html>
HTML;

        EmailTemplate::create([
            'name'         => 'Pivot Founding Day',
            'event_type'   => 'founding_day',
            'subject'      => 'Happy Founding Day, Pivot! 🏢🎉',
            'body_html'    => $foundingHtml,
            'body_text'    => "Celebrating another year of ideas, innovation, and impact. ✨\n\nHappy Founding Day to Pivot!\n\nFrom bold ideas to meaningful partnerships, every milestone has been shaped by the passion, creativity, and dedication of our incredible team.\n\nCheers to creating, growing, and transforming together. 🚀\n\nBest regards,\nTeam Pivot",
            'from_name'    => 'Team Pivot',
            'from_email'   => null,
            'cc_addresses' => null,
            'is_active'    => true,
            'is_default'   => true,
        ]);

        // ── Work Anniversary Template ─────────────────────────────────────────
        $anniversaryHtml = <<<'HTML'
<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<style>
  body { font-family: Arial, sans-serif; background: #ffffff; margin: 0; padding: 30px 15px; }
  .text { font-size: 16px; line-height: 1.6; color: #222222; margin-bottom: 15px; }
  .banner { width: 100%; max-width: 600px; height: auto; display: block; border: 0; margin-top: 10px; }
</style>
</head>
<body>
<table width="100%" cellpadding="0" cellspacing="0" border="0">
<tr><td align="center">
<table width="600" cellpadding="0" cellspacing="0" border="0" style="max-width:600px;width:100%;">
  <tr><td class="text">Dear <strong>{{ employee_name }}</strong>,</td></tr>
  <tr><td class="text">
    Your journey with Pivot has been filled with meaningful contributions, creative ideas, and moments that have helped shape our collective success.
    <br><br>
    Thank you for being an integral part of our story and for bringing your energy, expertise, and commitment to everything you do.
    <br><br>
    Here's to celebrating the journey so far and creating many more milestones together. 🚀
  </td></tr>
  <tr><td class="text">With appreciation,<br><strong>Team Pivot</strong></td></tr>
  <tr><td align="center">
    <img src="{{ banner_image }}" alt="Happy Work Anniversary" width="600" class="banner">
  </td></tr>
</table>
</td></tr>
</table>
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

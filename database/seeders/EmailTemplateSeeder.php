<?php

namespace Database\Seeders;

use App\Models\EmailTemplate;
use Illuminate\Database\Seeder;

class EmailTemplateSeeder extends Seeder
{
    public function run(): void
    {
        // Default Birthday Template
        EmailTemplate::updateOrCreate(
            ['name' => 'Default Birthday Template'],
            [
                'event_type' => 'birthday',
                'subject'    => 'Happy Birthday, {{ employee_name }}! 🎉',
                'body_html'  => <<<'HTML'
<!DOCTYPE html>
<html>
<head><meta charset="UTF-8"><meta name="viewport" content="width=device-width,initial-scale=1.0">
<style>
body{font-family:Arial,sans-serif;background:#f4f4f4;margin:0;padding:0;}
.wrapper{max-width:600px;margin:30px auto;background:#fff;border-radius:8px;overflow:hidden;box-shadow:0 2px 10px rgba(0,0,0,.1);}
.header{background:linear-gradient(135deg,#667eea,#764ba2);padding:40px 30px;text-align:center;color:#fff;}
.header h1{margin:0;font-size:28px;}
.body{padding:35px 30px;color:#333;line-height:1.6;}
.highlight{background:#f8f0ff;border-left:4px solid #764ba2;padding:15px 20px;border-radius:4px;margin:20px 0;}
.footer{background:#f8f8f8;padding:20px 30px;text-align:center;color:#888;font-size:13px;border-top:1px solid #eee;}
</style></head>
<body>
<div class="wrapper">
  <div class="header"><h1>🎂 Happy Birthday!</h1><p style="margin:10px 0 0;opacity:.9;">Wishing you a wonderful day</p></div>
  <div class="body">
    <p>Dear <strong>{{ employee_name }}</strong>,</p>
    <div class="highlight"><p style="margin:0;font-size:16px;">🎉 Wishing you a very Happy Birthday from all of us at <strong>{{ company_name }}</strong>!</p></div>
    <p>On this special day, we want you to know how much we appreciate your hard work, dedication, and the positive energy you bring to our team every day.</p>
    <p>May this birthday bring you joy, happiness, and all the success you deserve. Here's to another wonderful year ahead!</p>
    <p>Warm regards,<br><strong>HR Team</strong><br>{{ company_name }}</p>
  </div>
  <div class="footer"><p>{{ current_date }} · Sent by HR Celebration Scheduler</p></div>
</div>
</body></html>
HTML,
                'body_text'  => "Dear {{ employee_name }},\n\nWishing you a very Happy Birthday from all of us at {{ company_name }}!\n\nWarm regards,\nHR Team",
                'is_active'  => true,
                'is_default' => true,
            ]
        );

        // Default Anniversary Template
        EmailTemplate::updateOrCreate(
            ['name' => 'Default Anniversary Template'],
            [
                'event_type' => 'anniversary',
                'subject'    => 'Happy Work Anniversary, {{ employee_name }}! 🎉',
                'body_html'  => <<<'HTML'
<!DOCTYPE html>
<html>
<head><meta charset="UTF-8"><meta name="viewport" content="width=device-width,initial-scale=1.0">
<style>
body{font-family:Arial,sans-serif;background:#f4f4f4;margin:0;padding:0;}
.wrapper{max-width:600px;margin:30px auto;background:#fff;border-radius:8px;overflow:hidden;box-shadow:0 2px 10px rgba(0,0,0,.1);}
.header{background:linear-gradient(135deg,#f093fb,#f5576c);padding:40px 30px;text-align:center;color:#fff;}
.header h1{margin:0;font-size:28px;}
.body{padding:35px 30px;color:#333;line-height:1.6;}
.years-badge{background:linear-gradient(135deg,#f093fb,#f5576c);color:#fff;display:inline-block;padding:10px 25px;border-radius:30px;font-size:22px;font-weight:bold;margin:10px 0;}
.highlight{background:#fff5f5;border-left:4px solid #f5576c;padding:15px 20px;border-radius:4px;margin:20px 0;}
.footer{background:#f8f8f8;padding:20px 30px;text-align:center;color:#888;font-size:13px;border-top:1px solid #eee;}
</style></head>
<body>
<div class="wrapper">
  <div class="header"><h1>🌟 Work Anniversary!</h1><p style="margin:10px 0 0;opacity:.9;">Celebrating your journey with us</p></div>
  <div class="body">
    <p>Dear <strong>{{ employee_name }}</strong>,</p>
    <div class="highlight">
      <p style="margin:0;font-size:16px;">🎊 Congratulations on completing</p>
      <div><span class="years-badge">{{ years }} Year(s)</span></div>
      <p style="margin:5px 0 0;font-size:16px;">with <strong>{{ company_name }}</strong>!</p>
    </div>
    <p>Your dedication, hard work, and valuable contributions have been truly appreciated by the entire team.</p>
    <p>Thank you for being an integral part of our journey. We look forward to many more years of working together!</p>
    <p>Warm regards,<br><strong>HR Team</strong><br>{{ company_name }}</p>
  </div>
  <div class="footer"><p>{{ current_date }} · Sent by HR Celebration Scheduler</p></div>
</div>
</body></html>
HTML,
                'body_text'  => "Dear {{ employee_name }},\n\nCongratulations on completing {{ years }} year(s) with {{ company_name }}!\n\nThank you for your dedication and valuable contributions.\n\nWarm regards,\nHR Team",
                'is_active'  => true,
                'is_default' => true,
            ]
        );

    }
}

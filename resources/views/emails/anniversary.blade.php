<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<style>
  body { font-family: Arial, sans-serif; background: #f4f4f4; margin: 0; padding: 0; }
  .wrapper { max-width: 600px; margin: 30px auto; background: #ffffff; border-radius: 8px; overflow: hidden; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
  .header { background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%); padding: 40px 30px; text-align: center; color: white; }
  .header h1 { margin: 0; font-size: 28px; }
  .header p { margin: 10px 0 0; font-size: 18px; opacity: 0.9; }
  .body { padding: 35px 30px; color: #333333; line-height: 1.6; }
  .body p { margin: 0 0 15px; }
  .years-badge { background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%); color: white; display: inline-block; padding: 10px 25px; border-radius: 30px; font-size: 22px; font-weight: bold; margin: 15px 0; }
  .highlight { background: #fff5f5; border-left: 4px solid #f5576c; padding: 15px 20px; border-radius: 4px; margin: 20px 0; }
  .footer { background: #f8f8f8; padding: 20px 30px; text-align: center; color: #888; font-size: 13px; border-top: 1px solid #eee; }
</style>
</head>
<body>
<div class="wrapper">
  <div class="header">
    <h1>🌟 Work Anniversary!</h1>
    <p>Celebrating your journey with us</p>
  </div>
  <div class="body">
    <p>Dear <strong>{{ $employee->employee_name }}</strong>,</p>
    <div class="highlight">
      <p style="margin:0; font-size:16px;">🎊 Congratulations on completing</p>
      <div><span class="years-badge">{{ $completedYears }} {{ $completedYears === 1 ? 'Year' : 'Years' }}</span></div>
      <p style="margin:5px 0 0; font-size:16px;">with <strong>{{ $companyName }}</strong>!</p>
    </div>
    <p>Your dedication, hard work, and valuable contributions over the past {{ $completedYears }} {{ $completedYears === 1 ? 'year' : 'years' }} have been truly appreciated by the entire team.</p>
    <p>Thank you for being an integral part of our journey. We look forward to many more years of working together and achieving great things!</p>
    <p>Warm regards,<br><strong>HR Team</strong><br>{{ $companyName }}</p>
  </div>
  <div class="footer">
    <p>{{ $currentDate }} · This email was sent by the HR Celebration Scheduler</p>
  </div>
</div>
</body>
</html>

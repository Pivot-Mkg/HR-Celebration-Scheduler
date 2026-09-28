# HR Celebration Scheduler

Automatically sends birthday and work anniversary emails to employees, with template management, scheduler UI, and full email audit logs.

## Requirements

- PHP 8.2+ (tested on 8.5.9)
- Composer
- SQLite (development) or MySQL 8+ (production)
- SMTP credentials for sending email

## Installation

```bash
composer install
cp .env.example .env
php artisan key:generate
```

## Database Setup

**SQLite (default, development):**
```bash
php artisan migrate
```

**MySQL (production):**
1. Create the database:
   ```sql
   CREATE DATABASE hr_celebration_scheduler CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
   ```
2. Update `.env`:
   ```
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=hr_celebration_scheduler
   DB_USERNAME=your_username
   DB_PASSWORD=your_password
   ```
3. Run: `php artisan migrate`

## Running Locally

```bash
php artisan serve
```

Visit: http://localhost:8000

## Creating Admin User

```bash
php artisan db:seed --class=AdminUserSeeder
```

**Default credentials:**
- Email: `hr@pivotmkg.com`
- Password: `HRAdmin@2024!`

> **Change this password immediately after first login.**

## Seeding Test Data

```bash
php artisan db:seed
```

Seeds 10 sample employees and 2 default email templates.

## Running Tests

```bash
php artisan test
```

Expected: **97 tests, 97 passing.**

## Mail Configuration

Configure SMTP in the admin UI at **Settings → Email Settings**, or via `.env`:

```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=hr@pivotmkg.com
MAIL_PASSWORD=your_app_password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=hr@pivotmkg.com
MAIL_FROM_NAME="HR Team"
```

Settings saved via the UI override `.env` values at runtime.

## Email Templates

Manage HTML email templates at **Email Templates**. Each template supports dynamic variables:

| Variable | Description |
|---|---|
| `{{ employee_name }}` | Employee full name |
| `{{ employee_code }}` | Employee ID code |
| `{{ department }}` | Department |
| `{{ years }}` | Years of service (anniversary only) |
| `{{ company_name }}` | Company name |
| `{{ current_date }}` | Today's date |

## Automatic Scheduler

### Manual Run (Web UI)
Go to **Scheduler → Run Scheduler** to trigger immediately. Use **Dry Run** to preview without sending.

### Manual Run (CLI)
```bash
php artisan celebrations:process
php artisan celebrations:process --dry-run
```

### Automated (Production Cron)
Add this to your server's crontab:

```cron
* * * * * cd /path/to/app && php artisan schedule:run >> /dev/null 2>&1
```

The scheduler runs at **08:00 Asia/Kolkata** daily. It is protected against duplicate sends — if run multiple times on the same day, already-sent emails are skipped automatically.

## Email Logs

All sent/failed/skipped emails are logged at **Email History**. Failed emails can be retried from the log detail page.

## Production Deployment Checklist

1. Set `APP_DEBUG=false` and `APP_ENV=production` in `.env`
2. Generate a fresh app key: `php artisan key:generate`
3. Configure MySQL and run `php artisan migrate --force`
4. Set a strong admin password after first login
5. Configure SMTP credentials in Settings → Email Settings
6. Add the cron entry above to your server
7. Run optimization commands:
   ```bash
   php artisan config:cache
   php artisan route:cache
   php artisan view:cache
   ```
8. Seed default templates: `php artisan db:seed --class=EmailTemplateSeeder`
9. Run `composer audit` to check for known security vulnerabilities

## Security Notes

- SMTP passwords are stored in the database (encrypted-at-rest via your DB/disk encryption) and never echoed in HTML or logs
- `APP_DEBUG=false` must be set in production to prevent stack trace exposure
- CSRF protection is enabled on all POST/PUT/DELETE endpoints (Laravel default)
- All routes require authentication
- `eval()` is never used for template rendering — templates are rendered via safe string replacement only

## Birthday Logic

The `BirthdayService` finds active employees whose `date_of_birth` month+day matches today. The year is irrelevant — an employee born on 24-Sep-1990 matches every 24 September.

## Anniversary Logic

The `AnniversaryService` finds active employees whose `date_of_joining` month+day matches today AND whose joining year is before the current year (prevents false anniversaries on day 0). Completed years are calculated with `Carbon::diffInYears`.

## Test Email

1. Go to **Email → Test Email**
2. Select an employee and event type
3. Enter your test email address
4. Click Send — the email goes to your test address only, NOT to the employee

## Project Structure

```
app/
├── Http/Controllers/
│   ├── DashboardController.php
│   ├── EmployeeController.php
│   ├── EmailController.php
│   ├── EmailTemplateController.php
│   ├── EmailLogController.php
│   ├── SchedulerController.php
│   ├── HealthController.php
│   └── SettingsController.php
├── Mail/
│   ├── BirthdayMail.php
│   ├── AnniversaryMail.php
│   └── TemplateMail.php
├── Models/
│   ├── Employee.php
│   ├── EmailTemplate.php
│   ├── EmailLog.php
│   ├── Setting.php
│   └── User.php
└── Services/
    ├── AnniversaryService.php
    ├── BirthdayService.php
    ├── CelebrationProcessor.php
    ├── EmailService.php
    └── TemplateRenderer.php
```

## Troubleshooting

**PHP zip extension missing:** Enable `extension=zip` in `php.ini`.

**Composer not found:** Download from https://getcomposer.org/

**Mail not sending:** Check SMTP credentials in Settings → Email Settings, verify port/encryption match your provider.

**Tests failing:** Ensure `pdo_sqlite` extension is enabled in `php.ini`.

**Cron not running:** Verify the cron entry points to the correct project path and the `schedule:run` command runs without errors.

<?php

namespace App\Services;

use App\Models\Employee;
use App\Models\EmailTemplate;
use Carbon\Carbon;

class TemplateRenderer
{
    /** All supported variable names */
    public const VARIABLES = [
        'employee_name', 'employee_code', 'employee_email',
        'department', 'designation',
        'manager_name', 'manager_email',
        'date_of_birth', 'date_of_joining',
        'years', 'founding_year', 'years_since_founding',
        'company_name', 'hr_email', 'current_date',
        'banner_image',
    ];

    public function resolve(Employee $employee, string $eventType, ?Carbon $date = null): array
    {
        $today = $date ?? Carbon::now();
        $years = 0;
        if ($eventType === 'anniversary' && $employee->date_of_joining) {
            $years = (int) $employee->date_of_joining->diffInYears($today);
        }

        return [
            'employee_name'       => $employee->employee_name,
            'employee_code'       => $employee->employee_code,
            'employee_email'      => $employee->email,
            'department'          => $employee->department ?? '',
            'designation'         => $employee->designation ?? '',
            'manager_name'        => $employee->manager_name ?? '',
            'manager_email'       => $employee->manager_email ?? '',
            'date_of_birth'       => $employee->date_of_birth?->format('d M Y') ?? '',
            'date_of_joining'     => $employee->date_of_joining?->format('d M Y') ?? '',
            'years'               => (string) $years,
            'founding_year'       => (string) \App\Services\FoundingDayService::FOUNDING_YEAR,
            'years_since_founding'=> (string) (int)($today->year - \App\Services\FoundingDayService::FOUNDING_YEAR),
            'company_name'        => config('app.name'),
            'hr_email'            => config('mail.from.address', ''),
            'current_date'        => $today->format('d M Y'),
        ];
    }

    public function render(string $template, array $variables): string
    {
        $search  = array_map(fn($k) => '{{ ' . $k . ' }}', array_keys($variables));
        $replace = array_values($variables);
        return str_replace($search, $replace, $template);
    }

    public function renderTemplate(EmailTemplate $template, Employee $employee, string $eventType, ?Carbon $date = null, array $extras = []): array
    {
        $vars = array_merge($this->resolve($employee, $eventType, $date), $extras);

        return [
            'subject'   => $this->render($template->subject, $vars),
            'body_html' => $this->render($template->body_html, $vars),
            'body_text' => $template->body_text ? $this->render($template->body_text, $vars) : null,
        ];
    }

    public function detectUnknownVariables(string $content): array
    {
        preg_match_all('/\{\{\s*(\w+)\s*\}\}/', $content, $matches);
        $found = $matches[1] ?? [];
        return array_values(array_diff($found, self::VARIABLES));
    }
}

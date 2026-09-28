<?php

namespace Tests\Unit;

use App\Models\Employee;
use App\Models\EmailTemplate;
use App\Services\TemplateRenderer;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TemplateRendererTest extends TestCase
{
    use RefreshDatabase;

    private TemplateRenderer $renderer;

    protected function setUp(): void
    {
        parent::setUp();
        $this->renderer = new TemplateRenderer();
    }

    public function test_renders_employee_name_variable(): void
    {
        $employee = Employee::factory()->create(['employee_name' => 'Alice Test']);
        $result   = $this->renderer->render('Hello {{ employee_name }}!', ['employee_name' => 'Alice Test']);
        $this->assertEquals('Hello Alice Test!', $result);
    }

    public function test_resolves_all_birthday_variables(): void
    {
        $employee = Employee::factory()->create([
            'employee_name' => 'Bob Example',
            'employee_code' => 'EX001',
        ]);
        $vars = $this->renderer->resolve($employee, 'birthday');
        $this->assertArrayHasKey('employee_name', $vars);
        $this->assertArrayHasKey('company_name', $vars);
        $this->assertArrayHasKey('current_date', $vars);
        $this->assertEquals('Bob Example', $vars['employee_name']);
    }

    public function test_resolves_years_for_anniversary(): void
    {
        $employee = Employee::factory()->create([
            'date_of_joining' => Carbon::create(2022, 9, 24)->format('Y-m-d'),
        ]);
        $date = Carbon::create(2026, 9, 24);
        $vars = $this->renderer->resolve($employee, 'anniversary', $date);
        $this->assertEquals('4', $vars['years']);
    }

    public function test_years_is_zero_for_birthday_type(): void
    {
        $employee = Employee::factory()->create();
        $vars     = $this->renderer->resolve($employee, 'birthday');
        $this->assertEquals('0', $vars['years']);
    }

    public function test_detects_unknown_variables(): void
    {
        $unknown = $this->renderer->detectUnknownVariables('Hello {{ employee_name }} from {{ unknown_var }}');
        $this->assertContains('unknown_var', $unknown);
        $this->assertNotContains('employee_name', $unknown);
    }

    public function test_no_unknown_variables_in_valid_template(): void
    {
        $content = 'Hello {{ employee_name }}, today is {{ current_date }}';
        $unknown = $this->renderer->detectUnknownVariables($content);
        $this->assertEmpty($unknown);
    }

    public function test_render_template_method(): void
    {
        $employee = Employee::factory()->create(['employee_name' => 'Test Person']);
        $template = EmailTemplate::factory()->create([
            'subject'    => 'Happy Birthday {{ employee_name }}',
            'body_html'  => '<p>Dear {{ employee_name }}</p>',
            'event_type' => 'birthday',
        ]);
        $rendered = $this->renderer->renderTemplate($template, $employee, 'birthday');
        $this->assertStringContainsString('Test Person', $rendered['subject']);
        $this->assertStringContainsString('Test Person', $rendered['body_html']);
    }
}

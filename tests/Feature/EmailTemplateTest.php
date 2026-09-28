<?php

namespace Tests\Feature;

use App\Models\EmailTemplate;
use App\Models\Employee;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class EmailTemplateTest extends TestCase
{
    use RefreshDatabase;

    private User $user;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();
    }

    public function test_template_list_is_accessible(): void
    {
        $this->actingAs($this->user)->get('/templates')->assertStatus(200);
    }

    public function test_template_can_be_created(): void
    {
        $this->actingAs($this->user)
             ->post('/templates', $this->validTemplateData())
             ->assertRedirect();

        $this->assertDatabaseHas('email_templates', ['name' => 'Test Birthday Template']);
    }

    public function test_template_name_is_required(): void
    {
        $data = array_merge($this->validTemplateData(), ['name' => '']);
        $this->actingAs($this->user)->post('/templates', $data)->assertSessionHasErrors('name');
    }

    public function test_template_subject_is_required(): void
    {
        $data = array_merge($this->validTemplateData(), ['subject' => '']);
        $this->actingAs($this->user)->post('/templates', $data)->assertSessionHasErrors('subject');
    }

    public function test_template_event_type_must_be_valid(): void
    {
        $data = array_merge($this->validTemplateData(), ['event_type' => 'invalid']);
        $this->actingAs($this->user)->post('/templates', $data)->assertSessionHasErrors('event_type');
    }

    public function test_template_can_be_updated(): void
    {
        $template = EmailTemplate::factory()->create();

        $this->actingAs($this->user)
             ->put("/templates/{$template->id}", array_merge($this->validTemplateData(), ['name' => 'Updated Name']))
             ->assertRedirect();

        $this->assertDatabaseHas('email_templates', ['id' => $template->id, 'name' => 'Updated Name']);
    }

    public function test_template_can_be_archived(): void
    {
        $template = EmailTemplate::factory()->create(['is_active' => true]);
        $this->actingAs($this->user)->delete("/templates/{$template->id}")->assertRedirect('/templates');
        $this->assertSoftDeleted('email_templates', ['id' => $template->id]);
    }

    public function test_set_default_removes_previous_default(): void
    {
        $tpl1 = EmailTemplate::factory()->create(['event_type' => 'birthday', 'is_default' => true,  'is_active' => true]);
        $tpl2 = EmailTemplate::factory()->create(['event_type' => 'birthday', 'is_default' => false, 'is_active' => true]);

        $this->actingAs($this->user)->post("/templates/{$tpl2->id}/set-default")->assertRedirect();

        $this->assertDatabaseHas('email_templates',    ['id' => $tpl2->id, 'is_default' => true]);
        $this->assertDatabaseMissing('email_templates', ['id' => $tpl1->id, 'is_default' => true]);
    }

    public function test_only_one_default_per_event_type(): void
    {
        $tpl1 = EmailTemplate::factory()->create(['event_type' => 'birthday', 'is_default' => true, 'is_active' => true]);
        $tpl2 = EmailTemplate::factory()->create(['event_type' => 'birthday', 'is_default' => false, 'is_active' => true]);

        $tpl2->setAsDefault();

        $this->assertEquals(1, EmailTemplate::where('event_type', 'birthday')->where('is_default', true)->count());
    }

    public function test_template_preview_renders(): void
    {
        $template = EmailTemplate::factory()->create(['event_type' => 'birthday']);
        $employee = Employee::factory()->create();

        $this->actingAs($this->user)
             ->get("/templates/{$template->id}/preview?employee_id={$employee->id}")
             ->assertStatus(200);
    }

    public function test_duplicate_creates_copy(): void
    {
        $template = EmailTemplate::factory()->create(['is_default' => true]);
        $count    = EmailTemplate::count();

        $this->actingAs($this->user)->post("/templates/{$template->id}/duplicate")->assertRedirect();

        $this->assertEquals($count + 1, EmailTemplate::count());
        $clone = EmailTemplate::orderByDesc('id')->first();
        $this->assertFalse($clone->is_default);
        $this->assertStringContainsString('Copy', $clone->name);
    }

    private function validTemplateData(): array
    {
        return [
            'name'       => 'Test Birthday Template',
            'event_type' => 'birthday',
            'subject'    => 'Happy Birthday {{ employee_name }}!',
            'body_html'  => '<p>Dear {{ employee_name }}, happy birthday!</p>',
            'is_active'  => '1',
        ];
    }
}

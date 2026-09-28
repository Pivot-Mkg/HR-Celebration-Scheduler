<?php

namespace Tests\Feature;

use App\Models\Employee;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class EmployeeTest extends TestCase
{
    use RefreshDatabase;

    private User $user;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();
    }

    public function test_employee_list_is_accessible(): void
    {
        $this->actingAs($this->user)->get('/employees')->assertStatus(200);
    }

    public function test_employee_can_be_created(): void
    {
        $this->actingAs($this->user)
             ->post('/employees', $this->validEmployeeData())
             ->assertRedirect('/employees');

        $this->assertDatabaseHas('employees', ['employee_code' => 'TEST001', 'employee_name' => 'Test User']);
    }

    public function test_employee_name_is_required(): void
    {
        $data = array_merge($this->validEmployeeData(), ['employee_name' => '']);
        $this->actingAs($this->user)->post('/employees', $data)->assertSessionHasErrors('employee_name');
    }

    public function test_employee_email_is_required(): void
    {
        $data = array_merge($this->validEmployeeData(), ['email' => '']);
        $this->actingAs($this->user)->post('/employees', $data)->assertSessionHasErrors('email');
    }

    public function test_employee_email_must_be_valid(): void
    {
        $data = array_merge($this->validEmployeeData(), ['email' => 'not-an-email']);
        $this->actingAs($this->user)->post('/employees', $data)->assertSessionHasErrors('email');
    }

    public function test_employee_code_must_be_unique(): void
    {
        Employee::factory()->create(['employee_code' => 'DUP001']);
        $data = array_merge($this->validEmployeeData(), ['employee_code' => 'DUP001']);
        $this->actingAs($this->user)->post('/employees', $data)->assertSessionHasErrors('employee_code');
    }

    public function test_employee_can_be_updated(): void
    {
        $employee = Employee::factory()->create();

        $this->actingAs($this->user)
             ->put("/employees/{$employee->id}", array_merge($this->validEmployeeData(), [
                 'employee_code' => $employee->employee_code,
                 'email' => $employee->email,
                 'employee_name' => 'Updated Name',
             ]))
             ->assertRedirect("/employees/{$employee->id}");

        $this->assertDatabaseHas('employees', ['id' => $employee->id, 'employee_name' => 'Updated Name']);
    }

    public function test_employee_deactivation(): void
    {
        $employee = Employee::factory()->create(['status' => 'active']);

        $this->actingAs($this->user)
             ->delete("/employees/{$employee->id}")
             ->assertRedirect('/employees');

        $this->assertDatabaseHas('employees', ['id' => $employee->id, 'status' => 'inactive']);
    }

    public function test_employee_search_by_name(): void
    {
        Employee::factory()->create(['employee_name' => 'Searchable Person', 'employee_code' => 'SRH001', 'email' => 'srh@example.com']);
        Employee::factory()->create(['employee_name' => 'Other Person', 'employee_code' => 'OTH001', 'email' => 'oth@example.com']);

        $response = $this->actingAs($this->user)->get('/employees?search=Searchable');
        $response->assertStatus(200)->assertSee('Searchable Person')->assertDontSee('Other Person');
    }

    public function test_employee_status_filter(): void
    {
        Employee::factory()->create(['employee_name' => 'Active One', 'employee_code' => 'ACT001', 'email' => 'act@example.com', 'status' => 'active']);
        Employee::factory()->create(['employee_name' => 'Inactive One', 'employee_code' => 'INA001', 'email' => 'ina@example.com', 'status' => 'inactive']);

        $response = $this->actingAs($this->user)->get('/employees?status=active');
        $response->assertStatus(200)->assertSee('Active One')->assertDontSee('Inactive One');
    }

    public function test_employee_detail_page(): void
    {
        $employee = Employee::factory()->create();
        $this->actingAs($this->user)->get("/employees/{$employee->id}")->assertStatus(200)->assertSee($employee->employee_name);
    }

    private function validEmployeeData(): array
    {
        return [
            'employee_code'   => 'TEST001',
            'employee_name'   => 'Test User',
            'email'           => 'testuser@pivotmkg.com',
            'department'      => 'Testing',
            'designation'     => 'Tester',
            'date_of_birth'   => '1990-06-15',
            'date_of_joining' => '2020-01-01',
            'manager_name'    => '',
            'manager_email'   => '',
            'status'          => 'active',
        ];
    }
}

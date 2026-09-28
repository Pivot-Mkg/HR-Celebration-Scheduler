<?php

namespace Database\Seeders;

use App\Models\Employee;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class EmployeeSeeder extends Seeder
{
    public function run(): void
    {
        $today = Carbon::today();

        $employees = [
            // 1. Birthday today
            [
                'employee_code'   => 'EMP001',
                'employee_name'   => 'Alice Johnson',
                'email'           => 'alice@pivotmkg.com',
                'department'      => 'Marketing',
                'designation'     => 'Marketing Manager',
                'date_of_birth'   => $today->copy()->subYears(30)->format('Y-m-d'),
                'date_of_joining' => $today->copy()->subYears(3)->subMonth()->format('Y-m-d'),
                'manager_name'    => 'Bob Smith',
                'manager_email'   => 'bob@pivotmkg.com',
                'status'          => 'active',
            ],
            // 2. Anniversary today
            [
                'employee_code'   => 'EMP002',
                'employee_name'   => 'Bob Smith',
                'email'           => 'bob@pivotmkg.com',
                'department'      => 'Operations',
                'designation'     => 'Operations Head',
                'date_of_birth'   => $today->copy()->subYears(40)->subDays(10)->format('Y-m-d'),
                'date_of_joining' => $today->copy()->subYears(5)->format('Y-m-d'),
                'manager_name'    => null,
                'manager_email'   => null,
                'status'          => 'active',
            ],
            // 3. Both birthday AND anniversary today
            [
                'employee_code'   => 'EMP003',
                'employee_name'   => 'Carol Davis',
                'email'           => 'carol@pivotmkg.com',
                'department'      => 'HR',
                'designation'     => 'HR Executive',
                'date_of_birth'   => $today->copy()->subYears(28)->format('Y-m-d'),
                'date_of_joining' => $today->copy()->subYears(2)->format('Y-m-d'),
                'manager_name'    => 'Bob Smith',
                'manager_email'   => 'bob@pivotmkg.com',
                'status'          => 'active',
            ],
            // 4. Birthday in another month
            [
                'employee_code'   => 'EMP004',
                'employee_name'   => 'David Patel',
                'email'           => 'david@pivotmkg.com',
                'department'      => 'Design',
                'designation'     => 'Senior Designer',
                'date_of_birth'   => $today->copy()->subYears(32)->addMonths(2)->format('Y-m-d'),
                'date_of_joining' => $today->copy()->subYears(4)->subMonths(2)->format('Y-m-d'),
                'manager_name'    => 'Alice Johnson',
                'manager_email'   => 'alice@pivotmkg.com',
                'status'          => 'active',
            ],
            // 5. Anniversary on another date
            [
                'employee_code'   => 'EMP005',
                'employee_name'   => 'Eve Wilson',
                'email'           => 'eve@pivotmkg.com',
                'department'      => 'Sales',
                'designation'     => 'Sales Executive',
                'date_of_birth'   => $today->copy()->subYears(25)->subMonths(3)->format('Y-m-d'),
                'date_of_joining' => $today->copy()->subYears(1)->subMonths(3)->format('Y-m-d'),
                'manager_name'    => 'Bob Smith',
                'manager_email'   => 'bob@pivotmkg.com',
                'status'          => 'active',
            ],
            // 6. Inactive employee — should be excluded
            [
                'employee_code'   => 'EMP006',
                'employee_name'   => 'Frank Inactive',
                'email'           => 'frank@pivotmkg.com',
                'department'      => 'IT',
                'designation'     => 'Developer',
                'date_of_birth'   => $today->copy()->subYears(35)->format('Y-m-d'),
                'date_of_joining' => $today->copy()->subYears(5)->format('Y-m-d'),
                'manager_name'    => null,
                'manager_email'   => null,
                'status'          => 'inactive',
            ],
            // 7. Employee with manager email
            [
                'employee_code'   => 'EMP007',
                'employee_name'   => 'Grace Lee',
                'email'           => 'grace@pivotmkg.com',
                'department'      => 'Finance',
                'designation'     => 'Finance Analyst',
                'date_of_birth'   => $today->copy()->subYears(29)->subMonths(6)->format('Y-m-d'),
                'date_of_joining' => $today->copy()->subYears(3)->subMonths(6)->format('Y-m-d'),
                'manager_name'    => 'Bob Smith',
                'manager_email'   => 'bob@pivotmkg.com',
                'status'          => 'active',
            ],
            // 8. Employee without manager email
            [
                'employee_code'   => 'EMP008',
                'employee_name'   => 'Henry Kumar',
                'email'           => 'henry@pivotmkg.com',
                'department'      => 'IT',
                'designation'     => 'IT Support',
                'date_of_birth'   => $today->copy()->subYears(27)->subMonths(4)->format('Y-m-d'),
                'date_of_joining' => $today->copy()->subYears(2)->subMonths(4)->format('Y-m-d'),
                'manager_name'    => null,
                'manager_email'   => null,
                'status'          => 'active',
            ],
            // 9. Extra active employee
            [
                'employee_code'   => 'EMP009',
                'employee_name'   => 'Iris Sharma',
                'email'           => 'iris@pivotmkg.com',
                'department'      => 'Marketing',
                'designation'     => 'Content Writer',
                'date_of_birth'   => $today->copy()->subYears(24)->subMonths(7)->format('Y-m-d'),
                'date_of_joining' => $today->copy()->subYears(1)->subMonths(7)->format('Y-m-d'),
                'manager_name'    => 'Alice Johnson',
                'manager_email'   => 'alice@pivotmkg.com',
                'status'          => 'active',
            ],
            // 10. Tomorrow birthday
            [
                'employee_code'   => 'EMP010',
                'employee_name'   => 'James Rodrigues',
                'email'           => 'james@pivotmkg.com',
                'department'      => 'Sales',
                'designation'     => 'Sales Manager',
                'date_of_birth'   => $today->copy()->subYears(38)->addDay()->format('Y-m-d'),
                'date_of_joining' => $today->copy()->subYears(6)->addDay()->format('Y-m-d'),
                'manager_name'    => null,
                'manager_email'   => null,
                'status'          => 'active',
            ],
        ];

        foreach ($employees as $data) {
            Employee::updateOrCreate(['employee_code' => $data['employee_code']], $data);
        }

        $this->command->info('10 sample employees seeded.');
    }
}

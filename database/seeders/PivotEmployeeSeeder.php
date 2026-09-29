<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Employee;

class PivotEmployeeSeeder extends Seeder
{
    public function run(): void
    {
        // Remove all test/existing employees
        Employee::query()->delete();

        // Real Pivot employee data from Excel
        // DOB year set to 1990 (actual birth year not in source file; only MM-DD matters for scheduler)
        $employees = [
            [
                'employee_code'   => 'EMSL2025EMP01',
                'employee_name'   => 'Ashish',
                'email'           => 'aashish@pivotmkg.com',
                'date_of_birth'   => '1990-09-19',
                'date_of_joining' => null,
                'status'          => 'active',
            ],
            [
                'employee_code'   => 'EMSL2025EMP02',
                'employee_name'   => 'Robin',
                'email'           => 'rthomas@pivotmkg.com',
                'date_of_birth'   => '1990-10-05',
                'date_of_joining' => null,
                'status'          => 'active',
            ],
            [
                'employee_code'   => 'EMSL2023EMP03',
                'employee_name'   => 'Vidhya V',
                'email'           => 'vidhya@pivotmkg.com',
                'date_of_birth'   => '1990-11-16',
                'date_of_joining' => '2023-01-09',
                'status'          => 'active',
            ],
            [
                'employee_code'   => 'EMSL2023EMP04',
                'employee_name'   => 'Aakash Mishra',
                'email'           => 'aakash@pivotmkg.com',
                'date_of_birth'   => '1990-08-10',
                'date_of_joining' => '2023-07-24',
                'status'          => 'active',
            ],
            [
                'employee_code'   => 'EMSL2023EMP06',
                'employee_name'   => 'Disha Kadam',
                'email'           => 'disha@pivotmkg.com',
                'date_of_birth'   => '1990-11-09',
                'date_of_joining' => '2023-09-25',
                'status'          => 'active',
            ],
            [
                'employee_code'   => 'EMSL2024EMP08',
                'employee_name'   => 'Meghna Samanta',
                'email'           => 'meghna@pivotmkg.com',
                'date_of_birth'   => '1990-11-21',
                'date_of_joining' => '2024-04-22',
                'status'          => 'active',
            ],
            [
                'employee_code'   => 'EMSL2024EMP11',
                'employee_name'   => 'Dhruv Makwana',
                'email'           => 'dhruv@pivotmkg.com',
                'date_of_birth'   => '1990-09-01',
                'date_of_joining' => '2024-02-12',
                'status'          => 'active',
            ],
            [
                'employee_code'   => 'EMSL2024EMP12',
                'employee_name'   => 'Lhingneithem Haokip',
                'email'           => 'lhingneithem@pivotmkg.com',
                'date_of_birth'   => '1990-03-26',
                'date_of_joining' => '2024-03-12',
                'status'          => 'active',
            ],
            [
                'employee_code'   => 'EMSL2025EMP13',
                'employee_name'   => 'Siddhesh Patil',
                'email'           => 'siddhesh@pivotmkg.com',
                'date_of_birth'   => '1990-01-15',
                'date_of_joining' => '2025-05-03',
                'status'          => 'active',
            ],
            [
                'employee_code'   => 'EMSL2025EMP15',
                'employee_name'   => 'Aditya Dahiwadkar',
                'email'           => 'aditya@pivotmkg.com',
                'date_of_birth'   => '1990-12-20',
                'date_of_joining' => '2025-02-19',
                'status'          => 'active',
            ],
            [
                'employee_code'   => 'EMSL2025EMP18',
                'employee_name'   => 'Kumar Shetty',
                'email'           => 'kumar@pivotmkg.com',
                'date_of_birth'   => '1990-09-18',
                'date_of_joining' => '2025-03-27',
                'status'          => 'active',
            ],
            [
                'employee_code'   => 'EMSL2025EMP20',
                'employee_name'   => 'Shweta Lad',
                'email'           => 'shweta@pivotmkg.com',
                'date_of_birth'   => '1990-12-01',
                'date_of_joining' => '2025-09-22',
                'status'          => 'active',
            ],
            [
                'employee_code'   => 'EMSL2025EMP21',
                'employee_name'   => 'Sauleha Akram',
                'email'           => 'sauleha@pivotmkg.com',
                'date_of_birth'   => '1990-03-13',
                'date_of_joining' => '2025-05-11',
                'status'          => 'active',
            ],
            [
                'employee_code'   => 'EMSL2026EMP22',
                'employee_name'   => 'Joshua Rebello',
                'email'           => 'joshua@pivotmkg.com',
                'date_of_birth'   => '1990-07-08',
                'date_of_joining' => '2026-05-18',
                'status'          => 'active',
            ],
            [
                'employee_code'   => 'EMSL2026EMP23',
                'employee_name'   => 'Ajai E',
                'email'           => 'ajai@pivotmkg.com',
                'date_of_birth'   => '1990-11-08',
                'date_of_joining' => '2026-05-08',
                'status'          => 'active',
            ],
        ];

        foreach ($employees as $data) {
            Employee::create($data);
        }

        $this->command->info('✓ Inserted ' . count($employees) . ' Pivot employees.');
    }
}

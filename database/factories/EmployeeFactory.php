<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class EmployeeFactory extends Factory
{
    public function definition(): array
    {
        static $counter = 0;
        $counter++;

        return [
            'employee_code'   => 'FAC' . str_pad($counter, 4, '0', STR_PAD_LEFT),
            'employee_name'   => $this->faker->name(),
            'email'           => $this->faker->unique()->safeEmail(),
            'department'      => $this->faker->randomElement(['Marketing', 'Sales', 'HR', 'IT', 'Finance', 'Design']),
            'designation'     => $this->faker->jobTitle(),
            'date_of_birth'   => $this->faker->date('Y-m-d', '-20 years'),
            'date_of_joining' => $this->faker->date('Y-m-d', 'now'),
            'manager_name'    => $this->faker->optional()->name(),
            'manager_email'   => $this->faker->optional()->safeEmail(),
            'status'          => 'active',
        ];
    }
}

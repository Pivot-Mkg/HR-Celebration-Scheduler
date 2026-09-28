<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class EmailLogFactory extends Factory
{
    public function definition(): array
    {
        static $counter = 0;
        $counter++;
        return [
            'employee_id' => null,
            'template_id' => null,
            'event_type'  => $this->faker->randomElement(['birthday', 'anniversary']),
            'event_date'  => now()->subDays(rand(0, 30))->format('Y-m-d'),
            'to_email'    => $this->faker->unique()->safeEmail(),
            'subject'     => 'Happy celebration!',
            'status'      => $this->faker->randomElement(['sent', 'failed', 'pending', 'skipped']),
            'sent_at'     => now(),
        ];
    }
}

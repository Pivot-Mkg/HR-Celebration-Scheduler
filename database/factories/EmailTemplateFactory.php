<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class EmailTemplateFactory extends Factory
{
    public function definition(): array
    {
        return [
            'name'        => $this->faker->words(3, true) . ' Template',
            'event_type'  => $this->faker->randomElement(['birthday', 'anniversary']),
            'subject'     => 'Happy {{ employee_name }}!',
            'body_html'   => '<p>Dear {{ employee_name }}, congratulations!</p>',
            'body_text'   => 'Dear {{ employee_name }}, congratulations!',
            'from_email'  => null,
            'from_name'   => null,
            'is_active'   => true,
            'is_default'  => false,
        ];
    }
}

<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'hr@pivotmkg.com'],
            [
                'name'     => 'HR Admin',
                'email'    => 'hr@pivotmkg.com',
                'password' => Hash::make('HRAdmin@2024!'),
            ]
        );

        $this->command->info('Admin user created: hr@pivotmkg.com / HRAdmin@2024!');
        $this->command->warn('IMPORTANT: Change this password after first login!');
    }
}

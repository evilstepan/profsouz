<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        // Создаем первого пользователя с ролью 'admin'
        User::factory()->create([
            'last_name' => 'Admin',
            'first_name' => 'User',
            'middle_name' => null,
            'email' => 'admin@example.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
            'phone' => '1234567890',
            'date_of_birth' => '1990-01-01',
            'position' => 'Administrator',
            'passport_series' => '0000',
            'passport_number' => '000000',
            'passport_issue_date' => '2010-01-01',
            'passport_issued_by' => 'Administration',
            'personal_data_agreement' => true,
            'status' => 'approved'
        ]);

        // Создаем остальных пользователей с ролью 'user' (по умолчанию)
        User::factory(10)->create();
    }
}

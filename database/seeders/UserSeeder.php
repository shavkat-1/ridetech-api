<?php

namespace Database\Seeders;

use App\Models\User;
use App\Enums\UserRole;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Пассажир для тестов (всегда ID 1)
        User::firstOrCreate(
            ['email' => 'passenger@test.com'],
            [
                'name' => 'Шохрух Пассажир',
                'password' => Hash::make('password'),
                'phone' => '+992921111111',
                'role' => UserRole::PASSENGER,
            ]
        );

        // Водитель для тестов (всегда ID 2)
        User::firstOrCreate(
            ['email' => 'driver@test.com'],
            [
                'name' => 'Парвиз Водитель',
                'password' => Hash::make('password'),
                'phone' => '+992922222222',
                'role' => UserRole::DRIVER,
            ]
        );

        // Дополнительные юзеры для массовки в пагинации
        User::factory(10)->create(['role' => UserRole::PASSENGER]);
        User::factory(5)->create(['role' => UserRole::DRIVER]);
    }
}
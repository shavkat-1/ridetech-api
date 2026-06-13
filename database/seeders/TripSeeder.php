<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Car;
use App\Models\Trip;
use App\Enums\TripStatus;
use Illuminate\Database\Seeder;

class TripSeeder extends Seeder
{
    public function run(): void
    {
        $passenger = User::where('email', 'passenger@test.com')->first();
        $driver = User::where('email', 'driver@test.com')->first();
        $car = Car::where('driver_id', $driver->id)->first();

        // 1. Идеальная завершенная поездка (для тестов отзывов) — ID поездки 1
        Trip::create([
            'passenger_id' => $passenger->id,
            'driver_id' => $driver->id,
            'car_id' => $car->id,
            'origin' => 'Душанбе, ЦУМ',
            'destination' => 'Худжанд, Панчшанбе',
            'departure_time' => now()->subDays(2),
            'status' => TripStatus::COMPLETED,
            'preferences' => 'Без курящих, кондиционер',
        ]);

        // 2. Активная поездка в процессе
        Trip::create([
            'passenger_id' => $passenger->id,
            'driver_id' => $driver->id,
            'car_id' => $car->id,
            'origin' => 'Истаравшан, центр',
            'destination' => 'Худжанд, 19мкр',
            'departure_time' => now()->addHours(3),
            'status' => TripStatus::IN_PROGRESS,
            'preferences' => 'Пустой багажник',
        ]);

        // 3. Генерируем еще пачку фейковых поездок для проверки пагинации истории
        for ($i = 1; $i <= 15; $i++) {
            Trip::create([
                'passenger_id' => $passenger->id,
                'driver_id' => $driver->id,
                'car_id' => $car->id,
                'origin' => "Точка А {$i}",
                'destination' => "Точка Б {$i}",
                'departure_time' => now()->subDays($i),
                'status' => TripStatus::COMPLETED,
                'preferences' => 'Стандарт',
            ]);
        }
    }
}
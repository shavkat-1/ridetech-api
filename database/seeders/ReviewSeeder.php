<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Trip;
use App\Models\Review;
use App\Enums\TripStatus;
use Illuminate\Database\Seeder;

class ReviewSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Создаем тестовых пользователей, если их нет
        $passenger = User::firstOrCreate(
            ['email' => 'passenger@test.com'],
            ['name' => 'Иван Пассажиров', 'password' => bcrypt('password')]
        );

        $driver = User::firstOrCreate(
            ['email' => 'driver@test.com'],
            ['name' => 'Али Водитель', 'password' => bcrypt('password')]
        );

        // 2. Создаем завершенную поездку, где пассажир — это наш $passenger (ID 1 или какой запишется)
        $trip = Trip::create([
            'passenger_id' => $passenger->id,
            'driver_id' => $driver->id,
            'origin' => 'Душанбе, ул. Рудаки 15',
            'destination' => 'Худжанд, 19-й микрорайон',
            'departure_time' => now()->subHours(2),
            'status' => TripStatus::COMPLETED,
            'preferences' => 'С кондиционером',
        ]);

        // 3. Создаем один готовый отзыв от этого пассажира этому водителю
        Review::create([
            'trip_id' => $trip->id,
            'reviewer_id' => $passenger->id, // Пассажир оставляет отзыв
            'reviewee_id' => $driver->id,    // Водителю
            'rating' => 5,
            'comment' => 'Прекрасная поездка, Али отличный собеседник!',
        ]);
    }
}
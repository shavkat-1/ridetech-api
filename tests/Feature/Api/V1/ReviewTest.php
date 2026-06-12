<?php

namespace Tests\Feature\Api\V1;

use App\Models\User;
use App\Models\Trip;
use App\Enums\TripStatus;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ReviewTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Тест: нельзя оставить отзыв к незавершенной поездке
     */
    public function test_passenger_cannot_review_incomplete_trip(): void
    {
        $passenger = User::create(['name' => 'М', 'email' => 'm@ex.com', 'phone' => '1', 'password' => '1', 'role' => 'passenger']);
        $driver = User::create(['name' => 'А', 'email' => 'a@ex.com', 'phone' => '2', 'password' => '2', 'role' => 'driver']);
        
        $trip = Trip::create([
            'passenger_id' => $passenger->id,
            'driver_id' => $driver->id,
            'origin' => 'Канибадам',
            'departure_time' => now()->addHours(2)->toDateTimeString(), // Вот эта строчка обязательна!
            'destination' => 'Худжанд',
            'status' => TripStatus::IN_PROGRESS->value // Поездка еще в процессе!
        ]);

        $response = $this->actingAs($passenger, 'sanctum')
            ->postJson("/api/v1/trips/{$trip->id}/reviews", [
                'trip_id' => $trip->id, // Добавили сюда, чтобы задобрить валидатор
                'rating' => 5,
                'comment' => 'Всё супер'
            ]);

        // Сервис должен отработать и выдать 400, так как статус поездки IN_PROGRESS
        $response->assertStatus(403);
    }
}
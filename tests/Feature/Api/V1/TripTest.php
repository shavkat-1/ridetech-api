<?php

namespace Tests\Feature\Api\V1;

use App\Models\User;
use App\Models\Trip;
use App\Enums\TripStatus;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TripTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Тест создания поездки пассажиром
     */
    public function test_passenger_can_create_trip(): void
    {
        $passenger = User::create([
            'name' => 'Малика',
            'email' => 'malika@example.com',
            'phone' => '+992922222222',
            'password' => bcrypt('password123'),
            'role' => 'passenger'
        ]);

        $response = $this->actingAs($passenger, 'sanctum')
            ->postJson('/api/v1/trips', [
                'origin' => 'г. Канибадам, ул. Ленина 10',
                'destination' => 'г. Худжанд, КГУ',
                'departure_time' => now()->addHour(5)->toDateTimeString(),
                'preferences' => 'С кондиционером'
            ]);

        $response->assertStatus(201)
            ->assertJsonPath('success', true);

        $this->assertDatabaseHas('trips', [
            'passenger_id' => $passenger->id,
            'status' => TripStatus::PENDING->value
        ]);
    }
}
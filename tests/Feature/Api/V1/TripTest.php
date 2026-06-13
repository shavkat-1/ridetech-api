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

    public function test_passenger_can_view_own_trip_details(): void
    {
        $passenger = User::create([
            'name' => 'Малика', 'email' => 'malika2@example.com',
            'phone' => '+992922222233', 'password' => bcrypt('password123'), 'role' => 'passenger'
        ]);

        $trip = Trip::create([
            'passenger_id' => $passenger->id, 'origin' => 'Душанбе', 'destination' => 'Худжанд',
            'departure_time' => now()->addHours(3)->toDateTimeString(),
            'preferences' => 'Без курящих', 'status' => TripStatus::PENDING->value,
        ]);

        $response = $this->actingAs($passenger, 'sanctum')->getJson("/api/v1/trips/{$trip->id}");

        $response->assertStatus(200)
            ->assertJsonPath('success', true)
            ->assertJsonPath('data.id', $trip->id);
    }

    public function test_passenger_can_update_pending_trip(): void
    {
        $passenger = User::create([
            'name' => 'Малика', 'email' => 'malika3@example.com',
            'phone' => '+992922222244', 'password' => bcrypt('password123'), 'role' => 'passenger'
        ]);

        $trip = Trip::create([
            'passenger_id' => $passenger->id, 'origin' => 'Душанбе', 'destination' => 'Худжанд',
            'departure_time' => now()->addHours(3)->toDateTimeString(),
            'preferences' => 'Без курящих', 'status' => TripStatus::PENDING->value,
        ]);

        $response = $this->actingAs($passenger, 'sanctum')
            ->putJson("/api/v1/trips/{$trip->id}", ['destination' => 'Кулоб']);

        $response->assertStatus(200)->assertJsonPath('success', true);

        $this->assertDatabaseHas('trips', ['id' => $trip->id, 'destination' => 'Кулоб']);
    }

    public function test_other_passenger_cannot_update_trip(): void
    {
        $owner = User::create([
            'name' => 'Малика', 'email' => 'malika4@example.com',
            'phone' => '+992922222255', 'password' => bcrypt('password123'), 'role' => 'passenger'
        ]);

        $other = User::create([
            'name' => 'Фарход', 'email' => 'farhod@example.com',
            'phone' => '+992922222266', 'password' => bcrypt('password123'), 'role' => 'passenger'
        ]);

        $trip = Trip::create([
            'passenger_id' => $owner->id, 'origin' => 'Душанбе', 'destination' => 'Худжанд',
            'departure_time' => now()->addHours(3)->toDateTimeString(),
            'status' => TripStatus::PENDING->value,
        ]);

        $response = $this->actingAs($other, 'sanctum')
            ->putJson("/api/v1/trips/{$trip->id}", ['destination' => 'Кулоб']);

        $response->assertStatus(403);
    }

    public function test_passenger_cannot_update_accepted_trip(): void
    {
        $passenger = User::factory()->create(['role' => 'passenger']);
        $trip = Trip::create([
            'passenger_id' => $passenger->id,
            'origin' => 'Душанбе',       
            'destination' => 'Худжанд',  
            'departure_time' => now()->addHours(3)->toDateTimeString(),
            'status' => TripStatus::IN_PROGRESS->value, // Статус уже не PENDING
            // ... остальные поля
        ]);

        $response = $this->actingAs($passenger, 'sanctum')
            ->putJson("/api/v1/trips/{$trip->id}", ['destination' => 'Кулоб']);

        $response->assertStatus(403); // Или 422, в зависимости от логики
    }
}
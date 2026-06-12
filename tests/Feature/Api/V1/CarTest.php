<?php

namespace Tests\Feature\Api\V1;

use App\Models\User;
use App\Models\Car;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CarTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Тест добавления автомобиля водителем
     */
    public function test_driver_can_add_car(): void
    {
        $driver = User::create([
            'name' => 'Алишер',
            'email' => 'alisher@example.com',
            'phone' => '+992921111111',
            'password' => bcrypt('password123'),
            'role' => 'driver'
        ]);

        $response = $this->actingAs($driver, 'sanctum')
            ->postJson('/api/v1/cars', [
                'brand' => 'Toyota',
                'model' => 'Camry',
                'year' => 2018,
                'license_plate' => '8888TJ02'
            ]);

        $response->assertStatus(201)
            ->assertJsonPath('success', true);

        $this->assertDatabaseHas('cars', [
            'driver_id' => $driver->id,
            'license_plate' => '8888TJ02'
        ]);
    }
}
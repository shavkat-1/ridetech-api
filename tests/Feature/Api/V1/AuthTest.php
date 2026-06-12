<?php

namespace Tests\Feature\Api\V1;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use App\Models\User;
use Tests\TestCase;

class AuthTest extends TestCase
{
   use RefreshDatabase;

    /**
     * Тест успешной регистрации
     */
    public function test_user_can_register_successfully(): void
    {
        $response = $this->postJson('/api/v1/auth/register', [
            'name' => 'Шавкат',
            'email' => 'shavkat@example.com',
            'phone' => '+992920000000',
            'password' => 'password123',
            'password_confirmation' => 'password123',
            'role' => 'passenger'
        ]);

        $response->assertStatus(201)
            ->assertJsonPath('success', true)
            ->assertJsonStructure([
                'success',
                'message',
                'data' => ['token', 'user']
            ]);

        $this->assertDatabaseHas('users', ['email' => 'shavkat@example.com']);
    }

    /**
     * Тест успешного входа
     */
    public function test_user_can_login_successfully(): void
    {
        User::create([
            'name' => 'Алишер',
            'email' => 'alisher@example.com',
            'phone' => '+992921111111',
            'password' => bcrypt('password123'),
            'role' => 'driver'
        ]);

        $response = $this->postJson('/api/v1/auth/login', [
            'email' => 'alisher@example.com',
            'password' => 'password123',
        ]);

        $response->assertStatus(200)
            ->assertJsonPath('success', true)
            ->assertJsonStructure([
                'success',
                'data' => ['token']
            ]);
    }
}


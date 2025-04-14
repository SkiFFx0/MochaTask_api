<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthTest extends TestCase
{
    use RefreshDatabase;

    /**
     * A basic feature test example.
     */
    public function test_auth_controller_registration(): void
    {
        $response = $this->postJson('/api/register', [
            'first_name' => 'Test',
            'last_name' => 'Test',
            'middle_name' => 'Test',
            'email' => 'test@test.com',
            'password' => 'password',
        ]);

        $response->assertStatus(200);
        $this->assertDatabaseHas('users', [
            'email' => 'test@test.com',
        ]);
    }

    public function test_auth_controller_login(): void
    {
        User::factory(1)->create([
            'email' => 'test@test.com',
        ]);

        $response = $this->postJson('/api/login', [
            'email' => 'test@test.com',
            'password' => 'password',
        ]);

        $response->assertStatus(200);
    }

    public function test_auth_controller_logout(): void
    {
        User::factory(1)->create([
            'email' => 'test@test.com',
        ]);

        $token = $this->postJson('/api/login', [
            'email' => 'test@test.com',
            'password' => 'password',
        ])->json('data')['token'];

        $response = $this->withHeader('Authorization', 'Bearer ' . $token)
            ->postJson('/api/logout');

        $response->assertStatus(200);
    }
}

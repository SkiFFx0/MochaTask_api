<?php

namespace Tests\Feature\Fail;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthFailTest extends TestCase
{
    use RefreshDatabase;

    public function test_AuthController_login_wrong_credentials(): void
    {
        User::factory()->create();

        $response = $this->postJson('/api/login', [
            'email' => 'test@test.com',
            'password' => 'wrong-password',
        ]);

        $response->assertStatus(401);
    }
}

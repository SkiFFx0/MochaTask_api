<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class UserTest extends TestCase
{
    use RefreshDatabase;

    public function test_UserController_index(): void
    {
        $response = $this->getJson('/api/users');

        $response->assertStatus(200);
    }

    public function test_UserController_show(): void
    {
        $user = User::factory()->create();

        $response = $this->getJson("/api/users/$user->id");

        $response->assertStatus(200);
    }
}

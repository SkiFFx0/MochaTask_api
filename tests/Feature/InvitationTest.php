<?php

namespace Tests\Feature;

use App\Models\Company;
use App\Models\CompanyUser;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class InvitationTest extends TestCase
{
    use RefreshDatabase;

    public function test_InvitationController_invite(): void
    {
        $user = User::factory()->create([
            'email' => 'test@test.com',
        ]);

        $company = Company::factory()->create([
            'name' => 'Test Company',
        ]);

        CompanyUser::factory()->create([
            'user_id' => $user->id,
            'company_id' => $company->id,
            'role' => 'owner',
            'is_privileged' => true,
        ]);

        $response = $this->actingAs($user)
            ->postJson('/api/invitations/create', [
                'company_id' => $company->id,
            ]);

        $response->assertStatus(200);
        $this->assertNotNull($response->json('data')['link']);
    }

    public function test_InvitationController_accept(): void
    {
        $user = User::factory()->create([
            'email' => 'test@test.com',
        ]);

        $company = Company::factory()->create([
            'name' => 'Test Company',
        ]);

        CompanyUser::factory()->create([
            'user_id' => $user->id,
            'company_id' => $company->id,
            'role' => 'owner',
            'is_privileged' => true,
        ]);

        $link = $this->actingAs($user)
            ->postJson('/api/invitations/create', [
                'company_id' => $company->id,
            ])->json('data')['link'];

        $invitee = User::factory()->create([
            'email' => 'member@test.com',
        ]);

        $response = $this->actingAs($invitee)
            ->getJson($link);

        $response->assertStatus(200);
        $this->assertDatabaseHas('company_user', [
            'company_id' => $company->id,
            'user_id' => $invitee->id,
        ]);
    }
}

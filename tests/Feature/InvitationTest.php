<?php

namespace Tests\Feature;

use App\Models\Company;
use App\Models\CompanyUser;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\URL;
use Tests\TestCase;

class InvitationTest extends TestCase
{
    use RefreshDatabase;

    public function test_InvitationController_invite(): void
    {
        $user = User::factory()->create();

        $company = Company::factory()->create();

        CompanyUser::factory()->create([
            'role' => 'owner',
            'is_privileged' => true,
        ]);

        $response = $this->actingAs($user)
            ->postJson("/api/invitations/create/$company->id");

        $response->assertStatus(200);
        $this->assertNotNull($response->json('data')['link']);
    }

    public function test_InvitationController_accept(): void
    {
        $company = Company::factory()->create();

        $link = URL::temporarySignedRoute(
            'invitation.accept',
            now()->addHour(), [
                'company_id' => $company->id,
            ]
        );

        $invitee = User::factory()->create();

        $response = $this->actingAs($invitee)
            ->getJson($link);

        $response->assertStatus(200);
        $this->assertDatabaseHas('company_user', [
            'company_id' => $company->id,
            'user_id' => $invitee->id,
        ]);
    }
}

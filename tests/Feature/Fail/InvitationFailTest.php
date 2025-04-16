<?php

namespace Tests\Feature\Fail;

use App\Models\Company;
use App\Models\CompanyUser;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\URL;
use Tests\TestCase;

class InvitationFailTest extends TestCase
{
    use RefreshDatabase;

    public function test_InvitationController_accept_link_expired(): void
    {
        $company = Company::factory()->create();

        $link = URL::temporarySignedRoute(
            'invitation.accept',
            now()->subMinute(), // link expired 1 minute ago
            ['company_id' => $company->id]
        );

        $invitee = User::factory()->create();

        $response = $this->actingAs($invitee)
            ->getJson($link);

        $response->assertStatus(410);
    }

    public function test_InvitationController_accept_already_in_company(): void
    {
        $company = Company::factory()->create();

        $link = URL::temporarySignedRoute(
            'invitation.accept',
            now()->addHour(), [
                'company_id' => $company->id,
            ]
        );

        $invitee = User::factory()->create();

        CompanyUser::factory()->create([
            'user_id' => $invitee->id,
            'company_id' => $company->id,
            'role' => 'member',
            'is_privileged' => false,
        ]);

        $response = $this->actingAs($invitee)
            ->getJson($link);

        $response->assertStatus(409);
    }
}

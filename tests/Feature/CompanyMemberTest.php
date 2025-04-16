<?php

namespace Tests\Feature;

use App\Models\Company;
use App\Models\CompanyUser;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CompanyMemberTest extends TestCase
{
    use RefreshDatabase;

    public function test_CompanyMemberController_editRole(): void
    {
        $user = User::factory()->create();

        $company = Company::factory()->create();

        CompanyUser::factory()->create([
            'role' => 'owner',
            'is_privileged' => true,
        ]);

        $member = User::factory()->create();

        CompanyUser::factory()->create([
            'company_id' => $company->id,
            'user_id' => $member->id,
            'role' => 'member',
            'is_privileged' => false,
        ]);

        $response = $this->actingAs($user)
            ->patchJson("/api/companies/$company->id/members/$member->id", [
                'role' => 'admin',
            ]);

        $response->assertStatus(200);
        $this->assertDatabaseHas('company_user', [
            'company_id' => $company->id,
            'user_id' => $member->id,
            'role' => 'admin',
            'is_privileged' => true,
        ]);
    }

    public function test_CompanyMemberController_removeUser(): void
    {
        $user = User::factory()->create();

        $company = Company::factory()->create();

        CompanyUser::factory()->create([
            'role' => 'owner',
            'is_privileged' => true,
        ]);

        $member = User::factory()->create();

        CompanyUser::factory()->create([
            'company_id' => $company->id,
            'user_id' => $member->id,
            'role' => 'member',
            'is_privileged' => false,
        ]);

        $response = $this->actingAs($user)
            ->deleteJson("/api/companies/$company->id/members/$member->id");

        $response->assertStatus(200);
        $this->assertDatabaseMissing('company_user', [
            'company_id' => $company->id,
            'user_id' => $member->id,
        ]);
    }
}

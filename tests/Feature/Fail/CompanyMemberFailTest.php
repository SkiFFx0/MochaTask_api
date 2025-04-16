<?php

namespace Tests\Feature\Fail;

use App\Models\Company;
use App\Models\CompanyUser;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CompanyMemberFailTest extends TestCase
{
    use RefreshDatabase;

    public function test_CompanyMemberController_editRole_can_not_edit_yourself(): void
    {
        $user = User::factory()->create();

        $company = Company::factory()->create();

        CompanyUser::factory()->create([
            'role' => 'owner',
            'is_privileged' => true,
        ]);

        $response = $this->actingAs($user)
            ->patchJson("/api/companies/$company->id/members/$user->id", [
                'role' => 'admin',
            ]);

        $response->assertStatus(403);
    }

    public function test_CompanyMemberController_editRole_member_not_found(): void
    {
        $user = User::factory()->create();

        $company = Company::factory()->create();

        CompanyUser::factory()->create([
            'role' => 'owner',
            'is_privileged' => true,
        ]);

        $response = $this->actingAs($user)
            ->patchJson("/api/companies/$company->id/members/288", [
                'role' => 'admin',
            ]);

        $response->assertStatus(404);
    }

    public function test_CompanyMemberController_editRole_can_not_add_owners(): void
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
                'role' => 'owner',
            ]);

        $response->assertStatus(422);
    }

    public function test_CompanyMemberController_removeUser_can_not_remove_yourself(): void
    {
        $user = User::factory()->create();

        $company = Company::factory()->create();

        CompanyUser::factory()->create([
            'role' => 'owner',
            'is_privileged' => true,
        ]);

        $response = $this->actingAs($user)
            ->deleteJson("/api/companies/$company->id/members/$user->id");

        $response->assertStatus(403);
    }

    public function test_CompanyMemberController_removeUser_member_not_found(): void
    {
        $user = User::factory()->create();

        $company = Company::factory()->create();

        CompanyUser::factory()->create([
            'role' => 'owner',
            'is_privileged' => true,
        ]);

        $response = $this->actingAs($user)
            ->deleteJson("/api/companies/$company->id/members/288");

        $response->assertStatus(404);
    }

    public function test_CompanyMemberController_removeUser_can_not_remove_owner(): void
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
            'role' => 'owner',
            'is_privileged' => true,
        ]);

        $response = $this->actingAs($user)
            ->deleteJson("/api/companies/$company->id/members/$member->id");

        $response->assertStatus(422);
    }
}

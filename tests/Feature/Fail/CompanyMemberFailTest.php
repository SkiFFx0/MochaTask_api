<?php

namespace Tests\Feature\Fail;

use App\Models\Company;
use App\Models\CompanyUser;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class CompanyMemberFailTest extends TestCase
{
    use RefreshDatabase;

    public function test_CompanyMemberController_editRole_can_not_edit_yourself(): void
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
            ->patchJson("/api/company-members/$user->id", [
                'company_id' => $company->id,
                'role' => 'admin',
            ]);

        $response->assertStatus(403);
    }

    public function test_CompanyMemberController_editRole_member_not_found(): void
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
            ->patchJson("/api/company-members/228", [
                'company_id' => $company->id,
                'role' => 'admin',
            ]);

        $response->assertStatus(404);
    }

    public function test_CompanyMemberController_editRole_can_not_add_owners(): void
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

        $member = User::factory()->create([
            'email' => 'member@test.com',
        ]);

        CompanyUser::factory()->create([
            'user_id' => $member->id,
            'company_id' => $company->id,
            'role' => 'member',
            'is_privileged' => false,
        ]);

        $response = $this->actingAs($user)
            ->patchJson("/api/company-members/$member->id", [
                'company_id' => $company->id,
                'role' => 'owner',
            ]);

        $response->assertStatus(422);
    }

    public function test_CompanyMemberController_removeUser_can_not_remove_yourself(): void
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
            ->deleteJson("/api/company-members/$user->id", [
                'company_id' => $company->id,
            ]);

        $response->assertStatus(403);
    }

    public function test_CompanyMemberController_removeUser_member_not_found(): void
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
            ->deleteJson("/api/company-members/288", [
                'company_id' => $company->id,
            ]);

        $response->assertStatus(404);
    }

    public function test_CompanyMemberController_removeUser_can_not_remove_owner(): void
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

        $member = User::factory()->create([
            'email' => 'member@test.com',
        ]);

        CompanyUser::factory()->create([
            'user_id' => $member->id,
            'company_id' => $company->id,
            'role' => 'owner',
            'is_privileged' => true,
        ]);

        $response = $this->actingAs($user)
            ->deleteJson("/api/company-members/$member->id", [
                'company_id' => $company->id,
            ]);

        $response->assertStatus(422);
    }
}

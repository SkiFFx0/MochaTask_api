<?php

namespace Tests\Feature;

use App\Models\Company;
use App\Models\CompanyUser;
use App\Models\Project;
use App\Models\Team;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CompanyItemsGetTest extends TestCase
{
    use RefreshDatabase;

    public function test_CompanyMemberController_index(): void
    {
        $user = User::factory()->create();

        $company = Company::factory()->create();

        CompanyUser::factory()->create([
            'user_id' => $user->id,
            'company_id' => $company->id,
            'role' => 'member',
            'is_privileged' => false
        ]);

        User::factory(10)->create();

        Company::factory(10)->create();

        $response = $this->actingAs($user)
            ->getJson("/api/company-members/$company->id");

        $response->assertStatus(200);
    }

    public function test_ProjectController_index(): void
    {
        $user = User::factory()->create();

        $company = Company::factory()->create();

        CompanyUser::factory()->create([
            'role' => 'member',
            'is_privileged' => false,
        ]);

        Project::factory(10)->create();

        $response = $this->actingAs($user)
            ->getJson("/api/projects/$company->id");

        $response->assertStatus(200);
    }

    public function test_TeamController_index(): void
    {
        $user = User::factory()->create();

        $company = Company::factory()->create();

        CompanyUser::factory()->create([
            'role' => 'member',
            'is_privileged' => false,
        ]);

        Team::factory(10)->create();

        $response = $this->actingAs($user)
            ->getJson("/api/teams/$company->id");

        $response->assertStatus(200);
    }
}

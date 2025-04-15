<?php

namespace Tests\Feature;

use App\Models\Company;
use App\Models\CompanyUser;
use App\Models\Project;
use App\Models\Team;
use App\Models\TeamUser;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TeamTest extends TestCase
{
    use RefreshDatabase;

    public function test_TeamController_store(): void
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

        $project = Project::factory()->create([
            'company_id' => $company->id,
            'name' => 'Test Project',
        ]);

        $response = $this->actingAs($user)
            ->postJson("/api/teams/", [
                'company_id' => $company->id,
                'project_id' => $project->id,
                'name' => 'Test Project',
            ]);

        $response->assertStatus(200);
        $this->assertDatabaseHas('teams', [
            'name' => 'Test Project',
        ]);
    }

    public function test_TeamController_update(): void
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

        $project = Project::factory()->create([
            'company_id' => $company->id,
            'name' => 'Test Project',
        ]);

        $team = Team::factory()->create([
            'name' => 'Test Team',
            'project_id' => $project->id,
        ]);

        TeamUser::factory()->create([
            'team_id' => $team->id,
            'user_id' => $user->id,
            'role' => 'admin',
            'is_privileged' => true,
        ]);

        $response = $this->actingAs($user)
            ->patchJson("/api/teams/$team->id", [
                'company_id' => $company->id,
                'project_id' => $project->id,
                'name' => 'Test Project updated',
            ]);

        $response->assertStatus(200);
        $this->assertDatabaseHas('teams', [
            'name' => 'Test Project updated',
        ]);
    }

    public function test_TeamController_destroy(): void
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

        $project = Project::factory()->create([
            'company_id' => $company->id,
            'name' => 'Test Project',
        ]);

        $team = Team::factory()->create([
            'name' => 'Test Team',
            'project_id' => $project->id,
        ]);

        TeamUser::factory()->create([
            'team_id' => $team->id,
            'user_id' => $user->id,
            'role' => 'admin',
            'is_privileged' => true,
        ]);

        $response = $this->actingAs($user)
            ->deleteJson("/api/teams/$team->id", [
                'company_id' => $company->id,
                'project_id' => $project->id,
            ]);

        $response->assertStatus(200);
        $this->assertDatabaseMissing('teams', [
            'name' => 'Test Team',
            'deleted_at' => null,
        ]);
    }
}

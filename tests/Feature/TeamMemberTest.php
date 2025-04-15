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

class TeamMemberTest extends TestCase
{
    use RefreshDatabase;

    public function test_TeamMemberController_addUserWithRole(): void
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
            ->postJson("/api/team-members/$member->id", [
                'company_id' => $company->id,
                'project_id' => $project->id,
                'team_id' => $team->id,
                'role' => 'test',
                'is_privileged' => false,
            ]);

        $response->assertStatus(200);
        $this->assertDatabaseHas('team_user', [
            'team_id' => $team->id,
            'user_id' => $member->id,
        ]);
    }

    public function test_TeamMemberController_editRole(): void
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

        $member = User::factory()->create([
            'email' => 'member@test.com',
        ]);

        CompanyUser::factory()->create([
            'user_id' => $member->id,
            'company_id' => $company->id,
            'role' => 'member',
            'is_privileged' => false,
        ]);

        TeamUser::factory()->create([
            'team_id' => $team->id,
            'user_id' => $member->id,
            'role' => 'member',
            'is_privileged' => false,
        ]);

        $response = $this->actingAs($user)
            ->patchJson("/api/team-members/$member->id", [
                'company_id' => $company->id,
                'project_id' => $project->id,
                'team_id' => $team->id,
                'role' => 'test',
                'is_privileged' => false,
            ]);

        $response->assertStatus(200);
        $this->assertDatabaseHas('team_user', [
            'team_id' => $team->id,
            'user_id' => $member->id,
            'role' => 'test',
            'is_privileged' => false,
        ]);
    }

    public function test_TeamMemberController_removeUser(): void
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

        $member = User::factory()->create([
            'email' => 'member@test.com',
        ]);

        CompanyUser::factory()->create([
            'user_id' => $member->id,
            'company_id' => $company->id,
            'role' => 'member',
            'is_privileged' => false,
        ]);

        TeamUser::factory()->create([
            'team_id' => $team->id,
            'user_id' => $member->id,
            'role' => 'member',
            'is_privileged' => false,
        ]);

        $response = $this->actingAs($user)
            ->deleteJson("/api/team-members/$member->id", [
                'company_id' => $company->id,
                'project_id' => $project->id,
                'team_id' => $team->id,
            ]);

        $response->assertStatus(200);
        $this->assertDatabaseMissing('team_user', [
            'team_id' => $team->id,
            'user_id' => $member->id,
        ]);
    }
}

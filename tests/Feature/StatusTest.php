<?php

namespace Tests\Feature;

use App\Models\Company;
use App\Models\CompanyUser;
use App\Models\Project;
use App\Models\Status;
use App\Models\StatusTeam;
use App\Models\Team;
use App\Models\TeamUser;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class StatusTest extends TestCase
{
    use RefreshDatabase;

    public function test_StatusController_store(): void
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
            ->postJson('/api/statuses', [
                'company_id' => $company->id,
                'project_id' => $project->id,
                'team_id' => $team->id,
                'name' => 'Test Status',
            ]);

        $response->assertStatus(200);
        $this->assertDatabaseHas('statuses', [
            'name' => 'Test Status',
        ]);

        $statusId = Status::where('name', 'Test Status')->first()->id;

        $this->assertDatabaseHas('status_team', [
            'status_id' => $statusId,
            'team_id' => $team->id,
        ]);
    }

    public function test_StatusController_destroy(): void
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

        $status = Status::factory()->create([
            'name' => 'Test Status',
        ]);

        StatusTeam::factory()->create([
            'status_id' => $status->id,
            'team_id' => $team->id,
        ]);

        $response = $this->actingAs($user)
            ->deleteJson("/api/statuses/$status->id", [
                'company_id' => $company->id,
                'project_id' => $project->id,
                'team_id' => $team->id,
            ]);

        $response->assertStatus(200);
        $this->assertDatabaseMissing('status_team', [
            'status_id' => $status->id,
            'team_id' => $team->id,
        ]);
    }
}

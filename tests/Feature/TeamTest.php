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
        $user = User::factory()->create();

        Company::factory()->create();

        CompanyUser::factory()->create([
            'role' => 'owner',
            'is_privileged' => true,
        ]);

        $project = Project::factory()->create();

        $response = $this->actingAs($user)
            ->postJson("/api/projects/$project->id/team/", [
                'name' => 'Test Team',
            ]);

        $response->assertStatus(200);
        $this->assertDatabaseHas('teams', [
            'name' => 'Test Team',
        ]);
    }

    public function test_TeamController_update(): void
    {
        $user = User::factory()->create();

        Company::factory()->create();

        CompanyUser::factory()->create([
            'role' => 'owner',
            'is_privileged' => true,
        ]);

        Project::factory()->create();

        $team = Team::factory()->create();

        TeamUser::factory()->create([
            'role' => 'admin',
            'is_privileged' => true,
        ]);

        $response = $this->actingAs($user)
            ->patchJson("/api/teams/$team->id", [
                'name' => 'Test Team updated',
            ]);

        $response->assertStatus(200);
        $this->assertDatabaseHas('teams', [
            'name' => 'Test Team updated',
        ]);
    }

    public function test_TeamController_destroy(): void
    {
        $user = User::factory()->create();

        Company::factory()->create();

        CompanyUser::factory()->create([
            'role' => 'owner',
            'is_privileged' => true,
        ]);

        Project::factory()->create();

        $team = Team::factory()->create([
            'name' => 'Test Team',
        ]);

        TeamUser::factory()->create([
            'role' => 'admin',
            'is_privileged' => true,
        ]);

        $response = $this->actingAs($user)
            ->deleteJson("/api/teams/$team->id");

        $response->assertStatus(200);
        $this->assertDatabaseMissing('teams', [
            'name' => 'Test Team',
            'deleted_at' => null,
        ]);
    }
}

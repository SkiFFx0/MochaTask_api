<?php

namespace Tests\Feature;

use App\Models\Company;
use App\Models\CompanyUser;
use App\Models\Project;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProjectTest extends TestCase
{
    use RefreshDatabase;

    public function test_ProjectController_store(): void
    {
        $user = User::factory()->create();

        $company = Company::factory()->create();

        CompanyUser::factory()->create([
            'role' => 'owner',
            'is_privileged' => true,
        ]);

        $response = $this->actingAs($user)
            ->postJson("/api/companies/$company->id/project", [
                'name' => 'Test Project',
            ]);

        $response->assertStatus(200);
        $this->assertDatabaseHas('projects', [
            'name' => 'Test Project',
        ]);
    }

    public function test_ProjectController_update(): void
    {
        $user = User::factory()->create();

        $company = Company::factory()->create();

        CompanyUser::factory()->create([
            'role' => 'owner',
            'is_privileged' => true,
        ]);

        $project = Project::factory()->create();

        $response = $this->actingAs($user)
            ->patchJson("/api/projects/$project->id", [
                'name' => 'Test Project updated',
            ]);

        $response->assertStatus(200);
        $this->assertDatabaseHas('projects', [
            'name' => 'Test Project updated',
        ]);
    }

    public function test_ProjectController_destroy(): void
    {
        $user = User::factory()->create();

        $company = Company::factory()->create();

        CompanyUser::factory()->create([
            'role' => 'owner',
            'is_privileged' => true,
        ]);

        $project = Project::factory()->create([
            'name' => 'Test Project',
        ]);

        $response = $this->actingAs($user)
            ->deleteJson("/api/projects/$project->id");

        $response->assertStatus(200);
        $this->assertDatabaseMissing('projects', [
            'name' => 'Test Project',
            'deleted_at' => null,
        ]);
    }
}

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
            ->postJson('/api/projects', [
                'company_id' => $company->id,
                'name' => 'Test Project',
            ]);

        $response->assertStatus(200);
        $this->assertDatabaseHas('projects', [
            'name' => 'Test Project',
        ]);
    }

    public function test_ProjectController_update(): void
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
            ->patchJson("/api/projects/$project->id", [
                'company_id' => $company->id,
                'name' => 'Test Project updated',
            ]);

        $response->assertStatus(200);
        $this->assertDatabaseHas('projects', [
            'name' => 'Test Project updated',
        ]);
    }

    public function test_ProjectController_destroy(): void
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
            ->deleteJson("/api/projects/$project->id", [
                'company_id' => $company->id,
            ]);

        $response->assertStatus(200);
        $this->assertDatabaseMissing('projects', [
            'name' => 'Test Project',
            'deleted_at' => null,
        ]);
    }
}

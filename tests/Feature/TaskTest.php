<?php

namespace Tests\Feature;

use App\Models\Company;
use App\Models\CompanyUser;
use App\Models\File;
use App\Models\Project;
use App\Models\Status;
use App\Models\StatusTeam;
use App\Models\Task;
use App\Models\Team;
use App\Models\TeamUser;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class TaskTest extends TestCase
{
    use RefreshDatabase;

    public function test_TaskController_store(): void
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
            ->postJson('/api/tasks', [
                'company_id' => $company->id,
                'project_id' => $project->id,
                'team_id' => $team->id,
                'name' => 'Test Task',
                'description' => 'Test Description',
                'status' => 'Test Status',
                'user_id' => $user->id,
            ]);

        $response->assertStatus(200);
        $this->assertDatabaseHas('tasks', [
            'name' => 'Test Task',
        ]);
    }

    public function test_TaskController_store_with_files(): void
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

        $files = [
            UploadedFile::fake()->create('file1.pdf', 500),
            UploadedFile::fake()->create('file2.jpg', 300),
        ];

        Storage::fake('public');

        $response = $this->actingAs($user)
            ->postJson('/api/tasks', [
                'company_id' => $company->id,
                'project_id' => $project->id,
                'team_id' => $team->id,
                'name' => 'Test Task',
                'description' => 'Test Description',
                'status' => 'Test Status',
                'user_id' => $user->id,
                'files' => $files,
            ]);

        $response->assertStatus(200);
        $this->assertDatabaseHas('tasks', [
            'name' => 'Test Task',
        ]);
        $this->assertDatabaseHas('files', [
            'name' => 'file1.pdf',
        ]);
        $this->assertDatabaseHas('files', [
            'name' => 'file2.jpg',
        ]);
    }

    public function test_TaskController_update(): void
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

        $task = Task::factory()->create([
            'name' => 'Test Task',
            'description' => 'Test Description',
            'status_id' => $status->id,
            'team_id' => $team->id,
            'user_id' => $user->id,
        ]);

        $response = $this->actingAs($user)
            ->patchJson("/api/tasks/$task->id", [
                'company_id' => $company->id,
                'team_id' => $team->id,
                'name' => 'Test Task updated',
                'description' => 'Test Description updated',
                'user_id' => $user->id,
            ]);

        $response->assertStatus(200);
        $this->assertDatabaseHas('tasks', [
            'name' => 'Test Task updated',
        ]);
    }

    public function test_TaskController_update_with_files(): void
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

        $task = Task::factory()->create([
            'name' => 'Test Task',
            'description' => 'Test Description',
            'status_id' => $status->id,
            'team_id' => $team->id,
            'user_id' => $user->id,
        ]);

        $files = [
            UploadedFile::fake()->create('file1.pdf', 500),
            UploadedFile::fake()->create('file2.jpg', 300),
        ];

        Storage::fake('public');

        $response = $this->actingAs($user)
            ->patchJson("/api/tasks/$task->id", [
                'company_id' => $company->id,
                'team_id' => $team->id,
                'name' => 'Test Task updated',
                'description' => 'Test Description updated',
                'user_id' => $user->id,
                'files' => $files,
            ]);

        $response->assertStatus(200);
        $this->assertDatabaseHas('tasks', [
            'name' => 'Test Task updated',
        ]);
        $this->assertDatabaseHas('files', [
            'name' => 'file1.pdf',
        ]);
        $this->assertDatabaseHas('files', [
            'name' => 'file2.jpg',
        ]);
    }

    public function test_TaskController_update_only_files(): void
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

        $task = Task::factory()->create([
            'name' => 'Test Task',
            'description' => 'Test Description',
            'status_id' => $status->id,
            'team_id' => $team->id,
            'user_id' => $user->id,
        ]);

        $files = [
            UploadedFile::fake()->create('file1.pdf', 500),
            UploadedFile::fake()->create('file2.jpg', 300),
        ];

        Storage::fake('public');

        $response = $this->actingAs($user)
            ->patchJson("/api/tasks/$task->id", [
                'company_id' => $company->id,
                'team_id' => $team->id,
                'name' => 'Test Task updated',
                'description' => 'Test Description updated',
                'user_id' => $user->id,
                'files' => $files,
            ]);

        $response->assertStatus(200);
        $this->assertDatabaseHas('files', [
            'name' => 'file1.pdf',
        ]);
        $this->assertDatabaseHas('files', [
            'name' => 'file2.jpg',
        ]);
    }

    public function test_TaskController_changeStatus(): void
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

        $status2 = Status::factory()->create([
            'name' => 'Test Status 2',
        ]);

        StatusTeam::factory()->create([
            'status_id' => $status2->id,
            'team_id' => $team->id,
        ]);

        $member = User::factory()->create([
            'email' => 'member@test.com',
        ]);

        CompanyUser::factory()->create([
            'user_id' => $member->id,
            'company_id' => $company->id,
            'role' => 'member',
            'is_privileged' => false
        ]);

        TeamUser::factory()->create([
            'team_id' => $team->id,
            'user_id' => $member->id,
            'role' => 'member',
            'is_privileged' => false
        ]);

        $task = Task::factory()->create([
            'name' => 'Test Task',
            'description' => 'Test Description',
            'status_id' => $status->id,
            'team_id' => $team->id,
            'user_id' => $member->id,
        ]);

        $response = $this->actingAs($member)
            ->patchJson("/api/task-status/$task->id", [
                'company_id' => $company->id,
                'project_id' => $project->id,
                'team_id' => $team->id,
                'status' => 'Test Status 2',
            ]);

        $response->assertStatus(200);
        $this->assertDatabaseHas('tasks', [
            'status_id' => $status2->id,
        ]);
    }

    public function test_TaskController_destroy(): void
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

        $task = Task::factory()->create([
            'name' => 'Test Task',
            'description' => 'Test Description',
            'status_id' => $status->id,
            'team_id' => $team->id,
            'user_id' => $user->id,
        ]);

        $response = $this->actingAs($user)
            ->deleteJson("/api/tasks/$task->id", [
                'company_id' => $company->id,
                'project_id' => $project->id,
                'team_id' => $team->id,
            ]);

        $response->assertStatus(200);
        $this->assertDatabaseMissing('tasks', [
            'name' => $task->name,
            'deleted_at' => null,
        ]);
    }

    public function test_FileController_destroy(): void
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

        $task = Task::factory()->create([
            'name' => 'Test Task',
            'description' => 'Test Description',
            'status_id' => $status->id,
            'team_id' => $team->id,
            'user_id' => $user->id,
        ]);

        $file = File::factory()->create([
            'name' => 'file1.pdf',
            'size' => 2048,
            'path' => '/files/file1.pdf',
            'task_id' => $task->id,
        ]);

        $response = $this->actingAs($user)
            ->deleteJson("/api/files/$file->id", [
                'company_id' => $company->id,
                'project_id' => $project->id,
                'team_id' => $team->id,
                'task_id' => $task->id,
            ]);

        $response->assertStatus(200);
        $this->assertDatabaseMissing('files', [
            'name' => $file->name,
            'deleted_at' => null,
        ]);
    }
}

<?php

namespace App\Http\Controllers;

use App\Http\Requests\Project\StoreRequest;
use App\Models\ApiResponse;
use App\Models\Company;
use App\Models\Project;
use App\Models\ProjectRole;
use App\Models\ProjectUser;
use App\Models\Role;

class ProjectController extends Controller
{
    public function store(StoreRequest $request, Company $company)
    {
        $storeData = $request->validated();

        $companyId = $company->id;

        $project = Project::query()->create([
            'name' => $storeData['name'],
            'company_id' => $companyId,
        ]);
        $projectId = $project->id;

        $user = auth()->user();
        $userId = $user->id;

        $role = Role::query()->where('id', 1)->firstOrFail(['name']);

        $roleName = $role['name'];

        ProjectRole::query()->insert([
            ['project_id' => $projectId, 'role_id' => 1],
            ['project_id' => $projectId, 'role_id' => 2],
        ]);

        // Assign project creator as admin
        ProjectUser::setProjectUserRole($projectId, $userId, $roleName);

        return ApiResponse::success('Project created successfully', [
            'project' => $project
        ]);
    }

    public function update(StoreRequest $request, Company $company, Project $project)
    {
        $updateData = $request->validated();

        $project->update($updateData);

        return ApiResponse::success('Project updated successfully', [
            'project' => $project
        ]);
    }

    public function destroy(Company $company, Project $project)
    {
        $project->delete();

        return ApiResponse::success('Project deleted successfully');
    }
}

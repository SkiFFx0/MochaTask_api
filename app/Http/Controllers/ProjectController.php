<?php

namespace App\Http\Controllers;

use App\Enums\ProjectRole;
use App\Http\Requests\Project\StoreRequest;
use App\Models\ApiResponse;
use App\Models\Company;
use App\Models\Project;
use App\Models\ProjectUser;
use App\Models\Role;
use DB;

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

        $role = Role::query()->where('id', 1)->firstOrFail();

        // Assign creator as owner
        ProjectUser::setProjectUserRole($projectId, $userId, $role);

        return ApiResponse::success('Project created successfully', [
            'project' => $project
        ]);
    }

    public function update(StoreRequest $request, Project $project)
    {
        $updateData = $request->validated();

        $project->update($updateData);

        return ApiResponse::success('Project updated successfully', [
            'project' => $project
        ]);
    }

    public function destroy(Project $project)
    {
        $project->delete();

        return ApiResponse::success('Project deleted successfully');
    }
}

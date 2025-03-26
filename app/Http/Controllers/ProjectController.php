<?php

namespace App\Http\Controllers;

use App\Http\Requests\Project\StoreRequest;
use App\Models\ApiResponse;
use App\Models\Company;
use App\Models\Project;

class ProjectController extends Controller
{
    public function store(StoreRequest $request, Company $company)
    {
        $storeData = $request->validated();

        $project = Project::query()->create($storeData);

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

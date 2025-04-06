<?php

namespace App\Http\Controllers;

use App\Helpers\ApiResponse;
use App\Http\Requests\Project\StoreRequest;
use App\Http\Requests\Project\UpdateRequest;
use App\Models\Project;

class ProjectController extends Controller
{
    public function store(StoreRequest $request)
    {
        $validated = $request->validated();

        $companyId = $request->company_id;
        $project = Project::query()->create([
            'name' => $validated['name'],
            'company_id' => $companyId,
        ]);

        return ApiResponse::success('Project created successfully', [
            'project' => $project
        ]);
    }

    public function update(UpdateRequest $request, Project $project)
    {
        $validated = $request->validated();

        $project->update($validated);

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

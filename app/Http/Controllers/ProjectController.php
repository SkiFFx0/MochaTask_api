<?php

namespace App\Http\Controllers;

use App\Helpers\ApiResponse;
use App\Http\Requests\Project\StoreUpdateRequest;
use App\Models\Company;
use App\Models\Project;
use Illuminate\Http\Request;

class ProjectController extends Controller
{
    public function index(Company $company)
    {
        $companyName = $company->name;

        $companyProjects = $company->projects()->get();

        return ApiResponse::success("Projects inside $companyName company", [
            'Company projects' => $companyProjects
        ]);
    }

    public function store(StoreUpdateRequest $request, Company $company)
    {
        $validated = $request->validated();

        $project = Project::create([
            'name' => $validated['name'],
            'company_id' => $company->id]);

        return ApiResponse::success('Project created successfully', [
            'project' => $project
        ]);
    }

    public function update(StoreUpdateRequest $request, Project $project)
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

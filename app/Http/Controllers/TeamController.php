<?php

namespace App\Http\Controllers;

use App\Http\Requests\Team\StoreRequest;
use App\Http\Requests\Team\UpdateRequest;
use App\Models\ApiResponse;
use App\Models\Company;
use App\Models\Project;
use App\Models\Team;
use Illuminate\Http\Request;

class TeamController extends Controller
{
    public function index(Company $company, Project $project)
    {
        $teams = Team::all();

        return ApiResponse::success('Teams', [
            'teams' => $teams
        ]);
    }

    public function show(Company $company, Project $project, Team $team)
    {
        return ApiResponse::success('Team', [
            'team' => $team
        ]);
    }

    public function store(StoreRequest $request, Company $company, Project $project)
    {
        $storeData = $request->validated();
        $projectId = $project->id;

        $team = Team::query()->create([
            'name' => $storeData['name'],
            'project_id' => $projectId,
        ]);

        return ApiResponse::success('Team created successfully', [
            'team' => $team
        ]);
    }

    public function update(UpdateRequest $request, Company $company, Project $project, Team $team)
    {
        $updateData = $request->validated();

        $team->update($updateData);

        return ApiResponse::success('Team updated successfully', [
            'team' => $team
        ]);
    }

    public function destroy(Company $company, Project $project, Team $team)
    {
        $team->delete();

        return ApiResponse::success('Team deleted successfully');
    }
}

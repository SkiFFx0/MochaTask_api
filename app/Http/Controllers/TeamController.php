<?php

namespace App\Http\Controllers;

use App\Http\Requests\Team\StoreRequest;
use App\Http\Requests\Team\UpdateRequest;
use App\Models\ApiResponse;
use App\Models\Company;
use App\Models\Project;
use App\Models\Role;
use App\Models\RoleTeam;
use App\Models\Team;
use App\Models\TeamUser;

class TeamController extends Controller
{
    public function store(StoreRequest $request, Company $company, Project $project)
    {
        $storeData = $request->validated();
        $projectId = $project->id;

        $team = Team::query()->create([
            'name' => $storeData['name'],
            'project_id' => $projectId,
        ]);
        $teamId = $team->id;

        $user = auth()->user();
        $userId = $user->id;

        // Assign project creator as admin
        $role = Role::query()->where('id', 1)->firstOrFail(['name']);
        $roleName = $role['name'];

        RoleTeam::query()->insert([
            ['role_id' => 1, 'team_id' => $teamId, 'created_at' => now(), 'updated_at' => now()],
            ['role_id' => 2, 'team_id' => $teamId, 'created_at' => now(), 'updated_at' => now()],
        ]);

        TeamUser::setTeamUserRole($teamId, $userId, $roleName);

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

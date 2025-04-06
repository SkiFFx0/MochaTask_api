<?php

namespace App\Http\Controllers;

use App\Http\Requests\Team\StoreRequest;
use App\Http\Requests\Team\UpdateRequest;
use App\Models\ApiResponse;
use App\Models\Project;
use App\Models\Role;
use App\Models\RoleTeam;
use App\Models\Team;
use App\Models\TeamUser;

class TeamController extends Controller
{
    public function store(StoreRequest $request)
    {
        $validated = $request->validated();

        $user = auth()->user();
        $userId = $user->id;
        $projectId = $request->project_id;

        //Check if team being created on project is in company
        $companyId = $request->company_id;
        $isProjectInCompany = Project::query()
            ->where('id', $projectId)
            ->where('company_id', $companyId)
            ->exists();
        if (!$isProjectInCompany)
        {
            return ApiResponse::error('Failed to create team in this project');
        }

        $team = Team::query()->create([
            'name' => $validated['name'],
            'project_id' => $projectId,
        ]);
        $teamId = $team->id;

        RoleTeam::setRoleTeam(1, $teamId);
        RoleTeam::setRoleTeam(2, $teamId);

        // Assign project creator as admin
        $role = Role::query()->where('id', 1)->firstOrFail(['name']);
        $roleName = $role['name'];
        TeamUser::setTeamUserRole($teamId, $userId, $roleName);

        return ApiResponse::success('Team created successfully', [
            'team' => $team
        ]);
    }

    public function update(UpdateRequest $request, Team $team)
    {
        $validated = $request->validated();

        $team->update($validated);

        return ApiResponse::success('Team updated successfully', [
            'team' => $team
        ]);
    }

    public function destroy(Team $team)
    {
        $team->delete();

        return ApiResponse::success('Team deleted successfully');
    }
}

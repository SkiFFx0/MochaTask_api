<?php

namespace App\Http\Controllers;

use App\Helpers\ApiResponse;
use App\Http\Requests\Team\StoreRequest;
use App\Http\Requests\Team\UpdateRequest;
use App\Models\Role;
use App\Models\RoleTeam;
use App\Models\Team;
use App\Models\TeamUser;
use Illuminate\Support\Facades\DB;

class TeamController extends Controller
{
    public function store(StoreRequest $request)
    {
        $validated = $request->validated();

        $team = DB::transaction(function () use ($request, $validated)
        {
            $user = auth()->user();
            $userId = $user->id;
            $projectId = $request->project_id;

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

            return $team;
        });

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

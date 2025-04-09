<?php

namespace App\Http\Controllers;

use App\Helpers\ApiResponse;
use App\Http\Requests\Team\TeamRequest;
use App\Models\RoleTeam;
use App\Models\StatusTeam;
use App\Models\Team;
use App\Models\TeamUser;
use Illuminate\Support\Facades\DB;

class TeamController extends Controller
{
    public function store(TeamRequest $request)
    {
        $validated = $request->validated();

        $team = DB::transaction(function () use ($request, $validated)
        {
            $userId = auth()->user()->id;
            $projectId = $request->project_id;

            $team = Team::query()->create([
                'name' => $validated['name'],
                'project_id' => $projectId,
            ]);
            $teamId = $team->id;

            TeamUser::setTeamUserRole($teamId, $userId, 'admin', true);
            StatusTeam::assignDefaultStatuses($teamId);

            return $team;
        });

        return ApiResponse::success('Team created successfully', [
            'team' => $team
        ]);
    }

    public function update(TeamRequest $request, Team $team)
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

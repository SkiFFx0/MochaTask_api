<?php

namespace App\Http\Controllers;

use App\Helpers\ApiResponse;
use App\Http\Requests\Team\TeamRequest;
use App\Models\Company;
use App\Models\Project;
use App\Models\StatusTeam;
use App\Models\Team;
use App\Models\TeamUser;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class TeamController extends Controller
{
    public function index(Company $company)
    {
        $companyName = $company->name;

        $companyTeams = $company->teams()->get();

        return ApiResponse::success("Teams inside $companyName company", [
            'Company teams' => $companyTeams
        ]);
    }

    public function store(TeamRequest $request, Project $project)
    {
        $validated = $request->validated();

        $team = DB::transaction(function () use ($request, $project, $validated)
        {
            $userId = auth()->user()->id;

            $team = Team::query()->create([
                'name' => $validated['name'],
                'project_id' => $project->id,
            ]);

            TeamUser::setTeamUserRole($team->id, $userId, 'admin', true);
            StatusTeam::assignDefaultStatuses($team->id);

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

<?php

namespace App\Http\Controllers;

use App\Helpers\ApiResponse;
use App\Http\Requests\TeamMember\RoleRequest;
use App\Models\Project;
use App\Models\Team;
use App\Models\TeamUser;
use App\Models\User;
use Illuminate\Http\Request;

class TeamMemberController extends Controller
{
    public function index(Request $request)
    {
        $teamId = $request->team_id;
        $team = Team::find($teamId);
        $teamName = $team->name;

        $teamMembers = $team->users()->get();

        return ApiResponse::success("Members inside $teamName team", [
            'Team members' => $teamMembers
        ]);
    }

    public function addUserWithRole(RoleRequest $request, User $user)
    {
        $validated = $request->validated();

        $loggedInUserId = auth()->user()->id;

        $userId = $user->id;

        if ($loggedInUserId == $userId)
        {
            return ApiResponse::error('You can\'t add yourself as a team member');
        }

        $companyId = $request->company_id;
        $companyIds = $user->companies()->distinct()->pluck('company_id')->toArray();

        if (!in_array($companyId, $companyIds))
        {
            return ApiResponse::error('User is not in the company');
        }

        $projectIds = Project::whereIn('company_id', $companyIds)->pluck('id')->toArray();
        $teamId = $request->team_id;
        $teamIds = $user->teams()->whereIn('project_id', $projectIds)->distinct()->pluck('team_id')->toArray();

        if (in_array($teamId, $teamIds))
        {
            return ApiResponse::error('This user is already a member of this team');
        }

        TeamUser::setTeamUserRole($teamId, $userId, $validated['role'], $validated['is_privileged']);

        return ApiResponse::success('User added successfully', [
            'user' => $user,
        ]);
    }

    public function editRole(RoleRequest $request, User $user)
    {
        $validated = $request->validated();

        $loggedInUserId = auth()->user()->id;

        $userId = $user->id;

        if ($loggedInUserId == $userId)
        {
            return ApiResponse::error('You can\'t edit your own role');
        }

        $companyIds = $user->companies()->distinct()->pluck('company_id')->toArray();
        $projectIds = Project::whereIn('company_id', $companyIds)->pluck('id')->toArray();
        $teamId = $request->team_id;
        $teamIds = $user->teams()->whereIn('project_id', $projectIds)->distinct()->pluck('team_id')->toArray();

        if (!in_array($teamId, $teamIds))
        {
            return ApiResponse::error('This user is not a member of this team');
        }

        $isDuplicate = TeamUser::query()
            ->where('team_id', $teamId)
            ->where('user_id', $userId)
            ->where('role', $validated['role'])
            ->where('is_privileged', $validated['is_privileged'])
            ->exists();

        if ($isDuplicate)
        {
            return ApiResponse::error('This record already exists');
        }

        TeamUser::query()
            ->where('user_id', $userId)
            ->where('team_id', $teamId)
            ->update($validated);

        return ApiResponse::success('Role edited successfully', [
            'user' => $user,
        ]);
    }

    public function removeUser(Request $request, User $user)
    {
        $loggedInUserId = auth()->user()->id;

        $userId = $user->id;

        if ($loggedInUserId == $userId)
        {
            return ApiResponse::error('You can\'t remove yourself');
        }

        $companyIds = $user->companies()->distinct()->pluck('company_id')->toArray();
        $projectIds = Project::whereIn('company_id', $companyIds)->pluck('id')->toArray();
        $teamId = $request->team_id;
        $teamIds = $user->teams()->whereIn('project_id', $projectIds)->distinct()->pluck('team_id')->toArray();

        if (!in_array($teamId, $teamIds))
        {
            return ApiResponse::error('This user is not a member of this team');
        }

        TeamUser::unsetTeamUser($teamId, $userId);

        return ApiResponse::success('User deleted successfully');
    }
}

<?php

namespace App\Http\Controllers;

use App\Helpers\ApiResponse;
use App\Models\Project;
use App\Models\TeamUser;
use App\Models\User;
use Illuminate\Http\Request;

class TeamMemberController extends Controller
{
    public function addUser(Request $request)
    {
        $loggedInUserId = auth()->user()->id;

        $userId = $request->user_id;

        if (!$loggedInUserId == $userId)
        {
            return ApiResponse::error('You can\'t add yourself as a team member');
        }

        $user = User::find($userId);

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

        $role = 'member';
        TeamUser::setTeamUserRole($teamId, $userId, $role);

        return ApiResponse::success('User added successfully', [
            'user' => $user,
        ]);
    }
}

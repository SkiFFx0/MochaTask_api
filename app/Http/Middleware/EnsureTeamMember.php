<?php

namespace App\Http\Middleware;

use App\Models\ApiResponse;
use App\Models\CompanyUser;
use App\Models\Team;
use App\Models\TeamUser;
use Closure;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureTeamMember
{
    use AuthorizesRequests;

    /**
     * Handle an incoming request.
     *
     * @param \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response) $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = auth()->user();
        $userId = $user->id;
        $companyId = $request->company_id;
        $teamId = $request->team === null ? $request->team_id : $request->team->id;

        $userInCompany = CompanyUser::query()
            ->where('company_id', $companyId)
            ->where('user_id', $userId)
            ->exists();

        if (!$userInCompany)
        {
            return ApiResponse::error('You are not member of this company');
        }

        $userInTeam = TeamUser::query()
            ->where('team_id', $teamId)
            ->where('user_id', $userId)
            ->exists();

        if (!$userInTeam)
        {
            $userInCompanyPrivileged = CompanyUser::query()
                ->where('company_id', $companyId)
                ->where('user_id', $userId)
                ->privileged()
                ->exists();

            if (!$userInCompanyPrivileged)
            {
                return ApiResponse::error('You are not privileged in this company to perform this action');
            }

            $team = Team::find($teamId);
            $teamInCompany = false;
            if ($team && $team->project && $team->project->company_id === $companyId)
            {
                $teamInCompany = true;
            }

            if ($userInCompanyPrivileged && !$teamInCompany)
            {
                return ApiResponse::error('Team not found');
            }

            if ($userInCompanyPrivileged && $teamInCompany)
            {
                $request->attributes->set('userInCompanyPrivileged', true);
                return $next($request);
            }

            return ApiResponse::error('You are not member of this team');
        }

        return $next($request);
    }
}

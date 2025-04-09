<?php

namespace App\Http\Middleware;

use App\Helpers\ApiResponse;
use App\Models\Team;
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
        $companyId = $request->company_id;
        $teamId = $request->team === null ? $request->team_id : $request->team->id;

        $teamAccessIds = $request->attributes->get('team_access_ids');

        if (!in_array($teamId, $teamAccessIds))
        {
            $companyPrivilegedIds = $request->attributes->get('company_privileged_ids');
            $team = Team::find($teamId);

            if (!in_array($companyId, $companyPrivilegedIds) || !$team)
            {
                return ApiResponse::error('You are not member of this team');
            }

            $request->attributes->set('company_privileged', true);
        }

        $teamPrivilegedIds = $request->attributes->get('team_privileged_ids');

        if (in_array($teamId, $teamPrivilegedIds))
        {
            $request->attributes->set('team_privileged', true);
        }

        return $next($request);
    }
}

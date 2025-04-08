<?php

namespace App\Http\Middleware;

use App\Helpers\ApiResponse;
use App\Models\CompanyUser;
use App\Models\Team;
use App\Models\TeamUser;
use Closure;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

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

        $teamIds = $request->attributes->get('team_ids');

        if (!in_array($teamId, $teamIds))
        {
            $companyPrivilegedIds = $request->attributes->get('company_privileged_ids');
            $team = Team::find($teamId);

            if (!in_array($companyId, $companyPrivilegedIds) || !$team)
            {
                return ApiResponse::error('You are not member of this team');
            }

            $request->attributes->set('company_privileged', true);
        }

        return $next($request);
    }
}

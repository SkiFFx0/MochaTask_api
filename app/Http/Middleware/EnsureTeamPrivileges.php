<?php

namespace App\Http\Middleware;

use App\Helpers\ApiResponse;
use App\Models\TeamUser;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureTeamPrivileges
{
    /**
     * Handle an incoming request.
     *
     * @param \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response) $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $teamId = $request->team === null ? $request->team_id : $request->team->id;

        $teamPrivilegedIds = $request->attributes->get('team_privileged_ids');

        if ($request->attributes->get('company_privileged', false))
        {
            return $next($request);
        }

        if (!in_array($teamId, $teamPrivilegedIds))
        {
            return ApiResponse::error('You are not privileged in this team to perform this action');
        }

        return $next($request);
    }
}

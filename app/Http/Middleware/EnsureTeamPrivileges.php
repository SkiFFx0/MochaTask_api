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
     * @param Closure(Request): (Response) $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $userId = auth()->user()->id;

        $userInCompanyPrivileged = $request->attributes->get('user_in_company_privileged', false);

        if (!$userInCompanyPrivileged)
        {
            $teamId = $request->attributes->get('team_id');

            $userInTeamPrivileged = TeamUser::where('team_id', $teamId)
                ->where('user_id', $userId)
                ->privileged()
                ->first();

            if ($userInTeamPrivileged)
            {
                return $next($request);
            }

            return ApiResponse::error('You are not privileged in this company', null, 403);
        }

        return $next($request);
    }
}

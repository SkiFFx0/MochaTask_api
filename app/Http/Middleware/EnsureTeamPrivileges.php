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
        $user = auth()->user();
        $userId = $user->id;
        $teamId = $request->team === null ? $request->team_id : $request->team->id;

        $userInTeamPrivileged = TeamUser::query()
            ->where('team_id', $teamId)
            ->where('user_id', $userId)
            ->privileged()
            ->exists();

        if (!$userInTeamPrivileged)
        {
            if ($request->attributes->get('userInCompanyPrivileged', false))
            {
                return $next($request);
            }

            return ApiResponse::error('You are not privileged in this team to perform this action');
        }

        return $next($request);
    }
}

<?php

namespace App\Http\Middleware;

use App\Models\ApiResponse;
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

        $team = $request->team;

        if (!$team instanceof Team)
        {
            $team = Team::query()->findOrFail($team);
        }

        $teamId = $team->id;

        $membership = TeamUser::query()->where([
            ['team_id', $teamId],
            ['user_id', $userId]
        ])->exists();

        if (!$membership)
        {
            $company = $request->company;
            $companyPrivileged = $this->authorize('manage', $company);

            if ($companyPrivileged->allowed())
            {
                return $next($request);
            }

            return ApiResponse::error('You are not member of this team');
        }

        return $next($request);
    }
}

<?php

namespace App\Http\Middleware;

use App\Helpers\ApiResponse;
use App\Models\CompanyUser;
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
     * @param Closure(Request): (Response) $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $userId = auth()->user()->id;
        $companyId = $request->attributes->get('company_id');

        $userInCompanyPrivileged = CompanyUser::where('company_id', $companyId)
            ->where('user_id', $userId)
            ->privileged()
            ->first();

        if (!$userInCompanyPrivileged)
        {
            $teamId = $request->attributes->get('team_id');

            $userInTeam = TeamUser::where('team_id', $teamId)
                ->where('user_id', $userId)
                ->first();

            if ($userInTeam)
            {
                return $next($request);
            }

            return ApiResponse::error('You are not privileged in this company', null, 403);
        }

        $request->attributes->set('user_in_company_privileged', true);

        return $next($request);
    }
}

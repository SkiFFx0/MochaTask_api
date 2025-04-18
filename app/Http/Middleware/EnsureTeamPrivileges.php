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
        $pass = $request->attributes->get('company_privileged', false) || $request->attributes->get('team_privileged', false);

        if (!$pass)
        {
            return ApiResponse::error('You are not privileged in this team to perform this action');
        }

        return $next($request);
    }
}

<?php

namespace App\Http\Middleware;

use App\Models\RoleTeam;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class EnsureRoleOwnership
{
    /**
     * Handle an incoming request.
     *
     * @param \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response) $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $teamId = $request->team_id;
        $role = $request->role;
        $roleId = $role->id;

        $roleIsDefault = $role->is_default;

        $roleInTeam = RoleTeam::query()
            ->where('role_id', $roleId)
            ->where('team_id', $teamId)
            ->exists();

        if ($roleIsDefault || !$roleInTeam)
        {
            throw new NotFoundHttpException();
        }

        return $next($request);
    }
}

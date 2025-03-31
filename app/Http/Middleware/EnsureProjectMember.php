<?php

namespace App\Http\Middleware;

use App\Models\ApiResponse;
use App\Models\Project;
use App\Models\ProjectUser;
use Closure;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureProjectMember
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

        $project = $request->project;

        if (!$project instanceof Project)
        {
            $project = Project::query()->findOrFail($project);
        }

        $projectId = $project->id;

        $membership = ProjectUser::query()->where([
            ['project_id', $projectId],
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

            return ApiResponse::error('You are not member of this project');
        }

        return $next($request);
    }
}

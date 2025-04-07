<?php

namespace App\Http\Middleware;

use App\Models\CompanyUser;
use App\Models\File;
use App\Models\Project;
use App\Models\Role;
use App\Models\RoleTeam;
use App\Models\Task;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AssignAttributes
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = auth()->user();
        $userId = $user->id;
        $companyIds = $user->companies()->distinct()->pluck('company_id')->toArray();
        $projectIds = Project::whereIn('company_id', $companyIds)->pluck('id')->toArray();
        $teamIds = $user->teams()->whereIn('project_id', $projectIds)->distinct()->pluck('team_id')->toArray();
        $roleIds = RoleTeam::whereIn('team_id', $teamIds)->distinct()->pluck('role_id')->toArray();
        $taskIds = Task::whereIn('team_id', $teamIds)->pluck('id')->toArray();
        $fileIds = File::whereIn('task_id', $taskIds)->pluck('id')->toArray();

        $request->attributes->set('user_id', $userId);
        $request->attributes->set('company_ids', $companyIds);
        $request->attributes->set('project_ids', $projectIds);
        $request->attributes->set('team_ids', $teamIds);
        $request->attributes->set('role_ids', $roleIds);
        $request->attributes->set('task_ids', $taskIds);
        $request->attributes->set('file_ids', $fileIds);

        return $next($request);
    }
}

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

        $companyId = $request->company === null ? $request->company_id : $request->company->id;

        $companyIds = $user->companies()->distinct()->pluck('company_id')->toArray();
        $companyPrivilegedIds = $user->companies()->where('is_privileged', true)->distinct()->pluck('company_id')->toArray();
        $projectIds = Project::whereIn('company_id', $companyIds)->pluck('id')->toArray();
        $projectAccessIds = Project::where('company_id', $companyId)->pluck('id')->toArray();
        $teamIds = $user->teams()->whereIn('project_id', $projectIds)->distinct()->pluck('team_id')->toArray();
        $teamPrivilegedIds = $user->teams()->whereIn('project_id', $projectIds)->where('is_privileged', true)->distinct()->pluck('team_id')->toArray();
        $roleIds = RoleTeam::whereIn('team_id', $teamIds)->distinct()->pluck('role_id')->toArray();
        $taskIds = Task::whereIn('team_id', $teamIds)->pluck('id')->toArray();
        $fileIds = File::whereIn('task_id', $taskIds)->pluck('id')->toArray();

        $request->attributes->set('company_ids', $companyIds); //Companies user is member of
        $request->attributes->set('company_privileged_ids', $companyPrivilegedIds); //Companies where user is privileged
        $request->attributes->set('project_ids', $projectIds); //Projects which user can get
        $request->attributes->set('project_access_ids', $projectAccessIds); //Projects which user can manage, company_id being used
        $request->attributes->set('team_ids', $teamIds); //Teams user is member of
        $request->attributes->set('team_privileged_ids', $teamPrivilegedIds); //Teams where user is privileged
        $request->attributes->set('role_ids', $roleIds);
        $request->attributes->set('task_ids', $taskIds);
        $request->attributes->set('file_ids', $fileIds);

        return $next($request);
    }
}

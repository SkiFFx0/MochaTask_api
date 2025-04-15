<?php

namespace App\Http\Middleware;

use App\Models\File;
use App\Models\Project;
use App\Models\StatusTeam;
use App\Models\Task;
use App\Models\Team;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AssignAttributes
{
    /**
     * Handle an incoming request.
     *
     * @param \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response) $next
     */
    public function handle(Request $request, Closure $next): Response
    {//TODO bad implementation, delete this middleware and refactor whole project
        $user = auth()->user();

        $companyId = $request->company === null ? $request->company_id : $request->company->id;
        $projectId = $request->project === null ? $request->project_id : $request->project->id;
        $teamId = $request->team === null ? $request->team_id : $request->team->id;
        $taskId = $request->task === null ? $request->task_id : $request->task->id;

        $companyIds = $user->companies()->distinct()->pluck('company_id')->toArray();
        $companyPrivilegedIds = $user->companies()->where('is_privileged', true)->distinct()->pluck('company_id')->toArray();
        $projectIds = Project::whereIn('company_id', $companyIds)->pluck('id')->toArray();
        $projectAccessIds = Project::where('company_id', $companyId)->pluck('id')->toArray();
        $teamIds = $user->teams()->whereIn('project_id', $projectIds)->distinct()->pluck('team_id')->toArray();
        $teamAccessIds = Team::where('project_id', $projectId)->pluck('id')->toArray();
        $teamPrivilegedIds = $user->teams()->whereIn('project_id', $projectIds)->where('is_privileged', true)->distinct()->pluck('team_id')->toArray();
        $statusIds = StatusTeam::whereIn('team_id', $teamIds)->pluck('id')->toArray();
        $statusAccessIds = StatusTeam::where('team_id', $teamId)->pluck('id')->toArray();
        $taskIds = Task::whereIn('team_id', $teamIds)->pluck('id')->toArray();
        $taskAccessIds = Task::where('team_id', $teamId)->pluck('id')->toArray();
        $fileIds = File::whereIn('task_id', $taskIds)->pluck('id')->toArray();
        $fileAccessIds = File::where('task_id', $taskId)->pluck('id')->toArray();

        $request->attributes->set('company_ids', $companyIds); //Companies user is member of
        $request->attributes->set('company_privileged_ids', $companyPrivilegedIds); //Companies where user is privileged
        $request->attributes->set('project_ids', $projectIds); //Projects which user can access
        $request->attributes->set('project_access_ids', $projectAccessIds); //Projects which user can access with current company_id being used
        $request->attributes->set('team_ids', $teamIds); //Teams user is member of
        $request->attributes->set('team_access_ids', $teamAccessIds); //Teams which user can access with current company_id being used
        $request->attributes->set('team_privileged_ids', $teamPrivilegedIds); //Teams where user is privileged
        $request->attributes->set('status_ids', $statusIds); //Statuses which user cam access
        $request->attributes->set('status_access_ids', $statusAccessIds); //Statuses which user can access with current team_id being used
        $request->attributes->set('task_ids', $taskIds); //Tasks which user can access
        $request->attributes->set('task_access_ids', $taskAccessIds); //Tasks which user can access with current team_id being used
        $request->attributes->set('file_ids', $fileIds); //Files which user can access
        $request->attributes->set('file_access_ids', $fileAccessIds); //Files which user can access with current task_id being used

        return $next($request);
    }
}

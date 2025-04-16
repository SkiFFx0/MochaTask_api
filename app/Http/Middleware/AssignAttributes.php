<?php

namespace App\Http\Middleware;

use App\Helpers\ApiResponse;
use App\Models\Company;
use App\Models\File;
use App\Models\Project;
use App\Models\Status;
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
     * @param Closure(Request): (Response) $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $resource = $request->route('file')
            ?? $request->route('task')
            ?? $request->route('status')
            ?? $request->route('team')
            ?? $request->route('project')
            ?? $request->route('company');

        if (!$resource) {
            return $next($request);
        }

        // Init all to null
        $companyId = $projectId = $teamId = $statusId = $taskId = $fileId = null;

        if ($resource instanceof File) {
            $fileId = $resource->id;
            $resource->load('task.status', 'task.team.project.company');
            $task = $resource->task;
            $taskId = $task->id ?? null;
            $statusId = $task->status->id ?? null;
            $team = $task->team ?? null;
            $teamId = $team->id ?? null;
            $project = $team?->project;
            $projectId = $project->id ?? null;
            $companyId = $project?->company?->id;
        }
        elseif ($resource instanceof Task) {
            $taskId = $resource->id;
            $resource->load('status', 'team.project.company');
            $statusId = $resource->status->id ?? null;
            $team = $resource->team ?? null;
            $teamId = $team->id ?? null;
            $project = $team?->project;
            $projectId = $project->id ?? null;
            $companyId = $project?->company?->id;
        }
        elseif ($resource instanceof Status) {
            $statusId = $resource->id;

            // Load teams and try to use first one to climb up
            $resource->load('teams.project.company');

            // Only use first team (if any) for climbing
            $team = $resource->teams->first();
            if ($team) {
                $teamId = $team->id;
                $project = $team->project;
                $projectId = $project->id ?? null;
                $companyId = $project?->company?->id;
            }
        }
        elseif ($resource instanceof Team) {
            $teamId = $resource->id;
            $resource->load('project.company');
            $project = $resource->project;
            $projectId = $project->id ?? null;
            $companyId = $project?->company?->id;
        }
        elseif ($resource instanceof Project) {
            $projectId = $resource->id;
            $resource->load('company');
            $companyId = $resource->company?->id;
        }
        elseif ($resource instanceof Company) {
            $companyId = $resource->id;
        }

        $request->attributes->set('company_id', $companyId);
        $request->attributes->set('project_id', $projectId);
        $request->attributes->set('team_id', $teamId);
        $request->attributes->set('status_id', $statusId);
        $request->attributes->set('task_id', $taskId);
        $request->attributes->set('file_id', $fileId);

        return $next($request);
    }
}

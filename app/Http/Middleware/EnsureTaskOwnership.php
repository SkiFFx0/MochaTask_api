<?php

namespace App\Http\Middleware;

use App\Models\Task;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class EnsureTaskOwnership
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $teamId = $request->team_id;
        $taskId = $request->task->id;

        $taskInTeam = Task::query()
            ->where('id', $taskId)
            ->where('team_id', $teamId)
            ->exists();

        if (!$taskInTeam)
        {
            throw new NotFoundHttpException();
        }

        return $next($request);
    }
}

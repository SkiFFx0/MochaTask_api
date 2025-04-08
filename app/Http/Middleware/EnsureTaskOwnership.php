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
     * @param \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response) $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $taskId = $request->task === null ? $request->task_id : $request->task->id;
        $taskAccessIds = $request->attributes->get('task_access_ids');

        if (!in_array($taskId, $taskAccessIds))
        {
            throw new NotFoundHttpException();
        }

        return $next($request);
    }
}

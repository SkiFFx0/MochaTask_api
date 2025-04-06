<?php

namespace App\Http\Middleware;

use App\Models\File;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class EnsureFileOwnership
{
    /**
     * Handle an incoming request.
     *
     * @param \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response) $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $taskId = $request->task_id;
        $fileId = $request->file->id;

        $fileInTask = File::query()
            ->where('id', $fileId)
            ->where('task_id', $taskId)
            ->exists();

        if (!$fileInTask)
        {
            throw new NotFoundHttpException();
        }

        return $next($request);
    }
}

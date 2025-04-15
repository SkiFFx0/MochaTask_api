<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class EnsureProjectOwnership
{
    /**
     * Handle an incoming request.
     *
     * @param \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response) $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $projectId = $request->project === null ? $request->project_id : $request->project->id;
        $projectAccessIds = $request->attributes->get('project_access_ids');

        if (!in_array($projectId, $projectAccessIds))
        {
            throw new NotFoundHttpException();
        }

        return $next($request);
    }
}

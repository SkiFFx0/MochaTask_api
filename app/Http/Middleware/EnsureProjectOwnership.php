<?php

namespace App\Http\Middleware;

use App\Models\Project;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class EnsureProjectOwnership
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $companyId = $request->company_id;
        $projectId = $request->project === null ? $request->project_id : $request->project->id;

        $projectInCompany = Project::query()
            ->where('id', $projectId)
            ->where('company_id', $companyId)
            ->exists();

        if (!$projectInCompany)
        {
            throw new NotFoundHttpException();
        }

        return $next($request);
    }
}

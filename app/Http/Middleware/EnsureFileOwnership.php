<?php

namespace App\Http\Middleware;

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
        $fileId = $request->file === null ? $request->file_id : $request->file->id;
        $fileAccessIds = $request->attributes->get('file_access_ids');

        if (!in_array($fileId, $fileAccessIds))
        {
            throw new NotFoundHttpException();
        }

        return $next($request);
    }
}

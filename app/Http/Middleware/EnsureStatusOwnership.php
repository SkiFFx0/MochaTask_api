<?php

namespace App\Http\Middleware;

use App\Helpers\ApiResponse;
use App\Models\Status;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class EnsureStatusOwnership
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if ($request['status'] === null)
        {
            throw ValidationException::withMessages([
                'status' => ['The status field is required.'],
            ]);
        }

        $statusId = Status::query()
            ->where('name', $request['status'])
            ->value('id');

        $statusAccessIds = $request->attributes->get('status_access_ids');

        if (!in_array($statusId, $statusAccessIds))
        {
            throw new NotFoundHttpException();
        }

        return $next($request);
    }
}

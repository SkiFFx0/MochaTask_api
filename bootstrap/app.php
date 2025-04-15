<?php

use App\Helpers\ApiResponse;
use App\Http\Middleware\AssignAttributes;
use App\Http\Middleware\EnsureCompanyMember;
use App\Http\Middleware\EnsureCompanyPrivileges;
use App\Http\Middleware\EnsureFileOwnership;
use App\Http\Middleware\EnsureProjectOwnership;
use App\Http\Middleware\EnsureStatusOwnership;
use App\Http\Middleware\EnsureTaskOwnership;
use App\Http\Middleware\EnsureTeamMember;
use App\Http\Middleware\EnsureTeamPrivileges;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        api: __DIR__ . '/../routes/api.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware)
    {
        $middleware->alias([
            'assign.attributes' => AssignAttributes::class,
            'company.member' => EnsureCompanyMember::class,
            'company.privileges' => EnsureCompanyPrivileges::class,
            'team.member' => EnsureTeamMember::class,
            'team.privileges' => EnsureTeamPrivileges::class,
            'project.ownership' => EnsureProjectOwnership::class,
            'status.ownership' => EnsureStatusOwnership::class,
            'task.ownership' => EnsureTaskOwnership::class,
            'file.ownership' => EnsureFileOwnership::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions)
    {
        $exceptions->render(function (Exception $e)
        {
            if (get_class($e) === AccessDeniedHttpException::class)
            {
                return ApiResponse::error('You do not have permission to perform this action', null, 403);
            }

            if (get_class($e) === ValidationException::class)
            {
                return ApiResponse::error('Input validation data is not correct', null, 422);
            }

            if (get_class($e) === NotFoundHttpException::class)
            {
                return ApiResponse::error('Data not found in the database', null, 404);
            }
        });
    })->create();

<?php

use App\Http\Middleware\EnsureCompanyMember;
use App\Models\ApiResponse;
use Illuminate\Auth\Middleware\Authenticate;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Validation\ValidationException;
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
            'company.member' => EnsureCompanyMember::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions)
    {
        $exceptions->render(function (Exception $e)
        {
            if (get_class($e) === ValidationException::class)
            {
                return ApiResponse::error('Input data is not correct', null, 422);
            }
            if (get_class($e) === NotFoundHttpException::class)
            {
                return ApiResponse::error('Data not found in the database', null, 404);
            }
        });
    })->create();

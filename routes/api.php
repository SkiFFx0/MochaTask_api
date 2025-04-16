<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CompanyController;
use App\Http\Controllers\CompanyMemberController;
use App\Http\Controllers\FileController;
use App\Http\Controllers\InvitationController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\StatusController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\TeamController;
use App\Http\Controllers\TeamMemberController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::get('/users', [UserController::class, 'index']);
Route::get('/users/{user}', [UserController::class, 'show']);

Route::middleware(['auth:sanctum', 'assign.attributes'])->group(function ()
{
    Route::post('/logout', [AuthController::class, 'logout']);

    Route::get('/companies', [CompanyController::class, 'index']);
    Route::get('/companies/{company}', [CompanyController::class, 'show']);

    Route::get('invitations/accept', [InvitationController::class, 'accept'])->name('invitation.accept');

    Route::post('/companies', [CompanyController::class, 'store']);

    Route::middleware('company.member')->group(function ()
    {
        Route::get('/company-members/{company}', [CompanyMemberController::class, 'index']);
        Route::get('/projects/{company}', [ProjectController::class, 'index']);
        Route::get('/teams/{company}', [TeamController::class, 'index']);

        Route::middleware('company.privileges')->group(function ()
        {
            Route::post('invitations/create/{company}', [InvitationController::class, 'invite']);

            Route::prefix('companies/{company}')->group(function () //TODO
            {
                Route::patch('/', [CompanyController::class, 'update']);
                Route::delete('/', [CompanyController::class, 'destroy']);

                Route::patch('/members/{user}', [CompanyMemberController::class, 'editRole']);
                Route::delete('/members/{user}', [CompanyMemberController::class, 'removeUser']);

                Route::post('/project', [ProjectController::class, 'store']);
            });

            Route::prefix('projects/{project}')->group(function ()
            {
                Route::patch('/', [ProjectController::class, 'update']);
                Route::delete('/', [ProjectController::class, 'destroy']);

                Route::post('/team', [TeamController::class, 'store']);
            });
        });
    });

    Route::middleware('team.member')->group(function ()
    {
        Route::get('/team-members', [TeamMemberController::class, 'index']);
        Route::get('/statuses', [StatusController::class, 'index']);
        Route::get('/tasks', [TaskController::class, 'index']);
        Route::get('/files', [FileController::class, 'index']);

        Route::patch('/task-status/{task}', [TaskController::class, 'changeStatus'])->middleware(['task.ownership', 'status.ownership']);

        Route::middleware('team.privileges')->group(function ()
        {
            Route::patch('/teams/{team}', [TeamController::class, 'update']);
            Route::delete('/teams/{team}', [TeamController::class, 'destroy']);

            Route::prefix('team-members/{user}')->group(function ()
            {
                Route::post('/', [TeamMemberController::class, 'addUserWithRole']);
                Route::patch('/', [TeamMemberController::class, 'editRole']);
                Route::delete('/', [TeamMemberController::class, 'removeUser']);
            });

            Route::prefix('statuses')->group(function ()
            {
                Route::post('/', [StatusController::class, 'store']);
                Route::delete('/{status}', [StatusController::class, 'destroy']);
            });

            Route::prefix('tasks')->group(function ()
            {
                Route::post('/', [TaskController::class, 'store'])->middleware('status.ownership');
                Route::patch('/{task}', [TaskController::class, 'update'])->middleware('task.ownership');
                Route::delete('/{task}', [TaskController::class, 'destroy'])->middleware('task.ownership');
            });
            Route::delete('/files/{file}', [FileController::class, 'destroy'])->middleware('file.ownership');
        });
    });
});

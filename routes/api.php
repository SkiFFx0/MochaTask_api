<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CompanyController;
use App\Http\Controllers\FileController;
use App\Http\Controllers\InvitationController;
use App\Http\Controllers\MemberController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\TeamController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function ()
{
    Route::post('/logout', [AuthController::class, 'logout']);

    Route::prefix('users')->group(function ()
    {
        Route::get('/', [UserController::class, 'index']);
        Route::get('/{user}', [UserController::class, 'show']);
    });

    Route::post('/companies/', [CompanyController::class, 'store']);
    Route::middleware('company.member')->group(function ()
    {
        //TODO

        Route::middleware('company.privileges')->group(function ()
        {
            Route::patch('/companies/{company}', [CompanyController::class, 'update']);
            Route::delete('/companies/{company}', [CompanyController::class, 'destroy']);

            Route::prefix('invitations')->group(function ()
            {
                Route::post('/create', [InvitationController::class, 'generateInviteLink']);
                Route::get('/accept', [InvitationController::class, 'acceptInviteLink'])->name('invitation.accept');
            });

            Route::prefix('members/{user}')->group(function () //TODO refactor it, softdeletes are now not used here
            {
                Route::post('/', [MemberController::class, 'addRole']);
                Route::delete('/{role}', [MemberController::class, 'removeRole']);
                Route::delete('/', [MemberController::class, 'removeUser']);
            });

            Route::prefix('projects')->group(function ()
            {
                Route::post('/', [ProjectController::class, 'store']);
                Route::patch('/{project}', [ProjectController::class, 'update'])->middleware('project.ownership');
                Route::delete('/{project}', [ProjectController::class, 'destroy'])->middleware('project.ownership');
            });

            Route::post('/teams', [TeamController::class, 'store'])->middleware('project.ownership');
        });
    });

    Route::middleware('team.member')->group(function ()
    {
        //TODO

        Route::middleware('team.privileges')->group(function ()
        {
            Route::patch('/teams/{team}', [TeamController::class, 'update']);
            Route::delete('/teams/{team}', [TeamController::class, 'destroy']);

            //TODO company privileged can add to any team, team privileged can add to own team

            Route::prefix('roles')->group(function ()
            {
                Route::post('/', [RoleController::class, 'store']);
                Route::patch('/{role}', [RoleController::class, 'update'])->middleware('role.ownership');
                Route::delete('/{role}', [RoleController::class, 'destroy'])->middleware('role.ownership');
            });

            Route::prefix('tasks')->group(function ()
            {
                Route::post('/', [TaskController::class, 'store']);
                Route::patch('/{task}', [TaskController::class, 'update'])->middleware('task.ownership');
                Route::delete('/{task}', [TaskController::class, 'destroy'])->middleware('task.ownership');
            });
            Route::delete('/files/{file}', [FileController::class, 'destroy'])->middleware('file.ownership');
        });
    });
});
//TODO add get methods, only to show items, which are inside other items

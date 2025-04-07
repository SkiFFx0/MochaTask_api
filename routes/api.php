<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CompanyController;
use App\Http\Controllers\CompanyMemberController;
use App\Http\Controllers\FileController;
use App\Http\Controllers\InvitationController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\TeamController;
use App\Http\Controllers\TeamMemberController;
use Illuminate\Support\Facades\Route;

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::middleware(['auth:sanctum', 'assign.attributes'])->group(function ()
{
    Route::post('/logout', [AuthController::class, 'logout']);

    Route::post('/companies/', [CompanyController::class, 'store']);
    Route::get('invitations/accept', [InvitationController::class, 'acceptInviteLink'])->name('invitation.accept');
    Route::middleware('company.member')->group(function ()
    {
        //TODO add get methods, only to show items, which are inside of company, and other stuff that will not require privileges

        Route::middleware('company.privileges')->group(function ()
        {
            Route::patch('/companies/{company}', [CompanyController::class, 'update']);
            Route::delete('/companies/{company}', [CompanyController::class, 'destroy']);

            Route::post('invitations/create', [InvitationController::class, 'generateInviteLink']);
            Route::prefix('company-members/{user}')->group(function ()
            {
                Route::post('/', [CompanyMemberController::class, 'addRole']);
                Route::delete('/{role}', [CompanyMemberController::class, 'removeRole']);
                Route::delete('/', [CompanyMemberController::class, 'removeUser']);
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
        //TODO add get methods, only to show items, which are inside of team, and other stuff that will not require privileges

        Route::middleware('team.privileges')->group(function ()
        {
            Route::patch('/teams/{team}', [TeamController::class, 'update']);
            Route::delete('/teams/{team}', [TeamController::class, 'destroy']);

            Route::post('/team-members', [TeamMemberController::class, 'addUser']);
            Route::prefix('team-members/{user}')->group(function ()
            {
                Route::post('/', [TeamMemberController::class, 'addRole']);
                Route::delete('/{role}', [TeamMemberController::class, 'removeRole']);
                Route::delete('/', [TeamMemberController::class, 'removeUser']);
            });
            //TODO team members management
            //TODO company privileged can add to any team, team privileged can add to own team

            Route::prefix('roles')->group(function ()
            {
                Route::post('/', [RoleController::class, 'store']);
                Route::patch('/{role}', [RoleController::class, 'update'])->middleware('role.ownership');
                Route::delete('/{role}', [RoleController::class, 'destroy'])->middleware('role.ownership');
            });

            Route::prefix('tasks')->group(function () //TODO add time complexity using enum
            {
                Route::post('/', [TaskController::class, 'store']);
                Route::patch('/{task}', [TaskController::class, 'update'])->middleware('task.ownership');
                Route::delete('/{task}', [TaskController::class, 'destroy'])->middleware('task.ownership');
            });
            Route::delete('/files/{file}', [FileController::class, 'destroy'])->middleware('file.ownership');

            //TODO add task statuses, and ability for implementor to change it
        });
    });
});
//TODO using auth()->user()->id get every needed id for the operation, don't just put them in request body, FUCK MY ASS

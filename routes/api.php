<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CompanyController;
use App\Http\Controllers\InvitationController;
use App\Http\Controllers\MemberController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\TeamController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::post("/register", [AuthController::class, 'register']);
Route::post("/login", [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function ()
{
    Route::post("/logout", [AuthController::class, 'logout']);

    Route::prefix('users')->group(function ()
    {
        Route::get("/", [UserController::class, 'index']);
        Route::get("/{user}", [UserController::class, 'show']);
    });

    Route::post("/companies/", [CompanyController::class, 'store']);
    Route::middleware('company.member')->group(function ()
    {
        //TODO GET METHODS

        Route::middleware('company.privileges')->group(function ()
        {
            Route::patch("/companies/{company}", [CompanyController::class, 'update']);
            Route::delete("/companies/{company}", [CompanyController::class, 'destroy']);

            Route::prefix('invitations')->group(function () //TODO
            {
                Route::post('/invitation-link-create', [InvitationController::class, 'generateInviteLink']);
                Route::post('/invitation-token-create', [InvitationController::class, 'generateInviteToken']);
                Route::get('/invitation-accept/', [InvitationController::class, 'acceptInviteLink'])
                    ->name('invitation.accept');
                Route::post('/invitation-accept/{token}', [InvitationController::class, 'acceptInviteToken']);
            });

            Route::prefix('members')->group(function () //TODO refactor it, softdeletes are now not used here
            {
                Route::post("/", [MemberController::class, 'addUser']); //TODO replace with link and token invites
                Route::post("/{user}", [MemberController::class, 'addRole']);
                Route::delete('/{user}/{role}', [MemberController::class, 'removeRole']);
                Route::delete("/{user}", [MemberController::class, 'removeUser']);
            });

            Route::prefix('projects')->group(function ()
            {
                Route::post("/", [ProjectController::class, 'store']);
                Route::patch("/{project}", [ProjectController::class, 'update']);
                Route::delete("/{project}", [ProjectController::class, 'destroy']);
            });

            Route::post('/teams', [TeamController::class, 'store']);
        });
    });

    //TODO company privileged can add to any team, team privileged can add to own team

    Route::middleware('team.member')->group(function ()
    {
        //TODO GET METHODS and TASK STATUS EDITING

        Route::middleware('team.privileges')->group(function ()
        {
            Route::patch("/teams/{team}", [TeamController::class, 'update']);
            Route::delete("/teams/{team}", [TeamController::class, 'destroy']);

            Route::prefix('roles')->group(function ()
            {
                Route::post('/', [RoleController::class, 'store']);
                Route::patch("/{role}", [RoleController::class, 'update']);
                Route::delete("/{role}", [RoleController::class, 'destroy']);
            });

            Route::prefix('tasks')->group(function ()
            {
                Route::post('/', [TaskController::class, 'store']);
                Route::patch("/{task}", [TaskController::class, 'update']);
                Route::delete("/{task}", [TaskController::class, 'destroy']);
            });
        });
    });
});
//TODO REFACTOR CONTROLLERS
//TODO add DB::transactions in controller functions where multiple queries, reason: when query fails, previous queries still execute
//TODO add get methods, only to show items, which are inside other items
//TODO add "service layer", too much rows in controllers

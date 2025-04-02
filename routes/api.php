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

    Route::prefix('companies')->group(function ()
    {
        Route::post("/", [CompanyController::class, 'store']);

        Route::prefix('{company}')->group(function ()
        {
            Route::middleware(['company.member'])->group(function ()
            {
                Route::middleware('can:manage,company')->group(function ()
                {
                    Route::patch("/", [CompanyController::class, 'update']);
                    Route::delete("/", [CompanyController::class, 'destroy']);

                    Route::prefix('invitations')->group(function () //TODO
                    {
                        Route::post('/invitation-link-create', [InvitationController::class, 'generateInviteLink']);
                        Route::post('/invitation-token-create', [InvitationController::class, 'generateInviteToken']);

                        Route::get('/invitation-accept/', [InvitationController::class, 'acceptInviteLink'])
                            ->name('invitation.accept');
                        Route::post('/invitation-accept/{token}', [InvitationController::class, 'acceptInviteToken']);
                    });

                    Route::prefix('members')->group(function ()
                    {
                        Route::post("/", [MemberController::class, 'addUser']); //TODO replace with link and token invites

                        Route::prefix('{user}')->group(function ()
                        {
                            Route::post("/", [MemberController::class, 'addRole']);
                            Route::delete('/{role}', [MemberController::class, 'removeRole']);
                            Route::delete("/", [MemberController::class, 'removeUser']);
                        });
                    });

                    Route::prefix('projects')->group(function ()
                    {
                        Route::post("/", [ProjectController::class, 'store']);

                        Route::prefix('{project}')->group(function ()
                        {
                            Route::patch("/", [ProjectController::class, 'update']);
                            Route::delete("/", [ProjectController::class, 'destroy']);

                            //TODO company privileged can add to any team, team privileged can add to own team

                            Route::prefix('teams')->group(function ()
                            {
                                Route::post('/', [TeamController::class, 'store']);

                                Route::prefix('{team}')->group(function ()
                                {
                                    Route::middleware('team.member')->group(function ()
                                    {
                                        Route::middleware('can:manage,team')->group(function ()
                                        {
                                            Route::patch("/", [TeamController::class, 'update']);
                                            Route::delete("/", [TeamController::class, 'destroy']);

                                            Route::prefix('roles')->group(function () //TODO add getting all roles of the company
                                            {
                                                Route::post('/', [RoleController::class, 'store']);

                                                Route::prefix('{role}')->group(function ()
                                                {
                                                    Route::patch("/", [RoleController::class, 'update']);
                                                    Route::delete("/", [RoleController::class, 'destroy']);
                                                });
                                            });

                                            Route::prefix('tasks')->group(function ()
                                            {
                                                Route::post('/', [TaskController::class, 'store']);

                                                Route::prefix('{task}')->group(function ()
                                                {
                                                    Route::patch("/", [TaskController::class, 'update']);
                                                    Route::delete("/", [TaskController::class, 'destroy']);
                                                });
                                            });
                                        });
                                    });
                                });
                            });
                        });
                    });
                });
            });
        });
    });
});
//TODO REFACTOR THIS WHOLE FILE
//TODO add get methods, only to show items, which are inside other items
//TODO add DB::transactions, reason: when query fails, previous queries still execute and sometimes id iterates without reason
//TODO add "service layer", too much rows in controllers
//TODO just refactor whole code upon seeing problems

<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CompanyController;
use App\Http\Controllers\InvitationController;
use App\Http\Controllers\MemberController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\RoleController;
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
        Route::get("/", [CompanyController::class, 'index']);
        Route::get("/{company}", [CompanyController::class, 'show']);
        Route::post("/", [CompanyController::class, 'store']);

        Route::middleware(['company.member'])->group(function ()
        {
            Route::prefix('{company}')->group(function ()
            {
                //TODO get methods

                Route::middleware('can:manage,company')->group(function ()
                {
                    Route::patch("/", [CompanyController::class, 'update']);
                    Route::delete("/", [CompanyController::class, 'destroy']);

                    Route::prefix('invitations')->group(function ()
                    {
                        Route::post('/invitation-link-create', [InvitationController::class, 'generateInviteLink']);
                        Route::post('/invitation-token-create', [InvitationController::class, 'generateInviteToken']);

                        Route::get('/invitation-accept/', [InvitationController::class, 'acceptInviteLink'])
                            ->name('invitation.accept');
                        Route::post('/invitation-accept/{token}', [InvitationController::class, 'acceptInviteToken']);
                    }); //TODO refactor implementation

                    Route::prefix('members')->group(function ()
                    {
                        Route::post("/", [MemberController::class, 'addUser']); //TODO replace with link and token invites
                        Route::post("/{user}", [MemberController::class, 'addRole']);
                        Route::delete('/{user}/{role}', [MemberController::class, 'removeRole']);
                        Route::delete("/{user}", [MemberController::class, 'removeUser']);
                    });

                    Route::prefix('projects')->group(function ()
                    {
                        Route::post("/", [ProjectController::class, 'store']);

                        Route::middleware('project.member')->group(function ()
                        {
                            Route::middleware('can:manage,project')->group(function ()
                            {
                                Route::prefix('{project}')->group(function ()
                                {
                                    Route::patch("/", [ProjectController::class, 'update']);
                                    Route::delete("/", [ProjectController::class, 'destroy']);

                                    Route::prefix('roles')->group(function ()
                                    {
                                        Route::post('/', [RoleController::class, 'store']);
                                        Route::patch("/{role}", [RoleController::class, 'update']);
                                        Route::delete("/{role}", [RoleController::class, 'destroy']);
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

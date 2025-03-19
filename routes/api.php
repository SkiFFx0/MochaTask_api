<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CompanyController;
use App\Http\Controllers\InvitationController;
use App\Http\Controllers\ProjectController;
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
                Route::patch("/", [CompanyController::class, 'update']);
                Route::delete("/", [CompanyController::class, 'destroy']);

                Route::prefix('/members')->group(function ()
                {
                    Route::post("/", [CompanyController::class, 'editUserRole']);
                    Route::delete("/{user}", [CompanyController::class, 'removeUser']);
                })->middleware("can:manage,company");
            });

            Route::post("/{id}/", [CompanyController::class, 'send']);
        });
    });


    Route::prefix('invitations')->group(function ()
    {
        Route::middleware("company.member")->group(function ()
        {
            Route::post('/invitation-create/{company_id}/{role}', [InvitationController::class, 'generateInviteLink'])
            ->middleware("can:manage,company");
            Route::post('/invitation-create', [InvitationController::class, 'generateInviteToken']);
        });

        Route::get('/invitation-accept/', [InvitationController::class, 'acceptInviteLink'])
            ->name('invitation.accept');
        Route::post('/invitation-accept/{token}', [InvitationController::class, 'acceptInviteToken']);
    });

    Route::prefix('projects')->group(function ()
    {
        Route::middleware(['company.member'])->group(function ()
        {
            Route::get("/", [ProjectController::class, 'index']);
            Route::get("/{project}", [ProjectController::class, 'show']);
            Route::post("/", [ProjectController::class, 'store']);
            Route::patch("/{project}", [ProjectController::class, 'update']);
            Route::delete("/{project}", [ProjectController::class, 'destroy']);
        });
    });
});

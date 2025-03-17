<?php

use App\Http\Controllers\Admin\MemberController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CompanyController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::post("/register", [AuthController::class, 'register']);
Route::post("/login", [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function ()
{
    Route::post("/logout", [AuthController::class, 'logout']);

    Route::group(['prefix' => 'users'], function ()
    {
        Route::get("/", [UserController::class, 'index']);
        Route::get("/{user}", [UserController::class, 'show']);
    });

    Route::group(['prefix' => 'companies'], function ()
    {
        Route::get("/", [CompanyController::class, 'index']);
        Route::get("/{company}", [CompanyController::class, 'show']);
        Route::post("/", [CompanyController::class, 'store']);

        Route::middleware(['company.member'])->group(function ()
        {
            Route::patch("/{company}", [CompanyController::class, 'update']);
            Route::delete("/{company}", [CompanyController::class, 'destroy']);

            Route::group(['prefix' => '{company}'], function ()
            {
                Route::post("/add-user", [CompanyController::class, 'addUser']);
                Route::post("/remove-user", [CompanyController::class, 'removeUser']);
            })->middleware("can:manage,company");
        });
    });

    Route::group(['prefix' => 'projects'], function ()
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

    Route::group(['prefix' => 'members'], function ()
    {
        Route::middleware(['company.member'])->group(function ()
        {
            Route::post("/", [MemberController::class, 'store']);
            Route::delete("/{member}", [MemberController::class, 'destroy']);
        });
    });
});

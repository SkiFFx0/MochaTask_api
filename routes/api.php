<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CompanyController;
use App\Http\Controllers\ProjectController;
use Illuminate\Support\Facades\Route;

Route::post("/register", [AuthController::class, 'register']);
Route::post("/login", [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function ()
{
    Route::post("/logout", [AuthController::class, 'logout']);

    Route::group(['prefix' => 'company'], function ()
    {
        Route::post("/", [CompanyController::class, 'store']);
        Route::put("/{company}", [CompanyController::class, 'update']);
        Route::delete("/{company}", [CompanyController::class, 'destroy']);
    });

    Route::group(['prefix' => 'project'], function ()
    {
        Route::post("/", [ProjectController::class, 'store']);
        Route::put("/{project}", [ProjectController::class, 'update']);
        Route::delete("/{project}", [ProjectController::class, 'destroy']);
    });
});

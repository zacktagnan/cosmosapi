<?php

use App\Http\Controllers\V1\AuthController;
use App\Http\Controllers\V1\SpaceMissionController;
use App\Http\Middleware\V1\ApiResponseMiddleware;
use Illuminate\Support\Facades\Route;

Route::middleware([ApiResponseMiddleware::class])->group(function () {
    // Authentication routes (no auth required)
    Route::post('login', [AuthController::class, 'login']);

    // Protected routes
    Route::middleware('auth:sanctum')->group(function () {
        Route::post('logout', [AuthController::class, 'logout']);

        Route::prefix('space-missions')->as('space-missions.')->group(function () {
            Route::get('index-with-pipeline', [SpaceMissionController::class, 'indexWithPipeline'])->name('indexWithPipeline');
        });
        Route::apiResource('space-missions', SpaceMissionController::class);
    });
});

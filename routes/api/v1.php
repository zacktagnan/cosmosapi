<?php

use App\Http\Controllers\V1\SpaceMissionController;
use App\Http\Middleware\V1\ApiResponseMiddleware;
use Illuminate\Support\Facades\Route;

Route::middleware([ApiResponseMiddleware::class])->group(function () {
    Route::middleware('auth:sanctum')->group(function () {
        Route::apiResource('space-missions', SpaceMissionController::class);
    });
});

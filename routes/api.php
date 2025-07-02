<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Middleware\V1\ApiResponseMiddleware;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

// Route::prefix('v1')
//     ->as('v1.')
//     ->middleware(ApiResponseMiddleware::class)
//     ->group(
//         base_path('routes/api/v1.php')
//     );

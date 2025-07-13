<?php

use Dedoc\Scramble\Scramble;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

// Route::domain('docs.localhost:7415')->group(function () {
//     Scramble::registerUiRoute('api');
//     Scramble::registerJsonSpecificationRoute('api.json');
// });
// Scramble::registerUiRoute('api');
// Scramble::registerJsonSpecificationRoute('api.json');

Scramble::registerUiRoute('scramble/v1', api: 'default');
Scramble::registerJsonSpecificationRoute('scramble/v1/api.json', api: 'default');

// Route::middleware('auth:sanctum')->group(function () {
//     Scramble::registerUiRoute('scramble/v1', api: 'default');
//     Scramble::registerJsonSpecificationRoute('scramble/v1/api.json', api: 'default');
// });

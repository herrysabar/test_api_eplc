<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\TaskController;

// Public route - Login
Route::post('/login', [AuthController::class, 'login']);

// Protected routes - require token authentication
Route::middleware('token.auth')->group(function () {
    Route::apiResource('tasks', TaskController::class);
});

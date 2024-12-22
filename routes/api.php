<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\AssistantController;

Route::middleware(['auth:sanctum'])->group(function () {
    // User routes (admin only)
    Route::middleware(['admin'])->group(function () {
        Route::apiResource('users', UserController::class);
        Route::apiResource('assistants', AssistantController::class);
    });

    // Task routes (all authenticated users)
    Route::apiResource('tasks', TaskController::class);

    // Current user route
    Route::get('/user', function (Request $request) {
        return $request->user();
    });
});

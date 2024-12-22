<?php

use App\Http\Controllers\UserController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\AssistantController;
use App\Http\Controllers\ChatController;
use App\Http\Middleware\AdminMiddleware;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return view('welcome');
});

Route::middleware(['auth', 'verified'])->group(function () {
    // Admin-only routes
    Route::middleware(AdminMiddleware::class)->group(function () {
        // Web routes
        Route::get('/dashboard', [UserController::class, 'dashboard'])->name('dashboard');
        Route::get('/users', [UserController::class, 'index'])->name('users.index');
        Route::get('/assistants', [AssistantController::class, 'index'])->name('assistants.index');
        
        // API routes
        Route::prefix('api')->group(function () {
            Route::get('/assistants/list', [AssistantController::class, 'apiIndex'])->name('api.assistants.index');
            Route::apiResource('assistants', AssistantController::class)->except(['index']);
            Route::apiResource('users', UserController::class);
        });
    });

    // Routes accessible by all authenticated users
    Route::middleware(['auth'])->group(function () {
        Route::get('/tasks', [TaskController::class, 'index'])->name('tasks.index');
        Route::get('/tasks/{task}/chat', [ChatController::class, 'show'])->name('chat.show');
        Route::post('/tasks/{task}/chat', [ChatController::class, 'sendMessage'])->name('chat.send');
        Route::prefix('api')->group(function () {
            Route::apiResource('tasks', TaskController::class);
        });
    });
});

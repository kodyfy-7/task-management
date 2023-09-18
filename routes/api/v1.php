<?php

use App\Http\Controllers\Api\V1\AuthController;
use App\Http\Controllers\Api\V1\ProfileController;
use App\Http\Controllers\Api\V1\Tasks\TasksController;
use App\Http\Controllers\Api\V1\Tasks\MarkCompleteTaskController;
use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'v1'], function () {
    Route::group(['prefix' => 'auth'], function () {
        Route::post('login', [AuthController::class, 'login']);        
        Route::post('register', [AuthController::class, 'register']);
    });

    Route::middleware(['auth:sanctum'])->group(function () {
        Route::post('auth/logout', [AuthController::class, 'logout']);
        Route::get('profile', [ProfileController::class, 'index']);
        Route::put('profile', [ProfileController::class, 'update']);
        Route::put('tasks/status/{task}', MarkCompleteTaskController::class);
        Route::resource('tasks', TasksController::class)->only([
            'index', 'store', 'show', 'update', 'destroy'
        ]);
    });
});
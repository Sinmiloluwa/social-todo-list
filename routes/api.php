<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Broadcast;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');
Route::prefix('auth')->group(function () {
    Route::post('/register', [\App\Http\Controllers\AuthController::class, 'register']);
    Route::post('/login', [\App\Http\Controllers\AuthController::class, 'login']);
    Route::post('/logout', [\App\Http\Controllers\AuthController::class, 'logout']);
});
Route::get('search-user', [\App\Http\Controllers\UserController::class, 'search']);

Route::prefix('todos')->group(function () {
    Route::middleware('auth:sanctum')->group(function () {
        Route::post('create', [\App\Http\Controllers\TodoListController::class, 'create']);
        Route::get('show/{todoList}', [\App\Http\Controllers\TodoListController::class, 'show']);
        Route::patch('update/{todoList}', [\App\Http\Controllers\TodoListController::class, 'update']);
        Route::delete('destroy/{todoList}', [\App\Http\Controllers\TodoListController::class, 'destroy']);
        Route::post('invite-user/{todoList}/{user}', [\App\Http\Controllers\TodoListController::class, 'invite']);
        Route::get('list', [\App\Http\Controllers\TodoListController::class, 'index']);
    });
});

Route::prefix('todo-items')->middleware('auth:sanctum')->group(function () {
    Route::post('create/{todoList}', [\App\Http\Controllers\TodoItemController::class, 'create']);
    Route::patch('update/{todoItem}', [\App\Http\Controllers\TodoItemController::class, 'update']);
    Route::delete('destroy/{todoItem}', [\App\Http\Controllers\TodoItemController::class, 'destroy']);
    Route::post('/{todoItem}/complete', [\App\Http\Controllers\TodoItemController::class, 'complete']);
});

Broadcast::routes(['middleware' => ['auth:sanctum']]);


<?php

use App\Http\Controllers\KeywordController;
use App\Http\Controllers\TaskController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::prefix('tasks')->group(function () {
    Route::get('/', [TaskController::class, 'index']);
    Route::post('/', [TaskController::class, 'store']);
    Route::patch('/{id}/toggle', [TaskController::class, 'toggle']);
});

Route::prefix('keywords')->group(function () {
    Route::get('/', [KeywordController::class, 'index']);
    Route::post('/', [KeywordController::class, 'store']);
    Route::get('/{id}', [KeywordController::class, 'show']);
});

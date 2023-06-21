<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\BookController;
use App\Http\Controllers\Api\UserController;
use Illuminate\Support\Facades\Route;

Route::controller(AuthController::class)->group(function (): void {
    Route::post('register', 'register')->name('register');
    Route::post('login', 'login')->name('login');
    Route::post('logout', 'logout')->name('logout')->middleware('auth:sanctum');
});

Route::middleware('auth:sanctum')->group(function (): void {
    Route::get('user', UserController::class)->name('user');
    Route::apiResource('books', BookController::class)->parameters(['books' => 'id']);
});

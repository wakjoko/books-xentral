<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;

Route::controller(AuthController::class)->group(function (): void {
    Route::post('register', 'register')->name('register');
    Route::post('login', 'login')->name('login');
    Route::post('logout', 'logout')->name('logout')->middleware('auth:sanctum');
});

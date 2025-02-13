<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\PasswordController;

Route::middleware('guest')->group(function () {
    Route::get('users/register', [UserController::class, 'create'])
        ->name('register');

    Route::get('users/register', [UserController::class, 'store']);

    Route::get('users/login', [UserController::class, 'login'])
        ->name('login');

    Route::post('users/login', [UserController::class, 'authenticate']);
});

Route::get('users/reset-password', [PasswordController::class, 'create'])
    ->name('password.reset');

Route::middleware('auth')->group(function () {
    Route::post('users/logout', [UserController::class, 'destroy'])
        ->name('logout');
});
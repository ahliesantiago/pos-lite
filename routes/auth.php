<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;

Route::middleware('auth')->group(function () {
    Route::post('logout', [UserController::class, 'destroy'])
        ->name('logout');
});
<?php

use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Http\Middleware\CheckDefaultPassword;

Route::middleware(['auth', CheckDefaultPassword::class])->group(function () {
    Route::get('/', function () {
        return Inertia::render('Main/Dashboard');
    })->name('dashboard');
    
    Route::get('/inventory', function () {
        return Inertia::render('Products/Inventory');
    })->name('inventory');
    
    Route::get('/orders', function () {
        return Inertia::render('Orders/Orders');
    })->name('orders');
    
    Route::get('/records', function () {
        return Inertia::render('History/Records');
    })->name('records');
});

require __DIR__.'/auth.php';

<?php

use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Http\Middleware\CheckDefaultPassword;

Route::middleware(['auth', CheckDefaultPassword::class])->group(function () {
    Route::get('/', function () {
        return Inertia::render('Main/Dashboard');
    })->name('dashboard');
    
    Route::get('/products', function () {
        return Inertia::render('Inventory/AllProducts');
    })->name('products');
    
    Route::get('/products/bulk-add', function () {
        return Inertia::render('Inventory/BulkAddProducts');
    })->name('products.bulk-add');
    
    Route::get('/orders', function () {
        return Inertia::render('History/PendingOrders');
    })->name('orders');
    
    // Route::get('/records', function () {
    //     return Inertia::render('History/Records');
    // })->name('records');
});

require __DIR__.'/auth.php';

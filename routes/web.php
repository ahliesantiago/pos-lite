<?php

use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Http\Middleware\CheckDefaultPassword;
use App\Http\Controllers\CartController;
use App\Http\Controllers\ProductController;

Route::middleware(['auth', CheckDefaultPassword::class])->group(function () {
    Route::get('/', function () {
        return Inertia::render('Main/Dashboard');
    })->name('dashboard');

    Route::get('/products', [ProductController::class, 'index'])->name('products.index');
    Route::get('/products/featured', [ProductController::class, 'featured'])->name('products.featured'); 
    Route::post('/products', [ProductController::class, 'store'])->name('products.store');
    Route::get('/products/bulk-add', [ProductController::class, 'bulkCreate'])->name('products.bulk-add');
    
    Route::get('/orders', [CartController::class, 'index'])->name('orders');
    
    // Route::get('/records', function () {
    //     return Inertia::render('History/Records');
    // })->name('records');
});

require __DIR__.'/auth.php';

<?php

use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Http\Middleware\CheckDefaultPassword;
use App\Http\Controllers\CartController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProductTypeController;

Route::middleware(['auth', CheckDefaultPassword::class])->group(function () {
    Route::get('/', function () {
        return Inertia::render('Main/Dashboard');
    })->name('dashboard');

    Route::get('/inventory', [ProductController::class, 'index'])->name('inventory');

    Route::get('/inventory/products/list', [ProductController::class, 'list'])->name('products.list'); 
    Route::get('/inventory/products/featured/{count}', [ProductController::class, 'featured'])->name('products.featured'); 
    Route::post('/inventory/products', [ProductController::class, 'store'])->name('products.store');
    Route::get('/inventory/products/bulk-add', [ProductController::class, 'bulkCreate'])->name('products.bulk-add');
    
    Route::get('/inventory/types/list', [ProductTypeController::class, 'list'])->name('types.list'); 
    Route::post('/inventory/types', [ProductTypeController::class, 'store'])->name('types.store');
    Route::get('/inventory/types/bulk-add', [ProductTypeController::class, 'bulkCreate'])->name('types.bulk-add');
    
    Route::get('/orders', [CartController::class, 'index'])->name('orders');
    
    // Route::get('/records', function () {
    //     return Inertia::render('History/Records');
    // })->name('records');
});

require __DIR__.'/auth.php';

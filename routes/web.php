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
    Route::get('/inventory/products/search', [ProductController::class, 'search'])->name('products.search');
    Route::post('/inventory/products', [ProductController::class, 'store'])->name('products.store');
    Route::put('/inventory/products/{product_id}', [ProductController::class, 'update'])->name('products.update');
    Route::put('/inventory/products/{product}/restock', [ProductController::class, 'restock'])->name('products.stockUpdate');
    Route::put('/inventory/products/{product}/archive', [ProductController::class, 'archive'])->name('products.archive');
    Route::get('/inventory/products/bulk-add', [ProductController::class, 'bulkCreate'])->name('products.bulk-add');
    
    Route::get('/inventory/types/list', [ProductTypeController::class, 'list'])->name('types.list');
    Route::post('/inventory/types', [ProductTypeController::class, 'store'])->name('types.store');
    Route::get('/inventory/types/bulk-add', [ProductTypeController::class, 'bulkCreate'])->name('types.bulk-add');
    
    Route::get('/orders', [CartController::class, 'index'])->name('orders');
    Route::post('/orders', [CartController::class, 'store'])->name('orders.store');
    Route::get('/orders/{order_id}', [CartController::class, 'show'])->name('orders.show');
});

require __DIR__.'/auth.php';

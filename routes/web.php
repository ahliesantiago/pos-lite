<?php

use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use Illuminate\Support\Facades\Auth;

Route::get('/', function () {
    $isLoggedIn = false;
    $username = null;
    $isAdminDefaultPassword = false;

    if (Auth::check()) {
        // User is logged in
        $isLoggedIn = true;
        $user = Auth::user();
        $username = $user->username;

        // Check if the user is the admin and has the default password
        if ($user->username === 'admin' && password_verify('admin', $user->password)) {
            $isAdminDefaultPassword = true;
            // Render the Welcome component for admin with default password
            return Inertia::render('Welcome', [
                'isLoggedIn' => $isLoggedIn,
                'username' => $username,
                'isAdminDefaultPassword' => $isAdminDefaultPassword,
            ]);
        }

        // Redirect to dashboard for other users or if admin password is not default
        return redirect()->route('dashboard');
    }

    // User is not logged in
    return Inertia::render('Welcome', [
        'isLoggedIn' => $isLoggedIn,
        'username' => $username,
        'isAdminDefaultPassword' => $isAdminDefaultPassword,
    ]);
});

Route::get('/dashboard', function () {
    return Inertia::render('Main/Dashboard');
})->name('dashboard');
// })->middleware(['auth', 'verified'])->name('dashboard');

Route::get('/inventory', function () {
    return Inertia::render('Products/Inventory');
})->name('inventory');

Route::get('/orders', function () {
    return Inertia::render('Orders/Orders');
})->name('orders');

Route::get('/records', function () {
    return Inertia::render('History/Records');
})->name('records');

require __DIR__.'/auth.php';

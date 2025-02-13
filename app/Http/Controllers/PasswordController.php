<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Inertia\Inertia;

class PasswordController extends Controller
{
    /**
     * Display the password reset view.
     */
    public function create()
    {
        return Inertia::render('Auth', [
            'isLoggedIn' => Auth::check(),
            'username' => Auth::user()->username,
            'isPasswordDefault' => session('isPasswordDefault', false),
            'isResettingPassword' => session('isResettingPassword', false),
        ]);
    }

    /**
     * Handle an incoming new password request.
     */
    public function store(Request $request): RedirectResponse
    {
        
    }
}
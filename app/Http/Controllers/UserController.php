<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class UserController extends Controller
{
    public function store()
    {

    }

    public function login()
    {
        return Inertia::render('Auth', [
            'isLoggedIn' => Auth::check(),
        ]);
    }

    public function authenticate(Request $request)
    {
        $request->validate([
            'username' => 'required',
            'password' => 'required',
        ]);

        if (Auth::attempt($request->only('username', 'password'))) {
            $request->session()->regenerate();
            return redirect()->intended(route('dashboard'));
        } else {
            return back()->withErrors([
                'username' => 'The provided credentials do not match our records.',
            ]);
        }
    }

    /**
     * Destroy an authenticated session (logout a signed-in user).
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect(route('login'));
    }
}
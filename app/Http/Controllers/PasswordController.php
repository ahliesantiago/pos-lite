<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Inertia\Inertia;

class PasswordController extends Controller
{
    /**
     * Display the password reset view.
     */
    public function edit()
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
    public function update(Request $request): RedirectResponse
    {
        $request->validate([
            'username' => 'required',
            'password' => ['required', Rule::notIn(['password'])],
        ]);

        $user = User::where('username', $request->username)->first();

        // If there is no user in the database with the given username
        if (!$user) {
            return back()->withErrors(['username' => "Credentials don't match"]);
        }

        $user->update(['password' => Hash::make($request->password)]);

        return redirect(route('dashboard'))->with('message', 'Password successfully updated!');
    }
}
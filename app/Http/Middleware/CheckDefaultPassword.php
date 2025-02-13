<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class CheckDefaultPassword
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next)
    {
        $user = Auth::user();

        if ($user && Hash::check('password', $user->password)) {
            session()->put('isPasswordDefault', true);
            return redirect()->route('password.reset')
                ->with('warning', 'You must change your default password.');
        }

        return $next($request);
    }
}
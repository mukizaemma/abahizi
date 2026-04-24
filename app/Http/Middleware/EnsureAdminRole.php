<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EnsureAdminRole
{
    public function handle(Request $request, Closure $next)
    {
        $user = Auth::user();
        if (! $user || (int) ($user->role ?? 0) !== 1) {
            Auth::logout();
            return redirect()->route('loginForm')->with('error', 'Admin access only.');
        }

        return $next($request);
    }
}


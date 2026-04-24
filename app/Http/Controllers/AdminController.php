<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class AdminController extends Controller
{
    /**
     * Show the admin login form (same Blade as Fortify; posts to admin.login).
     */
    public function loginForm()
    {
        return view('auth.login', ['guard' => 'admin']);
    }

    /**
     * Handle an admin login attempt (session guard, role check).
     */
    public function store(Request $request)
    {
        $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string',
        ]);

        if (! Auth::guard('web')->attempt(
            $request->only('email', 'password'),
            $request->boolean('remember')
        )) {
            throw ValidationException::withMessages([
                'email' => [trans('auth.failed')],
            ]);
        }

        $request->session()->regenerate();

        $user = Auth::user();

        if (isset($user->role) && (int) $user->role !== 1) {
            Auth::guard('web')->logout();

            throw ValidationException::withMessages([
                'email' => [__('This account is not authorized to access the admin panel.')],
            ]);
        }

        return redirect()->intended(route('dashboard'));
    }
}

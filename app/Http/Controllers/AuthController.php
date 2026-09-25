<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    /**
     * Show Login Page
     */
    public function showLogin()
    {
        return view('auth.login');
    }

    /**
     * Login
     */
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        if (! Auth::attempt($credentials)) {

            return back()
                ->withInput($request->only('email'))
                ->with('error', 'Invalid email or password.');
        }

        $request->session()->regenerate();

        $user = Auth::user();

        /*
        |--------------------------------------------------------------------------
        | Check User Status
        |--------------------------------------------------------------------------
        */

        if (! $user->status) {

            Auth::logout();

            return back()
                ->withInput($request->only('email'))
                ->with('error', 'Your account is inactive.');
        }

        /*
        |--------------------------------------------------------------------------
        | Super Admin
        |--------------------------------------------------------------------------
        */

        if ($user->role === 'super_admin') {

            return redirect()
                ->route('admin.dashboard');
        }

        /*
        |--------------------------------------------------------------------------
        | Tenant User
        |--------------------------------------------------------------------------
        */

        if (! $user->tenant_id) {

            Auth::logout();

            return back()
                ->with('error', 'Your account is not linked to any business.');
        }

        /*
        |--------------------------------------------------------------------------
        | Tenant Status
        |--------------------------------------------------------------------------
        */

        if (! $user->tenant || ! $user->tenant->status) {

            Auth::logout();

            return back()
                ->with('error', 'Your business account is inactive.');
        }

        return redirect()
            ->route('tenant.dashboard');
    }

    /**
     * Logout
     */
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect()
            ->route('login')
            ->with('success', 'You have been logged out successfully.');
    }
}

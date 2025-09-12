<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        $request->authenticate();

        $request->session()->regenerate();

        $user = auth()->user();
        $roleSlug = $user->role->slug ?? 'no-role';
        \Log::info('User logged in', ['user_id' => $user->id, 'email' => $user->email, 'role_slug' => $roleSlug]);

        // Redirect based on role, similar to registration
        switch ($roleSlug) {
            case 'super-admin':
                return redirect()->route('dashboard.super-admin');
            case 'provider-professional':
                return redirect()->route('dashboard.provider-professional');
            case 'provider-bonding':
                return redirect()->route('dashboard.provider-bonding');
            case 'client':
            default:
                return redirect()->intended(route('dashboard', absolute: false));
        }
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}

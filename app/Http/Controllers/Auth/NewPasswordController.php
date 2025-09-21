<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class NewPasswordController extends Controller
{
    /**
     * Display the password reset view.
     */
    public function create(Request $request): View
    {
        return view('auth.reset-password', ['request' => $request]);
    }

    /**
     * Handle an incoming new password request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'token'    => ['required'],
            'email'    => ['required', 'email'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function (User $user) use ($request) {
                $user->forceFill([
                    'password'       => Hash::make($request->password),
                    'remember_token' => Str::random(60),
                ])->save();

                event(new PasswordReset($user));

                // 🔑 Auto-login after reset
                Auth::login($user);

                // Log for auditing
                \Log::info('Password reset and auto-login', [
                    'user_id' => $user->id,
                    'email'   => $user->email,
                    'role'    => $user->role->slug ?? 'no-role',
                ]);
            }
        );

        if ($status == Password::PASSWORD_RESET) {
            // Redirect user based on role
            $user = Auth::user();
            $roleSlug = $user->role->slug ?? 'no-role';

            $roleRoutes = [
                'super-admin'           => 'dashboard.super-admin',
                'provider-professional' => 'dashboard.provider-professional',
                'provider-bonding'      => 'dashboard.provider-bonding',
                'client'                => 'dashboard',
            ];

            if (array_key_exists($roleSlug, $roleRoutes) && \Route::has($roleRoutes[$roleSlug])) {
                return redirect()->route($roleRoutes[$roleSlug])
                    ->with('status', 'Your password has been reset successfully!');
            }

            return redirect()->route('dashboard')
                ->with('status', 'Your password has been reset successfully!');
        }

        return back()->withInput($request->only('email'))
            ->withErrors(['email' => __($status)]);
    }
}

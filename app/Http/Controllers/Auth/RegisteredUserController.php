<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\RegisterRequest;
use App\Models\Role;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        // Available roles (now including "client")
        $roles = Role::whereIn('slug', [
            'client',      // Regular client/parent - main app user
            'employee',    // Internal staff
            'provider',    // Activity/Service provider
            'manager',     // Limited admin access
            'admin'        // Super Admin
        ])->get();

        return view('auth.register', compact('roles'));
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(RegisterRequest $request): RedirectResponse
    {
        $validated = $request->validated();

        $user = User::create([
            'name'       => $validated['name'],
            'email'      => $validated['email'],
            'password'   => Hash::make($validated['password']),
            'role_id'    => $validated['role_id'],
            'phone'      => $validated['phone'] ?? null,
            'department' => $validated['department'] ?? null,
            'is_active'  => true, // Auto-activate (toggle if admin approval required)
        ]);

        event(new Registered($user));

        Auth::login($user);

        return $this->handlePostRegistration($user);
    }

    /**
     * Handle post-registration logic based on user role.
     */
    private function handlePostRegistration(User $user): RedirectResponse
    {
        switch ($user->role->slug) {
            case 'provider':
                return redirect()->route('provider.register')
                    ->with('success', 'Provider account created! Please complete your profile.');

            case 'admin':
                return redirect()->route('dashboard')
                    ->with('success', 'Welcome to the admin dashboard!');

            case 'manager':
                return redirect()->route('dashboard')
                    ->with('success', 'Welcome to the manager dashboard!');

            case 'employee':
                return redirect()->route('dashboard')
                    ->with('success', 'Welcome employee! Explore your tasks and activities.');

            case 'client': // NEW role
            default:
                return redirect()->route('dashboard')
                    ->with('success', 'Welcome! Explore bonding activities, parenting modules, and AI support.');
        }
    }

    /**
     * Universal dashboard redirect (future-proof).
     */
    private function redirectToDashboard(User $user): RedirectResponse
    {
        return redirect()->route('dashboard');
    }
}

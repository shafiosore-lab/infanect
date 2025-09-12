<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\RegisterRequest;
use App\Models\Role;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view with available roles.
     *
     * @return View
     */
    public function create(): View
    {
        $roles = Role::whereIn('slug', [
            'client',
            'employee',
            'provider',
            'activity_provider',
            'manager',
            'admin'
        ])->get();



        return view('auth.register', compact('roles'));
    }

    /**
     * Handle an incoming registration request.
     *
     * @param RegisterRequest $request
     * @return RedirectResponse
     */
    public function store(RegisterRequest $request): RedirectResponse
    {
        $validated = $request->validated();

        // Map slug to role_id
        $role = Role::where('id', $validated['role_id'])->first();

        $user = User::create([
            'name'       => $validated['name'],
            'email'      => $validated['email'],
            'password'   => Hash::make($validated['password']),
            'role_id'    => $role->id,
            'phone'      => $validated['phone'] ?? null,
            'department' => $validated['department'] ?? null,
            'is_active'  => true,
        ]);

        event(new Registered($user));
        Auth::login($user);

        return $this->redirectToRoleDashboard($user);
    }

    /**
     * Redirect user to their role-specific dashboard.
     *
     * @param User $user
     * @return RedirectResponse
     */
    private function redirectToRoleDashboard(User $user): RedirectResponse
    {
        $role = $user->role->slug;

        switch ($role) {
            case 'admin':
                return redirect()->route('admin.dashboard')
                    ->with('success', __('Welcome to the Admin Dashboard!'));

            case 'manager':
                return redirect()->route('manager.dashboard')
                    ->with('success', __('Welcome to the Manager Dashboard!'));

            case 'employee':
                return redirect()->route('employee.dashboard')
                    ->with('success', __('Welcome! Your tasks and schedules are ready.'));

            case 'provider':
                return redirect()->route('provider.dashboard')
                    ->with('success', __('Welcome! Manage your services, bookings, and insights.'));

            case 'activity_provider':
                return redirect()->route('provider.dashboard')
                    ->with('success', __('Welcome! Manage your activities and registrations.'));

            case 'client':
            default:
                return redirect()->route('user.dashboard')
                    ->with('success', __('Welcome! Explore activities, parenting modules, and AI support.'));
        }
    }
}

<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

class RegisterController extends Controller
{
    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = '/home';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * Show the registration form with roles list.
     *
     * @return \Illuminate\View\View
     */
    public function showRegistrationForm()
    {
        $roles = collect();

        // Try Spatie roles first
        if (class_exists(\Spatie\Permission\Models\Role::class)) {
            try {
                $roles = \Spatie\Permission\Models\Role::all();
            } catch (\Exception $e) {
                $roles = collect();
            }
        }

        // Fallback to an app Role model
        if ($roles->isEmpty() && class_exists(\App\Models\Role::class)) {
            try {
                $roles = \App\Models\Role::all();
            } catch (\Exception $e) {
                $roles = collect();
            }
        }

        // Final fallback static set
        if ($roles->isEmpty()) {
            $roles = collect([
                (object)['id' => 1, 'slug' => 'client', 'name' => 'Parent / Client'],
                (object)['id' => 2, 'slug' => 'provider', 'name' => 'Provider Admin'],
                (object)['id' => 3, 'slug' => 'provider-professional', 'name' => 'Provider (Professional)'],
                (object)['id' => 4, 'slug' => 'provider-bonding', 'name' => 'Provider (Bonding)'],
                (object)['id' => 5, 'slug' => 'admin', 'name' => 'Super Admin'],
            ]);
        }

        return view('auth.register', compact('roles'));
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'role_id' => ['nullable', 'string'], // using slug value from form now
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\Models\User
     */
    protected function create(array $data)
    {
        // Default role
        $roleSlug = 'client';
        $roleId = null;

        // If client sent role slug, map to role id
        if (!empty($data['role_id'])) {
            $candidateSlug = strtolower($data['role_id']);

            // Try spatie
            if (class_exists(\Spatie\Permission\Models\Role::class)) {
                $r = \Spatie\Permission\Models\Role::where('name', $candidateSlug)->orWhere('name', ucfirst($candidateSlug))->first();
                if ($r) { $roleId = $r->id; $roleSlug = $r->name; }
            }

            // Try app Role model
            if (empty($roleId) && class_exists(\App\Models\Role::class)) {
                $r = \App\Models\Role::where('slug', $candidateSlug)->orWhere('name', $candidateSlug)->first();
                if ($r) { $roleId = $r->id; $roleSlug = $r->slug ?? $r->name; }
            }

            // If still empty, map some known slugs
            if (empty($roleId)) {
                $known = ['client','employee','provider','provider-professional','provider-bonding'];
                if (in_array($candidateSlug, $known)) {
                    $roleSlug = $candidateSlug;
                } else {
                    $roleSlug = 'client';
                }
            }
        }

        // Attempt to find roleId if not found
        if (empty($roleId)) {
            if (class_exists(\App\Models\Role::class)) {
                $r = \App\Models\Role::where('slug','client')->orWhere('name','client')->first();
                if ($r) $roleId = $r->id;
            }
            if (empty($roleId) && class_exists(\Spatie\Permission\Models\Role::class)) {
                $r = \Spatie\Permission\Models\Role::where('name','client')->first();
                if ($r) $roleId = $r->id;
            }
        }

        if (empty($roleId)) $roleId = 1;

        $userData = [
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'role' => $roleSlug,
            'role_id' => $roleId,
        ];

        if ($roleSlug === 'provider') {
            $userData['provider_status'] = 'pending';
        }

        $user = User::create($userData);

        if (method_exists($user, 'assignRole')) {
            try { $user->assignRole($roleSlug); } catch (\Exception $e) { }
        }

        return $user;
    }
}

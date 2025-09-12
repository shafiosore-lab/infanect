<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Role;
use App\Models\Provider;
use App\Providers\RouteServiceProvider;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schema;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view with enhanced role handling.
     */
    public function create(): View
    {
        // Get roles with enhanced error handling
        $roles = collect([]);

        try {
            if (Schema::hasTable('roles')) {
                $roles = Role::where('is_active', true)->orderBy('name')->get();
            }
        } catch (\Exception $e) {
            \Log::warning('Could not fetch roles from database', ['error' => $e->getMessage()]);
        }

        // Comprehensive fallback roles to prevent registration page errors
        if ($roles->isEmpty()) {
            $roles = collect([
                (object)['id' => 1, 'slug' => 'client', 'name' => 'Client'],
                (object)['id' => 2, 'slug' => 'employee', 'name' => 'Employee'],
                (object)['id' => 3, 'slug' => 'provider', 'name' => 'Provider'],
                (object)['id' => 4, 'slug' => 'provider-professional', 'name' => 'Professional Provider'],
                (object)['id' => 5, 'slug' => 'provider-bonding', 'name' => 'Bonding Provider'],
                (object)['id' => 6, 'slug' => 'manager', 'name' => 'Manager'],
                (object)['id' => 7, 'slug' => 'admin', 'name' => 'Administrator'],
                (object)['id' => 8, 'slug' => 'super-admin', 'name' => 'Super Administrator'],
            ]);
        }

        return view('auth.register', compact('roles'));
    }

    /**
     * Handle an incoming registration request with enhanced provider support.
     */
    public function store(Request $request): RedirectResponse
    {
        // Enhanced validation with better messages
        $request->validate([
            'name' => ['required', 'string', 'max:255', 'min:2'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:'.User::class],
            'phone' => ['nullable', 'string', 'max:20', 'regex:/^[\+]?[0-9\s\-\(\)]{10,20}$/'],
            'department' => ['nullable', 'string', 'max:100'],
            'role_id' => ['required', 'string', 'max:50'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ], [
            'name.min' => 'Name must be at least 2 characters long',
            'phone.regex' => 'Please enter a valid phone number',
            'role_id.required' => 'Please select an account type',
        ]);

        // Start database transaction for data integrity
        DB::beginTransaction();

        try {
            // Resolve role with enhanced error handling
            $role = $this->resolveRole($request->role_id);

            if (!$role) {
                throw new \InvalidArgumentException('Invalid role selected: ' . $request->role_id);
            }

            // Create user with proper integer role ID
            $user = User::create([
                'name' => trim($request->name),
                'email' => strtolower(trim($request->email)),
                'phone' => $request->phone ? preg_replace('/[^\d\+\-\(\)\s]/', '', $request->phone) : null,
                'department' => $request->department ? trim($request->department) : null,
                'role_id' => $role->id, // ✅ Store integer ID, not string
                'password' => Hash::make($request->password),
                'email_verified_at' => now(), // Auto-verify for simplicity
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            // Enhanced provider initialization for provider roles
            if ($this->isProviderRole($role->slug)) {
                $this->initializeProviderProfile($user, $role);
            }

            // Log successful registration with role information
            Log::info('User registered successfully', [
                'user_id' => $user->id,
                'email' => $user->email,
                'role_slug' => $role->slug,
                'role_id' => $role->id,
                'is_provider' => $this->isProviderRole($role->slug),
                'ip_address' => $request->ip()
            ]);

            DB::commit();

            // Fire registration event
            event(new Registered($user));

            // Login user immediately
            Auth::login($user);

            // Enhanced role-based redirect with success messages
            return $this->redirectAfterRegistration($user, $role);

        } catch (\Exception $e) {
            DB::rollback();

            // Enhanced error logging
            Log::error('User registration failed', [
                'error' => $e->getMessage(),
                'email' => $request->email,
                'role_id' => $request->role_id,
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent()
            ]);

            return back()
                ->withInput($request->except('password', 'password_confirmation'))
                ->withErrors([
                    'registration' => 'Registration failed: ' . $e->getMessage() . '. Please try again.'
                ]);
        }
    }

    /**
     * Enhanced role resolution with multiple fallback strategies
     */
    private function resolveRole(string $roleIdentifier): ?Role
    {
        try {
            // Strategy 1: Try to find by numeric ID
            if (is_numeric($roleIdentifier)) {
                $role = Role::find($roleIdentifier);
                if ($role && $role->is_active) {
                    return $role;
                }
            }

            // Strategy 2: Find by slug (most common case)
            $role = Role::where('slug', $roleIdentifier)
                       ->where('is_active', true)
                       ->first();
            if ($role) {
                return $role;
            }

            // Strategy 3: Find by name (fallback)
            $role = Role::where('name', 'like', '%' . $roleIdentifier . '%')
                       ->where('is_active', true)
                       ->first();
            if ($role) {
                return $role;
            }

            // Strategy 4: Create role if in development environment
            if (app()->environment(['local', 'development', 'testing'])) {
                return $this->createRoleIfMissing($roleIdentifier);
            }

        } catch (\Exception $e) {
            Log::warning('Role resolution failed', [
                'identifier' => $roleIdentifier,
                'error' => $e->getMessage()
            ]);
        }

        return null;
    }

    /**
     * Create missing role in development with proper defaults
     */
    private function createRoleIfMissing(string $slug): ?Role
    {
        $roleDefinitions = [
            'client' => [
                'name' => 'Client',
                'description' => 'Family members and individuals seeking services',
                'permissions' => ['view_activities', 'book_services', 'submit_mood']
            ],
            'employee' => [
                'name' => 'Employee',
                'description' => 'General staff members',
                'permissions' => ['view_activities', 'book_services']
            ],
            'provider' => [
                'name' => 'Provider',
                'description' => 'General service providers',
                'permissions' => ['manage_services', 'view_bookings', 'manage_clients']
            ],
            'provider-professional' => [
                'name' => 'Professional Provider',
                'description' => 'Healthcare professionals, therapists, medical services',
                'permissions' => ['manage_services', 'view_bookings', 'manage_clients', 'access_ai_tools', 'view_mood_insights']
            ],
            'provider-bonding' => [
                'name' => 'Bonding Provider',
                'description' => 'Community organizers, family activity coordinators',
                'permissions' => ['manage_activities', 'create_events', 'manage_community']
            ],
            'manager' => [
                'name' => 'Manager',
                'description' => 'Department managers with limited admin access',
                'permissions' => ['view_reports', 'manage_team', 'approve_content']
            ],
            'admin' => [
                'name' => 'Administrator',
                'description' => 'System administrators',
                'permissions' => ['manage_users', 'manage_system', 'view_analytics', 'manage_content']
            ],
            'super-admin' => [
                'name' => 'Super Administrator',
                'description' => 'Full system access and control',
                'permissions' => ['*']
            ]
        ];

        if (isset($roleDefinitions[$slug])) {
            try {
                $roleData = $roleDefinitions[$slug];
                return Role::create([
                    'name' => $roleData['name'],
                    'slug' => $slug,
                    'description' => $roleData['description'],
                    'permissions' => json_encode($roleData['permissions']),
                    'is_active' => true,
                    'created_at' => now(),
                    'updated_at' => now()
                ]);
            } catch (\Exception $e) {
                Log::error('Failed to create missing role', [
                    'slug' => $slug,
                    'error' => $e->getMessage()
                ]);
            }
        }

        return null;
    }

    /**
     * Enhanced provider role detection
     */
    private function isProviderRole(string $roleSlug): bool
    {
        return in_array($roleSlug, [
            'provider',
            'provider-professional',
            'provider-bonding',
            'provider-organization',
            'provider-freelancer',
            'provider-educator'
        ]);
    }

    /**
     * Enhanced provider profile initialization with KYC setup
     */
    private function initializeProviderProfile(User $user, Role $role): void
    {
        // Enhanced provider metadata
        $providerData = [
            'provider_type' => $role->slug, // ✅ Store as string for provider_type
            'registration_stage' => 'profile_created',
            'kyc_status' => 'not_started',
            'onboarding_completed' => false,
            'registration_date' => now()->toISOString(),
            'dashboard_preferences' => [
                'sidebar_collapsed' => false,
                'default_view' => 'overview',
                'notifications_enabled' => true,
                'theme' => 'light'
            ],
            'verification_requirements' => $this->getVerificationRequirements($role->slug),
            'setup_progress' => [
                'profile_completed' => true,
                'documents_uploaded' => false,
                'services_added' => false,
                'kyc_submitted' => false
            ]
        ];

        // Update user with provider data
        $user->update([
            'provider_data' => json_encode($providerData)
        ]);

        // Create provider profile if providers table exists
        $this->createProviderProfileRecord($user, $role);
    }

    /**
     * Get verification requirements based on provider type
     */
    private function getVerificationRequirements(string $providerType): array
    {
        $requirements = [
            'provider-professional' => [
                'documents' => ['id_document', 'professional_license', 'insurance_certificate'],
                'background_check' => true,
                'reference_checks' => true,
                'estimated_approval_time' => '3-5 business days'
            ],
            'provider-bonding' => [
                'documents' => ['id_document', 'background_check'],
                'background_check' => true,
                'reference_checks' => false,
                'estimated_approval_time' => '1-2 business days'
            ],
            'provider' => [
                'documents' => ['id_document'],
                'background_check' => false,
                'reference_checks' => false,
                'estimated_approval_time' => '1 business day'
            ]
        ];

        return $requirements[$providerType] ?? $requirements['provider'];
    }

    /**
     * Create provider profile record with enhanced error handling
     */
    private function createProviderProfileRecord(User $user, Role $role): void
    {
        try {
            if (!Schema::hasTable('providers')) {
                Log::info('Providers table does not exist, skipping provider profile creation', [
                    'user_id' => $user->id
                ]);
                return;
            }

            $providerData = [
                'user_id' => $user->id,
                'provider_type' => $role->slug, // ✅ String value for provider_type column
                'business_name' => $user->name . "'s Practice",
                'status' => 'pending',
                'kyc_status' => 'not_started',
                'kyc_documents' => json_encode([]),
                'specializations' => json_encode([]),
                'availability' => json_encode([]),
                'created_at' => now(),
                'updated_at' => now()
            ];

            Provider::create($providerData);

            Log::info('Provider profile created successfully', [
                'user_id' => $user->id,
                'provider_type' => $role->slug
            ]);

        } catch (\Exception $e) {
            // Non-critical error - log but don't fail registration
            Log::warning('Could not create provider profile record', [
                'user_id' => $user->id,
                'error' => $e->getMessage(),
                'provider_type' => $role->slug
            ]);
        }
    }

    /**
     * Enhanced role-based redirect with appropriate success messages
     */
    private function redirectAfterRegistration(User $user, Role $role): RedirectResponse
    {
        // Determine redirect route and message based on role
        $redirectData = match($role->slug) {
            'provider-professional' => [
                'route' => 'dashboard.provider-professional',
                'fallback' => 'dashboard',
                'message' => 'Welcome to Infanect! Complete your professional provider profile and KYC documents to start offering services.',
                'alert_type' => 'info'
            ],
            'provider-bonding' => [
                'route' => 'dashboard.provider-bonding',
                'fallback' => 'dashboard',
                'message' => 'Welcome to Infanect! Set up your bonding provider profile to start creating community activities.',
                'alert_type' => 'info'
            ],
            'provider', 'provider-organization', 'provider-freelancer', 'provider-educator' => [
                'route' => 'dashboard.provider-professional',
                'fallback' => 'dashboard',
                'message' => 'Welcome to Infanect! Complete your provider registration to start offering services.',
                'alert_type' => 'info'
            ],
            'admin', 'super-admin' => [
                'route' => 'dashboard.super-admin',
                'fallback' => 'dashboard',
                'message' => 'Welcome to Infanect Admin Dashboard! You have full system access.',
                'alert_type' => 'success'
            ],
            'manager' => [
                'route' => 'dashboard.admin',
                'fallback' => 'dashboard',
                'message' => 'Welcome to Infanect Manager Dashboard! Manage your team and view reports.',
                'alert_type' => 'success'
            ],
            default => [
                'route' => 'dashboard.client',
                'fallback' => 'dashboard',
                'message' => 'Welcome to Infanect! Discover activities and services for your family.',
                'alert_type' => 'success'
            ]
        };

        // Try primary route, fall back to secondary, then to HOME
        if (\Illuminate\Support\Facades\Route::has($redirectData['route'])) {
            return redirect()->route($redirectData['route'])
                           ->with($redirectData['alert_type'], $redirectData['message']);
        }

        if (\Illuminate\Support\Facades\Route::has($redirectData['fallback'])) {
            return redirect()->route($redirectData['fallback'])
                           ->with($redirectData['alert_type'], $redirectData['message']);
        }

        // Final fallback to RouteServiceProvider::HOME
        return redirect(RouteServiceProvider::HOME)
                      ->with('success', 'Registration successful! Welcome to Infanect.');
    }
}

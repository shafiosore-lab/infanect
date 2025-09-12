<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        \App\Models\Provider::class => \App\Policies\ProviderPolicy::class,
        // Add other model => policy mappings here
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        $this->registerPolicies();

        // Example gate for managing providers
        Gate::define('manage-providers', function ($user) {
            return in_array($user->role?->slug ?? $user->role, [
                'super-admin',
                'provider-professional',
            ]);
        });

        // Dynamically register gates based on config/roles_permissions.php
        $mappings = config('roles_permissions.mappings', []);
        foreach ($mappings as $role => $permissions) {
            foreach ($permissions as $perm => $allowed) {
                Gate::define($perm, function ($user) use ($role, $allowed) {
                    if (!$user || !$user->role) {
                        return false;
                    }

                    // Allow checking against either role slug (string) or role object
                    $userRole = is_object($user->role) ? $user->role->slug : $user->role;

                    return $userRole === $role && (bool)$allowed;
                });
            }
        }
    }
}

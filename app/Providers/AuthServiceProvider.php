<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    protected $policies = [
        // ...existing policies...
        \App\Models\Provider::class => \App\Policies\ProviderPolicy::class,
    ];

    public function boot(): void
    {
        $this->registerPolicies();

        \Gate::define('manage-providers', function($user){
            return in_array($user->role, ['super-admin','provider-professional']);
        });

        $mappings = config('roles_permissions.mappings', []);
        foreach ($mappings as $role => $perms) {
            foreach ($perms as $perm => $allowed) {
                Gate::define($perm, function ($user) use ($role, $allowed) {
                    if (!$user) return false;
                    return ($user->role && $user->role->slug === $role) ? (bool)$allowed : false;
                });
            }
        }
    }
}

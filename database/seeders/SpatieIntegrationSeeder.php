<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Role as LocalRole;
use Illuminate\Support\Facades\Log;

class SpatieIntegrationSeeder extends Seeder
{
    public function run()
    {
        // Only run if Spatie Role model exists
        if (!class_exists('\Spatie\Permission\Models\Role')) {
            $this->command->info('Spatie package not installed; skipping SpatieIntegrationSeeder.');
            return;
        }

        $mappings = config('roles_permissions.mappings', []);

        foreach (LocalRole::all() as $localRole) {
            // Create or update Spatie role
            $spRole = \Spatie\Permission\Models\Role::firstOrCreate(['name' => $localRole->slug], ['guard_name' => 'web']);

            // Assign permissions from mapping if available
            $perms = $mappings[$localRole->slug] ?? [];
            foreach ($perms as $perm => $allowed) {
                if ($allowed) {
                    $spPerm = \Spatie\Permission\Models\Permission::firstOrCreate(['name' => $perm], ['guard_name' => 'web']);
                    $spRole->givePermissionTo($spPerm);
                }
            }

            $this->command->info('Synced role to Spatie: '.$spRole->name);
        }

        // Optionally assign existing users to Spatie roles
        foreach (\App\Models\User::with('role')->get() as $user) {
            if ($user->role) {
                $user->assignRole($user->role->slug);
            }
        }

        $this->command->info('Spatie role integration complete.');
    }
}

<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class RolesAndPermissionsSeeder extends Seeder
{
    public function run()
    {
        $roles = [
            ['name' => 'Parent / Client', 'slug' => 'client'],
            ['name' => 'Provider Admin', 'slug' => 'provider'],
            ['name' => 'Provider (Professional)', 'slug' => 'provider-professional'],
            ['name' => 'Provider (Bonding)', 'slug' => 'provider-bonding'],
            ['name' => 'Super Admin', 'slug' => 'admin'],
            ['name' => 'Manager', 'slug' => 'manager'],
        ];

        $permissions = [
            'manage users', 'manage services', 'view bookings', 'manage activities', 'view notifications', 'approve providers', 'submit mood', 'view recommendations'
        ];

        // If spatie is installed, use it
        if (class_exists(\Spatie\Permission\Models\Permission::class) && class_exists(\Spatie\Permission\Models\Role::class)) {
            $permModel = \Spatie\Permission\Models\Permission::class;
            $roleModel = \Spatie\Permission\Models\Role::class;

            foreach ($permissions as $p) {
                try { $permModel::firstOrCreate(['name' => $p]); } catch (\Exception $e) { }
            }

            // create roles and assign sensible permissions
            foreach ($roles as $r) {
                try {
                    $role = $roleModel::firstOrCreate(['name' => $r['slug']]);

                    switch ($r['slug']) {
                        case 'admin':
                            $role->givePermissionTo($permissions);
                            break;
                        case 'provider':
                        case 'provider-professional':
                            $role->givePermissionTo(['manage services','view bookings','view notifications']);
                            break;
                        case 'provider-bonding':
                            $role->givePermissionTo(['view bookings','view notifications']);
                            break;
                        case 'client':
                            $role->givePermissionTo(['submit mood','view recommendations']);
                            break;
                        case 'manager':
                            $role->givePermissionTo(['view bookings','view notifications']);
                            break;
                    }
                } catch (\Exception $e) { }
            }

            return;
        }

        // Fallback: insert into roles table if it exists
        if (Schema::hasTable('roles')) {
            foreach ($roles as $r) {
                try {
                    DB::table('roles')->updateOrInsert(
                        ['slug' => $r['slug']],
                        ['name' => $r['name'], 'slug' => $r['slug'], 'created_at' => now(), 'updated_at' => now()]
                    );
                } catch (\Exception $e) { }
            }
        }
    }
}

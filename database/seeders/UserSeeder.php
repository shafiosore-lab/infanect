<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;

class UserSeeder extends Seeder
{
    public function run()
    {
        // Resolve or create roles and obtain IDs
        $roleIds = [
            'provider' => null,
            'client' => null,
            'admin' => null,
        ];

        // If spatie is installed, use it
        if (class_exists(\Spatie\Permission\Models\Role::class)) {
            try {
                $spatieRoles = \Spatie\Permission\Models\Role::all()->keyBy(function($r){
                    return strtolower(str_replace(' ', '-', $r->name));
                });

                foreach (['provider','client','admin'] as $slug) {
                    if (isset($spatieRoles[$slug])) {
                        $roleIds[$slug] = $spatieRoles[$slug]->id;
                    } else {
                        $r = \Spatie\Permission\Models\Role::firstOrCreate(['name' => $slug]);
                        $roleIds[$slug] = $r->id;
                    }
                }
            } catch (\Exception $e) {
                // ignore and fallback
            }
        }

        // Fallback: roles table
        if (empty(array_filter($roleIds)) && Schema::hasTable('roles')) {
            $needed = [
                'client' => 'Parent / Client',
                'provider' => 'Provider Admin',
                'admin' => 'Super Admin',
            ];

            foreach ($needed as $slug => $name) {
                // Try find by slug or name
                $r = DB::table('roles')->where('slug', $slug)->orWhere('name', $name)->first();
                if (! $r) {
                    try {
                        $id = DB::table('roles')->updateOrInsert(
                            ['slug' => $slug],
                            ['name' => $name, 'slug' => $slug, 'updated_at' => now(), 'created_at' => now()]
                        );
                        // updateOrInsert returns boolean, fetch the record
                        $r = DB::table('roles')->where('slug', $slug)->first();
                    } catch (\Exception $e) {
                        // if insert fails due to race condition, fetch existing
                        $r = DB::table('roles')->where('slug', $slug)->orWhere('name', $name)->first();
                    }
                }

                if ($r) $roleIds[$slug] = $r->id;
            }
        }

        // Final fallback: set to 1 if still null
        foreach ($roleIds as $k => $v) {
            if (empty($v)) $roleIds[$k] = 1;
        }

        // Create provider user (idempotent)
        DB::table('users')->updateOrInsert(
            ['email' => 'provider@infanect.com'],
            [
                'name' => 'Peter Provider',
                'password' => Hash::make('password'),
                'role_id' => $roleIds['provider'],
                'phone' => '+254700987654',
                'department' => 'Provider Services',
                'is_active' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ]
        );

        // Create client user
        DB::table('users')->updateOrInsert(
            ['email' => 'client@infanect.com'],
            [
                'name' => 'Cathy Client',
                'password' => Hash::make('password'),
                'role_id' => $roleIds['client'],
                'phone' => '+254700123456',
                'department' => 'Families',
                'is_active' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ]
        );

        // Create admin user
        DB::table('users')->updateOrInsert(
            ['email' => 'admin@infanect.com'],
            [
                'name' => 'Super Admin',
                'password' => Hash::make('password'),
                'role_id' => $roleIds['admin'],
                'phone' => null,
                'department' => 'Administration',
                'is_active' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ]
        );
    }
}

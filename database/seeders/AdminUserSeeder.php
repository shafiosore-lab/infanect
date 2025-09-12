<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Role;

class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        // Ensure super-admin role exists
        $adminRole = Role::firstOrCreate(
            ['slug' => 'super-admin'],
            ['name' => 'Super Admin', 'description' => 'Platform administrator']
        );

        // Create or update admin user(s) idempotently
        $admins = [
            ['email' => 'admin@infanect.local', 'name' => 'Platform Admin', 'password' => 'password'],
            ['email' => 'shafiosore@gmail.com', 'name' => 'Admin User', 'password' => 'password'],
        ];

        foreach ($admins as $a) {
            User::updateOrCreate(
                ['email' => $a['email']],
                [
                    'name' => $a['name'],
                    'password' => Hash::make($a['password']),
                    'role_id' => $adminRole->id,
                    'is_active' => true,
                    'email_verified_at' => now(),
                ]
            );
        }
    }
}

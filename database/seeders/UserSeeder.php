<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Role;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Fetch roles (RoleSeeder must run first)
        $clientRole   = Role::where('slug', 'client')->first();
        $employeeRole = Role::where('slug', 'employee')->first();
        $providerRole = Role::where('slug', 'provider')->first();
        $managerRole  = Role::where('slug', 'manager')->first();
        $adminRole    = Role::where('slug', 'admin')->first();

        // --- Clients ---
        User::firstOrCreate(
            ['email' => 'client@infanect.com'],
            [
                'name'       => 'John Client',
                'password'   => Hash::make('password'),
                'role_id'    => $clientRole?->id ?? $employeeRole?->id,
                'phone'      => '+254700123456',
                'department' => 'Client',
                'is_active'  => true,
            ]
        );

        User::firstOrCreate(
            ['email' => 'sarah.client@example.com'],
            [
                'name'       => 'Sarah Johnson',
                'password'   => Hash::make('password'),
                'role_id'    => $clientRole?->id ?? $employeeRole?->id,
                'phone'      => '+1234567890',
                'department' => 'Parent',
                'is_active'  => true,
            ]
        );

        // --- Provider ---
        User::firstOrCreate(
            ['email' => 'provider@infanect.com'],
            [
                'name'       => 'Peter Provider',
                'password'   => Hash::make('password'),
                'role_id'    => $providerRole?->id,
                'phone'      => '+254700987654',
                'department' => 'Provider Services',
                'is_active'  => true,
            ]
        );

        // --- Manager ---
        User::firstOrCreate(
            ['email' => 'manager@infanect.com'],
            [
                'name'       => 'Mary Manager',
                'password'   => Hash::make('password'),
                'role_id'    => $managerRole?->id,
                'phone'      => '+254711222333',
                'department' => 'Management',
                'is_active'  => true,
            ]
        );

        // --- Admin ---
        User::firstOrCreate(
            ['email' => 'admin@infanect.com'],
            [
                'name'       => 'Super Admin',
                'password'   => Hash::make('password'),
                'role_id'    => $adminRole?->id,
                'phone'      => '+1111111111',
                'department' => 'System',
                'is_active'  => true,
            ]
        );
    }
}

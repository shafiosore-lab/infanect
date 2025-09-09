<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Role;
use Illuminate\Support\Facades\Hash;

class ProviderUserSeeder extends Seeder
{
    public function run(): void
    {
        $providerRole = Role::where('slug', 'provider')->first() ?? Role::first();
        $employeeRole = Role::where('slug', 'employee')->first() ?? Role::first();

        // Service Provider Admin
        User::firstOrCreate(
            ['email' => 'provider@infanect.com'],
            [
                'name' => 'Service Provider Admin',
                'password' => Hash::make('password'),
                'role_id' => $providerRole->id,
                'phone' => '+254700111111',
                'department' => 'Service Provider',
                'is_active' => true,
            ]
        );

        // Activity Provider Admin
        User::firstOrCreate(
            ['email' => 'activity@infanect.com'],
            [
                'name' => 'Activity Provider Admin',
                'password' => Hash::make('password'),
                'role_id' => $providerRole->id,
                'phone' => '+254700222222',
                'department' => 'Activity Provider',
                'is_active' => true,
            ]
        );

        // Employee under Service Provider
        User::firstOrCreate(
            ['email' => 'employee@infanect.com'],
            [
                'name' => 'Provider Employee',
                'password' => Hash::make('password'),
                'role_id' => $employeeRole->id,
                'phone' => '+254700333333',
                'department' => 'Service Operations',
                'is_active' => true,
            ]
        );
    }
}

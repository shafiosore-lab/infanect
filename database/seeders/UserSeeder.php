<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Role;
use Illuminate\Support\Facades\DB;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Get roles
        $superAdminRole = Role::where('slug', 'super-admin')->first();
        $adminRole = Role::where('slug', 'admin')->first();
        $professionalRole = Role::where('slug', 'provider-professional')->first();
        $bondingRole = Role::where('slug', 'provider-bonding')->first();
        $clientRole = Role::where('slug', 'client')->first();

        if (!$superAdminRole) {
            $superAdminRole = Role::create(['name' => 'Super Admin', 'slug' => 'super-admin', 'description' => 'Full access']);
        }
        if (!$adminRole) {
            $adminRole = Role::create(['name' => 'Admin', 'slug' => 'admin', 'description' => 'Admin access']);
        }
        if (!$professionalRole) {
            $professionalRole = Role::create(['name' => 'Provider Professional', 'slug' => 'provider-professional', 'description' => 'Professional provider']);
        }
        if (!$bondingRole) {
            $bondingRole = Role::create(['name' => 'Provider Bonding', 'slug' => 'provider-bonding', 'description' => 'Bonding provider']);
        }
        if (!$clientRole) {
            $clientRole = Role::create(['name' => 'Client', 'slug' => 'client', 'description' => 'Client user']);
        }

        // Create super admin
        $superAdmin = User::create([
            'name' => 'Super Admin',
            'email' => 'superadmin@infanect.com',
            'email_verified_at' => now(),
            'password' => Hash::make('password'),
            'phone' => '+254700000001',
            'location' => 'Nairobi, Kenya',
            'is_active' => true,
        ]);
        $superAdmin->roles()->attach($superAdminRole->id);

        // Create admin
        $admin = User::create([
            'name' => 'Admin User',
            'email' => 'admin@infanect.com',
            'email_verified_at' => now(),
            'password' => Hash::make('password'),
            'phone' => '+254700000002',
            'location' => 'Nairobi, Kenya',
            'is_active' => true,
        ]);
        $admin->roles()->attach($adminRole->id);

        // Create bonding provider
        $bondingProvider = User::create([
            'name' => 'Test Bonding Provider',
            'email' => 'test@infanect.com',
            'email_verified_at' => now(),
            'password' => Hash::make('password123'),
            'provider_type' => 'provider-bonding',
            'phone' => '+254700000000',
            'location' => 'Nairobi, Kenya',
            'is_active' => true,
        ]);
        $bondingProvider->roles()->attach($bondingRole->id);

        // Create another bonding provider
        $sarahBonding = User::create([
            'name' => 'Sarah Bonding Provider',
            'email' => 'bonding@infanect.com',
            'email_verified_at' => now(),
            'password' => Hash::make('password'),
            'provider_type' => 'provider-bonding',
            'phone' => '+254700000003',
            'location' => 'Nairobi, Kenya',
            'is_active' => true,
        ]);
        $sarahBonding->roles()->attach($bondingRole->id);

        // Create professional provider
        $professional = User::create([
            'name' => 'Dr. John Professional',
            'email' => 'professional@infanect.com',
            'email_verified_at' => now(),
            'password' => Hash::make('password'),
            'provider_type' => 'provider-professional',
            'phone' => '+254700000004',
            'location' => 'Nairobi, Kenya',
            'is_active' => true,
        ]);
        $professional->roles()->attach($professionalRole->id);

        // Create client users
        $clients = [
            ['name' => 'Mary Johnson', 'email' => 'mary@example.com'],
            ['name' => 'Peter Anderson', 'email' => 'peter@example.com'],
            ['name' => 'Grace Wanjiku', 'email' => 'grace@example.com'],
            ['name' => 'David Kimani', 'email' => 'david@example.com'],
            ['name' => 'Ruth Achieng', 'email' => 'ruth@example.com'],
            ['name' => 'James Mwangi', 'email' => 'james@example.com'],
            ['name' => 'Susan Njeri', 'email' => 'susan@example.com'],
            ['name' => 'Michael Ochieng', 'email' => 'michael@example.com'],
        ];

        foreach ($clients as $client) {
            $user = User::create([
                'name' => $client['name'],
                'email' => $client['email'],
                'email_verified_at' => now(),
                'password' => Hash::make('password'),
                'phone' => '+2547' . rand(10000000, 99999999),
                'location' => collect(['Nairobi', 'Mombasa', 'Kisumu', 'Nakuru'])->random() . ', Kenya',
                'is_active' => true,
            ]);
            $user->roles()->attach($clientRole->id);
        }
    }
}

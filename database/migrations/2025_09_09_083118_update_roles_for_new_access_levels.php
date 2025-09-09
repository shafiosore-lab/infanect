<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Update existing roles to match new access levels
        // Handle admin role
        if (DB::table('roles')->where('slug', 'admin')->exists()) {
            DB::table('roles')->where('slug', 'admin')->update([
                'slug' => 'super-admin',
                'name' => 'Super Admin'
            ]);
        }

        // Handle manager role - if it exists and super-admin doesn't, update it
        if (DB::table('roles')->where('slug', 'manager')->exists() &&
            !DB::table('roles')->where('slug', 'super-admin')->exists()) {
            DB::table('roles')->where('slug', 'manager')->update([
                'slug' => 'super-admin',
                'name' => 'Super Admin'
            ]);
        }

        // Handle provider role
        if (DB::table('roles')->where('slug', 'provider')->exists()) {
            DB::table('roles')->where('slug', 'provider')->update([
                'slug' => 'service-provider',
                'name' => 'Service Provider'
            ]);
        }

        // Update employee role name if it exists
        if (DB::table('roles')->where('slug', 'employee')->exists()) {
            DB::table('roles')->where('slug', 'employee')->update(['name' => 'Employee']);
        }

        // Add new roles if they don't exist
        $newRoles = [
            ['name' => 'Activity Provider', 'slug' => 'activity-provider'],
            ['name' => 'User', 'slug' => 'user'],
        ];

        foreach ($newRoles as $role) {
            if (!DB::table('roles')->where('slug', $role['slug'])->exists()) {
                DB::table('roles')->insert([
                    'name' => $role['name'],
                    'slug' => $role['slug'],
                    'created_at' => now(),
                    'updated_at' => now()
                ]);
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};

<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Role;
use Illuminate\Support\Str;

class RoleAssignmentSeeder extends Seeder
{
    public function run()
    {
        $super = Role::where('slug', 'super-admin')->first();
        if (!$super) return;

        // Ensure at least one super-admin exists
        $admin = User::where('email', 'admin@infanect.local')->first();
        if (!$admin) {
            $admin = User::create([
                'name' => 'Platform Admin',
                'email' => 'admin@infanect.local',
                'password' => bcrypt('password'),
                'email_verified_at' => now(),
            ]);
        }
        $admin->role_id = $super->id;
        $admin->save();

        // Assign random roles to first few users
        $roles = Role::whereIn('slug', ['provider-professional', 'provider-bonding', 'client'])->get();
        $users = User::where('id', '!=', $admin->id)->limit(10)->get();
        foreach ($users as $i => $u) {
            $r = $roles[$i % count($roles)];
            $u->role_id = $r->id;
            $u->save();
        }
    }
}

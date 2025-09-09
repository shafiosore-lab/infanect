<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Role;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        $roles = [
            [
                'name' => 'Client',
                'slug' => 'client',
                'description' => 'Default role for end-users/parents/clients.',
            ],
            [
                'name' => 'Employee',
                'slug' => 'employee',
                'description' => 'Regular user who can book activities and access learning modules.',
            ],
            [
                'name' => 'Provider',
                'slug' => 'provider',
                'description' => 'Service provider who can manage activities and services.',
            ],
            [
                'name' => 'Manager',
                'slug' => 'manager',
                'description' => 'Manager with limited administrative access.',
            ],
            [
                'name' => 'Administrator',
                'slug' => 'admin',
                'description' => 'Full system access with complete control.',
            ],
        ];

        foreach ($roles as $roleData) {
            Role::updateOrCreate(
                ['slug' => $roleData['slug']],
                $roleData
            );
        }
    }
}

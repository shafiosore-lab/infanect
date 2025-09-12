<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Role;
use Illuminate\Support\Facades\Log;
use Illuminate\Database\QueryException;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $roles = [
            [
                'name' => 'Super Admin',
                'slug' => 'super-admin',
                'description' => 'Full platform control over users, providers, finances, and AI documents.',
            ],
            [
                'name' => 'Professional Service Provider',
                'slug' => 'provider-professional',
                'description' => 'Doctors, therapists, and professionals delivering services with ERP/CRM and AI docs.',
            ],
            [
                'name' => 'Bonding Activity Provider',
                'slug' => 'provider-bonding',
                'description' => 'Event and activity providers (skating, conferences, workshops) with analytics.',
            ],
            [
                'name' => 'Client',
                'slug' => 'client',
                'description' => 'Users who book services, view analytics, and download approved AI docs.',
            ],
        ];

        foreach ($roles as $roleData) {
            try {
                $role = Role::where('slug', $roleData['slug'])
                    ->orWhere('name', $roleData['name'])
                    ->first();

                if ($role) {
                    $role->fill($roleData);
                    $role->save();
                } else {
                    Role::create($roleData);
                }
            } catch (QueryException $e) {
                // If a unique constraint race occurs, log and continue
                Log::warning('RoleSeeder skipped duplicate role: ' . $roleData['name'] . ' - ' . $e->getMessage());
                continue;
            }
        }

        $this->command->info('Roles seeded/updated successfully.');
    }
}

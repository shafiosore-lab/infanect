<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Booking;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            RoleSeeder::class,            // Must run first (creates roles)
            AdminUserSeeder::class,       // Seeds main admin user
            UserSeeder::class,            // Creates client/employee test users
            ProviderUserSeeder::class,    // Creates provider admins and employees

            CategoriesSeeder::class,      // Activity/Service categories
            ServiceProviderSeeder::class, // Seeds service provider organizations
            ServiceSeeder::class,         // Seeds services
            ActivitySeeder::class,        // Seeds bonding activities/events
            ProviderSeeder::class,        // Seeds provider details

            ReviewSeeder::class,          // Seeds reviews/ratings
            TransactionSeeder::class,     // Seeds transactions/payments
            ParentingModuleSeeder::class, // Seeds parenting modules
            TaskSeeder::class,            // Seeds sample tasks for dashboard
        ]);

        // 🚀 Use factory for richer booking seeding
        Booking::factory(20)->create();
    }
}

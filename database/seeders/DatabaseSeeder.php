<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Comprehensive database seeding that handles all table creation and data insertion.
     * Enhanced with additional development features and sample data.
     */
    public function run(): void
    {
        $this->call([
            RoleSeeder::class,
            UserSeeder::class,
            ProviderSeeder::class,
            ServiceSeeder::class,
            ActivitySeeder::class, // Now uses factories
            BookingSeeder::class,
            ReviewSeeder::class,
            EngagementSeeder::class,
            MoodSubmissionSeeder::class,
        ]);

        $this->command->info('🎉 Database seeded successfully!');
        $this->command->info('📧 Test login: test@infanect.com');
        $this->command->info('🔑 Password: password123');
        $this->command->info('📊 Activities created: ' . \App\Models\Activity::count());
    }
}

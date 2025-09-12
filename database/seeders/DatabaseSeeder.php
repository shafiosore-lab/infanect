<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Booking;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $ordered = [
            \Database\Seeders\RolesAndPermissionsSeeder::class,
            \Database\Seeders\UserSeeder::class,
            \Database\Seeders\RecommendationSeeder::class,
            \Database\Seeders\ActivityTemplateSeeder::class,
            \Database\Seeders\ReviewSeeder::class,
            \Database\Seeders\TransactionSeeder::class,
        ];

        foreach ($ordered as $seeder) {
            if (! class_exists($seeder)) continue;

            try {
                $this->call($seeder);
            } catch (\Throwable $e) {
                // Log or ignore - continue with next seeder
                // You can enable logging here if desired: info('Seeder failed: '.$seeder.': '.$e->getMessage());
            }
        }

        // 🚀 Use factory for richer booking seeding
        Booking::factory(20)->create();
    }
}

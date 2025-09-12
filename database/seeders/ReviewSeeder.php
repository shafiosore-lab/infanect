<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class ReviewSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        if (! Schema::hasTable('reviews')) {
            return;
        }

        if (! Schema::hasTable('providers')) {
            // No providers table to reference - skip seeding reviews
            return;
        }

        // Find an existing provider to attach reviews to
        $provider = DB::table('providers')->inRandomOrder()->first();
        if (! $provider || ! isset($provider->id)) {
            // No provider records available - skip seeding reviews
            return;
        }

        $providerId = $provider->id;

        // Double-check provider exists (safety)
        $exists = DB::table('providers')->where('id', $providerId)->exists();
        if (! $exists) return;

        // Insert sample reviews idempotently and safely
        $samples = [
            ['reviewer_name' => 'John Doe', 'country_code' => 'US', 'rating' => 5, 'comment' => 'Excellent service! Highly recommend.', 'is_approved' => 1],
            ['reviewer_name' => 'Alice Johnson', 'country_code' => 'KE', 'rating' => 5, 'comment' => 'Amazing bonding activities for families.', 'is_approved' => 1],
        ];

        foreach ($samples as $s) {
            try {
                DB::table('reviews')->updateOrInsert(
                    ['provider_id' => $providerId, 'reviewer_name' => $s['reviewer_name']],
                    array_merge(['provider_id' => $providerId, 'created_at' => now(), 'updated_at' => now()], $s)
                );
            } catch (\Exception $e) {
                // ignore FK or other insert errors
            }
        }
    }
}

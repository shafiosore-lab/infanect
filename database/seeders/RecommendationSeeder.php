<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class RecommendationSeeder extends Seeder
{
    public function run()
    {
        // Seed activities (if table exists)
        if (Schema::hasTable('activities')) {
            $now = now();
            $samples = [
                ['title' => 'Nature Walk', 'description' => 'A gentle nature walk for families', 'category' => 'outdoor', 'meta' => json_encode(['age_groups'=>['3-5']])],
                ['title' => 'Mini Soccer', 'description' => 'Basic soccer skills for kids', 'category' => 'sports', 'meta' => json_encode(['age_groups'=>['6-8']])],
            ];

            foreach ($samples as $s) {
                $row = ['title' => $s['title'], 'created_at' => $now, 'updated_at' => $now];
                if (Schema::hasColumn('activities', 'description')) $row['description'] = $s['description'];
                if (Schema::hasColumn('activities', 'category')) $row['category'] = $s['category'];
                if (Schema::hasColumn('activities', 'meta')) $row['meta'] = $s['meta'];

                try { DB::table('activities')->updateOrInsert(['title' => $s['title']], $row); } catch (\Exception $e) { }
            }
        }

        // Seed providers safely (if providers table exists)
        if (! Schema::hasTable('providers')) {
            return;
        }

        // Find a user to link provider to
        $user = null;
        if (Schema::hasTable('users')) {
            $user = DB::table('users')->first();
        }
        if (! $user) {
            // No users to attach providers to - skip provider seeding
            return;
        }

        $providerSamples = [
            [
                'user_id' => $user->id,
                'business_name' => 'Gibson-Hammes',
                'category' => 'Therapy',
                'country' => 'ZA',
                'city' => 'Cape Town',
                'state' => 'Western Cape',
                'timezone' => 'Africa/Johannesburg',
                'language' => 'en',
                'status' => 'approved',
                'email' => 'cecile.senger@example.net',
                'phone' => '442-454-7168',
                'address' => '523 Anna Land',
                'postal_code' => '13963-9579',
                'latitude' => ' -33.9249',
                'longitude' => '18.4241',
                'logo' => null,
                'is_available' => 1,
                'avg_rating' => 3.94,
                'total_reviews' => 221,
                'total_revenue' => 50109.98,
                'service_type' => 'general',
            ],
        ];

        foreach ($providerSamples as $p) {
            $row = ['created_at' => now(), 'updated_at' => now()];
            // Only include keys that exist in providers table
            foreach ($p as $col => $val) {
                if (Schema::hasColumn('providers', $col)) {
                    $row[$col] = $val;
                }
            }

            // Use email or business_name to identify
            $where = [];
            if (isset($row['email'])) $where['email'] = $row['email'];
            elseif (isset($row['business_name'])) $where['business_name'] = $row['business_name'];
            else continue;

            try {
                DB::table('providers')->updateOrInsert($where, $row);
            } catch (\Exception $e) {
                // ignore FK or other insert errors
            }
        }
    }
}

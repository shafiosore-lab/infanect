<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Provider;
use App\Models\User;

class ProviderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Ensure there are some users to link to providers
        $firstUser = User::first(); // fallback user if needed

        $providers = [
            [
                'user_id' => $firstUser?->id ?? 1,
                'name' => 'Dr. Sarah Johnson',
                'business_name' => 'Sarah Johnson Counseling',
                'location' => 'North America',
                'rating' => 4.9,
                'services' => json_encode(['Bonding Activities', 'Family Counseling']),
                'bio' => 'Specializes in strengthening parent-child bonds through evidence-based therapeutic techniques.',
                'price' => 120.00,
                'image_url' => 'https://images.unsplash.com/photo-1559839734-2b71ea197ec2?ixlib=rb-4.0.3&auto=format&fit=crop&w=112&h=112&q=85',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'user_id' => $firstUser?->id ?? 1,
                'name' => 'Maria Rodriguez',
                'business_name' => 'Rodriguez Family Therapy',
                'location' => 'Europe',
                'rating' => 4.8,
                'services' => json_encode(['Parenting Education', 'Family Counseling']),
                'bio' => 'Expert in multicultural family dynamics and bilingual therapy approaches.',
                'price' => 110.00,
                'image_url' => 'https://images.unsplash.com/photo-1594824388511-923247e6e01e?ixlib=rb-4.0.3&auto=format&fit=crop&w=112&h=112&q=85',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'user_id' => $firstUser?->id ?? 1,
                'name' => 'Dr. Ahmed Hassan',
                'business_name' => 'Hassan Parenting Solutions',
                'location' => 'Asia',
                'rating' => 4.7,
                'services' => json_encode(['Bonding Activities', 'Parenting Education']),
                'bio' => 'Focuses on attachment theory and trauma-informed care to help families heal.',
                'price' => 100.00,
                'image_url' => 'https://images.unsplash.com/photo-1612349317150-e3d4ac1d0e35?ixlib=rb-4.0.3&auto=format&fit=crop&w=112&h=112&q=85',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        foreach ($providers as $provider) {
            Provider::create($provider);
        }
    }
}

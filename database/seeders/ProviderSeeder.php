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
        $faker = \Faker\Factory::create();

        $providers = [
            [
                'name' => 'Dr. Sarah Johnson',
                'location' => 'North America',
                'rating' => 4.9,
                'services' => ['Bonding Activities', 'Family Counseling'],
                'bio' => 'Specializes in strengthening parent-child bonds through evidence-based therapeutic techniques.',
                'price' => 120.00,
                'image_url' => 'https://images.unsplash.com/photo-1559839734-2b71ea197ec2?ixlib=rb-4.0.3&auto=format&fit=crop&w=112&h=112&q=85'
            ],
            [
                'name' => 'Maria Rodriguez',
                'location' => 'Europe',
                'rating' => 4.8,
                'services' => ['Parenting Education', 'Family Counseling'],
                'bio' => 'Expert in multicultural family dynamics and bilingual therapy approaches.',
                'price' => 110.00,
                'image_url' => 'https://images.unsplash.com/photo-1594824388511-923247e6e01e?ixlib=rb-4.0.3&auto=format&fit=crop&w=112&h=112&q=85'
            ],
            [
                'name' => 'Dr. Ahmed Hassan',
                'location' => 'Asia',
                'rating' => 4.7,
                'services' => ['Bonding Activities', 'Parenting Education'],
                'bio' => 'Focuses on attachment theory and trauma-informed care to help families heal.',
                'price' => 100.00,
                'image_url' => 'https://images.unsplash.com/photo-1612349317150-e3d4ac1d0e35?ixlib=rb-4.0.3&auto=format&fit=crop&w=112&h=112&q=85'
            ],
            [
                'name' => 'Emma Thompson',
                'location' => 'Oceania',
                'rating' => 4.6,
                'services' => ['Family Counseling', 'Bonding Activities'],
                'bio' => 'Dedicated to supporting families through life transitions with compassionate guidance.',
                'price' => 95.00,
                'image_url' => 'https://images.unsplash.com/photo-1582750433449-648ed127bb54?ixlib=rb-4.0.3&auto=format&fit=crop&w=112&h=112&q=85'
            ],
            [
                'name' => 'Dr. Michael Chen',
                'location' => 'North America',
                'rating' => 4.8,
                'services' => ['Parenting Education', 'Family Counseling'],
                'bio' => 'Specializes in child development and positive parenting strategies.',
                'price' => 130.00,
                'image_url' => null
            ],
            [
                'name' => 'Isabella Silva',
                'location' => 'Europe',
                'rating' => 4.5,
                'services' => ['Bonding Activities', 'Parenting Education'],
                'bio' => 'Creative therapist using art and play-based interventions for families.',
                'price' => 105.00,
                'image_url' => null
            ]
        ];

        foreach ($providers as $provider) {
            Provider::create($provider);
        }
    }
}

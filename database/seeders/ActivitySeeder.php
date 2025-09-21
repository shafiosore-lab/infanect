<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Activity;
use App\Models\User;

class ActivitySeeder extends Seeder
{
    public function run(): void
    {
        // Get bonding providers
        $bondingProviders = User::where('provider_type', 'provider-bonding')->get();

        $defaultProvider = User::first();
        if ($bondingProviders->isEmpty()) {
            $this->command->warn('No bonding providers found. Using default user.');
            if (!$defaultProvider) {
                $this->command->error('No users found in database.');
                return;
            }
        }

        // Featured activities
        $featuredActivities = [
            [
                'title' => 'Family Cooking Workshop',
                'description' => 'Learn to cook traditional Kenyan dishes together as a family. Bond over preparing nyama choma, ugali, and sukuma wiki.',
                'category' => 'cooking',
                'price' => 1500.00,
                'max_participants' => 12,
                'location' => 'Community Center, Westlands',
            ],
            [
                'title' => 'Nature Walk & Picnic',
                'description' => 'Explore Karura Forest together as families. Enjoy nature, wildlife spotting, and a healthy picnic lunch.',
                'category' => 'outdoor',
                'price' => 1200.00,
                'max_participants' => 20,
                'location' => 'Karura Forest, Nairobi',
            ],
            [
                'title' => 'Arts & Crafts Session',
                'description' => 'Create beautiful Kenyan-inspired artwork together. Make beaded jewelry, paint traditional patterns, and craft memory books.',
                'category' => 'creative',
                'price' => 1000.00,
                'max_participants' => 15,
                'location' => 'Art Studio, Karen',
            ],
        ];

        foreach ($featuredActivities as $activityData) {
            $provider = $bondingProviders->isNotEmpty() ? $bondingProviders->random() : $defaultProvider;

            $startDate = now()->addDays(rand(1, 14))->setTime(rand(9, 16), [0, 30][rand(0, 1)]);
            $endDate = (clone $startDate)->addHours(3);

            Activity::create(array_merge($activityData, [
                'type' => 'bonding',
                'start_date' => $startDate,
                'end_date' => $endDate,
                'status' => 'published',
                'created_by' => $provider->id,
                'provider_id' => $provider->id,
                'requirements' => ['comfortable_clothes', 'positive_attitude'],
                'tags' => ['family', 'bonding', 'community'],
                'is_active' => true,
            ]));
        }

        // Generate random activities using factory
        if ($bondingProviders->isNotEmpty()) {
            Activity::factory()->count(15)->published()->upcoming()->create();
            Activity::factory()->count(10)->past()->create();
            Activity::factory()->count(5)->draft()->create();

            $this->command->info('✅ Created ' . Activity::count() . ' activities');
        } else {
            $this->command->warn('⚠️ Only created featured activities (no providers for factory generation)');
        }
    }
}

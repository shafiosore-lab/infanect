<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Activity;
use App\Models\ServiceProvider;

class ActivitySeeder extends Seeder
{
    public function run(): void
    {
        $activities = [
            [
                'title' => 'Parent-Child Bonding Workshop',
                'category' => 'Bonding',
                'country' => 'Kenya',
                'region' => 'Nairobi',
                'venue' => 'Community Center',
                'datetime' => now()->addDays(7)->setTime(10, 0),
                'price' => 1500.00,
                'slots' => 20,
                'booking_link' => null,
                'provider_id' => 1, // Little Stars Childcare
            ],
            [
                'title' => 'Infant Massage & Stimulation',
                'category' => 'Infant Care',
                'country' => 'Kenya',
                'region' => 'Nairobi',
                'venue' => 'Wellness Center',
                'datetime' => now()->addDays(3)->setTime(14, 0),
                'price' => 800.00,
                'slots' => 10,
                'booking_link' => null,
                'provider_id' => 2, // Sunshine Kids Academy
            ],
            [
                'title' => 'Toddler Art & Craft Session',
                'category' => 'Arts & Crafts',
                'country' => 'Kenya',
                'region' => 'Nairobi',
                'venue' => 'Creative Studio',
                'datetime' => now()->addDays(5)->setTime(11, 0),
                'price' => 600.00,
                'slots' => 15,
                'booking_link' => null,
                'provider_id' => 3, // Tiny Tots Nursery
            ],
            [
                'title' => 'Preschool Science Experiment',
                'category' => 'STEM',
                'country' => 'Kenya',
                'region' => 'Nairobi',
                'venue' => 'Science Lab',
                'datetime' => now()->addDays(10)->setTime(13, 0),
                'price' => 1000.00,
                'slots' => 12,
                'booking_link' => null,
                'provider_id' => 4, // Rainbow Children's Center
            ],
            [
                'title' => 'Outdoor Adventure Day',
                'category' => 'Outdoor',
                'country' => 'Kenya',
                'region' => 'Nairobi',
                'venue' => 'National Park',
                'datetime' => now()->addDays(14)->setTime(9, 0),
                'price' => 2500.00,
                'slots' => 25,
                'booking_link' => null,
                'provider_id' => 5, // Happy Hearts Daycare
            ],
            [
                'title' => 'Music & Movement Class',
                'category' => 'Music',
                'country' => 'Kenya',
                'region' => 'Nairobi',
                'venue' => 'Music Studio',
                'datetime' => now()->addDays(2)->setTime(15, 0),
                'price' => 700.00,
                'slots' => 18,
                'booking_link' => null,
                'provider_id' => 6, // Bright Futures Academy
            ],
            [
                'title' => 'Storytelling & Literacy Hour',
                'category' => 'Literacy',
                'country' => 'Kenya',
                'region' => 'Nairobi',
                'venue' => 'Library',
                'datetime' => now()->addDays(4)->setTime(16, 0),
                'price' => 500.00,
                'slots' => 20,
                'booking_link' => null,
                'provider_id' => 7, // Little Angels Montessori
            ],
            [
                'title' => 'Sensory Play Workshop',
                'category' => 'Sensory',
                'country' => 'Kenya',
                'region' => 'Nairobi',
                'venue' => 'Play Center',
                'datetime' => now()->addDays(6)->setTime(12, 0),
                'price' => 900.00,
                'slots' => 14,
                'booking_link' => null,
                'provider_id' => 8, // Playful Minds Nursery
            ],
            [
                'title' => 'Nature Exploration Walk',
                'category' => 'Nature',
                'country' => 'Kenya',
                'region' => 'Nairobi',
                'venue' => 'Urban Forest',
                'datetime' => now()->addDays(8)->setTime(8, 30),
                'price' => 400.00,
                'slots' => 16,
                'booking_link' => null,
                'provider_id' => 9, // Green Valley Childcare
            ],
            [
                'title' => 'Creative Expression Class',
                'category' => 'Arts',
                'country' => 'Kenya',
                'region' => 'Nairobi',
                'venue' => 'Art Gallery',
                'datetime' => now()->addDays(12)->setTime(10, 30),
                'price' => 750.00,
                'slots' => 12,
                'booking_link' => null,
                'provider_id' => 10, // Creative Kids Hub
            ],
        ];

        foreach ($activities as $activityData) {
            Activity::create($activityData);
        }
    }
}

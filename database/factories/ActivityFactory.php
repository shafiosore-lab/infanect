<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Activity;

class ActivityFactory extends Factory
{
    protected $model = Activity::class;

    public function definition()
    {
        $categories = ['bonding_activity', 'counseling', 'training', 'event'];
        $venues = ['Community Center', 'School Hall', 'Park Pavilion', 'Library', 'Church Hall'];
        $cities = ['Nairobi', 'Mombasa', 'Kisumu', 'Eldoret', 'Nakuru'];

        return [
            'title' => $this->faker->sentence(4),
            'category' => $this->faker->randomElement($categories),
            'description' => $this->faker->paragraphs(3, true),
            'datetime' => $this->faker->dateTimeBetween('now', '+3 months'),
            'price' => $this->faker->numberBetween(500, 5000),
            'slots' => $this->faker->numberBetween(10, 50),
            'venue' => $this->faker->randomElement($venues),
            'country' => 'Kenya',
            'city' => $this->faker->randomElement($cities),
            'duration' => $this->faker->randomElement(['1 hour', '2 hours', '3 hours', 'Half day', 'Full day']),
            'difficulty_level' => $this->faker->randomElement(['Beginner', 'Intermediate', 'Advanced']),
            'target_audience' => $this->faker->randomElement(['Parents', 'Children', 'Families', 'Teenagers', 'All ages']),
            'is_approved' => $this->faker->boolean(70), // 70% chance of being approved
        ];
    }
}

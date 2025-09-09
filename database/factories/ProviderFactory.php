<?php

namespace Database\Factories;

use App\Models\Provider;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Provider>
 */
class ProviderFactory extends Factory
{
    protected $model = Provider::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        // Random ISO country codes
        $countries = [
            ['code' => 'US', 'city' => 'New York', 'state' => 'NY'],
            ['code' => 'KE', 'city' => 'Nairobi', 'state' => 'Nairobi'],
            ['code' => 'JP', 'city' => 'Tokyo', 'state' => 'Tokyo'],
            ['code' => 'DE', 'city' => 'Berlin', 'state' => 'Berlin'],
            ['code' => 'IN', 'city' => 'Mumbai', 'state' => 'Maharashtra'],
            ['code' => 'BR', 'city' => 'São Paulo', 'state' => 'SP'],
            ['code' => 'CA', 'city' => 'Toronto', 'state' => 'Ontario'],
            ['code' => 'ZA', 'city' => 'Cape Town', 'state' => 'Western Cape'],
        ];

        $country = $this->faker->randomElement($countries);

        return [
            'name'          => $this->faker->company(),
            'service_type'  => $this->faker->randomElement([
                'Therapy & Counseling',
                'Bonding Activities',
                'Child Development',
                'Outdoor Events',
                'Workshops & Training'
            ]),
            'email'         => $this->faker->unique()->safeEmail(),
            'phone'         => $this->faker->phoneNumber(),
            'country_code'  => $country['code'],
            'city'          => $country['city'],
            'state'         => $country['state'],
            'address'       => $this->faker->streetAddress(),
            'postal_code'   => $this->faker->postcode(),
            'latitude'      => $this->faker->latitude(-90, 90),
            'longitude'     => $this->faker->longitude(-180, 180),
            'logo'          => null, // can be updated later
            'is_available'  => $this->faker->boolean(80), // 80% chance available
            'avg_rating'    => $this->faker->randomFloat(2, 3.5, 5.0),
            'total_reviews' => $this->faker->numberBetween(10, 500),
            'total_revenue' => $this->faker->randomFloat(2, 1000, 100000),
        ];
    }
}

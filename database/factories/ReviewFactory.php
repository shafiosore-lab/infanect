<?php

namespace Database\Factories;

use App\Models\Review;
use App\Models\Provider;
use Illuminate\Database\Eloquent\Factories\Factory;

class ReviewFactory extends Factory
{
    protected $model = Review::class;

    public function definition(): array
    {
        return [
            'provider_id'   => Provider::factory(),
            'reviewer_name' => $this->faker->name(),
            'country_code'  => $this->faker->countryCode(),
            'rating'        => $this->faker->numberBetween(1, 5),
            'comment'       => $this->faker->sentence(12),
            'is_approved'   => $this->faker->boolean(90),
            'translations'  => null,
        ];
    }
}

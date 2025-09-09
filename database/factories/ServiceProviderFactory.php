<?php

namespace Database\Factories;

use App\Models\ServiceProvider;
use Illuminate\Database\Eloquent\Factories\Factory;

class ServiceProviderFactory extends Factory
{
    protected $model = ServiceProvider::class;

    public function definition(): array
    {
        return [
            'name' => $this->faker->company,
            'email' => $this->faker->unique()->safeEmail,
            'phone' => $this->faker->phoneNumber,
            'bio' => $this->faker->paragraph,
            'specialization' => $this->faker->randomElement(['Therapy', 'Counseling', 'Coaching', 'Training']),
            'availability' => json_encode(['monday' => '9-5', 'tuesday' => '9-5']),
            'rating' => $this->faker->randomFloat(2, 1, 5),
            'country' => $this->faker->countryCode,
            'language' => $this->faker->languageCode,
            'currency' => $this->faker->currencyCode,
        ];
    }
}

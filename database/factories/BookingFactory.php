<?php

namespace Database\Factories;

use App\Models\Booking;
use App\Models\User;
use App\Models\Provider;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class BookingFactory extends Factory
{
    protected $model = Booking::class;

    public function definition(): array
    {
        $price = $this->faker->randomFloat(2, 20, 1500);

        return [
            // 🔗 Relationships
            'user_id'       => User::factory(),       // linked client
            'provider_id'   => Provider::factory(),   // linked provider
            'service_id'    => null,                  // set if you have services
            'activity_id'   => null,                  // set if you have activities
            'tenant_id'     => null,

            // 👤 Customer Info
            'customer_name'  => $this->faker->name(),
            'customer_email' => $this->faker->safeEmail(),
            'customer_phone' => $this->faker->e164PhoneNumber(),
            'country'        => $this->faker->country(),
            'country_code'   => $this->faker->countryCode(),

            // 📅 Booking details
            'booking_date'  => $this->faker->dateTimeBetween('-1 month', '+6 months'),
            'scheduled_at'  => $this->faker->dateTimeBetween('+1 day', '+1 year'),
            'status'        => $this->faker->randomElement(['pending', 'confirmed', 'cancelled', 'refunded', 'completed']),
            'location'      => $this->faker->city(),
            'timezone'      => $this->faker->timezone(),

            // 💰 Payment & financials
            'price'         => $price,
            'currency_code' => $this->faker->currencyCode(),
            'amount'        => $price,
            'amount_paid'   => $this->faker->randomElement([$price, $price * 0.5, 0]),
            'participants'  => $this->faker->numberBetween(1, 5),
            'discount'      => $this->faker->randomFloat(2, 0, 50),
            'payment_method'=> $this->faker->randomElement(['card', 'mpesa', 'paypal']),
            'payment_ref'   => strtoupper(Str::random(10)),

            // ⭐ Engagement
            'rating'        => $this->faker->optional()->numberBetween(1, 5),
            'is_returning'  => $this->faker->boolean(),

            // 📎 Extra info
            'reference_code'=> strtoupper(Str::random(8)),
            'notes'         => $this->faker->optional()->sentence(),
            'platform'      => $this->faker->randomElement(['web', 'mobile']),
            'metadata'      => [
                'ip_address' => $this->faker->ipv4(),
                'device'     => $this->faker->userAgent(),
            ],
        ];
    }
}

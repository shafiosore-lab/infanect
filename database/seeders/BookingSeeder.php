<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Booking;
use App\Models\User;
use App\Models\Provider;
use Illuminate\Support\Str;

class BookingSeeder extends Seeder
{
    public function run(): void
    {
        // Get clients (users with role employee/client)
        $clients = User::whereHas('role', function ($q) {
            $q->whereIn('slug', ['employee', 'client']);
        })->get();

        // Ensure providers exist
        $providers = Provider::all();

        if ($clients->isEmpty() || $providers->isEmpty()) {
            $this->command->warn('⚠️ No clients or providers found. Skipping booking seeding.');
            return;
        }

        foreach (range(1, 20) as $i) {
            $client   = $clients->random();
            $provider = $providers->random();

            Booking::create([
                // 🔗 Relationships
                'user_id'       => $client->id,
                'provider_id'   => $provider->id,
                'service_id'    => null, // set if you have services
                'activity_id'   => null, // set if you have activities
                'tenant_id'     => null, // multi-tenant support

                // 👤 Customer Info
                'customer_name'  => $client->name,
                'customer_email' => $client->email,
                'customer_phone' => fake()->e164PhoneNumber(),
                'country'        => fake()->country(),
                'country_code'   => fake()->countryCode(),

                // 📅 Booking details
                'booking_date'  => fake()->dateTimeBetween('-1 month', '+6 months'),
                'scheduled_at'  => fake()->dateTimeBetween('+1 day', '+1 year'),
                'status'        => fake()->randomElement(['pending', 'confirmed', 'cancelled', 'refunded', 'completed']),
                'location'      => fake()->city(),
                'timezone'      => fake()->timezone(),

                // 💰 Payment & financials
                'price'         => $price = fake()->randomFloat(2, 50, 1500),
                'currency_code' => fake()->currencyCode(),
                'amount'        => $price,
                'amount_paid'   => fake()->randomElement([$price, $price * 0.5, 0]),
                'participants'  => fake()->numberBetween(1, 5),
                'discount'      => fake()->randomFloat(2, 0, 50),
                'payment_method'=> fake()->randomElement(['card', 'mpesa', 'paypal']),
                'payment_ref'   => strtoupper(Str::random(10)),

                // ⭐ Engagement
                'rating'        => fake()->optional()->numberBetween(1, 5),
                'is_returning'  => fake()->boolean(),

                // 📎 Extra info
                'reference_code'=> strtoupper(Str::random(8)),
                'notes'         => fake()->optional()->sentence(),
                'platform'      => fake()->randomElement(['web', 'mobile']),
                'metadata'      => json_encode([
                    'ip_address' => fake()->ipv4(),
                    'device'     => fake()->userAgent(),
                ]),
            ]);
        }
    }
}

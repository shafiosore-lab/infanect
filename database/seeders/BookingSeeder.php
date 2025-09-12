<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\QueryException;
use App\Models\Booking;
use App\Models\User;
use App\Models\Provider;
use App\Models\Service;
use App\Models\Activity;
use Illuminate\Support\Str;

class BookingSeeder extends Seeder
{
    public function run(): void
    {
        // Get clients (users with role user, employee, or client)
        $clients = User::whereHas('role', function ($q) {
            $q->whereIn('slug', ['user', 'employee', 'client']);
        })->get();

        // Ensure providers exist
        $providers  = Provider::all();
        $services   = Service::all();
        $activities = Activity::all();

        if ($clients->isEmpty() || $providers->isEmpty()) {
            $this->command->warn('⚠️ No clients or providers found. Skipping booking seeding.');
            return;
        }

        $samples = [];
        // Track returning clients
        $returningClients = [];

        foreach (range(1, 20) as $i) {
            $client   = $clients->random();
            $provider = $providers->random();

            // Randomly assign a service or activity if available
            $serviceId  = $services->isNotEmpty() ? $services->random()->id : null;
            $activityId = $activities->isNotEmpty() ? $activities->random()->id : null;

            // Random number of participants (1–5)
            $participantsCount = fake()->numberBetween(1, 5);
            $participants = [];

            for ($p = 0; $p < $participantsCount; $p++) {
                $participants[] = [
                    'name'  => fake()->name(),
                    'email' => fake()->safeEmail(),
                    'phone' => fake()->e164PhoneNumber(),
                ];
            }

            // Determine if returning client
            $isReturning = in_array($client->id, $returningClients) ? true : fake()->boolean(30);
            if ($isReturning) {
                $returningClients[] = $client->id;
            }

            $samples[] = [
                'user_id'       => $client->id,
                'provider_id'   => $provider->id,
                'service_id'    => $serviceId,
                'activity_id'   => $activityId,
                'tenant_id'     => null,

                'customer_name'  => $client->name,
                'customer_email' => $client->email,
                'customer_phone' => fake()->e164PhoneNumber(),
                'country'        => fake()->country(),
                'country_code'   => fake()->countryCode(),

                'booking_date'  => fake()->dateTimeBetween('-1 month', '+6 months'),
                'scheduled_at'  => fake()->dateTimeBetween('+1 day', '+1 year'),
                'status'        => fake()->randomElement(['pending', 'confirmed', 'cancelled', 'refunded', 'completed']),
                'location'      => fake()->city(),
                'timezone'      => fake()->timezone(),

                'price'         => $price = fake()->randomFloat(2, 50, 1500),
                'currency_code' => fake()->currencyCode(),
                'amount'        => $price,
                'amount_paid'   => fake()->randomElement([$price, $price * 0.5, 0]),
                'participants'  => $participantsCount,
                'participants_details' => json_encode($participants),
                'discount'      => fake()->randomFloat(2, 0, 50),
                'payment_method'=> fake()->randomElement(['card', 'mpesa', 'paypal']),
                'payment_ref'   => strtoupper(Str::random(10)),

                'rating'        => fake()->optional()->numberBetween(1, 5),
                'is_returning'  => $isReturning,

                'reference_code'=> strtoupper(Str::random(8)),
                'notes'         => fake()->optional()->sentence(),
                'platform'      => fake()->randomElement(['web', 'mobile']),
                'metadata'      => json_encode([
                    'ip_address' => fake()->ipv4(),
                    'device'     => fake()->userAgent(),
                ]),
            ];
        }

        $columns = Schema::hasTable('bookings') ? Schema::getColumnListing('bookings') : [];

        foreach ($samples as $s) {
            $data = [];
            foreach ($s as $k => $v) {
                if (in_array($k, $columns)) {
                    $data[$k] = $v;
                }
            }

            // If bookings table requires service_id (column exists and sample is null), try to set a valid one
            if (in_array('service_id', $columns) && (!array_key_exists('service_id', $data) || is_null($data['service_id']))) {
                try {
                    if (Schema::hasTable('services') && \App\Models\Service::exists()) {
                        $data['service_id'] = \App\Models\Service::first()->id;
                    } else {
                        // If no services available, skip this booking to avoid NOT NULL violation
                        continue;
                    }
                } catch (\Throwable $e) {
                    // If any error, skip this sample
                    continue;
                }
            }

            if (empty($data)) {
                continue;
            }

            try {
                if (in_array('reference_code', $columns) && isset($data['reference_code'])) {
                    Booking::updateOrCreate(['reference_code' => $data['reference_code']], $data);
                } else {
                    DB::table('bookings')->insert($data);
                }
            } catch (QueryException $e) {
                // Skip duplicates or other DB errors
                continue;
            }
        }
    }
}

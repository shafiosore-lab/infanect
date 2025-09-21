<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use App\Models\Booking;
use App\Models\Activity;
use Illuminate\Support\Str;
use Illuminate\Database\QueryException;

class BookingSeeder extends Seeder
{
    public function run(): void
    {
        $bondingProvider = User::where('email', 'bonding@infanect.com')->first();
        $professionalProvider = User::where('email', 'professional@infanect.com')->first();
        $testProvider = User::where('email', 'test@infanect.com')->first();

        $clients = User::where('role', 'client')->take(6)->get();
        $activities = Activity::take(3)->get();

        if ($clients->isEmpty() || !$bondingProvider) return;

        $providers = collect([$bondingProvider, $professionalProvider, $testProvider])->filter();

        // Featured bookings
        foreach ($activities as $index => $activity) {
            if ($index < $clients->count()) {
                $client = $clients[$index];
                $provider = $bondingProvider;

                $startDate = now()->addDays($index + 1)->setTime(14, 0);
                $endDate = (clone $startDate)->addHours(3);

                DB::table('bookings')->insert([
                    'user_id'        => $client->id,
                    'provider_id'    => $provider->id,
                    'activity_id'    => $activity->id,
                    'service_type'   => 'bonding',
                    'booking_date'   => $startDate,
                    'duration'       => 180,
                    'amount'         => $activity->price,
                    'status'         => collect(['confirmed', 'pending', 'completed'])->random(),
                    'notes'          => 'Looking forward to this family activity!',
                    'participants'   => json_encode(['adults'=>2,'children'=>rand(1,3),'special_needs'=>null]),
                    'created_at'     => now()->subDays(rand(1,30)),
                    'updated_at'     => now(),
                    'reference_code' => strtoupper(Str::random(8)),
                    'rating'         => fake()->optional()->numberBetween(1,5),
                    'is_returning'   => fake()->boolean(),
                    'platform'       => fake()->randomElement(['web','mobile']),
                    'metadata'       => json_encode([
                        'ip_address' => fake()->ipv4(),
                        'device'     => fake()->userAgent(),
                    ]),
                ]);
            }
        }

        // Individual therapy bookings
        foreach ($clients->take(4) as $index => $client) {
            DB::table('bookings')->insert([
                'user_id'        => $client->id,
                'provider_id'    => $professionalProvider->id,
                'activity_id'    => null,
                'service_type'   => 'professional',
                'booking_date'   => now()->addDays($index + 2)->setTime(10 + $index, 0),
                'duration'       => 60,
                'amount'         => 5000,
                'status'         => collect(['confirmed','pending','completed'])->random(),
                'notes'          => 'Individual therapy session - anxiety management',
                'participants'   => json_encode(['adults'=>1,'children'=>0,'session_type'=>'individual_therapy']),
                'created_at'     => now()->subDays(rand(1,15)),
                'updated_at'     => now(),
                'reference_code' => strtoupper(Str::random(8)),
                'rating'         => fake()->optional()->numberBetween(1,5),
                'is_returning'   => fake()->boolean(),
                'platform'       => fake()->randomElement(['web','mobile']),
                'metadata'       => json_encode([
                    'ip_address' => fake()->ipv4(),
                    'device'     => fake()->userAgent(),
                ]),
            ]);
        }

        // Completed bookings for analytics
        for ($i=0;$i<10;$i++) {
            $client = $clients->random();
            $provider = collect([$bondingProvider, $professionalProvider])->random();

            DB::table('bookings')->insert([
                'user_id'        => $client->id,
                'provider_id'    => $provider->id,
                'activity_id'    => $provider->id === $bondingProvider->id ? $activities->random()->id : null,
                'service_type'   => $provider->id === $bondingProvider->id ? 'bonding' : 'professional',
                'booking_date'   => now()->subDays(rand(7,60)),
                'duration'       => $provider->id === $bondingProvider->id ? 180 : 60,
                'amount'         => $provider->id === $bondingProvider->id ? rand(800,2000) : 5000,
                'status'         => 'completed',
                'notes'          => 'Great session, very helpful!',
                'participants'   => json_encode([
                    'adults' => rand(1,2),
                    'children' => $provider->id === $bondingProvider->id ? rand(0,3) : 0,
                ]),
                'created_at'     => now()->subDays(rand(7,60)),
                'updated_at'     => now()->subDays(rand(1,7)),
                'reference_code' => strtoupper(Str::random(8)),
                'rating'         => fake()->optional()->numberBetween(1,5),
                'is_returning'   => fake()->boolean(),
                'platform'       => fake()->randomElement(['web','mobile']),
                'metadata'       => json_encode([
                    'ip_address' => fake()->ipv4(),
                    'device'     => fake()->userAgent(),
                ]),
            ]);
        }
    }
}

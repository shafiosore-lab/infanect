<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\User;

class ReviewSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $providers = User::whereIn('role', ['provider'])->get();
        $clients = User::where('role', 'client')->get();
        $bookings = DB::table('bookings')->where('status', 'completed')->get();

        if ($providers->isEmpty() || $clients->isEmpty()) return;

        $reviews = [
            [
                'rating' => 5,
                'comment' => 'Amazing family bonding experience! Our children loved every minute of the cooking workshop.',
            ],
            [
                'rating' => 5,
                'comment' => 'Dr. John is incredibly professional and helped our family through difficult times.',
            ],
            [
                'rating' => 4,
                'comment' => 'Great outdoor adventure! Well organized and safe for all family members.',
            ],
            [
                'rating' => 5,
                'comment' => 'The arts and crafts session brought our family closer together. Highly recommended!',
            ],
            [
                'rating' => 4,
                'comment' => 'Professional therapy session was very helpful. Felt comfortable and supported.',
            ],
            [
                'rating' => 5,
                'comment' => 'Sarah creates such a welcoming environment for families. Our kids ask when we can go back!',
            ],
            [
                'rating' => 4,
                'comment' => 'Nature walk was educational and fun. Learned so much about local wildlife.',
            ],
            [
                'rating' => 5,
                'comment' => 'Life-changing therapy sessions. Finally feel like we have tools to handle stress.',
            ],
        ];

        foreach ($reviews as $index => $review) {
            if ($index < $clients->count() && $index % 2 < $providers->count()) {
                $client = $clients[$index];
                $provider = $providers[$index % $providers->count()];
                $booking = $bookings->where('user_id', $client->id)->first();

                DB::table('reviews')->insert(array_merge($review, [
                    'user_id' => $client->id,
                    'provider_id' => $provider->id,
                    'booking_id' => $booking->id ?? null,
                    'is_anonymous' => rand(0, 1) === 1,
                    'is_published' => true,
                    'created_at' => now()->subDays(rand(1, 60)),
                    'updated_at' => now(),
                ]));
            }
        }
    }
}

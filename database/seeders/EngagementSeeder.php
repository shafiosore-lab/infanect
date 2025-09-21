<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\User;

class EngagementSeeder extends Seeder
{
    public function run(): void
    {
        $clients = User::where('role', 'client')->get();
        $providers = User::where('role', 'provider')->get();
        $activities = DB::table('activities')->get();

        if ($clients->isEmpty() || $providers->isEmpty()) return;

        $engagementTypes = [
            'activity_participation',
            'session_attendance',
            'community_interaction',
            'progress_milestone',
            'feedback_submission',
        ];

        // Create engagements for the last 90 days
        foreach ($clients as $client) {
            for ($day = 0; $day < 90; $day++) {
                if (rand(1, 4) === 1) { // 25% chance per day
                    $provider = $providers->random();
                    $activity = $activities->isNotEmpty() ? $activities->random() : null;

                    DB::table('engagements')->insert([
                        'user_id' => $client->id,
                        'provider_id' => $provider->id,
                        'activity_id' => $activity ? $activity->id : null,
                        'engagement_type' => collect($engagementTypes)->random(),
                        'engagement_score' => rand(3, 10),
                        'notes' => $this->getEngagementNote(),
                        'metadata' => json_encode([
                            'session_duration' => rand(30, 180),
                            'participation_level' => collect(['low', 'medium', 'high'])->random(),
                            'mood_before' => rand(1, 10),
                            'mood_after' => rand(5, 10),
                        ]),
                        'engaged_at' => now()->subDays($day)->setTime(rand(8, 18), rand(0, 59)),
                        'created_at' => now()->subDays($day),
                        'updated_at' => now(),
                    ]);
                }
            }
        }
    }

    private function getEngagementNote(): string
    {
        $notes = [
            'Actively participated in all activities',
            'Showed improvement in communication',
            'Engaged well with family members',
            'Demonstrated positive behavior changes',
            'Completed session goals successfully',
            'Expressed gratitude for the experience',
            'Made new connections with other families',
            'Overcame initial hesitation and participated fully',
        ];

        return collect($notes)->random();
    }
}

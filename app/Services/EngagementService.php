<?php

namespace App\Services;

use App\Models\User;
use App\Models\Activity;
use App\Models\Booking;
use Illuminate\Support\Facades\DB;

class EngagementService
{
    /**
     * Get activities linked to a specific provider
     */
    public function getProviderActivities(User $provider, $limit = 10)
    {
        try {
            return Activity::where('provider_profile_id', $provider->provider_profile->id ?? null)
                ->orWhere('provider_id', $provider->id)
                ->orderBy('created_at', 'desc')
                ->limit($limit)
                ->get();
        } catch (\Exception $e) {
            return collect([]);
        }
    }

    /**
     * Get upcoming events for a provider
     */
    public function getUpcomingEvents(User $provider, $limit = 5)
    {
        try {
            return DB::table('activities')
                ->where('provider_profile_id', $provider->provider_profile->id ?? $provider->id)
                ->where('start_date', '>=', now())
                ->orderBy('start_date')
                ->limit($limit)
                ->get();
        } catch (\Exception $e) {
            return collect([]);
        }
    }

    /**
     * Get engagement statistics for a provider
     */
    public function getEngagementStats(User $provider)
    {
        try {
            $totalActivities = Activity::where('provider_profile_id', $provider->provider_profile->id ?? $provider->id)
                ->orWhere('provider_id', $provider->id)
                ->count();

            $totalParticipants = DB::table('bookings')
                ->join('activities', 'bookings.activity_id', '=', 'activities.id')
                ->where('activities.provider_profile_id', $provider->provider_profile->id ?? $provider->id)
                ->orWhere('activities.provider_id', $provider->id)
                ->count();

            $avgParticipantsPerActivity = $totalActivities > 0 ?
                round($totalParticipants / $totalActivities, 1) : 0;

            return [
                'total_activities' => $totalActivities,
                'total_participants' => $totalParticipants,
                'avg_participants_per_activity' => $avgParticipantsPerActivity
            ];
        } catch (\Exception $e) {
            return [
                'total_activities' => 0,
                'total_participants' => 0,
                'avg_participants_per_activity' => 0
            ];
        }
    }

    /**
     * Find activities relevant for a client based on their mood submissions
     */
    public function getRecommendedActivities(User $client, $limit = 5)
    {
        try {
            // Get client's most recent mood
            $latestMood = DB::table('mood_submissions')
                ->where('user_id', $client->id)
                ->orderBy('created_at', 'desc')
                ->first();

            if (!$latestMood) {
                return $this->getPopularActivities($limit);
            }

            // Match activities based on mood
            $matchedActivities = Activity::where('is_approved', true)
                ->where(function($query) use ($latestMood) {
                    $query->where('mood_target', 'like', "%{$latestMood->mood}%")
                          ->orWhere('mood_target', 'all')
                          ->orWhereNull('mood_target');
                })
                ->orderBy('created_at', 'desc')
                ->limit($limit)
                ->get();

            return $matchedActivities->count() > 0 ? $matchedActivities : $this->getPopularActivities($limit);
        } catch (\Exception $e) {
            return $this->getPopularActivities($limit);
        }
    }

    /**
     * Get popular activities as fallback
     */
    private function getPopularActivities($limit = 5)
    {
        try {
            return Activity::where('is_approved', true)
                ->orderBy('view_count', 'desc')
                ->limit($limit)
                ->get();
        } catch (\Exception $e) {
            return collect([]);
        }
    }
}

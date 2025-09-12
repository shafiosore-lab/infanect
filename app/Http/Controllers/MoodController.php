<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\MoodSubmission;
use App\Jobs\RecommendationJob;
use Illuminate\Support\Facades\Cache;

class MoodController extends Controller
{
    public function submit(Request $request)
    {
        $user = $request->user();

        $data = $request->validate([
            'mood' => 'required|string|in:happy,okay,sad,excited,anxious,calm,stressed,grateful',
            'mood_score' => 'nullable|integer|min:1|max:10',
            'availability' => 'required|array',
            'availability.*' => 'string|in:morning,afternoon,evening,weekend',
            'timezone' => 'nullable|string|max:50',
            'location' => 'nullable|array',
            'location.latitude' => 'nullable|numeric|between:-90,90',
            'location.longitude' => 'nullable|numeric|between:-180,180',
            'age_group' => 'nullable|string|in:infant,toddler,preschool,school-age,teen,adult',
            'language' => 'nullable|string|max:10',
            'notes' => 'nullable|string|max:500'
        ]);

        // Create mood submission
        $mood = MoodSubmission::create([
            'user_id' => $user->id,
            'mood' => $data['mood'],
            'mood_score' => $data['mood_score'] ?? $this->calculateMoodScore($data['mood']),
            'availability' => json_encode($data['availability']),
            'location' => isset($data['location']) ? json_encode($data['location']) : null,
            'timezone' => $data['timezone'] ?? config('app.timezone'),
            'language' => $data['language'] ?? 'en',
            'age_group' => $data['age_group'] ?? null,
            'notes' => $data['notes'] ?? null,
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent()
        ]);

        // Clear user's mood cache to refresh recommendations
        Cache::forget('user_mood_insights_' . $user->id);
        Cache::forget('recommended_activities_' . $user->id);

        // Queue recommendation generation
        if (class_exists('App\Jobs\RecommendationJob')) {
            RecommendationJob::dispatch($mood->id, $user->id);
        }

        return response()->json([
            'status' => 'success',
            'mood_id' => $mood->id,
            'message' => 'Mood submitted successfully! Generating personalized recommendations...',
            'mood_score' => $mood->mood_score,
            'next_check_in' => now()->addHours(4)->toISOString()
        ], 202);
    }

    /**
     * Calculate default mood score based on mood string
     */
    private function calculateMoodScore(string $mood): int
    {
        $moodScores = [
            'happy' => 8,
            'excited' => 9,
            'grateful' => 9,
            'calm' => 7,
            'okay' => 5,
            'stressed' => 3,
            'anxious' => 3,
            'sad' => 2
        ];

        return $moodScores[$mood] ?? 5;
    }

    /**
     * Get mood insights for providers - Updated with integer role_id support
     */
    public function insights(Request $request)
    {
        $user = $request->user();

        // Updated to check integer role_id instead of string
        $allowedRoleIds = [3, 4, 5, 7, 8]; // provider, provider-professional, provider-bonding, admin, super-admin
        $userRoleId = is_numeric($user->role_id) ? (int)$user->role_id : null;

        if (!$userRoleId || !in_array($userRoleId, $allowedRoleIds)) {
            abort(403, 'Access denied');
        }

        $insights = Cache::remember('mood_insights_provider_' . $user->id, 30 * 60, function () use ($user) {
            // Get mood data for provider's clients
            $clientMoods = collect([]); // Default empty collection

            try {
                if (class_exists('App\Models\MoodSubmission')) {
                    $clientMoods = MoodSubmission::whereHas('user.bookings', function ($query) use ($user) {
                        $query->where('provider_id', $user->id);
                    })
                    ->with('user:id,name')
                    ->orderBy('created_at', 'desc')
                    ->limit(50)
                    ->get();
                }
            } catch (\Exception $e) {
                \Log::warning('Could not fetch mood submissions', ['error' => $e->getMessage()]);
            }

            return [
                'recent_submissions' => $clientMoods,
                'mood_trends' => $this->calculateMoodTrends($clientMoods),
                'recommendations' => $this->generateProviderRecommendations($clientMoods)
            ];
        });

        return response()->json($insights);
    }

    private function calculateMoodTrends($moods)
    {
        $trends = [
            'average_score' => $moods->avg('mood_score'),
            'mood_distribution' => $moods->countBy('mood'),
            'weekly_trend' => []
        ];

        // Calculate weekly trend
        for ($i = 6; $i >= 0; $i--) {
            $date = now()->subDays($i);
            $dayMoods = $moods->filter(function ($mood) use ($date) {
                return $mood->created_at->isSameDay($date);
            });

            $trends['weekly_trend'][] = [
                'date' => $date->format('Y-m-d'),
                'average_score' => $dayMoods->avg('mood_score') ?? 0,
                'count' => $dayMoods->count()
            ];
        }

        return $trends;
    }

    private function generateProviderRecommendations($moods)
    {
        $lowMoodCount = $moods->where('mood_score', '<=', 4)->count();
        $totalCount = $moods->count();

        $recommendations = [];

        if ($totalCount > 0 && ($lowMoodCount / $totalCount) > 0.3) {
            $recommendations[] = [
                'type' => 'alert',
                'title' => 'High Stress Levels Detected',
                'message' => 'Consider scheduling wellness check-ins or stress management sessions.',
                'action' => 'Schedule Wellness Session'
            ];
        }

        if ($moods->where('mood', 'anxious')->count() > 2) {
            $recommendations[] = [
                'type' => 'suggestion',
                'title' => 'Anxiety Support',
                'message' => 'Multiple clients showing anxiety. Consider group relaxation activities.',
                'action' => 'Create Group Activity'
            ];
        }

        return $recommendations;
    }
}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use App\Models\Booking;

class ClientDashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        // Cache client dashboard data for better performance
        $dashboardData = Cache::remember("client_dashboard_{$user->id}", 300, function () use ($user) {
            return [
                'metrics' => $this->getClientMetrics($user),
                'upcomingBookings' => $this->getUpcomingBookings($user),
                'recentActivities' => $this->getRecentActivities($user),
                'recommendations' => $this->getRecommendations($user),
                'wellnessProgress' => $this->getWellnessProgress($user),
                'favoriteProviders' => $this->getFavoriteProviders($user),
                'achievementBadges' => $this->getAchievementBadges($user),
                'communityHighlights' => $this->getCommunityHighlights($user),
            ];
        });

        return view('dashboards.client.index', array_merge([
            'userRole' => 'client',
            'user' => $user,
        ], $dashboardData));
    }

    private function getClientMetrics($user)
    {
        try {
            $bookings = $user->bookings();

            return [
                'totalBookings' => $bookings->count(),
                'completedSessions' => $bookings->completed()->count(),
                'upcomingSessions' => $bookings->confirmed()
                    ->where('booking_date', '>=', now())
                    ->count(),
                'totalSpent' => $bookings->completed()->sum('amount') ?? 0,
                'thisMonthSessions' => $bookings->completed()
                    ->whereMonth('booking_date', now()->month)
                    ->count(),
                'averageRating' => $user->reviews()->avg('rating') ?? 0,
            ];
        } catch (\Throwable $e) {
            return [
                'totalBookings' => 0,
                'completedSessions' => 0,
                'upcomingSessions' => 0,
                'totalSpent' => 0,
                'thisMonthSessions' => 0,
                'averageRating' => 0,
            ];
        }
    }

    private function getUpcomingBookings($user)
    {
        try {
            return $user->bookings()
                ->with(['provider:id,name', 'activity:id,title'])
                ->where('booking_date', '>=', now())
                ->confirmed()
                ->orderBy('booking_date')
                ->limit(3)
                ->get()
                ->map(function ($booking) {
                    return [
                        'id' => $booking->id,
                        'service_name' => $booking->activity->title ?? 'Session with ' . $booking->provider->name,
                        'provider_name' => $booking->provider->name ?? 'Provider',
                        'booking_date' => $booking->booking_date,
                        'amount' => $booking->formatted_amount,
                        'status' => $booking->status,
                    ];
                });
        } catch (\Throwable $e) {
            return collect([
                ['service_name' => 'Family Cooking Workshop', 'provider_name' => 'Sarah', 'booking_date' => now()->addDays(2), 'status' => 'confirmed', 'amount' => 'KSh 1,500'],
                ['service_name' => 'Nature Walk & Picnic', 'provider_name' => 'John', 'booking_date' => now()->addDays(5), 'status' => 'confirmed', 'amount' => 'KSh 1,200'],
            ]);
        }
    }

    private function getRecentActivities($user)
    {
        try {
            return DB::table('bookings')
                ->where('user_id', $user->id)
                ->orderBy('created_at', 'desc')
                ->limit(5)
                ->get();
        } catch (\Throwable $e) {
            return collect([
                (object)['service_name' => 'Completed Session', 'created_at' => now()->subDays(1), 'status' => 'completed'],
                (object)['service_name' => 'Family Consultation', 'created_at' => now()->subDays(3), 'status' => 'completed'],
            ]);
        }
    }

    private function getRecommendations($user)
    {
        return collect([
            'Consider booking a follow-up session',
            'Check out our new parenting resources',
            'Join our community support group',
            'Complete your wellness assessment'
        ]);
    }

    private function getWellnessProgress($user)
    {
        try {
            // Get real mood data if available
            $moodData = $user->moodSubmissions()->latest()->limit(7)->pluck('mood_score')->reverse()->values();

            return [
                'currentStreak' => $this->calculateStreak($user),
                'totalSessions' => $user->bookings()->completed()->count(),
                'progressScore' => $this->calculateProgressScore($user),
                'weeklyGoal' => 2,
                'completedThisWeek' => $user->bookings()
                    ->completed()
                    ->whereBetween('booking_date', [now()->startOfWeek(), now()->endOfWeek()])
                    ->count(),
                'moodTrend' => $moodData->count() >= 7 ? $moodData->toArray() : [6, 7, 8, 6, 7, 8, 7],
                'categories' => [
                    ['name' => 'Mental Wellness', 'progress' => 80, 'color' => '#4A90E2'],
                    ['name' => 'Family Bonding', 'progress' => 70, 'color' => '#7ED321'],
                    ['name' => 'Personal Growth', 'progress' => 65, 'color' => '#F5A623'],
                    ['name' => 'Parenting Skills', 'progress' => 85, 'color' => '#9013FE'],
                ],
            ];
        } catch (\Throwable $e) {
            return [
                'currentStreak' => 5,
                'totalSessions' => 12,
                'progressScore' => 75,
                'weeklyGoal' => 2,
                'completedThisWeek' => 1,
                'moodTrend' => [6, 7, 8, 6, 7, 8, 7],
                'categories' => [
                    ['name' => 'Mental Wellness', 'progress' => 80, 'color' => '#4A90E2'],
                    ['name' => 'Family Bonding', 'progress' => 70, 'color' => '#7ED321'],
                    ['name' => 'Personal Growth', 'progress' => 65, 'color' => '#F5A623'],
                    ['name' => 'Parenting Skills', 'progress' => 85, 'color' => '#9013FE'],
                ],
            ];
        }
    }

    private function getFavoriteProviders($user)
    {
        return collect([
            [
                'id' => 1,
                'name' => 'Dr. Sarah Wilson',
                'specialty' => 'Child Psychology',
                'rating' => 4.9,
                'totalSessions' => 8,
                'avatar' => 'SW',
                'color' => '#4A90E2'
            ],
            [
                'id' => 2,
                'name' => 'Maria Rodriguez',
                'specialty' => 'Family Therapy',
                'rating' => 4.8,
                'totalSessions' => 4,
                'avatar' => 'MR',
                'color' => '#7ED321'
            ],
            [
                'id' => 3,
                'name' => 'Dr. James Chen',
                'specialty' => 'Parenting Coach',
                'rating' => 4.9,
                'totalSessions' => 15,
                'avatar' => 'JC',
                'color' => '#F5A623'
            ]
        ]);
    }

    private function getAchievementBadges($user)
    {
        return collect([
            ['name' => 'Early Bird', 'description' => 'Completed 5 morning sessions', 'earned' => true, 'icon' => 'sunrise'],
            ['name' => 'Consistency Champion', 'description' => 'Maintained 7-day streak', 'earned' => true, 'icon' => 'award'],
            ['name' => 'Progress Pioneer', 'description' => 'Completed first wellness assessment', 'earned' => true, 'icon' => 'star'],
            ['name' => 'Community Helper', 'description' => 'Helped 3 other parents', 'earned' => false, 'icon' => 'heart'],
            ['name' => 'Milestone Master', 'description' => 'Completed 25 sessions', 'earned' => false, 'icon' => 'trophy']
        ]);
    }

    private function getCommunityHighlights($user)
    {
        return collect([
            [
                'type' => 'success_story',
                'title' => 'Sarah\'s Journey: From Overwhelmed to Empowered',
                'excerpt' => 'Discover how Sarah transformed her parenting approach through our program...',
                'date' => now()->subDays(2),
                'author' => 'Community Team'
            ],
            [
                'type' => 'tip',
                'title' => 'Weekly Tip: Building Emotional Intelligence in Children',
                'excerpt' => 'Simple techniques to help your child understand and manage emotions...',
                'date' => now()->subDays(1),
                'author' => 'Dr. Emily Foster'
            ],
            [
                'type' => 'event',
                'title' => 'Virtual Parent Support Group - This Saturday',
                'excerpt' => 'Join fellow parents for peer support and expert guidance...',
                'date' => now()->addDays(3),
                'author' => 'Events Team'
            ]
        ]);
    }

    private function calculateStreak($user)
    {
        // Calculate consecutive days of activity
        return 5; // Mock for now
    }

    private function calculateProgressScore($user)
    {
        // Calculate overall progress based on sessions, mood, etc.
        $sessions = $user->bookings()->completed()->count();
        $reviews = $user->reviews()->count();
        return min(100, ($sessions * 5) + ($reviews * 10));
    }
}

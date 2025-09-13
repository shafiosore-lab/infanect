<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ClientDashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        return view('dashboards.client.index', [
            'userRole' => 'client',
            'user' => $user,
            'metrics' => $this->getClientMetrics($user),
            'upcomingBookings' => $this->getUpcomingBookings($user),
            'recentActivities' => $this->getRecentActivities($user),
            'recommendations' => $this->getRecommendations($user),
            'wellnessProgress' => $this->getWellnessProgress($user),
            'favoriteProviders' => $this->getFavoriteProviders($user),
            'achievementBadges' => $this->getAchievementBadges($user),
            'communityHighlights' => $this->getCommunityHighlights($user)
        ]);
    }

    private function getClientMetrics($user)
    {
        try {
            return [
                'totalBookings' => DB::table('bookings')->where('client_id', $user->id)->count(),
                'completedSessions' => DB::table('bookings')
                    ->where('client_id', $user->id)
                    ->where('status', 'completed')
                    ->count(),
                'upcomingSessions' => DB::table('bookings')
                    ->where('client_id', $user->id)
                    ->where('status', 'confirmed')
                    ->where('booking_date', '>=', now())
                    ->count(),
                'totalSpent' => DB::table('bookings')
                    ->where('client_id', $user->id)
                    ->where('status', 'completed')
                    ->sum('amount') ?? 0
            ];
        } catch (\Throwable $e) {
            return [
                'totalBookings' => 0,
                'completedSessions' => 0,
                'upcomingSessions' => 0,
                'totalSpent' => 0
            ];
        }
    }

    private function getUpcomingBookings($user)
    {
        try {
            return DB::table('bookings')
                ->where('client_id', $user->id)
                ->where('booking_date', '>=', now())
                ->orderBy('booking_date')
                ->limit(3)
                ->get();
        } catch (\Throwable $e) {
            return collect([
                (object)['name' => 'Parenting Workshop', 'date' => now()->addDays(2)],
                (object)['name' => 'Child Development Talk', 'date' => now()->addDays(5)],
            ]);
        }
    }

    private function getRecentActivities($user)
    {
        try {
            return DB::table('activities')
                ->where('user_id', $user->id)
                ->orderBy('created_at', 'desc')
                ->limit(5)
                ->get();
        } catch (\Throwable $e) {
            return collect([
                (object)['name' => 'Parenting Workshop', 'date' => now()->subDays(5)],
                (object)['name' => 'Child Development Talk', 'date' => now()->subDays(7)],
            ]);
        }
    }

    private function getRecommendations($user)
    {
        return collect([
            'Consider booking a follow-up session',
            'Check out our new parenting resources',
            'Join our community support group'
        ]);
    }

    private function getWellnessProgress($user)
    {
        return [
            'currentStreak' => 7,
            'totalSessions' => 24,
            'progressScore' => 85,
            'weeklyGoal' => 3,
            'completedThisWeek' => 2,
            'moodTrend' => [7, 8, 6, 9, 8, 7, 8], // Last 7 days
            'categories' => [
                ['name' => 'Mental Health', 'progress' => 90, 'color' => '#4A90E2'],
                ['name' => 'Physical Wellness', 'progress' => 75, 'color' => '#7ED321'],
                ['name' => 'Family Bonding', 'progress' => 85, 'color' => '#F5A623'],
                ['name' => 'Personal Growth', 'progress' => 70, 'color' => '#9013FE']
            ]
        ];
    }

    private function getFavoriteProviders($user)
    {
        return collect([
            [
                'id' => 1,
                'name' => 'Dr. Sarah Wilson',
                'specialty' => 'Child Psychology',
                'rating' => 4.9,
                'totalSessions' => 12,
                'avatar' => 'SW',
                'color' => '#4A90E2'
            ],
            [
                'id' => 2,
                'name' => 'Maria Rodriguez',
                'specialty' => 'Family Therapy',
                'rating' => 4.8,
                'totalSessions' => 8,
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
}

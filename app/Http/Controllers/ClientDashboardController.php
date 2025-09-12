<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ClientDashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        $dashboardData = [
            'user' => $user,
            'welcomeMessage' => 'Welcome to your Client Dashboard!',
            'totalBookings' => $this->getTotalBookings($user),
            'upcomingBookings' => $this->getUpcomingBookings($user),
            'recentActivities' => $this->getRecentActivities($user),
            'quickActions' => [
                ['name' => 'Browse Activities', 'url' => route('activities.index'), 'icon' => 'calendar'],
                ['name' => 'Explore Services', 'url' => route('services.index'), 'icon' => 'concierge-bell'],
                ['name' => 'View Bookings', 'url' => route('bookings.index'), 'icon' => 'calendar-check'],
            ]
        ];

        return view('dashboards.client', $dashboardData);
    }

    private function getTotalBookings($user)
    {
        try {
            return DB::table('bookings')->where('user_id', $user->id)->count();
        } catch (\Exception $e) {
            return 0;
        }
    }

    private function getUpcomingBookings($user)
    {
        try {
            return DB::table('bookings')
                ->where('user_id', $user->id)
                ->where('booking_date', '>=', now())
                ->limit(3)
                ->get();
        } catch (\Exception $e) {
            return collect([]);
        }
    }

    private function getRecentActivities($user)
    {
        return collect([
            (object)['name' => 'Family Yoga Session', 'date' => now()->subDays(2)],
            (object)['name' => 'Parenting Workshop', 'date' => now()->subDays(5)],
            (object)['name' => 'Child Development Talk', 'date' => now()->subDays(7)],
        ]);
    }
}
                ->orderBy('booking_date')
                ->limit(3)
                ->get();
        } catch (\Throwable $e) {
            return collect();
        }
    }

    private function getRecentActivities($user)
    {
        try {
            return DB::table('activities')
                ->where('user_id', $user->id)
                ->orderByDesc('created_at')
                ->limit(5)
                ->get();
        } catch (\Throwable $e) {
            // fallback mock activities
            return collect([
                (object)['name' => 'Family Yoga Session', 'date' => now()->subDays(2)],
                (object)['name' => 'Parenting Workshop', 'date' => now()->subDays(5)],
                (object)['name' => 'Child Development Talk', 'date' => now()->subDays(7)],
            ]);
        }
    }

    private function getQuickActions(): array
    {
        return [
            ['name' => 'Browse Activities', 'url' => route('activities.index'), 'icon' => 'calendar'],
            ['name' => 'Find Providers', 'url' => route('providers.index'), 'icon' => 'users'],
            ['name' => 'Training Modules', 'url' => route('training.index'), 'icon' => 'graduation-cap'],
        ];
    }
}

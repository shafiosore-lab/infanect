<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use App\Models\Provider;

class BondingProviderDashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        // Get provider data without soft deletes
        $provider = Cache::remember("provider_data_{$user->id}", 300, function () use ($user) {
            return Provider::where('user_id', $user->id)
                ->active() // Use scope instead of soft delete check
                ->first();
        });

        if (!$provider) {
            // Redirect to provider setup if no provider profile exists
            return redirect()->route('provider.setup')->with('message', 'Please complete your provider profile first.');
        }

        return view('layouts.app', [
            'userRole' => 'provider-bonding',
            'provider' => $provider,
            'metrics' => $this->getBondingMetrics($user, $provider),
            'upcomingBookings' => $this->getUpcomingBookings($user, $provider),
            'recentActivities' => $this->getRecentActivities($user, $provider),
            'wellnessData' => $this->getWellnessData($user, $provider),
            'chartData' => $this->getBondingChartData($user, $provider)
        ]);
    }

    private function getBondingMetrics($user, $provider)
    {
        try {
            return [
                'totalClients' => DB::table('bookings')
                    ->where('provider_id', $provider->id)
                    ->where('status', 'completed')
                    ->distinct('user_id')
                    ->count(),
                'activeSessions' => DB::table('bookings')
                    ->where('provider_id', $provider->id)
                    ->where('status', 'active')
                    ->count(),
                'completedSessions' => DB::table('bookings')
                    ->where('provider_id', $provider->id)
                    ->where('status', 'completed')
                    ->count(),
                'revenue' => DB::table('bookings')
                    ->where('provider_id', $provider->id)
                    ->where('status', 'completed')
                    ->sum('amount') ?? 0,
                'averageRating' => $provider->rating ?? 0,
                'totalReviews' => $provider->total_reviews ?? 0,
                'wellnessScore' => 8.5,
                'bondingActivities' => 24
            ];
        } catch (\Throwable $e) {
            return [
                'totalClients' => 0,
                'activeSessions' => 0,
                'completedSessions' => 0,
                'revenue' => 0,
                'averageRating' => 0,
                'totalReviews' => 0,
                'wellnessScore' => 0,
                'bondingActivities' => 0
            ];
        }
    }

    private function getUpcomingBookings($user, $provider)
    {
        try {
            return DB::table('bookings')
                ->join('users', 'bookings.user_id', '=', 'users.id')
                ->where('bookings.provider_id', $provider->id)
                ->where('bookings.booking_date', '>=', now())
                ->where('bookings.status', 'confirmed')
                ->select('bookings.*', 'users.name as client_name', 'users.email as client_email')
                ->orderBy('bookings.booking_date')
                ->limit(5)
                ->get();
        } catch (\Throwable $e) {
            return collect();
        }
    }

    private function getRecentActivities($user, $provider)
    {
        try {
            return DB::table('bookings')
                ->join('users', 'bookings.user_id', '=', 'users.id')
                ->where('bookings.provider_id', $provider->id)
                ->whereIn('bookings.status', ['completed', 'cancelled'])
                ->select('bookings.*', 'users.name as client_name')
                ->orderBy('bookings.updated_at', 'desc')
                ->limit(10)
                ->get();
        } catch (\Throwable $e) {
            return collect();
        }
    }

    private function getWellnessData($user, $provider)
    {
        return [
            'physical' => 85,
            'mental' => 78,
            'emotional' => 82,
            'social' => 90,
            'spiritual' => 75,
            'weeklyProgress' => [7.5, 8.2, 7.8, 8.5, 8.1, 8.7, 8.3],
            'goals' => [
                ['name' => 'Daily Bonding Activities', 'current' => 12, 'target' => 15, 'unit' => 'activities'],
                ['name' => 'Client Satisfaction', 'current' => 4.8, 'target' => 5.0, 'unit' => 'stars'],
                ['name' => 'Session Completion Rate', 'current' => 92, 'target' => 95, 'unit' => '%']
            ]
        ];
    }

    private function getBondingChartData($user, $provider)
    {
        return [
            'bookingStatus' => [
                'labels' => ['Completed', 'Upcoming', 'Cancelled'],
                'data' => [65, 25, 10]
            ],
            'revenueOverTime' => [
                'labels' => ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'],
                'data' => [1200, 1500, 1800, 1600, 2000, 2300]
            ],
            'clientSatisfaction' => [
                'labels' => ['5 Stars', '4 Stars', '3 Stars', '2 Stars', '1 Star'],
                'data' => [45, 35, 15, 3, 2]
            ],
            'sessionTypes' => [
                'labels' => ['Individual Bonding', 'Group Activities', 'Family Sessions', 'Workshops'],
                'data' => [40, 30, 20, 10]
            ]
        ];
    }
}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use App\Models\Provider;

class BondingProviderDashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        $provider = Cache::remember("provider_data_{$user->id}", 300, function () use ($user) {
            return Provider::with(['user', 'reviews'])
                ->where('user_id', $user->id)
                ->active()
                ->first();
        });

        if (!$provider) {
            return redirect()->route('provider.setup')
                ->with('message', 'Please complete your provider profile first.');
        }

        return view('dashboards.provider-bonding', [
            'provider' => $provider,
            'metrics' => $this->getBondingMetrics($provider),
            'upcomingBookings' => $this->getUpcomingBookings($provider),
            'recentActivities' => $this->getRecentActivities($provider),
            'wellnessData' => $this->getWellnessData($provider),
            'chartData' => $this->getBondingChartData($provider),
        ]);
    }

    private function getBondingMetrics($provider)
    {
        return Cache::remember("provider_metrics_{$provider->id}", 300, function () use ($provider) {
            $completedBookings = $provider->bookings()->completed();
            $thisMonth = $provider->bookings()->thisMonth();

            return [
                'activeFamilies' => $provider->clients()->count(),
                'weeklyActivities' => $provider->activities()
                    ->whereBetween('start_date', [now()->startOfWeek(), now()->endOfWeek()])
                    ->count(),
                'satisfactionRate' => round($provider->average_rating * 20, 0) . '%', // Convert 5-star to percentage
                'totalRevenue' => $provider->monthly_revenue,
                'totalClients' => $provider->clients()->count(),
                'activeSessions' => $provider->bookings()->confirmed()->upcoming()->count(),
                'completedSessions' => $completedBookings->count(),
                'pendingBookings' => $provider->bookings()->pending()->count(),
                'todaySessions' => $provider->bookings()->today()->count(),
                'completionRate' => $provider->completion_rate,
                'averageRating' => round($provider->average_rating, 1),
                'totalReviews' => $provider->total_reviews,
                'monthlyBookings' => $thisMonth->count(),
                'monthlyRevenue' => $thisMonth->sum('amount'),
            ];
        });
    }

    private function getUpcomingBookings($provider)
    {
        return Cache::remember("provider_upcoming_{$provider->id}", 180, function () use ($provider) {
            return $provider->bookings()
                ->with(['user:id,name,email', 'activity:id,title'])
                ->upcoming()
                ->orderBy('booking_date')
                ->limit(5)
                ->get();
        });
    }

    private function getRecentActivities($provider)
    {
        return Cache::remember("provider_recent_{$provider->id}", 180, function () use ($provider) {
            return $provider->bookings()
                ->with(['user:id,name', 'activity:id,title'])
                ->whereIn('status', ['completed', 'cancelled'])
                ->orderByDesc('updated_at')
                ->limit(10)
                ->get();
        });
    }

    private function getWellnessData($provider)
    {
        // TODO: Replace with real wellness tracking data
        return Cache::remember("provider_wellness_{$provider->id}", 900, function () use ($provider) {
            return [
                'physical' => 85,
                'mental' => 78,
                'emotional' => 82,
                'social' => 90,
                'spiritual' => 75,
                'weeklyProgress' => [7.5, 8.2, 7.8, 8.5, 8.1, 8.7, 8.3],
                'goals' => [
                    [
                        'name' => 'Daily Bonding Activities',
                        'current' => $provider->activities()->whereDate('start_date', today())->count(),
                        'target' => 15,
                        'unit' => 'activities'
                    ],
                    [
                        'name' => 'Client Satisfaction',
                        'current' => $provider->average_rating,
                        'target' => 5.0,
                        'unit' => 'stars'
                    ],
                    [
                        'name' => 'Session Completion Rate',
                        'current' => $provider->completion_rate,
                        'target' => 95,
                        'unit' => '%'
                    ]
                ]
            ];
        });
    }

    private function getBondingChartData($provider)
    {
        return Cache::remember("provider_charts_{$provider->id}", 600, function () use ($provider) {
            // Booking status distribution
            $bookingCounts = $provider->bookings()
                ->selectRaw('status, COUNT(*) as count')
                ->groupBy('status')
                ->pluck('count', 'status')
                ->toArray();

            // Monthly revenue for the last 6 months
            $revenueData = $provider->bookings()
                ->completed()
                ->whereBetween('booking_date', [now()->subMonths(5)->startOfMonth(), now()->endOfMonth()])
                ->selectRaw('MONTH(booking_date) as month, YEAR(booking_date) as year, SUM(amount) as revenue')
                ->groupBy('month', 'year')
                ->orderBy('year')
                ->orderBy('month')
                ->get();

            // Client satisfaction ratings
            $ratingCounts = $provider->reviews()
                ->selectRaw('rating, COUNT(*) as count')
                ->groupBy('rating')
                ->pluck('count', 'rating')
                ->toArray();

            // Activity types distribution
            $activityTypes = $provider->activities()
                ->selectRaw('category, COUNT(*) as count')
                ->groupBy('category')
                ->pluck('count', 'category')
                ->toArray();

            return [
                'familyEngagement' => [
                    'labels' => ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'],
                    'data' => $revenueData->pluck('revenue')->values()->toArray()
                ],
                'activityTypes' => [
                    'labels' => array_keys($activityTypes),
                    'data' => array_values($activityTypes)
                ],
                'feedbackScores' => [
                    'labels' => ['5★', '4★', '3★', '2★', '1★'],
                    'data' => [
                        $ratingCounts[5] ?? 0,
                        $ratingCounts[4] ?? 0,
                        $ratingCounts[3] ?? 0,
                        $ratingCounts[2] ?? 0,
                        $ratingCounts[1] ?? 0,
                    ]
                ],
                'revenueBreakdown' => [
                    'labels' => ['Workshops', 'Adventures', 'Creative', 'Sports', 'Tours'],
                    'data' => [45000, 38000, 32000, 28000, 11200] // TODO: Make dynamic
                ]
            ];
        });
    }
}

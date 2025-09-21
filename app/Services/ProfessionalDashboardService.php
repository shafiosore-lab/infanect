<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;
use App\Models\User;
use App\Models\Provider;

class ProfessionalDashboardService
{
    public function getDashboardData(User $user, Provider $provider): array
    {
        return Cache::remember("professional_dashboard_{$user->id}", 300, function () use ($user, $provider) {
            return [
                'metrics' => $this->getMetrics($user, $provider),
                'recentMoodData' => $this->getRecentClientMoodData($user),
                'upcomingAppointments' => $this->getUpcomingAppointments($user),
                'recentFeedback' => $this->getRecentClientFeedback($user),
                'revenueData' => $this->getRevenueAnalytics($user),
                'engagementStats' => $this->getClientEngagementStats($user),
                'serviceStats' => $this->getServicePerformanceStats($user),
                'financialBreakdown' => $this->getFinancialBreakdown($user),
                'chartData' => $this->getChartData($user),
            ];
        });
    }

    private function getMetrics(User $user, Provider $provider): array
    {
        $bookings = $user->providedBookings();
        $completedBookings = $bookings->completed();
        $thisMonth = $bookings->thisMonth();

        return [
            'activePatients' => $user->providedBookings()
                ->with('user')
                ->whereHas('user', fn($q) => $q->where('is_active', true))
                ->distinct('user_id')
                ->count(),

            'weeklySessions' => $bookings
                ->whereBetween('booking_date', [now()->startOfWeek(), now()->endOfWeek()])
                ->count(),

            'successRate' => $this->calculateSuccessRate($user),

            'totalRevenue' => $completedBookings->sum('amount'),

            'monthlyRevenue' => $thisMonth->where('status', 'completed')->sum('amount'),

            'totalSessions' => $completedBookings->count(),

            'averageRating' => round($provider->average_rating, 1),

            'totalReviews' => $provider->total_reviews,

            'pendingAppointments' => $bookings->where('status', 'confirmed')
                ->whereDate('booking_date', '>=', today())
                ->count(),

            'completionRate' => $this->calculateCompletionRate($user),
        ];
    }

    private function getRecentClientMoodData(User $user)
    {
        return Cache::remember("provider_mood_data_{$user->id}", 180, function () use ($user) {
            return $user->providedBookings()
                ->with(['user:id,name,email', 'user.moodSubmissions' => function($q) {
                    $q->latest()->limit(3);
                }])
                ->whereHas('user.moodSubmissions')
                ->get()
                ->flatMap(function ($booking) {
                    return $booking->user->moodSubmissions->map(function ($mood) use ($booking) {
                        return [
                            'id' => $mood->id,
                            'mood' => $mood->mood,
                            'mood_score' => $mood->mood_score,
                            'notes' => $mood->notes,
                            'created_at' => $mood->created_at,
                            'client_name' => $booking->user->name,
                            'client_email' => $booking->user->email,
                        ];
                    });
                })
                ->sortByDesc('created_at')
                ->take(10)
                ->values();
        });
    }

    private function getUpcomingAppointments(User $user)
    {
        return Cache::remember("provider_appointments_{$user->id}", 180, function () use ($user) {
            return $user->providedBookings()
                ->with(['user:id,name,phone', 'activity:id,title'])
                ->confirmed()
                ->upcoming()
                ->orderBy('booking_date')
                ->limit(5)
                ->get()
                ->map(function ($booking) {
                    return [
                        'id' => $booking->id,
                        'client_name' => $booking->user->name,
                        'client_phone' => $booking->user->phone,
                        'service_name' => $booking->activity->title ?? 'Professional Session',
                        'booking_date' => $booking->booking_date,
                        'duration' => $booking->duration,
                        'amount' => $booking->formatted_amount,
                        'status' => $booking->status,
                        'notes' => $booking->notes,
                    ];
                });
        });
    }

    private function getRecentClientFeedback(User $user)
    {
        return Cache::remember("provider_feedback_{$user->id}", 300, function () use ($user) {
            return $user->receivedReviews()
                ->with('user:id,name')
                ->latest()
                ->limit(5)
                ->get()
                ->map(function ($review) {
                    return [
                        'id' => $review->id,
                        'rating' => $review->rating,
                        'comment' => $review->comment,
                        'client_name' => $review->is_anonymous ? 'Anonymous' : $review->user->name,
                        'created_at' => $review->created_at,
                    ];
                });
        });
    }

    private function getRevenueAnalytics(User $user)
    {
        return Cache::remember("provider_revenue_{$user->id}", 600, function () use ($user) {
            $months = [];
            $revenues = [];

            for ($i = 5; $i >= 0; $i--) {
                $date = now()->subMonths($i);
                $months[] = $date->format('M Y');

                $revenue = $user->providedBookings()
                    ->completed()
                    ->whereMonth('booking_date', $date->month)
                    ->whereYear('booking_date', $date->year)
                    ->sum('amount') ?? 0;

                $revenues[] = $revenue;
            }

            return [
                'months' => $months,
                'revenues' => $revenues,
                'total_revenue' => array_sum($revenues),
                'average_monthly' => count($revenues) > 0 ? round(array_sum($revenues) / count($revenues), 2) : 0,
            ];
        });
    }

    private function getClientEngagementStats(User $user)
    {
        return Cache::remember("provider_engagement_{$user->id}", 600, function () use ($user) {
            $bookings = $user->providedBookings();
            $totalBookings = $bookings->count();
            $totalClients = $bookings->distinct('user_id')->count();

            $repeatClients = $bookings
                ->selectRaw('user_id, COUNT(*) as booking_count')
                ->groupBy('user_id')
                ->havingRaw('COUNT(*) > 1')
                ->count();

            return [
                'total_bookings' => $totalBookings,
                'total_clients' => $totalClients,
                'repeat_clients' => $repeatClients,
                'average_sessions_per_client' => $totalClients > 0 ?
                    round($totalBookings / $totalClients, 1) : 0,
                'retention_rate' => $totalClients > 0 ?
                    round(($repeatClients / $totalClients) * 100, 1) : 0,
                'new_clients_this_month' => $bookings->thisMonth()
                    ->distinct('user_id')
                    ->count(),
            ];
        });
    }

    private function getServicePerformanceStats(User $user)
    {
        return Cache::remember("provider_services_{$user->id}", 600, function () use ($user) {
            return $user->providedBookings()
                ->with('activity:id,title,category')
                ->selectRaw('service_type, COUNT(*) as total_bookings, SUM(CASE WHEN status = "completed" THEN amount ELSE 0 END) as revenue, AVG(CASE WHEN status = "completed" THEN amount ELSE NULL END) as avg_rate')
                ->groupBy('service_type')
                ->orderByDesc('total_bookings')
                ->limit(5)
                ->get()
                ->map(function ($stat) {
                    return [
                        'name' => ucfirst(str_replace('_', ' ', $stat->service_type)),
                        'total_bookings' => $stat->total_bookings,
                        'revenue' => $stat->revenue ?? 0,
                        'avg_rate' => round($stat->avg_rate ?? 0, 2),
                    ];
                });
        });
    }

    private function getFinancialBreakdown(User $user)
    {
        return Cache::remember("provider_finances_{$user->id}", 300, function () use ($user) {
            $bookings = $user->providedBookings();

            return [
                'completed_revenue' => $bookings->completed()->sum('amount'),
                'pending_revenue' => $bookings->confirmed()->sum('amount'),
                'cancelled_amount' => $bookings->where('status', 'cancelled')->sum('amount'),
                'this_month_revenue' => $bookings->completed()->thisMonth()->sum('amount'),
                'last_month_revenue' => $bookings->completed()
                    ->whereMonth('booking_date', now()->subMonth()->month)
                    ->whereYear('booking_date', now()->subMonth()->year)
                    ->sum('amount'),
            ];
        });
    }

    private function getChartData(User $user)
    {
        return Cache::remember("provider_charts_{$user->id}", 600, function () use ($user) {
            // Session status distribution
            $statusCounts = $user->providedBookings()
                ->selectRaw('status, COUNT(*) as count')
                ->groupBy('status')
                ->pluck('count', 'status')
                ->toArray();

            // Client satisfaction ratings
            $ratingCounts = $user->receivedReviews()
                ->selectRaw('rating, COUNT(*) as count')
                ->groupBy('rating')
                ->pluck('count', 'rating')
                ->toArray();

            // Service type distribution
            $serviceTypes = $user->providedBookings()
                ->selectRaw('service_type, COUNT(*) as count')
                ->groupBy('service_type')
                ->pluck('count', 'service_type')
                ->toArray();

            return [
                'sessionStatus' => [
                    'labels' => array_keys($statusCounts),
                    'data' => array_values($statusCounts)
                ],
                'clientSatisfaction' => [
                    'labels' => ['5★', '4★', '3★', '2★', '1★'],
                    'data' => [
                        $ratingCounts[5] ?? 0,
                        $ratingCounts[4] ?? 0,
                        $ratingCounts[3] ?? 0,
                        $ratingCounts[2] ?? 0,
                        $ratingCounts[1] ?? 0,
                    ]
                ],
                'serviceTypes' => [
                    'labels' => array_map('ucfirst', array_keys($serviceTypes)),
                    'data' => array_values($serviceTypes)
                ],
            ];
        });
    }

    private function calculateSuccessRate(User $user): string
    {
        $totalSessions = $user->providedBookings()->count();
        if ($totalSessions === 0) return '0%';

        $completedSessions = $user->providedBookings()->completed()->count();
        return round(($completedSessions / $totalSessions) * 100, 1) . '%';
    }

    private function calculateCompletionRate(User $user): float
    {
        $totalBookings = $user->providedBookings()->count();
        if ($totalBookings === 0) return 0;

        $completedBookings = $user->providedBookings()->completed()->count();
        return round(($completedBookings / $totalBookings) * 100, 1);
    }
}

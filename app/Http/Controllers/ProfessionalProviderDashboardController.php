<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Services\DashboardMetricsService;
use Illuminate\Support\Facades\DB;

class ProfessionalProviderDashboardController extends Controller
{
    protected $metricsService;

    public function __construct(DashboardMetricsService $metricsService)
    {
        $this->middleware(['auth']);
        $this->metricsService = $metricsService;
    }

    public function index(Request $request)
    {
        $user = $request->user();
        $userRole = $user->role_id ?? ($user->role->slug ?? 'client');

        // Allow multiple role types to access this dashboard
        $allowedRoles = [
            'provider-professional',
            'provider',
            'admin',
            'super-admin',
            'provider-bonding' // Also allow bonding providers for testing
        ];

        if (!in_array($userRole, $allowedRoles)) {
            // Instead of blocking, redirect to appropriate dashboard
            return redirect()->route('dashboard')->with('info', 'Redirecting to your dashboard.');
        }

        // Get provider data from registration
        $providerData = json_decode($user->provider_data ?? '{}', true);

        // Get metrics from service
        $metrics = $this->metricsService->getMetrics($user);

        // Get additional data
        $recentMoodData = $this->getRecentClientMoodData($user);
        $upcomingAppointments = $this->getUpcomingAppointments($user);
        $recentFeedback = $this->getRecentClientFeedback($user);
        $revenueData = $this->getRevenueAnalytics($user);
        $engagementStats = $this->getClientEngagementStats($user);
        $serviceStats = $this->getServicePerformanceStats($user);
        $financialBreakdown = $this->getFinancialBreakdown($user);

        return view('dashboards.professional-provider', compact(
            'user',
            'providerData',
            'metrics',
            'recentMoodData',
            'upcomingAppointments',
            'recentFeedback',
            'revenueData',
            'engagementStats',
            'serviceStats',
            'financialBreakdown'
        ));
    }

    private function getRecentClientMoodData($user)
    {
        try {
            return DB::table('mood_submissions')
                ->join('bookings', 'mood_submissions.user_id', '=', 'bookings.user_id')
                ->join('users', 'mood_submissions.user_id', '=', 'users.id')
                ->where('bookings.provider_id', $user->id)
                ->select(
                    'mood_submissions.*',
                    'users.name as client_name',
                    'users.email as client_email'
                )
                ->orderBy('mood_submissions.created_at', 'desc')
                ->limit(10)
                ->get();
        } catch (\Exception $e) {
            return collect([]);
        }
    }

    private function getUpcomingAppointments($user)
    {
        try {
            // Try to get appointments with service information
            return DB::table('bookings')
                ->join('users', 'bookings.user_id', '=', 'users.id')
                ->leftJoin('services', 'bookings.service_id', '=', 'services.id')
                ->where('bookings.provider_id', $user->id)
                ->where('bookings.status', 'confirmed')
                ->whereDate('bookings.booking_date', '>=', now())
                ->select(
                    'bookings.*',
                    'users.name as client_name',
                    'users.phone as client_phone',
                    DB::raw('COALESCE(services.name, services.title, "Service") as service_name')
                )
                ->orderBy('bookings.booking_date')
                ->limit(5)
                ->get();
        } catch (\Exception $e) {
            return collect([]);
        }
    }

    private function getRecentClientFeedback($user)
    {
        try {
            return DB::table('reviews')
                ->join('users', 'reviews.user_id', '=', 'users.id')
                ->where('reviews.provider_id', $user->id)
                ->select(
                    'reviews.*',
                    'users.name as client_name'
                )
                ->orderBy('reviews.created_at', 'desc')
                ->limit(5)
                ->get();
        } catch (\Exception $e) {
            return collect([]);
        }
    }

    private function getRevenueAnalytics($user)
    {
        $months = [];
        $revenues = [];

        for ($i = 5; $i >= 0; $i--) {
            $date = now()->subMonths($i);
            $months[] = $date->format('M Y');

            try {
                $revenue = DB::table('bookings')
                    ->where('provider_id', $user->id)
                    ->where('status', 'completed')
                    ->whereMonth('created_at', $date->month)
                    ->whereYear('created_at', $date->year)
                    ->sum('amount') ?? 0;
            } catch (\Exception $e) {
                $revenue = 0;
            }

            $revenues[] = $revenue;
        }

        return [
            'months' => $months,
            'revenues' => $revenues
        ];
    }

    private function getClientEngagementStats($user)
    {
        try {
            $totalBookings = DB::table('bookings')
                ->where('provider_id', $user->id)
                ->count();

            $repeatClients = DB::table('bookings')
                ->where('provider_id', $user->id)
                ->select('user_id')
                ->groupBy('user_id')
                ->havingRaw('COUNT(*) > 1')
                ->count();

            $totalClients = DB::table('bookings')
                ->where('provider_id', $user->id)
                ->distinct('user_id')
                ->count('user_id') ?? 0;

            $averageSessionsPerClient = $totalClients > 0 ?
                round($totalBookings / $totalClients, 1) : 0;

            return [
                'total_bookings' => $totalBookings,
                'repeat_clients' => $repeatClients,
                'average_sessions_per_client' => $averageSessionsPerClient,
                'retention_rate' => $totalClients > 0 ?
                    round(($repeatClients / $totalClients) * 100, 1) : 0
            ];
        } catch (\Exception $e) {
            return [
                'total_bookings' => 0,
                'repeat_clients' => 0,
                'average_sessions_per_client' => 0,
                'retention_rate' => 0
            ];
        }
    }

    private function getServicePerformanceStats($user)
    {
        try {
            return DB::table('bookings')
                ->leftJoin('services', 'bookings.service_id', '=', 'services.id')
                ->where('bookings.provider_id', $user->id)
                ->select(
                    DB::raw('COALESCE(services.name, services.title, "Service") as name'),
                    DB::raw('COUNT(*) as total_bookings'),
                    DB::raw('SUM(CASE WHEN bookings.status = "completed" THEN bookings.amount ELSE 0 END) as revenue'),
                    DB::raw('AVG(CASE WHEN bookings.status = "completed" THEN bookings.amount ELSE NULL END) as avg_rate')
                )
                ->groupBy('services.id', 'services.name', 'services.title')
                ->orderBy('total_bookings', 'desc')
                ->limit(5)
                ->get();
        } catch (\Exception $e) {
            return collect([]);
        }
    }

    private function getFinancialBreakdown($user)
    {
        try {
            return [
                'completed_revenue' => DB::table('bookings')
                    ->where('provider_id', $user->id)
                    ->where('status', 'completed')
                    ->sum('amount') ?? 0,
                'pending_revenue' => DB::table('bookings')
                    ->where('provider_id', $user->id)
                    ->where('status', 'confirmed')
                    ->sum('amount') ?? 0,
                'refunded_amount' => DB::table('bookings')
                    ->where('provider_id', $user->id)
                    ->where('status', 'refunded')
                    ->sum('amount') ?? 0
            ];
        } catch (\Exception $e) {
            return [
                'completed_revenue' => 0,
                'pending_revenue' => 0,
                'refunded_amount' => 0
            ];
        }
    }
}

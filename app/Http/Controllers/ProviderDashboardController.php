<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ProviderDashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        // Resolve provider type
        $providerType = $user->provider_type ?? $user->type ?? '';

        if (in_array($providerType, ['professional', 'provider-professional'])) {
            return $this->professional();
        } elseif (in_array($providerType, ['bonding', 'provider-bonding'])) {
            return $this->bonding();
        }

        // Default provider dashboard
        return view('dashboards.provider', [
            'userRole' => 'provider',
            'user'     => $user,
            'metrics'  => $this->getProviderMetrics($user),
        ]);
    }

    /**
     * Professional Provider Dashboard
     */
    public function professional()
    {
        $user = Auth::user();

        return view('dashboards.provider-professional.index', [
            'userRole'  => 'provider-professional',
            'user'      => $user,
            'stats'     => $this->getProfessionalMetrics($user),
            'chartData' => $this->getProfessionalChartData($user),
            'recentBookings' => [],
            'todaysBookings' => [],
        ]);
    }

    /**
     * Bonding Provider Dashboard
     */
    public function bonding()
    {
        $user = Auth::user();

        return view('provider_bonding.dashboard', [
            'userRole'  => 'provider-bonding',
            'user'      => $user,
            'metrics'   => $this->getBondingMetrics($user),
            'chartData' => $this->getBondingChartData($user),
        ]);
    }

    /**
     * Default metrics for generic providers
     */
    private function getProviderMetrics($user)
    {
        return [
            'totalBookings' => DB::table('bookings')
                ->where('provider_id', $user->id)
                ->count() ?: 0,
            'totalRevenue' => DB::table('bookings')
                ->where('provider_id', $user->id)
                ->where('status', 'completed')
                ->sum('amount') ?: 0,
            'activeClients' => DB::table('bookings')
                ->where('provider_id', $user->id)
                ->distinct('user_id')
                ->count('user_id') ?: 0,
            'avgRating' => DB::table('reviews')
                ->where('provider_id', $user->id)
                ->avg('rating') ?: 0,
        ];
    }

    /**
     * Metrics for professional providers
     */
    private function getProfessionalMetrics($user)
    {
        return [
            'activePatients' => DB::table('patients')
                ->where('provider_id', $user->id)
                ->count() ?: 0,
            'weeklySessions' => DB::table('sessions')
                ->where('provider_id', $user->id)
                ->whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()])
                ->count() ?: 0,
            'successRate' => '92%', // could be dynamically calculated later
            'crisisInterventions' => DB::table('interventions')
                ->where('provider_id', $user->id)
                ->whereBetween('created_at', [now()->startOfMonth(), now()->endOfMonth()])
                ->count() ?: 0,
        ];
    }

    /**
     * Metrics for bonding providers
     */
    private function getBondingMetrics($user)
    {
        try {
            return [
                'activeFamilies' => DB::table('bookings')
                    ->where('provider_id', $user->id)
                    ->where('status', 'confirmed')
                    ->distinct('user_id')
                    ->count('user_id') ?: 0,
                'weeklyActivities' => DB::table('activities')
                    ->where('created_by', $user->id)
                    ->whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()])
                    ->count() ?: 0,
                'satisfactionRate' => '96%', // static for now
                'totalRevenue' => DB::table('bookings')
                    ->where('provider_id', $user->id)
                    ->where('status', 'completed')
                    ->sum('amount') ?: 0,
            ];
        } catch (\Exception $e) {
            // Fallback demo metrics
            return [
                'activeFamilies' => 89,
                'weeklyActivities' => 32,
                'satisfactionRate' => '96%',
                'totalRevenue' => 154200,
            ];
        }
    }

    /**
     * Chart data for professional providers
     */
    private function getProfessionalChartData($user)
    {
        return [
            'bookingStatus' => [
                'labels' => ['Completed', 'Upcoming', 'Cancelled'],
                'data'   => [65, 25, 10]
            ],
            'revenueOverTime' => [
                'labels' => ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'],
                'data'   => [1500, 1800, 2200, 1900, 2400, 2800]
            ],
        ];
    }

    /**
     * Chart data for bonding providers
     */
    private function getBondingChartData($user)
    {
        try {
            $monthlyBookings = DB::table('bookings')
                ->selectRaw('MONTH(created_at) as month, COUNT(*) as count')
                ->where('provider_id', $user->id)
                ->whereYear('created_at', now()->year)
                ->groupBy('month')
                ->pluck('count', 'month')
                ->toArray();

            $monthLabels = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun',
                            'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];

            $engagementData = [];
            for ($i = 1; $i <= 12; $i++) {
                $engagementData[] = $monthlyBookings[$i] ?? 0;
            }
        } catch (\Exception $e) {
            $monthLabels = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'];
            $engagementData = [45, 52, 38, 67, 74, 89];
        }

        return [
            'familyEngagement' => [
                'labels' => $monthLabels,
                'data'   => $engagementData,
            ],
            'activityTypes' => [
                'labels' => ['Outdoor Activities', 'Cooking Together', 'Arts & Crafts', 'Sports & Games', 'Educational'],
                'data'   => [35, 25, 20, 15, 5],
            ],
            'feedbackScores' => [
                'labels' => ['5 ★', '4 ★', '3 ★', '2 ★', '1 ★'],
                'data'   => [78, 15, 4, 2, 1],
            ],
            'revenueBreakdown' => [
                'labels' => ['Family Workshops', 'Outdoor Adventures', 'Creative Sessions', 'Sports Activities', 'Educational Tours'],
                'data'   => [45000, 38000, 32000, 28000, 11200],
            ],
        ];
    }
}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use App\Models\Activity;
use App\Models\Booking;
use App\Models\User;
use App\Models\Provider;
use App\Services\DashboardMetricsService;

class BondingProviderDashboardController extends Controller
{
    protected $metricsService;

    public function __construct(DashboardMetricsService $metricsService)
    {
        $this->metricsService = $metricsService;
    }

    public function index()
    {
        $user = Auth::user();

        // Check if user should be redirected to professional dashboard
        $userRole = $user->role_id ?? ($user->role->slug ?? 'client');
        $providerData = json_decode($user->provider_data ?? '{}', true);

        // If user is a professional provider, redirect appropriately
        if (in_array($userRole, ['provider-professional', 'provider']) ||
            ($providerData['provider_type'] ?? '') === 'provider-professional') {
            return redirect()->route('dashboard.provider-professional')
                ->with('info', 'Redirected to your Professional Provider Dashboard');
        }

        // Get provider data and metrics using the service
        $cacheKey = 'bonding_provider_dashboard_' . $user->id;
        $dashboardData = Cache::remember($cacheKey, 5 * 60, function () use ($user) {
            $provider = Provider::where('user_id', $user->id)->first();

            if (!$provider) {
                return [
                    'stats' => ['activities' => 0, 'employees' => 0, 'bookings' => 0, 'earnings' => 0],
                    'provider' => null,
                    'recentActivities' => collect([]),
                    'upcomingEvents' => collect([])
                ];
            }

            return [
                'stats' => [
                    'activities' => Activity::where('provider_profile_id', $provider->id)->count(),
                    'employees' => User::where('provider_profile_id', $provider->id)->count(),
                    'bookings' => Booking::where('provider_id', $provider->id)->count(),
                    'earnings' => Booking::where('provider_id', $provider->id)->where('status', 'completed')->sum('amount') ?? 0,
                ],
                'provider' => $provider,
                'recentActivities' => Activity::where('provider_profile_id', $provider->id)
                    ->orderBy('created_at', 'desc')
                    ->limit(5)
                    ->get(),
                'upcomingEvents' => Activity::where('provider_profile_id', $provider->id)
                    ->where('start_date', '>=', now())
                    ->orderBy('start_date')
                    ->limit(5)
                    ->get()
            ];
        });

        // Get metrics using the service
        try {
            $metrics = $this->metricsService->getMetrics($user);
        } catch (\Exception $e) {
            $metrics = [];
        }

        return view('dashboards.provider-bonding.index', array_merge($dashboardData, compact('metrics')));
    }
}

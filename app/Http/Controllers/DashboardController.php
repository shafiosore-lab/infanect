<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;

class DashboardController extends Controller
{
    /**
     * Display the main dashboard
     */
    public function index(Request $request)
    {
        $user = $request->user();

        // Resolve role & provider type
        $providerData = json_decode($user->provider_data ?? '{}', true);
        $providerType = $providerData['provider_type'] ?? null;
        $userRole = $providerType
            ?? $user->role_id
            ?? ($user->role->slug ?? 'client');

        // Allow testing override via URL ?dashboard_type=
        if ($request->filled('dashboard_type')) {
            return $this->redirectToDashboard($request->get('dashboard_type'), $user);
        }

        // Priority routing: providers first
        if (in_array($userRole, ['provider-professional', 'provider'])) {
            return $this->redirectToDashboard('professional', $user);
        }
        if ($userRole === 'provider-bonding') {
            return $this->redirectToDashboard('bonding', $user);
        }
        if (in_array($userRole, ['super-admin', 'admin'])) {
            return $this->redirectToDashboard('admin', $user);
        }

        // Default: client dashboard
        return $this->showBasicDashboard($user);
    }

    private function redirectToDashboard(string $type, $user)
    {
        $routes = [
            'professional' => 'dashboard.provider-professional',
            'bonding'      => 'dashboard.provider-bonding',
            'admin'        => 'dashboard.super-admin',
            'client'       => null, // handled in showBasicDashboard
        ];

        $routeName = $routes[$type] ?? null;

        if ($routeName && Route::has($routeName)) {
            return redirect()->route($routeName);
        }

        return $this->showBasicDashboard($user);
    }

    private function showBasicDashboard($user)
    {
        // Simple dashboard data
        $dashboardData = [
            'user'            => $user,
            'welcomeMessage'  => 'Welcome to Infanect!',
            'totalBookings'   => $this->getTotalBookings($user),
            'upcomingBookings'=> $this->getUpcomingBookings($user),
            'recentActivities'=> $this->getRecentActivities($user),
            'quickActions'    => [
                ['name' => 'Browse Activities', 'url' => route('activities.index'), 'icon' => 'calendar'],
                ['name' => 'Explore Services', 'url' => route('services.index'), 'icon' => 'users'],
                ['name' => 'Training Modules', 'url' => route('training.index'), 'icon' => 'graduation-cap'],
            ]
        ];

        return view('dashboards.basic', $dashboardData);
    }

    // -----------------------------
    //  Dashboard Metrics
    // -----------------------------

    private function getTotalBookings($user)
    {
        try {
            return DB::table('bookings')->where('user_id', $user->id)->count();
        } catch (\Throwable $e) {
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
        } catch (\Throwable $e) {
            return collect([]);
        }
    }

    private function getRecentActivities($user)
    {
        // Mock fallback until activity logs are implemented
        return collect([
            (object)['name' => 'Family Yoga Session', 'date' => now()->subDays(2)],
            (object)['name' => 'Parenting Workshop', 'date' => now()->subDays(5)],
            (object)['name' => 'Child Development Talk', 'date' => now()->subDays(7)],
        ]);
    }

    private function getActivitiesCount()
    {
        try {
            return DB::table('activities')->where('is_approved', true)->count();
        } catch (\Throwable $e) {
            return 0;
        }
    }

    private function getMonthlyBookings($user)
    {
        try {
            return DB::table('bookings')
                ->where('user_id', $user->id)
                ->whereMonth('created_at', now()->month)
                ->count();
        } catch (\Throwable $e) {
            return 0;
        }
    }

    private function getCompletedBookings($user)
    {
        try {
            return DB::table('bookings')
                ->where('user_id', $user->id)
                ->where('status', 'completed')
                ->count();
        } catch (\Throwable $e) {
            return 0;
        }
    }

    private function getTotalSpending($user)
    {
        try {
            return DB::table('bookings')
                ->where('user_id', $user->id)
                ->where('status', 'completed')
                ->sum('amount_paid') ?? 0;
        } catch (\Throwable $e) {
            return 0;
        }
    }

    private function getMonthlySpending($user)
    {
        try {
            return DB::table('bookings')
                ->where('user_id', $user->id)
                ->where('status', 'completed')
                ->whereMonth('created_at', now()->month)
                ->sum('amount_paid') ?? 0;
        } catch (\Throwable $e) {
            return 0;
        }
    }

    private function getRecentBookings($user)
    {
        try {
            return DB::table('bookings')
                ->leftJoin('services', 'bookings.service_id', '=', 'services.id')
                ->leftJoin('activities', 'bookings.activity_id', '=', 'activities.id')
                ->where('bookings.user_id', $user->id)
                ->select(
                    'bookings.*',
                    DB::raw('COALESCE(services.name, activities.title, "Service") as service_name')
                )
                ->orderByDesc('bookings.created_at')
                ->limit(10)
                ->get();
        } catch (\Throwable $e) {
            return collect([]);
        }
    }

    private function getProviders()
    {
        try {
            return DB::table('providers')
                ->whereNull('deleted_at')
                ->limit(6)
                ->get();
        } catch (\Throwable $e) {
            // Fallback: use users with provider roles
            try {
                return DB::table('users')
                    ->whereIn('role_id', ['provider', 'provider-professional', 'provider-bonding'])
                    ->limit(6)
                    ->get();
            } catch (\Throwable $e2) {
                return collect([]);
            }
        }
    }

    // -----------------------------
    //  Public APIs (Ajax Endpoints)
    // -----------------------------

    public function wellness()
    {
        $wellnessStats = [
            'completed_modules' => 0,
            'current_streak'    => 0,
            'total_activities'  => $this->getActivitiesCount(),
            'favorite_category' => 'Wellness',
        ];

        return view('dashboard.wellness', compact('wellnessStats'));
    }

    public function weeklyEngagement()
    {
        return response()->json([
            'labels' => ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'],
            'data'   => [12, 19, 3, 5, 2, 3, 9], // Mock data
        ]);
    }

    public function learningProgress()
    {
        return response()->json([
            'completed'   => 15,
            'in_progress' => 8,
            'total'       => 25,
            'percentage'  => 60,
        ]);
    }

    public function stats(Request $request)
    {
        $user = $request->user();

        return response()->json([
            'bookings' => [
                'total'      => $this->getTotalBookings($user),
                'this_month' => $this->getMonthlyBookings($user),
                'completed'  => $this->getCompletedBookings($user),
            ],
            'spending' => [
                'total'      => $this->getTotalSpending($user),
                'this_month' => $this->getMonthlySpending($user),
            ]
        ]);
    }

    public function tabContent($tab)
    {
        $user = auth()->user();

        $data = match ($tab) {
            'bookings'  => $this->getRecentBookings($user),
            'activities'=> $this->getRecentActivities($user),
            'providers' => $this->getProviders(),
            default     => [],
        };

        return response()->json($data);
    }

    public function search(Request $request)
    {
        $query = trim($request->get('q', ''));

        if ($query === '') {
            return response()->json([]);
        }

        return response()->json([
            'activities' => $this->searchActivities($query),
            'providers'  => $this->searchProviders($query),
            'services'   => $this->searchServices($query),
        ]);
    }

    // -----------------------------
    //  Search Helpers
    // -----------------------------

    private function searchActivities($query)
    {
        try {
            return DB::table('activities')
                ->where('is_approved', true)
                ->where(function ($q) use ($query) {
                    $q->where('title', 'like', "%{$query}%")
                      ->orWhere('description', 'like', "%{$query}%");
                })
                ->limit(5)
                ->get();
        } catch (\Throwable $e) {
            return collect([]);
        }
    }

    private function searchProviders($query)
    {
        try {
            return DB::table('providers')
                ->whereNull('deleted_at')
                ->where(function ($q) use ($query) {
                    $q->where('name', 'like', "%{$query}%")
                      ->orWhere('bio', 'like', "%{$query}%");
                })
                ->limit(5)
                ->get();
        } catch (\Throwable $e) {
            return collect([]);
        }
    }

    private function searchServices($query)
    {
        try {
            return DB::table('services')
                ->where('is_active', true)
                ->where(function ($q) use ($query) {
                    $q->where('name', 'like', "%{$query}%")
                      ->orWhere('description', 'like', "%{$query}%");
                })
                ->limit(5)
                ->get();
        } catch (\Throwable $e) {
            return collect([]);
        }
    }
}

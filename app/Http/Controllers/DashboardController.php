<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    /**
     * Display the main dashboard
     */
    public function index(Request $request)
    {
        $user = auth()->user();
        $userId = $user->id ?? null;

        if (!$userId) {
            return redirect()->route('login');
        }

        $totalBookings = (int) DB::table('bookings')->where('user_id', $userId)->count();
        $completedBookings = (int) DB::table('bookings')->where('user_id', $userId)->where('status', 'completed')->count();
        $pendingBookings = (int) DB::table('bookings')->where('user_id', $userId)->where('status', 'pending')->count();
        $totalSpent = (float) DB::table('bookings')->where('user_id', $userId)->where('status', 'completed')->sum('amount_paid');

        return view('dashboard', [
            'totalBookings' => $totalBookings,
            'completedBookings' => $completedBookings,
            'pendingBookings' => $pendingBookings,
            'totalSpent' => number_format($totalSpent, 2),
            'header' => __('Dashboard'),
            'message' => __("You're logged in!"),
        ]);
    }

    /**
     * Display wellness dashboard
     */
    public function wellness()
    {
        $user = auth()->user();

        // Wellness-related data
        $wellnessStats = [
            'completed_modules' => 0, // This would come from user progress tracking
            'current_streak' => 0,
            'total_activities' => Activity::count(),
            'favorite_category' => 'Wellness', // This would be calculated
        ];

        return view('dashboard.wellness', compact('wellnessStats'));
    }

    /**
     * Get weekly engagement data
     */
    public function weeklyEngagement()
    {
        $user = auth()->user();

        // Mock data for weekly engagement - replace with actual data
        $engagementData = [
            'labels' => ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'],
            'data' => [12, 19, 3, 5, 2, 3, 9]
        ];

        return response()->json($engagementData);
    }

    /**
     * Get learning progress data
     */
    public function learningProgress()
    {
        $user = auth()->user();

        // Mock data for learning progress - replace with actual data
        $progressData = [
            'completed' => 15,
            'in_progress' => 8,
            'total' => 25,
            'percentage' => 60
        ];

        return response()->json($progressData);
    }

    /**
     * Get dashboard stats
     */
    public function stats()
    {
        $user = auth()->user();

        $stats = [
            'bookings' => [
                'total' => Booking::where('user_id', $user->id)->count(),
                'this_month' => Booking::where('user_id', $user->id)
                    ->whereMonth('created_at', now()->month)
                    ->count(),
                'completed' => Booking::where('user_id', $user->id)->where('status', 'completed')->count(),
            ],
            'spending' => [
                'total' => Booking::where('user_id', $user->id)->where('status', 'completed')->sum('amount_paid'),
                'this_month' => Booking::where('user_id', $user->id)
                    ->where('status', 'completed')
                    ->whereMonth('created_at', now()->month)
                    ->sum('amount_paid'),
            ]
        ];

        return response()->json($stats);
    }

    /**
     * Get tab content dynamically
     */
    public function tabContent($tab)
    {
        $user = auth()->user();

        switch ($tab) {
            case 'bookings':
                $data = Booking::where('user_id', $user->id)
                    ->with(['service', 'activity'])
                    ->orderBy('created_at', 'desc')
                    ->limit(10)
                    ->get();
                break;

            case 'activities':
                $data = Activity::where('is_approved', true)
                    ->inRandomOrder()
                    ->limit(6)
                    ->get();
                break;

            case 'providers':
                $data = ServiceProvider::inRandomOrder()
                    ->limit(6)
                    ->get();
                break;

            default:
                $data = [];
        }

        return response()->json($data);
    }

    /**
     * Search functionality
     */
    public function search(Request $request)
    {
        $query = $request->get('q', '');

        if (empty($query)) {
            return response()->json([]);
        }

        // Search activities
        $activities = Activity::where('is_approved', true)
            ->where('title', 'LIKE', "%{$query}%")
            ->orWhere('description', 'LIKE', "%{$query}%")
            ->limit(5)
            ->get();

        // Search providers
        $providers = ServiceProvider::where('name', 'LIKE', "%{$query}%")
            ->orWhere('bio', 'LIKE', "%{$query}%")
            ->limit(5)
            ->get();

        // Search services
        $services = Service::where('is_active', true)
            ->where('name', 'LIKE', "%{$query}%")
            ->orWhere('description', 'LIKE', "%{$query}%")
            ->limit(5)
            ->get();

        return response()->json([
            'activities' => $activities,
            'providers' => $providers,
            'services' => $services
        ]);
    }
}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Cache;
use App\Models\User;
use App\Models\Provider;
use App\Models\Booking;
use App\Models\Transaction;
use App\Models\Review;

class SuperAdminDashboardController extends Controller
{
    public function index()
    {
        $stats = Cache::remember('super_admin_stats', 300, function () {
            return [
                'users' => $this->getUserStats(),
                'providers' => $this->getProviderStats(),
                'bookings' => $this->getBookingStats(),
                'transactions' => $this->getTransactionStats(),
                'reviews' => $this->getReviewStats(),
                'system' => $this->getSystemStats(),
            ];
        });

        $recentActivity = $this->getRecentActivity();
        $chartData = $this->getChartData();

        return view('dashboards.super-admin.index', compact('stats', 'recentActivity', 'chartData'));
    }

    private function getUserStats()
    {
        try {
            if (!Schema::hasTable('users')) return ['total' => 0, 'active' => 0, 'providers' => 0, 'clients' => 0];

            return [
                'total' => User::count(),
                'active' => User::where('is_active', true)->count(),
                'providers' => User::where('role', 'provider')->count(),
                'clients' => User::where('role', 'client')->count(),
                'new_this_month' => User::whereMonth('created_at', now()->month)->count(),
            ];
        } catch (\Throwable $e) {
            return ['total' => 0, 'active' => 0, 'providers' => 0, 'clients' => 0, 'new_this_month' => 0];
        }
    }

    private function getProviderStats()
    {
        try {
            if (!Schema::hasTable('providers')) return ['total' => 0, 'verified' => 0, 'pending' => 0];

            return [
                'total' => Provider::count(),
                'verified' => Provider::where('kyc_status', 'approved')->count(),
                'pending' => Provider::where('kyc_status', 'pending')->count(),
                'bonding' => Provider::where('provider_type', 'provider-bonding')->count(),
                'professional' => Provider::where('provider_type', 'provider-professional')->count(),
            ];
        } catch (\Throwable $e) {
            return ['total' => 0, 'verified' => 0, 'pending' => 0, 'bonding' => 0, 'professional' => 0];
        }
    }

    private function getBookingStats()
    {
        try {
            if (!Schema::hasTable('bookings')) return ['total' => 0, 'completed' => 0, 'pending' => 0, 'revenue' => 0];

            return [
                'total' => Booking::count(),
                'completed' => Booking::where('status', 'completed')->count(),
                'pending' => Booking::where('status', 'pending')->count(),
                'confirmed' => Booking::where('status', 'confirmed')->count(),
                'this_month' => Booking::whereMonth('created_at', now()->month)->count(),
                'revenue' => Booking::where('status', 'completed')->sum('amount'),
            ];
        } catch (\Throwable $e) {
            return ['total' => 0, 'completed' => 0, 'pending' => 0, 'confirmed' => 0, 'this_month' => 0, 'revenue' => 0];
        }
    }

    private function getTransactionStats()
    {
        try {
            if (!Schema::hasTable('transactions')) return ['total' => 0, 'successful' => 0, 'failed' => 0, 'volume' => 0];

            return [
                'total' => Transaction::count(),
                'successful' => Transaction::where('status', 'success')->count(),
                'failed' => Transaction::where('status', 'failed')->count(),
                'pending' => Transaction::where('status', 'pending')->count(),
                'volume' => Transaction::where('status', 'success')->sum('amount'),
                'this_month_volume' => Transaction::where('status', 'success')
                    ->whereMonth('created_at', now()->month)
                    ->sum('amount'),
            ];
        } catch (\Throwable $e) {
            return ['total' => 0, 'successful' => 0, 'failed' => 0, 'pending' => 0, 'volume' => 0, 'this_month_volume' => 0];
        }
    }

    private function getReviewStats()
    {
        try {
            if (!Schema::hasTable('reviews')) return ['total' => 0, 'average_rating' => 0, 'five_star' => 0];

            return [
                'total' => Review::count(),
                'average_rating' => round(Review::avg('rating'), 1),
                'five_star' => Review::where('rating', 5)->count(),
                'this_month' => Review::whereMonth('created_at', now()->month)->count(),
            ];
        } catch (\Throwable $e) {
            return ['total' => 0, 'average_rating' => 0, 'five_star' => 0, 'this_month' => 0];
        }
    }

    private function getSystemStats()
    {
        return [
            'database_size' => $this->getDatabaseSize(),
            'cache_hit_rate' => 95, // Mock data
            'uptime' => '99.9%', // Mock data
            'server_load' => 'Normal', // Mock data
        ];
    }

    private function getRecentActivity()
    {
        try {
            $recentUsers = User::latest()->limit(5)->get(['id', 'name', 'email', 'created_at']);
            $recentBookings = Booking::with(['user:id,name', 'provider:id,name'])
                ->latest()
                ->limit(5)
                ->get(['id', 'user_id', 'provider_id', 'amount', 'status', 'created_at']);

            return [
                'users' => $recentUsers,
                'bookings' => $recentBookings,
            ];
        } catch (\Throwable $e) {
            return ['users' => collect(), 'bookings' => collect()];
        }
    }

    private function getChartData()
    {
        try {
            // Get last 12 months data
            $months = [];
            $userGrowth = [];
            $revenueGrowth = [];

            for ($i = 11; $i >= 0; $i--) {
                $date = now()->subMonths($i);
                $months[] = $date->format('M Y');

                $userGrowth[] = User::whereMonth('created_at', $date->month)
                    ->whereYear('created_at', $date->year)
                    ->count();

                $revenueGrowth[] = Booking::where('status', 'completed')
                    ->whereMonth('created_at', $date->month)
                    ->whereYear('created_at', $date->year)
                    ->sum('amount');
            }

            return [
                'months' => $months,
                'userGrowth' => $userGrowth,
                'revenueGrowth' => $revenueGrowth,
            ];
        } catch (\Throwable $e) {
            return [
                'months' => [],
                'userGrowth' => [],
                'revenueGrowth' => [],
            ];
        }
    }

    private function getDatabaseSize()
    {
        try {
            $size = \DB::select("SELECT ROUND(SUM(data_length + index_length) / 1024 / 1024, 1) AS 'DB Size in MB' FROM information_schema.tables WHERE table_schema = DATABASE()");
            return $size[0]->{'DB Size in MB'} . ' MB';
        } catch (\Throwable $e) {
            return 'N/A';
        }
    }
}

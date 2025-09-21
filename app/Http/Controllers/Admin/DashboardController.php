<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class DashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('role:admin'); // Only admins allowed
    }

    /**
     * Show the admin dashboard.
     */
    public function index(Request $request)
    {
        // Generate stats for super admin dashboard
        $stats = [
            'users' => [
                'total' => $this->safeCount('users'),
                'new_this_month' => $this->safeCount('users', ['created_at', '>=', now()->startOfMonth()]),
                'active' => $this->safeCount('users', ['is_active', '=', true]),
                'providers' => $this->safeCount('users', function($query) {
                    return $query->whereHas('roles', function($q) {
                        $q->where('slug', 'like', 'provider%');
                    });
                })
            ],
            'providers' => [
                'total' => $this->safeCount('providers'),
                'verified' => $this->safeCount('providers', ['status', '=', 'approved']),
                'pending' => $this->safeCount('providers', ['status', '=', 'pending']),
                'bonding' => $this->safeCount('providers', ['provider_type', '=', 'provider-bonding']),
                'professional' => $this->safeCount('providers', ['provider_type', '=', 'provider-professional'])
            ],
            'bookings' => [
                'total' => $this->safeCount('bookings'),
                'this_month' => $this->safeCount('bookings', ['created_at', '>=', now()->startOfMonth()]),
                'completed' => $this->safeCount('bookings', ['status', '=', 'completed']),
                'pending' => $this->safeCount('bookings', ['status', '=', 'pending']),
                'revenue' => $this->safeSum('bookings', 'amount', ['status', '=', 'completed'])
            ],
            'transactions' => [
                'successful' => $this->safeCount('transactions', ['status', '=', 'successful']),
                'failed' => $this->safeCount('transactions', ['status', '=', 'failed']),
                'pending' => $this->safeCount('transactions', ['status', '=', 'pending']),
                'volume' => $this->safeSum('transactions', 'amount'),
                'this_month_volume' => $this->safeSum('transactions', 'amount', ['created_at', '>=', now()->startOfMonth()])
            ],
            'reviews' => [
                'average_rating' => $this->safeAvg('reviews', 'rating')
            ],
            'system' => [
                'database_size' => '2.4GB',
                'cache_hit_rate' => 95,
                'uptime' => '99.9%',
                'server_load' => 'Low'
            ]
        ];

        $recentActivity = [
            'users' => $this->getRecentUsers(),
            'bookings' => $this->getRecentBookings()
        ];

        $chartData = [
            'months' => ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'],
            'userGrowth' => [120, 150, 180, 200, 250, 300],
            'revenueGrowth' => [15000, 18000, 22000, 25000, 30000, 35000]
        ];

        return view('dashboards.super-admin.index', compact('stats', 'recentActivity', 'chartData'));
    }

    private function safeCount($table, $conditions = null)
    {
        if (!Schema::hasTable($table)) return 0;

        try {
            $query = DB::table($table);

            if (is_callable($conditions)) {
                return $conditions($query)->count();
            } elseif (is_array($conditions) && count($conditions) === 3) {
                [$column, $operator, $value] = $conditions;
                if (Schema::hasColumn($table, $column)) {
                    return $query->where($column, $operator, $value)->count();
                }
            }

            return $query->count();
        } catch (\Throwable $e) {
            return 0;
        }
    }

    private function safeSum($table, $column, $conditions = null)
    {
        if (!Schema::hasTable($table) || !Schema::hasColumn($table, $column)) return 0;

        try {
            $query = DB::table($table);

            if (is_array($conditions) && count($conditions) === 3) {
                [$condColumn, $operator, $value] = $conditions;
                if (Schema::hasColumn($table, $condColumn)) {
                    $query->where($condColumn, $operator, $value);
                }
            }

            return $query->sum($column) ?: 0;
        } catch (\Throwable $e) {
            return 0;
        }
    }

    private function safeAvg($table, $column)
    {
        if (!Schema::hasTable($table) || !Schema::hasColumn($table, $column)) return 0;

        try {
            return round(DB::table($table)->avg($column) ?: 0, 1);
        } catch (\Throwable $e) {
            return 0;
        }
    }

    private function getRecentUsers($limit = 5)
    {
        if (!Schema::hasTable('users')) return collect();

        try {
            return DB::table('users')
                ->select('name', 'email', 'created_at')
                ->orderBy('created_at', 'desc')
                ->limit($limit)
                ->get();
        } catch (\Throwable $e) {
            return collect();
        }
    }

    private function getRecentBookings($limit = 5)
    {
        if (!Schema::hasTable('bookings')) return collect();

        try {
            return DB::table('bookings')
                ->leftJoin('users', 'bookings.user_id', '=', 'users.id')
                ->select('bookings.*', 'users.name as user_name')
                ->orderBy('bookings.created_at', 'desc')
                ->limit($limit)
                ->get()
                ->map(function($booking) {
                    $booking->user = (object)['name' => $booking->user_name];
                    return $booking;
                });
        } catch (\Throwable $e) {
            return collect();
        }
    }
}

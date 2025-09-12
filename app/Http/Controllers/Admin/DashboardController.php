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
        $metrics = [
            'parenting_modules' => null,
            'bookings_today' => null,
            'revenue_total' => null,
            'reviews_count' => null,
        ];

        // Safe modules count: parameterized raw query
        if (Schema::hasTable('modules')) {
            try {
                if (Schema::hasColumn('modules', 'type')) {
                    $row = DB::selectOne('SELECT COUNT(*) AS c FROM `modules` WHERE `type` = ? AND `deleted_at` IS NULL', ['parenting']);
                    $metrics['parenting_modules'] = $row->c ?? 0;
                } else {
                    $metrics['parenting_modules'] = DB::table('modules')->count();
                }
            } catch (\Throwable $e) {
                $metrics['parenting_modules'] = null;
            }
        }

        // bookings today
        if (Schema::hasTable('bookings')) {
            try {
                $metrics['bookings_today'] = DB::table('bookings')->whereDate('created_at', now()->toDateString())->count();
            } catch (\Throwable $e) {
                $metrics['bookings_today'] = null;
            }
        }

        // transactions total
        if (Schema::hasTable('transactions')) {
            try {
                $metrics['revenue_total'] = DB::table('transactions')->sum('amount');
            } catch (\Throwable $e) {
                $metrics['revenue_total'] = null;
            }
        }

        // reviews count
        if (Schema::hasTable('reviews')) {
            try {
                $metrics['reviews_count'] = DB::table('reviews')->count();
            } catch (\Throwable $e) {
                $metrics['reviews_count'] = null;
            }
        }

        if (view()->exists('admin.dashboard')) {
            return view('admin.dashboard', $metrics);
        }

        return response()->json($metrics);
    }
}

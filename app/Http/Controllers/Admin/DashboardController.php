<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Service;
use App\Models\Provider;
use App\Models\Booking;
use App\Models\Review;
use App\Models\Module; // Parenting & Training modules
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdminDashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('role:admin'); // Only admins allowed
    }

    /**
     * Show the admin dashboard.
     */
    public function index()
    {
        // --- Stats ---
        $stats = [
            'total_users'             => User::count(),
            'total_providers'         => Provider::count(),
            'total_services'          => Service::count(),
            'total_bookings'          => Booking::count(),
            'total_reviews'           => Review::count(),
            'total_revenue'           => Booking::where('status', 'completed')->sum('amount'),
            'avg_rating'              => Review::avg('rating') ?? 0,
            'available_providers'     => Provider::where('status', 'active')->count(),
            'total_parenting_modules' => Module::where('type', 'parenting')->count(),
            'total_training_modules'  => Module::where('type', 'training')->count(),
            'completed_modules'       => DB::table('user_modules')->where('status', 'completed')->count(),
            'active_learners'         => DB::table('user_modules')->distinct('user_id')->count('user_id'),
            'active_clients'          => Booking::distinct('user_id')->count('user_id'),
            'revenue'                 => Booking::where('status', 'completed')->sum('amount'),
        ];

        // --- Charts ---
        $charts = [
            'service_data' => Service::withCount('bookings')
                ->orderByDesc('bookings_count')
                ->take(5)
                ->pluck('bookings_count', 'name')
                ->toArray(),

            'client_data' => [
                'Returning' => Booking::where('status', 'completed')
                    ->select('user_id')
                    ->groupBy('user_id')
                    ->havingRaw('COUNT(id) > 1')
                    ->count(),

                'One-time' => Booking::where('status', 'completed')
                    ->select('user_id')
                    ->groupBy('user_id')
                    ->havingRaw('COUNT(id) = 1')
                    ->count(),

                'Inactive' => User::doesntHave('bookings')->count(),
            ],

            'provider_data' => Provider::select('category', DB::raw('COUNT(*) as total'))
                ->groupBy('category')
                ->pluck('total', 'category')
                ->toArray(),

            'booking_status_data' => Booking::select('status', DB::raw('COUNT(*) as total'))
                ->groupBy('status')
                ->pluck('total', 'status')
                ->toArray(),
        ];

        return view('dashboards.admin', compact('stats', 'charts'));
    }
}

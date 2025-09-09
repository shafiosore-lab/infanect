<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Booking;
use App\Models\Service;

class AnalyticsController extends Controller
{
    /**
     * Show the main analytics dashboard
     */
    public function index()
    {
        return view('admin.analytics.index');
    }

    /**
     * Show performance analytics
     */
    public function performance()
    {
        // Example data: total bookings, earnings per service, etc.
        $totalBookings = Booking::count();
        $totalEarnings = Booking::sum('amount_paid');
        $topServices = Service::withCount('bookings')
                              ->orderBy('bookings_count', 'desc')
                              ->take(5)
                              ->get();

        return view('admin.analytics.performance', compact(
            'totalBookings',
            'totalEarnings',
            'topServices'
        ));
    }

    /**
     * Show growth analytics
     */
    public function growth()
    {
        // Example: monthly bookings growth
        $monthlyBookings = Booking::selectRaw('MONTH(created_at) as month, COUNT(*) as total')
                                  ->groupBy('month')
                                  ->get();

        return view('admin.analytics.growth', compact('monthlyBookings'));
    }

    /**
     * Show retention analytics
     */
    public function retention()
    {
        // Example: returning clients
        $returningClients = Booking::where('is_returning', true)->count();
        $totalClients = Booking::count();

        return view('admin.analytics.retention', compact('returningClients', 'totalClients'));
    }

    /**
     * Show engagement analytics
     */
    public function engagement()
    {
        // Example: service engagement counts
        $engagements = Service::withCount('bookings')->get();

        return view('admin.analytics.engagement', compact('engagements'));
    }
}

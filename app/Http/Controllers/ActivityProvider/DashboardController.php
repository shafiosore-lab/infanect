<?php

namespace App\Http\Controllers\ActivityProvider;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        // Activity provider-specific metrics
        $metrics = (object)[
            'total_activities' => 12,
            'active_bookings' => 8,
            'total_participants' => 45,
            'this_month_participants' => 18,
            'total_revenue' => 3200.00,
            'this_month_revenue' => 1200.00,
            'average_rating' => 4.8,
            'upcoming_activities' => 5
        ];

        // Recent bookings with participants
        $recentBookings = collect([
            (object)[
                'id' => 1,
                'activity' => 'Family Safari Adventure',
                'family' => 'The Johnson Family',
                'participants' => 4,
                'date' => '2024-02-15',
                'status' => 'confirmed',
                'amount' => 1800.00
            ],
            (object)[
                'id' => 2,
                'activity' => 'Art & Craft Workshop',
                'family' => 'The Williams Family',
                'participants' => 2,
                'date' => '2024-02-20',
                'status' => 'pending',
                'amount' => 70.00
            ]
        ]);

        // Participant age distribution
        $ageDistribution = [
            'Toddlers (2-4)' => 8,
            'Children (5-12)' => 22,
            'Teenagers (13-17)' => 10,
            'Adults (18+)' => 15
        ];

        // Monthly revenue trend
        $revenueTrend = [
            'August' => 2800,
            'September' => 3200,
            'October' => 2900,
            'November' => 3500,
            'December' => 4200,
            'January' => 1200
        ];

        return view('activity-provider.dashboard', compact('metrics', 'recentBookings', 'ageDistribution', 'revenueTrend'));
    }
}

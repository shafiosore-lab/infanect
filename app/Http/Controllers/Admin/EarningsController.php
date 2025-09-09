<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Models\Booking;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class EarningsController extends Controller
{
    /**
     * Show earnings dashboard.
     */
    public function index(Request $request)
    {
        // 0️⃣ Filters (optional for scalability)
        $country  = $request->input('country');   // e.g., 'Kenya', 'USA'
        $platform = $request->input('platform');  // e.g., 'web', 'mobile'

        // Base query
        $query = Booking::query();

        if ($country) {
            $query->byCountry($country);
        }
        if ($platform) {
            $query->byPlatform($platform);
        }

        // 1️⃣ Summary Metrics
        $totalEarnings   = $query->sum('amount_paid');
        $monthlyEarnings = $query->whereMonth('created_at', now()->month)
                                 ->whereYear('created_at', now()->year)
                                 ->sum('amount_paid');
        $pendingPayouts  = $query->where('status', 'pending')->sum('amount');

        $returningClients = $query->where('is_returning', true)->count();
        $bookingsCount    = $query->count();

        // 2️⃣ Monthly Earnings Data (last 12 months for charts)
        $monthlyLabels = [];
        $monthlyValues = [];
        for ($i = 11; $i >= 0; $i--) {
            $month = Carbon::now()->subMonths($i);
            $monthlyLabels[] = $month->format('M Y');
            $monthlyValues[] = Booking::when($country, fn($q) => $q->where('country', $country))
                                      ->when($platform, fn($q) => $q->where('platform', $platform))
                                      ->whereMonth('created_at', $month->month)
                                      ->whereYear('created_at', $month->year)
                                      ->sum('amount_paid');
        }

        // 3️⃣ Booking Status Breakdown
        $statusBreakdown = $query->select('status', DB::raw('count(*) as total'))
                                 ->groupBy('status')
                                 ->pluck('total', 'status')
                                 ->toArray();

        // 4️⃣ Recent Bookings
        $recentBookings = Booking::with('user', 'service')
                                 ->latest()
                                 ->take(10)
                                 ->get();

        // 📊 Packaged Earnings Data
        $earnings = [
            'total_earnings'    => $totalEarnings,
            'monthly_earnings'  => $monthlyEarnings,
            'pending_payouts'   => $pendingPayouts,
            'monthly_labels'    => $monthlyLabels,
            'monthly_values'    => $monthlyValues,
            'status_breakdown'  => $statusBreakdown,
            'returning_clients' => $returningClients,
            'bookings_count'    => $bookingsCount,
        ];

        return view('admin.finance.earnings', compact(
            'earnings',
            'recentBookings',
            'country',
            'platform'
        ));
    }

    /**
     * Show details for a single booking.
     */
    public function show($id)
    {
        $booking = Booking::with(['user', 'service'])->findOrFail($id);

        return view('earnings.show', compact('booking'));
    }
}

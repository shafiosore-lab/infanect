<?php

namespace App\Http\Controllers\Provider;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Booking;
use App\Models\Transaction;
use App\Models\ServiceProvider;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class MetricsController extends Controller
{
    public function index(Request $request)
    {
        // Get provider profile (not just user ID)
        $provider = ServiceProvider::where('user_id', Auth::id())->firstOrFail();

        $startDate = now()->subDays(6)->toDateString();
        $endDate   = now()->toDateString();

        // Group bookings by date
        $bookingData = Booking::select(
                DB::raw('DATE(created_at) as date'),
                DB::raw('COUNT(*) as total')
            )
            ->whereHas('activity', function ($q) use ($provider) {
                $q->where('provider_id', $provider->id);
            })
            ->whereBetween(DB::raw('DATE(created_at)'), [$startDate, $endDate])
            ->groupBy('date')
            ->pluck('total', 'date');

        // Group revenue by date
        $revenueData = Transaction::select(
                DB::raw('DATE(created_at) as date'),
                DB::raw('SUM(amount) as total')
            )
            ->where('provider_id', $provider->id)
            ->whereBetween(DB::raw('DATE(created_at)'), [$startDate, $endDate])
            ->groupBy('date')
            ->pluck('total', 'date');

        // Prepare final arrays
        $labels   = [];
        $bookings = [];
        $revenue  = [];

        for ($i = 6; $i >= 0; $i--) {
            $date = now()->subDays($i)->toDateString();
            $labels[]   = date('M d', strtotime($date));
            $bookings[] = $bookingData[$date] ?? 0;
            $revenue[]  = $revenueData[$date] ?? 0;
        }

        return response()->json([
            'labels'   => $labels,
            'bookings' => $bookings,
            'revenue'  => $revenue,
        ]);
    }
}

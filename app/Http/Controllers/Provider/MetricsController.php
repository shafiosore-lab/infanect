<?php

namespace App\Http\Controllers\Provider;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Booking;
use App\Models\Transaction;

class MetricsController extends Controller
{
    public function index(Request $request)
    {
        $providerId = $request->user()->id;
        $labels = [];
        $bookings = [];
        $revenue = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = now()->subDays($i);
            $labels[] = $date->format('M d');
            $bookings[] = Booking::where('provider_id', $providerId)->whereDate('created_at', $date->toDateString())->count();
            $revenue[] = Transaction::where('provider_id', $providerId)->whereDate('created_at', $date->toDateString())->sum('amount');
        }

        return response()->json(['labels' => $labels, 'bookings' => $bookings, 'revenue' => $revenue]);
    }
}

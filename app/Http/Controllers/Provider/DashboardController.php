<?php

namespace App\Http\Controllers\Provider;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $provider = Auth::user();
        $providerId = $provider->id ?? null;

        if (!$providerId) {
            return redirect()->route('login');
        }

        // Stats computed directly to ensure scalar values
        $totalBookings = (int) DB::table('bookings')->where('provider_id', $providerId)->count();
        $completedBookings = (int) DB::table('bookings')->where('provider_id', $providerId)->where('status', 'completed')->count();
        $pendingBookings = (int) DB::table('bookings')->where('provider_id', $providerId)->where('status', 'pending')->count();
        $totalEarnings = (float) DB::table('bookings')->where('provider_id', $providerId)->where('status', 'completed')->sum('amount_paid');

        return view('provider.dashboard', [
            'totalBookings' => $totalBookings,
            'completedBookings' => $completedBookings,
            'pendingBookings' => $pendingBookings,
            'totalEarnings' => number_format($totalEarnings, 2),
        ]);
    }
}

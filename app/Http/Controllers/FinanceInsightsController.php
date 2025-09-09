<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Booking;
use App\Models\Service;
use App\Models\User;

class FinanceInsightsController extends Controller
{
    /**
     * Display a high-level finance dashboard for admin.
     */
    public function index()
    {
        $totalEarnings = Booking::sum('amount_paid'); // assuming amount_paid column exists
        $monthlyEarnings = Booking::whereMonth('created_at', now()->month)
                                   ->sum('amount_paid');
        $pendingPayouts = Booking::where('status', 'pending')->sum('amount_paid');

        $activeProviders = User::whereHas('role', fn($q) => $q->where('slug', 'provider'))
                               ->where('is_active', true)
                               ->count();

        $popularServices = Service::withCount('bookings')
                                  ->orderByDesc('bookings_count')
                                  ->take(5)
                                  ->get();

        return view('admin.finance.insights', compact(
            'totalEarnings',
            'monthlyEarnings',
            'pendingPayouts',
            'activeProviders',
            'popularServices'
        ));
    }
}

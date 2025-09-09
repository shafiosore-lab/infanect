<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Service;
use App\Models\Booking;
use App\Models\User;

class FinanceInsightsController extends Controller
{
    /**
     * Display a high-level finance dashboard for admin.
     */
    public function index()
    {
        // Total earnings from all services
        $totalEarnings = Booking::sum('amount_paid'); // assuming you track 'amount_paid'

        // Monthly earnings (current month)
        $monthlyEarnings = Booking::whereMonth('created_at', now()->month)
                                   ->sum('amount_paid');

        // Pending payouts to providers
        $pendingPayouts = Booking::where('status', 'pending')->sum('amount_paid');

        // Number of active providers
        $activeProviders = User::whereHas('role', fn($q) => $q->where('slug', 'provider'))
                               ->where('is_active', true)
                               ->count();

        // Most popular services
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

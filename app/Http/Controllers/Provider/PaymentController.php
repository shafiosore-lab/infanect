<?php

namespace App\Http\Controllers\Provider;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Transaction;
use App\Models\Booking;
use App\Models\ServiceProvider;
use Illuminate\Support\Facades\Auth;

class PaymentController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $provider = ServiceProvider::where('user_id', $user->id)->first();

        $transactions = collect(); // Empty collection if no provider
        $stats = [
            'total_earnings' => 0,
            'pending_payments' => 0,
            'this_month' => 0,
        ];

        if ($provider) {
            $transactions = Transaction::whereHas('booking.activity', function($query) use ($provider) {
                $query->where('provider_id', $provider->id);
            })
            ->with(['booking.activity', 'booking.user'])
            ->orderBy('created_at', 'desc')
            ->paginate(10);

            $stats = [
                'total_earnings' => Transaction::whereHas('booking.activity', function($query) use ($provider) {
                    $query->where('provider_id', $provider->id);
                })->where('status', 'completed')->sum('amount'),
                'pending_payments' => Transaction::whereHas('booking.activity', function($query) use ($provider) {
                    $query->where('provider_id', $provider->id);
                })->where('status', 'pending')->sum('amount'),
                'this_month' => Transaction::whereHas('booking.activity', function($query) use ($provider) {
                    $query->where('provider_id', $provider->id);
                })->where('status', 'completed')
                ->whereMonth('created_at', now()->month)
                ->whereYear('created_at', now()->year)
                ->sum('amount'),
            ];
        }

        return view('provider.payments.index', compact('transactions', 'stats', 'provider'));
    }
}

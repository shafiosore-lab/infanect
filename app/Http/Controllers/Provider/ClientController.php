<?php

namespace App\Http\Controllers\Provider;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Booking;
use App\Models\ServiceProvider;
use Illuminate\Support\Facades\Auth;

class ClientController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $provider = ServiceProvider::where('user_id', $user->id)->first();

        $clients = collect(); // Empty collection if no provider

        if ($provider) {
            // Get unique clients who have booked activities from this provider
            $clients = User::whereHas('bookings.activity', function($query) use ($provider) {
                $query->where('provider_id', $provider->id);
            })
            ->with(['bookings' => function($query) use ($provider) {
                $query->whereHas('activity', function($subQuery) use ($provider) {
                    $subQuery->where('provider_id', $provider->id);
                })->with('activity');
            }])
            ->withCount(['bookings' => function($query) use ($provider) {
                $query->whereHas('activity', function($subQuery) use ($provider) {
                    $subQuery->where('provider_id', $provider->id);
                });
            }])
            ->orderBy('bookings_count', 'desc')
            ->paginate(10);
        }

        return view('provider.clients.index', compact('clients', 'provider'));
    }

    public function show(User $client)
    {
        $user = Auth::user();
        $provider = ServiceProvider::where('user_id', $user->id)->first();

        $bookings = collect(); // Empty collection if no provider
        $stats = [
            'total_bookings' => 0,
            'total_spent' => 0,
            'last_booking' => null,
        ];

        if ($provider) {
            // Get client's bookings for this provider's activities
            $bookings = Booking::where('user_id', $client->id)
                ->whereHas('activity', function($query) use ($provider) {
                    $query->where('provider_id', $provider->id);
                })
                ->with('activity')
                ->orderBy('created_at', 'desc')
                ->get();

            $stats = [
                'total_bookings' => $bookings->count(),
                'total_spent' => $bookings->where('status', 'completed')->sum('amount'),
                'last_booking' => $bookings->first()?->created_at,
            ];
        }

        return view('provider.clients.show', compact('client', 'bookings', 'stats', 'provider'));
    }
}

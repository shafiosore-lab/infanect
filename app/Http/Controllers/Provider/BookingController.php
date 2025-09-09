<?php

namespace App\Http\Controllers\Provider;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Booking;
use App\Models\Activity;
use App\Models\ServiceProvider;
use Illuminate\Support\Facades\Auth;

class BookingController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $provider = ServiceProvider::where('user_id', $user->id)->first();

        $bookings = collect(); // Empty collection if no provider

        if ($provider) {
            $bookings = Booking::whereHas('activity', function($query) use ($provider) {
                $query->where('provider_id', $provider->id);
            })
            ->with(['activity', 'user'])
            ->orderBy('created_at', 'desc')
            ->paginate(10);
        }

        return view('provider.bookings.index', compact('bookings', 'provider'));
    }

    public function show(Booking $booking)
    {
        $user = Auth::user();
        $provider = ServiceProvider::where('user_id', $user->id)->first();

        // Ensure booking belongs to this provider
        if ($booking->activity->provider_id !== $provider->id) {
            abort(403);
        }

        return view('provider.bookings.show', compact('booking', 'provider'));
    }

    public function update(Request $request, Booking $booking)
    {
        $user = Auth::user();
        $provider = ServiceProvider::where('user_id', $user->id)->first();

        // Ensure booking belongs to this provider
        if ($booking->activity->provider_id !== $provider->id) {
            abort(403);
        }

        $request->validate([
            'status' => 'required|in:pending,confirmed,cancelled,completed',
        ]);

        $booking->update([
            'status' => $request->status,
        ]);

        return redirect()->back()->with('success', 'Booking status updated successfully.');
    }
}

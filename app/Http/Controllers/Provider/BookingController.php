<?php

namespace App\Http\Controllers\Provider;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Booking;
use App\Models\ServiceProvider;
use Illuminate\Support\Facades\Auth;

class BookingController extends Controller
{
    /**
     * Display a list of bookings for the provider.
     */
    public function index()
    {
        $provider = ServiceProvider::where('user_id', Auth::id())->first();

        if (!$provider) {
            return redirect()->route('dashboard')
                ->with('error', 'You must be a registered provider to view bookings.');
        }

        $bookings = Booking::with(['activity', 'user'])
            ->whereHas('activity', fn($q) => $q->where('provider_id', $provider->id))
            ->latest()
            ->paginate(10);

        return view('provider.bookings.index', compact('bookings', 'provider'));
    }

    /**
     * Show details of a specific booking.
     */
    public function show(Booking $booking)
    {
        $this->authorize('view', $booking);

        $provider = ServiceProvider::where('user_id', Auth::id())->first();

        return view('provider.bookings.show', compact('booking', 'provider'));
    }

    /**
     * Update booking status (confirm/cancel/complete).
     */
    public function update(Request $request, Booking $booking)
    {
        $this->authorize('update', $booking);

        $validated = $request->validate([
            'status' => 'required|in:pending,confirmed,cancelled,completed',
        ]);

        $booking->update($validated);

        return back()->with('success', 'Booking status updated successfully.');
    }
}

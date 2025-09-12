<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Carbon\Carbon;

class ServiceBookingController extends Controller
{
    public function store(Request $request, $serviceId)
    {
        $request->validate([
            'start_at' => 'required|date',
            'end_at' => 'required|date|after:start_at',
            'timezone' => 'nullable|string',
        ]);

        $service = \App\Models\Service::findOrFail($serviceId);
        $user = Auth::user();

        $tz = $request->input('timezone') ?? config('app.timezone');

        // Parse start/end in user's timezone then convert to UTC for storage
        try {
            $start = Carbon::parse($request->input('start_at'), $tz)->setTimezone('UTC');
            $end = Carbon::parse($request->input('end_at'), $tz)->setTimezone('UTC');
        } catch (\Exception $e) {
            return back()->withErrors(['start_at' => 'Invalid date/time']);
        }

        // Ensure slot is still free
        $overlap = $service->bookings()->where('status', '!=', 'canceled')
            ->where(function($q) use ($start, $end) {
                $q->whereBetween('start_at', [$start->toDateTimeString(), $end->toDateTimeString()])
                  ->orWhereBetween('end_at', [$start->toDateTimeString(), $end->toDateTimeString()])
                  ->orWhere(function($q2) use ($start, $end) {
                      $q2->where('start_at', '<=', $start->toDateTimeString())->where('end_at', '>=', $end->toDateTimeString());
                  });
            })->exists();

        if ($overlap) {
            return back()->withErrors(['slot' => 'Selected time slot is no longer available.']);
        }

        $booking = \App\Models\Booking::create([
            'service_id' => $service->id,
            'provider_id' => $service->provider_id,
            'user_id' => $user->id,
            'start_at' => $start,
            'end_at' => $end,
            'status' => 'pending',
            'amount_paid' => $service->price,
            'currency' => $service->currency ?? 'KES',
            'booking_meta' => [
                'ip' => $request->ip(),
                'user_agent' => $request->header('User-Agent'),
                'timezone' => $tz,
            ],
        ]);

        // Create payment record placeholder
        $payment = \App\Models\Payment::create([
            'booking_id' => $booking->id,
            'gateway' => null,
            'status' => 'pending',
            'amount' => $booking->amount_paid,
            'currency' => $booking->currency,
            'meta' => null,
        ]);

        // Send booking created emails
        try {
            Mail::to($user->email)->send(new \App\Mail\BookingCreated($booking));
            if ($service->provider && $service->provider->email) {
                Mail::to($service->provider->email)->send(new \App\Mail\BookingCreated($booking));
            }
        } catch (\Exception $e) {
            // Log but don't break user flow
            \Log::error('Mail send failed for booking '.$booking->id.': '.$e->getMessage());
        }

        return redirect()->route('payments.checkout', $booking->id);
    }
}

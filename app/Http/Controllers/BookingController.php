<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Booking;
use App\Models\Activity;
use App\Models\Service;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class BookingController extends Controller
{
    /**
     * Display paginated bookings for the authenticated user.
     */
    public function index(Request $request)
    {
        $request->validate([
            'direction' => 'nullable|in:asc,desc',
            'sort' => 'nullable|string'
        ]);

        $user = auth()->user();

        $query = Booking::with(['activity.provider', 'service'])
                        ->where('user_id', $user->id);

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('date_from')) {
            $query->where('scheduled_at', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->where('scheduled_at', '<=', $request->date_to);
        }

        $allowedSorts = ['created_at', 'scheduled_at', 'amount'];
        $sortBy = in_array($request->get('sort', 'created_at'), $allowedSorts) ? $request->get('sort', 'created_at') : 'created_at';
        $sortDirection = in_array(strtolower($request->get('direction', 'desc')), ['asc','desc']) ? strtolower($request->get('direction', 'desc')) : 'desc';

        $query->orderBy($sortBy, $sortDirection);

        $bookings = $query->paginate(10);

        return view('bookings.index', compact('bookings'));
    }

    /**
     * Show a single booking details.
     */
    public function show(Booking $booking)
    {
        $this->authorizeBooking($booking);
        $booking->load(['activity.provider', 'service']);
        return view('bookings.show', compact('booking'));
    }

    /**
     * Show booking creation page for an activity.
     */
    public function create(Activity $activity = null)
    {
        $user = auth()->user();

        if ($activity) {
            $existingBooking = Booking::where('user_id', $user->id)
                                      ->where('activity_id', $activity->id)
                                      ->first();

            if ($existingBooking) {
                return redirect()->route('bookings.show', $existingBooking)
                                 ->with('info', 'You have already booked this activity.');
            }

            // Pass activity and null for availableActivities
            return view('bookings.create', [
                'activity' => $activity,
                'availableActivities' => null
            ]);
        }

        $availableActivities = Activity::upcoming()
                                       ->with('provider')
                                       ->whereDoesntHave('bookings', function ($q) use ($user) {
                                           $q->where('user_id', $user->id);
                                       })
                                       ->paginate(12);

        // Pass null for single activity to prevent undefined variable
        return view('bookings.create', [
            'activity' => null,
            'availableActivities' => $availableActivities
        ]);
    }

    /**
     * Store a new booking for an activity.
     */
    public function store(Request $request)
    {
        $request->validate([
            'activity_id' => 'required|exists:activities,id',
            'customer_name' => 'required|string|max:255',
            'customer_email' => 'required|email|max:255',
            'customer_phone' => 'required|string|max:20',
            'participants' => 'nullable|integer|min:1',
            'currency' => 'required|string|in:KES,USD,EUR,GBP',
            'payment_method' => 'required|in:mpesa,manual',
            'mpesa_number' => 'required_if:payment_method,mpesa|string|max:20',
            'payment_code' => 'required_if:payment_method,manual|string|max:50',
            'notes' => 'nullable|string|max:500',
        ]);

        $user = auth()->user();
        $activity = Activity::with('bookings')->findOrFail($request->activity_id);
        $participants = $request->participants ?? 1;

        if (Booking::where('user_id', $user->id)->where('activity_id', $activity->id)->exists()) {
            return response()->json([
                'success' => false,
                'message' => 'You have already booked this activity.'
            ], 409);
        }

        if ($participants > $activity->availableSlots()) {
            return response()->json([
                'success' => false,
                'message' => 'Not enough slots available for ' . $participants . ' participants.'
            ], 422);
        }

        // Calculate total amount
        $rates = ['KES'=>1,'USD'=>0.0072,'EUR'=>0.0065,'GBP'=>0.0056];
        $currency = $request->currency;
        $totalAmount = $activity->price * $participants * ($rates[$currency] ?? 1);

        $transactionId = 'PAY' . time();

        $booking = Booking::create([
            'user_id' => $user->id,
            'activity_id' => $activity->id,
            'provider_id' => $activity->provider_id,
            'amount' => $totalAmount,
            'amount_paid' => 0,
            'currency_code' => $currency,
            'status' => 'pending',
            'scheduled_at' => $activity->datetime,
            'customer_name' => $request->customer_name,
            'customer_email' => $request->customer_email,
            'customer_phone' => $request->customer_phone,
            'participants' => $participants,
            'payment_method' => $request->payment_method,
            'transaction_id' => $transactionId,
            'notes' => $request->notes,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Booking created successfully!',
            'booking' => $booking,
            'redirect' => route('payments.confirm', $booking->id)
        ]);
    }

    /**
     * Cancel a booking.
     */
    public function cancel(Request $request, Booking $booking)
    {
        $this->authorizeBooking($booking);

        if ($booking->status === 'completed' || ($booking->scheduled_at && $booking->scheduled_at->isPast())) {
            return response()->json([
                'success' => false,
                'message' => 'Cannot cancel this booking.'
            ], 422);
        }

        $booking->update(['status' => 'cancelled']);

        return response()->json([
            'success' => true,
            'message' => 'Booking cancelled successfully.',
            'booking' => $booking
        ]);
    }

    /**
     * Show booking form for a service.
     */
    public function createForService(Service $service)
    {
        return view('bookings.create_service', compact('service'));
    }

    /**
     * Store booking for a service.
     */
    public function storeServiceBooking(Request $request, Service $service)
    {
        $request->validate([
            'date' => 'required|date',
            'slots' => 'required|integer|min:1',
        ]);

        $user = auth()->user();

        if ($user->bookings()->where('service_id', $service->id)->exists()) {
            return redirect()->back()->with('info', 'You have already booked this service.');
        }

        $booking = Booking::create([
            'user_id' => $user->id,
            'service_id' => $service->id,
            'provider_id' => $service->user_id,
            'scheduled_at' => $request->date,
            'participants' => $request->slots,
            'status' => 'pending',
            'amount' => $service->price * $request->slots,
            'currency_code' => 'KES',
            'payment_method' => 'manual',
        ]);

        return redirect()->route('dashboard')->with('success', 'Service booked successfully!');
    }

    /**
     * Ensure authenticated user owns the booking.
     */
    protected function authorizeBooking(Booking $booking)
    {
        if ($booking->user_id !== auth()->id()) {
            abort(403, 'Unauthorized action.');
        }
    }
}

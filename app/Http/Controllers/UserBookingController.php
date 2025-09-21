<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Booking;
use App\Models\Activity;
use App\Models\Service;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Log;

class UserBookingController extends Controller
{
    /**
     * Display paginated bookings for the authenticated user.
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        if (!$user) {
            return redirect()->route('login');
        }

        if (!Schema::hasTable('bookings')) {
            return view('bookings.index', ['bookings' => collect()]);
        }

        $request->validate([
            'direction' => 'nullable|in:asc,desc',
            'sort' => 'nullable|string'
        ]);

        $perPage   = 15;
        $sort      = $request->get('sort', 'created_at');
        $direction = $request->get('direction', 'desc');

        try {
            if (class_exists(\App\Models\Booking::class)) {
                $query = Booking::query()
                    ->where('user_id', $user->id)
                    ->orderBy($sort, $direction);

                $with = [];
                if (method_exists(Booking::class, 'service')) $with[] = 'service';
                if (method_exists(Booking::class, 'provider')) $with[] = 'provider';
                if (method_exists(Booking::class, 'activity')) $with[] = 'activity';
                if (!empty($with)) $query->with($with);

                $bookings = $query->paginate($perPage);
            } else {
                $bookings = DB::table('bookings')
                    ->where('user_id', $user->id)
                    ->orderBy($sort, $direction)
                    ->paginate($perPage);
            }
        } catch (\Throwable $e) {
            Log::error('Error fetching bookings', ['error' => $e->getMessage()]);
            $bookings = collect();
        }

        return view('bookings.index', compact('bookings'));
    }

    /**
     * Show a single booking details.
     */
    public function show(Booking $booking)
    {
        $this->authorizeBooking($booking);
        $booking->load(['activity', 'service', 'provider']);
        return view('bookings.show', compact('booking'));
    }

    /**
     * Show booking creation page for activities.
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

            return view('bookings.create', compact('activity'));
        }

        $availableActivities = Activity::upcoming()
            ->with('provider')
            ->whereDoesntHave('bookings', function ($q) use ($user) {
                $q->where('user_id', $user->id);
            })
            ->paginate(12);

        return view('bookings.create', compact('availableActivities'));
    }

    /**
     * Show booking creation page for services.
     */
    public function createForService(Service $service)
    {
        $user = auth()->user();

        $existingBooking = Booking::where('user_id', $user->id)
            ->where('service_id', $service->id)
            ->first();

        if ($existingBooking) {
            return redirect()->route('bookings.show', $existingBooking)
                ->with('info', 'You have already booked this service.');
        }

        return view('bookings.create-service', compact('service'));
    }

    /**
     * Store a new booking and redirect to payment confirmation.
     */
    public function store(Request $request)
    {
        $request->validate([
            'activity_id' => 'nullable|exists:activities,id',
            'service_id' => 'nullable|exists:services,id',
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

        if (!$request->activity_id && !$request->service_id) {
            return $this->jsonOrRedirect($request, [
                'success' => false,
                'message' => 'Either activity or service must be specified.'
            ], 422);
        }

        $user = auth()->user();
        $activity = null;
        $service = null;
        $providerId = null;
        $price = 0;

        if ($request->activity_id) {
            $activity = Activity::with('bookings')->findOrFail($request->activity_id);
            $providerId = $activity->provider_id;
            $price = $activity->price;
        } elseif ($request->service_id) {
            $service = Service::with('bookings')->findOrFail($request->service_id);
            $providerId = $service->provider_id;
            $price = $service->price;
        }

        $participants = $request->participants ?? 1;

        $existingQuery = Booking::where('user_id', $user->id);
        if ($activity) {
            $existingQuery->where('activity_id', $activity->id);
        } elseif ($service) {
            $existingQuery->where('service_id', $service->id);
        }

        if ($existingQuery->exists()) {
            return $this->jsonOrRedirect($request, [
                'success' => false,
                'message' => 'You have already booked this ' . ($activity ? 'activity' : 'service') . '.'
            ], 409);
        }

        if ($activity && method_exists($activity, 'availableSlots') &&
            $participants > $activity->availableSlots()) {
            return $this->jsonOrRedirect($request, [
                'success' => false,
                'message' => 'Not enough slots available for ' . $participants . ' participants.'
            ], 422);
        }

        // Ensure base prices are in KES, convert if needed
        $rates = ['KES'=>1,'USD'=>0.0072,'EUR'=>0.0065,'GBP'=>0.0056];
        $currency = $request->currency;
        $totalAmount = $price * $participants * ($rates[$currency] ?? 1);

        $transactionId = 'PAY' . time();

        DB::beginTransaction();
        try {
            $booking = Booking::create([
                'user_id' => $user->id,
                'activity_id' => $activity ? $activity->id : null,
                'service_id' => $service ? $service->id : null,
                'provider_id' => $providerId,
                'amount' => $totalAmount,
                'amount_paid' => 0,
                'currency_code' => $currency,
                'status' => 'pending',
                'scheduled_at' => $activity ? $activity->datetime : now(),
                'customer_name' => $request->customer_name,
                'customer_email' => $request->customer_email,
                'customer_phone' => $request->customer_phone,
                'participants' => $participants,
                'payment_method' => $request->payment_method,
                'transaction_id' => $transactionId,
                'notes' => $request->notes,
            ]);

            DB::commit();

            return $this->jsonOrRedirect($request, [
                'success' => true,
                'message' => 'Booking created successfully!',
                'booking' => $booking,
                'redirect' => route('payments.confirm', $booking->id)
            ]);
        } catch (\Exception $e) {
            DB::rollback();
            Log::error('Booking creation failed', ['error' => $e->getMessage()]);
            return $this->jsonOrRedirect($request, [
                'success' => false,
                'message' => 'Booking failed, please try again later.'
            ], 500);
        }
    }

    /**
     * Cancel a booking.
     */
    public function cancel(Request $request, Booking $booking)
    {
        $this->authorizeBooking($booking);

        if ($booking->status === 'completed' || ($booking->scheduled_at && $booking->scheduled_at->isPast())) {
            return $this->jsonOrRedirect($request, [
                'success' => false,
                'message' => 'Cannot cancel this booking.'
            ], 422);
        }

        $booking->update(['status' => 'cancelled']);

        return $this->jsonOrRedirect($request, [
            'success' => true,
            'message' => 'Booking cancelled successfully.',
            'booking' => $booking
        ]);
    }

    /**
     * Ensure authenticated user owns the booking
     */
    protected function authorizeBooking(Booking $booking)
    {
        if ($booking->user_id !== auth()->id()) {
            abort(403, 'Unauthorized action.');
        }
    }

    /**
     * Decide response format (JSON for API, redirect/flash for web).
     */
    protected function jsonOrRedirect(Request $request, array $data, int $status = 200)
    {
        if ($request->wantsJson()) {
            return response()->json($data, $status);
        }

        if ($status >= 400) {
            return redirect()->back()->withErrors($data['message'] ?? 'Something went wrong.');
        }

        return redirect($data['redirect'] ?? route('bookings.index'))
            ->with('success', $data['message'] ?? 'Success.');
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\Activity;
use App\Models\Booking;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Carbon\Carbon;

class ActivityController extends Controller
{
    /**
     * List activities with filters and pagination.
     */
    public function index(Request $request)
    {
        try {
            $query = Activity::query()->active()->with(['creator', 'provider']);

            // Apply filters
            if ($request->filled('category')) {
                $query->byCategory($request->category);
            }

            if ($request->filled('age_group')) {
                $query->byAgeGroup($request->age_group);
            }

            if ($request->filled('difficulty_level')) {
                $query->where('difficulty_level', $request->difficulty_level);
            }

            if ($request->filled('search')) {
                $query->where(function ($q) use ($request) {
                    $q->where('title', 'like', '%' . $request->search . '%')
                      ->orWhere('description', 'like', '%' . $request->search . '%');
                });
            }

            // Provider filter for dashboard
            if (auth()->check() && auth()->user()->role === 'provider') {
                $query->byProvider(auth()->id());
            }

            $activities = $query->orderBy('created_at', 'desc')->paginate(12);

            return view('activities.index', compact('activities'));

        } catch (\Exception $e) {
            \Log::error('Activities index error: ' . $e->getMessage());

            $activities = new \Illuminate\Pagination\LengthAwarePaginator(
                collect([]), 0, 12, 1,
                ['path' => request()->url(), 'pageName' => 'page']
            );

            return view('activities.index', compact('activities'))
                ->with('error', 'There was an issue loading activities. Please try again.');
        }
    }

    /**
     * Show activity details.
     */
    public function show($id)
    {
        $activity = Activity::with(['creator', 'provider', 'bookings'])->find($id);

        if (!$activity) {
            abort(404, 'Activity not found');
        }

        $related = Activity::active()
            ->where('category', $activity->category)
            ->where('id', '!=', $id)
            ->take(3)
            ->get();

        return view('activities.show', compact('activity', 'related'));
    }

    /**
     * Store booking in session before payment.
     */
    public function storeBooking(Request $request, $id)
    {
        $request->validate([
            'participants' => 'required|integer|min:1',
            'date' => 'required|date|after_or_equal:today',
            'participant_details' => 'nullable|string|max:1000',
        ]);

        if (!auth()->check()) {
            return redirect()->route('login')->with('message', 'Please log in to make a booking.');
        }

        $activity = Activity::findOrFail($id);

        // Check availability (assumes participants stored as JSON { "count": x })
        $existingBookings = Booking::where('activity_id', $activity->id)
            ->whereDate('booking_date', $request->date)
            ->where('status', '!=', 'cancelled')
            ->sum(DB::raw("JSON_UNQUOTE(JSON_EXTRACT(participants, '$.count'))"));

        if (($existingBookings + $request->participants) > $activity->max_participants) {
            return back()->withErrors(['participants' => 'Not enough spots available for this date.']);
        }

        try {
            $booking = Booking::create([
                'user_id' => auth()->id(),
                'activity_id' => $activity->id,
                'provider_id' => $activity->provider_id ?? $activity->created_by,
                'service_type' => $activity->type ?? 'bonding',
                'booking_date' => $request->date,
                'duration' => $activity->duration_minutes,
                'status' => 'pending',
                'amount' => $activity->price * $request->participants,
                'notes' => $request->participant_details,
                'participants' => ['count' => $request->participants],
                'reference' => 'BK-' . strtoupper(Str::random(8)),
            ]);

            Session::put('booking', [
                'id' => $booking->id,
                'reference' => $booking->reference,
                'amount' => $booking->amount,
            ]);

            return redirect()->route('activities.checkout', ['id' => $id])
                ->with('success', 'Booking created successfully.');

        } catch (\Exception $e) {
            \Log::error('Booking creation failed: ' . $e->getMessage());
            return back()->withErrors(['error' => 'Failed to create booking. Please try again.']);
        }
    }

    /**
     * Checkout view.
     */
    public function checkout($id)
    {
        $sessionBooking = Session::get('booking');
        if (!$sessionBooking) {
            return redirect()->route('activities.show', $id)->withErrors('No active booking found.');
        }

        $booking = Booking::with(['activity', 'user'])->find($sessionBooking['id']);
        if (!$booking || $booking->activity_id != $id) {
            return redirect()->route('activities.show', $id)->withErrors('Invalid booking session.');
        }

        return view('activities.checkout', compact('booking'));
    }

    /**
     * Process payment (mock).
     */
    public function processPayment(Request $request, $id)
    {
        $sessionBooking = Session::get('booking');
        if (!$sessionBooking) {
            return redirect()->route('activities.show', $id)->withErrors('No active booking found.');
        }

        $booking = Booking::findOrFail($sessionBooking['id']);

        try {
            $transaction = Transaction::create([
                'user_id' => auth()->id(),
                'booking_id' => $booking->id,
                'reference' => 'TX-' . strtoupper(Str::random(10)),
                'amount' => $booking->amount,
                'status' => 'success',
                'payment_method' => 'mock_payment',
                'gateway_response' => [
                    'mock' => true,
                    'processed_at' => now()->toISOString(),
                    'reference' => $booking->reference,
                ]
            ]);

            $booking->update(['status' => 'confirmed']);
            Session::forget('booking');

            return redirect()->route('activities.index')
                ->with('success', 'Payment successful! Your booking has been confirmed. Reference: ' . $booking->reference);

        } catch (\Exception $e) {
            \Log::error('Payment processing failed: ' . $e->getMessage());
            return back()->withErrors(['error' => 'Payment processing failed. Please try again.']);
        }
    }

    /**
     * Show the form for creating a new activity.
     */
    public function create()
    {
        if (!auth()->check() || auth()->user()->role !== 'provider') {
            return redirect()->route('activities.index')
                ->withErrors('Only providers can create activities.');
        }

        return view('activities.create');
    }

    /**
     * Store a newly created activity in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string|min:50',
            'category' => 'required|string',
            'age_group' => 'required|string',
            'difficulty_level' => 'required|string',
            'duration_minutes' => 'required|integer|min:15|max:480',
            'max_participants' => 'required|integer|min:1|max:100',
            'price' => 'required|numeric|min:0',
            'location' => 'required|string|max:255',
            'start_date' => 'required|date|after_or_equal:today',
        ]);

        try {
            $activity = Activity::create([
                'title' => $request->title,
                'description' => $request->description,
                'type' => 'bonding',
                'category' => $request->category,
                'age_group' => $request->age_group,
                'difficulty_level' => $request->difficulty_level,
                'duration_minutes' => $request->duration_minutes,
                'max_participants' => $request->max_participants,
                'price' => $request->price,
                'location' => $request->location,
                'start_date' => $request->start_date,
                'end_date' => Carbon::parse($request->start_date)->addMinutes($request->duration_minutes),
                'status' => 'published',
                'created_by' => auth()->id(),
                'provider_id' => auth()->id(), // ⚡ adjust if you have a providers table
                'requirements' => ['comfortable_clothes', 'positive_attitude'],
                'tags' => ['family', 'bonding', $request->category],
                'is_active' => true,
            ]);

            return redirect()->route('activities.index')
                ->with('success', 'Activity "' . $activity->title . '" created successfully!');

        } catch (\Exception $e) {
            \Log::error('Activity creation failed: ' . $e->getMessage());
            return back()->withInput()
                ->withErrors(['error' => 'Failed to create activity. Please try again.']);
        }
    }
}

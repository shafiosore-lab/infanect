<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;
use App\Models\Activity;
use App\Models\Booking;
use App\Models\Transaction;

class ActivityController extends Controller
{
    /**
     * List activities with filters and pagination.
     */
    public function index(Request $request)
    {
        $query = Activity::query();

        // Apply filters
        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }
        if ($request->filled('age_group')) {
            $query->where('age_group', $request->age_group);
        }
        if ($request->filled('location')) {
            $query->where('location', 'like', "%{$request->location}%");
        }
        if ($request->filled('search')) {
            $query->where('name', 'like', "%{$request->search}%");
        }

        $activities = $query->paginate(6);

        // Fallback to mock data if DB is empty
        if ($activities->isEmpty()) {
            $activities = $this->paginateCollection(
                $this->generateActivities(),
                6,
                $request->get('page', 1)
            );
        }

        return view('activities.index', [
            'activities' => $activities,
            'filters'    => $this->getFilters(),
        ]);
    }

    /**
     * Show activity details.
     */
    public function show($id)
    {
        $activity = Activity::find($id);

        if (!$activity) {
            $activity = collect($this->generateActivities())->firstWhere('id', $id);
            if (!$activity) {
                abort(404, 'Activity not found');
            }
        }

        $related = Activity::where('category', $activity->category)
            ->where('id', '!=', $id)
            ->take(3)
            ->get();

        if ($related->isEmpty()) {
            $related = collect($this->generateActivities())
                ->where('category', $activity->category)
                ->where('id', '!=', $id)
                ->take(3);
        }

        return view('activities.show', [
            'activity'       => $activity,
            'relatedActivities' => $related,
        ]);
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

        $activity = Activity::find($id);
        if (!$activity) {
            $activity = collect($this->generateActivities())->firstWhere('id', $id);
            if (!$activity) {
                abort(404, 'Activity not found');
            }
        }

        $booking = [
            'id'         => Str::uuid()->toString(),
            'activity_id'=> $id,
            'participants'=> $request->participants,
            'date'       => $request->date,
            'details'    => strip_tags($request->participant_details ?? ''),
            'status'     => 'pending',
            'reference'  => strtoupper(Str::random(10)),
        ];

        Session::put('booking', $booking);

        return redirect()->route('activities.checkout', ['id' => $id])
            ->with('success', 'Booking created successfully.');
    }

    /**
     * Checkout view.
     */
    public function checkout($id)
    {
        $booking = Session::get('booking');
        if (!$booking || $booking['activity_id'] != $id) {
            return redirect()->route('activities.index')->withErrors('No active booking found.');
        }

        $activity = Activity::find($id);
        if (!$activity) {
            $activity = collect($this->generateActivities())->firstWhere('id', $id);
        }

        return view('activities.checkout', compact('booking', 'activity'));
    }

    /**
     * Process payment (mock).
     */
    public function processPayment(Request $request, $id)
    {
        $booking = Session::get('booking');
        if (!$booking || $booking['activity_id'] != $id) {
            return redirect()->route('activities.index')->withErrors('No active booking found.');
        }

        // Simulate payment success
        $transaction = [
            'id'        => Str::uuid()->toString(),
            'reference' => $booking['reference'],
            'amount'    => 100, // Placeholder, replace with activity price
            'status'    => 'success',
        ];

        Session::forget('booking');

        return redirect()->route('activities.index')->with('success', 'Payment successful! Booking confirmed.');
    }

    /**
     * Mock generator (fallback).
     */
    private function generateActivities()
    {
        return [
            [
                'id' => 1,
                'name' => 'Mindful Storytelling',
                'category' => 'Storytelling',
                'age_group' => '5-8',
                'location' => 'Nairobi',
                'price' => 20,
                'duration' => '1h',
                'image' => '/images/storytelling.jpg',
                'includes' => 'Guided storytelling session, workbook',
                'requirements' => 'Notebook, pen',
            ],
            [
                'id' => 2,
                'name' => 'Creative Art Therapy',
                'category' => 'Art',
                'age_group' => '8-12',
                'location' => 'Mombasa',
                'price' => 25,
                'duration' => '1.5h',
                'image' => '/images/art.jpg',
                'includes' => 'Art supplies, guidance',
                'requirements' => 'Old clothes for painting',
            ],
            [
                'id' => 3,
                'name' => 'Outdoor Mindfulness',
                'category' => 'Wellness',
                'age_group' => '12-16',
                'location' => 'Kisumu',
                'price' => 30,
                'duration' => '2h',
                'image' => '/images/mindfulness.jpg',
                'includes' => 'Guided meditation, mats',
                'requirements' => 'Comfortable clothing',
            ],
            [
                'id' => 4,
                'name' => 'Drama for Confidence',
                'category' => 'Drama',
                'age_group' => '10-14',
                'location' => 'Eldoret',
                'price' => 15,
                'duration' => '1h',
                'image' => '/images/drama.jpg',
                'includes' => 'Role play activities',
                'requirements' => 'Open mind, creativity',
            ],
        ];
    }

    /**
     * Manual pagination for mock collection.
     */
    private function paginateCollection($items, $perPage, $page)
    {
        $collection = collect($items);
        $offset = ($page * $perPage) - $perPage;

        return new \Illuminate\Pagination\LengthAwarePaginator(
            $collection->slice($offset, $perPage)->values(),
            $collection->count(),
            $perPage,
            $page,
            ['path' => request()->url(), 'query' => request()->query()]
        );
    }

    /**
     * Static filter options.
     */
    private function getFilters()
    {
        return [
            'categories' => ['Storytelling', 'Art', 'Wellness', 'Drama'],
            'age_groups' => ['5-8', '8-12', '10-14', '12-16'],
            'locations'  => ['Nairobi', 'Mombasa', 'Kisumu', 'Eldoret'],
        ];
    }
}

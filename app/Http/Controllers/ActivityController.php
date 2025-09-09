<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Activity;
use App\Models\Booking;
use App\Events\DashboardUpdated;

class ActivityController extends Controller
{
    public function index(Request $request)
    {
        $user = auth()->user();

        // Check if user can list activities
        if (!$user->canListActivities()) {
            abort(403, 'You do not have permission to view activities.');
        }

        $query = Activity::with('provider')->upcoming();

        // Activity Providers can only see their own activities
        if ($user->isActivityProvider()) {
            $query->where('provider_id', $user->id);
        }

        $activities = $query->search($request->search)
            ->category($request->category)
            ->location($request->location)
            ->sortBy($request->sort, $request->direction)
            ->paginate(12);

        $categories = Activity::distinct('category')->pluck('category')->filter()->values();

        return view('activities.index', compact('activities', 'categories'));
    }

    public function show(Activity $activity)
    {
        $activity->load('provider');

        $user     = auth()->user();
        $booking  = $user ? Booking::where('user_id', $user->id)->where('activity_id', $activity->id)->first() : null;
        $hasBooked = $booking !== null;

        $similarActivities = Activity::with('provider')
            ->upcoming()
            ->where('category', $activity->category)
            ->where('id', '!=', $activity->id)
            ->limit(4)
            ->get();

        return view('activities.show', compact('activity', 'hasBooked', 'booking', 'similarActivities'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'        => 'required|string|max:255',
            'description' => 'nullable|string',
            'category'    => 'nullable|string|max:100',
            'location'    => 'nullable|string|max:255',
            'datetime'    => 'required|date',
            'price'       => 'nullable|numeric|min:0',
            'provider_id' => 'required|exists:providers,id',
        ]);

        $activity = Activity::create($validated)->load('provider');

        // Render fragment for Echo broadcast
        $html = view('partials.activities-services-providers', [
            'activities' => collect([$activity]),
            'services'   => collect(),
            'providers'  => collect(),
        ])->render();

        // Broadcast counts + HTML
        broadcast(new DashboardUpdated([
            'activitiesHtml' => $html,
            'counts' => [
                'activities' => Activity::count(),
            ]
        ]))->toOthers();

        return redirect()->route('activities.index')
            ->with('success', 'Activity created successfully and broadcasted!');
    }

    public function search(Request $request)
    {
        $query = $request->get('q', '');
        $activities = Activity::with('provider')
            ->upcoming()
            ->where('name', 'like', "%{$query}%")
            ->orWhere('description', 'like', "%{$query}%")
            ->orWhere('category', 'like', "%{$query}%")
            ->orWhere('location', 'like', "%{$query}%")
            ->paginate(12);

        $categories = Activity::distinct('category')->pluck('category')->filter()->values();

        return view('activities.index', compact('activities', 'categories'));
    }

    public function family(Request $request)
    {
        $activities = Activity::with('provider')
            ->upcoming()
            ->where('category', 'family')
            ->paginate(12);

        $categories = Activity::distinct('category')->pluck('category')->filter()->values();

        return view('activities.index', compact('activities', 'categories'));
    }

    public function outdoor(Request $request)
    {
        $activities = Activity::with('provider')
            ->upcoming()
            ->where('category', 'outdoor')
            ->paginate(12);

        $categories = Activity::distinct('category')->pluck('category')->filter()->values();

        return view('activities.index', compact('activities', 'categories'));
    }

    public function indoor(Request $request)
    {
        $activities = Activity::with('provider')
            ->upcoming()
            ->where('category', 'indoor')
            ->paginate(12);

        $categories = Activity::distinct('category')->pluck('category')->filter()->values();

        return view('activities.index', compact('activities', 'categories'));
    }
}

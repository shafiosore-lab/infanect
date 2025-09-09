<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Activity;

class ActivityController extends Controller
{
    /**
     * Display a listing of activities with optional filters.
     */
    public function index(Request $request)
    {
        $query = Activity::query();

        // Optional filters for future scalability
        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }

        if ($request->filled('country')) {
            $query->where('country', $request->country);
        }

        if ($request->filled('tenant_id')) {
            $query->where('tenant_id', $request->tenant_id);
        }

        // Paginate for scalable admin dashboards
        $activities = $query->latest()->paginate(15);

        // Pass to admin view
        return view('admin.activities.index', compact('activities'));
    }

    /**
     * Show the form for creating a new activity.
     */
    public function create()
    {
        return view('admin.activities.create');
    }

    /**
     * Store a newly created activity in storage.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'title'       => 'required|string|max:255',
            'category'    => 'required|string|max:255',
            'datetime'    => 'nullable|date',
            'price'       => 'nullable|numeric|min:0',
            'slots'       => 'nullable|integer|min:0',
            'venue'       => 'nullable|string|max:255',
            'country'     => 'nullable|string|max:100',
            'provider_id' => 'nullable|exists:service_providers,id',
            'tenant_id'   => 'nullable|exists:tenants,id',
            'meta'        => 'nullable|array',
        ]);

        $activity = Activity::create($data);

        return redirect()->route('activities.index')
                         ->with('success', 'Activity created successfully.');
    }

    /**
     * Display the specified activity.
     */
    public function show(Activity $activity)
    {
        return view('admin.activities.show', compact('activity'));
    }

    /**
     * Show the form for editing the specified activity.
     */
    public function edit(Activity $activity)
    {
        return view('admin.activities.edit', compact('activity'));
    }

    /**
     * Update the specified activity in storage.
     */
    public function update(Request $request, Activity $activity)
    {
        $data = $request->validate([
            'title'    => 'sometimes|required|string|max:255',
            'category' => 'sometimes|required|string|max:255',
            'datetime' => 'nullable|date',
            'price'    => 'nullable|numeric|min:0',
            'slots'    => 'nullable|integer|min:0',
            'venue'    => 'nullable|string|max:255',
            'country'  => 'nullable|string|max:100',
            'meta'     => 'nullable|array',
        ]);

        $activity->update($data);

        return redirect()->route('activities.index')
                         ->with('success', 'Activity updated successfully.');
    }

    /**
     * Remove the specified activity from storage.
     */
    public function destroy(Activity $activity)
    {
        $activity->delete(); // soft delete if model uses SoftDeletes
        return redirect()->route('activities.index')
                         ->with('success', 'Activity deleted successfully.');
    }
}

<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Activity;
use App\Models\Category;
use Illuminate\Support\Facades\Auth;

class ActivityController extends Controller
{
    /**
     * Display a listing of activities with role-based access control.
     * - Super admins and admins: See ALL activities with provider info
     * - Activity providers: See only their own activities
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        $query = Activity::with(['provider', 'providerProfile']);

        // Role-based filtering
        if ($user->isActivityProvider() || $user->hasRole('provider-bonding')) {
            // Activity providers can only see their own activities
            $query->where('provider_id', $user->id);
        }
        // Super admins and regular admins can see all activities (no additional filtering needed)

        // Apply filters
        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }

        if ($request->filled('status')) {
            if ($request->status === 'approved') {
                $query->where('is_approved', true);
            } elseif ($request->status === 'pending') {
                $query->where('is_approved', false);
            }
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('title', 'LIKE', "%{$search}%")
                  ->orWhere('description', 'LIKE', "%{$search}%")
                  ->orWhere('location', 'LIKE', "%{$search}%");
            });
        }

        // Sort options
        $sortBy = $request->get('sort', 'created_at');
        $sortDirection = $request->get('direction', 'desc');
        $query->orderBy($sortBy, $sortDirection);

        $activities = $query->paginate(15);

        // Get filter options
        $categories = Category::where('type', 'activity')->pluck('name', 'slug');

        // Determine user permissions
        $canCreate = $user->isSuperAdmin() || $user->isAdmin() || $user->isActivityProvider();
        $canEditAll = $user->isSuperAdmin() || $user->isAdmin();
        $canApprove = $user->isSuperAdmin() || $user->isAdmin();

        return view('admin.activities.index', compact(
            'activities',
            'categories',
            'canCreate',
            'canEditAll',
            'canApprove'
        ));
    }

    /**
     * Show the form for creating a new activity.
     */
    public function create()
    {
        $user = Auth::user();

        // Check permissions
        if (!$user->isSuperAdmin() && !$user->isAdmin() && !$user->isActivityProvider()) {
            abort(403, 'Unauthorized access');
        }

        $categories = Category::where('type', 'activity')->get();

        return view('admin.activities.create', compact('categories'));
    }

    /**
     * Store a newly created activity in storage.
     */
    public function store(Request $request)
    {
        $user = Auth::user();

        // Check permissions
        if (!$user->isSuperAdmin() && !$user->isAdmin() && !$user->isActivityProvider()) {
            abort(403, 'Unauthorized access');
        }

        $rules = [
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'category' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'currency' => 'required|string|size:3',
            'duration_minutes' => 'required|integer|min:1',
            'slots' => 'required|integer|min:1',
            'location' => 'required|string|max:255',
            'images' => 'nullable|array',
            'images.*' => 'url',
            'meta' => 'nullable|array',
        ];

        // For activity providers, automatically set provider_id and mark as pending approval
        if ($user->isActivityProvider()) {
            $rules['provider_id'] = 'prohibited'; // Cannot be set by provider
            $data = $request->validate($rules);
            $data['provider_id'] = $user->id;
            $data['is_approved'] = false; // Requires admin approval
        } else {
            // Admins can set provider and approval status
            $rules['provider_id'] = 'nullable|exists:users,id';
            $rules['is_approved'] = 'boolean';
            $data = $request->validate($rules);
            $data['is_approved'] = $request->boolean('is_approved', false);
        }

        $activity = Activity::create($data);

        $message = $user->isActivityProvider()
            ? 'Activity submitted successfully and is pending approval.'
            : 'Activity created successfully.';

        return redirect()->route('admin.activities.index')
                         ->with('success', $message);
    }

    /**
     * Display the specified activity.
     */
    public function show(Activity $activity)
    {
        $user = Auth::user();

        // Check if user can view this activity
        if (!$this->canAccessActivity($user, $activity)) {
            abort(403, 'Unauthorized access');
        }

        $activity->load(['provider', 'providerProfile', 'bookings']);

        return view('admin.activities.show', compact('activity'));
    }

    /**
     * Show the form for editing the specified activity.
     */
    public function edit(Activity $activity)
    {
        $user = Auth::user();

        // Check if user can edit this activity
        if (!$this->canModifyActivity($user, $activity)) {
            abort(403, 'Unauthorized access');
        }

        $categories = Category::where('type', 'activity')->get();

        return view('admin.activities.edit', compact('activity', 'categories'));
    }

    /**
     * Update the specified activity in storage.
     */
    public function update(Request $request, Activity $activity)
    {
        $user = Auth::user();

        // Check if user can modify this activity
        if (!$this->canModifyActivity($user, $activity)) {
            abort(403, 'Unauthorized access');
        }

        $rules = [
            'title' => 'sometimes|required|string|max:255',
            'description' => 'sometimes|required|string',
            'category' => 'sometimes|required|string|max:255',
            'price' => 'sometimes|required|numeric|min:0',
            'currency' => 'sometimes|required|string|size:3',
            'duration_minutes' => 'sometimes|required|integer|min:1',
            'slots' => 'sometimes|required|integer|min:1',
            'location' => 'sometimes|required|string|max:255',
            'images' => 'nullable|array',
            'images.*' => 'url',
            'meta' => 'nullable|array',
        ];

        // Only admins can approve/reject activities
        if ($user->isSuperAdmin() || $user->isAdmin()) {
            $rules['is_approved'] = 'boolean';
        }

        $data = $request->validate($rules);

        // Handle approval status
        if (isset($rules['is_approved'])) {
            $data['is_approved'] = $request->boolean('is_approved', $activity->is_approved);
        }

        $activity->update($data);

        return redirect()->route('admin.activities.index')
                         ->with('success', 'Activity updated successfully.');
    }

    /**
     * Approve or reject an activity.
     */
    public function approve(Activity $activity, Request $request)
    {
        $user = Auth::user();

        // Only admins can approve/reject
        if (!$user->isSuperAdmin() && !$user->isAdmin()) {
            abort(403, 'Unauthorized access');
        }

        $approved = $request->boolean('approved', false);
        $activity->update(['is_approved' => $approved]);

        $status = $approved ? 'approved' : 'rejected';

        return redirect()->back()
                         ->with('success', "Activity has been {$status}.");
    }

    /**
     * Suspend an activity.
     */
    public function suspend(Activity $activity, Request $request)
    {
        $user = Auth::user();

        // Only admins can suspend
        if (!$user->isSuperAdmin() && !$user->isAdmin()) {
            abort(403, 'Unauthorized access');
        }

        $suspend = $request->boolean('suspended', true);
        $activity->update([
            'is_approved' => !$suspend,
            'suspended_at' => $suspend ? now() : null
        ]);

        $status = $suspend ? 'suspended' : 'reactivated';

        return redirect()->back()
                         ->with('success', "Activity has been {$status}.");
    }

    /**
     * Bulk actions for activities.
     */
    public function bulkAction(Request $request)
    {
        $user = Auth::user();

        // Only admins can perform bulk actions
        if (!$user->isSuperAdmin() && !$user->isAdmin()) {
            abort(403, 'Unauthorized access');
        }

        $request->validate([
            'action' => 'required|in:approve,reject,suspend,delete',
            'activity_ids' => 'required|array',
            'activity_ids.*' => 'exists:activities,id'
        ]);

        $activities = Activity::whereIn('id', $request->activity_ids);
        $count = $activities->count();

        switch ($request->action) {
            case 'approve':
                $activities->update(['is_approved' => true, 'suspended_at' => null]);
                $message = "{$count} activities approved successfully.";
                break;
            case 'reject':
                $activities->update(['is_approved' => false]);
                $message = "{$count} activities rejected successfully.";
                break;
            case 'suspend':
                $activities->update(['is_approved' => false, 'suspended_at' => now()]);
                $message = "{$count} activities suspended successfully.";
                break;
            case 'delete':
                $activities->delete();
                $message = "{$count} activities deleted successfully.";
                break;
        }

        return redirect()->back()->with('success', $message);
    }

    /**
     * Remove the specified activity from storage.
     */
    public function destroy(Activity $activity)
    {
        $user = Auth::user();

        // Check if user can delete this activity
        if (!$this->canModifyActivity($user, $activity)) {
            abort(403, 'Unauthorized access');
        }

        $activity->delete(); // soft delete if model uses SoftDeletes

        return redirect()->route('admin.activities.index')
                         ->with('success', 'Activity deleted successfully.');
    }

    /**
     * Check if user can access/view an activity.
     */
    private function canAccessActivity($user, Activity $activity)
    {
        // Super admins and admins can see all activities
        if ($user->isSuperAdmin() || $user->isAdmin()) {
            return true;
        }

        // Activity providers can only see their own activities
        if ($user->isActivityProvider() && $activity->provider_id == $user->id) {
            return true;
        }

        return false;
    }

    /**
     * Check if user can modify an activity.
     */
    private function canModifyActivity($user, Activity $activity)
    {
        // Super admins and admins can modify all activities
        if ($user->isSuperAdmin() || $user->isAdmin()) {
            return true;
        }

        // Activity providers can only modify their own activities
        if ($user->isActivityProvider() && $activity->provider_id == $user->id) {
            return true;
        }

        return false;
    }
}

<?php

namespace App\Http\Controllers\Provider;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Activity;
use App\Models\Service;
use App\Models\Approval;
use App\Models\ServiceProvider;
use Illuminate\Support\Facades\Auth;

class ProviderController extends Controller
{
    public function dashboard()
    {
        $user = Auth::user();

        // Get provider associated with this user
        $provider = ServiceProvider::where('user_id', $user->id)->first();

        // Default stats for users without provider profile
        $stats = [
            'total_activities' => 0,
            'approved_activities' => 0,
            'active_activities' => 0,
            'pending_approvals' => 0,
            'total_bookings' => 0,
            'total_revenue' => 0,
            'total_employees' => 0,
        ];

        $recentActivities = collect();
        $pendingApprovals = collect();

        if ($provider) {
            // Get real engagement data if provider exists
            $totalBookings = \App\Models\Booking::whereHas('activity', function($query) use ($provider) {
                $query->where('provider_id', $provider->id);
            })->count();

            $totalRevenue = \App\Models\Booking::whereHas('activity', function($query) use ($provider) {
                $query->where('provider_id', $provider->id);
            })->where('status', 'completed')->sum('amount');

            $stats = [
                'total_activities' => Activity::where('provider_id', $provider->id)->count(),
                'approved_activities' => Activity::where('provider_id', $provider->id)->where('is_approved', true)->count(),
                'active_activities' => Activity::where('provider_id', $provider->id)->where('is_approved', true)->count(),
                'pending_approvals' => Approval::where('requestor_id', $user->id)->pending()->count(),
                'total_bookings' => $totalBookings,
                'total_revenue' => $totalRevenue,
                'total_employees' => \App\Models\User::where('department', 'like', '%'.$provider->name.'%')->count(),
            ];

            $recentActivities = Activity::where('provider_id', $provider->id)
                                       ->orderBy('created_at', 'desc')
                                       ->limit(5)
                                       ->get();

            $pendingApprovals = Approval::where('requestor_id', $user->id)
                                       ->pending()
                                       ->with('entity')
                                       ->limit(5)
                                       ->get();
        }

        return view('dashboards.provider', compact('stats', 'recentActivities', 'pendingApprovals', 'provider'));
    }

    public function register(Request $request)
    {
        if ($request->isMethod('post')) {
            $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|email|unique:service_providers',
                'phone' => 'required|string|max:20',
                'specialization' => 'required|string|max:255',
                'country' => 'required|string|max:100',
                'bio' => 'nullable|string|max:1000',
            ]);

            $user = Auth::user();

            // Check if user already has a provider
            $existingProvider = ServiceProvider::where('user_id', $user->id)->first();

            dd($existingProvider);
            if ($existingProvider) {
                return redirect()->route('provider.dashboard')->with('info', 'You already have a provider profile.');
            }

            // Create provider (will need approval)
            $provider = ServiceProvider::create([
                'name' => $request->name,
                'email' => $request->email,
                'phone' => $request->phone,
                'specialization' => $request->specialization,
                'country' => $request->country,
                'bio' => $request->bio,
                'user_id' => $user->id,
                'is_approved' => false, // Needs approval
            ]);

            // Create approval request
            Approval::create([
                'type' => 'provider_registration',
                'action' => 'create',
                'requestor_id' => $user->id,
                'entity_type' => 'App\Models\ServiceProvider',
                'entity_id' => $provider->id,
                'status' => 'pending',
                'request_data' => $request->all(),
            ]);

            return redirect()->route('provider.dashboard')->with('success', 'Provider registration submitted for approval.');
        }

        return view('provider.register');
    }

    // Activity Management
    public function activities()
    {
        $user = Auth::user();
        $provider = ServiceProvider::where('user_id', $user->id)->first();

        $activities = collect(); // Empty collection if no provider

        if ($provider) {
            $activities = Activity::where('provider_id', $provider->id)
                                 ->orderBy('created_at', 'desc')
                                 ->paginate(10);
        }

        return view('provider.activities.index', compact('activities', 'provider'));
    }

    public function createActivity(Request $request)
    {
        $user = Auth::user();
        $provider = ServiceProvider::where('user_id', $user->id)->first();

        if ($request->isMethod('post')) {
            // Handle different submit actions
            $action = $request->input('action', 'submit');

            // Check if provider exists before processing form
            if (!$provider) {
                return redirect()->route('provider.register')
                               ->with('error', 'Please register your provider profile first before creating activities.');
            }

            // Validate form data
            $request->validate([
                'title' => 'required|string|max:255',
                'category' => 'required|string|max:100',
                'description' => 'required|string|max:1000',
                'datetime' => 'required|date|after:now',
                'price' => 'required|numeric|min:0',
                'slots' => 'required|integer|min=1',
                'venue' => 'required|string|max:255',
                'country' => 'required|string|max:100',
                'duration' => 'nullable|string|max:100',
                'difficulty_level' => 'nullable|string|max:50',
                'target_audience' => 'nullable|string|max:255',
            ]);

            // Handle preview action
            if ($action === 'preview') {
                return view('provider.activities.preview', compact('request', 'provider'));
            }

            // Handle draft action (save without approval)
            if ($action === 'draft') {
                // Save as draft (you might want to create a draft status or separate table)
                return response()->json([
                    'success' => true,
                    'message' => 'Activity draft saved successfully!'
                ]);
            }

            // Handle submit action (create activity and send for approval)
            try {
                // Create activity (pending approval)
                $activity = Activity::create([
                    'title' => $request->title,
                    'category' => $request->category,
                    'description' => $request->description,
                    'datetime' => $request->datetime,
                    'price' => $request->price,
                    'slots' => $request->slots,
                    'venue' => $request->venue,
                    'country' => $request->country,
                    'duration' => $request->duration,
                    'difficulty_level' => $request->difficulty_level,
                    'target_audience' => $request->target_audience,
                    'provider_id' => $provider->id,
                    'is_approved' => false,
                ]);

                // Create approval request
                Approval::create([
                    'type' => 'activity',
                    'action' => 'create',
                    'requestor_id' => $user->id,
                    'entity_type' => 'App\Models\Activity',
                    'entity_id' => $activity->id,
                    'status' => 'pending',
                    'request_data' => $request->all(),
                ]);

                return redirect()->route('provider.activities')->with('success', 'Activity submitted for approval successfully!');

            } catch (\Exception $e) {
                return redirect()->back()
                               ->withInput()
                               ->with('error', 'Failed to create activity. Please try again.');
            }
        }

        return view('provider.activities.create', compact('provider'));
    }

    public function editActivity(Request $request, Activity $activity)
    {
        $user = Auth::user();
        $provider = ServiceProvider::where('user_id', $user->id)->first();

        // Ensure activity belongs to this provider
        if ($activity->provider_id !== $provider->id) {
            abort(403);
        }

        if ($request->isMethod('post')) {
            $request->validate([
                'title' => 'required|string|max:255',
                'category' => 'required|string|max:100',
                'description' => 'required|string|max:1000',
                'datetime' => 'required|date',
                'price' => 'required|numeric|min:0',
                'slots' => 'required|integer|min:1',
                'venue' => 'required|string|max:255',
                'country' => 'required|string|max:100',
                'duration' => 'nullable|string|max:100',
                'difficulty_level' => 'nullable|string|max:50',
                'target_audience' => 'nullable|string|max:255',
            ]);

            // Create approval request for update
            Approval::create([
                'type' => 'activity',
                'action' => 'update',
                'requestor_id' => $user->id,
                'entity_type' => 'App\Models\Activity',
                'entity_id' => $activity->id,
                'status' => 'pending',
                'request_data' => $activity->toArray(),
                'approved_data' => $request->all(),
            ]);

            return redirect()->route('provider.activities')->with('success', 'Activity update submitted for approval.');
        }

        return view('provider.activities.edit', compact('activity', 'provider'));
    }

    public function deleteActivity(Activity $activity)
    {
        $user = Auth::user();
        $provider = ServiceProvider::where('user_id', $user->id)->first();

        // Ensure activity belongs to this provider
        if ($activity->provider_id !== $provider->id) {
            abort(403);
        }

        // Create approval request for deletion
        Approval::create([
            'type' => 'activity',
            'action' => 'delete',
            'requestor_id' => $user->id,
            'entity_type' => 'App\Models\Activity',
            'entity_id' => $activity->id,
            'status' => 'pending',
            'request_data' => $activity->toArray(),
        ]);

        return redirect()->route('provider.activities')->with('success', 'Activity deletion submitted for approval.');
    }

    // Employee Management
    public function employees()
    {
        $user = Auth::user();
        $provider = ServiceProvider::where('user_id', $user->id)->first();

        $employees = collect(); // Empty collection if no provider

        if ($provider) {
            // Get employees under this provider (users with role 'employee' linked to this provider)
            $employees = \App\Models\User::where('department', 'like', '%'.$provider->name.'%')
                                        ->orWhere('id', $user->id)
                                        ->get();
        }

        return view('provider.employees.index', compact('employees', 'provider'));
    }

    public function createEmployee(Request $request)
    {
        $user = Auth::user();
        $provider = ServiceProvider::where('user_id', $user->id)->first();

        if ($request->isMethod('post')) {
            // Handle different submit actions
            $action = $request->input('action', 'submit');

            // Check if provider exists before processing form
            if (!$provider) {
                return redirect()->route('provider.register')
                               ->with('error', 'Please register your provider profile first before adding employees.');
            }

            // Validate form data
            $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|email|unique:users',
                'phone' => 'nullable|string|max:20',
            ]);

            // Handle preview action
            if ($action === 'preview') {
                return view('provider.employees.preview', compact('request', 'provider'));
            }

            // Handle draft action (save without approval)
            if ($action === 'draft') {
                // Save as draft (you might want to create a draft status or separate table)
                return response()->json([
                    'success' => true,
                    'message' => 'Employee draft saved successfully!'
                ]);
            }

            // Handle submit action (create employee and send for approval)
            try {
                $employeeRole = \App\Models\Role::where('slug', 'employee')->first() ?? \App\Models\Role::first();

                // Create employee user
                $employee = \App\Models\User::create([
                    'name' => $request->name,
                    'email' => $request->email,
                    'phone' => $request->phone,
                    'password' => bcrypt('password123'), // Default password
                    'role_id' => $employeeRole->id,
                    'department' => $provider->name . ' Employee',
                    'is_active' => false, // Needs approval
                ]);

                // Create approval request
                Approval::create([
                    'type' => 'employee_registration',
                    'action' => 'create',
                    'requestor_id' => $user->id,
                    'entity_type' => 'App\Models\User',
                    'entity_id' => $employee->id,
                    'status' => 'pending',
                    'request_data' => $request->all(),
                ]);

                return redirect()->route('provider.employees')->with('success', 'Employee registration submitted for approval successfully!');

            } catch (\Exception $e) {
                return redirect()->back()
                               ->withInput()
                               ->with('error', 'Failed to create employee. Please try again.');
            }
        }

        return view('provider.employees.create', compact('provider'));
    }
}

<?php

namespace App\Http\Controllers\Provider;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Service;
use Illuminate\Support\Facades\Auth;

class ServiceController extends Controller
{
    /**
     * Apply middleware to controller
     */
    public function __construct()
    {
        $this->middleware('auth'); // Only authenticated users
        $this->middleware('role:provider'); // Only provider users
    }

    /**
     * Display a listing of services.
     */
    public function index()
    {
        $services = Service::where('user_id', Auth::id())
                           ->latest()
                           ->paginate(10);

        return view('provider.services.index', compact('services'));
    }

    /**
     * Show the form for creating a new service.
     */
    public function create()
    {
        return view('provider.services.create');
    }

    /**
     * Store a newly created service.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'        => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        $validated['user_id'] = Auth::id();
        $validated['is_active'] = true;

        Service::create($validated);

        return redirect()->route('provider.services.index')
                         ->with('success', 'Service created successfully.');
    }

    /**
     * Show the form for editing a service.
     */
    public function edit(Service $service)
    {
        $this->authorizeOwner($service);

        return view('provider.services.edit', compact('service'));
    }

    /**
     * Update a service.
     */
    public function update(Request $request, Service $service)
    {
        $this->authorizeOwner($service);

        $validated = $request->validate([
            'name'        => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        $service->update($validated);

        return redirect()->route('provider.services.index')
                         ->with('success', 'Service updated successfully.');
    }

    /**
     * Delete a service.
     */
    public function destroy(Service $service)
    {
        $this->authorizeOwner($service);

        $service->delete();

        return redirect()->route('provider.services.index')
                         ->with('success', 'Service deleted successfully.');
    }

    /**
     * Ensure the authenticated provider owns the service.
     */
    private function authorizeOwner(Service $service)
    {
        if ($service->user_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }
    }
}

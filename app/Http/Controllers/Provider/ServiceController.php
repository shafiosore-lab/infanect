<?php

namespace App\Http\Controllers\Provider;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

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
        return view('provider.services.index');
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
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        // Store logic here (e.g., Service::create([...]))
        return redirect()->route('provider.services.index')
                         ->with('success', 'Service created successfully.');
    }

    /**
     * Show the form for editing a service.
     */
    public function edit($id)
    {
        return view('provider.services.edit', compact('id'));
    }

    /**
     * Update a service.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        // Update logic here
        return redirect()->route('provider.services.index')
                         ->with('success', 'Service updated successfully.');
    }

    /**
     * Delete a service.
     */
    public function destroy($id)
    {
        // Delete logic here
        return redirect()->route('provider.services.index')
                         ->with('success', 'Service deleted successfully.');
    }
}

<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ServiceProvider;


class ServiceProviderController extends Controller
{
    /**
     * Display a listing of providers.
     */
    public function index()
    {
        $providers = ServiceProvider::latest()->paginate(10);
        return view('admin.providers.index', compact('providers'));
    }

    /**
     * Show the form for creating a new provider.
     */
    public function create()
    {
        return view('admin.providers.create');
    }

    /**
     * Store a newly created provider in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'  => 'required|string|max:255',
            'email' => 'required|email|unique:service_providers,email',
            'phone' => 'nullable|string|max:50',
            'bio'   => 'nullable|string',
            'specialization' => 'nullable|string',
            'availability'   => 'nullable|array',
            'rating'         => 'nullable|numeric|min:0|max:5',
        ]);

        ServiceProvider::create($validated);

        return redirect()->route('admin.providers.index')
                         ->with('success', 'Provider created successfully.');
    }

    /**
     * Display the specified provider.
     */
    public function show(ServiceProvider $provider)
    {
        return view('admin.providers.show', compact('provider'));
    }

    /**
     * Show the form for editing the specified provider.
     */
    public function edit(ServiceProvider $provider)
    {
        return view('admin.providers.edit', compact('provider'));
    }

    /**
     * Update the specified provider in storage.
     */
    public function update(Request $request, ServiceProvider $provider)
    {
        $validated = $request->validate([
            'name'  => 'required|string|max:255',
            'email' => 'required|email|unique:service_providers,email,' . $provider->id,
            'phone' => 'nullable|string|max:50',
            'bio'   => 'nullable|string',
            'specialization' => 'nullable|string',
            'availability'   => 'nullable|array',
            'rating'         => 'nullable|numeric|min:0|max:5',
        ]);

        $provider->update($validated);

        return redirect()->route('admin.providers.index')
                         ->with('success', 'Provider updated successfully.');
    }

    /**
     * Remove the specified provider from storage.
     */
    public function destroy(ServiceProvider $provider)
    {
        $provider->delete();

        return redirect()->route('admin.providers.index')
                         ->with('success', 'Provider deleted successfully.');
    }
}


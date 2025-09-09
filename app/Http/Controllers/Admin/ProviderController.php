<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Provider;
use Illuminate\Support\Facades\Storage;

class ProviderController extends Controller
{
    /**
     * Display a listing of the resource.
     * Future: add search, filters, pagination.
     */
    public function index(Request $request)
    {
        $query = Provider::query();

        // Anticipation: search by name, service, or location
        if ($search = $request->input('search')) {
            $query->where('name', 'like', "%{$search}%")
                  ->orWhere('service_type', 'like', "%{$search}%")
                  ->orWhere('location', 'like', "%{$search}%");
        }

        // Anticipation: filter by availability
        if ($request->has('available')) {
            $query->where('is_available', $request->input('available'));
        }

        $providers = $query->latest()->paginate(10);

        return view('admin.providers.index', compact('providers'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.providers.create');
    }

    /**
     * Store a newly created resource in storage.
     * Anticipation: handle logo/profile image uploads.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'          => 'required|string|max:255',
            'service_type'  => 'required|string|max:255',
            'email'         => 'nullable|email|unique:providers,email',
            'phone'         => 'nullable|string|max:20',
            'location'      => 'nullable|string|max:255',
            'logo'          => 'nullable|image|max:2048',
        ]);

        if ($request->hasFile('logo')) {
            $validated['logo'] = $request->file('logo')->store('providers/logos', 'public');
        }

        $validated['is_available'] = true; // default availability

        Provider::create($validated);

        return redirect()->route('admin.providers.index')
                         ->with('success', 'Provider created successfully.');
    }

    /**
     * Display the specified resource.
     * Future: show insights like bookings, revenue, ratings.
     */
    public function show(string $id)
    {
        $provider = Provider::findOrFail($id);

        // Anticipation: load related data like bookings & reviews
        $bookings = $provider->bookings()->latest()->limit(10)->get();
        $reviews  = $provider->reviews()->latest()->limit(5)->get();

        return view('admin.providers.show', compact('provider', 'bookings', 'reviews'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $provider = Provider::findOrFail($id);
        return view('admin.providers.edit', compact('provider'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $provider = Provider::findOrFail($id);

        $validated = $request->validate([
            'name'          => 'required|string|max:255',
            'service_type'  => 'required|string|max:255',
            'email'         => 'nullable|email|unique:providers,email,' . $provider->id,
            'phone'         => 'nullable|string|max:20',
            'location'      => 'nullable|string|max:255',
            'logo'          => 'nullable|image|max:2048',
            'is_available'  => 'boolean',
        ]);

        if ($request->hasFile('logo')) {
            // Delete old file if exists
            if ($provider->logo && Storage::disk('public')->exists($provider->logo)) {
                Storage::disk('public')->delete($provider->logo);
            }
            $validated['logo'] = $request->file('logo')->store('providers/logos', 'public');
        }

        $provider->update($validated);

        return redirect()->route('admin.providers.index')
                         ->with('success', 'Provider updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     * Anticipation: soft delete to allow recovery.
     */
    public function destroy(string $id)
    {
        $provider = Provider::findOrFail($id);
        $provider->delete();

        return redirect()->route('admin.providers.index')
                         ->with('success', 'Provider deleted successfully.');
    }
}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Provider;
use App\Models\Review;
use App\Events\NewContentCreated;

class ProviderController extends Controller
{
    /**
     * Display a paginated list of providers with search, filter, and sorting.
     */
    public function index(Request $request)
    {
        $query = Provider::query();

        // Search functionality
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'LIKE', "%{$search}%")
                  ->orWhere('service_type', 'LIKE', "%{$search}%")
                  ->orWhere('city', 'LIKE', "%{$search}%");
            });
        }

        // Filter by service type
        if ($request->filled('service_type')) {
            $query->where('service_type', $request->service_type);
        }

        // Filter by location
        if ($request->filled('location')) {
            $query->where(function($q) use ($request) {
                $q->where('city', 'LIKE', "%{$request->location}%")
                  ->orWhere('state', 'LIKE', "%{$request->location}%")
                  ->orWhere('address', 'LIKE', "%{$request->location}%");
            });
        }

        // Filter by availability
        if ($request->get('available') == '1') {
            $query->where('is_available', true);
        }

        // Sort options
        $sortBy = $request->get('sort', 'name');
        $sortDirection = $request->get('direction', 'asc');

        if ($sortBy === 'rating') {
            $query->orderBy('avg_rating', $sortDirection);
        } elseif ($sortBy === 'reviews') {
            $query->orderBy('total_reviews', $sortDirection);
        } else {
            $query->orderBy('name', $sortDirection);
        }

        $providers = $query->paginate(12);

        // Unique service types for filters
        $serviceTypes = Provider::distinct('service_type')->pluck('service_type')->filter()->values();

        return view('providers.index', compact('providers', 'serviceTypes'));
    }

    /**
     * Display a single provider with recent reviews and completed bookings.
     */
    public function show(Provider $provider)
    {
        $provider->load([
            'reviews' => function($query) {
                $query->latest()->limit(10);
            },
            'bookings' => function($query) {
                $query->where('status', 'completed')->latest()->limit(5);
            }
        ]);

        // Average rating
        $avgRating = $provider->reviews->avg('rating') ?? $provider->avg_rating;

        // Recent reviews
        $recentReviews = $provider->reviews->take(5);

        return view('providers.show', compact('provider', 'avgRating', 'recentReviews'));
    }

    /**
     * Store a newly created provider and broadcast it live.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'  => 'required|string|max:255',
            'bio'   => 'nullable|string',
            'email' => 'nullable|email',
            'phone' => 'nullable|string|max:20',
        ]);

        $provider = Provider::create($validated);

        // Render only the new provider card
        $html = view('partials.activities-services-providers', [
            'activities' => collect(),
            'services'   => collect(),
            'providers'  => collect([$provider]),
        ])->render();

        // Broadcast via Laravel Echo
        broadcast(new NewContentCreated($html))->toOthers();

        return redirect()->route('providers.index')
                         ->with('success', 'Provider created successfully and broadcasted!');
    }

    /**
     * Display featured providers.
     */
    public function featured(Request $request)
    {
        $providers = Provider::where('is_featured', true)
                             ->orderBy('avg_rating', 'desc')
                             ->paginate(12);

        return view('providers.featured', compact('providers'));
    }

    /**
     * Display top-rated providers.
     */
    public function topRated(Request $request)
    {
        $providers = Provider::orderBy('avg_rating', 'desc')
                             ->orderBy('total_reviews', 'desc')
                             ->paginate(12);

        return view('providers.top-rated', compact('providers'));
    }
}

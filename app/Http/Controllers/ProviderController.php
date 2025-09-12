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
        try {
            $query = Provider::query();

            // Filter: Location
            if ($request->filled('location') && $request->location !== 'All') {
                $query->where('location', $request->location);
            }

            // Filter: Service
            if ($request->filled('service') && $request->service !== 'All') {
                $query->whereJsonContains('services', $request->service);
            }

            // Filter: Rating
            if ($request->filled('rating') && $request->rating !== 'Any') {
                $query->where('rating', '>=', $request->rating);
            }

            $providers = $query->paginate(10)->appends($request->all());

            return view('providers.index', compact('providers'));
        } catch (\Exception $e) {
            // Create empty paginated collection as fallback
            $providers = new \Illuminate\Pagination\LengthAwarePaginator(
                collect([]),
                0,
                10,
                1,
                ['path' => request()->url(), 'pageName' => 'page']
            );

            return view('providers.index', compact('providers'));
        }
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

    /**
     * Show the onboarding form for providers.
     */
    public function showOnboarding(Request $request)
    {
        return view('providers.onboarding');
    }

    /**
     * Handle the onboarding form submission.
     */
    public function storeOnboarding(Request $request)
    {
        $data = $request->validate([
            'business_name' => 'nullable|string|max:255',
            'category' => 'nullable|string|max:100',
            'country' => 'nullable|string|max:100',
            'city' => 'nullable|string|max:100',
            'timezone' => 'nullable|string|max:100',
            'language' => 'nullable|string|max:10',
        ]);

        // Create or update provider profile linked to user
        $provider = Provider::updateOrCreate(
            ['user_id' => $request->user()->id],
            array_merge($data, ['status' => 'pending'])
        );

        return redirect()->route('provider.documents')->with('status', 'Profile saved. Please upload required documents.');
    }

    /**
     * Show the documents upload form for providers.
     */
    public function documents(Request $request)
    {
        return view('providers.documents');
    }

    /**
     * Handle the documents upload.
     */
    public function uploadDocuments(Request $request)
    {
        $request->validate([
            'business_license' => 'nullable|file|max:2048',
            'id_document' => 'nullable|file|max:2048',
        ]);

        $provider = Provider::firstOrCreate(['user_id' => $request->user()->id]);

        if ($request->hasFile('business_license')) {
            $path = $request->file('business_license')->store('provider_documents');
            $provider->business_license_path = $path;
        }

        if ($request->hasFile('id_document')) {
            $path = $request->file('id_document')->store('provider_documents');
            $provider->id_document_path = $path;
        }

        $provider->status = 'review';
        $provider->save();

        return redirect()->route('provider.documents')->with('status', 'Documents uploaded. Awaiting review.');
    }
}

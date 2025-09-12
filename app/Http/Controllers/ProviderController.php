<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use App\Models\Provider;
use App\Models\Role;
use App\Jobs\ProcessKYCDocuments;
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
     * Register a new provider.
     */
    public function register()
    {
        $providerTypes = config('provider.types', []);
        $kycDocuments = config('provider.kyc_documents', []);

        return view('auth.provider-register', compact('providerTypes', 'kycDocuments'));
    }

    /**
     * Store a newly created provider and broadcast it live.
     */
    public function store(Request $request)
    {
        // Dynamic validation based on provider type
        $providerType = $request->input('provider_type');
        $typeConfig = collect(config('provider.types'))->firstWhere('slug', $providerType);

        if (!$typeConfig) {
            return back()->withErrors(['provider_type' => 'Invalid provider type selected.']);
        }

        // Base validation rules
        $rules = [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'phone' => 'nullable|string|max:20',
            'provider_type' => 'required|string',
            'password' => 'required|string|min:8|confirmed',
            'business_name' => 'nullable|string|max:255',
            'business_description' => 'nullable|string|max:1000',
            'website' => 'nullable|url|max:255',
            'address' => 'nullable|string|max:500',
            'city' => 'nullable|string|max:100',
            'state' => 'nullable|string|max:100',
            'country' => 'nullable|string|max:100',
            'postal_code' => 'nullable|string|max:20',
            'specializations' => 'nullable|array',
            'years_experience' => 'nullable|integer|min:0|max:50',
            'hourly_rate' => 'nullable|numeric|min:0',
            'availability' => 'nullable|array'
        ];

        // Add dynamic document validation based on provider type
        $requiredDocs = $typeConfig['required_documents'] ?? [];
        foreach ($requiredDocs as $docKey) {
            $rules[$docKey] = 'required|file|mimes:pdf,jpg,jpeg,png|max:5120'; // 5MB max
        }

        // Optional documents
        $kycDocuments = config('provider.kyc_documents', []);
        foreach ($kycDocuments as $docKey => $docConfig) {
            if (!in_array($docKey, $requiredDocs)) {
                $rules[$docKey] = 'nullable|file|mimes:pdf,jpg,jpeg,png,doc,docx|max:10240'; // 10MB max
            }
        }

        $validated = $request->validate($rules);

        DB::beginTransaction();

        try {
            // Create user account
            $user = User::create([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'phone' => $validated['phone'] ?? null,
                'password' => Hash::make($validated['password']),
                'role_id' => $providerType, // Store provider type as role
                'provider_data' => json_encode([
                    'provider_type' => $providerType,
                    'registration_stage' => 'kyc_pending'
                ])
            ]);

            // Create provider profile
            $providerData = [
                'user_id' => $user->id,
                'provider_type' => $providerType,
                'business_name' => $validated['business_name'] ?? $validated['name'],
                'business_description' => $validated['business_description'] ?? null,
                'website' => $validated['website'] ?? null,
                'address' => $validated['address'] ?? null,
                'city' => $validated['city'] ?? null,
                'state' => $validated['state'] ?? null,
                'country' => $validated['country'] ?? null,
                'postal_code' => $validated['postal_code'] ?? null,
                'specializations' => json_encode($validated['specializations'] ?? []),
                'years_experience' => $validated['years_experience'] ?? null,
                'hourly_rate' => $validated['hourly_rate'] ?? null,
                'availability' => json_encode($validated['availability'] ?? []),
                'status' => 'pending',
                'kyc_status' => 'pending',
                'created_at' => now(),
                'updated_at' => now()
            ];

            $provider = Provider::create($providerData);

            // Handle file uploads
            $uploadedDocuments = [];
            foreach ($kycDocuments as $docKey => $docConfig) {
                if ($request->hasFile($docKey)) {
                    $file = $request->file($docKey);
                    $filename = $docKey . '_' . $user->id . '_' . time() . '.' . $file->getClientOriginalExtension();
                    $path = $file->storeAs('kyc-documents/' . $user->id, $filename, 'private');

                    $uploadedDocuments[$docKey] = [
                        'original_name' => $file->getClientOriginalName(),
                        'stored_path' => $path,
                        'file_size' => $file->getSize(),
                        'mime_type' => $file->getMimeType(),
                        'uploaded_at' => now()
                    ];
                }
            }

            // Update provider with document info
            $provider->update([
                'kyc_documents' => json_encode($uploadedDocuments)
            ]);

            // Dispatch KYC processing job
            ProcessKYCDocuments::dispatch($provider->id);

            DB::commit();

            return redirect()
                ->route('login')
                ->with('success', 'Provider registration submitted successfully! Please check your email for verification instructions. Your account will be reviewed within 24-48 hours.');

        } catch (\Exception $e) {
            DB::rollback();

            // Clean up any uploaded files
            foreach ($uploadedDocuments ?? [] as $doc) {
                if (Storage::disk('private')->exists($doc['stored_path'])) {
                    Storage::disk('private')->delete($doc['stored_path']);
                }
            }

            return back()
                ->withInput()
                ->withErrors(['error' => 'Registration failed. Please try again.']);
        }
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

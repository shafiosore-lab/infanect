<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
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

            // Search by name/business
            if ($request->filled('q')) {
                $q = $request->q;
                $query->where(function ($sub) use ($q) {
                    $sub->where('business_name', 'like', "%{$q}%")
                        ->orWhereHas('user', function ($userQuery) use ($q) {
                            $userQuery->where('name', 'like', "%{$q}%")
                                      ->orWhere('email', 'like', "%{$q}%");
                        });
                });
            }

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
            Log::error('Provider index failed', ['error' => $e->getMessage()]);

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
            'reviews' => fn ($q) => $q->latest()->limit(10),
            'bookings' => fn ($q) => $q->where('status', 'completed')->latest()->limit(5)
        ]);

        $avgRating = $provider->reviews->avg('rating') ?? $provider->avg_rating;
        $recentReviews = $provider->reviews->take(5);

        return view('providers.show', compact('provider', 'avgRating', 'recentReviews'));
    }

    /**
     * Show provider registration form.
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
        $providerType = $request->input('provider_type');
        $typeConfig = collect(config('provider.types'))->firstWhere('slug', $providerType);

        if (!$typeConfig) {
            return back()->withErrors(['provider_type' => 'Invalid provider type selected.']);
        }

        // Validation rules
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

        // Required KYC docs
        $requiredDocs = $typeConfig['required_documents'] ?? [];
        foreach ($requiredDocs as $docKey) {
            $rules[$docKey] = 'required|file|mimes:pdf,jpg,jpeg,png|max:5120';
        }

        // Optional docs
        $kycDocuments = config('provider.kyc_documents', []);
        foreach ($kycDocuments as $docKey => $docConfig) {
            if (!in_array($docKey, $requiredDocs)) {
                $rules[$docKey] = 'nullable|file|mimes:pdf,jpg,jpeg,png,doc,docx|max:10240';
            }
        }

        $validated = $request->validate($rules);

        DB::beginTransaction();
        $user = null;
        $uploadedDocuments = [];

        try {
            // Resolve role
            $roleId = Role::where('slug', $providerType)->value('id') ?? Role::PROVIDER;

            // Create user
            $user = User::create([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'phone' => $validated['phone'] ?? null,
                'password' => Hash::make($validated['password']),
                'role_id' => $roleId,
                'provider_data' => json_encode([
                    'provider_type' => $providerType,
                    'registration_stage' => 'kyc_pending'
                ])
            ]);

            // Create provider profile
            $provider = Provider::create([
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
                'kyc_status' => 'pending'
            ]);

            // Handle KYC uploads (private disk)
            foreach ($kycDocuments as $docKey => $docConfig) {
                if ($request->hasFile($docKey)) {
                    $file = $request->file($docKey);
                    $filename = "{$docKey}_{$user->id}_" . time() . '.' . $file->getClientOriginalExtension();
                    $path = $file->storeAs("kyc-documents/{$user->id}", $filename, 'private');

                    $uploadedDocuments[$docKey] = [
                        'original_name' => $file->getClientOriginalName(),
                        'stored_path' => $path,
                        'file_size' => $file->getSize(),
                        'mime_type' => $file->getMimeType(),
                        'uploaded_at' => now()
                    ];
                }
            }

            $provider->update(['kyc_documents' => json_encode($uploadedDocuments)]);

            // Dispatch KYC processing job
            ProcessKYCDocuments::dispatch($provider->id);

            DB::commit();

            // Broadcast event
            event(new NewContentCreated($provider));

            return redirect()
                ->route('login')
                ->with('success', 'Provider registration submitted successfully! Please check your email for verification instructions. Your account will be reviewed within 24-48 hours.');

        } catch (\Exception $e) {
            DB::rollBack();

            // Cleanup uploaded docs
            foreach ($uploadedDocuments as $doc) {
                if (Storage::disk('private')->exists($doc['stored_path'])) {
                    Storage::disk('private')->delete($doc['stored_path']);
                }
            }

            // Cleanup user if created
            if ($user) {
                $user->delete();
            }

            Log::error('Provider registration failed', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return back()->withInput()->withErrors(['error' => 'Registration failed. Please try again.']);
        }
    }

    /**
     * Featured providers.
     */
    public function featured()
    {
        $providers = Provider::where('is_featured', true)
            ->orderBy('avg_rating', 'desc')
            ->paginate(12);

        return view('providers.featured', compact('providers'));
    }

    /**
     * Top-rated providers.
     */
    public function topRated()
    {
        $providers = Provider::orderBy('avg_rating', 'desc')
            ->orderBy('total_reviews', 'desc')
            ->paginate(12);

        return view('providers.top-rated', compact('providers'));
    }

    /**
     * Show provider onboarding form.
     */
    public function showOnboarding()
    {
        return view('providers.onboarding');
    }

    /**
     * Store onboarding data.
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

        $provider = Provider::updateOrCreate(
            ['user_id' => $request->user()->id],
            array_merge($data, ['status' => 'pending'])
        );

        return redirect()->route('provider.documents')
            ->with('status', 'Profile saved. Please upload required documents.');
    }

    /**
     * Show documents upload form.
     */
    public function documents()
    {
        return view('providers.documents');
    }

    /**
     * Handle documents upload.
     */
    public function uploadDocuments(Request $request)
    {
        $request->validate([
            'business_license' => 'nullable|file|max:2048',
            'id_document' => 'nullable|file|max:2048',
        ]);

        $provider = Provider::firstOrCreate(['user_id' => $request->user()->id]);

        if ($request->hasFile('business_license')) {
            $path = $request->file('business_license')->store("provider_documents/{$request->user()->id}", 'private');
            $provider->business_license_path = $path;
        }

        if ($request->hasFile('id_document')) {
            $path = $request->file('id_document')->store("provider_documents/{$request->user()->id}", 'private');
            $provider->id_document_path = $path;
        }

        $provider->status = 'review';
        $provider->save();

        return redirect()->route('provider.documents')
            ->with('status', 'Documents uploaded. Awaiting review.');
    }
}

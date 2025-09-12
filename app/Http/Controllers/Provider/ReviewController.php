<?php

namespace App\Http\Controllers\Provider;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class ReviewController extends Controller
{
    public function index(Request $request)
    {
        $provider = Auth::user();
        $providerId = $provider->id ?? null;

        if (!$providerId) {
            return redirect()->route('login');
        }

        if (!Schema::hasTable('provider_profiles')) {
            // Table missing — return empty paginated collection to avoid fatal error
            $reviews = collect([])->paginate(20);
            return view('provider.reviews.index', compact('reviews'));
        }

        // Simple query: reviews for services owned by this provider
        $reviews = DB::table('service_reviews')
            ->join('professional_services', 'service_reviews.professional_service_id', '=', 'professional_services.id')
            ->where('professional_services.provider_profile_id', $provider->provider_profile_id ?? null)
            ->select('service_reviews.*', 'professional_services.name as service_name')
            ->orderBy('service_reviews.created_at', 'desc')
            ->paginate(20);

        return view('provider.reviews.index', compact('reviews'));
    }
}

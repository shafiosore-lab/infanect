<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use App\Models\User;
use App\Models\Provider;
use App\Services\ProfessionalDashboardService;

class ProfessionalProviderDashboardController extends Controller
{
    protected $dashboardService;

    public function __construct(ProfessionalDashboardService $dashboardService)
    {
        $this->middleware(['auth']);
        $this->dashboardService = $dashboardService;
    }

    public function index(Request $request)
    {
        $user = $request->user();

        // Check if user is a professional provider
        if (!$this->isAuthorizedProvider($user)) {
            return redirect()->route('dashboard')
                ->with('info', 'Redirecting to your appropriate dashboard.');
        }

        // Get provider profile with caching
        $provider = $this->getProviderProfile($user);

        if (!$provider) {
            return redirect()->route('provider.setup')
                ->with('message', 'Please complete your provider profile first.');
        }

        // Get all dashboard data through service
        $dashboardData = $this->dashboardService->getDashboardData($user, $provider);

        return view('dashboards.professional-provider', [
            'user' => $user,
            'provider' => $provider,
            'dashboard' => $dashboardData
        ]);
    }

    private function isAuthorizedProvider(User $user): bool
    {
        $allowedRoles = [
            'provider',
            'admin',
            'super-admin',
        ];

        $allowedProviderTypes = [
            'provider-professional',
            'provider-bonding' // Allow for testing
        ];

        return in_array($user->role, $allowedRoles) ||
               in_array($user->provider_type, $allowedProviderTypes);
    }

    private function getProviderProfile(User $user)
    {
        return Cache::remember("provider_profile_{$user->id}", 600, function () use ($user) {
            return Provider::with(['user'])
                ->where('user_id', $user->id)
                ->active()
                ->first();
        });
    }

    public function metrics(Request $request)
    {
        $user = $request->user();

        // Check if user is a professional provider
        if (!$this->isAuthorizedProvider($user)) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        // Get provider profile
        $provider = $this->getProviderProfile($user);

        if (!$provider) {
            return response()->json(['error' => 'Provider profile not found'], 404);
        }

        // Get revenue analytics for chart data
        $revenueData = $this->dashboardService->getRevenueAnalytics($user);

        return response()->json([
            'labels' => $revenueData['months'],
            'bookings' => $revenueData['revenues'], // Using revenue as bookings data for now
            'revenue' => $revenueData['revenues']
        ]);
    }
}

<?php

namespace App\Http\Controllers\Provider;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Service;
use App\Models\Booking;
use App\Models\Provider;
use App\Models\Review;
use App\Models\Transaction;
use App\Models\ServiceProvider;
use App\Models\Activity;
use App\Models\Approval;

class ProviderDashboardController extends Controller
{
    /**
     * Redirect to appropriate provider dashboard
     */
    public function index()
    {
        $user = auth()->user();

        if ($user->isServiceProvider()) {
            return $this->serviceProviderDashboard();
        }

        if ($user->isActivityProvider()) {
            return $this->activityProviderDashboard();
        }

        return redirect()->route('dashboard')->with('error', 'Unauthorized access to provider dashboard.');
    }

    // ----------------- SERVICE PROVIDER DASHBOARD -----------------
    private function serviceProviderDashboard()
    {
        $user = auth()->user();
        $provider = ServiceProvider::where('user_id', $user->id)->first();

        if (!$provider) {
            return redirect()->route('provider.register');
        }

        $stats = [
            'total_services'    => Service::where('user_id', $user->id)->count(),
            'active_services'   => Service::where('user_id', $user->id)->where('is_active', true)->count(),
            'pending_approvals' => Approval::where('requestor_id', $user->id)->where('status', 'pending')->count(),
            'total_bookings'    => Service::where('user_id', $user->id)->withCount('bookings')->get()->sum('bookings_count'),
            'total_revenue'     => Booking::whereHas('service', fn($q) => $q->where('user_id', $user->id))
                                          ->where('status', 'completed')
                                          ->sum('amount_paid'),
            'approved_services' => Service::where('user_id', $user->id)->where('is_active', true)->count(),
        ];

        $recentServices = Service::where('user_id', $user->id)
                                 ->orderBy('created_at', 'desc')
                                 ->limit(5)
                                 ->get();

        $pendingApprovals = Approval::where('requestor_id', $user->id)
                                    ->where('status', 'pending')
                                    ->with('entity')
                                    ->limit(5)
                                    ->get();

        return view('dashboards.provider-service', compact('stats', 'recentServices', 'pendingApprovals', 'provider'));
    }

    // ----------------- ACTIVITY PROVIDER DASHBOARD -----------------
    private function activityProviderDashboard()
    {
        $user = auth()->user();
        $provider = ServiceProvider::where('user_id', $user->id)->first();

        if (!$provider) {
            return redirect()->route('provider.register');
        }

        $stats = [
            'total_activities'    => Activity::where('provider_id', $provider->id)->count(),
            'active_activities'   => Activity::where('provider_id', $provider->id)->where('is_approved', true)->count(),
            'pending_approvals'   => Approval::where('requestor_id', $user->id)->where('status', 'pending')->count(),
            'total_bookings'      => Activity::where('provider_id', $provider->id)->withCount('bookings')->get()->sum('bookings_count'),
            'total_revenue'       => Booking::whereHas('activity', fn($q) => $q->where('provider_id', $provider->id))
                                            ->where('status', 'completed')
                                            ->sum('amount_paid'),
            'approved_activities' => Activity::where('provider_id', $provider->id)->where('is_approved', true)->count(),
        ];

        $recentActivities = Activity::where('provider_id', $provider->id)
                                    ->orderBy('created_at', 'desc')
                                    ->limit(5)
                                    ->get();

        $pendingApprovals = Approval::where('requestor_id', $user->id)
                                    ->where('status', 'pending')
                                    ->with('entity')
                                    ->limit(5)
                                    ->get();

        return view('dashboards.provider-activity', compact('stats', 'recentActivities', 'pendingApprovals', 'provider'));
    }
}

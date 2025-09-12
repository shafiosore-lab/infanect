<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
use App\Models\User;
use App\Models\ProviderProfile;
use App\Models\Booking;
use App\Models\Payment;

class DashboardStatsController extends Controller
{
    /**
     * Return system-wide stats for Super Admin dashboard.
     */
    public function superAdmin()
    {
        $stats = [
            'users'     => 0,
            'providers' => 0,
            'bookings'  => 0,
            'revenue'   => 0.0,
        ];

        try {
            if (Schema::hasTable('users')) {
                $stats['users'] = User::count();
            }

            if (Schema::hasTable('provider_profiles')) {
                $stats['providers'] = ProviderProfile::count();
            }

            if (Schema::hasTable('bookings')) {
                $stats['bookings'] = Booking::count();
            }

            if (Schema::hasTable('payments')) {
                $stats['revenue'] = (float) Payment::sum('amount');
            }
        } catch (\Throwable $e) {
            // Log errors silently, avoid breaking the dashboard
            report($e);
        }

        return response()->json($stats);
    }
}

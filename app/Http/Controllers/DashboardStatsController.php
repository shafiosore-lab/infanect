<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;

class DashboardStatsController extends Controller
{
    public function superAdmin()
    {
        $data = ['users' => 0, 'providers' => 0, 'bookings' => 0, 'revenue' => 0];
        try {
            $data['users'] = Schema::hasTable('users') ? \App\Models\User::count() : 0;
            $data['providers'] = Schema::hasTable('provider_profiles') ? \App\Models\ProviderProfile::count() : 0;
            $data['bookings'] = Schema::hasTable('bookings') ? \App\Models\Booking::count() : 0;
            $data['revenue'] = Schema::hasTable('payments') ? (float)\App\Models\Payment::sum('amount') : 0;
        } catch (\Throwable $e) {}

        return response()->json($data);
    }
}

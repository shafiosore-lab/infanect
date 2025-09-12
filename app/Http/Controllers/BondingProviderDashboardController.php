<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Auth;

class BondingProviderDashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $stats = ['activities' => 0, 'employees' => 0, 'bookings' => 0, 'earnings' => 0];

        try {
            if (Schema::hasTable('activities')) {
                $stats['activities'] = \App\Models\Activity::where('provider_profile_id', $user->provider_profile->id ?? null)->count();
            }
        } catch (\Throwable $e) {}

        return view('dashboards.provider-bonding.index', compact('stats'));
    }
}

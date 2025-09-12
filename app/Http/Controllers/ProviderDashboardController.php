<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ProviderDashboardController extends Controller
{
    public function index(Request $request)
    {
        $stats = [
            'services' => $request->user()->provider?->services()->count() ?? 0,
            'activities' => $request->user()->provider?->activities()->count() ?? 0,
            'bookings' => 0,
        ];

        return view('providers.dashboard', compact('stats'));
    }
}

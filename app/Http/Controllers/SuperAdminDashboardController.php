<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;

class SuperAdminDashboardController extends Controller
{
    public function index()
    {
        $stats = ['users' => 0, 'providers' => 0, 'bookings' => 0, 'payments' => 0, 'pending_documents' => 0];

        try {
            $stats['users'] = Schema::hasTable('users') ? \App\Models\User::count() : 0;
            $stats['providers'] = Schema::hasTable('provider_profiles') ? \App\Models\ProviderProfile::count() : 0;
            $stats['bookings'] = Schema::hasTable('bookings') ? \App\Models\Booking::count() : 0;
            $stats['payments'] = Schema::hasTable('payments') ? \App\Models\Payment::count() : 0;
            $stats['pending_documents'] = Schema::hasTable('documents') ? \App\Models\Document::count() : 0;
        } catch (\Throwable $e) {
            // ignore
        }

        return view('dashboards.super-admin.index', compact('stats'));
    }
}

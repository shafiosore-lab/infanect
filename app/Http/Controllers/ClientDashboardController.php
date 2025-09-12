<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Schema;

class ClientDashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $stats = ['bookings' => 0, 'payments' => 0, 'analytics' => 0, 'ai_library' => 0];

        try {
            if (Schema::hasTable('bookings')) {
                $stats['bookings'] = \App\Models\Booking::where('user_id', $user->id)->count();
            }
            if (Schema::hasTable('payments')) {
                $stats['payments'] = \App\Models\Payment::where('user_id', $user->id)->count();
            }
            if (Schema::hasTable('documents')) {
                $stats['ai_library'] = \App\Models\Document::where('approved', true)->count();
            }
        } catch (\Throwable $e) {}

        return view('dashboards.client.index', compact('stats'));
    }
}

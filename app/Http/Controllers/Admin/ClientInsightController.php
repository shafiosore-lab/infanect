<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Booking;

class ClientInsightController extends Controller
{
    public function index()
    {
        $stats = [
            'total_clients'   => User::whereHas('role', fn($q) => $q->where('slug', 'client'))->count(),
            'total_bookings'  => Booking::count(),
            'active_clients'  => User::where('is_active', true)->count(),
        ];

        return view('admin.insights.clients', compact('stats'));
    }
}

<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Service;
use App\Models\User;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('role:admin');
    }

    public function index()
    {
        // Generate basic reports data
        $reports = [
            'total_users' => User::count(),
            'total_services' => Service::count(),
            'total_bookings' => Booking::count(),
            'total_revenue' => Booking::sum('amount') ?? 0,
            'active_users' => User::where('is_active', true)->count(),
            'monthly_bookings' => Booking::whereMonth('created_at', now()->month)->count(),
        ];

        return view('admin.reports.index', compact('reports'));
    }
}

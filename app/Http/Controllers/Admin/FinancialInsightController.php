<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;

class FinancialInsightController extends Controller
{
    public function index()
    {
        $stats = [
            'total_revenue'   => Booking::sum('amount'),
            'pending_payouts' => Booking::where('status', 'pending')->sum('amount'),
            'completed_payouts' => Booking::where('status', 'completed')->sum('amount'),
        ];

        return view('admin.insights.financials', compact('stats'));
    }
}

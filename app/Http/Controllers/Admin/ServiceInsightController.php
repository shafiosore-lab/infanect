<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Service;

class ServiceInsightController extends Controller
{
    public function index()
    {
        $stats = [
            'total_services'   => Service::count(),
            'active_services'  => Service::where('is_active', true)->count(),
            'inactive_services'=> Service::where('is_active', false)->count(),
        ];

        return view('admin.insights.services', compact('stats'));
    }
}

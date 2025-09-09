<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

class AnalyticsController extends Controller
{
    public function performance()
    {
        return view('admin.analytics.performance');
    }

    public function growth()
    {
        return view('admin.analytics.growth');
    }

    public function retention()
    {
        return view('admin.analytics.retention');
    }

    public function engagement()
    {
        return view('admin.analytics.engagement');
    }
}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Auth;

class ProfessionalProviderDashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $stats = ['services' => 0, 'employees' => 0, 'clients' => 0, 'earnings' => 0, 'ai_documents' => 0];

        try {
            if (Schema::hasTable('professional_services')) {
                $stats['services'] = \App\Models\Service::where('provider_id', $user->id)->count();
            }
            if (Schema::hasTable('provider_profiles')) {
                $stats['ai_documents'] = \App\Models\Document::where('user_id', $user->id)->count();
            }
        } catch (\Throwable $e) {}

        return view('dashboards.provider-professional.index', compact('stats'));
    }
}

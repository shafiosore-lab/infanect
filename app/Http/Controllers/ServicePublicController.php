<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ServicePublicController extends Controller
{
    public function show($serviceId, Request $request)
    {
        $service = \App\Models\Service::with('provider')->findOrFail($serviceId);
        $date = $request->query('date', now()->toDateString());
        $tz = $request->query('timezone');
        $slots = $service->availableSlots($date, $tz);

        return view('services.show', compact('service', 'date', 'slots'));
    }

    public function slots($serviceId, Request $request)
    {
        $date = $request->query('date', now()->toDateString());
        $tz = $request->query('timezone');
        $service = \App\Models\Service::findOrFail($serviceId);

        return response()->json(['slots' => $service->availableSlots($date, $tz)]);
    }
}

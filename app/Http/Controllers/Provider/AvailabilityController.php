<?php

namespace App\Http\Controllers\Provider;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AvailabilityController extends Controller
{
    public function edit($serviceId)
    {
        $service = \App\Models\Service::findOrFail($serviceId);
        $this->authorize('update', $service);

        return view('provider.services.availability', ['service' => $service]);
    }

    public function update(Request $request, $serviceId)
    {
        $service = \App\Models\Service::findOrFail($serviceId);
        $this->authorize('update', $service);

        $data = $request->validate([
            'availability' => 'present|array',
        ]);

        $service->availability = $data['availability'];
        $service->save();

        return redirect()->route('provider.services.availability.edit', $service->id)->with('status', 'Availability updated');
    }

    public function slots(Request $request, $serviceId)
    {
        $date = $request->query('date', now()->toDateString());
        $service = \App\Models\Service::findOrFail($serviceId);

        return response()->json(['slots' => $service->availableSlots($date)]);
    }
}

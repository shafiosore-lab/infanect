<?php

namespace App\Http\Controllers\Provider;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Service;

class AvailabilityController extends Controller
{
    /**
     * Show availability edit form for a service.
     */
    public function edit(Service $service)
    {
        $this->authorize('update', $service);

        return view('provider.services.availability', compact('service'));
    }

    /**
     * Update availability for a service.
     */
    public function update(Request $request, Service $service)
    {
        $this->authorize('update', $service);

        $validated = $request->validate([
            'availability' => 'required|array',
            'availability.*.day' => 'required|string',
            'availability.*.slots' => 'required|array',
            'availability.*.slots.*' => 'date_format:H:i', // example: 09:00
        ]);

        $service->availability = $validated['availability'];
        $service->save();

        return redirect()
            ->route('provider.services.availability.edit', $service->id)
            ->with('status', 'Availability updated successfully.');
    }

    /**
     * Get available slots for a given date.
     */
    public function slots(Request $request, Service $service)
    {
        $this->authorize('view', $service);

        $date = $request->query('date', now()->toDateString());

        return response()->json([
            'slots' => $service->availableSlots($date),
        ]);
    }
}

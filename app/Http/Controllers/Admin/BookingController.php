<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Booking;
use App\Models\Client;
use App\Models\Service;
use Carbon\Carbon;
use Illuminate\Support\Facades\App;

class BookingController extends Controller
{
    /**
     * Display a paginated list of bookings with filters
     */
    public function index(Request $request)
    {
        $query = Booking::with(['client', 'service', 'service.provider']); // eager load

        // Filters
        if ($request->filled('client_id')) {
            $query->where('client_id', $request->client_id);
        }
        if ($request->filled('service_id')) {
            $query->where('service_id', $request->service_id);
        }
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('date_from')) {
            $query->whereDate('datetime', '>=', Carbon::parse($request->date_from));
        }
        if ($request->filled('date_to')) {
            $query->whereDate('datetime', '<=', Carbon::parse($request->date_to));
        }

        $bookings = $query->latest()->paginate(20);

        // Pass clients and services for filter dropdowns
        $clients  = Client::orderBy('name')->get();
        $services = Service::orderBy('name')->get();

        // Set locale for internationalization
        App::setLocale($request->get('locale', 'en'));

        return view('admin.bookings.index', compact('bookings', 'clients', 'services'));
    }

    /**
     * Show a single booking
     */
    public function show(Booking $booking)
    {
        return view('admin.bookings.show', compact('booking'));
    }

    /**
     * Show form to create a new booking
     */
    public function create()
    {
        $clients  = Client::orderBy('name')->get();
        $services = Service::orderBy('name')->get();

        return view('admin.bookings.create', compact('clients', 'services'));
    }

    /**
     * Store a new booking
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'client_id'  => 'required|exists:clients,id',
            'service_id' => 'required|exists:services,id',
            'datetime'   => 'required|date',
            'status'     => 'required|string|max:50',
            'notes'      => 'nullable|string|max:500',
        ]);

        // Convert to UTC for international consistency
        $data['datetime'] = Carbon::parse($data['datetime'])->timezone('UTC');

        Booking::create($data);

        return redirect()->route('admin.bookings.index')
                         ->with('success', __('Booking created successfully.'));
    }

    /**
     * Show form to edit an existing booking
     */
    public function edit(Booking $booking)
    {
        $clients  = Client::orderBy('name')->get();
        $services = Service::orderBy('name')->get();

        return view('admin.bookings.edit', compact('booking', 'clients', 'services'));
    }

    /**
     * Update an existing booking
     */
    public function update(Request $request, Booking $booking)
    {
        $data = $request->validate([
            'client_id'  => 'required|exists:clients,id',
            'service_id' => 'required|exists:services,id',
            'datetime'   => 'required|date',
            'status'     => 'required|string|max:50',
            'notes'      => 'nullable|string|max:500',
        ]);

        $data['datetime'] = Carbon::parse($data['datetime'])->timezone('UTC');

        $booking->update($data);

        return redirect()->route('admin.bookings.index')
                         ->with('success', __('Booking updated successfully.'));
    }

    /**
     * Delete a booking
     */
    public function destroy(Booking $booking)
    {
        $booking->delete();

        return redirect()->route('admin.bookings.index')
                         ->with('success', __('Booking deleted successfully.'));
    }
}

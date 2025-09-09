<?php
namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;
use App\Models\Activity;
use App\Models\Booking;
use Illuminate\Http\Request;
use App\Services\BookingService;

class BookingController extends Controller {
    public function store(Request $r, BookingService $bookingService) {
        $r->validate([
            'activity_id'=>'required|exists:activities,id',
            'payment_method'=>'required|string' // e.g. stripe_payment_method_id
        ]);

        $user = $r->user();
        $result = $bookingService->book($user, $r->activity_id, $r->payment_method);

        if ($result['success']) {
            return response()->json($result['booking'], 201);
        }

        return response()->json(['error' => $result['message']], 422);
    }
}

<?php
namespace App\Services;
use App\Models\Activity;
use App\Models\Booking;
use App\Services\PaymentService;

class BookingService {
    protected $payments;
    public function __construct(PaymentService $payments){ $this->payments=$payments; }

    public function book($user, $activityId, $paymentMethod){
        $activity = Activity::findOrFail($activityId);
        if ($activity->availableSlots() <= 0) return ['success'=>false,'message'=>'No slots'];
        // charge via payment service
        $charge = $this->payments->charge($user,$activity->price,$paymentMethod);
        if (!$charge['success']) return ['success'=>false,'message'=>$charge['message']];

        $booking = Booking::create([
            'user_id'=>$user->id,
            'activity_id'=>$activity->id,
            'provider_id'=>$activity->provider_id,
            'status'=>'confirmed',
            'price'=>$activity->price,
            'payment_ref'=>$charge['ref']
        ]);

        // dispatch notifications, analytics events, etc.
        return ['success'=>true,'booking'=>$booking];
    }
}

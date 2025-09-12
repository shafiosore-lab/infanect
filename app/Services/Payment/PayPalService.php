<?php

namespace App\Services\Payment;

class PayPalService
{
    public function createOrder($booking)
    {
        // Placeholder: integrate PayPal SDK to create an order and return approval URL
        return route('payments.checkout', $booking->id);
    }
}

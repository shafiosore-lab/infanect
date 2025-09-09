<?php
namespace App\Services;
class PaymentService {
    public function charge($user, $amount, $paymentMethod) {
        // integrate Stripe / Cashier to charge and return ['success' => true, 'ref' => 'stripe_charge_id']
    }
}

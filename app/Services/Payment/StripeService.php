<?php

namespace App\Services\Payment;

class StripeService
{
    public function createCheckoutSession($booking)
    {
        // Placeholder: create a Stripe checkout session and return the approval URL
        if (!class_exists('\Stripe\Checkout\Session')) {
            // Fallback: return route to internal checkout flow
            return route('payments.checkout', $booking->id);
        }

        \Stripe\Stripe::setApiKey(config('services.stripe.secret'));

        $session = \Stripe\Checkout\Session::create([
            'payment_method_types' => ['card'],
            'line_items' => [[
                'price_data' => [
                    'currency' => strtolower($booking->currency ?? 'kes'),
                    'product_data' => ['name' => $booking->service->name],
                    'unit_amount' => (int)($booking->amount_paid * 100),
                ],
                'quantity' => 1,
            ]],
            'mode' => 'payment',
            'success_url' => route('services.show', $booking->service->id).'?payment=success',
            'cancel_url' => route('services.show', $booking->service->id).'?payment=cancel',
        ]);

        return $session->url ?? route('payments.checkout', $booking->id);
    }
}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Redirect;
use Barryvdh\DomPDF\Facades\Pdf; // Ensure barryvdh/laravel-dompdf is installed

class PaymentController extends Controller
{
    /**
     * Show checkout and/or redirect to gateway checkout flow.
     */
    public function checkout(Request $request, $bookingId, $gateway = null)
    {
        $booking = \App\Models\Booking::with('service','provider','client')->findOrFail($bookingId);

        // If gateway specified, start gateway-specific flow
        if ($gateway) {
            $gateway = strtolower($gateway);

            try {
                if ($gateway === 'stripe') {
                    $svc = new \App\Services\Payment\StripeService();
                    $sessionUrl = $svc->createCheckoutSession($booking);
                    return Redirect::to($sessionUrl);
                }

                if ($gateway === 'paypal') {
                    $svc = new \App\Services\Payment\PayPalService();
                    $approveUrl = $svc->createOrder($booking);
                    return Redirect::to($approveUrl);
                }

                if ($gateway === 'mpesa') {
                    $svc = new \App\Services\Payment\MpesaService();
                    $resp = $svc->initiatePayment($booking);
                    // Mpesa flow may return instructions or short code response
                    return view('payments.mpesa-instructions', compact('booking','resp'));
                }
            } catch (\Exception $e) {
                Log::error('Payment gateway checkout failed: '.$e->getMessage());
                return back()->withErrors(['payment' => 'Payment gateway initiation failed.']);
            }
        }

        // Otherwise show generic checkout page with options
        return view('payments.checkout', compact('booking'));
    }

    /**
     * Generic webhook endpoint that dispatches to provider handlers.
     */
    public function webhook(Request $request)
    {
        $source = $request->header('X-Payment-Source') ?? $request->input('source') ?? 'unknown';
        $source = strtolower($source);

        try {
            if ($source === 'stripe') {
                return $this->stripeWebhook($request);
            }

            if ($source === 'paypal') {
                return $this->paypalWebhook($request);
            }

            if ($source === 'mpesa') {
                return $this->mpesaWebhook($request);
            }
        } catch (\Exception $e) {
            Log::error('Webhook handling error: '.$e->getMessage());
            return response('error', 500);
        }

        return response('ok');
    }

    protected function stripeWebhook(Request $request)
    {
        // Example: verify event using stripe-php and handle checkout.session.completed
        try {
            $payload = $request->getContent();
            $sigHeader = $request->header('Stripe-Signature');

            // Use Stripe SDK to verify event if available
            if (class_exists('\Stripe\Webhook')) {
                \Stripe\Stripe::setApiKey(config('services.stripe.secret'));
                $event = \Stripe\Webhook::constructEvent($payload, $sigHeader, config('services.stripe.webhook_secret'));
            } else {
                $event = json_decode($payload, true);
            }

            $type = is_array($event) ? ($event['type'] ?? null) : ($event->type ?? null);

            if ($type === 'checkout.session.completed' || $type === 'payment_intent.succeeded') {
                // Map session -> payment -> booking and mark completed
                // This requires storing gateway identifiers on Payment->meta when creating the session
                // Placeholder: log and return
                Log::info('Stripe event received: '.$type);
            }

            return response('ok');
        } catch (\Exception $e) {
            Log::error('Stripe webhook error: '.$e->getMessage());
            return response('error', 500);
        }
    }

    protected function paypalWebhook(Request $request)
    {
        // Placeholder for PayPal IPN / Webhook handling
        Log::info('PayPal webhook payload: '.json_encode($request->all()));
        return response('ok');
    }

    protected function mpesaWebhook(Request $request)
    {
        // Placeholder for Mpesa webhook handling
        Log::info('Mpesa webhook payload: '.json_encode($request->all()));
        return response('ok');
    }

    /**
     * Generate PDF receipt for a payment.
     */
    public function receipt($paymentId)
    {
        $payment = \App\Models\Payment::with('booking.service','booking.provider','booking.client')->findOrFail($paymentId);
        $booking = $payment->booking;

        $viewData = compact('payment','booking');

        // Use Dompdf via barryvdh/laravel-dompdf if available
        if (class_exists(Pdf::class)) {
            $pdf = Pdf::loadView('payments.receipt', $viewData);
            $filename = 'receipt_'.$payment->id.'.pdf';
            return $pdf->download($filename);
        }

        // Fallback: render HTML and force download as .html
        return response()->view('payments.receipt', $viewData)->header('Content-Type', 'text/html');
    }
}

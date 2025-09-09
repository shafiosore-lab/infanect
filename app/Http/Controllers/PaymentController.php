<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Booking;
use App\Models\Transaction;
use Illuminate\Support\Facades\Auth;

class PaymentController extends Controller
{
    /**
     * Supported currencies and conversion rates relative to base currency (KES).
     * In real application, you might fetch rates from an API.
     */
    private $currencies = [
        'KES' => 1,        // Base currency
        'USD' => 0.0071,
        'EUR' => 0.0065,
        'GBP' => 0.0055,
    ];

    /**
     * Show payment form for a booking.
     */
    public function create(Booking $booking)
    {
        $this->authorizeBooking($booking);

        if ($booking->amount_paid >= $booking->amount) {
            return redirect()->route('bookings.show', $booking)
                             ->with('info', 'This booking is already fully paid.');
        }

        return view('payments.create', compact('booking'));
    }

    /**
     * Store payment and handle different payment methods with currency conversion.
     */
    public function store(Request $request, Booking $booking)
    {
        $this->authorizeBooking($booking);

        $request->validate([
            'payment_method' => 'required|in:mpesa,card,bank',
            'currency' => 'required|in:' . implode(',', array_keys($this->currencies)),
            'phone_number' => 'required_if:payment_method,mpesa|string|max:15',
            'amount' => 'required|numeric|min:0.01',
        ]);

        // Convert amount to base currency (KES) for consistency
        $amountInKES = $request->amount / $this->currencies[$request->currency];

        // Ensure payment does not exceed remaining balance
        $remaining = $booking->amount - $booking->amount_paid;
        if ($amountInKES > $remaining) {
            return back()->withErrors(['amount' => 'Amount exceeds remaining balance.']);
        }

        switch ($request->payment_method) {
            case 'mpesa':
                $transactionRef = 'MPESA_' . time() . '_' . rand(1000, 9999);
                $transaction = $this->createTransaction($booking, $amountInKES, $request->currency, 'mpesa', $transactionRef);
                $this->simulateMpesaPayment($transaction, $booking, $amountInKES);
                return redirect()->route('payments.confirm', ['booking' => $booking, 'transaction' => $transaction])
                                 ->with('success', 'Payment initiated. Enter the M-Pesa confirmation code.');

            case 'card':
                $transactionRef = 'CARD_' . time() . '_' . rand(1000, 9999);
                $transaction = $this->createTransaction($booking, $amountInKES, $request->currency, 'card', $transactionRef, 'completed');
                $this->applyPayment($booking, $amountInKES);
                return redirect()->route('bookings.show', $booking)
                                 ->with('success', 'Payment completed successfully!');

            case 'bank':
                $transactionRef = 'BANK_' . time() . '_' . rand(1000, 9999);
                $transaction = $this->createTransaction($booking, $amountInKES, $request->currency, 'bank', $transactionRef);
                return redirect()->route('bookings.show', $booking)
                                 ->with('success', 'Bank transfer initiated. Payment will be confirmed within 24 hours.');
        }
    }

    /**
     * Confirm M-Pesa payment using user input code.
     */
    public function confirm(Request $request, Booking $booking, Transaction $transaction)
    {
        $this->authorizeBooking($booking);

        if ($request->isMethod('post')) {
            $request->validate(['confirmation_code' => 'required|string|size:6']);

            if (preg_match('/^\d{6}$/', $request->confirmation_code)) {
                $transaction->update(['status' => 'completed']);
                $this->applyPayment($booking, $transaction->amount);

                return redirect()->route('bookings.show', $booking)
                                 ->with('success', 'Payment confirmed successfully!');
            } else {
                return back()->with('error', 'Invalid confirmation code. Please try again.');
            }
        }

        return view('payments.confirm', compact('booking', 'transaction'));
    }

    /**
     * Payment history for user or provider.
     */
    public function history()
    {
        $user = auth()->user();

        $transactions = Transaction::whereHas('bookings', fn($q) => $q->where('user_id', $user->id))
                                   ->orWhereIn('provider_id', fn($sub) => $sub->select('provider_id')
                                                                                ->from('bookings')
                                                                                ->where('user_id', $user->id))
                                   ->orderBy('created_at', 'desc')
                                   ->paginate(10);

        return view('payments.history', compact('transactions'));
    }

    /**
     * Handle M-Pesa STK callback.
     */
    public function mpesaCallback(Request $request)
    {
        $data = $request->all();
        $checkoutRequestID = $data['Body']['stkCallback']['CheckoutRequestID'] ?? null;
        $resultCode = $data['Body']['stkCallback']['ResultCode'] ?? 1;

        if ($checkoutRequestID && $resultCode == 0) {
            $amount = $data['Body']['stkCallback']['CallbackMetadata']['Item'][0]['Value'] ?? 0;
            $mpesaReceipt = $data['Body']['stkCallback']['CallbackMetadata']['Item'][1]['Value'] ?? null;

            $booking = Booking::where('transaction_id', $checkoutRequestID)->first();
            if ($booking) {
                $booking->update([
                    'status' => 'confirmed',
                    'amount_paid' => $amount,
                    'transaction_id' => $mpesaReceipt,
                ]);
            }
        }

        return response()->json(['success' => true]);
    }

    /**
     * Helper: Authorize that the current user owns the booking.
     */
    private function authorizeBooking(Booking $booking)
    {
        if ($booking->user_id !== auth()->id()) {
            abort(403);
        }
    }

    /**
     * Helper: Create a transaction.
     */
    private function createTransaction(Booking $booking, $amount, $currency, $method, $reference, $status = 'pending')
    {
        return Transaction::create([
            'provider_id' => $booking->provider_id,
            'transaction_type' => 'booking_payment',
            'amount' => $amount,
            'currency_code' => $currency,
            'payment_method' => $method,
            'transaction_reference' => $reference,
            'status' => $status,
        ]);
    }

    /**
     * Helper: Apply payment to booking and update status.
     */
    private function applyPayment(Booking $booking, $amount)
    {
        $booking->increment('amount_paid', $amount);

        if ($booking->amount_paid >= $booking->amount) {
            $booking->update(['status' => 'confirmed']);
        }
    }

    /**
     * Process payment for booking (alternative method).
     */
    public function processPayment(Request $request, Booking $booking)
    {
        $this->authorizeBooking($booking);

        $request->validate([
            'payment_method' => 'required|in:mpesa,card,bank',
            'currency' => 'required|in:' . implode(',', array_keys($this->currencies)),
            'phone_number' => 'required_if:payment_method,mpesa|string|max:15',
            'amount' => 'required|numeric|min:0.01',
        ]);

        // Convert amount to base currency (KES) for consistency
        $amountInKES = $request->amount / $this->currencies[$request->currency];

        // Ensure payment does not exceed remaining balance
        $remaining = $booking->amount - $booking->amount_paid;
        if ($amountInKES > $remaining) {
            return response()->json([
                'success' => false,
                'message' => 'Amount exceeds remaining balance.'
            ], 422);
        }

        switch ($request->payment_method) {
            case 'mpesa':
                $transactionRef = 'MPESA_' . time() . '_' . rand(1000, 9999);
                $transaction = $this->createTransaction($booking, $amountInKES, $request->currency, 'mpesa', $transactionRef);
                $this->simulateMpesaPayment($transaction, $booking, $amountInKES);
                return response()->json([
                    'success' => true,
                    'message' => 'Payment initiated successfully.',
                    'transaction' => $transaction
                ]);

            case 'card':
                $transactionRef = 'CARD_' . time() . '_' . rand(1000, 9999);
                $transaction = $this->createTransaction($booking, $amountInKES, $request->currency, 'card', $transactionRef, 'completed');
                $this->applyPayment($booking, $amountInKES);
                return response()->json([
                    'success' => true,
                    'message' => 'Payment completed successfully.',
                    'transaction' => $transaction
                ]);

            case 'bank':
                $transactionRef = 'BANK_' . time() . '_' . rand(1000, 9999);
                $transaction = $this->createTransaction($booking, $amountInKES, $request->currency, 'bank', $transactionRef);
                return response()->json([
                    'success' => true,
                    'message' => 'Bank transfer initiated successfully.',
                    'transaction' => $transaction
                ]);
        }
    }

    /**
     * Simulate M-Pesa payment (for demo purposes only).
     */
    private function simulateMpesaPayment(Transaction $transaction, Booking $booking, $amount)
    {
        \Log::info('M-Pesa payment initiated', [
            'transaction_id' => $transaction->id,
            'booking_id' => $booking->id,
            'amount' => $amount,
            'phone' => $booking->customer_phone ?? 'N/A',
        ]);
    }
}

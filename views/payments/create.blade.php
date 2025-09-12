@extends('layouts.app')

@section('title', 'Make Payment')

@section('content')
<div class="max-w-3xl mx-auto bg-white shadow-md rounded-lg p-6">
    <h2 class="text-2xl font-semibold mb-4">Payment for Booking #{{ $booking->id }}</h2>

    <p class="mb-4">
        <strong>Booking Amount:</strong> {{ number_format($booking->amount, 2) }} KES
        <br>
        <strong>Amount Paid:</strong> {{ number_format($booking->amount_paid, 2) }} KES
        <br>
        <strong>Remaining:</strong> {{ number_format($booking->amount - $booking->amount_paid, 2) }} KES
    </p>

    <form action="{{ route('payments.store', $booking) }}" method="POST" class="space-y-4">
        @csrf

        <!-- Payment Method -->
        <div>
            <label class="block font-medium text-gray-700">Payment Method</label>
            <select name="payment_method" id="payment_method" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" required>
                <option value="">Select method</option>
                <option value="mpesa">M-Pesa</option>
                <option value="card">Card</option>
                <option value="bank">Bank Transfer</option>
            </select>
        </div>

        <!-- Currency Selection -->
        <div>
            <label class="block font-medium text-gray-700">Currency</label>
            <select name="currency" id="currency" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" required>
                <option value="KES" selected>KES</option>
                <option value="USD">USD</option>
                <option value="EUR">EUR</option>
                <option value="GBP">GBP</option>
            </select>
        </div>

        <!-- Amount -->
        <div>
            <label class="block font-medium text-gray-700">Amount</label>
            <input type="number" name="amount" id="amount" min="0.01" step="0.01"
                   max="{{ $booking->amount - $booking->amount_paid }}"
                   value="{{ $booking->amount - $booking->amount_paid }}"
                   class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" required>
            <p class="text-sm text-gray-500 mt-1">Converted to KES: <span id="converted_amount">{{ $booking->amount - $booking->amount_paid }}</span> KES</p>
        </div>

        <!-- M-Pesa Phone (only if selected) -->
        <div id="mpesa_phone_div" class="hidden">
            <label class="block font-medium text-gray-700">Phone Number (M-Pesa)</label>
            <input type="text" name="phone_number" id="phone_number" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" placeholder="e.g., 2547XXXXXXXX" maxlength="15">
        </div>

        <button type="submit" class="bg-indigo-600 text-white px-4 py-2 rounded-md hover:bg-indigo-700">Pay Now</button>
    </form>
</div>

<script>
    const currencies = {
        KES: 1,
        USD: 0.0071,
        EUR: 0.0065,
        GBP: 0.0055
    };

    const currencySelect = document.getElementById('currency');
    const amountInput = document.getElementById('amount');
    const convertedDisplay = document.getElementById('converted_amount');
    const paymentMethod = document.getElementById('payment_method');
    const mpesaDiv = document.getElementById('mpesa_phone_div');

    function updateConversion() {
        const currency = currencySelect.value;
        const amount = parseFloat(amountInput.value) || 0;
        const converted = (amount / currencies[currency]).toFixed(2);
        convertedDisplay.textContent = converted;
    }

    currencySelect.addEventListener('change', updateConversion);
    amountInput.addEventListener('input', updateConversion);

    paymentMethod.addEventListener('change', () => {
        if(paymentMethod.value === 'mpesa') {
            mpesaDiv.classList.remove('hidden');
        } else {
            mpesaDiv.classList.add('hidden');
        }
    });

    // Initialize
    updateConversion();
</script>
@endsection

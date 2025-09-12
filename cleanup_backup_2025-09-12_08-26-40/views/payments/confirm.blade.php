@extends('layouts.app')

@section('title', 'Confirm Payment')

@section('content')
<div class="max-w-3xl mx-auto py-10">
    <div class="bg-white shadow rounded-lg p-6">
        <h2 class="text-2xl font-bold mb-6 text-green-700">Confirm Your Payment</h2>

        <!-- Booking Details -->
        <div class="mb-6">
            <h3 class="text-lg font-semibold mb-2">Booking Details</h3>
            <ul class="space-y-1 text-gray-700">
                <li><strong>Activity:</strong> {{ $booking->activity->title }}</li>
                <li><strong>Date:</strong> {{ $booking->activity->datetime->format('d M Y, H:i') }}</li>
                <li><strong>Location:</strong> {{ $booking->activity->venue }}, {{ $booking->activity->region }}, {{ $booking->activity->country }}</li>
                <li><strong>Participants:</strong> {{ $booking->participants }}</li>
            </ul>
        </div>

        <!-- Payment Summary -->
        <div class="mb-6">
            <h3 class="text-lg font-semibold mb-2">Payment Summary</h3>
            <div class="flex items-center gap-4 mb-2">
                <span><strong>Amount:</strong></span>
                <input type="number" id="amount" value="{{ $booking->amount }}" readonly class="border rounded px-2 py-1 w-24 text-gray-700">
                <select id="currency" class="border rounded px-2 py-1">
                    <option value="KES" selected>KES</option>
                    <option value="USD">USD</option>
                    <option value="EUR">EUR</option>
                    <option value="GBP">GBP</option>
                </select>
            </div>
            <div class="text-gray-700 mt-1">
                Converted: <span id="converted-amount">{{ number_format($booking->amount, 2) }} KES</span>
            </div>
        </div>

        <!-- Payment Method -->
        <div class="mb-6">
            <h3 class="text-lg font-semibold mb-2">Select Payment Method</h3>
            <div class="flex items-center gap-6">
                <label class="inline-flex items-center">
                    <input type="radio" name="payment_method" value="mpesa" checked class="mr-2" id="mpesa">
                    STK Push
                </label>
                <label class="inline-flex items-center">
                    <input type="radio" name="payment_method" value="manual" class="mr-2" id="manual">
                    Manual Payment
                </label>
            </div>
        </div>

        <!-- Payment Form -->
        <form action="{{ route('payments.store', $booking->id) }}" method="POST" id="payment-form">
            @csrf
            <input type="hidden" name="amount" id="form-amount" value="{{ $booking->amount }}">
            <input type="hidden" name="currency" id="form-currency" value="KES">
            <input type="hidden" name="payment_method" id="form-payment-method" value="mpesa">

            <!-- STK Push Phone -->
            <div id="mpesaFields" class="mb-4">
                <label class="block text-sm font-medium text-gray-700">Phone Number</label>
                <input type="text" name="phone_number" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm p-2" required>
            </div>

            <!-- Manual Payment Code -->
            <div id="manualFields" class="mb-4 hidden">
                <label class="block text-sm font-medium text-gray-700">Payment Reference</label>
                <input type="text" name="manual_code" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm p-2">
            </div>

            <div class="mb-4">
                <label for="confirm" class="inline-flex items-center">
                    <input type="checkbox" id="confirm" name="confirm" required class="mr-2">
                    I confirm that all details above are correct.
                </label>
            </div>

            <button type="submit" id="submit-btn" class="bg-green-600 hover:bg-green-700 text-white font-semibold px-6 py-2 rounded-lg transition">
                Proceed to Pay
            </button>
        </form>

        <a href="{{ route('bookings.index') }}" class="block mt-4 text-gray-500 hover:text-gray-700">
            &larr; Go Back to Bookings
        </a>
    </div>
</div>

<script>
    const rates = { KES: 1, USD: 0.0071, EUR: 0.0065, GBP: 0.0055 };

    const amountInput = document.getElementById('amount');
    const currencySelect = document.getElementById('currency');
    const convertedDisplay = document.getElementById('converted-amount');
    const formAmount = document.getElementById('form-amount');
    const formCurrency = document.getElementById('form-currency');
    const paymentRadios = document.querySelectorAll('input[name="payment_method"]');
    const formPaymentMethod = document.getElementById('form-payment-method');
    const form = document.getElementById('payment-form');
    const submitBtn = document.getElementById('submit-btn');

    const mpesaFields = document.getElementById('mpesaFields');
    const manualFields = document.getElementById('manualFields');

    function updateConversion() {
        const baseAmount = parseFloat(amountInput.value);
        const currency = currencySelect.value;
        const converted = baseAmount * rates[currency];
        convertedDisplay.textContent = converted.toFixed(2) + ' ' + currency;
        formAmount.value = converted.toFixed(2);
        formCurrency.value = currency;
    }

    currencySelect.addEventListener('change', updateConversion);

    paymentRadios.forEach(radio => {
        radio.addEventListener('change', () => {
            formPaymentMethod.value = radio.value;
            if (radio.value === 'mpesa') {
                mpesaFields.classList.remove('hidden');
                manualFields.classList.add('hidden');
                mpesaFields.querySelector('input').required = true;
                manualFields.querySelector('input').required = false;
            } else {
                mpesaFields.classList.add('hidden');
                manualFields.classList.remove('hidden');
                mpesaFields.querySelector('input').required = false;
                manualFields.querySelector('input').required = true;
            }
        });
    });

    // Rate-limit submission
    form.addEventListener('submit', function(e) {
        submitBtn.disabled = true;
        submitBtn.textContent = "Processing...";
    });

    updateConversion();
</script>
@endsection

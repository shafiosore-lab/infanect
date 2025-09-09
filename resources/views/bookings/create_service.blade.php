@extends('layouts.app')

@section('title', 'Book Activity')

@section('content')
<div class="container mx-auto p-6">
    <div class="bg-white shadow rounded-lg p-6 max-w-3xl mx-auto">
        <h1 class="text-2xl font-bold text-gray-900 mb-4">Book Activity</h1>
        <p class="text-gray-600 mb-6">Fill in your details and confirm your booking.</p>

        <form id="bookingForm" class="space-y-4">
            @csrf
            <input type="hidden" name="activity_id" value="{{ $activity->id }}">

            <!-- Customer Info -->
            <div>
                <label class="block text-sm font-medium text-gray-700">Name</label>
                <input type="text" name="customer_name" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm p-2">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700">Email</label>
                <input type="email" name="customer_email" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm p-2">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700">Phone</label>
                <input type="text" name="customer_phone" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm p-2">
            </div>

            <!-- Participants -->
            <div>
                <label class="block text-sm font-medium text-gray-700">Number of Participants</label>
                <input type="number" name="participants" min="1" max="{{ $activity->slots }}" value="1" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm p-2">
                <p class="text-xs text-gray-500 mt-1">Available slots: {{ $activity->availableSlots() }}</p>
            </div>

            <!-- Currency -->
            <div>
                <label class="block text-sm font-medium text-gray-700">Currency</label>
                <select name="currency" id="currency" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm p-2">
                    <option value="KES" selected>KES</option>
                    <option value="USD">USD</option>
                    <option value="EUR">EUR</option>
                    <option value="GBP">GBP</option>
                </select>
                <p class="text-sm text-gray-500 mt-1">Converted amount will be displayed below.</p>
                <p id="convertedAmount" class="text-lg font-semibold text-gray-900 mt-1">{{ number_format($activity->price, 2) }} KES</p>
            </div>

            <!-- Payment Method -->
            <div>
                <label class="block text-sm font-medium text-gray-700">Payment Method</label>
                <select name="payment_method" id="paymentMethod" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm p-2">
                    <option value="mpesa">STK Push</option>
                    <option value="manual">Upload Payment Code</option>
                </select>
            </div>

            <!-- Conditional Payment Fields -->
            <div id="stkPushFields" class="mt-2">
                <label class="block text-sm font-medium text-gray-700">Phone Number for STK Push</label>
                <input type="text" name="mpesa_number" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm p-2">
            </div>

            <div id="manualFields" class="mt-2 hidden">
                <label class="block text-sm font-medium text-gray-700">Upload Payment Code</label>
                <input type="text" name="payment_code" placeholder="Enter payment reference" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm p-2">
            </div>

            <!-- Notes -->
            <div>
                <label class="block text-sm font-medium text-gray-700">Notes (Optional)</label>
                <textarea name="notes" rows="3" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm p-2"></textarea>
            </div>

            <!-- Submit -->
            <div class="mt-4">
                <button type="submit" id="submitBtn" class="w-full bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-md text-lg font-medium">
                    Confirm Booking
                </button>
                <p id="formMessage" class="mt-2 text-sm"></p>
            </div>
        </form>
    </div>
</div>

<script>
    // Payment method toggle
    const paymentMethod = document.getElementById('paymentMethod');
    const stkPushFields = document.getElementById('stkPushFields');
    const manualFields = document.getElementById('manualFields');

    paymentMethod.addEventListener('change', () => {
        if(paymentMethod.value === 'mpesa'){
            stkPushFields.classList.remove('hidden');
            manualFields.classList.add('hidden');
            stkPushFields.querySelector('input').required = true;
            manualFields.querySelector('input').required = false;
        } else {
            stkPushFields.classList.add('hidden');
            manualFields.classList.remove('hidden');
            stkPushFields.querySelector('input').required = false;
            manualFields.querySelector('input').required = true;
        }
    });

    // Currency conversion
    const currencySelect = document.getElementById('currency');
    const convertedAmount = document.getElementById('convertedAmount');
    const participantsInput = document.querySelector('input[name="participants"]');
    const baseAmount = {{ $activity->price }};
    const rates = { KES:1, USD:0.0072, EUR:0.0065, GBP:0.0056 };

    function updateConvertedAmount(){
        const selected = currencySelect.value;
        const participants = participantsInput.value || 1;
        const converted = (baseAmount * rates[selected] * participants).toFixed(2);
        convertedAmount.innerText = `${selected} ${converted}`;
    }

    currencySelect.addEventListener('change', updateConvertedAmount);
    participantsInput.addEventListener('input', updateConvertedAmount);

    // Rate-limited submission & redirect to payment confirmation
    let lastSubmit = 0;
    const bookingForm = document.getElementById('bookingForm');
    const submitBtn = document.getElementById('submitBtn');
    const formMessage = document.getElementById('formMessage');

    bookingForm.addEventListener('submit', async (e) => {
        e.preventDefault();
        const now = Date.now();
        if(now - lastSubmit < 1000){
            formMessage.innerText = "Please wait before submitting again.";
            formMessage.classList.add('text-red-600');
            return;
        }
        lastSubmit = now;

        submitBtn.disabled = true;
        submitBtn.innerText = "Processing...";

        const formData = new FormData(bookingForm);

        try {
            const response = await fetch("{{ route('bookings.store') }}", {
                method: "POST",
                headers: {'X-CSRF-TOKEN':'{{ csrf_token() }}'},
                body: formData
            });

            if(response.redirected){
                // Follow redirect to payment confirmation page
                window.location.href = response.url;
            } else {
                const result = await response.json();
                formMessage.innerText = result.message || "Booking failed.";
                formMessage.classList.add('text-red-600');
            }
        } catch(err){
            formMessage.innerText = "An error occurred. Please try again.";
            formMessage.classList.add('text-red-600');
        } finally {
            submitBtn.disabled = false;
            submitBtn.innerText = "Confirm Booking";
        }
    });
</script>
@endsection

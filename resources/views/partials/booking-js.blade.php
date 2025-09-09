@if($activity)
<script>
document.addEventListener('DOMContentLoaded', function () {

    const bookingForm = document.getElementById('bookingForm');
    const submitBtn = bookingForm.querySelector('button[type="submit"]');
    const mpesaSection = document.getElementById('mpesaSection');
    const cardSection = document.getElementById('cardSection');
    const customerPhone = document.getElementById('customer_phone');
    const mpesaNumber = document.getElementById('mpesa_number');

    // Payment method toggle
    document.querySelectorAll('input[name="payment_method"]').forEach(radio => {
        radio.addEventListener('change', function() {
            if (this.value === 'mpesa') {
                mpesaSection.classList.remove('hidden');
                cardSection.classList.add('hidden');
            } else {
                mpesaSection.classList.add('hidden');
                cardSection.classList.remove('hidden');
            }
        });
    });

    // Auto-fill M-Pesa number
    customerPhone.addEventListener('input', function() {
        mpesaNumber.value = this.value;
    });

    // Form submission
    bookingForm.addEventListener('submit', function(e) {
        e.preventDefault();
        clearAlerts();

        const formData = new FormData(bookingForm);
        const paymentMethod = formData.get('payment_method');
        const originalText = submitBtn.innerHTML;

        submitBtn.disabled = true;
        submitBtn.innerHTML = loadingButtonHTML();

        const bookingData = {
            activity_id: formData.get('activity_id'),
            customer_name: formData.get('customer_name'),
            customer_email: formData.get('customer_email'),
            customer_phone: formData.get('customer_phone'),
            participants: formData.get('participants') || 1,
            payment_method: paymentMethod,
            notes: formData.get('notes')
        };

        if (paymentMethod === 'mpesa') {
            bookingData.mpesa_number = formData.get('mpesa_number');
        } else {
            bookingData.card_number = formData.get('card_number');
            bookingData.expiry_date = formData.get('expiry_date');
            bookingData.cvv = formData.get('cvv');
        }

        processPayment(bookingData, paymentMethod)
            .then(paymentResult => {
                if (paymentResult.success) return createBooking(bookingData, paymentResult);
                throw new Error(paymentResult.message || 'Payment failed');
            })
            .then(bookingResult => {
                if (bookingResult.success) {
                    showSuccess(bookingResult);
                    setTimeout(() => {
                        window.location.href = '{{ route("bookings.success", ":bookingId") }}'.replace(':bookingId', bookingResult.booking.id);
                    }, 2000);
                } else {
                    throw new Error(bookingResult.message || 'Booking failed');
                }
            })
            .catch(error => {
                showError(error.message);
                submitBtn.disabled = false;
                submitBtn.innerHTML = originalText;
            });
    });

    // Helper Functions
    function processPayment(data, method) {
        return new Promise(resolve => {
            const delay = method === 'mpesa' ? 3000 : 2000;
            setTimeout(() => {
                resolve({
                    success: true,
                    transaction_id: (method === 'mpesa' ? 'MP' : 'CARD') + Date.now(),
                    message: method === 'mpesa' ? 'M-Pesa payment successful' : 'Card payment successful'
                });
            }, delay);
        });
    }

    function createBooking(data, paymentResult) {
        return fetch('{{ route("bookings.store") }}', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Accept': 'application/json',
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({ ...data, payment_result: paymentResult })
        }).then(res => res.json());
    }

    function showSuccess(result) {
        const alertDiv = document.createElement('div');
        alertDiv.className = 'mb-4 bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded relative';
        alertDiv.innerHTML = `
            <div class="flex">
                <div class="flex-shrink-0">
                    <svg class="h-5 w-5 text-green-400" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                    </svg>
                </div>
                <div class="ml-3">
                    <p class="text-sm font-medium">🎉 Booking Successful!</p>
                    <p class="text-sm">Payment processed successfully. Your booking is confirmed.</p>
                    <p class="text-sm mt-1">Booking ID: #${result.booking.id}</p>
                </div>
            </div>
        `;
        bookingForm.parentNode.insertBefore(alertDiv, bookingForm);
    }

    function showError(message) {
        const alertDiv = document.createElement('div');
        alertDiv.className = 'mb-4 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded relative';
        alertDiv.innerHTML = `
            <div class="flex">
                <div class="flex-shrink-0">
                    <svg class="h-5 w-5 text-red-400" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                    </svg>
                </div>
                <div class="ml-3">
                    <p class="text-sm font-medium">${message}</p>
                </div>
            </div>
        `;
        bookingForm.parentNode.insertBefore(alertDiv, bookingForm);
    }

    function clearAlerts() {
        document.querySelectorAll('.bg-red-50, .bg-green-50').forEach(el => el.remove());
    }

    function loadingButtonHTML() {
        return `
            <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
            </svg>
            Processing Payment...
        `;
    }

});
</script>
@endif

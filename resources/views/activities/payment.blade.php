@extends('layouts.app')

@section('title', 'Payment - ' . $activity->title)

@section('content')
<div class="min-h-screen bg-gradient-to-br from-green-50 via-blue-50 to-purple-50">
    <!-- Hero Header -->
    <section class="relative bg-gradient-to-r from-green-600 via-teal-600 to-blue-700 text-white py-8 overflow-hidden">
        <div class="absolute inset-0 bg-black/10"></div>

        <div class="relative max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center">
                <div class="w-16 h-16 bg-white/20 rounded-full flex items-center justify-center mb-4 backdrop-blur-sm border border-white/30 mx-auto">
                    <i class="fas fa-credit-card text-2xl text-white"></i>
                </div>
                <h1 class="text-3xl md:text-4xl font-bold mb-2 bg-gradient-to-r from-white to-green-100 bg-clip-text text-transparent">
                    Complete Payment
                </h1>
                <p class="text-lg text-green-100">
                    Secure your booking for {{ $activity->title }}
                </p>
            </div>
        </div>
    </section>

    <!-- Payment Section -->
    <section class="py-8">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid lg:grid-cols-3 gap-8">

                <!-- Booking Summary -->
                <div class="lg:col-span-1">
                    <div class="bg-white rounded-xl shadow-md p-6 border border-gray-100 sticky top-8">
                        <h3 class="text-lg font-bold text-gray-900 mb-4">Booking Summary</h3>

                        <div class="space-y-4">
                            <div>
                                <h4 class="font-semibold text-gray-900">{{ $activity->title }}</h4>
                                <p class="text-sm text-gray-600">{{ $activity->location }}</p>
                            </div>

                            <div class="border-t border-gray-200 pt-4">
                                <div class="flex justify-between text-sm">
                                    <span>Date:</span>
                                    <span class="font-medium">{{ date('M j, Y', strtotime($bookingData['date'])) }}</span>
                                </div>
                                <div class="flex justify-between text-sm">
                                    <span>Time:</span>
                                    <span class="font-medium">{{ date('g:i A', strtotime($bookingData['time'])) }}</span>
                                </div>
                                <div class="flex justify-between text-sm">
                                    <span>Participants:</span>
                                    <span class="font-medium">{{ $bookingData['participants'] }} people</span>
                                </div>
                            </div>

                            <div class="border-t border-gray-200 pt-4">
                                <h5 class="font-medium text-gray-900 mb-2">Participants:</h5>
                                @foreach($bookingData['participant_details'] as $participant)
                                    <div class="text-sm text-gray-600 mb-1">
                                        {{ $participant['name'] }} ({{ $participant['age'] }} years)
                                    </div>
                                @endforeach
                            </div>

                            <div class="bg-gradient-to-r from-green-50 to-teal-50 rounded-lg p-4 border-t border-gray-200">
                                <div class="flex justify-between items-center">
                                    <span class="font-semibold text-gray-900">Total Amount</span>
                                    <span class="text-2xl font-bold text-green-600">${{ $bookingData['total_amount'] }}</span>
                                </div>
                                <div class="text-xs text-gray-500 mt-1">
                                    {{ $bookingData['participants'] }} × ${{ $activity->price }}
                                </div>
                            </div>

                            <div class="text-center">
                                <div class="text-sm text-gray-600">Booking Reference</div>
                                <div class="font-mono text-sm font-semibold text-gray-900">{{ $bookingData['booking_reference'] }}</div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Payment Form -->
                <div class="lg:col-span-2">
                    <form action="{{ route('activities.payment.process', $activity->id) }}" method="POST" class="space-y-6">
                        @csrf

                        <!-- Payment Methods -->
                        <div class="bg-white rounded-xl shadow-md p-6 border border-gray-100">
                            <h3 class="text-lg font-bold text-gray-900 mb-6">Select Payment Method</h3>

                            <div class="space-y-4">
                                <!-- M-Pesa -->
                                <div class="relative">
                                    <input type="radio" name="payment_method" value="mpesa" id="mpesa" class="sr-only" checked>
                                    <label for="mpesa" class="flex items-center p-4 border-2 border-green-500 bg-green-50 rounded-lg cursor-pointer transition-all duration-300 payment-option">
                                        <div class="flex items-center">
                                            <div class="w-12 h-12 bg-green-600 rounded-lg flex items-center justify-center mr-4">
                                                <i class="fas fa-mobile-alt text-white text-xl"></i>
                                            </div>
                                            <div>
                                                <div class="font-semibold text-gray-900">M-Pesa</div>
                                                <div class="text-sm text-gray-600">Pay with your mobile money</div>
                                            </div>
                                        </div>
                                        <div class="ml-auto">
                                            <div class="w-6 h-6 border-2 border-green-500 rounded-full flex items-center justify-center">
                                                <div class="w-3 h-3 bg-green-500 rounded-full"></div>
                                            </div>
                                        </div>
                                    </label>

                                    <!-- M-Pesa Phone Number Field -->
                                    <div class="mt-4 ml-16" id="mpesa-details">
                                        <label class="block text-sm font-medium text-gray-700 mb-2">M-Pesa Phone Number</label>
                                        <input type="tel" name="phone_number" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500" placeholder="+254 7XX XXX XXX" required>
                                        <p class="text-xs text-gray-500 mt-1">Enter the phone number registered with M-Pesa</p>
                                    </div>
                                </div>

                                <!-- Credit/Debit Card -->
                                <div class="relative">
                                    <input type="radio" name="payment_method" value="card" id="card" class="sr-only">
                                    <label for="card" class="flex items-center p-4 border-2 border-gray-200 rounded-lg cursor-pointer hover:border-blue-400 transition-all duration-300 payment-option">
                                        <div class="flex items-center">
                                            <div class="w-12 h-12 bg-blue-600 rounded-lg flex items-center justify-center mr-4">
                                                <i class="fas fa-credit-card text-white text-xl"></i>
                                            </div>
                                            <div>
                                                <div class="font-semibold text-gray-900">Credit/Debit Card</div>
                                                <div class="text-sm text-gray-600">Visa, Mastercard, American Express</div>
                                            </div>
                                        </div>
                                        <div class="ml-auto">
                                            <div class="w-6 h-6 border-2 border-gray-300 rounded-full flex items-center justify-center">
                                                <div class="w-3 h-3 bg-transparent rounded-full"></div>
                                            </div>
                                        </div>
                                    </label>
                                </div>

                                <!-- PayPal -->
                                <div class="relative">
                                    <input type="radio" name="payment_method" value="paypal" id="paypal" class="sr-only">
                                    <label for="paypal" class="flex items-center p-4 border-2 border-gray-200 rounded-lg cursor-pointer hover:border-yellow-400 transition-all duration-300 payment-option">
                                        <div class="flex items-center">
                                            <div class="w-12 h-12 bg-yellow-500 rounded-lg flex items-center justify-center mr-4">
                                                <i class="fab fa-paypal text-white text-xl"></i>
                                            </div>
                                            <div>
                                                <div class="font-semibold text-gray-900">PayPal</div>
                                                <div class="text-sm text-gray-600">Pay with your PayPal account</div>
                                            </div>
                                        </div>
                                        <div class="ml-auto">
                                            <div class="w-6 h-6 border-2 border-gray-300 rounded-full flex items-center justify-center">
                                                <div class="w-3 h-3 bg-transparent rounded-full"></div>
                                            </div>
                                        </div>
                                    </label>
                                </div>
                            </div>
                        </div>

                        <!-- Security Notice -->
                        <div class="bg-blue-50 border border-blue-200 rounded-xl p-4">
                            <div class="flex items-center">
                                <i class="fas fa-shield-alt text-blue-600 mr-3"></i>
                                <div>
                                    <h4 class="font-semibold text-blue-900 text-sm">Secure Payment</h4>
                                    <p class="text-blue-700 text-sm">Your payment information is encrypted and secure. We never store your card details.</p>
                                </div>
                            </div>
                        </div>

                        <!-- Payment Button -->
                        <div class="bg-gradient-to-r from-green-600 to-teal-600 rounded-xl shadow-lg p-6 text-white">
                            <button type="submit" class="w-full px-6 py-4 bg-white text-green-600 rounded-lg hover:bg-green-50 transition-all duration-300 font-bold text-lg shadow-lg hover:shadow-xl transform hover:scale-105">
                                <i class="fas fa-lock mr-2"></i>
                                Pay ${{ $bookingData['total_amount'] }} Now
                            </button>

                            <div class="flex justify-center items-center mt-4 space-x-4 text-green-100 text-xs">
                                <div class="flex items-center">
                                    <i class="fas fa-shield-alt mr-1"></i>
                                    <span>SSL Secured</span>
                                </div>
                                <div class="flex items-center">
                                    <i class="fas fa-lock mr-1"></i>
                                    <span>256-bit Encryption</span>
                                </div>
                                <div class="flex items-center">
                                    <i class="fas fa-undo mr-1"></i>
                                    <span>Refund Policy</span>
                                </div>
                            </div>
                        </div>
                    </form>

                    <!-- Back to Booking Button -->
                    <div class="text-center mt-6">
                        <a href="{{ route('activities.book', $activity->id) }}" class="inline-flex items-center px-4 py-2 text-gray-600 hover:text-gray-800 transition-colors">
                            <i class="fas fa-arrow-left mr-2"></i>
                            Back to Booking Details
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const paymentOptions = document.querySelectorAll('input[name="payment_method"]');
    const mpesaDetails = document.getElementById('mpesa-details');

    paymentOptions.forEach(option => {
        option.addEventListener('change', function() {
            // Reset all labels
            document.querySelectorAll('.payment-option').forEach(label => {
                label.classList.remove('border-green-500', 'bg-green-50', 'border-blue-500', 'bg-blue-50', 'border-yellow-500', 'bg-yellow-50');
                label.classList.add('border-gray-200');

                // Reset radio indicators
                const indicator = label.querySelector('.w-6.h-6 > div');
                indicator.classList.remove('bg-green-500', 'bg-blue-500', 'bg-yellow-500');
                indicator.classList.add('bg-transparent');

                const border = label.querySelector('.w-6.h-6');
                border.classList.remove('border-green-500', 'border-blue-500', 'border-yellow-500');
                border.classList.add('border-gray-300');
            });

            // Style selected option
            const selectedLabel = this.closest('.payment-option');
            const indicator = selectedLabel.querySelector('.w-6.h-6 > div');
            const border = selectedLabel.querySelector('.w-6.h-6');

            if (this.value === 'mpesa') {
                selectedLabel.classList.add('border-green-500', 'bg-green-50');
                indicator.classList.add('bg-green-500');
                border.classList.add('border-green-500');
                mpesaDetails.style.display = 'block';
            } else if (this.value === 'card') {
                selectedLabel.classList.add('border-blue-500', 'bg-blue-50');
                indicator.classList.add('bg-blue-500');
                border.classList.add('border-blue-500');
                mpesaDetails.style.display = 'none';
            } else if (this.value === 'paypal') {
                selectedLabel.classList.add('border-yellow-500', 'bg-yellow-50');
                indicator.classList.add('bg-yellow-500');
                border.classList.add('border-yellow-500');
                mpesaDetails.style.display = 'none';
            }

            selectedLabel.classList.remove('border-gray-200');
            border.classList.remove('border-gray-300');
            indicator.classList.remove('bg-transparent');
        });
    });
});
</script>
@endsection

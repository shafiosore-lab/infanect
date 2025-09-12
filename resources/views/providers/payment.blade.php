@extends('layouts.app')

@section('title', 'Payment - Infanect')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-blue-50 via-indigo-50 to-purple-50">
    <!-- Payment Header -->
    <section class="relative bg-gradient-to-r from-blue-600 via-indigo-600 to-purple-700 text-white py-6 overflow-hidden">
        <div class="absolute inset-0 bg-black/10"></div>

        <!-- Security Elements -->
        <div class="absolute top-5 left-5 w-12 h-12 bg-white/10 rounded-full animate-pulse"></div>
        <div class="absolute bottom-5 right-5 w-16 h-16 bg-purple-300/20 rounded-full animate-bounce"></div>

        <div class="relative max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <div class="flex flex-col items-center">
                <div class="w-12 h-12 bg-white/20 rounded-full flex items-center justify-center mb-3 backdrop-blur-sm border border-white/30">
                    <i class="fas fa-credit-card text-lg text-white"></i>
                </div>
                <h1 class="text-2xl md:text-3xl font-bold mb-2 bg-gradient-to-r from-white to-blue-100 bg-clip-text text-transparent">
                    Secure Payment
                </h1>
                <p class="text-sm text-blue-100 max-w-2xl">
                    Complete your booking payment securely
                </p>
            </div>
        </div>
    </section>

    <!-- Payment Section -->
    <section class="py-6 relative">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid lg:grid-cols-3 gap-6">

                <!-- Payment Form -->
                <div class="lg:col-span-2">
                    <form action="{{ route('providers.payment.process', $booking->provider->id) }}" method="POST" class="space-y-4">
                        @csrf

                        <!-- Payment Method -->
                        <div class="bg-white rounded-xl shadow-lg p-6 border border-gray-100">
                            <div class="flex items-center mb-4">
                                <i class="fas fa-credit-card text-blue-600 mr-3"></i>
                                <h2 class="text-xl font-bold text-gray-900">Payment Method</h2>
                                <div class="ml-auto flex space-x-2">
                                    <i class="fab fa-cc-visa text-2xl text-blue-600"></i>
                                    <i class="fab fa-cc-mastercard text-2xl text-red-600"></i>
                                    <i class="fab fa-cc-amex text-2xl text-green-600"></i>
                                </div>
                            </div>

                            <div class="grid grid-cols-1 gap-4">
                                <div>
                                    <label class="block text-sm font-semibold text-gray-700 mb-2">Card Number</label>
                                    <input type="text" name="card_number" placeholder="1234 5678 9012 3456"
                                           class="w-full px-4 py-3 border border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-300" required>
                                </div>

                                <div class="grid grid-cols-3 gap-4">
                                    <div>
                                        <label class="block text-sm font-semibold text-gray-700 mb-2">Month</label>
                                        <select name="expiry_month" class="w-full px-3 py-3 border border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-300" required>
                                            <option value="">MM</option>
                                            @for($i = 1; $i <= 12; $i++)
                                                <option value="{{ sprintf('%02d', $i) }}">{{ sprintf('%02d', $i) }}</option>
                                            @endfor
                                        </select>
                                    </div>
                                    <div>
                                        <label class="block text-sm font-semibold text-gray-700 mb-2">Year</label>
                                        <select name="expiry_year" class="w-full px-3 py-3 border border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-300" required>
                                            <option value="">YYYY</option>
                                            @for($i = date('Y'); $i <= date('Y') + 10; $i++)
                                                <option value="{{ $i }}">{{ $i }}</option>
                                            @endfor
                                        </select>
                                    </div>
                                    <div>
                                        <label class="block text-sm font-semibold text-gray-700 mb-2">CVV</label>
                                        <input type="text" name="cvv" placeholder="123" maxlength="4"
                                               class="w-full px-3 py-3 border border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-300" required>
                                    </div>
                                </div>

                                <div>
                                    <label class="block text-sm font-semibold text-gray-700 mb-2">Cardholder Name</label>
                                    <input type="text" name="card_name" placeholder="John Doe"
                                           class="w-full px-4 py-3 border border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-300" required>
                                </div>
                            </div>
                        </div>

                        <!-- Billing Address -->
                        <div class="bg-white rounded-xl shadow-lg p-6 border border-gray-100">
                            <div class="flex items-center mb-4">
                                <i class="fas fa-map-marker-alt text-blue-600 mr-3"></i>
                                <h2 class="text-xl font-bold text-gray-900">Billing Address</h2>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div class="md:col-span-2">
                                    <label class="block text-sm font-semibold text-gray-700 mb-2">Street Address</label>
                                    <input type="text" name="billing_address" placeholder="123 Main Street"
                                           class="w-full px-4 py-3 border border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-300" required>
                                </div>
                                <div>
                                    <label class="block text-sm font-semibold text-gray-700 mb-2">City</label>
                                    <input type="text" name="billing_city" placeholder="New York"
                                           class="w-full px-4 py-3 border border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-300" required>
                                </div>
                                <div>
                                    <label class="block text-sm font-semibold text-gray-700 mb-2">ZIP Code</label>
                                    <input type="text" name="billing_zip" placeholder="10001"
                                           class="w-full px-4 py-3 border border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-300" required>
                                </div>
                            </div>
                        </div>

                        <!-- Payment Button -->
                        <div class="bg-gradient-to-r from-green-600 to-emerald-600 rounded-xl shadow-lg p-6 text-white">
                            <div class="flex justify-center">
                                <button type="submit" class="px-8 py-4 bg-white text-green-600 rounded-full hover:bg-green-50 transition-all duration-300 font-bold text-lg shadow-xl hover:shadow-2xl transform hover:scale-105 flex items-center">
                                    <i class="fas fa-lock mr-3"></i>
                                    Pay ${{ $booking->provider->price }} Securely
                                </button>
                            </div>
                            <p class="text-center text-green-100 text-sm mt-3">
                                <i class="fas fa-shield-alt mr-1"></i>
                                256-bit SSL encryption • Your payment is completely secure
                            </p>
                        </div>
                    </form>
                </div>

                <!-- Order Summary -->
                <div class="lg:col-span-1">
                    <div class="space-y-4">
                        <!-- Booking Summary -->
                        <div class="bg-white rounded-xl shadow-lg p-6 border border-gray-100">
                            <h3 class="text-lg font-bold text-gray-900 mb-4">Booking Summary</h3>

                            <!-- Provider Info -->
                            <div class="flex items-center mb-4 pb-4 border-b border-gray-100">
                                <div class="w-12 h-12 rounded-full overflow-hidden ring-2 ring-blue-100 mr-4">
                                    <img src="https://images.unsplash.com/photo-{{
                                        collect([
                                            '1559839734-2b71ea197ec2?ixlib=rb-4.0.3&auto=format&fit=crop&w=48&h=48&q=85',
                                            '1594824388511-923247e6e01e?ixlib=rb-4.0.3&auto=format&fit=crop&w=48&h=48&q=85',
                                            '1612349317150-e3d4ac1d0e35?ixlib=rb-4.0.3&auto=format&fit=crop&w=48&h=48&q=85',
                                            '1582750433449-648ed127bb54?ixlib=rb-4.0.3&auto=format&fit=crop&w=48&h=48&q=85'
                                        ])[($booking->provider->id - 1) % 4]
                                    }}" alt="{{ $booking->provider->name }}" class="w-full h-full object-cover">
                                </div>
                                <div>
                                    <h4 class="font-semibold text-gray-900">{{ $booking->provider->name }}</h4>
                                    <p class="text-sm text-blue-600">{{ $booking->provider->title }}</p>
                                </div>
                            </div>

                            <!-- Session Details -->
                            <div class="space-y-3 text-sm">
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Date:</span>
                                    <span class="font-semibold">{{ \Carbon\Carbon::parse($booking->date)->format('M j, Y') }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Time:</span>
                                    <span class="font-semibold">{{ \Carbon\Carbon::parse($booking->time)->format('g:i A') }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Type:</span>
                                    <span class="font-semibold capitalize">{{ $booking->session_type }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Format:</span>
                                    <span class="font-semibold capitalize">{{ $booking->session_format }}</span>
                                </div>
                            </div>

                            <!-- Price Breakdown -->
                            <div class="mt-6 pt-4 border-t border-gray-100">
                                <div class="flex justify-between text-sm mb-2">
                                    <span class="text-gray-600">Session fee:</span>
                                    <span>${{ $booking->provider->price }}</span>
                                </div>
                                <div class="flex justify-between text-sm mb-2">
                                    <span class="text-gray-600">Platform fee:</span>
                                    <span>$5</span>
                                </div>
                                <div class="flex justify-between text-lg font-bold text-gray-900 pt-2 border-t border-gray-200">
                                    <span>Total:</span>
                                    <span>${{ $booking->provider->price + 5 }}</span>
                                </div>
                            </div>
                        </div>

                        <!-- Security Badges -->
                        <div class="bg-gradient-to-r from-green-50 to-emerald-50 rounded-xl p-4 border border-green-100">
                            <div class="text-center">
                                <i class="fas fa-shield-check text-green-600 text-2xl mb-2"></i>
                                <h4 class="font-semibold text-gray-900 mb-2">Secure Payment</h4>
                                <p class="text-xs text-gray-600 leading-relaxed">
                                    Your payment information is encrypted and secure. We never store your card details.
                                </p>
                                <div class="mt-3 flex justify-center space-x-3">
                                    <img src="https://via.placeholder.com/40x25/4CAF50/FFFFFF?text=SSL" alt="SSL" class="rounded">
                                    <img src="https://via.placeholder.com/40x25/2196F3/FFFFFF?text=PCI" alt="PCI" class="rounded">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Format card number input
    const cardNumberInput = document.querySelector('input[name="card_number"]');
    cardNumberInput.addEventListener('input', function(e) {
        let value = e.target.value.replace(/\s+/g, '').replace(/[^0-9]/gi, '');
        let formattedValue = value.match(/.{1,4}/g)?.join(' ') || value;
        e.target.value = formattedValue;
    });

    // CVV input validation
    const cvvInput = document.querySelector('input[name="cvv"]');
    cvvInput.addEventListener('input', function(e) {
        e.target.value = e.target.value.replace(/[^0-9]/g, '');
    });
});
</script>
@endsection

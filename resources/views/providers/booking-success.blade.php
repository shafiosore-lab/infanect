@extends('layouts.app')

@section('title', 'Payment Successful - Infanect')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-green-50 via-emerald-50 to-teal-50">
    <!-- Success Header -->
    <section class="relative bg-gradient-to-r from-green-600 via-emerald-600 to-teal-700 text-white py-8 overflow-hidden">
        <div class="absolute inset-0 bg-black/10"></div>

        <!-- Success Animation Elements -->
        <div class="absolute top-5 left-5 w-16 h-16 bg-white/10 rounded-full animate-bounce"></div>
        <div class="absolute bottom-5 right-5 w-12 h-12 bg-green-300/20 rounded-full animate-pulse"></div>

        <div class="relative max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <div class="flex flex-col items-center">
                <div class="w-20 h-20 bg-white/20 rounded-full flex items-center justify-center mb-4 backdrop-blur-sm border border-white/30 animate-pulse">
                    <i class="fas fa-check text-3xl text-white"></i>
                </div>
                <h1 class="text-3xl md:text-4xl font-bold mb-3 bg-gradient-to-r from-white to-green-100 bg-clip-text text-transparent">
                    Payment Successful!
                </h1>
                <p class="text-lg text-green-100 max-w-2xl">
                    Your booking has been confirmed and payment processed successfully
                </p>

                <!-- Success Badges -->
                <div class="flex space-x-4 mt-6">
                    <div class="bg-white/20 backdrop-blur-sm rounded-full px-4 py-2 border border-white/30">
                        <i class="fas fa-envelope text-white mr-2"></i>
                        <span class="text-sm font-medium">Email Sent</span>
                    </div>
                    <div class="bg-white/20 backdrop-blur-sm rounded-full px-4 py-2 border border-white/30">
                        <i class="fas fa-sms text-white mr-2"></i>
                        <span class="text-sm font-medium">SMS Sent</span>
                    </div>
                    <div class="bg-white/20 backdrop-blur-sm rounded-full px-4 py-2 border border-white/30">
                        <i class="fas fa-receipt text-white mr-2"></i>
                        <span class="text-sm font-medium">Receipt Ready</span>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Success Details -->
    <section class="py-8 relative">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid lg:grid-cols-3 gap-6">

                <!-- Main Content -->
                <div class="lg:col-span-2 space-y-6">

                    <!-- Payment Confirmation -->
                    <div class="bg-white rounded-2xl shadow-lg p-6 border border-gray-100">
                        <div class="flex items-center mb-6">
                            <div class="w-12 h-12 bg-green-600 text-white rounded-full flex items-center justify-center mr-4">
                                <i class="fas fa-credit-card"></i>
                            </div>
                            <div>
                                <h2 class="text-2xl font-bold text-gray-900">Payment Confirmed</h2>
                                <p class="text-green-600 font-medium">Transaction completed successfully</p>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                            <div class="text-center p-4 bg-green-50 rounded-lg border border-green-200">
                                <div class="text-2xl font-bold text-green-600">${{ $booking->provider->price + 5 }}</div>
                                <div class="text-sm text-gray-600">Total Paid</div>
                            </div>
                            <div class="text-center p-4 bg-blue-50 rounded-lg border border-blue-200">
                                <div class="text-lg font-bold text-blue-600">{{ $booking->payment_id ?? 'pay_' . uniqid() }}</div>
                                <div class="text-sm text-gray-600">Transaction ID</div>
                            </div>
                            <div class="text-center p-4 bg-purple-50 rounded-lg border border-purple-200">
                                <div class="text-lg font-bold text-purple-600">{{ now()->format('M j, Y') }}</div>
                                <div class="text-sm text-gray-600">Payment Date</div>
                            </div>
                        </div>

                        <!-- Download Receipt -->
                        <div class="text-center">
                            <a href="{{ route('booking.receipt', $booking->id) }}"
                               class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-blue-600 to-blue-700 text-white rounded-full hover:from-blue-700 hover:to-blue-800 transition-all duration-300 font-medium shadow-lg hover:shadow-xl transform hover:scale-105">
                                <i class="fas fa-download mr-2"></i>
                                Download PDF Receipt
                            </a>
                        </div>
                    </div>

                    <!-- Confirmation Notifications -->
                    <div class="bg-white rounded-2xl shadow-lg p-6 border border-gray-100">
                        <h3 class="text-xl font-bold text-gray-900 mb-4 flex items-center">
                            <i class="fas fa-bell text-green-600 mr-3"></i>
                            Confirmations Sent
                        </h3>

                        <div class="space-y-4">
                            <!-- Email Confirmation -->
                            <div class="flex items-start p-4 bg-blue-50 rounded-lg border border-blue-200">
                                <div class="w-10 h-10 bg-blue-600 text-white rounded-full flex items-center justify-center mr-4 mt-0.5">
                                    <i class="fas fa-envelope"></i>
                                </div>
                                <div class="flex-1">
                                    <h4 class="font-semibold text-gray-900 mb-1">Email Confirmation Sent</h4>
                                    <p class="text-sm text-gray-600 mb-2">
                                        A detailed confirmation email has been sent to <strong>{{ $booking->email }}</strong>
                                    </p>
                                    <div class="text-xs text-blue-600 font-medium">
                                        <i class="fas fa-check-circle mr-1"></i>
                                        Delivered {{ now()->diffForHumans() }}
                                    </div>
                                </div>
                            </div>

                            <!-- SMS Confirmation -->
                            <div class="flex items-start p-4 bg-green-50 rounded-lg border border-green-200">
                                <div class="w-10 h-10 bg-green-600 text-white rounded-full flex items-center justify-center mr-4 mt-0.5">
                                    <i class="fas fa-sms"></i>
                                </div>
                                <div class="flex-1">
                                    <h4 class="font-semibold text-gray-900 mb-1">SMS Confirmation Sent</h4>
                                    <p class="text-sm text-gray-600 mb-2">
                                        A booking reminder SMS has been sent to <strong>{{ $booking->phone }}</strong>
                                    </p>
                                    <div class="text-xs text-green-600 font-medium">
                                        <i class="fas fa-check-circle mr-1"></i>
                                        Delivered {{ now()->diffForHumans() }}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- What's Next -->
                    <div class="bg-white rounded-2xl shadow-lg p-6 border border-gray-100">
                        <h3 class="text-xl font-bold text-gray-900 mb-4 flex items-center">
                            <i class="fas fa-list-check text-blue-600 mr-3"></i>
                            What Happens Next?
                        </h3>

                        <div class="space-y-4">
                            <div class="flex items-start">
                                <div class="w-8 h-8 bg-blue-100 text-blue-600 rounded-full flex items-center justify-center mr-4 mt-0.5">
                                    <span class="font-bold text-sm">1</span>
                                </div>
                                <div>
                                    <h4 class="font-semibold text-gray-900">Provider Notification</h4>
                                    <p class="text-gray-600 text-sm">{{ $booking->provider->name }} has been notified and will contact you within 24 hours.</p>
                                </div>
                            </div>

                            <div class="flex items-start">
                                <div class="w-8 h-8 bg-blue-100 text-blue-600 rounded-full flex items-center justify-center mr-4 mt-0.5">
                                    <span class="font-bold text-sm">2</span>
                                </div>
                                <div>
                                    <h4 class="font-semibold text-gray-900">Session Preparation</h4>
                                    <p class="text-gray-600 text-sm">
                                        @if($booking->session_format === 'online')
                                            You'll receive a secure video link 30 minutes before your session.
                                        @else
                                            Address and parking information will be provided in your confirmation email.
                                        @endif
                                    </p>
                                </div>
                            </div>

                            <div class="flex items-start">
                                <div class="w-8 h-8 bg-blue-100 text-blue-600 rounded-full flex items-center justify-center mr-4 mt-0.5">
                                    <span class="font-bold text-sm">3</span>
                                </div>
                                <div>
                                    <h4 class="font-semibold text-gray-900">Session Reminder</h4>
                                    <p class="text-gray-600 text-sm">We'll send you a reminder 24 hours and 1 hour before your session.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Sidebar -->
                <div class="lg:col-span-1 space-y-6">

                    <!-- Session Summary -->
                    <div class="bg-white rounded-2xl shadow-lg p-6 border border-gray-100">
                        <h3 class="text-lg font-bold text-gray-900 mb-4">Your Session</h3>

                        <!-- Provider Info -->
                        <div class="text-center mb-6">
                            <div class="w-16 h-16 mx-auto mb-3 rounded-full overflow-hidden ring-4 ring-green-100">
                                <img src="https://images.unsplash.com/photo-{{
                                    collect([
                                        '1559839734-2b71ea197ec2?ixlib=rb-4.0.3&auto=format&fit=crop&w=64&h=64&q=85',
                                        '1594824388511-923247e6e01e?ixlib=rb-4.0.3&auto=format&fit=crop&w=64&h=64&q=85',
                                        '1612349317150-e3d4ac1d0e35?ixlib=rb-4.0.3&auto=format&fit=crop&w=64&h=64&q=85',
                                        '1582750433449-648ed127bb54?ixlib=rb-4.0.3&auto=format&fit=crop&w=64&h=64&q=85'
                                    ])[($booking->provider->id - 1) % 4]
                                }}" alt="{{ $booking->provider->name }}" class="w-full h-full object-cover">
                            </div>
                            <h4 class="font-bold text-gray-900">{{ $booking->provider->name }}</h4>
                            <p class="text-sm text-blue-600">{{ $booking->provider->title }}</p>
                        </div>

                        <!-- Session Details -->
                        <div class="space-y-3 text-sm">
                            <div class="flex justify-between">
                                <span class="text-gray-600">Date:</span>
                                <span class="font-semibold">{{ \Carbon\Carbon::parse($booking->date)->format('F j, Y') }}</span>
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
                            <div class="flex justify-between">
                                <span class="text-gray-600">Booking ID:</span>
                                <span class="font-mono text-xs">{{ $booking->id }}</span>
                            </div>
                        </div>
                    </div>

                    <!-- Quick Actions -->
                    <div class="bg-white rounded-2xl shadow-lg p-6 border border-gray-100 space-y-4">
                        <h4 class="font-semibold text-gray-900">Quick Actions</h4>

                        <button class="w-full px-4 py-3 bg-gradient-to-r from-green-600 to-green-700 text-white rounded-full hover:from-green-700 hover:to-green-800 transition-all duration-300 font-medium shadow-lg hover:shadow-xl transform hover:scale-105">
                            <i class="fas fa-calendar-plus mr-2"></i>
                            Add to Calendar
                        </button>

                        <button class="w-full px-4 py-3 bg-blue-100 text-blue-700 rounded-full hover:bg-blue-200 transition-colors font-medium">
                            <i class="fas fa-share mr-2"></i>
                            Share Booking
                        </button>

                        <a href="{{ route('providers.index') }}" class="block w-full px-4 py-3 bg-white text-gray-700 border-2 border-gray-200 rounded-full hover:bg-gray-50 transition-colors font-medium text-center">
                            <i class="fas fa-arrow-left mr-2"></i>
                            Back to Providers
                        </a>
                    </div>

                    <!-- Support Contact -->
                    <div class="bg-gradient-to-r from-purple-50 to-blue-50 rounded-2xl p-6 border border-purple-100">
                        <div class="text-center">
                            <i class="fas fa-headset text-purple-600 text-2xl mb-3"></i>
                            <h4 class="font-semibold text-gray-900 mb-2">Need Help?</h4>
                            <p class="text-sm text-gray-600 mb-4">Questions about your booking? We're here to help 24/7</p>
                            <button class="px-4 py-2 bg-purple-600 text-white rounded-full hover:bg-purple-700 transition-colors text-sm font-medium">
                                <i class="fas fa-comments mr-2"></i>
                                Contact Support
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
@endsection

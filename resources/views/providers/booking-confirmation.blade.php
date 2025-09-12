@extends('layouts.app')

@section('title', 'Booking Confirmed - Infanect')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-green-50 via-blue-50 to-indigo-50">
    <!-- Success Header -->
    <section class="relative bg-gradient-to-r from-green-600 via-emerald-600 to-green-700 text-white py-8 overflow-hidden">
        <div class="absolute inset-0 bg-black/10"></div>

        <!-- Success Animation Elements -->
        <div class="absolute top-5 left-5 w-16 h-16 bg-white/10 rounded-full animate-bounce"></div>
        <div class="absolute bottom-5 right-5 w-12 h-12 bg-green-300/20 rounded-full animate-pulse"></div>

        <div class="relative max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <div class="flex flex-col items-center">
                <div class="w-16 h-16 bg-white/20 rounded-full flex items-center justify-center mb-4 backdrop-blur-sm border border-white/30 animate-pulse">
                    <i class="fas fa-check text-2xl text-white"></i>
                </div>
                <h1 class="text-3xl md:text-4xl font-bold mb-3 bg-gradient-to-r from-white to-green-100 bg-clip-text text-transparent">
                    Booking Confirmed!
                </h1>
                <p class="text-lg text-green-100 max-w-2xl">
                    Your session with {{ $booking->provider->name }} has been successfully scheduled
                </p>
            </div>
        </div>
    </section>

    <!-- Confirmation Details -->
    <section class="py-8 relative">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid lg:grid-cols-3 gap-6">

                <!-- Booking Details Card -->
                <div class="lg:col-span-2 space-y-6">
                    <!-- Session Information -->
                    <div class="bg-white rounded-2xl shadow-lg p-6 border border-gray-100">
                        <div class="flex items-center mb-4">
                            <div class="w-10 h-10 bg-blue-600 text-white rounded-full flex items-center justify-center mr-4">
                                <i class="fas fa-calendar-check"></i>
                            </div>
                            <h2 class="text-2xl font-bold text-gray-900">Session Details</h2>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="space-y-4">
                                <div>
                                    <label class="text-sm font-semibold text-gray-600">Date & Time</label>
                                    <p class="text-lg font-bold text-gray-900">
                                        {{ \Carbon\Carbon::parse($booking->date)->format('F j, Y') }}
                                        <span class="text-blue-600">at {{ \Carbon\Carbon::parse($booking->time)->format('g:i A') }}</span>
                                    </p>
                                </div>

                                <div>
                                    <label class="text-sm font-semibold text-gray-600">Session Type</label>
                                    <p class="text-lg text-gray-900 capitalize">{{ $booking->session_type }} Session</p>
                                </div>

                                <div>
                                    <label class="text-sm font-semibold text-gray-600">Format</label>
                                    <p class="text-lg text-gray-900 capitalize">
                                        {{ $booking->session_format }}
                                        @if($booking->session_format === 'online')
                                            <i class="fas fa-video text-blue-500 ml-2"></i>
                                        @else
                                            <i class="fas fa-map-marker-alt text-purple-500 ml-2"></i>
                                        @endif
                                    </p>
                                </div>
                            </div>

                            <div class="space-y-4">
                                <div>
                                    <label class="text-sm font-semibold text-gray-600">Booking ID</label>
                                    <p class="text-lg font-mono text-gray-900">{{ $booking->id }}</p>
                                </div>

                                <div>
                                    <label class="text-sm font-semibold text-gray-600">Session Fee</label>
                                    <p class="text-2xl font-bold text-blue-600">${{ $booking->provider->price }}</p>
                                </div>

                                <div>
                                    <label class="text-sm font-semibold text-gray-600">Status</label>
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800">
                                        <i class="fas fa-check-circle mr-1"></i>
                                        Confirmed
                                    </span>
                                </div>
                            </div>
                        </div>

                        @if($booking->notes)
                        <div class="mt-6 p-4 bg-blue-50 rounded-lg border border-blue-200">
                            <label class="text-sm font-semibold text-blue-700 block mb-1">Your Notes</label>
                            <p class="text-gray-700">{{ $booking->notes }}</p>
                        </div>
                        @endif
                    </div>

                    <!-- What's Next -->
                    <div class="bg-white rounded-2xl shadow-lg p-6 border border-gray-100">
                        <h3 class="text-xl font-bold text-gray-900 mb-4 flex items-center">
                            <i class="fas fa-list-check text-blue-600 mr-3"></i>
                            What's Next?
                        </h3>

                        <div class="space-y-4">
                            <div class="flex items-start">
                                <div class="w-8 h-8 bg-blue-100 text-blue-600 rounded-full flex items-center justify-center mr-4 mt-0.5">
                                    <span class="font-bold text-sm">1</span>
                                </div>
                                <div>
                                    <h4 class="font-semibold text-gray-900">Confirmation Email</h4>
                                    <p class="text-gray-600 text-sm">You'll receive a detailed confirmation email at <strong>{{ $booking->email }}</strong> within 5 minutes.</p>
                                </div>
                            </div>

                            <div class="flex items-start">
                                <div class="w-8 h-8 bg-blue-100 text-blue-600 rounded-full flex items-center justify-center mr-4 mt-0.5">
                                    <span class="font-bold text-sm">2</span>
                                </div>
                                <div>
                                    <h4 class="font-semibold text-gray-900">Provider Contact</h4>
                                    <p class="text-gray-600 text-sm">{{ $booking->provider->name }} will contact you 24 hours before your session to confirm details.</p>
                                </div>
                            </div>

                            <div class="flex items-start">
                                <div class="w-8 h-8 bg-blue-100 text-blue-600 rounded-full flex items-center justify-center mr-4 mt-0.5">
                                    <span class="font-bold text-sm">3</span>
                                </div>
                                <div>
                                    <h4 class="font-semibold text-gray-900">Session Preparation</h4>
                                    <p class="text-gray-600 text-sm">
                                        @if($booking->session_format === 'online')
                                            You'll receive a secure video link 30 minutes before your session.
                                        @else
                                            Address and parking information will be sent in your confirmation email.
                                        @endif
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Provider & Actions Card -->
                <div class="lg:col-span-1 space-y-6">
                    <!-- Provider Info -->
                    <div class="bg-white rounded-2xl shadow-lg p-6 border border-gray-100">
                        <div class="text-center mb-6">
                            <div class="w-20 h-20 mx-auto mb-4 rounded-full overflow-hidden ring-4 ring-blue-100">
                                <img src="https://images.unsplash.com/photo-{{
                                    collect([
                                        '1559839734-2b71ea197ec2?ixlib=rb-4.0.3&auto=format&fit=crop&w=80&h=80&q=85',
                                        '1594824388511-923247e6e01e?ixlib=rb-4.0.3&auto=format&fit=crop&w=80&h=80&q=85',
                                        '1612349317150-e3d4ac1d0e35?ixlib=rb-4.0.3&auto=format&fit=crop&w=80&h=80&q=85',
                                        '1582750433449-648ed127bb54?ixlib=rb-4.0.3&auto=format&fit=crop&w=80&h=80&q=85'
                                    ])[($booking->provider->id - 1) % 4]
                                }}" alt="{{ $booking->provider->name }}" class="w-full h-full object-cover">
                            </div>
                            <h3 class="text-xl font-bold text-gray-900 mb-2">{{ $booking->provider->name }}</h3>
                            <p class="text-blue-600 font-medium">{{ $booking->provider->title }}</p>
                        </div>

                        <div class="space-y-3 text-sm">
                            <div class="flex items-center justify-between">
                                <span class="text-gray-600">Email:</span>
                                <span class="text-gray-900">{{ $booking->provider->email }}</span>
                            </div>
                            <div class="flex items-center justify-between">
                                <span class="text-gray-600">Phone:</span>
                                <span class="text-gray-900">{{ $booking->provider->phone }}</span>
                            </div>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="bg-white rounded-2xl shadow-lg p-6 border border-gray-100 space-y-4">
                        <h4 class="font-semibold text-gray-900 mb-4">Quick Actions</h4>

                        <button class="w-full px-4 py-3 bg-gradient-to-r from-blue-600 to-blue-700 text-white rounded-full hover:from-blue-700 hover:to-blue-800 transition-all duration-300 font-medium shadow-lg hover:shadow-xl transform hover:scale-105">
                            <i class="fas fa-calendar-plus mr-2"></i>
                            Add to Calendar
                        </button>

                        <button class="w-full px-4 py-3 bg-gray-100 text-gray-700 rounded-full hover:bg-gray-200 transition-colors font-medium">
                            <i class="fas fa-download mr-2"></i>
                            Download Confirmation
                        </button>

                        <a href="{{ route('providers.index') }}" class="block w-full px-4 py-3 bg-white text-blue-600 border-2 border-blue-200 rounded-full hover:bg-blue-50 transition-colors font-medium text-center">
                            <i class="fas fa-arrow-left mr-2"></i>
                            Back to Providers
                        </a>
                    </div>

                    <!-- Support -->
                    <div class="bg-gradient-to-r from-purple-50 to-blue-50 rounded-2xl p-6 border border-purple-100">
                        <div class="text-center">
                            <i class="fas fa-headset text-purple-600 text-2xl mb-3"></i>
                            <h4 class="font-semibold text-gray-900 mb-2">Need Help?</h4>
                            <p class="text-sm text-gray-600 mb-4">Our support team is here to assist you 24/7</p>
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

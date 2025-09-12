@extends('layouts.app')

@section('title', 'Booking Details - ' . $booking->reference)

@section('content')
<div class="min-h-screen bg-gradient-to-br from-green-50 via-blue-50 to-purple-50">
    <!-- Hero Header -->
    <section class="relative bg-gradient-to-r from-green-600 via-teal-600 to-blue-700 text-white py-8 overflow-hidden">
        <div class="absolute inset-0 bg-black/10"></div>

        <div class="relative max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Back Button -->
            <div class="flex justify-start mb-4">
                <a href="{{ route('activities.my-bookings') }}" class="inline-flex items-center px-4 py-2 bg-white/20 backdrop-blur-sm text-white rounded-full hover:bg-white/30 transition-all duration-300 border border-white/20 text-sm">
                    <i class="fas fa-arrow-left mr-2"></i>
                    <span class="font-medium">Back to My Bookings</span>
                </a>
            </div>

            <div class="text-center">
                <div class="w-16 h-16 bg-white/20 rounded-full flex items-center justify-center mb-4 backdrop-blur-sm border border-white/30 mx-auto">
                    @switch($booking->status)
                        @case('confirmed')
                            <i class="fas fa-check-circle text-2xl text-white"></i>
                            @break
                        @case('completed')
                            <i class="fas fa-flag-checkered text-2xl text-white"></i>
                            @break
                        @case('pending')
                            <i class="fas fa-clock text-2xl text-white"></i>
                            @break
                        @default
                            <i class="fas fa-calendar-check text-2xl text-white"></i>
                    @endswitch
                </div>
                <h1 class="text-3xl md:text-4xl font-bold mb-2 bg-gradient-to-r from-white to-green-100 bg-clip-text text-transparent">
                    Booking Details
                </h1>
                <p class="text-lg text-green-100 mb-4">
                    {{ $booking->activity_title }}
                </p>

                <!-- Status & Reference -->
                <div class="flex justify-center items-center gap-4 mb-4">
                    <span class="px-4 py-2 rounded-full text-sm font-medium bg-white/20 backdrop-blur-sm border border-white/20">
                        Reference: {{ $booking->reference }}
                    </span>
                    <span class="px-4 py-2 rounded-full text-sm font-medium
                        @switch($booking->status)
                            @case('confirmed')
                                bg-green-500/20 text-green-100 border border-green-400/30
                                @break
                            @case('completed')
                                bg-blue-500/20 text-blue-100 border border-blue-400/30
                                @break
                            @case('pending')
                                bg-yellow-500/20 text-yellow-100 border border-yellow-400/30
                                @break
                            @default
                                bg-white/20 text-white border border-white/20
                        @endswitch
                    ">
                        @switch($booking->status)
                            @case('confirmed')
                                <i class="fas fa-check-circle mr-1"></i>Confirmed
                                @break
                            @case('completed')
                                <i class="fas fa-flag-checkered mr-1"></i>Completed
                                @break
                            @case('pending')
                                <i class="fas fa-clock mr-1"></i>Pending Confirmation
                                @break
                        @endswitch
                    </span>
                </div>
            </div>
        </div>
    </section>

    <!-- Booking Details Section -->
    <section class="py-8">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid lg:grid-cols-3 gap-8">

                <!-- Main Details -->
                <div class="lg:col-span-2 space-y-6">

                    <!-- Activity Information -->
                    <div class="bg-white rounded-xl shadow-md p-6 border border-gray-100">
                        <div class="flex items-center mb-4">
                            <div class="w-10 h-10 rounded-lg flex items-center justify-center mr-3
                                @switch($booking->activity_category)
                                    @case('outdoor')
                                        bg-green-100 text-green-600
                                        @break
                                    @case('creative')
                                        bg-purple-100 text-purple-600
                                        @break
                                    @case('sports')
                                        bg-blue-100 text-blue-600
                                        @break
                                    @case('educational')
                                        bg-orange-100 text-orange-600
                                        @break
                                    @default
                                        bg-gray-100 text-gray-600
                                @endswitch
                            ">
                                @switch($booking->activity_category)
                                    @case('outdoor')
                                        <i class="fas fa-tree"></i>
                                        @break
                                    @case('creative')
                                        <i class="fas fa-palette"></i>
                                        @break
                                    @case('sports')
                                        <i class="fas fa-futbol"></i>
                                        @break
                                    @case('educational')
                                        <i class="fas fa-graduation-cap"></i>
                                        @break
                                    @default
                                        <i class="fas fa-puzzle-piece"></i>
                                @endswitch
                            </div>
                            <h2 class="text-xl font-bold text-gray-900">Activity Information</h2>
                        </div>

                        <div class="space-y-4">
                            <div>
                                <h3 class="font-bold text-lg text-gray-900 mb-2">{{ $booking->activity_title }}</h3>
                                <div class="flex items-center text-gray-600 mb-2">
                                    <i class="fas fa-map-marker-alt mr-2 text-red-400"></i>
                                    <span>{{ $booking->activity_location }}</span>
                                </div>
                                <div class="flex items-center gap-6 text-sm text-gray-600">
                                    <div class="flex items-center">
                                        <i class="fas fa-calendar mr-2 text-blue-500"></i>
                                        <span>{{ date('l, F j, Y', strtotime($booking->date)) }}</span>
                                    </div>
                                    <div class="flex items-center">
                                        <i class="fas fa-clock mr-2 text-green-500"></i>
                                        <span>{{ date('g:i A', strtotime($booking->time)) }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Participant Details -->
                    <div class="bg-white rounded-xl shadow-md p-6 border border-gray-100">
                        <div class="flex items-center mb-4">
                            <div class="w-10 h-10 bg-purple-100 rounded-lg flex items-center justify-center mr-3">
                                <i class="fas fa-users text-purple-600"></i>
                            </div>
                            <h2 class="text-xl font-bold text-gray-900">Participants ({{ $booking->participants }})</h2>
                        </div>

                        <div class="grid gap-4">
                            @foreach($booking->participant_details as $index => $participant)
                                <div class="border border-gray-200 rounded-lg p-4 bg-gray-50">
                                    <div class="flex items-center justify-between">
                                        <div>
                                            <h4 class="font-semibold text-gray-900">{{ $participant['name'] }}</h4>
                                            <div class="flex items-center gap-4 text-sm text-gray-600 mt-1">
                                                <span>{{ $participant['age'] }} years old</span>
                                                <span class="px-2 py-1 bg-blue-100 text-blue-700 rounded-full text-xs font-medium">
                                                    {{ ucfirst(str_replace('_', ' ', $participant['age_group'])) }}
                                                </span>
                                            </div>
                                        </div>
                                        <div class="text-right">
                                            <div class="text-sm text-gray-500">Participant {{ $index + 1 }}</div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <!-- Special Requirements -->
                    @if($booking->special_requirements)
                        <div class="bg-yellow-50 border border-yellow-200 rounded-xl p-6">
                            <div class="flex items-center mb-3">
                                <div class="w-10 h-10 bg-yellow-100 rounded-lg flex items-center justify-center mr-3">
                                    <i class="fas fa-exclamation-triangle text-yellow-600"></i>
                                </div>
                                <h3 class="text-lg font-bold text-yellow-900">Special Requirements</h3>
                            </div>
                            <p class="text-yellow-800">{{ $booking->special_requirements }}</p>
                        </div>
                    @endif

                    <!-- Contact Information -->
                    <div class="bg-white rounded-xl shadow-md p-6 border border-gray-100">
                        <div class="flex items-center mb-4">
                            <div class="w-10 h-10 bg-gray-100 rounded-lg flex items-center justify-center mr-3">
                                <i class="fas fa-address-card text-gray-600"></i>
                            </div>
                            <h2 class="text-xl font-bold text-gray-900">Contact Information</h2>
                        </div>

                        <div class="grid md:grid-cols-2 gap-4">
                            <div>
                                <label class="text-sm font-medium text-gray-500">Email</label>
                                <div class="flex items-center mt-1">
                                    <i class="fas fa-envelope text-gray-400 mr-2"></i>
                                    <span class="text-gray-900">{{ $booking->email }}</span>
                                </div>
                            </div>
                            <div>
                                <label class="text-sm font-medium text-gray-500">Phone</label>
                                <div class="flex items-center mt-1">
                                    <i class="fas fa-phone text-gray-400 mr-2"></i>
                                    <span class="text-gray-900">{{ $booking->phone }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Sidebar -->
                <div class="lg:col-span-1 space-y-6">

                    <!-- Payment Summary -->
                    <div class="bg-white rounded-xl shadow-md p-6 border border-gray-100 sticky top-8">
                        <h3 class="text-lg font-bold text-gray-900 mb-4">Payment Summary</h3>

                        <div class="space-y-3">
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-600">Participants:</span>
                                <span class="font-medium">{{ $booking->participants }} people</span>
                            </div>
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-600">Price per person:</span>
                                <span class="font-medium">${{ number_format($booking->total_amount / $booking->participants, 2) }}</span>
                            </div>
                            <div class="border-t border-gray-200 pt-3">
                                <div class="flex justify-between items-center">
                                    <span class="font-semibold text-gray-900">Total Amount:</span>
                                    <span class="text-2xl font-bold text-green-600">${{ $booking->total_amount }}</span>
                                </div>
                            </div>
                        </div>

                        <!-- Payment Status -->
                        <div class="mt-4 p-3 rounded-lg
                            @if($booking->payment_status === 'paid')
                                bg-green-50 border border-green-200
                            @else
                                bg-yellow-50 border border-yellow-200
                            @endif
                        ">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center">
                                    @if($booking->payment_status === 'paid')
                                        <i class="fas fa-check-circle text-green-600 mr-2"></i>
                                        <span class="text-green-800 font-medium">Payment Confirmed</span>
                                    @else
                                        <i class="fas fa-clock text-yellow-600 mr-2"></i>
                                        <span class="text-yellow-800 font-medium">Payment Pending</span>
                                    @endif
                                </div>
                            </div>
                            <div class="text-xs mt-1 @if($booking->payment_status === 'paid') text-green-600 @else text-yellow-600 @endif">
                                Paid via {{ ucfirst($booking->payment_method) }}
                            </div>
                        </div>

                        <!-- Booking Date -->
                        <div class="mt-4 pt-4 border-t border-gray-200">
                            <div class="text-sm text-gray-500">Booked on</div>
                            <div class="font-medium text-gray-900">
                                {{ date('M j, Y \a\t g:i A', strtotime($booking->booked_at)) }}
                            </div>
                        </div>
                    </div>

                    <!-- Quick Actions -->
                    <div class="bg-white rounded-xl shadow-md p-6 border border-gray-100">
                        <h3 class="text-lg font-bold text-gray-900 mb-4">Quick Actions</h3>

                        <div class="space-y-3">
                            <button onclick="window.print()" class="w-full px-4 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors font-medium text-sm">
                                <i class="fas fa-print mr-2"></i>
                                Print Booking Details
                            </button>

                            <a href="mailto:{{ $booking->email }}?subject=Activity Booking - {{ $booking->reference }}"
                               class="w-full px-4 py-3 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors font-medium text-sm text-center block">
                                <i class="fas fa-envelope mr-2"></i>
                                Send Email Copy
                            </a>

                            @if($booking->status === 'confirmed' && strtotime($booking->date) > time())
                                <button class="w-full px-4 py-3 bg-red-100 text-red-600 rounded-lg hover:bg-red-200 transition-colors font-medium text-sm">
                                    <i class="fas fa-times-circle mr-2"></i>
                                    Cancel Booking
                                </button>
                            @endif
                        </div>
                    </div>

                    <!-- Support -->
                    <div class="bg-gray-50 border border-gray-200 rounded-xl p-6">
                        <div class="flex items-center mb-3">
                            <div class="w-8 h-8 bg-gray-100 rounded-lg flex items-center justify-center mr-3">
                                <i class="fas fa-headset text-gray-600 text-sm"></i>
                            </div>
                            <h3 class="font-bold text-gray-900">Need Help?</h3>
                        </div>

                        <div class="space-y-2 text-sm text-gray-600">
                            <div class="flex items-center">
                                <i class="fas fa-envelope text-gray-400 mr-2 text-xs"></i>
                                <span>support@infanect.com</span>
                            </div>
                            <div class="flex items-center">
                                <i class="fas fa-phone text-gray-400 mr-2 text-xs"></i>
                                <span>+254 700 000 000</span>
                            </div>
                            <div class="text-xs text-gray-500 mt-2">
                                Available 24/7 for assistance
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<!-- Print Styles -->
<style>
@media print {
    .no-print {
        display: none !important;
    }

    body {
        background: white !important;
    }

    .bg-gradient-to-r {
        background: #059669 !important;
        color: white !important;
    }

    .shadow-md, .shadow-lg {
        box-shadow: none !important;
        border: 1px solid #e5e7eb !important;
    }
}
</style>
@endsection

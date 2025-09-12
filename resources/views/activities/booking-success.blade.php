@extends('layouts.app')

@section('title', 'Booking Confirmed - ' . $activity->title)

@section('content')
<div class="min-h-screen bg-gradient-to-br from-green-50 via-blue-50 to-purple-50">
    <!-- Success Hero -->
    <section class="relative bg-gradient-to-r from-green-600 via-teal-600 to-blue-700 text-white py-16 overflow-hidden">
        <div class="absolute inset-0 bg-black/10"></div>

        <!-- Floating Success Elements -->
        <div class="absolute top-8 left-8 w-16 h-16 bg-white/10 rounded-full blur-lg animate-pulse"></div>
        <div class="absolute bottom-8 right-8 w-20 h-20 bg-green-200/10 rounded-full blur-xl animate-bounce"></div>

        <div class="relative max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <div class="w-24 h-24 bg-white/20 rounded-full flex items-center justify-center mb-6 backdrop-blur-sm border border-white/30 mx-auto">
                <i class="fas fa-check text-4xl text-white"></i>
            </div>
            <h1 class="text-4xl md:text-5xl font-bold mb-4 bg-gradient-to-r from-white to-green-100 bg-clip-text text-transparent">
                Booking Confirmed!
            </h1>
            <p class="text-xl text-green-100 max-w-2xl mx-auto mb-8">
                Your activity booking has been successfully processed. Get ready for an amazing experience!
            </p>

            <!-- Booking Reference -->
            <div class="bg-white/10 backdrop-blur-sm rounded-xl p-6 border border-white/20 mb-6 max-w-md mx-auto">
                <div class="text-green-100 text-sm mb-2">Booking Reference</div>
                <div class="font-mono text-2xl font-bold text-white">{{ $reference }}</div>
            </div>
        </div>
    </section>

    <!-- Booking Details -->
    <section class="py-12">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid lg:grid-cols-2 gap-8">

                <!-- Activity Details -->
                <div class="bg-white rounded-xl shadow-lg p-8 border border-gray-100">
                    <div class="flex items-center mb-6">
                        <div class="w-12 h-12 bg-green-100 rounded-xl flex items-center justify-center mr-4">
                            <i class="fas fa-calendar-check text-green-600 text-xl"></i>
                        </div>
                        <h2 class="text-2xl font-bold text-gray-900">Activity Details</h2>
                    </div>

                    <div class="space-y-4">
                        <div>
                            <h3 class="font-bold text-lg text-gray-900 mb-2">{{ $activity->title }}</h3>
                            <p class="text-gray-600">{{ $activity->description }}</p>
                        </div>

                        <div class="grid grid-cols-2 gap-4 py-4 border-t border-gray-200">
                            <div>
                                <div class="text-sm font-medium text-gray-500">Duration</div>
                                <div class="font-semibold text-gray-900">{{ $activity->duration }}</div>
                            </div>
                            <div>
                                <div class="text-sm font-medium text-gray-500">Location</div>
                                <div class="font-semibold text-gray-900">{{ $activity->location }}</div>
                            </div>
                        </div>

                        <div class="border-t border-gray-200 pt-4">
                            <h4 class="font-semibold text-gray-900 mb-3">What's Included:</h4>
                            <ul class="space-y-2">
                                @foreach($activity->includes as $item)
                                    <li class="flex items-center text-sm text-gray-600">
                                        <i class="fas fa-check-circle text-green-500 mr-3"></i>
                                        {{ $item }}
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>

                <!-- Next Steps -->
                <div class="space-y-6">
                    <!-- What's Next -->
                    <div class="bg-blue-50 border border-blue-200 rounded-xl p-6">
                        <div class="flex items-center mb-4">
                            <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center mr-3">
                                <i class="fas fa-info-circle text-blue-600"></i>
                            </div>
                            <h3 class="font-bold text-blue-900">What Happens Next?</h3>
                        </div>

                        <ul class="space-y-3 text-sm text-blue-800">
                            <li class="flex items-start">
                                <i class="fas fa-envelope text-blue-600 mr-3 mt-0.5 text-xs"></i>
                                <span>You'll receive a confirmation email within 5 minutes with your booking details</span>
                            </li>
                            <li class="flex items-start">
                                <i class="fas fa-phone text-blue-600 mr-3 mt-0.5 text-xs"></i>
                                <span>Our team will contact you 24 hours before your activity to confirm details</span>
                            </li>
                            <li class="flex items-start">
                                <i class="fas fa-map-marked-alt text-blue-600 mr-3 mt-0.5 text-xs"></i>
                                <span>Detailed location and arrival instructions will be sent to you</span>
                            </li>
                        </ul>
                    </div>

                    <!-- Important Reminders -->
                    <div class="bg-yellow-50 border border-yellow-200 rounded-xl p-6">
                        <div class="flex items-center mb-4">
                            <div class="w-10 h-10 bg-yellow-100 rounded-lg flex items-center justify-center mr-3">
                                <i class="fas fa-exclamation-triangle text-yellow-600"></i>
                            </div>
                            <h3 class="font-bold text-yellow-900">Important Reminders</h3>
                        </div>

                        <ul class="space-y-3 text-sm text-yellow-800">
                            @foreach($activity->requirements as $requirement)
                                <li class="flex items-start">
                                    <i class="fas fa-circle text-yellow-600 mr-3 mt-1.5 text-xs"></i>
                                    <span>{{ $requirement }}</span>
                                </li>
                            @endforeach
                        </ul>
                    </div>

                    <!-- Contact Support -->
                    <div class="bg-gray-50 border border-gray-200 rounded-xl p-6">
                        <div class="flex items-center mb-4">
                            <div class="w-10 h-10 bg-gray-100 rounded-lg flex items-center justify-center mr-3">
                                <i class="fas fa-headset text-gray-600"></i>
                            </div>
                            <h3 class="font-bold text-gray-900">Need Help?</h3>
                        </div>

                        <div class="space-y-3 text-sm text-gray-600">
                            <div class="flex items-center">
                                <i class="fas fa-envelope text-gray-400 mr-3"></i>
                                <span>support@infanect.com</span>
                            </div>
                            <div class="flex items-center">
                                <i class="fas fa-phone text-gray-400 mr-3"></i>
                                <span>+254 700 000 000</span>
                            </div>
                            <div class="flex items-center">
                                <i class="fas fa-clock text-gray-400 mr-3"></i>
                                <span>Available 24/7 for your assistance</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="flex flex-col sm:flex-row gap-4 justify-center mt-12">
                <a href="{{ route('activities.index') }}" class="px-8 py-3 bg-gradient-to-r from-green-600 to-teal-600 text-white rounded-full hover:from-green-700 hover:to-teal-700 transition-all duration-300 font-medium text-center shadow-lg hover:shadow-xl transform hover:scale-105">
                    <i class="fas fa-search mr-2"></i>
                    Browse More Activities
                </a>

                <a href="{{ route('dashboard') }}" class="px-8 py-3 bg-white text-gray-700 border-2 border-gray-300 rounded-full hover:bg-gray-50 hover:border-gray-400 transition-all duration-300 font-medium text-center">
                    <i class="fas fa-user-circle mr-2"></i>
                    Go to Dashboard
                </a>

                <button onclick="window.print()" class="px-8 py-3 bg-blue-600 text-white rounded-full hover:bg-blue-700 transition-all duration-300 font-medium text-center">
                    <i class="fas fa-print mr-2"></i>
                    Print Confirmation
                </button>
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
}
</style>
@endsection

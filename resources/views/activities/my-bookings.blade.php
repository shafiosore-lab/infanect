@extends('layouts.app')

@section('title', 'My Activity Bookings - Infanect')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-green-50 via-blue-50 to-purple-50">
    <!-- Hero Header -->
    <section class="relative bg-gradient-to-r from-green-600 via-teal-600 to-blue-700 text-white py-12 overflow-hidden">
        <div class="absolute inset-0 bg-black/10"></div>
        <div class="absolute inset-0 bg-gradient-to-r from-transparent via-white/5 to-transparent"></div>

        <!-- Floating Elements -->
        <div class="absolute top-8 left-8 w-16 h-16 bg-white/10 rounded-full blur-lg animate-pulse"></div>
        <div class="absolute bottom-8 right-8 w-20 h-20 bg-green-200/10 rounded-full blur-xl animate-bounce"></div>

        <div class="relative max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <div class="flex flex-col items-center">
                <div class="w-16 h-16 bg-white/20 rounded-full flex items-center justify-center mb-4 backdrop-blur-sm border border-white/30">
                    <i class="fas fa-calendar-check text-2xl text-white"></i>
                </div>
                <h1 class="text-4xl md:text-5xl font-bold mb-4 bg-gradient-to-r from-white to-green-100 bg-clip-text text-transparent">
                    My Activity Bookings
                </h1>
                <p class="text-xl text-green-100 max-w-3xl mb-8">
                    Manage and track all your activity bookings in one place
                </p>

                <!-- Quick Stats -->
                <div class="flex flex-wrap gap-6 justify-center">
                    <div class="bg-white/20 backdrop-blur-sm rounded-full px-6 py-3 border border-white/30">
                        <span class="font-bold text-lg">{{ $bookings->count() }}</span>
                        <span class="text-green-100 ml-1">Total Bookings</span>
                    </div>
                    <div class="bg-white/20 backdrop-blur-sm rounded-full px-6 py-3 border border-white/30">
                        <span class="font-bold text-lg">{{ $bookings->where('status', 'confirmed')->count() }}</span>
                        <span class="text-green-100 ml-1">Confirmed</span>
                    </div>
                    <div class="bg-white/20 backdrop-blur-sm rounded-full px-6 py-3 border border-white/30">
                        <span class="font-bold text-lg">{{ $bookings->where('status', 'completed')->count() }}</span>
                        <span class="text-green-100 ml-1">Completed</span>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Bookings Section -->
    <section class="py-12">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">

            @if($bookings->count() > 0)
                <!-- Filter Tabs -->
                <div class="flex flex-wrap gap-2 mb-8 justify-center">
                    <button class="filter-tab active px-4 py-2 rounded-full bg-green-600 text-white text-sm font-medium" data-status="all">
                        All Bookings ({{ $bookings->count() }})
                    </button>
                    <button class="filter-tab px-4 py-2 rounded-full bg-white text-gray-600 border border-gray-200 text-sm font-medium hover:bg-gray-50" data-status="confirmed">
                        Confirmed ({{ $bookings->where('status', 'confirmed')->count() }})
                    </button>
                    <button class="filter-tab px-4 py-2 rounded-full bg-white text-gray-600 border border-gray-200 text-sm font-medium hover:bg-gray-50" data-status="completed">
                        Completed ({{ $bookings->where('status', 'completed')->count() }})
                    </button>
                    <button class="filter-tab px-4 py-2 rounded-full bg-white text-gray-600 border border-gray-200 text-sm font-medium hover:bg-gray-50" data-status="pending">
                        Pending ({{ $bookings->where('status', 'pending')->count() }})
                    </button>
                </div>

                <!-- Bookings Grid -->
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach($bookings as $booking)
                        <div class="booking-card bg-white rounded-xl shadow-md hover:shadow-lg transition-all duration-300 border border-gray-100 overflow-hidden"
                             data-status="{{ $booking->status }}">

                            <!-- Status Header -->
                            <div class="p-4 border-b border-gray-100">
                                <div class="flex items-center justify-between mb-2">
                                    <div class="flex items-center">
                                        <!-- Category Icon & Color -->
                                        <div class="w-8 h-8 rounded-lg flex items-center justify-center mr-3
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
                                                    <i class="fas fa-tree text-sm"></i>
                                                    @break
                                                @case('creative')
                                                    <i class="fas fa-palette text-sm"></i>
                                                    @break
                                                @case('sports')
                                                    <i class="fas fa-futbol text-sm"></i>
                                                    @break
                                                @case('educational')
                                                    <i class="fas fa-graduation-cap text-sm"></i>
                                                    @break
                                                @default
                                                    <i class="fas fa-puzzle-piece text-sm"></i>
                                            @endswitch
                                        </div>
                                        <span class="text-xs font-mono text-gray-500">{{ $booking->reference }}</span>
                                    </div>

                                    <!-- Status Badge -->
                                    <span class="px-2 py-1 rounded-full text-xs font-medium
                                        @switch($booking->status)
                                            @case('confirmed')
                                                bg-green-100 text-green-800
                                                @break
                                            @case('completed')
                                                bg-blue-100 text-blue-800
                                                @break
                                            @case('pending')
                                                bg-yellow-100 text-yellow-800
                                                @break
                                            @case('cancelled')
                                                bg-red-100 text-red-800
                                                @break
                                            @default
                                                bg-gray-100 text-gray-800
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
                                                <i class="fas fa-clock mr-1"></i>Pending
                                                @break
                                            @case('cancelled')
                                                <i class="fas fa-times-circle mr-1"></i>Cancelled
                                                @break
                                        @endswitch
                                    </span>
                                </div>
                            </div>

                            <!-- Activity Details -->
                            <div class="p-4">
                                <h3 class="font-bold text-gray-900 text-lg mb-1 line-clamp-1">
                                    {{ $booking->activity_title }}
                                </h3>
                                <p class="text-sm text-gray-600 mb-3 flex items-center">
                                    <i class="fas fa-map-marker-alt mr-2 text-red-400 text-xs"></i>
                                    {{ $booking->activity_location }}
                                </p>

                                <!-- Date & Time -->
                                <div class="flex items-center justify-between mb-3 text-sm">
                                    <div class="flex items-center text-gray-600">
                                        <i class="fas fa-calendar mr-2 text-blue-500 text-xs"></i>
                                        <span>{{ date('M j, Y', strtotime($booking->date)) }}</span>
                                    </div>
                                    <div class="flex items-center text-gray-600">
                                        <i class="fas fa-clock mr-2 text-green-500 text-xs"></i>
                                        <span>{{ date('g:i A', strtotime($booking->time)) }}</span>
                                    </div>
                                </div>

                                <!-- Participants -->
                                <div class="flex items-center justify-between mb-4 text-sm">
                                    <div class="flex items-center text-gray-600">
                                        <i class="fas fa-users mr-2 text-purple-500 text-xs"></i>
                                        <span>{{ $booking->participants }} participant{{ $booking->participants > 1 ? 's' : '' }}</span>
                                    </div>
                                    <div class="font-bold text-green-600 text-lg">
                                        ${{ $booking->total_amount }}
                                    </div>
                                </div>

                                <!-- Payment Status -->
                                <div class="flex items-center justify-between mb-4">
                                    <div class="flex items-center text-sm">
                                        <i class="fas fa-credit-card mr-2 text-gray-400 text-xs"></i>
                                        <span class="text-gray-600">{{ ucfirst($booking->payment_method) }}</span>
                                    </div>
                                    <span class="px-2 py-1 rounded-full text-xs font-medium
                                        @if($booking->payment_status === 'paid')
                                            bg-green-100 text-green-800
                                        @else
                                            bg-yellow-100 text-yellow-800
                                        @endif
                                    ">
                                        {{ ucfirst($booking->payment_status) }}
                                    </span>
                                </div>

                                <!-- Actions -->
                                <div class="flex gap-2">
                                    <a href="{{ route('activities.booking.details', $booking->reference) }}"
                                       class="flex-1 px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors text-center text-sm font-medium">
                                        <i class="fas fa-eye mr-1"></i>
                                        View Details
                                    </a>

                                    @if($booking->status === 'confirmed' && strtotime($booking->date) > time())
                                        <button class="px-3 py-2 bg-red-100 text-red-600 rounded-lg hover:bg-red-200 transition-colors text-sm">
                                            <i class="fas fa-times"></i>
                                        </button>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

            @else
                <!-- No Bookings -->
                <div class="text-center py-16">
                    <div class="w-24 h-24 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-6">
                        <i class="fas fa-calendar-times text-3xl text-gray-400"></i>
                    </div>
                    <h3 class="text-2xl font-bold text-gray-900 mb-4">No Bookings Yet</h3>
                    <p class="text-gray-600 mb-8 max-w-md mx-auto">
                        You haven't booked any activities yet. Start exploring our amazing activities and create unforgettable memories!
                    </p>
                    <a href="{{ route('activities.index') }}"
                       class="px-8 py-3 bg-gradient-to-r from-green-600 to-teal-600 text-white rounded-full hover:from-green-700 hover:to-teal-700 transition-all duration-300 font-medium shadow-lg">
                        <i class="fas fa-search mr-2"></i>
                        Browse Activities
                    </a>
                </div>
            @endif
        </div>
    </section>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const filterTabs = document.querySelectorAll('.filter-tab');
    const bookingCards = document.querySelectorAll('.booking-card');

    filterTabs.forEach(tab => {
        tab.addEventListener('click', function() {
            const status = this.getAttribute('data-status');

            // Update active tab
            filterTabs.forEach(t => {
                t.classList.remove('active', 'bg-green-600', 'text-white');
                t.classList.add('bg-white', 'text-gray-600', 'border', 'border-gray-200');
            });

            this.classList.remove('bg-white', 'text-gray-600', 'border', 'border-gray-200');
            this.classList.add('active', 'bg-green-600', 'text-white');

            // Filter cards
            bookingCards.forEach(card => {
                if (status === 'all' || card.getAttribute('data-status') === status) {
                    card.style.display = 'block';
                } else {
                    card.style.display = 'none';
                }
            });
        });
    });
});
</script>

<style>
.line-clamp-1 {
    display: -webkit-box;
    -webkit-line-clamp: 1;
    -webkit-box-orient: vertical;
    overflow: hidden;
}
</style>
@endsection

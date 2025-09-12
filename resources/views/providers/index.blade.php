{{-- resources/views/providers/index.blade.php --}}
@extends('layouts.app')

@section('title', 'Find Trusted Service Providers')

@section('content')
<div class="min-h-screen bg-gradient-to-b from-slate-40 to-white">
    <!-- Hero Section -->
    <section class="relative bg-gradient-to-r from-blue-600 via-purple-600 to-indigo-700 text-white py-12 sm:py-16">
        <div class="absolute inset-0 bg-black/20"></div>
        <div class="relative max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <h1 class="text-2xl sm:text-3xl md:text-4xl lg:text-5xl font-bold mb-3 sm:mb-4 leading-tight">
                Find Trusted Service Providers
            </h1>
            <p class="text-sm sm:text-base md:text-lg mb-5 sm:mb-6 text-blue-100 max-w-3xl mx-auto leading-relaxed px-4 sm:px-0">
                Connect with verified professionals for meaningful family support worldwide
            </p>

        </div>
    </section>

    <!-- Quick Stats -->
    <section class="py-8 bg-gray-100 shadow-sm">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4 text-center">
                <div class="bg-gray-50 rounded-lg p-4">
                    <div class="text-2xl font-bold text-blue-600">2,500+</div>
                    <div class="text-gray-600 text-sm mt-1">Verified Providers</div>
                </div>
                <div class="bg-gray-50 rounded-lg p-4">
                    <div class="text-2xl font-bold text-green-600">150+</div>
                    <div class="text-gray-600 text-sm mt-1">Countries</div>
                </div>
                <div class="bg-gray-50 rounded-lg p-4">
                    <div class="text-2xl font-bold text-purple-600">4.8★</div>
                    <div class="text-gray-600 text-sm mt-1">Average Rating</div>
                </div>
                <div class="bg-gray-50 rounded-lg p-4">
                    <div class="text-2xl font-bold text-orange-600">50K+</div>
                    <div class="text-gray-600 text-sm mt-1">Happy Families</div>
                </div>
            </div>
        </div>
    </section>

    <!-- Filters Section -->
    <section class="py-6 bg-gray-50 border-b">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <select class="px-3 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 text-sm">
                    <option>All Locations</option>
                    <option>North America</option>
                    <option>Europe</option>
                    <option>Asia</option>
                    <option>Oceania</option>
                </select>
                <select class="px-3 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 text-sm">
                    <option>All Services</option>
                    <option>Bonding Activities</option>
                    <option>Family Counseling</option>
                    <option>Parenting Education</option>
                </select>
                <select class="px-3 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 text-sm">
                    <option>Any Rating</option>
                    <option>4.5+ ★</option>
                    <option>4.0+ ★</option>
                </select>
                <button class="px-5 py-2.5 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors text-sm font-medium">
                    Apply Filters
                </button>
            </div>
        </div>
    </section>

    <!-- Featured Providers -->
    <section class="py-12">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-8">
                <h2 class="text-2xl font-bold text-gray-900 mb-3">Featured Providers</h2>
                <p class="text-lg text-gray-600">Top-rated professionals ready to support your family's journey</p>
            </div>

            <div class="space-y-6">
                @for($i = 0; $i < 6; $i++)
                    <div class="bg-white rounded-2xl shadow-lg hover:shadow-xl transition-shadow duration-300 overflow-hidden">
                        <div class="flex min-h-32">
                            <!-- Provider Photo -->
                            <div class="w-24 h-24 relative flex-shrink-0 overflow-hidden rounded-2xl m-3">
                                <img
                                    src="https://images.unsplash.com/photo-{{
                                        collect([
                                            '1559839734-2b71ea197ec2?ixlib=rb-4.0.3&auto=format&fit=crop&w=112&h=112&q=85', // Professional woman
                                            '1594824388511-923247e6e01e?ixlib=rb-4.0.3&auto=format&fit=crop&w=112&h=112&q=85', // Professional man
                                            '1612349317150-e3d4ac1d0e35?ixlib=rb-4.0.3&auto=format&fit=crop&w=112&h=112&q=85', // Doctor woman
                                            '1582750433449-648ed127bb54?ixlib=rb-4.0.3&auto=format&fit=crop&w=112&h=112&q=85'  // Therapist
                                        ])[$i % 4]
                                    }}"
                                    alt="@switch($i % 4)
                                        @case(0) Dr. Sarah Johnson @break
                                        @case(1) Maria Rodriguez @break
                                        @case(2) Dr. Ahmed Hassan @break
                                        @case(3) Emma Thompson @break
                                    @endswitch"
                                    class="w-full h-full object-cover"
                                >
                                <div class="absolute top-2 right-2">
                                    <div class="bg-white/95 backdrop-blur-sm px-2 py-1 rounded-full flex items-center text-xs shadow-lg border border-white/20">
                                        <i class="fas fa-star text-yellow-500 mr-1"></i>
                                        <span class="font-semibold text-gray-800">4.{{ 7 + ($i % 3) }}</span>
                                    </div>
                                </div>
                            </div>

                            <!-- Provider Info -->
                            <div class="flex-1 px-5 py-4 flex flex-col justify-between">
                                <!-- Header Section -->
                                <div class="flex justify-between items-start mb-3">
                                    <div class="flex-1 pr-4">
                                        <h3 class="text-lg font-bold text-gray-900 leading-tight mb-1">
                                            @switch($i % 4)
                                                @case(0) Dr. Sarah Johnson @break
                                                @case(1) Maria Rodriguez @break
                                                @case(2) Dr. Ahmed Hassan @break
                                                @case(3) Emma Thompson @break
                                            @endswitch
                                        </h3>
                                        <p class="text-gray-600 text-sm font-medium mb-2">Certified Family Therapist & Bonding Specialist</p>
                                        <p class="text-gray-500 text-sm leading-relaxed">
                                            @switch($i % 4)
                                                @case(0) Specializes in strengthening parent-child bonds through evidence-based therapeutic techniques and interactive play sessions. @break
                                                @case(1) Expert in multicultural family dynamics and bilingual therapy approaches for diverse communities worldwide. @break
                                                @case(2) Focuses on attachment theory and trauma-informed care to help families heal and reconnect meaningfully. @break
                                                @case(3) Dedicated to supporting families through life transitions with compassionate, personalized therapeutic guidance. @break
                                            @endswitch
                                        </p>
                                    </div>
                                    <div class="text-right ml-4">
                                        <div class="text-xl font-bold text-blue-600">${{ 100 + ($i * 15) }}</div>
                                        <div class="text-xs text-gray-500 font-medium">per session</div>
                                    </div>
                                </div>

                                <!-- Info and Actions Section -->
                                <div class="flex justify-between items-end">
                                    <!-- Left: Location and Details -->
                                    <div class="flex flex-col gap-2">
                                        <div class="flex items-center gap-4 text-sm text-gray-600">
                                            <div class="flex items-center">
                                                <i class="fas fa-map-marker-alt mr-1.5 text-blue-500"></i>
                                                <span class="font-medium">
                                                    @switch($i % 4)
                                                        @case(0) New York, USA @break
                                                        @case(1) Barcelona, Spain @break
                                                        @case(2) Dubai, UAE @break
                                                        @case(3) London, UK @break
                                                    @endswitch
                                                </span>
                                            </div>
                                            <div class="flex items-center">
                                                <i class="fas fa-language mr-1.5 text-green-500"></i>
                                                <span class="font-medium">English, Spanish</span>
                                            </div>
                                            <div class="flex items-center">
                                                <i class="fas fa-clock mr-1.5 text-purple-500"></i>
                                                <span class="font-medium text-green-600">Available Today</span>
                                            </div>
                                        </div>
                                        <div class="flex flex-wrap gap-2">
                                            <span class="px-3 py-1 bg-blue-100 text-blue-800 text-xs rounded-full font-semibold">Parent-Child Bonding</span>
                                            <span class="px-3 py-1 bg-green-100 text-green-800 text-xs rounded-full font-semibold">Family Therapy</span>
                                            <span class="px-3 py-1 bg-purple-100 text-purple-800 text-xs rounded-full font-semibold">Attachment Support</span>
                                        </div>
                                    </div>

                                    <!-- Right: Action Buttons -->
                                    <div class="flex gap-1.5 ml-4 items-center">
                                        <a href="{{ route('providers.book', $i + 1) }}" class="px-2.5 py-1.5 bg-gradient-to-r from-blue-600 to-blue-700 text-white rounded-full hover:from-blue-700 hover:to-blue-800 transition-all duration-300 font-medium text-xs shadow-sm hover:shadow-md transform hover:scale-105">
                                            <i class="fas fa-calendar mr-1 text-blue-100"></i>Book
                                        </a>
                                        <a href="{{ route('providers.show', $i + 1) }}" class="px-2.5 py-1.5 bg-gradient-to-r from-emerald-100 to-green-100 text-emerald-700 rounded-full hover:from-emerald-200 hover:to-green-200 transition-all duration-300 font-medium text-xs shadow-sm hover:shadow-md transform hover:scale-105">
                                            <i class="fas fa-eye mr-1 text-emerald-600"></i>View
                                        </a>
                                        @php
                                            $rating = 4.7 + ($i % 3) * 0.1; // Dynamic rating: 4.7, 4.8, 4.9
                                            $fullStars = floor($rating);
                                            $halfStar = ($rating - $fullStars) >= 0.5;
                                        @endphp
                                        <div class="flex items-center ml-1.5 text-sm">
                                            @for ($s = 1; $s <= 5; $s++)
                                                @if ($s <= $fullStars)
                                                    <i class="fas fa-star text-yellow-400"></i>
                                                @elseif ($halfStar && $s == $fullStars + 1)
                                                    <i class="fas fa-star-half-alt text-yellow-400"></i>
                                                @else
                                                    <i class="far fa-star text-gray-300"></i>
                                                @endif
                                            @endfor
                                            <span class="ml-2 text-xs font-medium text-gray-600">{{ number_format($rating, 1) }}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endfor
            </div>

            <!-- Classic Pagination -->
            <div class="flex justify-center mt-12">
                <nav class="flex items-center space-x-1" aria-label="Pagination">
                    <!-- Previous Button -->
                    <button class="px-3 py-2 text-gray-500 hover:text-gray-700 hover:bg-gray-100 rounded-full transition-colors disabled:opacity-50 disabled:cursor-not-allowed" disabled>
                        <i class="fas fa-chevron-left"></i>
                        <span class="sr-only">Previous</span>
                    </button>

                    <!-- Page Numbers -->
                    <button class="px-4 py-2 bg-blue-600 text-white rounded-full font-medium">1</button>
                    <button class="px-4 py-2 text-gray-700 hover:text-blue-600 hover:bg-blue-50 rounded-full transition-colors">2</button>
                    <button class="px-4 py-2 text-gray-700 hover:text-blue-600 hover:bg-blue-50 rounded-full transition-colors">3</button>
                    <span class="px-2 py-2 text-gray-400">...</span>
                    <button class="px-4 py-2 text-gray-700 hover:text-blue-600 hover:bg-blue-50 rounded-full transition-colors">8</button>
                    <button class="px-4 py-2 text-gray-700 hover:text-blue-600 hover:bg-blue-50 rounded-full transition-colors">9</button>

                    <!-- Next Button -->
                    <button class="px-3 py-2 text-gray-700 hover:text-gray-900 hover:bg-gray-100 rounded-full transition-colors">
                        <i class="fas fa-chevron-right"></i>
                        <span class="sr-only">Next</span>
                    </button>
                </nav>
            </div>

            <!-- Page Info -->
            <div class="text-center mt-4">
                <p class="text-sm text-gray-600">
                    Showing <span class="font-medium">1</span> to <span class="font-medium">6</span> of <span class="font-medium">52</span> providers
                </p>
            </div>
        </div>
    </section>



<style>
.line-clamp-2 {
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}
</style>
@endsection

@extends('layouts.app')

@section('title', $activity->title . ' - Infanect')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-green-50 via-blue-50 to-purple-50">

    <!-- Activity Hero -->
    <section class="relative h-96 overflow-hidden">
        <img src="https://images.unsplash.com/photo-{{ $activity->image_id }}?ixlib=rb-4.0.3&auto=format&fit=crop&w=1200&h=400&q=85"
             alt="{{ $activity->title }}"
             class="w-full h-full object-cover">

        <!-- Overlay -->
        <div class="absolute inset-0 bg-gradient-to-r from-black/60 via-black/40 to-transparent"></div>

        <!-- Content -->
        <div class="absolute inset-0 flex items-end">
            <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 pb-8 w-full">
                <!-- Back Button -->
                <div class="mb-4">
                    <a href="{{ route('activities.index') }}" class="inline-flex items-center px-4 py-2 bg-white/20 backdrop-blur-sm text-white rounded-full hover:bg-white/30 transition-all duration-300 border border-white/20">
                        <i class="fas fa-arrow-left mr-2"></i>
                        <span class="font-medium">Back to Activities</span>
                    </a>
                </div>

                <div class="flex flex-wrap gap-4 items-end justify-between">
                    <div>
                        <h1 class="text-4xl md:text-5xl font-bold text-white mb-4">{{ $activity->title }}</h1>
                        <div class="flex items-center text-white/90 mb-2">
                            <i class="fas fa-map-marker-alt mr-2"></i>
                            <span class="text-lg">{{ $activity->location }}</span>
                        </div>
                        <div class="flex items-center text-white/90">
                            <div class="flex items-center mr-4">
                                @for($i = 1; $i <= 5; $i++)
                                    @if($i <= floor($activity->rating))
                                        <i class="fas fa-star text-yellow-400"></i>
                                    @elseif($i <= $activity->rating)
                                        <i class="fas fa-star-half-alt text-yellow-400"></i>
                                    @else
                                        <i class="far fa-star text-white/50"></i>
                                    @endif
                                @endfor
                                <span class="ml-2">{{ $activity->rating }} ({{ $activity->reviews }} reviews)</span>
                            </div>
                        </div>
                    </div>
                    <div class="text-right">
                        <div class="text-3xl md:text-4xl font-bold text-white">${{ $activity->price }}</div>
                        <div class="text-white/90">per person</div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Activity Details -->
    <section class="py-12">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid lg:grid-cols-3 gap-8">

                <!-- Main Content -->
                <div class="lg:col-span-2 space-y-8">

                    <!-- Description -->
                    <div class="bg-white rounded-2xl shadow-lg p-8 border border-gray-100">
                        <h2 class="text-2xl font-bold text-gray-900 mb-4">About This Activity</h2>
                        <p class="text-gray-700 text-lg leading-relaxed">{{ $activity->description }}</p>

                        <div class="mt-6 grid grid-cols-1 md:grid-cols-3 gap-6">
                            <div class="text-center p-4 bg-green-50 rounded-xl border border-green-200">
                                <i class="fas fa-clock text-2xl text-green-600 mb-2"></i>
                                <div class="font-semibold text-gray-900">Duration</div>
                                <div class="text-green-600">{{ $activity->duration }}</div>
                            </div>
                            <div class="text-center p-4 bg-blue-50 rounded-xl border border-blue-200">
                                <i class="fas fa-users text-2xl text-blue-600 mb-2"></i>
                                <div class="font-semibold text-gray-900">Age Groups</div>
                                <div class="text-blue-600 text-sm">
                                    @foreach($activity->age_groups as $index => $ageGroup)
                                        {{ $index > 0 ? ', ' : '' }}{{ ucfirst($ageGroup) }}
                                    @endforeach
                                </div>
                            </div>
                            <div class="text-center p-4 bg-purple-50 rounded-xl border border-purple-200">
                                <i class="fas fa-tag text-2xl text-purple-600 mb-2"></i>
                                <div class="font-semibold text-gray-900">Category</div>
                                <div class="text-purple-600">{{ ucfirst(str_replace('-', ' ', $activity->category)) }}</div>
                            </div>
                        </div>
                    </div>

                    <!-- What's Included -->
                    <div class="bg-white rounded-2xl shadow-lg p-8 border border-gray-100">
                        <h3 class="text-xl font-bold text-gray-900 mb-4">What's Included</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div class="flex items-center text-gray-700">
                                <i class="fas fa-check text-green-600 mr-3"></i>
                                <span>Professional instruction</span>
                            </div>
                            <div class="flex items-center text-gray-700">
                                <i class="fas fa-check text-green-600 mr-3"></i>
                                <span>All equipment provided</span>
                            </div>
                            <div class="flex items-center text-gray-700">
                                <i class="fas fa-check text-green-600 mr-3"></i>
                                <span>Safety gear included</span>
                            </div>
                            <div class="flex items-center text-gray-700">
                                <i class="fas fa-check text-green-600 mr-3"></i>
                                <span>Photo opportunities</span>
                            </div>
                            <div class="flex items-center text-gray-700">
                                <i class="fas fa-check text-green-600 mr-3"></i>
                                <span>Family-friendly environment</span>
                            </div>
                            <div class="flex items-center text-gray-700">
                                <i class="fas fa-check text-green-600 mr-3"></i>
                                <span>Refreshments</span>
                            </div>
                        </div>
                    </div>

                    <!-- Reviews Summary -->
                    <div class="bg-white rounded-2xl shadow-lg p-8 border border-gray-100">
                        <h3 class="text-xl font-bold text-gray-900 mb-6">What Families Say</h3>

                        <!-- Sample Reviews -->
                        <div class="space-y-6">
                            <div class="border-l-4 border-green-500 pl-4">
                                <div class="flex items-center mb-2">
                                    <div class="flex items-center mr-3">
                                        @for($i = 1; $i <= 5; $i++)
                                            <i class="fas fa-star text-yellow-400 text-sm"></i>
                                        @endfor
                                    </div>
                                    <span class="font-semibold text-gray-900">Sarah K.</span>
                                    <span class="text-gray-500 ml-2">• Family with 2 kids</span>
                                </div>
                                <p class="text-gray-700">"Amazing experience! Our kids loved every moment and we created memories that will last a lifetime."</p>
                            </div>

                            <div class="border-l-4 border-green-500 pl-4">
                                <div class="flex items-center mb-2">
                                    <div class="flex items-center mr-3">
                                        @for($i = 1; $i <= 4; $i++)
                                            <i class="fas fa-star text-yellow-400 text-sm"></i>
                                        @endfor
                                        <i class="far fa-star text-gray-300 text-sm"></i>
                                    </div>
                                    <span class="font-semibold text-gray-900">Michael R.</span>
                                    <span class="text-gray-500 ml-2">• Father of 3</span>
                                </div>
                                <p class="text-gray-700">"Well organized and safe. The instructors were patient and great with children of all ages."</p>
                            </div>
                        </div>

                        <div class="text-center mt-6">
                            <button class="px-6 py-3 bg-gray-100 text-gray-700 rounded-xl hover:bg-gray-200 transition-colors font-medium">
                                <i class="fas fa-comments mr-2"></i>
                                View All {{ $activity->reviews }} Reviews
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Booking Sidebar -->
                <div class="lg:col-span-1">
                    <div class="sticky top-8 space-y-6">

                        <!-- Booking Card -->
                        <div class="bg-white rounded-2xl shadow-lg p-6 border border-gray-100">
                            <div class="text-center mb-6">
                                <div class="text-3xl font-bold text-gray-900 mb-1">${{ $activity->price }}</div>
                                <div class="text-gray-600">per person</div>
                            </div>

                            <form class="space-y-4">
                                <div>
                                    <label class="block text-sm font-semibold text-gray-700 mb-2">Select Date</label>
                                    <input type="date" class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-green-500 focus:border-green-500">
                                </div>

                                <div>
                                    <label class="block text-sm font-semibold text-gray-700 mb-2">Number of Participants</label>
                                    <select class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-green-500 focus:border-green-500">
                                        <option>1 person</option>
                                        <option>2 people</option>
                                        <option>3 people</option>
                                        <option>4 people</option>
                                        <option>5+ people</option>
                                    </select>
                                </div>

                                <div>
                                    <label class="block text-sm font-semibold text-gray-700 mb-2">Preferred Time</label>
                                    <select class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-green-500 focus:border-green-500">
                                        <option>Morning (9:00 AM)</option>
                                        <option>Afternoon (2:00 PM)</option>
                                        <option>Evening (5:00 PM)</option>
                                    </select>
                                </div>

                                <button type="submit" class="w-full px-6 py-4 bg-gradient-to-r from-green-600 to-teal-600 text-white rounded-xl hover:from-green-700 hover:to-teal-700 transition-all duration-300 font-bold text-lg shadow-lg hover:shadow-xl transform hover:scale-105">
                                    <i class="fas fa-calendar-check mr-2"></i>
                                    Book Now
                                </button>
                            </form>

                            <p class="text-center text-gray-500 text-sm mt-4">
                                <i class="fas fa-shield-alt mr-1"></i>
                                Free cancellation up to 24 hours before
                            </p>
                        </div>

                        <!-- Contact Info -->
                        <div class="bg-white rounded-2xl shadow-lg p-6 border border-gray-100">
                            <h4 class="font-bold text-gray-900 mb-4">Need Help?</h4>
                            <div class="space-y-3">
                                <div class="flex items-center text-gray-600">
                                    <i class="fas fa-phone text-green-600 mr-3 w-4"></i>
                                    <span>+254 700 123 456</span>
                                </div>
                                <div class="flex items-center text-gray-600">
                                    <i class="fas fa-envelope text-green-600 mr-3 w-4"></i>
                                    <span>activities@infanect.com</span>
                                </div>
                                <div class="flex items-center text-gray-600">
                                    <i class="fas fa-clock text-green-600 mr-3 w-4"></i>
                                    <span>Mon-Sun 8AM-8PM</span>
                                </div>
                            </div>
                        </div>

                        <!-- Safety Badge -->
                        <div class="bg-gradient-to-r from-green-50 to-teal-50 rounded-2xl p-6 border border-green-100">
                            <div class="text-center">
                                <i class="fas fa-shield-check text-green-600 text-3xl mb-3"></i>
                                <h4 class="font-bold text-gray-900 mb-2">Family Safe</h4>
                                <p class="text-sm text-gray-600">
                                    All activities are thoroughly vetted for family safety and age-appropriateness.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Related Activities -->
    @if($relatedActivities->count() > 0)
    <section class="py-12 bg-gray-50">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
            <h3 class="text-2xl font-bold text-gray-900 mb-8 text-center">Similar Activities</h3>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                @foreach($relatedActivities as $relatedActivity)
                    <div class="bg-white rounded-xl shadow-md overflow-hidden hover:shadow-lg transition-all duration-300 border border-gray-100">
                        <div class="relative h-32">
                            <img src="https://images.unsplash.com/photo-{{ $relatedActivity->image_id }}?ixlib=rb-4.0.3&auto=format&fit=crop&w=300&h=200&q=85"
                                 alt="{{ $relatedActivity->title }}"
                                 class="w-full h-full object-cover">
                        </div>
                        <div class="p-4">
                            <h4 class="font-bold text-gray-900 mb-2">{{ $relatedActivity->title }}</h4>
                            <p class="text-gray-600 text-sm mb-3 line-clamp-2">{{ $relatedActivity->description }}</p>
                            <div class="flex items-center justify-between">
                                <span class="font-bold text-green-600">${{ $relatedActivity->price }}</span>
                                <a href="{{ route('activities.show', $relatedActivity->id) }}"
                                   class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors text-sm font-medium">
                                    View Details
                                </a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>
    @endif
</div>
@endsection

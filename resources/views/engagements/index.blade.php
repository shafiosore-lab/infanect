@extends('layouts.app')

@section('title', 'Community Engagements - Infanect')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-green-50 via-blue-50 to-purple-50">
    <!-- Hero Header -->
    <section class="relative bg-gradient-to-r from-indigo-600 via-purple-600 to-pink-700 text-white py-12 overflow-hidden">
        <div class="absolute inset-0 bg-black/10"></div>
        <div class="absolute inset-0 bg-gradient-to-r from-transparent via-white/5 to-transparent"></div>

        <!-- Floating Elements -->
        <div class="absolute top-8 left-8 w-16 h-16 bg-white/10 rounded-full blur-lg animate-pulse"></div>
        <div class="absolute bottom-8 right-8 w-20 h-20 bg-indigo-200/10 rounded-full blur-xl animate-bounce"></div>

        <div class="relative max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <div class="flex flex-col items-center">
                <div class="w-16 h-16 bg-white/20 rounded-full flex items-center justify-center mb-4 backdrop-blur-sm border border-white/30">
                    <i class="fas fa-users text-2xl text-white"></i>
                </div>
                <h1 class="text-4xl md:text-5xl font-bold mb-4 bg-gradient-to-r from-white to-indigo-100 bg-clip-text text-transparent">
                    Community Engagements
                </h1>
                <p class="text-xl text-indigo-100 max-w-3xl mb-8">
                    Join contests, challenges, and community activities to connect with other families
                </p>
            </div>
        </div>
    </section>

    <!-- Engagements Section -->
    <section class="py-8">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">

            <!-- Stats Overview -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                <div class="bg-white rounded-xl shadow-md p-6 border border-gray-100">
                    <div class="flex items-center">
                        <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center mr-4">
                            <i class="fas fa-trophy text-blue-600"></i>
                        </div>
                        <div>
                            <h3 class="text-2xl font-bold text-gray-900">{{ $engagements->where('type', 'contest')->count() }}</h3>
                            <p class="text-sm text-gray-600">Active Contests</p>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-xl shadow-md p-6 border border-gray-100">
                    <div class="flex items-center">
                        <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center mr-4">
                            <i class="fas fa-tasks text-purple-600"></i>
                        </div>
                        <div>
                            <h3 class="text-2xl font-bold text-gray-900">{{ $engagements->where('type', 'challenge')->count() }}</h3>
                            <p class="text-sm text-gray-600">Weekly Challenges</p>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-xl shadow-md p-6 border border-gray-100">
                    <div class="flex items-center">
                        <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center mr-4">
                            <i class="fas fa-heart text-green-600"></i>
                        </div>
                        <div>
                            <h3 class="text-2xl font-bold text-green-600">{{ $engagements->where('type', 'community')->count() }}</h3>
                            <p class="text-sm text-gray-600">Community Events</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Engagements Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($engagements as $engagement)
                    <div class="bg-white rounded-xl shadow-md hover:shadow-lg transition-all duration-300 border border-gray-100 overflow-hidden">

                        <!-- Engagement Header -->
                        <div class="p-6 border-b border-gray-100">
                            <div class="flex items-center justify-between mb-3">
                                <span class="px-3 py-1 rounded-full text-xs font-medium
                                    @switch($engagement->type)
                                        @case('contest')
                                            bg-blue-100 text-blue-800
                                            @break
                                        @case('challenge')
                                            bg-purple-100 text-purple-800
                                            @break
                                        @case('community')
                                            bg-green-100 text-green-800
                                            @break
                                    @endswitch
                                ">
                                    {{ ucfirst($engagement->type) }}
                                </span>

                                <span class="px-2 py-1 rounded-full text-xs font-medium
                                    @if($engagement->status === 'active')
                                        bg-green-100 text-green-800
                                    @else
                                        bg-yellow-100 text-yellow-800
                                    @endif
                                ">
                                    {{ ucfirst($engagement->status) }}
                                </span>
                            </div>

                            <h3 class="text-lg font-bold text-gray-900 mb-2">{{ $engagement->title }}</h3>
                            <p class="text-gray-600 text-sm line-clamp-2">{{ $engagement->description }}</p>
                        </div>

                        <!-- Engagement Details -->
                        <div class="p-6">
                            <!-- Participation Info -->
                            <div class="mb-4">
                                <div class="flex items-center justify-between text-sm mb-2">
                                    <span class="text-gray-600">Participants</span>
                                    <span class="font-medium">{{ $engagement->participants }}/{{ $engagement->max_participants }}</span>
                                </div>
                                <div class="w-full bg-gray-200 rounded-full h-2">
                                    @php
                                        $percentage = ($engagement->participants / $engagement->max_participants) * 100;
                                    @endphp
                                    <div class="bg-gradient-to-r from-green-500 to-teal-500 h-2 rounded-full transition-all duration-500"
                                         style="width: {{ $percentage }}%"></div>
                                </div>
                            </div>

                            <!-- Date Info -->
                            <div class="flex items-center justify-between text-sm mb-4">
                                <div>
                                    <span class="text-gray-500">Start:</span>
                                    <span class="font-medium">{{ date('M j', strtotime($engagement->start_date)) }}</span>
                                </div>
                                <div>
                                    <span class="text-gray-500">End:</span>
                                    <span class="font-medium">{{ date('M j', strtotime($engagement->end_date)) }}</span>
                                </div>
                            </div>

                            <!-- Prize -->
                            <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-3 mb-4">
                                <div class="flex items-center">
                                    <i class="fas fa-gift text-yellow-600 mr-2"></i>
                                    <span class="text-yellow-800 text-sm font-medium">{{ $engagement->prize }}</span>
                                </div>
                            </div>

                            <!-- Actions -->
                            <div class="flex gap-2">
                                <a href="{{ route('engagements.show', $engagement->id) }}"
                                   class="flex-1 px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors text-center text-sm font-medium">
                                    View Details
                                </a>

                                @if($engagement->available && $engagement->status === 'active')
                                    <form action="{{ route('engagements.join', $engagement->id) }}" method="POST" class="flex-1">
                                        @csrf
                                        <button type="submit" class="w-full px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition-colors text-sm font-medium">
                                            Join Now
                                        </button>
                                    </form>
                                @else
                                    <button disabled class="flex-1 px-4 py-2 bg-gray-300 text-gray-500 rounded-lg text-sm font-medium cursor-not-allowed">
                                        {{ $engagement->status === 'upcoming' ? 'Coming Soon' : 'Full' }}
                                    </button>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            @if($engagements->count() === 0)
                <!-- No Engagements -->
                <div class="text-center py-16">
                    <div class="w-24 h-24 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-6">
                        <i class="fas fa-users text-3xl text-gray-400"></i>
                    </div>
                    <h3 class="text-2xl font-bold text-gray-900 mb-4">No Active Engagements</h3>
                    <p class="text-gray-600 mb-8 max-w-md mx-auto">
                        Check back soon for new contests, challenges, and community events!
                    </p>
                </div>
            @endif
        </div>
    </section>
</div>

<style>
.line-clamp-2 {
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}
</style>
@endsection

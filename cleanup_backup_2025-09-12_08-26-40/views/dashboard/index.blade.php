@extends('layouts.admin')

@section('title', 'Dashboard')

@section('content')
<section class="container min-h-screen overflow-x-auto">

    <div class=" mx-auto p-6 space-y-10">

    <!-- Welcome Header -->
    <header>
        <h1 class="text-3xl font-bold text-gray-900">
            Welcome back, {{ auth()->user()->name }}!
        </h1>
        <p class="mt-2 text-gray-600">Here's your activity overview</p>
    </header>

    <!-- User Metrics Dashboard -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        @include('dashboards.partials.metrics', ['stats' => $stats])
    </div>

    <!-- Wellness Score Section -->
    <div class="bg-white rounded-xl p-6 shadow-md">
        <h2 class="text-xl font-semibold text-gray-800 mb-6">Wellness Score</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6" id="wellness-scores">
            @include('dashboards.partials.wellness')
        </div>
    </div>

    <!-- Additional Stats -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        @include('dashboards.partials.additional_stats', ['stats' => $stats])
    </div>

    <!-- Tabs Section for Activities, Services, Providers, Bookings -->
    <div x-data="{ tab: 'top-services' }">
        <div class="flex space-x-4 border-b mb-6">
            <button @click="tab='top-services'"
                    :class="tab === 'top-services' ? 'border-b-2 border-blue-500 text-blue-600 font-semibold' : 'text-gray-500'"
                    class="px-4 py-2 focus:outline-none">Top Services</button>
            <button @click="tab='recent-activity'"
                    :class="tab === 'recent-activity' ? 'border-b-2 border-blue-500 text-blue-600 font-semibold' : 'text-gray-500'"
                    class="px-4 py-2 focus:outline-none">Recent Activity</button>
            <button @click="tab='top-providers'"
                    :class="tab === 'top-providers' ? 'border-b-2 border-blue-500 text-blue-600 font-semibold' : 'text-gray-500'"
                    class="px-4 py-2 focus:outline-none">Top Providers</button>
            <button @click="tab='my-bookings'"
                    :class="tab === 'my-bookings' ? 'border-b-2 border-blue-500 text-blue-600 font-semibold' : 'text-gray-500'"
                    class="px-4 py-2 focus:outline-none">My Bookings</button>
        </div>

        <div class="space-y-6">
            <div x-show="tab === 'top-services'">
                @include('dashboards.partials.top_services', ['topServices' => $topServices])
            </div>
            <div x-show="tab === 'recent-activity'">
                @include('dashboards.partials.top_activities', ['topBondingActivities' => $topBondingActivities])
            </div>
            <div x-show="tab === 'top-providers'">
                @include('dashboards.partials.top_providers', ['topProviders' => $topProviders])
            </div>
            <div x-show="tab === 'my-bookings'">
                @include('dashboards.partials.my_bookings', ['bookings' => $bookings])
            </div>
        </div>
    </div>

    <!-- Engagements Section -->
    @if($engagements && count($engagements) > 0)
    <section class="bg-white rounded-xl p-6 shadow-md">
        <h2 class="text-xl font-semibold text-gray-800 mb-4">Recent Engagements</h2>
        <div class="space-y-4">
            @foreach($engagements as $engagement)
            <div class="border rounded-lg p-4 hover:shadow-lg transition-shadow">
                <h3 class="font-medium text-gray-900">{{ $engagement['title'] ?? 'Engagement' }}</h3>
                <p class="text-sm text-gray-600 mt-1">{{ $engagement['description'] ?? '' }}</p>
                <p class="text-xs text-gray-500 mt-2">{{ $engagement['date'] ?? now()->format('M d, Y') }}</p>
            </div>
            @endforeach
        </div>
    </section>
    @endif

</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Load wellness scores dynamically
    fetch('{{ route("dashboard.wellness") }}')
        .then(res => res.json())
        .then(scores => {
            const scoreElements = {
                'Mental Health': { element: 'mental-health-score', path: 'mental-health-path' },
                'Physical Health': { element: 'physical-health-score', path: 'physical-health-path' },
                'Social Wellness': { element: 'social-wellness-score', path: 'social-wellness-path' },
                'Learning Growth': { element: 'learning-growth-score', path: 'learning-growth-path' }
            };
            Object.keys(scores).forEach(key => {
                if(scoreElements[key]){
                    const score = Math.min(100, Math.max(0, scores[key]));
                    const el = document.getElementById(scoreElements[key].element);
                    const path = document.getElementById(scoreElements[key].path);
                    if(el) el.textContent = score;
                    if(path) {
                        const circumference = 2 * Math.PI * 15.9155;
                        path.style.strokeDasharray = `${(score/100)*circumference}, ${circumference}`;
                    }
                }
            });
        })
        .catch(err => console.error('Error fetching wellness scores:', err));
});
</script>

</section>

@endsection

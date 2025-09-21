@extends('layouts.admin')

@section('title', 'Dashboard')

@section('content')
<section class="container mx-auto px-4 pt-0 mt-0">

    <!-- Welcome Header -->
    <header class="mb-8 text-center md:text-left">
        <h1 class="text-3xl md:text-4xl font-extrabold text-gray-900 tracking-tight">
            Welcome back, {{ auth()->user()->name }} 👋
        </h1>
        <p class="mt-2 text-lg text-gray-600">Here’s your latest activity overview</p>
    </header>

    <!-- User Metrics -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        @include('dashboards.partials.metrics', ['stats' => $stats])
    </div>

    <!-- Wellness Score -->
    <div class="bg-white rounded-2xl p-6 shadow-lg mb-8 border border-gray-100">
        <h2 class="text-2xl font-semibold text-gray-800 mb-6">Wellness Score</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6" id="wellness-scores">
            @include('dashboards.partials.wellness')
        </div>
    </div>

    <!-- Additional Stats -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        @include('dashboards.partials.additional_stats', ['stats' => $stats])
    </div>

    <!-- Tabs Section -->
    <div x-data="{ tab: 'top-services' }" class="bg-white rounded-2xl shadow-lg border border-gray-100 p-6 mb-8">
        <div class="flex flex-wrap gap-4 border-b border-gray-200 mb-6">
            <button @click="tab='top-services'"
                    :class="tab === 'top-services' ? 'border-b-2 border-blue-600 text-blue-600 font-semibold' : 'text-gray-500 hover:text-gray-700'"
                    class="px-4 py-2 transition">Top Services</button>
            <button @click="tab='recent-activity'"
                    :class="tab === 'recent-activity' ? 'border-b-2 border-blue-600 text-blue-600 font-semibold' : 'text-gray-500 hover:text-gray-700'"
                    class="px-4 py-2 transition">Recent Activity</button>
            <button @click="tab='top-providers'"
                    :class="tab === 'top-providers' ? 'border-b-2 border-blue-600 text-blue-600 font-semibold' : 'text-gray-500 hover:text-gray-700'"
                    class="px-4 py-2 transition">Top Providers</button>
            <button @click="tab='my-bookings'"
                    :class="tab === 'my-bookings' ? 'border-b-2 border-blue-600 text-blue-600 font-semibold' : 'text-gray-500 hover:text-gray-700'"
                    class="px-4 py-2 transition">My Bookings</button>
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

    <!-- Engagements -->
    @if($engagements && count($engagements) > 0)
    <section class="bg-white rounded-2xl p-6 shadow-lg border border-gray-100">
        <h2 class="text-2xl font-semibold text-gray-800 mb-6">Recent Engagements</h2>
        <div class="space-y-4">
            @foreach($engagements as $engagement)
            <div class="border rounded-xl p-4 hover:shadow-md transition bg-gray-50">
                <h3 class="font-semibold text-gray-900">{{ $engagement['title'] ?? 'Engagement' }}</h3>
                <p class="text-sm text-gray-600 mt-2">{{ $engagement['description'] ?? '' }}</p>
                <p class="text-xs text-gray-500 mt-3">{{ $engagement['date'] ?? now()->format('M d, Y') }}</p>
            </div>
            @endforeach
        </div>
    </section>
    @endif

</section>

<!-- Wellness Score Script -->
<script>
document.addEventListener('DOMContentLoaded', function() {
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
@endsection

@extends('layouts.app')

@section('title', 'My Dashboard')

@section('content')
<div class="container mx-auto p-6 space-y-10">

    <!-- Welcome Header -->
    <header>
        <h1 class="text-3xl font-bold text-gray-900">
            Welcome back, {{ $user->name }}!
        </h1>
        <p class="mt-2 text-gray-600">Here’s your personalized wellness and learning overview</p>
    </header>

    <!-- Bonding Activities -->
    <section>
        <h2 class="text-xl font-semibold text-gray-800 mb-4">👨‍👩‍👧 Bonding Activities</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
            @forelse($bondingActivities as $activity)
                <div class="p-4 bg-white shadow rounded-xl hover:shadow-md transition">
                    <h3 class="font-semibold text-gray-900">{{ $activity->name }}</h3>
                    <p class="text-sm text-gray-600">{{ $activity->location ?? 'Online/Virtual' }}</p>
                </div>
            @empty
                <p class="text-sm text-gray-500">No bonding activities available right now.</p>
            @endforelse
        </div>
    </section>

    <!-- Learning Modules -->
    <section class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
        {{-- Parenting Modules --}}
        @include('partials.learning-card', [
            'title' => 'Parenting Modules',
            'description' => 'Expert parenting guidance & resources',
            'iconColor' => 'from-pink-500 to-purple-600',
            'progress' => $parentingModules->count(),
            'browseRoute' => route('parenting-modules.index'),
            'progressRoute' => $parentingModules->count() > 0 ? route('parenting-modules.my-progress') : null
        ])

        {{-- All Modules --}}
        @include('partials.learning-card', [
            'title' => 'All Modules',
            'description' => 'Digital wellness & professional learning',
            'iconColor' => 'from-blue-500 to-indigo-600',
            'progress' => $allModules->count(),
            'browseRoute' => route('modules.index'),
            'extraRoute' => route('ai-chat.index'),
            'extraLabel' => 'AI Chat',
            'extraColor' => 'from-green-500 to-blue-600'
        ])
    </section>

    <!-- AI Assistants -->
    <section>
        <h2 class="text-xl font-semibold text-gray-800 mb-4">🤖 AI Assistants</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            @foreach($aiChats as $chat)
                <div class="p-4 bg-gradient-to-r from-green-50 to-blue-50 border border-green-200 rounded-xl shadow-sm flex flex-col justify-between">
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900">{{ $chat['title'] }}</h3>
                        <p class="text-sm text-gray-600">{{ $chat['description'] }}</p>
                    </div>
                    <a href="{{ $chat['link'] }}"
                       class="mt-3 inline-flex items-center px-3 py-1.5 text-xs font-medium rounded-md text-white bg-gradient-to-r from-green-500 to-blue-600 hover:from-green-600 hover:to-blue-700">
                        Start Chat
                    </a>
                </div>
            @endforeach
        </div>
    </section>

    <!-- Stats with Graphs -->
    <section class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        {{-- Wellness Score --}}
        @include('partials.stat-card', [
            'title' => 'Wellness Score',
            'description' => 'Track your mental wellness trends this week.',
            'value' => '85%',
            'chartId' => 'wellnessChart',
            'chartType' => 'line',
            'labels' => ['Mon','Tue','Wed','Thu','Fri','Sat','Sun'],
            'data' => [65,70,75,80,85,90,88],
            'color' => 'success',
            'borderColor' => '#198754',
            'backgroundColor' => 'rgba(25,135,84,0.2)',
            'datasetLabel' => 'Score',
            'max' => 100
        ])

        {{-- Activity Engagement --}}
        @include('partials.stat-card', [
            'title' => 'Activity Engagement',
            'description' => 'Time spent on bonding activities.',
            'value' => '12 hrs',
            'chartId' => 'engagementChart',
            'chartType' => 'bar',
            'labels' => ['Mon','Tue','Wed','Thu','Fri','Sat','Sun'],
            'data' => [2,1.5,3,2.5,1,2,0],
            'color' => 'info',
            'borderColor' => '#0dcaf0',
            'backgroundColor' => 'rgba(13,202,240,0.2)',
            'datasetLabel' => 'Hours',
            'max' => 5
        ])

        {{-- Service Usage --}}
        @include('partials.stat-card', [
            'title' => 'Service Usage',
            'description' => 'Top services engaged by users.',
            'value' => '24 Services',
            'chartId' => 'serviceChart',
            'chartType' => 'doughnut',
            'labels' => ['Therapy','Workshops','Consults','Training'],
            'data' => [12,6,4,2],
            'color' => 'purple',
            'borderColor' => '#9333ea',
            'backgroundColor' => 'rgba(147,51,234,0.5)',
            'datasetLabel' => 'Usage',
            'max' => 20
        ])
    </section>

    <!-- Extra Charts -->
    <div class="bg-white rounded-xl p-6 shadow-md">
        <h3 class="text-lg font-semibold text-gray-800">Weekly Engagement</h3>
        <canvas id="weeklyEngagementChart" class="mt-4 h-40"></canvas>
    </div>

    <div class="bg-white rounded-xl p-6 shadow-md">
        <h3 class="text-lg font-semibold text-gray-800">Learning Progress</h3>
        <canvas id="learningProgressChart" class="mt-4 h-40"></canvas>
    </div>

</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
// Weekly Engagement
fetch("{{ route('dashboard.weekly-engagement') }}")
    .then(res => res.json())
    .then(data => {
        new Chart(document.getElementById('weeklyEngagementChart'), {
            type: 'line',
            data: {
                labels: data.labels,
                datasets: [{
                    label: 'Minutes',
                    data: data.data,
                    borderColor: '#3B82F6',
                    backgroundColor: 'rgba(59,130,246,0.2)',
                    tension: 0.4,
                    fill: true
                }]
            },
            options: { plugins: { legend: { display: false } } }
        });
    });

// Learning Progress
fetch("{{ route('dashboard.learning-progress') }}")
    .then(res => res.json())
    .then(data => {
        new Chart(document.getElementById('learningProgressChart'), {
            type: 'bar',
            data: {
                labels: data.labels,
                datasets: [{
                    label: 'Completion %',
                    data: data.data,
                    backgroundColor: '#10B981'
                }]
            },
            options: {
                plugins: { legend: { display: false } },
                scales: { y: { beginAtZero: true, max: 100 } }
            }
        });
    });
</script>
@endpush
@endsection

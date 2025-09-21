@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-50 p-6">
    <!-- Welcome Header -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900">Welcome back, {{ $user->name }}! 👋</h1>
        <p class="text-gray-600 mt-2">Here's your wellness journey overview</p>
    </div>

    <!-- Key Metrics Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <!-- Total Sessions -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600">Total Sessions</p>
                    <p class="text-3xl font-bold text-gray-900">{{ $metrics['completedSessions'] }}</p>
                    <p class="text-sm text-blue-600 mt-1">
                        <i class="fas fa-calendar-check mr-1"></i>
                        {{ $metrics['thisMonthSessions'] }} this month
                    </p>
                </div>
                <div class="p-3 bg-blue-100 rounded-full">
                    <i class="fas fa-user-check text-blue-600 text-xl"></i>
                </div>
            </div>
        </div>

        <!-- Upcoming Sessions -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600">Upcoming</p>
                    <p class="text-3xl font-bold text-gray-900">{{ $metrics['upcomingSessions'] }}</p>
                    <p class="text-sm text-green-600 mt-1">
                        <i class="fas fa-clock mr-1"></i>
                        Sessions scheduled
                    </p>
                </div>
                <div class="p-3 bg-green-100 rounded-full">
                    <i class="fas fa-calendar-plus text-green-600 text-xl"></i>
                </div>
            </div>
        </div>

        <!-- Progress Score -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600">Progress Score</p>
                    <p class="text-3xl font-bold text-gray-900">{{ $wellnessProgress['progressScore'] }}%</p>
                    <div class="w-full bg-gray-200 rounded-full h-2 mt-2">
                        <div class="bg-purple-600 h-2 rounded-full" style="width: {{ $wellnessProgress['progressScore'] }}%"></div>
                    </div>
                </div>
                <div class="p-3 bg-purple-100 rounded-full">
                    <i class="fas fa-chart-line text-purple-600 text-xl"></i>
                </div>
            </div>
        </div>

        <!-- Total Investment -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600">Investment</p>
                    <p class="text-3xl font-bold text-gray-900">KSh {{ number_format($metrics['totalSpent']) }}</p>
                    <p class="text-sm text-yellow-600 mt-1">
                        <i class="fas fa-coins mr-1"></i>
                        In your wellness
                    </p>
                </div>
                <div class="p-3 bg-yellow-100 rounded-full">
                    <i class="fas fa-piggy-bank text-yellow-600 text-xl"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Dashboard Grid with AI Assistant -->
    <div class="grid grid-cols-1 lg:grid-cols-4 gap-6 mb-8">
        <!-- AI Research Assistant Card -->
        <div class="lg:col-span-4 bg-gradient-to-r from-blue-600 via-purple-600 to-indigo-600 rounded-xl shadow-lg text-white p-6 mb-6">
            <div class="flex items-center justify-between">
                <div class="flex items-center">
                    <div class="w-16 h-16 bg-white bg-opacity-20 rounded-full flex items-center justify-center mr-4">
                        <i class="fas fa-brain text-3xl"></i>
                    </div>
                    <div>
                        <h3 class="text-2xl font-bold">AI Research Assistant</h3>
                        <p class="opacity-90 mt-1">Get evidence-based mental health information from peer-reviewed research</p>
                        <div class="flex items-center mt-3 space-x-4 text-sm">
                            <span class="bg-white bg-opacity-20 px-3 py-1 rounded-full">
                                <i class="fas fa-book mr-1"></i>
                                {{ $dashboardData['total_research_papers'] ?? 0 }} Research Papers
                            </span>
                            <span class="bg-white bg-opacity-20 px-3 py-1 rounded-full">
                                <i class="fas fa-shield-alt mr-1"></i>
                                Evidence-Based
                            </span>
                        </div>
                    </div>
                </div>
                <div class="text-right">
                    <a href="{{ route('ai.chat') }}"
                       class="bg-white text-blue-600 px-6 py-3 rounded-lg font-semibold hover:bg-gray-50 transition-colors inline-flex items-center">
                        <i class="fas fa-comments mr-2"></i>
                        Start Chat
                    </a>
                    <p class="text-sm opacity-75 mt-2">Available 24/7</p>
                </div>
            </div>
        </div>

        <!-- Wellness Progress Chart -->
        <div class="lg:col-span-3 bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-lg font-semibold text-gray-900">Wellness Journey</h3>
                <div class="flex space-x-2">
                    <button class="px-3 py-1 text-sm bg-blue-100 text-blue-700 rounded-full">7D</button>
                    <button class="px-3 py-1 text-sm text-gray-500 hover:bg-gray-100 rounded-full">30D</button>
                </div>
            </div>

            <!-- Mood Trend Chart -->
            <div class="h-64 mb-6">
                <canvas id="moodTrendChart"></canvas>
            </div>

            <!-- Progress Categories -->
            <div class="grid grid-cols-2 gap-4">
                @foreach($wellnessProgress['categories'] as $category)
                    <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                        <div class="flex items-center">
                            <div class="w-3 h-3 rounded-full mr-3" style="background-color: {{ $category['color'] }}"></div>
                            <span class="text-sm font-medium text-gray-700">{{ $category['name'] }}</span>
                        </div>
                        <span class="text-sm font-bold text-gray-900">{{ $category['progress'] }}%</span>
                    </div>
                @endforeach
            </div>
        </div>

        <!-- Upcoming Sessions -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-semibold text-gray-900">Upcoming Sessions</h3>
                <a href="{{ route('activities.index') }}" class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                    View All
                </a>
            </div>

            <div class="space-y-4">
                @forelse($upcomingBookings as $booking)
                    <div class="border border-gray-200 rounded-lg p-4 hover:bg-gray-50 transition-colors">
                        <div class="flex justify-between items-start">
                            <div class="flex-1">
                                <h4 class="font-medium text-gray-900">{{ $booking['service_name'] }}</h4>
                                <p class="text-sm text-gray-600">with {{ $booking['provider_name'] }}</p>
                                <div class="flex items-center mt-2 text-sm text-gray-500">
                                    <i class="fas fa-calendar mr-2"></i>
                                    {{ \Carbon\Carbon::parse($booking['booking_date'])->format('M j, Y g:i A') }}
                                </div>
                            </div>
                            <div class="text-right">
                                <span class="text-sm font-medium text-gray-900">{{ $booking['amount'] }}</span>
                                <span class="block text-xs px-2 py-1 bg-green-100 text-green-800 rounded-full mt-1">
                                    {{ ucfirst($booking['status']) }}
                                </span>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="text-center py-8">
                        <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                            <i class="fas fa-calendar-plus text-gray-400 text-xl"></i>
                        </div>
                        <p class="text-gray-500 mb-4">No upcoming sessions</p>
                        <a href="{{ route('activities.index') }}" class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                            <i class="fas fa-plus mr-2"></i>
                            Book a Session
                        </a>
                    </div>
                @endforelse
            </div>
        </div>
    </div>

    <!-- Bottom Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
        <!-- Achievement Badges -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Your Achievements</h3>
            <div class="grid grid-cols-2 gap-4">
                @foreach($achievementBadges->take(4) as $badge)
                    <div class="flex items-center p-3 {{ $badge['earned'] ? 'bg-yellow-50 border-yellow-200' : 'bg-gray-50 border-gray-200' }} border rounded-lg">
                        <div class="w-10 h-10 {{ $badge['earned'] ? 'bg-yellow-100 text-yellow-600' : 'bg-gray-200 text-gray-400' }} rounded-full flex items-center justify-center mr-3">
                            <i class="fas fa-{{ $badge['icon'] }}"></i>
                        </div>
                        <div class="flex-1">
                            <h4 class="text-sm font-medium {{ $badge['earned'] ? 'text-gray-900' : 'text-gray-500' }}">
                                {{ $badge['name'] }}
                            </h4>
                            <p class="text-xs {{ $badge['earned'] ? 'text-gray-600' : 'text-gray-400' }}">
                                {{ $badge['description'] }}
                            </p>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        <!-- Favorite Providers -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Your Providers</h3>
            <div class="space-y-4">
                @foreach($favoriteProviders->take(3) as $provider)
                    <div class="flex items-center justify-between">
                        <div class="flex items-center">
                            <div class="w-10 h-10 rounded-full flex items-center justify-center text-white font-medium mr-3"
                                 style="background-color: {{ $provider['color'] }}">
                                {{ $provider['avatar'] }}
                            </div>
                            <div>
                                <h4 class="text-sm font-medium text-gray-900">{{ $provider['name'] }}</h4>
                                <p class="text-xs text-gray-500">{{ $provider['specialty'] }}</p>
                            </div>
                        </div>
                        <div class="text-right">
                            <div class="flex items-center text-sm text-yellow-500">
                                <i class="fas fa-star mr-1"></i>
                                {{ $provider['rating'] }}
                            </div>
                            <p class="text-xs text-gray-500">{{ $provider['totalSessions'] }} sessions</p>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    <!-- Recommendations & Community -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Recommendations -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Recommended for You</h3>
            <div class="space-y-3">
                @foreach($recommendations as $recommendation)
                    <div class="flex items-center p-3 bg-blue-50 border border-blue-200 rounded-lg">
                        <div class="w-8 h-8 bg-blue-100 text-blue-600 rounded-full flex items-center justify-center mr-3">
                            <i class="fas fa-lightbulb text-sm"></i>
                        </div>
                        <p class="text-sm text-gray-700">{{ $recommendation }}</p>
                    </div>
                @endforeach
            </div>
        </div>

        <!-- Community Highlights -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Community Highlights</h3>
            <div class="space-y-4">
                @foreach($communityHighlights as $highlight)
                    <div class="border-l-4 {{ $highlight['type'] === 'event' ? 'border-green-400' : ($highlight['type'] === 'tip' ? 'border-blue-400' : 'border-purple-400') }} pl-4">
                        <h4 class="text-sm font-medium text-gray-900">{{ $highlight['title'] }}</h4>
                        <p class="text-xs text-gray-600 mt-1">{{ $highlight['excerpt'] }}</p>
                        <p class="text-xs text-gray-400 mt-2">
                            {{ \Carbon\Carbon::parse($highlight['date'])->diffForHumans() }} • {{ $highlight['author'] }}
                        </p>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Mood Trend Chart
    const ctx = document.getElementById('moodTrendChart').getContext('2d');
    const moodData = @json($wellnessProgress['moodTrend']);

    new Chart(ctx, {
        type: 'line',
        data: {
            labels: ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'],
            datasets: [{
                label: 'Mood Score',
                data: moodData,
                borderColor: '#4A90E2',
                backgroundColor: 'rgba(74, 144, 226, 0.1)',
                fill: true,
                tension: 0.4,
                pointBackgroundColor: '#4A90E2',
                pointBorderColor: '#fff',
                pointBorderWidth: 2,
                pointRadius: 6
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: false
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    max: 10,
                    grid: {
                        color: 'rgba(0, 0, 0, 0.05)'
                    }
                },
                x: {
                    grid: {
                        display: false
                    }
                }
            }
        }
    });
});
</script>
@endsection

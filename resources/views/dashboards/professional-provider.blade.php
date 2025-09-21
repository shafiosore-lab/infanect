@extends('layouts.provider-professional')

@section('page-title', 'Professional Dashboard')
@section('page-description', 'Manage your professional services, clients, and mental health modules')

@section('content')
@php
    use Illuminate\Support\Facades\Route;
@endphp

<!-- Key Metrics Cards -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
    <!-- Total Services -->
    <div class="bg-white overflow-hidden shadow-sm rounded-lg">
        <div class="p-5">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-8 h-8 bg-blue-500 rounded-md flex items-center justify-center">
                        <i class="fas fa-cogs text-white text-sm"></i>
                    </div>
                </div>
                <div class="ml-5 w-0 flex-1">
                    <dl>
                        <dt class="text-sm font-medium text-gray-500 truncate">Total Services</dt>
                        <dd class="text-lg font-medium text-gray-900">{{ $metrics['total_clients'] ?? 0 }}</dd>
                    </dl>
                </div>
            </div>
        </div>
        <div class="bg-gray-50 px-5 py-3">
            <div class="text-sm">
                @if(Route::has('services.index'))
                    <a href="{{ route('services.index') }}" class="font-medium text-blue-700 hover:text-blue-900">View all</a>
                @endif
            </div>
        </div>
    </div>

    <!-- Active Sessions -->
    <div class="bg-white overflow-hidden shadow-sm rounded-lg">
        <div class="p-5">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-8 h-8 bg-green-500 rounded-md flex items-center justify-center">
                        <i class="fas fa-calendar-check text-white text-sm"></i>
                    </div>
                </div>
                <div class="ml-5 w-0 flex-1">
                    <dl>
                        <dt class="text-sm font-medium text-gray-500 truncate">Active Sessions</dt>
                        <dd class="text-lg font-medium text-gray-900">{{ $metrics['active_sessions'] ?? 0 }}</dd>
                    </dl>
                </div>
            </div>
        </div>
        <div class="bg-gray-50 px-5 py-3">
            <div class="text-sm">
                @if(Route::has('bookings.index'))
                    <a href="{{ route('bookings.index') }}" class="font-medium text-green-700 hover:text-green-900">Manage</a>
                @endif
            </div>
        </div>
    </div>

    <!-- Total Earnings -->
    <div class="bg-white overflow-hidden shadow-sm rounded-lg">
        <div class="p-5">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-8 h-8 bg-yellow-500 rounded-md flex items-center justify-center">
                        <i class="fas fa-dollar-sign text-white text-sm"></i>
                    </div>
                </div>
                <div class="ml-5 w-0 flex-1">
                    <dl>
                        <dt class="text-sm font-medium text-gray-500 truncate">Monthly Revenue</dt>
                        <dd class="text-lg font-medium text-gray-900">${{ number_format($metrics['monthly_revenue'] ?? 0) }}</dd>
                    </dl>
                </div>
            </div>
        </div>
        <div class="bg-gray-50 px-5 py-3">
            <div class="text-sm">
                <a href="#" class="font-medium text-yellow-700 hover:text-yellow-900">View analytics</a>
            </div>
        </div>
    </div>

    <!-- Client Satisfaction -->
    <div class="bg-white overflow-hidden shadow-sm rounded-lg">
        <div class="p-5">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-8 h-8 bg-purple-500 rounded-md flex items-center justify-center">
                        <i class="fas fa-star text-white text-sm"></i>
                    </div>
                </div>
                <div class="ml-5 w-0 flex-1">
                    <dl>
                        <dt class="text-sm font-medium text-gray-500 truncate">Satisfaction</dt>
                        <dd class="text-lg font-medium text-gray-900">{{ $metrics['client_satisfaction'] ?? 0 }}/5</dd>
                    </dl>
                </div>
            </div>
        </div>
        <div class="bg-gray-50 px-5 py-3">
            <div class="text-sm">
                <a href="#" class="font-medium text-purple-700 hover:text-purple-900">View reviews</a>
            </div>
        </div>
    </div>
</div>

<!-- Main Dashboard Content Grid -->
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
    <!-- Left Column - 2/3 width -->
    <div class="lg:col-span-2 space-y-6">
        <!-- Upcoming Appointments -->
        <div class="bg-white shadow-sm rounded-lg">
            <div class="px-4 py-5 sm:p-6">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg leading-6 font-medium text-gray-900">Upcoming Appointments</h3>
                    @if(Route::has('bookings.index'))
                        <a href="{{ route('bookings.index') }}" class="text-sm font-medium text-blue-600 hover:text-blue-500">View all</a>
                    @endif
                </div>

                @if(isset($upcomingAppointments) && $upcomingAppointments->count() > 0)
                    <div class="overflow-hidden">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead>
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Client</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Service</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date & Time</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200">
                                @foreach($upcomingAppointments->take(5) as $appointment)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <div class="h-8 w-8 rounded-full bg-gray-200 flex items-center justify-center">
                                                <span class="text-sm font-medium text-gray-700">
                                                    {{ strtoupper(substr($appointment->client_name ?? 'C', 0, 1)) }}
                                                </span>
                                            </div>
                                            <div class="ml-4">
                                                <div class="text-sm font-medium text-gray-900">{{ $appointment->client_name ?? 'Client' }}</div>
                                                <div class="text-sm text-gray-500">{{ $appointment->client_phone ?? '' }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        {{ $appointment->service_name ?? 'Service' }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-900">{{ \Carbon\Carbon::parse($appointment->booking_date)->format('M j, Y') }}</div>
                                        <div class="text-sm text-gray-500">{{ \Carbon\Carbon::parse($appointment->booking_time ?? '00:00:00')->format('g:i A') }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">
                                            Confirmed
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                        @if(Route::has('bookings.show'))
                                            <a href="{{ route('bookings.show', $appointment->id) }}" class="text-blue-600 hover:text-blue-900 mr-3">View</a>
                                        @endif
                                        <button class="text-gray-600 hover:text-gray-900">More</button>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="text-center py-8">
                        <i class="fas fa-calendar-alt text-gray-300 text-4xl mb-4"></i>
                        <h4 class="text-lg font-medium text-gray-900 mb-2">No upcoming appointments</h4>
                        <p class="text-gray-500 mb-4">You don't have any appointments scheduled for today.</p>
                        <button onclick="openQuickBooking()" class="btn btn-primary">
                            <i class="fas fa-plus mr-2"></i>Create Appointment
                        </button>
                    </div>
                @endif
            </div>
        </div>

        <!-- Mental Health Module Usage -->
        <div class="bg-white shadow-sm rounded-lg">
            <div class="px-4 py-5 sm:p-6">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg leading-6 font-medium text-gray-900">Mental Health Module Insights</h3>
                    @if(Route::has('training.index'))
                        <a href="{{ route('training.index') }}" class="text-sm font-medium text-blue-600 hover:text-blue-500">Manage modules</a>
                    @endif
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
                    <div class="text-center p-4 bg-blue-50 rounded-lg">
                        <div class="text-2xl font-bold text-blue-600">12</div>
                        <div class="text-sm text-gray-600">Active Modules</div>
                    </div>
                    <div class="text-center p-4 bg-green-50 rounded-lg">
                        <div class="text-2xl font-bold text-green-600">89%</div>
                        <div class="text-sm text-gray-600">Completion Rate</div>
                    </div>
                    <div class="text-center p-4 bg-purple-50 rounded-lg">
                        <div class="text-2xl font-bold text-purple-600">4.2</div>
                        <div class="text-sm text-gray-600">Avg. Rating</div>
                    </div>
                </div>

                @if(isset($recentMoodData) && $recentMoodData->count() > 0)
                    <h4 class="font-medium text-gray-900 mb-3">Recent Client Mood Submissions</h4>
                    <div class="space-y-2">
                        @foreach($recentMoodData->take(3) as $mood)
                        <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                            <div class="flex items-center">
                                <div class="h-8 w-8 rounded-full bg-gray-200 flex items-center justify-center mr-3">
                                    <span class="text-sm font-medium text-gray-700">
                                        {{ strtoupper(substr($mood->client_name ?? 'C', 0, 1)) }}
                                    </span>
                                </div>
                                <div>
                                    <div class="text-sm font-medium text-gray-900">{{ $mood->client_name ?? 'Client' }}</div>
                                    <div class="text-sm text-gray-500">{{ \Carbon\Carbon::parse($mood->created_at)->diffForHumans() }}</div>
                                </div>
                            </div>
                            <div class="flex items-center">
                                @php
                                    $moodEmojis = ['happy' => '😊', 'sad' => '😔', 'anxious' => '😰', 'calm' => '😌', 'stressed' => '😤', 'okay' => '😐'];
                                    $emoji = $moodEmojis[$mood->mood] ?? '😐';
                                @endphp
                                <span class="text-lg mr-2">{{ $emoji }}</span>
                                <span class="text-sm font-medium text-gray-700 capitalize">{{ $mood->mood }}</span>
                            </div>
                        </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Right Column - 1/3 width -->
    <div class="space-y-6">
        <!-- Quick Actions -->
        <div class="bg-white shadow-sm rounded-lg">
            <div class="px-4 py-5 sm:p-6">
                <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4">Quick Actions</h3>
                <div class="space-y-3">
                    <button onclick="openQuickBooking()" class="w-full flex items-center justify-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700">
                        <i class="fas fa-plus mr-2"></i>Create Booking
                    </button>

                    @if(Route::has('services.create'))
                        <a href="{{ route('services.create') }}" class="w-full flex items-center justify-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                            <i class="fas fa-cogs mr-2"></i>Add Service
                        </a>
                    @endif

                    <button onclick="openBulkSMS()" class="w-full flex items-center justify-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                        <i class="fas fa-sms mr-2"></i>Send Bulk SMS
                    </button>

                    @if(Route::has('ai.chat'))
                        <a href="{{ route('ai.chat') }}" class="w-full flex items-center justify-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                            <i class="fas fa-robot mr-2"></i>AI Assistant
                        </a>
                    @endif
                </div>
            </div>
        </div>

        <!-- KYC Status -->
        @php
            $kycStatus = $providerData['kyc_status'] ?? 'pending';
            $kycStatusConfig = [
                'approved' => ['bg' => 'bg-green-50', 'text' => 'text-green-700', 'icon' => 'fas fa-check-circle', 'title' => 'KYC Approved'],
                'pending' => ['bg' => 'bg-yellow-50', 'text' => 'text-yellow-700', 'icon' => 'fas fa-clock', 'title' => 'KYC Pending'],
                'rejected' => ['bg' => 'bg-red-50', 'text' => 'text-red-700', 'icon' => 'fas fa-times-circle', 'title' => 'KYC Rejected'],
            ];
            $config = $kycStatusConfig[$kycStatus] ?? $kycStatusConfig['pending'];
        @endphp

        <div class="bg-white shadow-sm rounded-lg">
            <div class="px-4 py-5 sm:p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-8 h-8 {{ $config['bg'] }} rounded-md flex items-center justify-center">
                            <i class="{{ $config['icon'] }} {{ $config['text'] }} text-sm"></i>
                        </div>
                    </div>
                    <div class="ml-3">
                        <h3 class="text-sm font-medium text-gray-900">{{ $config['title'] }}</h3>
                        <div class="mt-1 text-sm text-gray-500">
                            @if($kycStatus === 'approved')
                                Your account is verified and active.
                            @elseif($kycStatus === 'pending')
                                Your documents are under review.
                            @else
                                Please resubmit your documents.
                            @endif
                        </div>
                    </div>
                </div>

                @if($kycStatus !== 'approved' && Route::has('provider.register'))
                    <div class="mt-3">
                        <a href="{{ route('provider.register') }}" class="text-sm font-medium text-blue-600 hover:text-blue-500">
                            @if($kycStatus === 'pending')
                                Update Documents
                            @else
                                Submit Documents
                            @endif
                        </a>
                    </div>
                @endif
            </div>
        </div>

        <!-- Client Engagement Stats -->
        @if(isset($engagementStats))
        <div class="bg-white shadow-sm rounded-lg">
            <div class="px-4 py-5 sm:p-6">
                <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4">Client Engagement</h3>

                <div class="space-y-4">
                    <div>
                        <div class="flex justify-between text-sm">
                            <span>Retention Rate</span>
                            <span class="font-medium">{{ $engagementStats['retention_rate'] ?? 0 }}%</span>
                        </div>
                        <div class="mt-1 bg-gray-200 rounded-full h-2">
                            <div class="bg-green-500 h-2 rounded-full" style="width: {{ $engagementStats['retention_rate'] ?? 0 }}%"></div>
                        </div>
                    </div>

                    <div>
                        <div class="flex justify-between text-sm">
                            <span>Avg Sessions/Client</span>
                            <span class="font-medium">{{ $engagementStats['average_sessions_per_client'] ?? 0 }}</span>
                        </div>
                    </div>

                    <div>
                        <div class="flex justify-between text-sm">
                            <span>Total Bookings</span>
                            <span class="font-medium">{{ $engagementStats['total_bookings'] ?? 0 }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endif

        <!-- Recent Reviews -->
        @if(isset($recentFeedback) && $recentFeedback->count() > 0)
        <div class="bg-white shadow-sm rounded-lg">
            <div class="px-4 py-5 sm:p-6">
                <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4">Recent Reviews</h3>

                <div class="space-y-4">
                    @foreach($recentFeedback->take(2) as $review)
                    <div class="border-l-4 border-blue-400 pl-4">
                        <div class="flex items-center mb-1">
                            @for($i = 1; $i <= 5; $i++)
                                <i class="fas fa-star {{ $i <= ($review->rating ?? 5) ? 'text-yellow-400' : 'text-gray-300' }} text-xs"></i>
                            @endfor
                            <span class="ml-2 text-sm text-gray-600">{{ $review->client_name ?? 'Anonymous' }}</span>
                        </div>
                        <p class="text-sm text-gray-700">{{ Str::limit($review->comment ?? 'Great service!', 80) }}</p>
                        <p class="text-xs text-gray-500 mt-1">{{ \Carbon\Carbon::parse($review->created_at)->diffForHumans() }}</p>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
        @endif
    </div>
</div>

<!-- Revenue Analytics Chart -->
@if(isset($revenueData))
<div class="bg-white shadow-sm rounded-lg">
    <div class="px-4 py-5 sm:p-6">
        <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4">Revenue Analytics</h3>
        <div class="h-64">
            <canvas id="revenueChart"></canvas>
        </div>
    </div>
</div>
@endif

<!-- Wellness Scores Section -->
<div class="bg-white shadow-sm rounded-lg mb-8">
    <div class="px-4 py-5 sm:p-6">
        <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4">Wellness Scores</h3>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="text-center">
                <svg width="120" height="120" viewBox="0 0 120 120">
                    <circle cx="60" cy="60" r="50" fill="none" stroke="#e5e7eb" stroke-width="8"></circle>
                    <circle id="physical-health-path" cx="60" cy="60" r="50" fill="none" stroke="#10b981" stroke-width="8" stroke-dasharray="0 314" transform="rotate(-90 60 60)"></circle>
                </svg>
                <p class="mt-4 text-sm font-medium text-gray-900">Physical Health</p>
                <p id="physical-health-score" class="text-2xl font-bold text-green-600">0%</p>
            </div>
            <div class="text-center">
                <svg width="120" height="120" viewBox="0 0 120 120">
                    <circle cx="60" cy="60" r="50" fill="none" stroke="#e5e7eb" stroke-width="8"></circle>
                    <circle id="overall-wellness-path" cx="60" cy="60" r="50" fill="none" stroke="#8b5cf6" stroke-width="8" stroke-dasharray="0 314" transform="rotate(-90 60 60)"></circle>
                </svg>
                <p class="mt-4 text-sm font-medium text-gray-900">Overall Wellness</p>
                <p id="overall-wellness-score" class="text-2xl font-bold text-purple-600">0%</p>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const scoreElements = {
        'Physical Health': { element: 'physical-health-score', path: 'physical-health-path' },
        'Overall Wellness': { element: 'overall-wellness-score', path: 'overall-wellness-path' }
    };

    fetch('/api/wellness-scores')
        .then(response => response.json())
        .then(scores => {
            for (const [key, { element, path }] of Object.entries(scoreElements)) {
                if (scores[key] !== undefined) {
                    const score = Math.min(Math.max(scores[key], 0), 100);
                    document.getElementById(element).textContent = score + '%';
                    const circle = document.getElementById(path);
                    const radius = circle.r.baseVal.value;
                    const circumference = 2 * Math.PI * radius;
                    const offset = circumference - (score / 100) * circumference;
                    circle.style.strokeDasharray = `${circumference} ${circumference}`;
                    circle.style.strokeDashoffset = offset;
                }
            }
        })
        .catch(err => console.error('Error fetching wellness scores:', err));
});
</script>
@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
// ...existing code...

@if(isset($revenueData))
// Revenue Chart
document.addEventListener('DOMContentLoaded', function() {
    const ctx = document.getElementById('revenueChart');
    if (ctx) {
        new Chart(ctx, {
            type: 'line',
            data: {
                labels: {!! json_encode($revenueData['months'] ?? []) !!},
                datasets: [{
                    label: 'Revenue ($)',
                    data: {!! json_encode($revenueData['revenues'] ?? []) !!},
                    borderColor: 'rgb(59, 130, 246)',
                    backgroundColor: 'rgba(59, 130, 246, 0.1)',
                    borderWidth: 2,
                    fill: true,
                    tension: 0.4
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
                        ticks: {
                            callback: function(value) {
                                return '$' + value.toLocaleString();
                            }
                        }
                    }
                }
            }
        });
    }
});
@endif
</script>
@endpush
@endsection

@extends('layouts.app')

@section('content')
<div class="flex min-h-screen">
    <aside class="w-64 bg-gray-800 text-white p-4 hidden md:block">
        @include('layouts.partials.user-sidebar')
    </aside>
    <main class="flex-1">
<div class="client-dashboard p-4 md:ml-0">
    {{-- Welcome Header --}}
    <div class="welcome-section mb-4">
        <div class="row align-items-center">
            <div class="col-lg-8">
                <div class="d-flex align-items-center mb-3">
                    <div class="user-avatar me-3">
                        <div class="avatar-circle bg-primary" style="width: 60px; height: 60px; font-size: 1.5rem;">
                            {{ strtoupper(substr($user->name ?? 'U', 0, 2)) }}
                        </div>
                    </div>
                    <div>
                        <h2 class="mb-1">Welcome back, {{ $user->name ?? 'User' }}! 👋</h2>
                        <p class="text-muted mb-0">Ready to continue your wellness journey today?</p>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 text-lg-end">
                <div class="quick-actions">
                    <button class="btn btn-primary me-2">
                        <i class="fas fa-calendar-plus me-1"></i> Book Session
                    </button>
                    <button class="btn btn-outline-secondary">
                        <i class="fas fa-comments me-1"></i> Messages
                    </button>
                </div>
            </div>
        </div>
    </div>

    {{-- Progress Overview Cards --}}
    <div class="row g-3 mb-4">
        <div class="col-sm-6 col-lg-3">
            <div class="card progress-card h-100 border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start mb-2">
                        <div>
                            <div class="text-muted small">Current Streak</div>
                            <h3 class="mb-0 text-primary">{{ $wellnessProgress['currentStreak'] }} days</h3>
                        </div>
                        <div class="icon-container bg-primary bg-opacity-10 p-2 rounded">
                            <i class="fas fa-fire text-primary fa-lg"></i>
                        </div>
                    </div>
                    <div class="small text-success">
                        <i class="fas fa-arrow-up me-1"></i> Keep it up!
                    </div>
                </div>
            </div>
        </div>

        <div class="col-sm-6 col-lg-3">
            <div class="card progress-card h-100 border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start mb-2">
                        <div>
                            <div class="text-muted small">Wellness Score</div>
                            <h3 class="mb-0 text-success">{{ $wellnessProgress['progressScore'] }}%</h3>
                        </div>
                        <div class="icon-container bg-success bg-opacity-10 p-2 rounded">
                            <i class="fas fa-heart text-success fa-lg"></i>
                        </div>
                    </div>
                    <div class="progress" style="height: 4px;">
                        <div class="progress-bar bg-success" style="width: {{ $wellnessProgress['progressScore'] }}%"></div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-sm-6 col-lg-3">
            <div class="card progress-card h-100 border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start mb-2">
                        <div>
                            <div class="text-muted small">Weekly Progress</div>
                            <h3 class="mb-0 text-warning">{{ $wellnessProgress['completedThisWeek'] }}/{{ $wellnessProgress['weeklyGoal'] }}</h3>
                        </div>
                        <div class="icon-container bg-warning bg-opacity-10 p-2 rounded">
                            <i class="fas fa-target text-warning fa-lg"></i>
                        </div>
                    </div>
                    <div class="small text-muted">
                        {{ $wellnessProgress['weeklyGoal'] - $wellnessProgress['completedThisWeek'] }} sessions to goal
                    </div>
                </div>
            </div>
        </div>

        <div class="col-sm-6 col-lg-3">
            <div class="card progress-card h-100 border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start mb-2">
                        <div>
                            <div class="text-muted small">Total Sessions</div>
                            <h3 class="mb-0 text-info">{{ $wellnessProgress['totalSessions'] }}</h3>
                        </div>
                        <div class="icon-container bg-info bg-opacity-10 p-2 rounded">
                            <i class="fas fa-graduation-cap text-info fa-lg"></i>
                        </div>
                    </div>
                    <div class="small text-muted">
                        Amazing progress!
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        {{-- Wellness Progress Chart --}}
        <div class="col-lg-8 mb-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-white d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Your Wellness Journey</h5>
                    <div class="btn-group btn-group-sm">
                        <button class="btn btn-outline-secondary active">Week</button>
                        <button class="btn btn-outline-secondary">Month</button>
                        <button class="btn btn-outline-secondary">Year</button>
                    </div>
                </div>
                <div class="card-body">
                    <div class="wellness-categories mb-4">
                        @foreach($wellnessProgress['categories'] as $category)
                        <div class="category-item mb-3">
                            <div class="d-flex justify-content-between align-items-center mb-1">
                                <span class="fw-medium">{{ $category['name'] }}</span>
                                <span class="text-muted">{{ $category['progress'] }}%</span>
                            </div>
                            <div class="progress" style="height: 8px;">
                                <div class="progress-bar"
                                     style="width: {{ $category['progress'] }}%; background-color: {{ $category['color'] }}">
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                    <canvas id="moodTrendChart" style="height: 200px;"></canvas>
                </div>
            </div>
        </div>

        {{-- Quick Actions & Achievements --}}
        <div class="col-lg-4 mb-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-white">
                    <h5 class="mb-0">Your Achievements</h5>
                </div>
                <div class="card-body">
                    <div class="achievements-grid">
                        @foreach($achievementBadges as $badge)
                        <div class="achievement-badge {{ $badge['earned'] ? 'earned' : 'locked' }} mb-2">
                            <div class="d-flex align-items-center">
                                <div class="badge-icon me-2 {{ $badge['earned'] ? 'text-warning' : 'text-muted' }}">
                                    <i class="fas fa-{{ $badge['icon'] }}"></i>
                                </div>
                                <div class="flex-grow-1">
                                    <div class="fw-semibold small">{{ $badge['name'] }}</div>
                                    <div class="text-muted" style="font-size: 0.75rem;">{{ $badge['description'] }}</div>
                                </div>
                                @if($badge['earned'])
                                    <div class="text-success"><i class="fas fa-check-circle"></i></div>
                                @endif
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Upcoming Sessions & Favorite Providers --}}
    <div class="row">
        <div class="col-lg-6 mb-4">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Upcoming Sessions</h5>
                    <a href="#" class="btn btn-sm btn-outline-primary">View All</a>
                </div>
                <div class="card-body p-0">
                    <div class="upcoming-sessions">
                        <div class="session-item p-3 border-bottom">
                            <div class="d-flex align-items-center">
                                <div class="session-date me-3 text-center">
                                    <div class="fw-bold text-primary">15</div>
                                    <div class="small text-muted">Dec</div>
                                </div>
                                <div class="flex-grow-1">
                                    <h6 class="mb-1">Family Therapy Session</h6>
                                    <div class="text-muted small mb-1">Dr. Sarah Wilson</div>
                                    <div class="text-muted small">
                                        <i class="fas fa-clock me-1"></i> 2:00 PM - 3:00 PM
                                    </div>
                                </div>
                                <div class="session-actions">
                                    <button class="btn btn-sm btn-outline-primary">Join</button>
                                </div>
                            </div>
                        </div>

                        <div class="session-item p-3 border-bottom">
                            <div class="d-flex align-items-center">
                                <div class="session-date me-3 text-center">
                                    <div class="fw-bold text-primary">18</div>
                                    <div class="small text-muted">Dec</div>
                                </div>
                                <div class="flex-grow-1">
                                    <h6 class="mb-1">Parenting Workshop</h6>
                                    <div class="text-muted small mb-1">Maria Rodriguez</div>
                                    <div class="text-muted small">
                                        <i class="fas fa-clock me-1"></i> 10:00 AM - 11:30 AM
                                    </div>
                                </div>
                                <div class="session-actions">
                                    <button class="btn btn-sm btn-outline-secondary">Reschedule</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-6 mb-4">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white">
                    <h5 class="mb-0">Your Favorite Providers</h5>
                </div>
                <div class="card-body">
                    @foreach($favoriteProviders as $provider)
                    <div class="provider-item d-flex align-items-center mb-3">
                        <div class="avatar-circle me-3" style="background-color: {{ $provider['color'] }};">
                            {{ $provider['avatar'] }}
                        </div>
                        <div class="flex-grow-1">
                            <h6 class="mb-1">{{ $provider['name'] }}</h6>
                            <div class="text-muted small">{{ $provider['specialty'] }}</div>
                            <div class="d-flex align-items-center mt-1">
                                <div class="text-warning me-2">
                                    @for($i = 0; $i < 5; $i++)
                                        <i class="fas fa-star{{ $i < floor($provider['rating']) ? '' : '-o' }}"></i>
                                    @endfor
                                </div>
                                <small class="text-muted">{{ $provider['totalSessions'] }} sessions</small>
                            </div>
                        </div>
                        <button class="btn btn-sm btn-outline-primary">Book</button>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    {{-- Community Highlights --}}
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-header bg-white">
            <h5 class="mb-0">Community Highlights</h5>
        </div>
        <div class="card-body">
            <div class="row">
                @foreach($communityHighlights as $highlight)
                <div class="col-md-4 mb-3">
                    <div class="community-card h-100 p-3 border rounded">
                        <div class="d-flex align-items-center mb-2">
                            <span class="badge bg-primary me-2">
                                @if($highlight['type'] === 'success_story')
                                    <i class="fas fa-trophy"></i>
                                @elseif($highlight['type'] === 'tip')
                                    <i class="fas fa-lightbulb"></i>
                                @else
                                    <i class="fas fa-calendar"></i>
                                @endif
                            </span>
                            <small class="text-muted">{{ $highlight['date']->diffForHumans() }}</small>
                        </div>
                        <h6 class="mb-2">{{ $highlight['title'] }}</h6>
                        <p class="small text-muted mb-2">{{ $highlight['excerpt'] }}</p>
                        <div class="d-flex justify-content-between align-items-center">
                            <small class="text-muted">by {{ $highlight['author'] }}</small>
                            <a href="#" class="btn btn-sm btn-outline-primary">Read More</a>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
</div>
    </main>
</div>

<style>
.client-dashboard .progress-card {
    transition: transform 0.2s ease, box-shadow 0.2s ease;
}

.client-dashboard .progress-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(0,0,0,0.1) !important;
}

.achievement-badge.earned {
    opacity: 1;
}

.achievement-badge.locked {
    opacity: 0.6;
}

.session-item:last-child {
    border-bottom: none !important;
}

.community-card {
    transition: box-shadow 0.2s ease;
}

.community-card:hover {
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
}

.wellness-categories .progress {
    border-radius: 4px;
}
</style>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Mood Trend Chart
        const moodCtx = document.getElementById('moodTrendChart');
        if (moodCtx) {
            new Chart(moodCtx.getContext('2d'), {
                type: 'line',
                data: {
                    labels: ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'],
                    datasets: [{
                        label: 'Mood Score',
                        data: {!! json_encode($wellnessProgress['moodTrend']) !!},
                        borderColor: '#4A90E2',
                        backgroundColor: 'rgba(74, 144, 226, 0.1)',
                        tension: 0.4,
                        fill: true,
                        pointBackgroundColor: '#4A90E2',
                        pointBorderColor: '#fff',
                        pointBorderWidth: 2,
                        pointRadius: 4
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { display: false }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            max: 10,
                            grid: { color: 'rgba(0,0,0,0.05)' },
                            ticks: { color: '#666' }
                        },
                        x: {
                            grid: { display: false },
                            ticks: { color: '#666' }
                        }
                    }
                }
            });
        }
    });
</script>
@endsection

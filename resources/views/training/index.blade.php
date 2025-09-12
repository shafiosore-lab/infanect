@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <!-- Sidebar -->
        <div class="col-md-3">
            @include('partials.sidebar')
        </div>

        <!-- Main Content -->
        <div class="col-md-9">
            <!-- Mood-Based Welcome Message -->
            @if($recentMood)
            <div class="alert alert-info mb-4">
                <div class="d-flex align-items-center">
                    <div class="me-3">
                        @if($recentMood->mood_score <= 3)
                            🤗
                        @elseif($recentMood->mood_score <= 5)
                            💪
                        @elseif($recentMood->mood_score <= 7)
                            😊
                        @else
                            🎉
                        @endif
                    </div>
                    <div>
                        <h6 class="mb-1">Personalized for You</h6>
                        <p class="mb-0">{{ $moodBasedMessage }}</p>
                    </div>
                </div>
            </div>
            @endif

            <!-- Header Section -->
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h1 class="h3 mb-0 text-gray-800">🎮 Training Modules</h1>
                    <p class="text-muted">Level up your parenting skills through interactive learning</p>
                </div>
                <div class="d-flex gap-2">
                    <span class="badge bg-primary">Level {{ $userLevel ?? 1 }}</span>
                    <span class="badge bg-warning">{{ $totalPoints ?? 0 }} XP</span>
                    @if(!$recentMood)
                        <button class="btn btn-sm btn-outline-primary" onclick="openMoodSubmission()">
                            <i class="bi bi-heart me-1"></i>Mood Check-in
                        </button>
                    @endif
                </div>
            </div>

            <!-- ...existing progress overview code... -->

            <!-- Training Categories with Mood-Based Prioritization -->
            <div class="row">
                @foreach($trainingCategories ?? [] as $category)
                <div class="col-lg-6 mb-4">
                    <div class="card shadow-sm border-0 h-100 {{ $category['priority'] === 'urgent' ? 'border-warning' : ($category['priority'] === 'high' ? 'border-info' : '') }}">
                        <div class="card-header {{ $category['priority'] === 'urgent' ? 'bg-gradient-warning' : ($category['priority'] === 'high' ? 'bg-gradient-info' : 'bg-gradient-primary') }} text-white">
                            <div class="d-flex justify-content-between align-items-center">
                                <h5 class="mb-0">{{ $category['icon'] }} {{ $category['name'] }}</h5>
                                @if($category['priority'] === 'urgent')
                                    <span class="badge bg-light text-warning">⚡ Recommended for You</span>
                                @elseif($category['priority'] === 'high')
                                    <span class="badge bg-light text-info">🎯 Suggested</span>
                                @endif
                            </div>
                        </div>
                        <div class="card-body">
                            <p class="text-muted">{{ $category['description'] }}</p>

                            <!-- Module Progress -->
                            <div class="mb-3">
                                <div class="d-flex justify-content-between mb-1">
                                    <span class="small">Progress</span>
                                    <span class="small">{{ $category['progress'] }}%</span>
                                </div>
                                <div class="progress">
                                    <div class="progress-bar" role="progressbar" style="width: {{ $category['progress'] }}%"></div>
                                </div>
                            </div>

                            <!-- Modules List -->
                            <div class="modules-list">
                                @foreach($category['modules'] ?? [] as $module)
                                <div class="d-flex align-items-center mb-2 p-2 rounded {{ $module['completed'] ? 'bg-success-light' : 'bg-light' }} {{ $module['recommended'] ?? false ? 'border border-warning' : '' }}">
                                    <div class="me-3">
                                        @if($module['completed'])
                                            <i class="fas fa-check-circle text-success"></i>
                                        @elseif($module['locked'])
                                            <i class="fas fa-lock text-muted"></i>
                                        @else
                                            <i class="fas fa-play-circle text-primary"></i>
                                        @endif
                                    </div>
                                    <div class="flex-grow-1">
                                        <h6 class="mb-0">
                                            {{ $module['title'] }}
                                            @if($module['recommended'] ?? false)
                                                <span class="badge bg-warning text-dark ms-2">Recommended</span>
                                            @endif
                                        </h6>
                                        <small class="text-muted">{{ $module['duration'] }} • {{ $module['points'] }} XP</small>
                                    </div>
                                    <div>
                                        @if($module['completed'])
                                            <span class="badge bg-success">✓</span>
                                        @elseif(!$module['locked'])
                                            <a href="{{ route('training.module', $module['id']) }}" class="btn btn-sm {{ $module['recommended'] ?? false ? 'btn-warning' : 'btn-primary' }}" data-module-id="{{ $module['id'] }}">
                                                {{ $module['recommended'] ?? false ? 'Start Now' : 'Start' }}
                                            </a>
                                        @endif
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>

            <!-- ...existing achievements section code... -->
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Track module engagement with mood context
    const moduleButtons = document.querySelectorAll('[data-module-id]');
    moduleButtons.forEach(button => {
        button.addEventListener('click', function() {
            const moduleId = this.dataset.moduleId;
            const moodId = {{ $recentMood->id ?? 'null' }};

            // Track analytics with mood context
            if (typeof gtag !== 'undefined') {
                gtag('event', 'module_start', {
                    'module_id': moduleId,
                    'mood_id': moodId,
                    'category': 'training'
                });
            }

            // Store mood context for the training session
            if (moodId) {
                sessionStorage.setItem('currentMoodId', moodId);
            }
        });
    });
});
</script>
@endpush

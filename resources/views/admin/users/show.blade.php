@extends('layouts.super-admin')

@section('page-title', 'User Details - ' . ($user->name ?? 'Unknown'))
@section('page-description', 'Complete user profile and activity overview')

@section('content')
<div class="container-fluid py-4">
    {{-- Header with Actions --}}
    <div class="d-flex justify-content-between align-items-start mb-4">
        <div class="d-flex align-items-center">
            <a href="{{ route('admin.users.index') }}" class="btn btn-outline-secondary me-3">
                <i class="fas fa-arrow-left"></i>
            </a>
            <div>
                <h1 class="h3 fw-bold text-primary mb-1">{{ $user->name ?? 'Unknown User' }}</h1>
                <p class="text-muted mb-0">
                    {{ $user->role->name ?? 'No Role' }} •
                    Member since {{ $user->created_at ? $user->created_at->format('M Y') : 'Unknown' }}
                </p>
            </div>
        </div>
        <div class="d-flex gap-2">
            <button class="btn btn-outline-primary" onclick="loginAsUser({{ $user->id }})">
                <i class="fas fa-sign-in-alt me-1"></i>Login As User
            </button>
            <a href="{{ route('admin.users.edit', $user->id) }}" class="btn btn-primary">
                <i class="fas fa-edit me-1"></i>Edit User
            </a>
        </div>
    </div>

    {{-- User Status Cards --}}
    <div class="row g-4 mb-4">
        <div class="col-md-3">
            <div class="card shadow-sm border-0 rounded-4 p-3">
                <div class="d-flex align-items-center">
                    <div class="icon bg-{{ $user->email_verified_at ? 'success' : 'warning' }} text-white rounded-circle p-3 me-3">
                        <i class="fas fa-{{ $user->email_verified_at ? 'check' : 'clock' }} fa-lg"></i>
                    </div>
                    <div>
                        <h6 class="mb-1">Account Status</h6>
                        <small class="text-muted">
                            {{ $user->email_verified_at ? 'Verified' : 'Pending Verification' }}
                        </small>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card shadow-sm border-0 rounded-4 p-3">
                <div class="d-flex align-items-center">
                    <div class="icon bg-info text-white rounded-circle p-3 me-3">
                        <i class="fas fa-calendar-check fa-lg"></i>
                    </div>
                    <div>
                        <h6 class="mb-1">Total Bookings</h6>
                        <h4 class="mb-0">{{ $userStats['total_bookings'] ?? 0 }}</h4>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card shadow-sm border-0 rounded-4 p-3">
                <div class="d-flex align-items-center">
                    <div class="icon bg-success text-white rounded-circle p-3 me-3">
                        <i class="fas fa-heart fa-lg"></i>
                    </div>
                    <div>
                        <h6 class="mb-1">Mood Submissions</h6>
                        <h4 class="mb-0">{{ $userStats['mood_submissions'] ?? 0 }}</h4>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card shadow-sm border-0 rounded-4 p-3">
                <div class="d-flex align-items-center">
                    <div class="icon bg-primary text-white rounded-circle p-3 me-3">
                        <i class="fas fa-clock fa-lg"></i>
                    </div>
                    <div>
                        <h6 class="mb-1">Last Active</h6>
                        <small class="text-muted">
                            {{ $user->updated_at ? $user->updated_at->diffForHumans() : 'Never' }}
                        </small>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Main Content with Tabs --}}
    <div class="card shadow-sm border-0 rounded-4">
        <div class="card-header border-0">
            <ul class="nav nav-tabs card-header-tabs" role="tablist">
                <li class="nav-item">
                    <button class="nav-link active" data-bs-toggle="tab" data-bs-target="#profile-tab">
                        <i class="fas fa-user me-2"></i>Profile
                    </button>
                </li>
                <li class="nav-item">
                    <button class="nav-link" data-bs-toggle="tab" data-bs-target="#bookings-tab">
                        <i class="fas fa-calendar me-2"></i>Bookings
                        <span class="badge bg-primary ms-1">{{ $userStats['total_bookings'] ?? 0 }}</span>
                    </button>
                </li>
                <li class="nav-item">
                    <button class="nav-link" data-bs-toggle="tab" data-bs-target="#mood-tab">
                        <i class="fas fa-brain me-2"></i>Mood Analytics
                    </button>
                </li>
                @if($user->role && str_contains($user->role->slug ?? '', 'provider'))
                    <li class="nav-item">
                        <button class="nav-link" data-bs-toggle="tab" data-bs-target="#provider-tab">
                            <i class="fas fa-briefcase me-2"></i>Provider Details
                        </button>
                    </li>
                @endif
                <li class="nav-item">
                    <button class="nav-link" data-bs-toggle="tab" data-bs-target="#activity-tab">
                        <i class="fas fa-history me-2"></i>Activity Log
                    </button>
                </li>
            </ul>
        </div>
        <div class="card-body">
            <div class="tab-content">
                {{-- Profile Tab --}}
                <div class="tab-pane fade show active" id="profile-tab">
                    <div class="row">
                        <div class="col-md-6">
                            <h5 class="fw-semibold mb-3">Personal Information</h5>
                            <table class="table table-borderless">
                                <tr>
                                    <td class="fw-semibold">Full Name:</td>
                                    <td>{{ $user->name ?? 'Not provided' }}</td>
                                </tr>
                                <tr>
                                    <td class="fw-semibold">Email:</td>
                                    <td>
                                        {{ $user->email ?? 'Not provided' }}
                                        @if($user->email_verified_at)
                                            <span class="badge bg-success ms-2">Verified</span>
                                        @else
                                            <span class="badge bg-warning ms-2">Unverified</span>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <td class="fw-semibold">Phone:</td>
                                    <td>{{ $user->phone ?? 'Not provided' }}</td>
                                </tr>
                                <tr>
                                    <td class="fw-semibold">Department:</td>
                                    <td>{{ $user->department ?? 'Not specified' }}</td>
                                </tr>
                                <tr>
                                    <td class="fw-semibold">Role:</td>
                                    <td>
                                        <span class="badge bg-primary">{{ $user->role->name ?? 'No Role' }}</span>
                                    </td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <h5 class="fw-semibold mb-3">Account Details</h5>
                            <table class="table table-borderless">
                                <tr>
                                    <td class="fw-semibold">User ID:</td>
                                    <td>{{ $user->id }}</td>
                                </tr>
                                <tr>
                                    <td class="fw-semibold">Registration Date:</td>
                                    <td>{{ $user->created_at ? $user->created_at->format('M d, Y g:i A') : 'Unknown' }}</td>
                                </tr>
                                <tr>
                                    <td class="fw-semibold">Last Updated:</td>
                                    <td>{{ $user->updated_at ? $user->updated_at->format('M d, Y g:i A') : 'Never' }}</td>
                                </tr>
                                <tr>
                                    <td class="fw-semibold">Email Verified:</td>
                                    <td>{{ $user->email_verified_at ? $user->email_verified_at->format('M d, Y g:i A') : 'Not verified' }}</td>
                                </tr>
                            </table>

                            {{-- Quick Actions --}}
                            <div class="mt-4">
                                <h6 class="fw-semibold mb-3">Quick Actions</h6>
                                <div class="d-grid gap-2">
                                    @if(!$user->email_verified_at)
                                        <button class="btn btn-success btn-sm" onclick="verifyUser({{ $user->id }})">
                                            <i class="fas fa-check me-2"></i>Verify Email
                                        </button>
                                    @endif
                                    <button class="btn btn-info btn-sm" onclick="resetPassword({{ $user->id }})">
                                        <i class="fas fa-key me-2"></i>Reset Password
                                    </button>
                                    <button class="btn btn-warning btn-sm" onclick="suspendUser({{ $user->id }})">
                                        <i class="fas fa-pause me-2"></i>Suspend Account
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Bookings Tab --}}
                <div class="tab-pane fade" id="bookings-tab">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h5 class="fw-semibold mb-0">Booking History</h5>
                        <a href="{{ route('admin.bookings.create') }}?user_id={{ $user->id }}" class="btn btn-primary btn-sm">
                            <i class="fas fa-plus me-1"></i>Create Booking
                        </a>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead class="table-light">
                                <tr>
                                    <th>Service/Provider</th>
                                    <th>Date & Time</th>
                                    <th>Status</th>
                                    <th>Amount</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($userBookings ?? [] as $booking)
                                    <tr>
                                        <td>
                                            <div>
                                                <div class="fw-semibold">{{ $booking->service->name ?? 'Service' }}</div>
                                                <small class="text-muted">{{ $booking->provider->business_name ?? 'Provider' }}</small>
                                            </div>
                                        </td>
                                        <td>
                                            <div>
                                                <div>{{ \Carbon\Carbon::parse($booking->booking_date)->format('M d, Y') }}</div>
                                                <small class="text-muted">{{ $booking->booking_time ? \Carbon\Carbon::parse($booking->booking_time)->format('g:i A') : 'Time TBD' }}</small>
                                            </div>
                                        </td>
                                        <td>
                                            @php
                                                $statusColors = ['pending' => 'warning', 'confirmed' => 'success', 'completed' => 'primary', 'cancelled' => 'danger'];
                                                $statusColor = $statusColors[$booking->status] ?? 'secondary';
                                            @endphp
                                            <span class="badge bg-{{ $statusColor }}">{{ ucfirst($booking->status) }}</span>
                                        </td>
                                        <td>${{ number_format($booking->amount ?? 0, 2) }}</td>
                                        <td>
                                            <a href="{{ route('admin.bookings.show', $booking->id) }}" class="btn btn-sm btn-outline-primary">
                                                View
                                            </a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center text-muted py-4">No bookings found</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                {{-- Mood Analytics Tab --}}
                <div class="tab-pane fade" id="mood-tab">
                    <div class="row">
                        <div class="col-md-8">
                            <h5 class="fw-semibold mb-3">Mood Tracking Over Time</h5>
                            <canvas id="moodChart" height="300"></canvas>
                        </div>
                        <div class="col-md-4">
                            <h5 class="fw-semibold mb-3">Mood Distribution</h5>
                            <canvas id="moodDistributionChart" height="200"></canvas>

                            <div class="mt-4">
                                <h6 class="fw-semibold mb-2">Recent Mood Submissions</h6>
                                @forelse($userMoods ?? [] as $mood)
                                    <div class="d-flex justify-content-between align-items-center mb-2 p-2 bg-light rounded">
                                        <div>
                                            <span class="badge bg-primary">{{ ucfirst($mood->mood) }}</span>
                                            <small class="text-muted ms-2">Score: {{ $mood->mood_score }}/10</small>
                                        </div>
                                        <small class="text-muted">{{ $mood->created_at->format('M d') }}</small>
                                    </div>
                                @empty
                                    <p class="text-muted">No mood submissions yet</p>
                                @endforelse
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Provider Tab (if applicable) --}}
                @if($user->role && str_contains($user->role->slug ?? '', 'provider'))
                    <div class="tab-pane fade" id="provider-tab">
                        <div class="row">
                            <div class="col-md-6">
                                <h5 class="fw-semibold mb-3">Provider Information</h5>
                                @if($providerProfile ?? null)
                                    <table class="table table-borderless">
                                        <tr>
                                            <td class="fw-semibold">Business Name:</td>
                                            <td>{{ $providerProfile->business_name ?? 'Not set' }}</td>
                                        </tr>
                                        <tr>
                                            <td class="fw-semibold">Provider Type:</td>
                                            <td>{{ $providerProfile->provider_type ?? 'Unknown' }}</td>
                                        </tr>
                                        <tr>
                                            <td class="fw-semibold">KYC Status:</td>
                                            <td>
                                                @php
                                                    $kycColors = ['not_started' => 'secondary', 'pending' => 'warning', 'approved' => 'success', 'rejected' => 'danger'];
                                                    $kycColor = $kycColors[$providerProfile->kyc_status ?? 'not_started'] ?? 'secondary';
                                                @endphp
                                                <span class="badge bg-{{ $kycColor }}">{{ ucfirst($providerProfile->kyc_status ?? 'Not Started') }}</span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="fw-semibold">Hourly Rate:</td>
                                            <td>${{ $providerProfile->hourly_rate ?? 'Not set' }}</td>
                                        </tr>
                                        <tr>
                                            <td class="fw-semibold">Experience:</td>
                                            <td>{{ $providerProfile->years_experience ?? 0 }} years</td>
                                        </tr>
                                    </table>
                                @else
                                    <p class="text-muted">Provider profile not yet created</p>
                                @endif
                            </div>
                            <div class="col-md-6">
                                <h5 class="fw-semibold mb-3">Performance Metrics</h5>
                                <div class="row g-3">
                                    <div class="col-6">
                                        <div class="card bg-primary text-white">
                                            <div class="card-body text-center">
                                                <h3>{{ $providerStats['total_bookings'] ?? 0 }}</h3>
                                                <small>Total Bookings</small>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="card bg-success text-white">
                                            <div class="card-body text-center">
                                                <h3>${{ number_format($providerStats['total_revenue'] ?? 0) }}</h3>
                                                <small>Total Revenue</small>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif

                {{-- Activity Log Tab --}}
                <div class="tab-pane fade" id="activity-tab">
                    <h5 class="fw-semibold mb-3">Recent Activity</h5>
                    <div class="timeline">
                        @forelse($userActivity ?? [] as $activity)
                            <div class="d-flex mb-3">
                                <div class="flex-shrink-0 me-3">
                                    <div class="bg-primary rounded-circle d-flex align-items-center justify-content-center" style="width: 32px; height: 32px;">
                                        <i class="fas fa-{{ $activity['icon'] ?? 'dot-circle' }} text-white small"></i>
                                    </div>
                                </div>
                                <div class="flex-grow-1">
                                    <div class="fw-semibold">{{ $activity['title'] ?? 'Activity' }}</div>
                                    <small class="text-muted">{{ $activity['description'] ?? 'No description' }}</small>
                                    <br><small class="text-muted">{{ isset($activity['created_at']) ? \Carbon\Carbon::parse($activity['created_at'])->format('M d, Y g:i A') : 'Unknown time' }}</small>
                                </div>
                            </div>
                        @empty
                            <p class="text-muted">No recent activity</p>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
// ...existing code...

// Initialize mood charts if data exists
document.addEventListener('DOMContentLoaded', function() {
    @if(isset($moodChartData))
        // Mood tracking chart
        const moodCtx = document.getElementById('moodChart');
        if (moodCtx) {
            new Chart(moodCtx, {
                type: 'line',
                data: {
                    labels: @json($moodChartData['labels'] ?? []),
                    datasets: [{
                        label: 'Mood Score',
                        data: @json($moodChartData['scores'] ?? []),
                        borderColor: '#0d6efd',
                        backgroundColor: 'rgba(13, 110, 253, 0.1)',
                        tension: 0.4,
                        fill: true
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        y: { beginAtZero: true, max: 10 }
                    }
                }
            });
        }

        // Mood distribution chart
        const distributionCtx = document.getElementById('moodDistributionChart');
        if (distributionCtx) {
            new Chart(distributionCtx, {
                type: 'doughnut',
                data: {
                    labels: @json($moodDistributionData['labels'] ?? []),
                    datasets: [{
                        data: @json($moodDistributionData['values'] ?? []),
                        backgroundColor: ['#28a745', '#ffc107', '#dc3545', '#17a2b8', '#6c757d']
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false
                }
            });
        }
    @endif
});
</script>
@endpush
@endsection

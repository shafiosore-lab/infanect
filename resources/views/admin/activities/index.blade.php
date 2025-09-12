@extends('layouts.admin')

@section('content')
<!-- FontAwesome Icons -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<!-- Bootstrap CSS -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

<div class="container-fluid">
    <!-- Header Section -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-0 text-gray-800">
                <i class="fas fa-calendar-alt text-primary me-2"></i>
                @if(auth()->user()->isActivityProvider())
                    My Activities
                @else
                    All Activities Management
                @endif
            </h1>
            <p class="text-muted">
                @if(auth()->user()->isActivityProvider())
                    Manage your bonding activities and track their approval status
                @else
                    View all platform activities and manage approvals
                @endif
            </p>
        </div>
        @if($canCreate)
        <a href="{{ route('admin.activities.create') }}" class="btn btn-primary">
            <i class="fas fa-plus me-2"></i>Create New Activity
        </a>
        @endif
    </div>

    <!-- Filters Section -->
    <div class="card mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('admin.activities.index') }}" class="row g-3">
                <div class="col-md-3">
                    <label for="search" class="form-label">Search</label>
                    <input type="text" class="form-control" id="search" name="search"
                           value="{{ request('search') }}" placeholder="Search activities...">
                </div>
                <div class="col-md-3">
                    <label for="category" class="form-label">Category</label>
                    <select class="form-select" id="category" name="category">
                        <option value="">All Categories</option>
                        @foreach($categories as $slug => $name)
                            <option value="{{ $slug }}" {{ request('category') == $slug ? 'selected' : '' }}>
                                {{ $name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <label for="status" class="form-label">Status</label>
                    <select class="form-select" id="status" name="status">
                        <option value="">All Status</option>
                        <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>Approved</option>
                        <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending Approval</option>
                    </select>
                </div>
                <div class="col-md-3 d-flex align-items-end">
                    <button type="submit" class="btn btn-outline-primary me-2">
                        <i class="fas fa-search me-1"></i>Filter
                    </button>
                    <a href="{{ route('admin.activities.index') }}" class="btn btn-outline-secondary">
                        <i class="fas fa-times me-1"></i>Clear
                    </a>
                </div>
            </form>
        </div>
    </div>

    <!-- Activities Grid -->
    <div class="row">
        @forelse($activities as $activity)
        <div class="col-xl-4 col-md-6 mb-4">
            <div class="card h-100 shadow-sm border-0 activity-card">
                <!-- Activity Image -->
                <div class="position-relative">
                    @if($activity->images && count($activity->images) > 0)
                        <img src="{{ $activity->images[0] }}" class="card-img-top" alt="{{ $activity->title }}" style="height: 200px; object-fit: cover;">
                    @else
                        <div class="card-img-top bg-gradient-primary d-flex align-items-center justify-content-center" style="height: 200px;">
                            <i class="fas fa-image fa-3x text-white opacity-50"></i>
                        </div>
                    @endif

                    <!-- Status Badge -->
                    <div class="position-absolute top-0 end-0 m-2">
                        @if($activity->is_approved)
                            <span class="badge bg-success">
                                <i class="fas fa-check me-1"></i>Approved
                            </span>
                        @else
                            <span class="badge bg-warning">
                                <i class="fas fa-clock me-1"></i>Pending
                            </span>
                        @endif
                    </div>

                    <!-- Price Badge -->
                    <div class="position-absolute bottom-0 start-0 m-2">
                        <span class="badge bg-dark">
                            <i class="fas fa-dollar-sign me-1"></i>{{ $activity->currency ?? 'KES' }} {{ number_format($activity->price, 2) }}
                        </span>
                    </div>
                </div>

                <div class="card-body d-flex flex-column">
                    <h5 class="card-title">{{ $activity->title }}</h5>
                    <p class="card-text text-muted small flex-grow-1">
                        {{ Str::limit($activity->description, 100) }}
                    </p>

                    <!-- Activity Details -->
                    <div class="mb-3">
                        <div class="row g-2 small text-muted">
                            <div class="col-6">
                                <i class="fas fa-map-marker-alt me-1"></i>
                                {{ $activity->location }}
                            </div>
                            <div class="col-6">
                                <i class="fas fa-clock me-1"></i>
                                {{ $activity->duration_minutes }}min
                            </div>
                            <div class="col-6">
                                <i class="fas fa-users me-1"></i>
                                {{ $activity->slots }} slots
                            </div>
                            <div class="col-6">
                                <i class="fas fa-tag me-1"></i>
                                {{ ucfirst($activity->category) }}
                            </div>
                        </div>
                    </div>

                    <!-- Provider Info (only for admins/super admins) -->
                    @if($canEditAll && $activity->provider)
                    <div class="mb-3 p-2 bg-light rounded">
                        <small class="text-muted d-block">Provider:</small>
                        <strong>{{ $activity->provider->name }}</strong>
                        <small class="text-muted d-block">{{ $activity->provider->email }}</small>
                    </div>
                    @endif

                    <!-- Action Buttons -->
                    <div class="d-flex gap-2 mt-auto">
                        <a href="{{ route('admin.activities.show', $activity) }}" class="btn btn-sm btn-outline-primary flex-fill">
                            <i class="fas fa-eye me-1"></i>View
                        </a>

                        @if(auth()->user()->isActivityProvider() && $activity->provider_id == auth()->id())
                            <a href="{{ route('admin.activities.edit', $activity) }}" class="btn btn-sm btn-outline-warning">
                                <i class="fas fa-edit me-1"></i>Edit
                            </a>
                        @elseif($canEditAll)
                            <a href="{{ route('admin.activities.edit', $activity) }}" class="btn btn-sm btn-outline-warning">
                                <i class="fas fa-edit me-1"></i>Edit
                            </a>
                        @endif

                        @if($canApprove && !$activity->is_approved)
                        <form method="POST" action="{{ route('admin.activities.approve', $activity) }}" class="d-inline">
                            @csrf
                            <input type="hidden" name="approved" value="1">
                            <button type="submit" class="btn btn-sm btn-success" onclick="return confirm('Approve this activity?')">
                                <i class="fas fa-check me-1"></i>Approve
                            </button>
                        </form>
                        @endif

                        @if((auth()->user()->isActivityProvider() && $activity->provider_id == auth()->id()) || $canEditAll)
                        <form method="POST" action="{{ route('admin.activities.destroy', $activity) }}" class="d-inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-outline-danger" onclick="return confirm('Delete this activity?')">
                                <i class="fas fa-trash me-1"></i>Delete
                            </button>
                        </form>
                        @endif
                    </div>
                </div>
            </div>
        </div>
        @empty
        <div class="col-12">
            <div class="text-center py-5">
                <i class="fas fa-calendar-alt fa-3x text-muted mb-3"></i>
                <h4 class="text-muted">No Activities Found</h4>
                <p class="text-muted">
                    @if(auth()->user()->isActivityProvider())
                        You haven't created any activities yet. Create your first activity to get started.
                    @else
                        No activities match your current filters. Try adjusting your search criteria.
                    @endif
                </p>
                @if($canCreate)
                <a href="{{ route('admin.activities.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus me-2"></i>Create First Activity
                </a>
                @endif
            </div>
        </div>
        @endforelse
    </div>

    <!-- Pagination -->
    @if($activities->hasPages())
    <div class="d-flex justify-content-center mt-4">
        {{ $activities->appends(request()->query())->links() }}
    </div>
    @endif
</div>

<style>
.activity-card {
    transition: all 0.3s ease;
    border-radius: 15px;
    overflow: hidden;
}

.activity-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 25px rgba(0,0,0,0.15);
}

.card-img-top {
    transition: transform 0.3s ease;
}

.activity-card:hover .card-img-top {
    transform: scale(1.05);
}

.bg-gradient-primary {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
}

.badge {
    font-size: 0.75rem;
    padding: 0.5rem 0.75rem;
}

.btn-sm {
    font-size: 0.875rem;
    padding: 0.375rem 0.75rem;
}
</style>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
@endsection

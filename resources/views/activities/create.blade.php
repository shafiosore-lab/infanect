@extends('layouts.app')

@section('content')
<div class="dashboard-container">
    <div class="dashboard-section">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h3 class="mb-1 fw-bold text-dark">
                    <i class="fas fa-plus-circle text-primary me-2"></i>
                    Create New Activity
                </h3>
                <p class="text-muted mb-0 small">Design engaging bonding experiences for families in your community</p>
            </div>
            <a href="{{ route('activities.index') }}" class="btn btn-outline-secondary">
                <i class="fas fa-arrow-left me-1"></i> Back to Activities
            </a>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-8">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-white border-0 py-3">
                    <h5 class="mb-0 fw-bold text-dark">Activity Details</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('activities.store') }}" method="POST">
                        @csrf

                        <div class="row g-3">
                            <div class="col-md-12">
                                <label for="title" class="form-label fw-semibold">Activity Title *</label>
                                <input type="text" class="form-control @error('title') is-invalid @enderror"
                                       id="title" name="title" value="{{ old('title') }}"
                                       placeholder="e.g., Family Cooking Workshop">
                                @error('title')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label for="category" class="form-label fw-semibold">Category *</label>
                                <select class="form-select @error('category') is-invalid @enderror" id="category" name="category">
                                    <option value="">Select Category</option>
                                    <option value="Outdoor Activities" {{ old('category') == 'Outdoor Activities' ? 'selected' : '' }}>Outdoor Activities</option>
                                    <option value="Cooking Together" {{ old('category') == 'Cooking Together' ? 'selected' : '' }}>Cooking Together</option>
                                    <option value="Arts & Crafts" {{ old('category') == 'Arts & Crafts' ? 'selected' : '' }}>Arts & Crafts</option>
                                    <option value="Sports & Games" {{ old('category') == 'Sports & Games' ? 'selected' : '' }}>Sports & Games</option>
                                    <option value="Educational" {{ old('category') == 'Educational' ? 'selected' : '' }}>Educational</option>
                                </select>
                                @error('category')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label for="age_group" class="form-label fw-semibold">Age Group *</label>
                                <select class="form-select @error('age_group') is-invalid @enderror" id="age_group" name="age_group">
                                    <option value="">Select Age Group</option>
                                    <option value="3-6" {{ old('age_group') == '3-6' ? 'selected' : '' }}>3-6 years</option>
                                    <option value="6-10" {{ old('age_group') == '6-10' ? 'selected' : '' }}>6-10 years</option>
                                    <option value="10-14" {{ old('age_group') == '10-14' ? 'selected' : '' }}>10-14 years</option>
                                    <option value="All Ages" {{ old('age_group') == 'All Ages' ? 'selected' : '' }}>All Ages</option>
                                </select>
                                @error('age_group')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-4">
                                <label for="duration_minutes" class="form-label fw-semibold">Duration (minutes) *</label>
                                <input type="number" class="form-control @error('duration_minutes') is-invalid @enderror"
                                       id="duration_minutes" name="duration_minutes" value="{{ old('duration_minutes', 60) }}"
                                       min="15" max="480">
                                @error('duration_minutes')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-4">
                                <label for="max_participants" class="form-label fw-semibold">Max Participants *</label>
                                <input type="number" class="form-control @error('max_participants') is-invalid @enderror"
                                       id="max_participants" name="max_participants" value="{{ old('max_participants', 10) }}"
                                       min="1" max="100">
                                @error('max_participants')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-4">
                                <label for="price" class="form-label fw-semibold">Price (KSh) *</label>
                                <input type="number" class="form-control @error('price') is-invalid @enderror"
                                       id="price" name="price" value="{{ old('price', 0) }}"
                                       min="0" step="0.01">
                                @error('price')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-12">
                                <label for="description" class="form-label fw-semibold">Description *</label>
                                <textarea class="form-control @error('description') is-invalid @enderror"
                                          id="description" name="description" rows="4"
                                          placeholder="Describe the activity, its benefits, and what families can expect...">{{ old('description') }}</textarea>
                                @error('description')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-12">
                                <div class="d-flex justify-content-end gap-2">
                                    <a href="{{ route('activities.index') }}" class="btn btn-secondary">Cancel</a>
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-save me-1"></i> Create Activity
                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-white border-0 py-3">
                    <h5 class="mb-0 fw-bold text-dark">
                        <i class="fas fa-lightbulb text-warning me-2"></i>
                        Tips for Great Activities
                    </h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <h6 class="small fw-bold text-primary">
                            <i class="fas fa-heart me-1"></i> Focus on Connection
                        </h6>
                        <p class="small text-muted mb-0">Design activities that encourage family members to interact, communicate, and bond with each other.</p>
                    </div>

                    <div class="mb-3">
                        <h6 class="small fw-bold text-success">
                            <i class="fas fa-users me-1"></i> Age-Appropriate
                        </h6>
                        <p class="small text-muted mb-0">Ensure activities are suitable for the specified age group and engaging for all participants.</p>
                    </div>

                    <div class="mb-3">
                        <h6 class="small fw-bold text-info">
                            <i class="fas fa-clock me-1"></i> Realistic Timing
                        </h6>
                        <p class="small text-muted mb-0">Consider setup, activity time, and cleanup when setting duration.</p>
                    </div>

                    <div class="mb-0">
                        <h6 class="small fw-bold text-warning">
                            <i class="fas fa-shield-alt me-1"></i> Safety First
                        </h6>
                        <p class="small text-muted mb-0">Always consider safety requirements and include any necessary precautions.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

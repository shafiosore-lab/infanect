@extends('layouts.guest')

@section('content')
<div class="container-fluid py-5 bg-gradient-to-br from-blue-50 to-indigo-100 min-h-screen">
    <div class="row justify-content-center">
        <div class="col-lg-10 col-xl-8">
            <!-- Progress Header -->
            <div class="card shadow-lg border-0 rounded-4 mb-4">
                <div class="card-body p-4">
                    <div class="row align-items-center">
                        <div class="col-md-8">
                            <h1 class="h2 fw-bold text-primary mb-2">
                                <i class="fas fa-rocket me-2"></i>Provider Registration
                            </h1>
                            <p class="text-muted mb-0">Join our network of professional service providers and start making a difference</p>
                        </div>
                        <div class="col-md-4 text-end">
                            <div class="progress mb-2" style="height: 8px;">
                                <div class="progress-bar bg-primary" role="progressbar" style="width: 0%" id="form-progress">
                                    <span class="sr-only">0% Complete</span>
                                </div>
                            </div>
                            <small class="text-muted">Step <span id="current-step">1</span> of 6</small>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Main Registration Form -->
            <div class="card shadow-lg border-0 rounded-4">
                <div class="card-body p-5">
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    @if(session('error'))
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <i class="fas fa-exclamation-circle me-2"></i>{{ session('error') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('provider.store') }}" enctype="multipart/form-data"
                          class="needs-validation multi-step-form" novalidate id="provider-registration-form">
                        @csrf

                        <!-- Step 1: Personal Information -->
                        <div class="form-step active" id="step-1">
                            <div class="text-center mb-4">
                                <div class="step-icon bg-primary bg-opacity-10 rounded-circle p-3 d-inline-flex align-items-center justify-content-center mb-3">
                                    <i class="fas fa-user text-primary fa-2x"></i>
                                </div>
                                <h3 class="text-primary mb-2">👤 Personal Information</h3>
                                <p class="text-muted">Tell us about yourself and your professional background</p>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="name" class="form-label fw-semibold">
                                        Full Name <span class="text-danger">*</span>
                                    </label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="fas fa-user"></i></span>
                                        <input id="name" name="name" type="text" value="{{ old('name', 'Shafi abubakar') }}"
                                               class="form-control @error('name') is-invalid @enderror"
                                               required placeholder="Enter your full legal name">
                                    </div>
                                    @error('name')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                    <div class="form-text">This should match your official documents</div>
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label for="email" class="form-label fw-semibold">
                                        Email Address <span class="text-danger">*</span>
                                    </label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                                        <input id="email" name="email" type="email" value="{{ old('email', 'shhhhafiosore@gmail.com') }}"
                                               class="form-control @error('email') is-invalid @enderror"
                                               required placeholder="your.email@domain.com">
                                    </div>
                                    @error('email')
                                        <div class="invalid-feedback d-block text-danger fw-bold">
                                            <i class="fas fa-exclamation-triangle me-1"></i>{{ $message }}
                                        </div>
                                    @enderror
                                    <div class="form-text">We'll use this for account notifications and client communications</div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="phone" class="form-label fw-semibold">Phone Number</label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="fas fa-phone"></i></span>
                                        <input id="phone" name="phone" type="tel" value="{{ old('phone', '0711263020') }}"
                                               class="form-control @error('phone') is-invalid @enderror"
                                               placeholder="+1 (555) 123-4567">
                                    </div>
                                    @error('phone')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label for="years_experience" class="form-label fw-semibold">Years of Experience</label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="fas fa-calendar"></i></span>
                                        <input id="years_experience" name="years_experience" type="number"
                                               min="0" max="50" value="{{ old('years_experience', '3') }}"
                                               class="form-control @error('years_experience') is-invalid @enderror"
                                               placeholder="0">
                                        <span class="input-group-text">years</span>
                                    </div>
                                    @error('years_experience')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="mb-4">
                                <label for="professional_bio" class="form-label fw-semibold">Professional Bio</label>
                                <textarea id="professional_bio" name="professional_bio" rows="4"
                                          class="form-control @error('professional_bio') is-invalid @enderror"
                                          placeholder="Share your professional background, qualifications, and what drives you to help families...">{{ old('professional_bio') }}</textarea>
                                @error('professional_bio')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <div class="form-text">This will be shown to potential clients on your profile</div>
                            </div>
                        </div>

                        <!-- Step 2: Provider Information -->
                        <div class="form-step" id="step-2">
                            <div class="text-center mb-4">
                                <div class="step-icon bg-success bg-opacity-10 rounded-circle p-3 d-inline-flex align-items-center justify-content-center mb-3">
                                    <i class="fas fa-briefcase text-success fa-2x"></i>
                                </div>
                                <h3 class="text-success mb-2">🏢 Provider Information</h3>
                                <p class="text-muted">Configure your professional services and business details</p>
                            </div>

                            <div class="mb-4">
                                <label for="provider_type" class="form-label fw-semibold">
                                    Provider Type <span class="text-danger">*</span>
                                </label>
                                <select id="provider_type" name="provider_type"
                                        class="form-select form-select-lg @error('provider_type') is-invalid @enderror" required>
                                    <option value="">Select your provider type...</option>
                                    @foreach($providerTypes as $type)
                                        <option value="{{ $type['slug'] }}"
                                                data-required-docs="{{ json_encode($type['required_documents'] ?? []) }}"
                                                data-description="{{ $type['description'] ?? '' }}"
                                                {{ old('provider_type', 'provider-professional') == $type['slug'] ? 'selected' : '' }}>
                                            {{ $type['name'] }}
                                        </option>
                                    @endforeach
                                </select>
                                <div class="form-text text-primary fw-medium mt-2" id="provider-type-description">
                                    This determines your dashboard features and required documents.
                                </div>
                                @error('provider_type')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="business_name" class="form-label fw-semibold">Business/Organization Name</label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="fas fa-building"></i></span>
                                        <input id="business_name" name="business_name" type="text"
                                               value="{{ old('business_name', 'YIDA') }}"
                                               class="form-control @error('business_name') is-invalid @enderror"
                                               placeholder="Your practice or business name">
                                    </div>
                                    @error('business_name')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label for="website" class="form-label fw-semibold">Website</label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="fas fa-globe"></i></span>
                                        <input id="website" name="website" type="url"
                                               value="{{ old('website', 'https://www.infanect.com') }}"
                                               class="form-control @error('website') is-invalid @enderror"
                                               placeholder="https://www.yourwebsite.com">
                                    </div>
                                    @error('website')
                                        <div class="invalid-feedback d-block text-danger fw-bold">
                                            <i class="fas fa-exclamation-triangle me-1"></i>{{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="business_description" class="form-label fw-semibold">Business Description</label>
                                <textarea id="business_description" name="business_description" rows="3"
                                          class="form-control @error('business_description') is-invalid @enderror"
                                          placeholder="Describe your services, approach, and what makes you unique...">{{ old('business_description', 'Professional healthcare services specializing in family wellness and child development.') }}</textarea>
                                @error('business_description')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="row">
                                <div class="col-md-4 mb-3">
                                    <label for="hourly_rate" class="form-label fw-semibold">Hourly Rate ($)</label>
                                    <div class="input-group">
                                        <span class="input-group-text">$</span>
                                        <input id="hourly_rate" name="hourly_rate" type="number"
                                               min="0" step="0.01" value="{{ old('hourly_rate') }}"
                                               class="form-control @error('hourly_rate') is-invalid @enderror"
                                               placeholder="75.00">
                                        <span class="input-group-text">/hr</span>
                                    </div>
                                    @error('hourly_rate')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-8 mb-3">
                                    <label class="form-label fw-semibold">Specializations</label>
                                    <div class="row g-2">
                                        @php
                                            $specializations = [
                                                'Child Psychology' => 'fas fa-child',
                                                'Family Therapy' => 'fas fa-users',
                                                'Parenting Coaching' => 'fas fa-heart',
                                                'Educational Support' => 'fas fa-graduation-cap',
                                                'Health & Wellness' => 'fas fa-heartbeat',
                                                'Mental Health' => 'fas fa-brain',
                                                'Physical Therapy' => 'fas fa-dumbbell',
                                                'Nutrition' => 'fas fa-apple-alt',
                                                'Art Therapy' => 'fas fa-palette',
                                                'Music Therapy' => 'fas fa-music'
                                            ];
                                            $selectedSpecs = old('specializations', ['Child Psychology', 'Family Therapy']);
                                        @endphp
                                        @foreach($specializations as $spec => $icon)
                                            <div class="col-md-6">
                                                <div class="form-check form-check-card">
                                                    <input class="form-check-input" type="checkbox"
                                                           name="specializations[]" value="{{ $spec }}"
                                                           id="spec_{{ $loop->index }}"
                                                           {{ in_array($spec, $selectedSpecs) ? 'checked' : '' }}>
                                                    <label class="form-check-label d-flex align-items-center" for="spec_{{ $loop->index }}">
                                                        <i class="{{ $icon }} me-2 text-primary"></i>{{ $spec }}
                                                    </label>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Step 3: Location Information -->
                        <div class="form-step" id="step-3">
                            <div class="text-center mb-4">
                                <div class="step-icon bg-info bg-opacity-10 rounded-circle p-3 d-inline-flex align-items-center justify-content-center mb-3">
                                    <i class="fas fa-map-marker-alt text-info fa-2x"></i>
                                </div>
                                <h3 class="text-info mb-2">📍 Location Information</h3>
                                <p class="text-muted">Help clients find you and understand your service areas</p>
                            </div>

                            <div class="mb-3">
                                <label for="address" class="form-label fw-semibold">Practice Address</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="fas fa-home"></i></span>
                                    <input id="address" name="address" type="text"
                                           value="{{ old('address', '50102_021') }}"
                                           class="form-control @error('address') is-invalid @enderror"
                                           placeholder="123 Main Street, Suite 456">
                                </div>
                                @error('address')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="row">
                                <div class="col-md-4 mb-3">
                                    <label for="city" class="form-label fw-semibold">City</label>
                                    <input id="city" name="city" type="text"
                                           value="{{ old('city', 'Mumias') }}"
                                           class="form-control @error('city') is-invalid @enderror"
                                           placeholder="City name">
                                    @error('city')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-4 mb-3">
                                    <label for="state" class="form-label fw-semibold">State/Province</label>
                                    <input id="state" name="state" type="text"
                                           value="{{ old('state', 'Western Province') }}"
                                           class="form-control @error('state') is-invalid @enderror"
                                           placeholder="State or Province">
                                    @error('state')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-4 mb-3">
                                    <label for="postal_code" class="form-label fw-semibold">Postal Code</label>
                                    <input id="postal_code" name="postal_code" type="text"
                                           value="{{ old('postal_code', '50102') }}"
                                           class="form-control @error('postal_code') is-invalid @enderror"
                                           placeholder="12345">
                                    @error('postal_code')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="country" class="form-label fw-semibold">Country</label>
                                    <select id="country" name="country" class="form-select @error('country') is-invalid @enderror">
                                        <option value="">Select Country</option>
                                        <option value="US" {{ old('country') === 'US' ? 'selected' : '' }}>United States</option>
                                        <option value="CA" {{ old('country') === 'CA' ? 'selected' : '' }}>Canada</option>
                                        <option value="UK" {{ old('country') === 'UK' ? 'selected' : '' }}>United Kingdom</option>
                                        <option value="AU" {{ old('country') === 'AU' ? 'selected' : '' }}>Australia</option>
                                        <option value="KE" {{ old('country', 'KE') === 'KE' ? 'selected' : '' }}>Kenya</option>
                                        <option value="NG" {{ old('country') === 'NG' ? 'selected' : '' }}>Nigeria</option>
                                        <option value="ZA" {{ old('country') === 'ZA' ? 'selected' : '' }}>South Africa</option>
                                    </select>
                                    @error('country')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label class="form-label fw-semibold">Service Delivery Options</label>
                                    <div class="d-flex flex-wrap gap-2">
                                        @php
                                            $serviceOptions = [
                                                'in_person' => ['In-Person', 'fas fa-handshake'],
                                                'virtual' => ['Virtual/Online', 'fas fa-video'],
                                                'home_visits' => ['Home Visits', 'fas fa-home'],
                                                'group_sessions' => ['Group Sessions', 'fas fa-users']
                                            ];
                                        @endphp
                                        @foreach($serviceOptions as $key => [$label, $icon])
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox"
                                                       name="service_delivery[]" value="{{ $key }}"
                                                       id="service_{{ $key }}">
                                                <label class="form-check-label d-flex align-items-center" for="service_{{ $key }}">
                                                    <i class="{{ $icon }} me-1"></i>{{ $label }}
                                                </label>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Step 4: Availability & Scheduling -->
                        <div class="form-step" id="step-4">
                            <div class="text-center mb-4">
                                <div class="step-icon bg-warning bg-opacity-10 rounded-circle p-3 d-inline-flex align-items-center justify-content-center mb-3">
                                    <i class="fas fa-calendar-alt text-warning fa-2x"></i>
                                </div>
                                <h3 class="text-warning mb-2">⏰ Availability & Scheduling</h3>
                                <p class="text-muted">Set your working hours and availability preferences</p>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-4">
                                    <label class="form-label fw-semibold">Available Days</label>
                                    <div class="availability-grid">
                                        @php
                                            $days = ['monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday'];
                                            $dayLabels = ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'];
                                        @endphp
                                        @foreach($days as $index => $day)
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" type="checkbox"
                                                       name="available_days[]" value="{{ $day }}"
                                                       id="day_{{ $day }}" {{ in_array($day, ['monday', 'tuesday', 'wednesday', 'thursday', 'friday']) ? 'checked' : '' }}>
                                                <label class="form-check-label" for="day_{{ $day }}">{{ $dayLabels[$index] }}</label>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>

                                <div class="col-md-6 mb-4">
                                    <label class="form-label fw-semibold">Preferred Time Slots</label>
                                    <div class="d-flex flex-wrap gap-2">
                                        @php
                                            $timeSlots = [
                                                'morning' => ['Morning (8AM-12PM)', 'fas fa-sun'],
                                                'afternoon' => ['Afternoon (12PM-5PM)', 'fas fa-cloud-sun'],
                                                'evening' => ['Evening (5PM-8PM)', 'fas fa-moon']
                                            ];
                                        @endphp
                                        @foreach($timeSlots as $slot => [$label, $icon])
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox"
                                                       name="availability[]" value="{{ $slot }}"
                                                       id="time_{{ $slot }}" {{ in_array($slot, ['morning', 'afternoon']) ? 'checked' : '' }}>
                                                <label class="form-check-label d-flex align-items-center" for="time_{{ $slot }}">
                                                    <i class="{{ $icon }} me-1"></i>{{ $label }}
                                                </label>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="session_duration" class="form-label fw-semibold">Default Session Duration</label>
                                    <select id="session_duration" name="session_duration" class="form-select">
                                        <option value="30">30 minutes</option>
                                        <option value="45">45 minutes</option>
                                        <option value="60" selected>1 hour</option>
                                        <option value="90">1.5 hours</option>
                                        <option value="120">2 hours</option>
                                    </select>
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label for="timezone" class="form-label fw-semibold">Timezone</label>
                                    <select id="timezone" name="timezone" class="form-select">
                                        <option value="UTC">UTC (Coordinated Universal Time)</option>
                                        <option value="America/New_York">Eastern Time (ET)</option>
                                        <option value="America/Chicago">Central Time (CT)</option>
                                        <option value="America/Denver">Mountain Time (MT)</option>
                                        <option value="America/Los_Angeles">Pacific Time (PT)</option>
                                        <option value="Africa/Nairobi" selected>East Africa Time (EAT)</option>
                                        <option value="Europe/London">Greenwich Mean Time (GMT)</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <!-- Step 5: Required Documents (KYC) -->
                        <div class="form-step" id="step-5">
                            <div class="text-center mb-4">
                                <div class="step-icon bg-danger bg-opacity-10 rounded-circle p-3 d-inline-flex align-items-center justify-content-center mb-3">
                                    <i class="fas fa-file-upload text-danger fa-2x"></i>
                                </div>
                                <h3 class="text-danger mb-2">📄 Required Documents (KYC)</h3>
                                <p class="text-muted">Upload your verification documents for account approval</p>
                            </div>

                            <div class="alert alert-info d-flex align-items-start" role="alert">
                                <i class="fas fa-info-circle me-2 mt-1"></i>
                                <div>
                                    <strong>Document Requirements:</strong> Documents will be dynamically shown based on your selected provider type.
                                    All uploads are encrypted and stored securely. Our team will review within 24-48 hours.
                                </div>
                            </div>

                            <div id="document-requirements">
                                @foreach($kycDocuments as $docKey => $docConfig)
                                    <div class="mb-4 document-field" data-doc-key="{{ $docKey }}" style="display: none;">
                                        <div class="card border-2 border-dashed" id="upload-{{ $docKey }}">
                                            <div class="card-body text-center p-4">
                                                <label for="{{ $docKey }}" class="form-label fw-semibold d-flex align-items-center justify-content-center">
                                                    <i class="fas fa-file-alt me-2"></i>
                                                    {{ $docConfig['name'] }}
                                                    <span class="required-indicator text-danger ms-1" style="display: none;">*</span>
                                                </label>

                                                <div class="upload-area" onclick="document.getElementById('{{ $docKey }}').click()">
                                                    <i class="fas fa-cloud-upload-alt fa-3x text-muted mb-3"></i>
                                                    <p class="text-muted mb-2">Click to upload or drag and drop</p>
                                                    <p class="text-sm text-muted mb-3">{{ $docConfig['accept'] }} | Max {{ $docConfig['max_size'] }}</p>

                                                    <input type="file" name="{{ $docKey }}" id="{{ $docKey }}"
                                                           class="form-control d-none @error($docKey) is-invalid @enderror"
                                                           accept="{{ $docConfig['accept'] }}"
                                                           onchange="handleFileUpload('{{ $docKey }}')">

                                                    <div class="upload-preview" id="preview-{{ $docKey }}" style="display: none;">
                                                        <div class="alert alert-success d-flex align-items-center">
                                                            <i class="fas fa-check-circle me-2"></i>
                                                            <span class="filename"></span>
                                                            <button type="button" class="btn btn-sm btn-outline-danger ms-auto"
                                                                    onclick="removeFile('{{ $docKey }}')">
                                                                <i class="fas fa-times"></i>
                                                            </button>
                                                        </div>
                                                    </div>
                                                </div>

                                                @error($docKey)
                                                    <div class="invalid-feedback d-block text-danger fw-bold mt-2">
                                                        <i class="fas fa-exclamation-triangle me-1"></i>{{ $message }}
                                                    </div>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>

                        <!-- Step 6: Account Security & Terms -->
                        <div class="form-step" id="step-6">
                            <div class="text-center mb-4">
                                <div class="step-icon bg-success bg-opacity-10 rounded-circle p-3 d-inline-flex align-items-center justify-content-center mb-3">
                                    <i class="fas fa-shield-alt text-success fa-2x"></i>
                                </div>
                                <h3 class="text-success mb-2">🔐 Account Security & Agreement</h3>
                                <p class="text-muted">Secure your account and review our terms</p>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="password" class="form-label fw-semibold">
                                        Password <span class="text-danger">*</span>
                                    </label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="fas fa-lock"></i></span>
                                        <input id="password" name="password" type="password"
                                               class="form-control @error('password') is-invalid @enderror"
                                               required placeholder="Create a strong password">
                                        <button class="btn btn-outline-secondary" type="button" onclick="togglePassword('password')">
                                            <i class="fas fa-eye" id="password-icon"></i>
                                        </button>
                                    </div>
                                    <div class="password-strength mt-2" id="password-strength">
                                        <div class="progress" style="height: 5px;">
                                            <div class="progress-bar" role="progressbar"></div>
                                        </div>
                                        <small class="text-muted">Password strength: <span id="strength-text">Weak</span></small>
                                    </div>
                                    @error('password')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label for="password_confirmation" class="form-label fw-semibold">
                                        Confirm Password <span class="text-danger">*</span>
                                    </label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="fas fa-lock"></i></span>
                                        <input id="password_confirmation" name="password_confirmation" type="password"
                                               class="form-control @error('password_confirmation') is-invalid @enderror"
                                               required placeholder="Confirm your password">
                                        <button class="btn btn-outline-secondary" type="button" onclick="togglePassword('password_confirmation')">
                                            <i class="fas fa-eye" id="password_confirmation-icon"></i>
                                        </button>
                                    </div>
                                    <div class="password-match mt-2" id="password-match" style="display: none;">
                                        <small class="text-success">
                                            <i class="fas fa-check me-1"></i>Passwords match
                                        </small>
                                    </div>
                                    @error('password_confirmation')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <!-- Terms and Agreements -->
                            <div class="card bg-light border-0 mb-4">
                                <div class="card-body">
                                    <h5 class="card-title">Terms of Service & Privacy Policy</h5>
                                    <div class="form-check mb-3">
                                        <input class="form-check-input" type="checkbox" id="terms" required>
                                        <label class="form-check-label" for="terms">
                                            I agree to the <a href="#" target="_blank" class="text-primary">Terms of Service</a>
                                            and <a href="#" target="_blank" class="text-primary">Privacy Policy</a>
                                            <span class="text-danger">*</span>
                                        </label>
                                    </div>

                                    <div class="form-check mb-3">
                                        <input class="form-check-input" type="checkbox" id="marketing" name="marketing_consent">
                                        <label class="form-check-label" for="marketing">
                                            I agree to receive marketing emails and promotional content
                                        </label>
                                    </div>

                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="data_processing" required>
                                        <label class="form-check-label" for="data_processing">
                                            I consent to the processing of my personal data as described in the Privacy Policy
                                            <span class="text-danger">*</span>
                                        </label>
                                    </div>
                                </div>
                            </div>

                            <!-- Application Summary -->
                            <div class="card border-primary mb-4">
                                <div class="card-header bg-primary text-white">
                                    <h5 class="card-title mb-0">
                                        <i class="fas fa-clipboard-check me-2"></i>Application Summary
                                    </h5>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <p><strong>Provider Type:</strong> <span id="summary-provider-type">Professional Provider</span></p>
                                            <p><strong>Specializations:</strong> <span id="summary-specializations">Child Psychology, Family Therapy</span></p>
                                            <p><strong>Location:</strong> <span id="summary-location">Mumias, Western Province</span></p>
                                        </div>
                                        <div class="col-md-6">
                                            <p><strong>Experience:</strong> <span id="summary-experience">3 years</span></p>
                                            <p><strong>Service Options:</strong> <span id="summary-services">In-Person, Virtual</span></p>
                                            <p><strong>Documents:</strong> <span id="summary-documents">3 required documents</span></p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Navigation Buttons -->
                        <div class="d-flex justify-content-between mt-4">
                            <button type="button" class="btn btn-outline-secondary btn-lg px-4" id="prev-btn" style="display: none;">
                                <i class="fas fa-chevron-left me-2"></i>Previous
                            </button>

                            <div class="ms-auto">
                                <button type="button" class="btn btn-primary btn-lg px-4" id="next-btn">
                                    Next<i class="fas fa-chevron-right ms-2"></i>
                                </button>

                                <button type="submit" class="btn btn-success btn-lg px-4" id="submit-btn" style="display: none;">
                                    <i class="fas fa-rocket me-2"></i>Submit Application
                                </button>
                            </div>
                        </div>

                        <!-- Save Draft Button -->
                        <div class="text-center mt-3">
                            <button type="button" class="btn btn-outline-info btn-sm" onclick="saveDraft()">
                                <i class="fas fa-save me-1"></i>Save Draft
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Login Link -->
            <div class="text-center mt-4">
                <div class="card border-0 bg-transparent">
                    <div class="card-body">
                        <span class="text-muted">Already have an account?</span>
                        <a href="{{ route('login') }}" class="text-decoration-none ms-1 fw-bold">Sign in here</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('styles')
<style>
.bg-gradient-to-br {
    background: linear-gradient(135deg, #eff6ff 0%, #dbeafe 50%, #e0e7ff 100%);
}

.form-step {
    display: none;
}

.form-step.active {
    display: block;
}

.step-icon {
    width: 80px;
    height: 80px;
}

.form-check-card {
    background: #f8f9fa;
    border: 2px solid #e9ecef;
    border-radius: 8px;
    padding: 12px;
    margin-bottom: 10px;
    transition: all 0.3s ease;
}

.form-check-card:hover {
    background: #e3f2fd;
    border-color: #2196f3;
}

.form-check-card input:checked + label {
    color: #1976d2;
    font-weight: 600;
}

.upload-area {
    cursor: pointer;
    padding: 30px;
    border: 2px dashed #dee2e6;
    border-radius: 8px;
    transition: all 0.3s ease;
}

.upload-area:hover {
    border-color: #007bff;
    background: #f8f9fa;
}

.upload-area.dragover {
    border-color: #28a745;
    background: #d4edda;
}

.password-strength .progress-bar {
    transition: all 0.3s ease;
}

.strength-weak { background-color: #dc3545; }
.strength-fair { background-color: #fd7e14; }
.strength-good { background-color: #ffc107; }
.strength-strong { background-color: #28a745; }

.availability-grid {
    display: flex;
    flex-wrap: wrap;
    gap: 10px;
}

@media (max-width: 768px) {
    .form-step {
        padding: 0 10px;
    }

    .step-icon {
        width: 60px;
        height: 60px;
    }

    .availability-grid {
        flex-direction: column;
    }
}
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    let currentStep = 1;
    const totalSteps = 6;

    // Multi-step navigation
    function showStep(step) {
        // Hide all steps
        document.querySelectorAll('.form-step').forEach(s => s.classList.remove('active'));

        // Show current step
        document.getElementById(`step-${step}`).classList.add('active');

        // Update progress
        const progress = ((step - 1) / (totalSteps - 1)) * 100;
        document.getElementById('form-progress').style.width = `${progress}%`;
        document.getElementById('current-step').textContent = step;

        // Update navigation buttons
        document.getElementById('prev-btn').style.display = step === 1 ? 'none' : 'block';
        document.getElementById('next-btn').style.display = step === totalSteps ? 'none' : 'block';
        document.getElementById('submit-btn').style.display = step === totalSteps ? 'block' : 'none';

        // Update summary on last step
        if (step === totalSteps) {
            updateSummary();
        }
    }

    // Navigation event listeners
    document.getElementById('next-btn').addEventListener('click', function() {
        if (validateStep(currentStep)) {
            currentStep++;
            showStep(currentStep);
        }
    });

    document.getElementById('prev-btn').addEventListener('click', function() {
        currentStep--;
        showStep(currentStep);
    });

    // Step validation
    function validateStep(step) {
        const stepElement = document.getElementById(`step-${step}`);
        const requiredFields = stepElement.querySelectorAll('[required]');
        let valid = true;

        requiredFields.forEach(field => {
            if (!field.checkValidity()) {
                field.classList.add('is-invalid');
                valid = false;
            } else {
                field.classList.remove('is-invalid');
            }
        });

        return valid;
    }

    // Provider type change handler
    document.getElementById('provider_type').addEventListener('change', function() {
        const selectedOption = this.options[this.selectedIndex];
        const description = selectedOption.dataset.description || '';
        const requiredDocs = JSON.parse(selectedOption.dataset.requiredDocs || '[]');

        // Update description
        document.getElementById('provider-type-description').textContent = description;

        // Show/hide document fields
        document.querySelectorAll('.document-field').forEach(field => {
            const docKey = field.dataset.docKey;
            const input = field.querySelector('input[type="file"]');
            const requiredIndicator = field.querySelector('.required-indicator');

            if (requiredDocs.includes(docKey) || docKey === 'id_document') {
                field.style.display = 'block';
                input.setAttribute('required', 'required');
                requiredIndicator.style.display = 'inline';
            } else {
                field.style.display = 'none';
                input.removeAttribute('required');
                requiredIndicator.style.display = 'none';
            }
        });
    });

    // File upload handlers
    window.handleFileUpload = function(docKey) {
        const input = document.getElementById(docKey);
        const preview = document.getElementById(`preview-${docKey}`);
        const uploadArea = preview.closest('.upload-area');

        if (input.files && input.files[0]) {
            const file = input.files[0];
            const filename = file.name;

            // Show preview
            preview.style.display = 'block';
            preview.querySelector('.filename').textContent = filename;

            // Update upload area appearance
            uploadArea.classList.add('border-success');
            uploadArea.style.borderColor = '#28a745';
        }
    };

    window.removeFile = function(docKey) {
        const input = document.getElementById(docKey);
        const preview = document.getElementById(`preview-${docKey}`);
        const uploadArea = preview.closest('.upload-area');

        input.value = '';
        preview.style.display = 'none';
        uploadArea.classList.remove('border-success');
        uploadArea.style.borderColor = '';
    };

    // Password strength checker
    document.getElementById('password').addEventListener('input', function() {
        const password = this.value;
        const strengthBar = document.querySelector('#password-strength .progress-bar');
        const strengthText = document.getElementById('strength-text');

        let strength = 0;
        let label = 'Weak';
        let colorClass = 'strength-weak';

        if (password.length >= 8) strength++;
        if (password.match(/[a-z]/)) strength++;
        if (password.match(/[A-Z]/)) strength++;
        if (password.match(/[0-9]/)) strength++;
        if (password.match(/[^a-zA-Z0-9]/)) strength++;

        switch (strength) {
            case 0:
            case 1:
                label = 'Weak';
                colorClass = 'strength-weak';
                break;
            case 2:
                label = 'Fair';
                colorClass = 'strength-fair';
                break;
            case 3:
                label = 'Good';
                colorClass = 'strength-good';
                break;
            case 4:
            case 5:
                label = 'Strong';
                colorClass = 'strength-strong';
                break;
        }

        strengthBar.style.width = `${(strength / 5) * 100}%`;
        strengthBar.className = `progress-bar ${colorClass}`;
        strengthText.textContent = label;
    });

    // Password confirmation checker
    document.getElementById('password_confirmation').addEventListener('input', function() {
        const password = document.getElementById('password').value;
        const confirmation = this.value;
        const matchIndicator = document.getElementById('password-match');

        if (confirmation && password === confirmation) {
            matchIndicator.style.display = 'block';
            this.classList.remove('is-invalid');
            this.classList.add('is-valid');
        } else if (confirmation) {
            matchIndicator.style.display = 'none';
            this.classList.add('is-invalid');
            this.classList.remove('is-valid');
        }
    });

    // Toggle password visibility
    window.togglePassword = function(fieldId) {
        const field = document.getElementById(fieldId);
        const icon = document.getElementById(`${fieldId}-icon`);

        if (field.type === 'password') {
            field.type = 'text';
            icon.className = 'fas fa-eye-slash';
        } else {
            field.type = 'password';
            icon.className = 'fas fa-eye';
        }
    };

    // Update summary
    function updateSummary() {
        const providerType = document.getElementById('provider_type');
        const selectedSpecs = Array.from(document.querySelectorAll('input[name="specializations[]"]:checked'))
            .map(cb => cb.value);
        const city = document.getElementById('city').value;
        const state = document.getElementById('state').value;
        const experience = document.getElementById('years_experience').value;

        document.getElementById('summary-provider-type').textContent =
            providerType.options[providerType.selectedIndex]?.text || 'Professional Provider';
        document.getElementById('summary-specializations').textContent =
            selectedSpecs.join(', ') || 'None selected';
        document.getElementById('summary-location').textContent =
            `${city}, ${state}` || 'Not specified';
        document.getElementById('summary-experience').textContent =
            experience ? `${experience} years` : 'Not specified';
    }

    // Save draft functionality
    window.saveDraft = function() {
        const formData = new FormData(document.getElementById('provider-registration-form'));
        const draftData = {};

        for (let [key, value] of formData.entries()) {
            if (key !== '_token' && value) {
                draftData[key] = value;
            }
        }

        localStorage.setItem('provider_registration_draft', JSON.stringify(draftData));

        // Show success message
        const toast = document.createElement('div');
        toast.className = 'alert alert-success position-fixed top-0 end-0 m-3';
        toast.style.zIndex = '9999';
        toast.innerHTML = '<i class="fas fa-save me-2"></i>Draft saved successfully!';
        document.body.appendChild(toast);

        setTimeout(() => toast.remove(), 3000);
    };

    // Load draft on page load
    const draft = localStorage.getItem('provider_registration_draft');
    if (draft) {
        try {
            const draftData = JSON.parse(draft);
            Object.entries(draftData).forEach(([key, value]) => {
                const field = document.querySelector(`[name="${key}"]`);
                if (field) {
                    if (field.type === 'checkbox') {
                        field.checked = true;
                    } else {
                        field.value = value;
                    }
                }
            });
        } catch (e) {
            console.warn('Could not load draft data:', e);
        }
    }

    // Initialize first step
    showStep(1);

    // Trigger provider type change to show documents
    document.getElementById('provider_type').dispatchEvent(new Event('change'));
});
</script>
@endpush
@endsection

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Infanect') }}</title>

    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- FontAwesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <!-- Custom CSS -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">

    <style>
        :root {
            --sidebar-width: 240px;
            --navbar-height: 56px;
            --footer-height: 36px;
            --primary: #ea1c4d;
            --accent: #65c16e;
            --warning: #fbc761;
            --darkgray: #333333;
            --infanect-green: #65c16e;
            --sidebar-bg: #222222;
            --content-padding: 12px;
        }

        body {
            display: flex;
            min-height: 100vh;
            margin: 0;
            overflow: hidden;
            font-size: 0.9rem;
        }

        .app-container {
            display: flex;
            flex: 1;
            width: 100%;
            height: 100vh;
        }

        .navbar-container {
            height: var(--navbar-height);
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            z-index: 110;
            width: 100%;
            background-color: var(--accent);
            border-bottom: 1px solid rgba(255,255,255,0.1);
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
            color: white;
        }

        .navbar-content {
            height: 100%;
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 1rem;
        }

        .sidebar-wrapper {
            width: var(--sidebar-width);
            min-width: var(--sidebar-width);
            flex-shrink: 0;
            position: fixed;
            left: 0;
            top: var(--navbar-height);
            height: calc(100vh - var(--navbar-height));
            background: var(--sidebar-bg);
            color: #fff;
            box-shadow: 0 0 10px rgba(0,0,0,0.2);
            z-index: 100;
            transition: transform 0.3s ease;
            overflow-y: auto;
            overflow-x: hidden;
        }

        .sidebar-content {
            padding: 0.75rem 0.5rem;
            height: 100%;
            display: flex;
            flex-direction: column;
        }

        .content-wrapper {
            flex-grow: 1;
            margin-left: var(--sidebar-width);
            margin-top: var(--navbar-height);
            display: flex;
            flex-direction: column;
            overflow: hidden;
            height: calc(100vh - var(--navbar-height));
            width: calc(100% - var(--sidebar-width));
        }

        .main-content {
            flex: 1;
            overflow-y: auto;
            overflow-x: hidden;
            padding: var(--content-padding);
            background-color: #f8f9fa;
            min-width: 0;
            height: 100%;
            scroll-behavior: smooth;
        }

        .main-content::-webkit-scrollbar {
            width: 8px;
        }

        .main-content::-webkit-scrollbar-track {
            background: #f1f1f1;
            border-radius: 4px;
        }

        .main-content::-webkit-scrollbar-thumb {
            background: #c1c1c1;
            border-radius: 4px;
        }

        .main-content::-webkit-scrollbar-thumb:hover {
            background: #a8a8a8;
        }

        .professional-dashboard {
            padding-bottom: 1.5rem;
        }

        .professional-dashboard .dashboard-section {
            margin-bottom: 1.5rem;
        }

        .professional-dashboard .card-body {
            padding: 1rem;
        }

        .professional-dashboard .card-header {
            padding: 0.75rem 1rem;
        }

        .chart-container-sm {
            height: 200px !important;
            max-height: 200px;
            position: relative;
        }

        .chart-container-md {
            height: 250px !important;
            max-height: 250px;
            position: relative;
        }

        .chart-container-lg {
            height: 300px !important;
            max-height: 300px;
            position: relative;
        }

        .professional-dashboard .metric-card {
            max-height: 140px;
        }

        .professional-dashboard .metric-card .card-body {
            padding: 0.75rem;
        }

        .professional-dashboard .metric-card h3 {
            font-size: 1.5rem;
            margin-bottom: 0.25rem;
        }

        .dashboard-section-title {
            font-size: 1rem;
            font-weight: 600;
            margin-bottom: 0.75rem;
            padding-bottom: 0.5rem;
            border-bottom: 1px solid rgba(0,0,0,0.05);
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .avatar-circle {
            width: 32px;
            height: 32px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #fff;
            font-weight: bold;
            font-size: 0.85rem;
        }

        .footer-container {
            height: var(--footer-height);
        }

        .footer-infanect {
            text-align: center;
            padding: 8px;
            font-size: 0.8rem;
        }
    </style>
    @stack('styles')

    <!-- Chart.js CDN -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels"></script>
</head>
<body>
    <div class="navbar-container">
        <div class="navbar-content">
            @include('layouts.partials.navbar', ['userRole' => $userRole ?? null])
        </div>
    </div>

    <div class="app-container">
        <aside class="sidebar-wrapper">
            <div class="sidebar-content">
                @include('layouts.partials.sidebar', ['userRole' => $userRole ?? null, 'isCompact' => true])
            </div>
        </aside>

        <div class="content-wrapper">
            <main class="main-content {{ isset($userRole) && $userRole === 'provider-professional' ? 'professional-dashboard' : '' }}">
                @yield('content')
            </main>

            <div class="footer-container">
                <footer class="footer-infanect">
                    &copy; {{ date('Y') }} {{ config('app.name', 'Infanect') }}. All rights reserved.
                </footer>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="{{ asset('js/app.js') }}"></script>

    @stack('scripts')
</body>
</html>
            transform: translateY(-3px);
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        }

        .dashboard-section {
            margin-bottom: 20px; /* Reduced margin */
            width: 100%;
        }

        .dashboard-section-title {
            font-size: 1.1rem; /* Smaller font */
            font-weight: 600;
            margin-bottom: 12px; /* Reduced margin */
            padding-bottom: 8px; /* Added padding */
            border-bottom: 1px solid rgba(0,0,0,0.05); /* Added subtle border */
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .avatar-circle {
            width: 32px;
            height: 32px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #fff;
            font-weight: bold;
            font-size: 0.85rem;
        }

        .trend-up { color: #65c16e; }
        .trend-down { color: #ea1c4d; }
        .trend-stable { color: #fbc761; }
        .footer-infanect { text-align: center; padding: 8px; font-size: 0.8rem; }

        /* Responsive styles */
        @media (max-width: 1200px) {
            .main-content {
                padding: 16px;
            }
        }

        @media (max-width: 992px) {
            .dashboard-grid {
                grid-template-columns: repeat(6, 1fr);
            }
        }

        @media (max-width: 768px) {
            .sidebar-wrapper {
                transform: translateX(-100%);
                top: var(--navbar-height);
            }

            .sidebar-wrapper.active {
                transform: translateX(0);
            }

            .content-wrapper {
                margin-left: 0;
                width: 100%;
                margin-top: var(--navbar-height);
            }
        }

        @media (max-width: 576px) {
            .dashboard-grid {
                grid-template-columns: 1fr;
                gap: 15px;
            }
        }
    </style>
    @stack('styles')
    <!-- Chart.js CDN -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels"></script>
</head>
<body>
    {{-- Navbar now outside the app-container to span full width --}}
    <div class="navbar-container">
        <div class="navbar-content">
            @include('layouts.partials.navbar', ['userRole' => $userRole ?? null])
        </div>
    </div>

    <div class="app-container">
        {{-- Sidebar with updated structure --}}
        <aside class="sidebar-wrapper">
            <div class="sidebar-content">
                @include('layouts.partials.sidebar', ['userRole' => $userRole ?? null, 'isCompact' => true])
            </div>
        </aside>

        <div class="content-wrapper">
            {{-- Main content area --}}
            <main class="main-content {{ isset($userRole) && $userRole === 'provider-professional' ? 'professional-dashboard' : '' }}">
                @if(isset($userRole) && $userRole === 'provider-professional')
                    {{-- Professional Dashboard Header --}}
                    <div class="dashboard-section">
                        <div class="d-flex justify-content-between align-items-center mb-4">
                            <div>
                                <h2 class="mb-1">Professional Dashboard</h2>
                                <p class="text-muted mb-0">Welcome back! Here's your practice overview for today.</p>
                            </div>
                            <div class="d-flex gap-2">
                                <button class="btn btn-outline-primary btn-sm">
                                    <i class="fas fa-download me-1"></i> Export Report
                                </button>
                                <button class="btn btn-primary btn-sm">
                                    <i class="fas fa-plus me-1"></i> New Session
                                </button>
                            </div>
                        </div>
                    </div>

                    {{-- Quick Actions Section --}}
                    <div class="dashboard-section">
                        <div class="card shadow-sm border-0 mb-4">
                            <div class="card-body p-3">
                                <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
                                    <h6 class="mb-0 text-muted fw-bold">Quick Actions</h6>
                                    <div class="d-flex flex-wrap gap-2">
                                        <button class="btn btn-sm btn-primary">
                                            <i class="fas fa-plus me-1"></i> New Patient
                                        </button>
                                        <button class="btn btn-sm btn-success">
                                            <i class="fas fa-calendar-plus me-1"></i> Schedule Session
                                        </button>
                                        <button class="btn btn-sm btn-info">
                                            <i class="fas fa-notes-medical me-1"></i> Add Notes
                                        </button>
                                        <button class="btn btn-sm btn-warning">
                                            <i class="fas fa-file-invoice me-1"></i> Create Invoice
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Mental Health Metrics Section --}}
                    <div class="dashboard-section">
                        <div class="dashboard-section-title">
                            <span>Mental Health Practice Overview</span>
                            <div class="btn-group btn-group-sm">
                                <button type="button" class="btn btn-outline-secondary active" data-period="today">Today</button>
                                <button type="button" class="btn btn-outline-secondary" data-period="week">Week</button>
                                <button type="button" class="btn btn-outline-secondary" data-period="month">Month</button>
                                <button type="button" class="btn btn-outline-secondary" data-period="year">Year</button>
                            </div>
                        </div>

                        <div class="row g-3 mb-4">
                            {{-- Active Patients --}}
                            <div class="col-sm-6 col-md-3">
                                <div class="card h-100 border-0 shadow-sm" style="background: linear-gradient(135deg, #4A90E2, #7ED321);">
                                    <div class="card-body text-white">
                                        <div class="d-flex justify-content-between align-items-start mb-3">
                                            <div>
                                                <div class="small opacity-75 mb-1">Active Patients</div>
                                                <h3 class="mb-0">{{ $metrics['activePatients'] ?? 147 }}</h3>
                                                <div class="small mt-2">
                                                    <i class="fas fa-arrow-up me-1"></i> +8% this month
                                                </div>
                                            </div>
                                            <div class="p-2 rounded" style="background: rgba(255,255,255,0.2);">
                                                <i class="fas fa-users fa-lg"></i>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {{-- Sessions This Week --}}
                            <div class="col-sm-6 col-md-3">
                                <div class="card h-100 border-0 shadow-sm" style="background: linear-gradient(135deg, #9013FE, #00BCD4);">
                                    <div class="card-body text-white">
                                        <div class="d-flex justify-content-between align-items-start mb-3">
                                            <div>
                                                <div class="small opacity-75 mb-1">Sessions This Week</div>
                                                <h3 class="mb-0">{{ $metrics['weeklySessions'] ?? 64 }}</h3>
                                                <div class="small mt-2">
                                                    <i class="fas fa-arrow-up me-1"></i> +12% vs last week
                                                </div>
                                            </div>
                                            <div class="p-2 rounded" style="background: rgba(255,255,255,0.2);">
                                                <i class="fas fa-calendar-check fa-lg"></i>
                                            </div>
                                        </div>
                                        <div class="progress bg-white bg-opacity-25" style="height: 6px;">
                                            <div class="progress-bar bg-white" style="width: 75%"></div>
                                        </div>
                                        <div class="small mt-1 opacity-75">75% of capacity</div>
                                    </div>
                                </div>
                            </div>

                            {{-- Treatment Success Rate --}}
                            <div class="col-sm-6 col-md-3">
                                <div class="card h-100 border-0 shadow-sm" style="background: linear-gradient(135deg, #FFD700, #FF9500);">
                                    <div class="card-body text-white">
                                        <div class="d-flex justify-content-between align-items-start mb-3">
                                            <div>
                                                <div class="small opacity-75 mb-1">Treatment Success</div>
                                                <h3 class="mb-0">{{ $metrics['successRate'] ?? '92%' }}</h3>
                                                <div class="small mt-2">
                                                    <i class="fas fa-arrow-up me-1"></i> +5% this quarter
                                                </div>
                                            </div>
                                            <div class="p-2 rounded" style="background: rgba(255,255,255,0.2);">
                                                <i class="fas fa-heart fa-lg"></i>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {{-- Crisis Interventions --}}
                            <div class="col-sm-6 col-md-3">
                                <div class="card h-100 border-0 shadow-sm" style="background: linear-gradient(135deg, #FF6B6B, #EE5A24);">
                                    <div class="card-body text-white">
                                        <div class="d-flex justify-content-between align-items-start mb-3">
                                            <div>
                                                <div class="small opacity-75 mb-1">Crisis Alerts</div>
                                                <h3 class="mb-0">{{ $metrics['crisisInterventions'] ?? 3 }}</h3>
                                                <div class="small mt-2">
                                                    <i class="fas fa-shield-alt me-1"></i> All resolved
                                                </div>
                                            </div>
                                            <div class="p-2 rounded" style="background: rgba(255,255,255,0.2);">
                                                <i class="fas fa-exclamation-triangle fa-lg"></i>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Charts and Analytics Section --}}
                    <div class="dashboard-section">
                        <div class="dashboard-section-title">
                            <span>Analytics & Insights</span>
                        </div>

                        <div class="row mb-4">
                            {{-- Patient Progress Chart --}}
                            <div class="col-lg-8 mb-4">
                                <div class="card shadow-sm h-100 border-0">
                                    <div class="card-header d-flex justify-content-between align-items-center bg-white">
                                        <span class="fw-bold">Patient Progress Analytics</span>
                                        <div class="btn-group btn-group-sm">
                                            <button type="button" class="btn btn-outline-secondary active">PHQ-9</button>
                                            <button type="button" class="btn btn-outline-secondary">GAD-7</button>
                                            <button type="button" class="btn btn-outline-secondary">Beck</button>
                                        </div>
                                    </div>
                                    <div class="card-body">
                                        <canvas id="patientProgressChart" style="height: 300px;"></canvas>
                                    </div>
                                </div>
                            </div>

                            {{-- Treatment Modalities --}}
                            <div class="col-lg-4 mb-4">
                                <div class="card shadow-sm h-100 border-0">
                                    <div class="card-header fw-bold bg-white">Treatment Modalities</div>
                                    <div class="card-body d-flex align-items-center justify-content-center">
                                        <canvas id="treatmentModalitiesChart" style="height: 300px;"></canvas>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row mb-4">
                            {{-- Mood Tracking --}}
                            <div class="col-lg-6 mb-4">
                                <div class="card shadow-sm h-100 border-0">
                                    <div class="card-header fw-bold bg-white">Weekly Mood Trends</div>
                                    <div class="card-body">
                                        <canvas id="moodTrendChart" style="height: 250px;"></canvas>
                                    </div>
                                </div>
                            </div>

                            {{-- Session Outcomes --}}
                            <div class="col-lg-6 mb-4">
                                <div class="card shadow-sm h-100 border-0">
                                    <div class="card-header fw-bold bg-white">Session Outcomes</div>
                                    <div class="card-body">
                                        <canvas id="sessionOutcomesChart" style="height: 250px;"></canvas>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Today's Schedule --}}
                    <div class="dashboard-section">
                        <div class="dashboard-section-title">
                            <span>Today's Schedule</span>
                            <a href="#" class="btn btn-sm btn-outline-primary">
                                <i class="fas fa-calendar-alt me-1"></i> Full Calendar
                            </a>
                        </div>
                        <div class="card shadow-sm border-0 mb-4">
                            <div class="card-body p-0">
                                <div class="table-responsive">
                                    <table class="table table-hover mb-0">
                                        <thead class="table-light">
                                            <tr>
                                                <th>Time</th>
                                                <th>Patient</th>
                                                <th>Session Type</th>
                                                <th>Status</th>
                                                <th>Notes</th>
                                                <th>Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td>
                                                    <div class="fw-bold">9:00 AM</div>
                                                    <small class="text-muted">1 hour</small>
                                                </td>
                                                <td>
                                                    <div class="d-flex align-items-center">
                                                        <div class="avatar-circle me-2 bg-primary">SJ</div>
                                                        <div>
                                                            <div class="fw-semibold">Sarah Johnson</div>
                                                            <small class="text-muted">Depression, Anxiety</small>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>CBT Session</td>
                                                <td><span class="badge bg-success">Confirmed</span></td>
                                                <td><small class="text-muted">Follow-up on homework</small></td>
                                                <td>
                                                    <div class="btn-group btn-group-sm">
                                                        <button class="btn btn-outline-primary" title="Start Session">
                                                            <i class="fas fa-play"></i>
                                                        </button>
                                                        <button class="btn btn-outline-secondary" title="View Notes">
                                                            <i class="fas fa-file-alt"></i>
                                                        </button>
                                                        <button class="btn btn-outline-warning" title="Reschedule">
                                                            <i class="fas fa-calendar-alt"></i>
                                                        </button>
                                                    </div>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <div class="fw-bold">11:00 AM</div>
                                                    <small class="text-muted">50 minutes</small>
                                                </td>
                                                <td>
                                                    <div class="d-flex align-items-center">
                                                        <div class="avatar-circle me-2 bg-info">MC</div>
                                                        <div>
                                                            <div class="fw-semibold">Michael Chen</div>
                                                            <small class="text-muted">PTSD</small>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>EMDR Session</td>
                                                <td><span class="badge bg-warning text-dark">Pending</span></td>
                                                <td><small class="text-muted">New patient intake</small></td>
                                                <td>
                                                    <div class="btn-group btn-group-sm">
                                                        <button class="btn btn-outline-primary" title="Start Session">
                                                            <i class="fas fa-play"></i>
                                                        </button>
                                                        <button class="btn btn-outline-secondary" title="View Notes">
                                                            <i class="fas fa-file-alt"></i>
                                                        </button>
                                                        <button class="btn btn-outline-warning" title="Reschedule">
                                                            <i class="fas fa-calendar-alt"></i>
                                                        </button>
                                                    </div>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <div class="fw-bold">2:00 PM</div>
                                                    <small class="text-muted">90 minutes</small>
                                                </td>
                                                <td>
                                                    <div class="d-flex align-items-center">
                                                        <div class="avatar-circle me-2 bg-success">GS</div>
                                                        <div>
                                                            <div class="fw-semibold">Group Session</div>
                                                            <small class="text-muted">Anxiety Support Group</small>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>Group Therapy</td>
                                                <td><span class="badge bg-info">Scheduled</span></td>
                                                <td><small class="text-muted">6 participants</small></td>
                                                <td>
                                                    <div class="btn-group btn-group-sm">
                                                        <button class="btn btn-outline-primary" title="Start Session">
                                                            <i class="fas fa-play"></i>
                                                        </button>
                                                        <button class="btn btn-outline-secondary" title="View Notes">
                                                            <i class="fas fa-file-alt"></i>
                                                        </button>
                                                        <button class="btn btn-outline-info" title="Manage Group">
                                                            <i class="fas fa-users"></i>
                                                        </button>
                                                    </div>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Patient Risk Dashboard --}}
                    <div class="dashboard-section">
                        <div class="dashboard-section-title">
                            <span>Patient Risk Assessment</span>
                            <div class="btn-group btn-group-sm">
                                <button class="btn btn-outline-danger btn-sm">High Risk (3)</button>
                                <button class="btn btn-outline-warning btn-sm">Medium Risk (7)</button>
                                <button class="btn btn-outline-success btn-sm">Low Risk (23)</button>
                            </div>
                        </div>
                        <div class="card shadow-sm border-0 mb-4">
                            <div class="card-body p-0">
                                <div class="table-responsive">
                                    <table class="table table-hover mb-0">
                                        <thead class="table-light">
                                            <tr>
                                                <th>Patient</th>
                                                <th>Risk Level</th>
                                                <th>Last Assessment</th>
                                                <th>Next Session</th>
                                                <th>Risk Factors</th>
                                                <th>Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr class="table-danger table-danger">
                                                <td>
                                                    <div class="d-flex align-items-center">
                                                        <div class="avatar-circle me-2 bg-danger">ED</div>
                                                        <div>
                                                            <div class="fw-semibold">Emma Davis</div>
                                                            <small class="text-muted">Major Depression</small>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td><span class="badge bg-danger">High Risk</span></td>
                                                <td>Yesterday</td>
                                                <td>Today 3:30 PM</td>
                                                <td>
                                                    <small class="text-muted">
                                                        <i class="fas fa-exclamation-triangle text-warning"></i>
                                                        Suicidal ideation, Social isolation
                                                    </small>
                                                </td>
                                                <td>
                                                    <button class="btn btn-danger btn-sm me-1" title="Crisis Protocol">
                                                        <i class="fas fa-phone"></i>
                                                    </button>
                                                    <button class="btn btn-outline-secondary btn-sm" title="View Details">
                                                        <i class="fas fa-eye"></i>
                                                    </button>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <div class="d-flex align-items-center">
                                                        <div class="avatar-circle me-2 bg-warning">JW</div>
                                                        <div>
                                                            <div class="fw-semibold">James Wilson</div>
                                                            <small class="text-muted">PTSD, Anxiety</small>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td><span class="badge bg-warning text-dark">Medium Risk</span></td>
                                                <td>3 days ago</td>
                                                <td>Friday 10:00 AM</td>
                                                <td>
                                                    <small class="text-muted">
                                                        <i class="fas fa-moon text-info"></i>
                                                        Sleep disturbances, Hypervigilance
                                                    </small>
                                                </td>
                                                <td>
                                                    <button class="btn btn-warning btn-sm me-1" title="Follow Up">
                                                        <i class="fas fa-phone"></i>
                                                    </button>
                                                    <button class="btn btn-outline-secondary btn-sm" title="View Details">
                                                        <i class="fas fa-eye"></i>
                                                    </button>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- AI Insights & Recommendations --}}
                    <div class="dashboard-section">
                        <div class="dashboard-section-title">
                            <span>AI-Powered Clinical Insights</span>
                        </div>
                        <div class="row g-3 mb-4">
                            <div class="col-md-4">
                                <div class="card shadow-sm border-0 h-100" style="background: linear-gradient(135deg, #667eea, #764ba2);">
                                    <div class="card-body text-white">
                                        <div class="d-flex align-items-start mb-3">
                                            <div class="p-2 rounded me-3" style="background: rgba(255,255,255,0.2);">
                                                <i class="fas fa-brain"></i>
                                            </div>
                                            <div>
                                                <h6 class="card-title mb-1">Treatment Efficacy</h6>
                                                <p class="card-text small opacity-90">CBT shows 87% improvement rate for your anxiety patients. Consider increasing CBT sessions for optimal outcomes.</p>
                                            </div>
                                        </div>
                                        <button class="btn btn-light btn-sm">
                                            <i class="fas fa-chart-line me-1"></i> View Analysis
                                        </button>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="card shadow-sm border-0 h-100" style="background: linear-gradient(135deg, #f093fb, #f5576c);">
                                    <div class="card-body text-white">
                                        <div class="d-flex align-items-start mb-3">
                                            <div class="p-2 rounded me-3" style="background: rgba(255,255,255,0.2);">
                                                <i class="fas fa-clock"></i>
                                            </div>
                                            <div>
                                                <h6 class="card-title mb-1">Session Optimization</h6>
                                                <p class="card-text small opacity-90">Patients show better engagement in 50-minute vs 45-minute sessions. Consider adjusting your schedule.</p>
                                            </div>
                                        </div>
                                        <button class="btn btn-light btn-sm">
                                            <i class="fas fa-calendar me-1"></i> Optimize Schedule
                                        </button>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="card shadow-sm border-0 h-100" style="background: linear-gradient(135deg, #4facfe, #00f2fe);">
                                    <div class="card-body text-white">
                                        <div class="d-flex align-items-start mb-3">
                                            <div class="p-2 rounded me-3" style="background: rgba(255,255,255,0.2);">
                                                <i class="fas fa-user-check"></i>
                                            </div>
                                            <div>
                                                <h6 class="card-title mb-1">Relapse Prevention</h6>
                                                <p class="card-text small opacity-90">5 patients showing early relapse indicators. Proactive intervention recommended within 48 hours.</p>
                                            </div>
                                        </div>
                                        <button class="btn btn-light btn-sm">
                                            <i class="fas fa-shield-alt me-1"></i> Take Action
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif

                {{-- Top Clients Table --}}
                @if(isset($topClients))
                <div class="dashboard-section">
                    <div class="dashboard-section-title">
                        <span>Client Information</span>
                    </div>
                    <div class="card shadow-sm mb-4">
                        <div class="card-header bg-white d-flex justify-content-between align-items-center">
                            <span class="fw-bold">Top Performing Clients</span>
                            <a href="{{ route_exists('clients.index') ? route('clients.index') : '#' }}"
                               class="btn btn-sm btn-outline-primary">View All</a>
                        </div>
                        <div class="card-body p-0">
                            <div class="table-responsive">
                                <table class="table table-hover mb-0">
                                    <thead class="table-light">
                                        <tr>
                                            <th>#</th>
                                            <th>Client Name</th>
                                            <th>Email</th>
                                            <th>Location</th>
                                            <th>Total Bookings</th>
                                            <th>Total Revenue</th>
                                            <th>Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($topClients as $i => $client)
                                        <tr>
                                            <td>{{ $i+1 }}</td>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <div class="avatar-circle me-2"
                                                         style="background-color: {{ ['#65c16e','#fbc761','#ea1c4d','#14213d','#4361ee'][$i % 5] }}">
                                                        {{ strtoupper(substr($client['name'] ?? 'A', 0, 1)) }}
                                                    </div>
                                                    {{ $client['name'] }}
                                                </div>
                                            </td>
                                            <td>{{ $client['email'] }}</td>
                                            <td>{{ $client['location'] ?? 'N/A' }}</td>
                                            <td>{{ $client['bookings'] }}</td>
                                            <td>${{ number_format($client['revenue'], 2) }}</td>
                                            <td>
                                                <span class="badge bg-{{ ['success','primary','warning','secondary'][$i % 4] }}">
                                                    {{ ['Active','Regular','New','Inactive'][$i % 4] }}
                                                </span>
                                            </td>
                                        </tr>
                                        @empty
                                        <tr>
                                            <td colspan="7" class="text-center text-muted py-4">No client data available</td>
                                        </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                @endif

                @yield('content')
            </main>

            {{-- Footer --}}
            <div class="footer-container">
                <footer class="footer-infanect">
                    &copy; {{ date('Y') }} {{ config('app.name', 'Infanect') }}. All rights reserved.
                </footer>
            </div>
        </div>
    </div>

    {{-- Toggle sidebar button for mobile --}}
    <button id="sidebarToggle" class="btn btn-primary position-fixed d-md-none"
            style="bottom: 15px; right: 15px; z-index: 1060; width: 45px; height: 45px; border-radius: 50%;">
        <i class="fas fa-bars"></i>
    </button>

    {{-- Scripts --}}
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="{{ asset('js/app.js') }}"></script>

    <script>
        // Add a class to fix the sidebar to a specific height if needed
        document.addEventListener('DOMContentLoaded', function() {
            const sidebar = document.querySelector('.sidebar-wrapper');
            const sidebarContent = document.querySelector('.sidebar-content');

            // Calculate available space for nav items - prevents overflow
            if (sidebar && sidebarContent) {
                const sidebarHeader = sidebar.querySelector('.sidebar-header');
                const headerHeight = sidebarHeader ? sidebarHeader.offsetHeight : 0;
                const availableHeight = sidebar.clientHeight - headerHeight - 40; // 40px buffer

                // Add a class to limit the main navigation height
                const navContainer = sidebar.querySelector('.sidebar-nav-container');
                if (navContainer) {
                    navContainer.style.maxHeight = availableHeight + 'px';
                }
            }

            // Sidebar toggle functionality
            const sidebarToggle = document.getElementById('sidebarToggle');
            if (sidebarToggle) {
                sidebarToggle.addEventListener('click', function() {
                    sidebar.classList.toggle('active');
                });
            }

            // Close sidebar when clicking outside on mobile
            document.addEventListener('click', function(event) {
                if (window.innerWidth < 768 &&
                    sidebar &&
                    sidebar.classList.contains('active') &&
                    !sidebar.contains(event.target) &&
                    event.target !== sidebarToggle) {
                    sidebar.classList.remove('active');
                }
            });
        });
    </script>

    <!-- Chart.js scripts -->
    @if(isset($userRole) && $userRole === 'provider-professional')
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        // Register Chart.js plugins
        if (typeof Chart !== 'undefined' && typeof ChartDataLabels !== 'undefined') {
            Chart.register(ChartDataLabels);
        }

        // Patient Progress Chart
        const progressCtx = document.getElementById('patientProgressChart');
        if (progressCtx) {
            new Chart(progressCtx.getContext('2d'), {
                type: 'line',
                data: {
                    labels: ['Week 1', 'Week 2', 'Week 3', 'Week 4', 'Week 5', 'Week 6'],
                    datasets: [{
                        label: 'Depression Scores (PHQ-9)',
                        data: [18, 16, 14, 12, 10, 8],
                        borderColor: '#FF6B6B',
                        backgroundColor: 'rgba(255, 107, 107, 0.1)',
                        tension: 0.4,
                        fill: true
                    }, {
                        label: 'Anxiety Scores (GAD-7)',
                        data: [15, 13, 12, 9, 8, 6],
                        borderColor: '#4ECDC4',
                        backgroundColor: 'rgba(78, 205, 196, 0.1)',
                        tension: 0.4,
                        fill: true
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { position: 'top' }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            max: 20,
                            title: { display: true, text: 'Score' }
                        }
                    }
                }
            });
        }

        // Treatment Modalities Chart
        const modalitiesCtx = document.getElementById('treatmentModalitiesChart');
        if (modalitiesCtx) {
            new Chart(modalitiesCtx.getContext('2d'), {
                type: 'doughnut',
                data: {
                    labels: ['CBT', 'DBT', 'EMDR', 'Psychodynamic', 'Mindfulness'],
                    datasets: [{
                        data: [35, 25, 15, 15, 10],
                        backgroundColor: ['#4A90E2', '#7ED321', '#9013FE', '#00BCD4', '#FF9500'],
                        borderWidth: 0
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'bottom',
                            labels: {
                                padding: 20,
                                usePointStyle: true
                            }
                        }
                    }
                }
            });
        }

        // Mood Trend Chart
        const moodCtx = document.getElementById('moodTrendChart');
        if (moodCtx) {
            new Chart(moodCtx.getContext('2d'), {
                type: 'bar',
                data: {
                    labels: ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'],
                    datasets: [{
                        label: 'Average Mood (1-10)',
                        data: [6.2, 7.1, 6.8, 7.5, 8.2, 7.9, 7.3],
                        backgroundColor: ['#FF6B6B', '#FF8E53', '#FFD93D', '#6BCF7F', '#4D96FF', '#9B59B6', '#1ABC9C'],
                        borderRadius: 4,
                        borderSkipped: false,
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
                            title: { display: true, text: 'Mood Score' }
                        }
                    }
                }
            });
        }

        // Session Outcomes Chart
        const outcomesCtx = document.getElementById('sessionOutcomesChart');
        if (outcomesCtx) {
            new Chart(outcomesCtx.getContext('2d'), {
                type: 'pie',
                data: {
                    labels: ['Excellent Progress', 'Good Progress', 'Moderate Progress', 'No Change', 'Setback'],
                    datasets: [{
                        data: [25, 40, 20, 10, 5],
                        backgroundColor: ['#28a745', '#17a2b8', '#ffc107', '#fd7e14', '#dc3545'],
                        borderWidth: 2,
                        borderColor: '#fff'
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'bottom',
                            labels: {
                                padding: 15,
                                usePointStyle: true
                            }
                        }
                    }
                }
            });
        }

        // Existing chart implementations
        // Booking Status Doughnut Chart
        const bookingStatusElement = document.getElementById('bookingStatusDoughnut');
        if (bookingStatusElement) {
            new Chart(bookingStatusElement, {
                type: 'doughnut',
                data: {
                    labels: {!! json_encode($chartData['bookingStatus']['labels'] ?? ['Completed','Upcoming','Cancelled']) !!},
                    datasets: [{
                        data: {!! json_encode($chartData['bookingStatus']['data'] ?? [65,25,10]) !!},
                        backgroundColor: ['#65c16e','#fbc761','#ea1c4d']
                    }]
                },
                options: {
                    responsive: true,
                    cutout: '70%',
                    plugins: {
                        legend: { position: 'bottom' },
                        datalabels: {
                            color: 'white',
                            font: { weight: 'bold' },
                            formatter: (value) => value + '%'
                        }
                    }
                }
            });
        }

        // Revenue Performance Chart
        const revenueChartElement = document.getElementById('revenuePerformanceChart');
        if (revenueChartElement) {
            new Chart(revenueChartElement, {
                type: 'line',
                data: {
                    labels: {!! json_encode($chartData['revenueOverTime']['labels'] ?? ['Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec']) !!},
                    datasets: [
                        {
                            label: 'Actual Revenue',
                            data: {!! json_encode($chartData['revenueOverTime']['data'] ?? [1500,1800,2200,1900,2400,2800,3100,3400,3300,3700,4100,4500]) !!},
                            borderColor: '#65c16e',
                            backgroundColor: 'rgba(101,193,110,0.15)',
                            fill: true,
                            tension: 0.4
                        },
                        {
                            label: 'Forecast',
                            data: {!! json_encode($chartData['revenueOverTime']['forecast'] ?? [1500,1900,2300,2100,2600,3000,3400,3700,3900,4200,4600,5000]) !!},
                            borderColor: '#fbc761',
                            borderDash: [5,5],
                            fill: false,
                            tension: 0.4
                        }
                    ]
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: { position: 'top' }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: { callback: value => '$' + value.toLocaleString() }
                        }
                    }
                }
            });
        }

        // Client Demographics Chart
        const demographicsElement = document.getElementById('clientDemographicsChart');
        if (demographicsElement) {
            new Chart(demographicsElement, {
                type: 'pie',
                data: {
                    labels: {!! json_encode($chartData['clientDemographics']['labels'] ?? ['Individual','Corporate','Government','Non-profit','Educational']) !!},
                    datasets: [{
                        data: {!! json_encode($chartData['clientDemographics']['data'] ?? [45,25,10,12,8]) !!},
                        backgroundColor: ['#65c16e','#14213d','#fbc761','#4361ee','#ea1c4d']
                    }]
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: { position: 'bottom' }
                    }
                }
            });
        }

        // Service Popularity Chart
        const serviceElement = document.getElementById('servicePopularityChart');
        if (serviceElement) {
            new Chart(serviceElement, {
                type: 'bar',
                data: {
                    labels: {!! json_encode($chartData['servicePopularity']['labels'] ?? ['Service A','Service B','Service C','Service D','Service E']) !!},
                    datasets: [{
                        label: 'Booking Count',
                        data: {!! json_encode($chartData['servicePopularity']['data'] ?? [65,59,80,81,56]) !!},
                        backgroundColor: ['#65c16e','#fbc761','#ea1c4d','#4361ee','#14213d']
                    }]
                },
                options: {
                    indexAxis: 'y',
                    plugins: { legend: { display: false } }
                }
            });
        }

        // Satisfaction Chart
        const satisfactionElement = document.getElementById('satisfactionChart');
        if (satisfactionElement) {
            new Chart(satisfactionElement, {
                type: 'bar',
                data: {
                    labels: {!! json_encode($chartData['satisfaction']['labels'] ?? ['1 ★','2 ★','3 ★','4 ★','5 ★']) !!},
                    datasets: [{
                        label: 'Reviews',
                        data: {!! json_encode($chartData['satisfaction']['data'] ?? [2,5,10,25,58]) !!},
                        backgroundColor: ['#ea1c4d','#fbc761','#fbc761','#65c16e','#65c16e']
                    }]
                },
                options: {
                    plugins: { legend: { display: false } },
                    scales: {
                        y: {
                            beginAtZero: true,
                            title: { display: true, text: 'Number of Reviews' }
                        }
                    }
                }
            });
        }
    });
    </script>
    @endif

    @if(isset($userRole) && $userRole === 'provider-bonding')
    <script>
        // Register ChartJS plugin
        if (typeof Chart !== 'undefined' && typeof ChartDataLabels !== 'undefined') {
            Chart.register(ChartDataLabels);
        }

        // Chart data initialization
        const bondingChartData = {
            bookingStatus: {
                labels: {!! json_encode($chartData['bookingStatus']['labels'] ?? ['Completed','Upcoming','Cancelled']) !!},
                data: {!! json_encode($chartData['bookingStatus']['data'] ?? [55,30,15]) !!}
            },
            revenueOverTime: {
                labels: {!! json_encode($chartData['revenueOverTime']['labels'] ?? ['Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec']) !!},
                data: {!! json_encode($chartData['revenueOverTime']['data'] ?? [1200,1500,1800,1600,2000,2300,2600,2900,2800,3200,3500,4000]) !!}
            }
        };

        // Wait for DOM to be ready
        document.addEventListener('DOMContentLoaded', function() {
            // Booking Status Doughnut Chart
            const bookingElement = document.getElementById('bookingStatusDoughnut');
            if (bookingElement) {
                new Chart(bookingElement, {
                    type: 'doughnut',
                    data: {
                        labels: bondingChartData.bookingStatus.labels,
                        datasets: [{
                            data: bondingChartData.bookingStatus.data,
                            backgroundColor: ['#4361ee','#65c16e','#ea1c4d']
                        }]
                    },
                    options: {
                        responsive: true,
                        cutout: '70%',
                        plugins: {
                            legend: { position: 'bottom' }
                        }
                    }
                });
            }

            // Revenue Performance Chart
            const revenueElement = document.getElementById('revenuePerformanceChart');
            if (revenueElement) {
                new Chart(revenueElement, {
                    type: 'line',
                    data: {
                        labels: bondingChartData.revenueOverTime.labels,
                        datasets: [{
                            label: 'Actual Revenue',
                            data: bondingChartData.revenueOverTime.data,
                            borderColor: '#4361ee',
                            backgroundColor: 'rgba(67,97,238,0.2)'
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false
                    }
                });
            }
        });
    </script>
    @endif

    @stack('scripts')
</body>
</html>



@php
    $topBondingActivities = $topBondingActivities ?? collect();
    $topServices = $topServices ?? collect();
    $topProviders = $topProviders ?? collect();
    $engagements = $engagements ?? collect();
@endphp

<!-- Activity Engagement Card -->
<div class="card shadow-sm border-0 rounded-4 mb-4">
    <div class="card-header bg-gradient text-white d-flex justify-content-between align-items-center rounded-top-4"
         style="background: linear-gradient(90deg, #4facfe 0%, #00f2fe 100%);">
        <h5 class="mb-0 fw-bold">
            🌍 {{ __('dashboard.activity_engagement') }}
        </h5>
        <span class="badge bg-light text-dark px-3" id="engagement-count">
            {{ $engagements->sum('participants_count') ?? 0 }}
        </span>
    </div>
    <div class="card-body" id="activity-engagement-content">
        @if($engagements->isEmpty())
            <div class="text-center text-muted py-4">
                <i class="bi bi-activity fs-2 d-block mb-2"></i>
                {{ __('dashboard.no_engagement_data') }}
            </div>
        @else
            <div class="space-y-3">
                @foreach($engagements as $engagement)
                    <div class="flex items-center justify-between p-3 bg-gradient-to-r from-indigo-50 to-indigo-100 rounded-lg border border-indigo-200 shadow-sm">
                        <div>
                            <h4 class="text-gray-900 font-semibold">{{ $engagement['title'] ?? $engagement->title ?? 'Untitled Activity' }}</h4>
                            <p class="text-gray-600 text-sm">{{ $engagement['description'] ?? $engagement->description ?? '-' }}</p>
                        </div>
                        <span class="text-indigo-700 font-medium">{{ $engagement['score'] ?? $engagement->participants_count ?? 0 }} pts</span>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</div>

<!-- Tabs -->
<ul class="nav nav-tabs fw-semibold" id="aspTabs" role="tablist">
    <li class="nav-item">
        <button class="nav-link active d-flex align-items-center" id="activities-tab" data-bs-toggle="tab" data-bs-target="#activities" type="button" role="tab">
            🏃 Top Activities <span class="badge bg-success ms-2" id="activities-count">{{ $topBondingActivities->count() }}</span>
        </button>
    </li>
    <li class="nav-item">
        <button class="nav-link d-flex align-items-center" id="services-tab" data-bs-toggle="tab" data-bs-target="#services" type="button" role="tab">
            🎭 Top Services <span class="badge bg-primary ms-2" id="services-count">{{ $topServices->count() }}</span>
        </button>
    </li>
    <li class="nav-item">
        <button class="nav-link d-flex align-items-center" id="providers-tab" data-bs-toggle="tab" data-bs-target="#providers" type="button" role="tab">
            👩‍🏫 Top Providers <span class="badge bg-purple ms-2" id="providers-count">{{ $topProviders->count() }}</span>
        </button>
    </li>
</ul>

<!-- Tab Contents -->
<div class="tab-content mt-3 position-relative">
    <!-- Activities Tab -->
    <div class="tab-pane fade show active" id="activities" role="tabpanel">
        @if($topBondingActivities->isEmpty())
            <p class="text-gray-500 mt-3">No bonding activities available.</p>
        @else
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 mt-3">
                @foreach($topBondingActivities as $activity)
                    <div class="p-4 bg-gradient-to-r from-green-50 to-green-100 rounded-xl border border-green-200 shadow-sm">
                        <h5 class="font-semibold text-gray-900">{{ $activity['name'] ?? 'Untitled' }}</h5>
                        <p class="text-gray-600 text-sm">{{ $activity['description'] ?? '-' }}</p>
                    </div>
                @endforeach
            </div>
        @endif
    </div>

    <!-- Services Tab -->
    <div class="tab-pane fade" id="services" role="tabpanel">
        @if($topServices->isEmpty())
            <p class="text-gray-500 mt-3">No services available.</p>
        @else
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 mt-3">
                @foreach($topServices as $service)
                    <div class="p-4 bg-gradient-to-r from-blue-50 to-blue-100 rounded-xl border border-blue-200 shadow-sm">
                        <h5 class="font-semibold text-gray-900">{{ $service['name'] ?? 'Untitled' }}</h5>
                        <p class="text-gray-600 text-sm">{{ $service['description'] ?? '-' }}</p>
                    </div>
                @endforeach
            </div>
        @endif
    </div>

    <!-- Providers Tab -->
    <div class="tab-pane fade" id="providers" role="tabpanel">
        @if($topProviders->isEmpty())
            <p class="text-gray-500 mt-3">No providers available.</p>
        @else
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 mt-3">
                @foreach($topProviders as $provider)
                    <div class="p-4 bg-gradient-to-r from-purple-50 to-purple-100 rounded-xl border border-purple-200 shadow-sm">
                        <h5 class="font-semibold text-gray-900">{{ $provider['name'] ?? 'Untitled' }}</h5>
                        <p class="text-gray-600 text-sm">{{ $provider['specialty'] ?? '-' }}</p>
                    </div>
                @endforeach
            </div>
        @endif
    </div>

    <!-- Overlay -->
    <div id="search-overlay" class="d-none position-absolute top-0 start-0 w-100 h-100 bg-white bg-opacity-75 d-flex justify-content-center align-items-center" style="z-index:1050;">
        <div class="spinner-border text-primary me-2" role="status"></div>
        <span class="fw-semibold text-muted">Searching...</span>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener("DOMContentLoaded", function () {
    const overlay = document.getElementById("search-overlay");
    const counts = {
        activities: document.getElementById("activities-count"),
        services: document.getElementById("services-count"),
        providers: document.getElementById("providers-count"),
        engagement: document.getElementById("engagement-count")
    };

    function intl(n){ return new Intl.NumberFormat(navigator.language).format(n); }
    function showOverlay(){ overlay?.classList.remove("d-none"); }
    function hideOverlay(){ overlay?.classList.add("d-none"); }

    // Real-time refresh via Echo
    if (window.Echo) {
        window.Echo.channel('dashboard.global')
            .listen('.DashboardUpdated', (e) => {
                if(e.counts){
                    counts.activities.textContent = intl(e.counts.activities ?? 0);
                    counts.services.textContent   = intl(e.counts.services ?? 0);
                    counts.providers.textContent  = intl(e.counts.providers ?? 0);
                    counts.engagement.textContent = intl(e.counts.engagements ?? 0);
                }
                if(e.activitiesHtml) document.getElementById("activities").innerHTML = e.activitiesHtml;
                if(e.servicesHtml)   document.getElementById("services").innerHTML   = e.servicesHtml;
                if(e.providersHtml)  document.getElementById("providers").innerHTML  = e.providersHtml;
                if(e.engagementsHtml) document.getElementById("activity-engagement-content").innerHTML = e.engagementsHtml;
            });
    }
});
</script>
@endpush

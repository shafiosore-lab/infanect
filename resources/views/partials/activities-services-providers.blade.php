<section class="mt-4">
    <!-- Tabs -->
    <ul class="nav nav-tabs fw-semibold" id="aspTabs" role="tablist">
        <li class="nav-item">
            <button class="nav-link active d-flex align-items-center" id="activities-tab"
                data-bs-toggle="tab" data-bs-target="#activities" type="button" role="tab">
                🏃 {{ __('dashboard.top_activities') }}
                <span class="badge bg-success ms-2" id="activities-count">{{ $topBondingActivities->count() ?? 0 }}</span>
            </button>
        </li>
        <li class="nav-item">
            <button class="nav-link d-flex align-items-center" id="services-tab"
                data-bs-toggle="tab" data-bs-target="#services" type="button" role="tab">
                🎭 {{ __('dashboard.top_services') }}
                <span class="badge bg-primary ms-2" id="services-count">{{ $topServices->count() ?? 0 }}</span>
            </button>
        </li>
        <li class="nav-item">
            <button class="nav-link d-flex align-items-center" id="providers-tab"
                data-bs-toggle="tab" data-bs-target="#providers" type="button" role="tab">
                👩‍🏫 {{ __('dashboard.top_providers') }}
                <span class="badge bg-purple ms-2" id="providers-count">{{ $topProviders->count() ?? 0 }}</span>
            </button>
        </li>
    </ul>

    <!-- Tab Content -->
    <div class="tab-content mt-3 border border-top-0 rounded-bottom shadow-sm bg-white p-3"
         style="min-height: 350px; position: relative;">

        <!-- Overlay -->
        <div id="search-overlay"
             class="d-none position-absolute top-0 start-0 w-100 h-100 bg-white bg-opacity-75
                    d-flex justify-content-center align-items-center"
             style="z-index: 1050;">
            <div class="spinner-border text-primary me-2" role="status"></div>
            <span class="fw-semibold text-muted">{{ __('dashboard.searching') }}</span>
        </div>

        <!-- Activities Tab -->
        <div class="tab-pane fade show active" id="activities" role="tabpanel">
            @include('partials.top-bonding-activities', ['activities' => $topBondingActivities])
        </div>

        <!-- Services Tab -->
        <div class="tab-pane fade" id="services" role="tabpanel">
            @include('partials.top-services', ['services' => $topServices])
        </div>

        <!-- Providers Tab -->
        <div class="tab-pane fade" id="providers" role="tabpanel">
            @include('partials.top-providers', ['providers' => $topProviders])
        </div>
    </div>
</section>

@push('scripts')
<script>
document.addEventListener("DOMContentLoaded", function () {
    const overlay = document.getElementById("search-overlay");
    const counts = {
        activities: document.getElementById("activities-count"),
        services: document.getElementById("services-count"),
        providers: document.getElementById("providers-count")
    };

    function intl(n) { try { return new Intl.NumberFormat(navigator.language).format(n); } catch(e){ return n; } }
    function showOverlay(){ overlay?.classList.remove("d-none"); }
    function hideOverlay(){ overlay?.classList.add("d-none"); }

    // Initial counts from server
    fetch("{{ route('dashboard.stats') }}")
        .then(r => r.json())
        .then(d => {
            counts.activities.textContent = intl(d.activities ?? 0);
            counts.services.textContent   = intl(d.services ?? 0);
            counts.providers.textContent  = intl(d.providers ?? 0);
        });

    // Real-time refresh via Laravel Echo
    if (window.Echo) {
        window.Echo.channel('dashboard.global')
            .listen('.DashboardUpdated', (e) => {
                if (e.counts) {
                    counts.activities.textContent = intl(e.counts.activities ?? 0);
                    counts.services.textContent   = intl(e.counts.services ?? 0);
                    counts.providers.textContent  = intl(e.counts.providers ?? 0);
                }
                if (e.activitiesHtml) document.querySelector('#activities').innerHTML = e.activitiesHtml;
                if (e.servicesHtml)   document.querySelector('#services').innerHTML   = e.servicesHtml;
                if (e.providersHtml)  document.querySelector('#providers').innerHTML  = e.providersHtml;
            });
    }

    // Dashboard search
    const searchInput = document.getElementById('dashboard-search');
    const searchBtn   = document.getElementById('search-btn');
    let debounce;

    function performSearch(q) {
        if (!q.trim()) return;
        showOverlay();
        fetch(`/dashboard/search?q=${encodeURIComponent(q)}`)
            .then(res => res.json())
            .then(data => {
                document.querySelector("#activities").innerHTML = data.activities ?? '';
                document.querySelector("#services").innerHTML   = data.services ?? '';
                document.querySelector("#providers").innerHTML  = data.providers ?? '';

                counts.activities.textContent = document.querySelectorAll("#activities .card, #activities > div").length;
                counts.services.textContent   = document.querySelectorAll("#services .card, #services > div").length;
                counts.providers.textContent  = document.querySelectorAll("#providers .card, #providers > div").length;
            })
            .finally(hideOverlay);
    }

    if (searchInput && searchBtn) {
        searchBtn.addEventListener('click', () => performSearch(searchInput.value));
        searchInput.addEventListener('keyup', function(){
            clearTimeout(debounce);
            debounce = setTimeout(() => performSearch(this.value), 500);
        });
    }
});
</script>
@endpush

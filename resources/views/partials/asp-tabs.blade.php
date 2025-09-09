@push('scripts')
<script>
document.addEventListener("DOMContentLoaded", function () {
    const overlay = document.getElementById("search-overlay");

    // Infinite scroll setup
    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                let activeTab = document.querySelector(".tab-pane.active");
                let tabId = activeTab.id;
                loadMore(tabId);
            }
        });
    }, { threshold: 1.0 });

    function loadMore(tab) {
        let container = document.getElementById(tab);
        let nextPage = container.getAttribute("data-next-page");
        if (!nextPage) return;

        overlay.classList.remove("d-none");
        fetch(`/dashboard/tab-content/${tab}?page=${nextPage}`)
            .then(res => res.text())
            .then(html => {
                container.insertAdjacentHTML("beforeend", html);
                overlay.classList.add("d-none");
                let newPage = parseInt(nextPage) + 1;
                container.setAttribute("data-next-page", newPage);
            })
            .catch(() => overlay.classList.add("d-none"));
    }

    // Attach observer to each tab
    ["activities", "services", "providers"].forEach(tab => {
        let container = document.getElementById(tab);
        container.setAttribute("data-next-page", 2);

        let sentinel = document.createElement("div");
        sentinel.classList.add("sentinel");
        container.appendChild(sentinel);
        observer.observe(sentinel);
    });

    // Update badge counts dynamically (optional, every 15s)
    setInterval(() => {
        fetch("{{ route('dashboard.tab-content', 'activities') }}?count=1")
            .then(r => r.text()).then(() => {
                document.getElementById("activities-count").innerText = "{{ $topBondingActivities->count() }}";
                document.getElementById("services-count").innerText = "{{ $topServices->count() }}";
                document.getElementById("providers-count").innerText = "{{ $topProviders->count() }}";
            });
    }, 15000);
});
</script>

@push('scripts')
<script>
document.addEventListener("DOMContentLoaded", function () {
    const searchInput = document.getElementById("dashboard-search");
    const searchBtn = document.getElementById("search-btn");
    const overlay = document.getElementById("search-overlay");

    function performSearch(query) {
        overlay.classList.remove("d-none");

        fetch(`/dashboard/search?q=${encodeURIComponent(query)}`)
            .then(res => res.json())
            .then(data => {
                document.querySelector("#activities").innerHTML = data.activities;
                document.querySelector("#services").innerHTML = data.services;
                document.querySelector("#providers").innerHTML = data.providers;

                // Update badge counts
                document.getElementById("activities-count").innerText =
                    document.querySelectorAll("#activities .card").length;
                document.getElementById("services-count").innerText =
                    document.querySelectorAll("#services .card").length;
                document.getElementById("providers-count").innerText =
                    document.querySelectorAll("#providers .card").length;

                overlay.classList.add("d-none");
            })
            .catch(() => overlay.classList.add("d-none"));
    }

    // Search on button click
    searchBtn.addEventListener("click", () => {
        performSearch(searchInput.value);
    });

    // Search as you type (debounced)
    let debounceTimeout;
    searchInput.addEventListener("keyup", function () {
        clearTimeout(debounceTimeout);
        debounceTimeout = setTimeout(() => {
            performSearch(this.value);
        }, 500);
    });
});
</script>
@endpush

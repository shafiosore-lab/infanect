<div class="col-lg-4 col-md-6 mb-4">
    <div class="card shadow-sm border-0 h-100">
        <!-- Colored header -->
        <div class="card-header text-white fw-bold bg-{{ $color ?? 'primary' }}">
            {{ $title ?? 'Stat Card' }}
        </div>

        <!-- Body -->
        <div class="card-body">
            <!-- Description -->
            @isset($description)
                <p class="text-muted small mb-3">
                    {{ $description }}
                </p>
            @endisset

            <!-- Value Highlight -->
            @isset($value)
                <h4 class="fw-bold text-dark mb-3">{{ $value }}</h4>
            @endisset

            <!-- Chart -->
            <canvas id="{{ $chartId ?? 'chart-default' }}" class="w-100" height="120"></canvas>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener("DOMContentLoaded", function () {
    const ctx = document.getElementById("{{ $chartId ?? 'chart-default' }}");
    if (ctx) {
        new Chart(ctx, {
            type: "{{ $chartType ?? 'bar' }}",
            data: {
                labels: {!! json_encode($labels ?? ['A','B','C','D']) !!},
                datasets: [{
                    label: "{{ $datasetLabel ?? 'Data' }}",
                    data: {!! json_encode($data ?? [10, 20, 30, 40]) !!},
                    borderColor: "{{ $borderColor ?? '#0d6efd' }}",
                    backgroundColor: "{{ $backgroundColor ?? 'rgba(13,110,253,0.2)' }}",
                    fill: {{ $fill ?? 'true' }},
                    tension: 0.4
                }]
            },
            options: {
                responsive: true,
                plugins: { legend: { display: false } },
                scales: {
                    y: { beginAtZero: true, max: {{ $max ?? 100 }} }
                }
            }
        });
    }
});
</script>
@endpush

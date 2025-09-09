<div class="bg-white rounded-xl p-6 shadow-md">
    <h3 class="text-lg font-semibold text-gray-800">Wellness Score</h3>

    <!-- Doughnut Chart -->
    <canvas id="wellnessChart" class="mt-4 h-40"></canvas>

    <!-- Optional: Legend or summary -->
    <div class="mt-4 flex flex-wrap gap-4 text-sm text-gray-600">
        <span><span class="font-semibold text-green-600">●</span> Mental Health</span>
        <span><span class="font-semibold text-blue-600">●</span> Physical Health</span>
        <span><span class="font-semibold text-orange-500">●</span> Social Wellness</span>
        <span><span class="font-semibold text-purple-600">●</span> Learning Growth</span>
    </div>
</div>

<script>
document.addEventListener("DOMContentLoaded", function() {
    fetch("{{ route('dashboard.wellness') }}")
        .then(res => res.json())
        .then(data => {
            const ctx = document.getElementById('wellnessChart').getContext('2d');
            new Chart(ctx, {
                type: 'doughnut',
                data: {
                    labels: Object.keys(data),
                    datasets: [{
                        data: Object.values(data),
                        backgroundColor: ['#22c55e', '#3b82f6', '#f97316', '#a855f7'],
                        borderColor: '#fff',
                        borderWidth: 2
                    }]
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: { position: 'bottom' },
                        tooltip: { enabled: true }
                    }
                }
            });
        })
        .catch(err => console.error('Error loading wellness data:', err));
});
</script>

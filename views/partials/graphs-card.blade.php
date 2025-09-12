<div class="dashboard-card p-6 bg-white rounded-2xl shadow-md">
    <h3 class="text-lg font-semibold mb-4">Analytics Overview</h3>

    <!-- Doughnut Chart -->
    <canvas id="activityDoughnut" class="mb-6"></canvas>

    <!-- Line Chart -->
    <canvas id="engagementLine" class="mb-6"></canvas>

    <!-- Bar Chart -->
    <canvas id="bookingBar"></canvas>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
   @push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const wellnessData = @json($chartData);

    new Chart(document.getElementById('activityDoughnut'), {
        type: 'doughnut',
        data: {
            labels: ['Mental Health', 'Physical Health', 'Social Wellness', 'Learning Growth'],
            datasets: [{
                data: [
                    wellnessData.mental,
                    wellnessData.physical,
                    wellnessData.social,
                    wellnessData.learning
                ],
                backgroundColor: ['#16a34a','#22d3ee','#f59e0b','#7e22ce'],
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: { position: 'bottom', labels: { boxWidth: 12, padding: 15 } }
            },
            cutout: '60%'
        }
    });
</script>


    // Line Chart
    new Chart(document.getElementById('engagementLine'), {
        type: 'line',
        data: {
            labels: ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'],
            datasets: [{
                label: 'User Engagement',
                data: [12, 19, 14, 23, 17, 20, 25],
                borderColor: '#16a34a',
                backgroundColor: 'rgba(22,163,74,0.2)',
                fill: true,
                tension: 0.3
            }]
        },
        options: { responsive: true }
    });

    // Bar Chart
    new Chart(document.getElementById('bookingBar'), {
        type: 'bar',
        data: {
            labels: ['Week 1','Week 2','Week 3','Week 4'],
            datasets: [{
                label: 'Bookings',
                data: [5, 12, 9, 15],
                backgroundColor: '#22d3ee'
            }]
        },
        options: { responsive: true, plugins: { legend: { display: false } } }
    });
</script>
@endpush

@extends('layouts.provider-bonding')

@section('page-title', 'Family Bonding Dashboard')
@section('page-description', 'Strengthen family connections through meaningful activities')

@section('content')
<div class="min-h-screen bg-gray-50 space-y-10 p-6">
    {{-- Welcome Hero --}}
    <div class="bg-gradient-to-r from-green-500 via-teal-500 to-blue-600 rounded-3xl shadow-2xl overflow-hidden">
        <div class="p-8 md:p-12">
            <div class="flex items-center">
                <div class="w-16 h-16 bg-white bg-opacity-20 rounded-full flex items-center justify-center mr-5">
                    <i class="fas fa-heart text-3xl text-white"></i>
                </div>
                <div>
                    <h2 class="text-3xl md:text-4xl font-bold text-white">
                        Welcome Back, {{ Auth::user()->name }} 👋
                    </h2>
                    <p class="text-green-100 mt-2 text-lg">
                        Empowering {{ $metrics['activeFamilies'] ?? 89 }} families this month
                    </p>
                </div>
            </div>

            {{-- Top Stats --}}
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mt-8">
                <div class="bg-white bg-opacity-20 rounded-2xl p-6 hover:bg-opacity-30 transition-all duration-300">
                    <div class="flex items-center">
                        <i class="fas fa-calendar-check text-2xl text-white mr-4"></i>
                        <div>
                            <p class="text-white text-2xl font-bold">{{ $metrics['weeklyActivities'] ?? 32 }}</p>
                            <p class="text-green-100 text-sm">Weekly Activities</p>
                        </div>
                    </div>
                </div>

                <div class="bg-white bg-opacity-20 rounded-2xl p-6 hover:bg-opacity-30 transition-all duration-300">
                    <div class="flex items-center">
                        <i class="fas fa-star text-2xl text-yellow-300 mr-4"></i>
                        <div>
                            <p class="text-white text-2xl font-bold">{{ $metrics['satisfactionRate'] ?? '96%' }}</p>
                            <p class="text-green-100 text-sm">Satisfaction</p>
                        </div>
                    </div>
                </div>

                <div class="bg-white bg-opacity-20 rounded-2xl p-6 hover:bg-opacity-30 transition-all duration-300">
                    <div class="flex items-center">
                        <i class="fas fa-chart-line text-2xl text-white mr-4"></i>
                        <div>
                            <p class="text-white text-2xl font-bold">KSh {{ number_format($metrics['totalRevenue'] ?? 154200, 0) }}K</p>
                            <p class="text-green-100 text-sm">Revenue</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- KPI Section --}}
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        @include('partials.dashboard.cards.active-families', ['metrics' => $metrics])
        @include('partials.dashboard.cards.weekly-activities', ['metrics' => $metrics])
        @include('partials.dashboard.cards.satisfaction', ['metrics' => $metrics])
        @include('partials.dashboard.cards.revenue', ['metrics' => $metrics])
    </div>

    {{-- Analytics & Quick Actions --}}
    <div class="grid grid-cols-1 xl:grid-cols-4 gap-8">
        <div class="xl:col-span-3">
            @include('partials.dashboard.charts.engagement-trends')
        </div>
        <div class="space-y-6">
            @include('partials.dashboard.quick-actions')
            @include('partials.dashboard.charts.activity-types')
        </div>
    </div>

    {{-- Performance Section --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        @include('partials.dashboard.charts.feedback')
        @include('partials.dashboard.charts.revenue-breakdown')
    </div>

    {{-- Recent Activities --}}
    @include('partials.dashboard.recent-activities')
</div>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    function renderChart(id, type, labels, data, datasetOptions = {}, extraOptions = {}) {
        const ctx = document.getElementById(id);
        if (!ctx) return;

        new Chart(ctx, {
            type: type,
            data: {
                labels: labels,
                datasets: [{
                    data: data,
                    ...datasetOptions
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: {
                            usePointStyle: true,
                            padding: 20,
                            font: { size: 12, weight: '500' }
                        }
                    }
                },
                ...extraOptions
            }
        });
    }

    const chartData = @json($chartData ?? []);

    // Family Engagement Chart
    renderChart('engagementTrendsChart', 'line', 
        chartData.familyEngagement?.labels || ['Jan','Feb','Mar','Apr','May','Jun'], 
        chartData.familyEngagement?.data || [45,52,38,67,74,89], 
        {
            label: 'Family Participation',
            fill: true,
            backgroundColor: 'rgba(59,130,246,0.1)',
            borderColor: '#3B82F6',
            borderWidth: 3,
            tension: 0.4,
            pointBackgroundColor: '#3B82F6',
            pointBorderColor: '#fff',
            pointRadius: 5
        },
        {
            scales: {
                y: { beginAtZero: true, title: { display: true, text: 'Number of Families' } }
            }
        }
    );

    // Activity Types Chart
    renderChart('activityTypesChart', 'doughnut',
        chartData.activityTypes?.labels || ['Outdoor','Cooking','Arts','Sports','Education'],
        chartData.activityTypes?.data || [35,25,20,15,5],
        { backgroundColor: ['#3B82F6','#10B981','#F59E0B','#EF4444','#8B5CF6'], borderWidth: 0 }
    );

    // Feedback Chart
    renderChart('feedbackChart', 'bar',
        chartData.feedbackScores?.labels || ['5★','4★','3★','2★','1★'],
        chartData.feedbackScores?.data || [78,15,4,2,1],
        { backgroundColor: ['#28a745','#17a2b8','#ffc107','#fd7e14','#dc3545'], borderRadius: 6 },
        { plugins:{legend:{display:false}}, scales:{y:{beginAtZero:true, title:{display:true, text:'Reviews'}}} }
    );

    // Revenue Breakdown Chart
    renderChart('revenueBreakdownChart', 'bar',
        chartData.revenueBreakdown?.labels || ['Workshops','Adventures','Creative','Sports','Tours'],
        chartData.revenueBreakdown?.data || [45000,38000,32000,28000,11200],
        { backgroundColor: ['#667EEA','#F093FB','#4FACFE','#FBCF61','#43E97B'], borderRadius: 4 },
        { indexAxis:'y', plugins:{legend:{display:false}}, scales:{x:{beginAtZero:true, title:{display:true, text:'Revenue (KSh)'}}} }
    );
});

// Quick Actions
function createNewActivity() { 
    window.location.href = "{{ route('activities.create') }}"; 
}

function sendFamilyUpdate() { 
    openBulkSMS(); 
}

function viewBookingRequests() { 
    window.location.href = "{{ route('bookings.index') }}"; 
}
</script>
@endsection
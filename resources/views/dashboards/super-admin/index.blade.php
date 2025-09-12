@extends('layouts.super-admin')

@section('content')
<div class="container mx-auto p-6">
    <h1 class="text-2xl font-bold mb-4">Super Admin Dashboard</h1>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <div class="p-4 bg-white rounded shadow">
            <div class="text-sm text-gray-500">Users</div>
            <div class="text-2xl font-bold">{{ $stats['users'] ?? 0 }}</div>
        </div>
        <div class="p-4 bg-white rounded shadow">
            <div class="text-sm text-gray-500">Providers</div>
            <div class="text-2xl font-bold">{{ $stats['providers'] ?? 0 }}</div>
        </div>
        <div class="p-4 bg-white rounded shadow">
            <div class="text-sm text-gray-500">Pending Documents</div>
            <div class="text-2xl font-bold">{{ $stats['pending_documents'] ?? 0 }}</div>
        </div>
    </div>

    <div class="mt-6">
        <a href="{{ route('admin.documents.index') }}" class="btn btn-primary">Manage Documents</a>
        <a href="{{ route('user.bookings.index') }}" class="btn btn-secondary">Manage Bookings</a>
    </div>

    <canvas id="statsChart" height="100"></canvas>

    <script>
    async function loadStats(){
        const resp = await fetch('{{ route('dashboard.stats.super') }}');
        const data = await resp.json();
        const labels = ['Users','Providers','Bookings','Revenue'];
        const values = [data.users, data.providers, data.bookings, data.revenue];
        const ctx = document.getElementById('statsChart').getContext('2d');
        if (window.statsChart) { window.statsChart.data.datasets[0].data = values; window.statsChart.update(); return; }
        window.statsChart = new Chart(ctx, { type: 'bar', data: { labels, datasets: [{ label: 'Platform Stats', data: values, backgroundColor: ['#6366f1','#10b981','#f59e0b','#ef4444'] }] }, options: {} });
    }
    loadStats();
    setInterval(loadStats, 10000);

    // Realtime listener for document uploads
    @if(env('PUSHER_APP_KEY'))
    document.addEventListener('DOMContentLoaded', function(){
        try {
            window.Echo.channel('documents').listen('DocumentUploaded', (e) => {
                loadStats();
                alert('New document uploaded: '+e.name);
            });
        } catch(e) { console.warn('Echo listen failed', e); }
    });
    @endif
    </script>

</div>
@endsection

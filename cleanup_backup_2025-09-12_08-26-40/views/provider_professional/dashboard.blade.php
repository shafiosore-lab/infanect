@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto p-6">
    <h1 class="text-2xl font-semibold">Professional Provider Dashboard</h1>
    <p class="text-sm text-gray-500">Tools for professional providers to manage clients and services.</p>

    <div class="grid grid-cols-2 gap-4 mt-4">
        <x-card>
            <p class="text-sm text-gray-500">Active Clients</p>
            <p class="text-xl font-bold">{{ \App\Models\Client::where('provider_id', auth()->id())->count() ?? 0 }}</p>
        </x-card>
        <x-card>
            <p class="text-sm text-gray-500">Open Sessions</p>
            <p class="text-xl font-bold">{{ \App\Models\Booking::where('provider_id', auth()->id())->where('status','pending')->count() ?? 0 }}</p>
        </x-card>
    </div>

    <div class="mt-6 bg-white p-4 rounded shadow">
        <h2 class="font-semibold mb-2">Bookings & Revenue (7 days)</h2>
        <canvas id="providerLineChart" height="120"></canvas>
    </div>

    <div class="mt-4 grid grid-cols-2 gap-4">
        <div>
            <a href="{{ route('provider.services.index') }}"><x-button.primary>Manage Services</x-button.primary></a>
            <a href="{{ route('provider.bookings.index') }}" class="ml-2"><x-button.primary>View Bookings</x-button.primary></a>
        </div>
        <div class="text-right">
            <a href="{{ route('provider.notifications') }}" class="text-sm text-indigo-600 hover:underline">My Notifications</a>
            <span class="mx-2">|</span>
            <a href="{{ route('admin.engagement.insights') }}" class="text-sm text-indigo-600 hover:underline">Engagement Insights</a>
        </div>
    </div>

    {{-- Suggested for nearby families (AJAX loaded) --}}
    <div class="mt-6 bg-white p-4 rounded shadow">
        <h2 class="font-semibold mb-2">Suggested for Nearby Families</h2>
        <div id="suggestionsContainer">Loading suggestions...</div>
    </div>

</div>

<script>
async function loadProviderMetrics(){
    const res = await fetch('{{ route('provider.metrics') }}', { credentials: 'same-origin' });
    const data = await res.json();
    const ctx = document.getElementById('providerLineChart').getContext('2d');
    new Chart(ctx, {
        type: 'line',
        data: {
            labels: data.labels,
            datasets: [
                { label: 'Bookings', data: data.bookings, borderColor: '#3B82F6', backgroundColor: 'rgba(59,130,246,0.08)', tension: 0.3 },
                { label: 'Revenue', data: data.revenue, borderColor: '#10B981', backgroundColor: 'rgba(16,185,129,0.08)', tension: 0.3 }
            ]
        }
    });
}
loadProviderMetrics();

async function loadSuggestions() {
    const res = await fetch('{{ route('api.provider.suggestions') }}', { credentials: 'same-origin' });
    if (!res.ok) { document.getElementById('suggestionsContainer').innerText = 'Unable to load suggestions.'; return; }
    const data = await res.json();
    const items = data.data || data;
    if (!items || items.length === 0) { document.getElementById('suggestionsContainer').innerText = 'No recent suggestions including your services.'; return; }

    const html = items.map(s => {
        const act = (s.payload && s.payload.activities && s.payload.activities[0]) || null;
        const title = act ? (act.title || act.name) : 'Activity';
        const id = act ? act.id : null;
        const activityLink = id ? ('/activities/' + id) : ('/services/' + id);
        return `<div class="p-3 border rounded mb-2 flex justify-between items-center"><div><div class="font-semibold">${title}</div><div class="text-sm text-gray-500">Suggested ${new Date(s.created_at).toLocaleString()}</div></div><div><a href="${activityLink}" class="text-indigo-600 hover:underline">View</a></div></div>`;
    }).join('');

    document.getElementById('suggestionsContainer').innerHTML = html;
}

loadSuggestions();
</script>
@endsection

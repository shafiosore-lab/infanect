@extends('layouts.app')

@section('content')
<div class="p-6">
    <h1 class="text-2xl font-bold mb-6">📊 Subscriptions Dashboard</h1>

    {{-- ✅ Summary Cards --}}
    <div class="grid grid-cols-1 md:grid-cols-5 gap-6 mb-8">
        <div class="p-4 bg-white shadow rounded">
            <h3 class="font-semibold">Total</h3>
            <p class="text-xl text-blue-600">{{ number_format($totalSubscriptions) }}</p>
        </div>
        <div class="p-4 bg-white shadow rounded">
            <h3 class="font-semibold">Active</h3>
            <p class="text-xl text-green-600">{{ number_format($activeSubscriptions) }}</p>
        </div>
        <div class="p-4 bg-white shadow rounded">
            <h3 class="font-semibold">Expired</h3>
            <p class="text-xl text-red-500">{{ number_format($expiredSubscriptions) }}</p>
        </div>
        <div class="p-4 bg-white shadow rounded">
            <h3 class="font-semibold">Trial</h3>
            <p class="text-xl text-yellow-600">{{ number_format($trialSubscriptions) }}</p>
        </div>
        <div class="p-4 bg-white shadow rounded">
            <h3 class="font-semibold">Expiring Soon</h3>
            <p class="text-xl text-orange-600">{{ number_format($expiringSoon) }}</p>
        </div>
    </div>

    {{-- 📈 Growth Chart --}}
    <div class="bg-white shadow rounded p-6 mb-8">
        <h2 class="text-lg font-bold mb-4">Subscriptions Growth (Last 12 Months)</h2>
        <canvas id="subscriptionsChart" height="120"></canvas>
    </div>

    {{-- 📋 Subscriptions Table --}}
    <div class="bg-white shadow rounded p-6">
        <h2 class="text-lg font-bold mb-4">All Subscriptions</h2>

        {{-- Filters --}}
        <form method="GET" class="flex flex-wrap gap-4 mb-4">
            <select name="status" class="border rounded p-2">
                <option value="">All Status</option>
                <option value="active" {{ request('status')=='active' ? 'selected' : '' }}>Active</option>
                <option value="expired" {{ request('status')=='expired' ? 'selected' : '' }}>Expired</option>
                <option value="trial" {{ request('status')=='trial' ? 'selected' : '' }}>Trial</option>
            </select>
            <input type="text" name="country" value="{{ request('country') }}" placeholder="Country"
                   class="border rounded p-2">
            <select name="platform" class="border rounded p-2">
                <option value="">All Platforms</option>
                <option value="web" {{ request('platform')=='web' ? 'selected' : '' }}>Web</option>
                <option value="mobile" {{ request('platform')=='mobile' ? 'selected' : '' }}>Mobile</option>
            </select>
            <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded">Filter</button>
        </form>

        {{-- Table --}}
        <div class="overflow-x-auto">
            <table class="w-full border-collapse border">
                <thead>
                    <tr class="bg-gray-100 text-left">
                        <th class="border p-2">#</th>
                        <th class="border p-2">User</th>
                        <th class="border p-2">Plan</th>
                        <th class="border p-2">Amount</th>
                        <th class="border p-2">Status</th>
                        <th class="border p-2">Platform</th>
                        <th class="border p-2">Country</th>
                        <th class="border p-2">Starts</th>
                        <th class="border p-2">Expires</th>
                        <th class="border p-2">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($subscriptions as $subscription)
                        <tr>
                            <td class="border p-2">{{ $subscription->id }}</td>
                            <td class="border p-2">{{ optional($subscription->user)->name ?? 'N/A' }}</td>
                            <td class="border p-2">{{ $subscription->plan }}</td>
                            <td class="border p-2">{{ number_format($subscription->amount, 2) }} {{ $subscription->currency }}</td>
                            <td class="border p-2">
                                <x-status-badge :status="$subscription->status" />
                            </td>
                            <td class="border p-2">{{ $subscription->platform ?? '-' }}</td>
                            <td class="border p-2">{{ $subscription->country ?? '-' }}</td>
                            <td class="border p-2">{{ optional($subscription->starts_at)->format('d M Y') }}</td>
                            <td class="border p-2">{{ optional($subscription->expires_at)->format('d M Y') }}</td>
                            <td class="border p-2">
                                <div class="flex gap-2">
                                    <a href="{{ route('admin.subscriptions.show', $subscription->id) }}" class="text-blue-600">View</a>
                                    <a href="{{ route('admin.subscriptions.edit', $subscription->id) }}" class="text-yellow-600">Edit</a>
                                    <form method="POST" action="{{ route('admin.subscriptions.destroy', $subscription->id) }}" onsubmit="return confirm('Delete this subscription?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600">Delete</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="10" class="text-center p-4 text-gray-500">No subscriptions found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-4">
            {{ $subscriptions->links() }}
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const ctx = document.getElementById('subscriptionsChart').getContext('2d');
    new Chart(ctx, {
        type: 'line',
        data: {
            labels: @json($monthlyLabels),
            datasets: [{
                label: 'Subscriptions',
                data: @json($monthlyValues),
                borderColor: '#3b82f6',
                backgroundColor: 'rgba(59, 130, 246, 0.2)',
                fill: true,
                tension: 0.3
            }]
        }
    });
</script>
@endpush

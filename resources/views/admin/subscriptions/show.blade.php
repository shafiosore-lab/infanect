@extends('layouts.app')

@section('content')
<div class="p-6">
    <h1 class="text-2xl font-bold mb-6">📄 Subscription Details</h1>

    {{-- 🔙 Back --}}
    <a href="{{ route('admin.subscriptions.index') }}" class="text-blue-600 mb-4 inline-block">&larr; Back to Subscriptions</a>

    {{-- Main Details Card --}}
    <div class="bg-white shadow rounded p-6 mb-6">
        <h2 class="text-xl font-semibold mb-4">Subscription #{{ $subscription->id }}</h2>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <p><strong>User:</strong> {{ optional($subscription->user)->name ?? 'N/A' }} ({{ optional($subscription->user)->email ?? '' }})</p>
                <p><strong>Plan:</strong> {{ $subscription->plan }}</p>
                <p><strong>Status:</strong> <x-status-badge :status="$subscription->status" /></p>
                <p><strong>Amount:</strong> {{ number_format($subscription->amount, 2) }} {{ $subscription->currency }}</p>
                <p><strong>Payment Method:</strong> {{ $subscription->payment_method ?? 'N/A' }}</p>
                <p><strong>Payment Ref:</strong> {{ $subscription->payment_reference ?? '-' }}</p>
            </div>

            <div>
                <p><strong>Country:</strong> {{ $subscription->country ?? 'N/A' }}</p>
                <p><strong>Platform:</strong> {{ $subscription->platform ?? 'N/A' }}</p>
                <p><strong>Starts At:</strong> {{ optional($subscription->starts_at)->format('d M Y') }}</p>
                <p><strong>Expires At:</strong> {{ optional($subscription->expires_at)->format('d M Y') }}</p>

                {{-- 🔔 Expiry Alerts --}}
                @if($subscription->expires_at)
                    @if($subscription->expires_at->isPast())
                        <p class="text-red-600 font-semibold mt-2">⚠ Subscription Expired</p>
                    @elseif($subscription->expires_at->diffInDays(now()) <= 7)
                        <p class="text-orange-600 font-semibold mt-2">⚠ Expiring Soon ({{ $subscription->expires_at->diffInDays(now()) }} days)</p>
                    @else
                        <p class="text-green-600 font-semibold mt-2">✔ Active until {{ $subscription->expires_at->format('d M Y') }}</p>
                    @endif
                @endif
            </div>
        </div>
    </div>

    {{-- 📊 Analytics --}}
    <div class="bg-white shadow rounded p-6 mb-6">
        <h2 class="text-xl font-semibold mb-4">Analytics</h2>
        <ul class="list-disc list-inside text-gray-700">
            <li><strong>Total Subscriptions by this User:</strong> {{ optional($subscription->user)->subscriptions()->count() ?? 0 }}</li>
            <li><strong>Active Subscriptions:</strong> {{ optional($subscription->user)->subscriptions()->where('status','active')->count() ?? 0 }}</li>
            <li><strong>Expired Subscriptions:</strong> {{ optional($subscription->user)->subscriptions()->where('status','expired')->count() ?? 0 }}</li>
        </ul>
    </div>

    {{-- 🛠 Actions --}}
    <div class="flex gap-4">
        <a href="{{ route('admin.subscriptions.edit', $subscription->id) }}"
           class="bg-yellow-500 text-white px-4 py-2 rounded">✏ Edit</a>

        <form action="{{ route('admin.subscriptions.destroy', $subscription->id) }}" method="POST"
              onsubmit="return confirm('Are you sure you want to delete this subscription?')">
            @csrf
            @method('DELETE')
            <button type="submit" class="bg-red-600 text-white px-4 py-2 rounded">🗑 Delete</button>
        </form>
    </div>
</div>
@endsection

@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto p-6">
    <h1 class="text-2xl font-semibold">Bonding Provider Dashboard</h1>
    <p class="text-sm text-gray-500">Manage bonding sessions and compliance.</p>

    <div class="grid grid-cols-3 gap-4 mt-4">
        <x-card>
            <p class="text-sm text-gray-500">Scheduled Bonding Sessions</p>
            <p class="text-xl font-bold">{{ \App\Models\Booking::where('provider_id', auth()->id())->where('type','bonding')->count() ?? 0 }}</p>
        </x-card>
        <x-card>
            <p class="text-sm text-gray-500">Compliance Docs</p>
            <p class="text-xl font-bold">{{ \App\Models\ProviderDocument::where('provider_id', auth()->id())->count() ?? 0 }}</p>
        </x-card>
        <x-card>
            <p class="text-sm text-gray-500">Pending Approvals</p>
            <p class="text-xl font-bold">{{ \App\Models\Approval::where('provider_id', auth()->id())->where('status','pending')->count() ?? 0 }}</p>
        </x-card>
    </div>

    <div class="mt-6 p-4 bg-white rounded shadow">
        <h2 class="font-semibold mb-2">Bonding Activity (7 days)</h2>
        <canvas id="bondingChart" height="120"></canvas>
    </div>

    <div class="mt-4">
        <a href="{{ route('provider.activities.index') }}"><x-button.primary>Manage Activities</x-button.primary></a>
    </div>
</div>

<script>
// Placeholder metric load if needed
</script>
@endsection

@extends('layouts.app')

@section('content')
<div class="p-6">
    <h1 class="text-2xl font-bold mb-4">Client Insights</h1>

    <ul class="grid grid-cols-3 gap-6">
        <li class="bg-white shadow rounded p-4">Total Clients: {{ $stats['total_clients'] }}</li>
        <li class="bg-blue-100 shadow rounded p-4">Total Bookings: {{ $stats['total_bookings'] }}</li>
        <li class="bg-green-100 shadow rounded p-4">Active Clients: {{ $stats['active_clients'] }}</li>
    </ul>
</div>
@endsection

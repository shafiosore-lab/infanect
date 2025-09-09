@extends('layouts.app')

@section('content')
<div class="p-6">
    <h1 class="text-2xl font-bold mb-4">Service Insights</h1>

    <ul class="grid grid-cols-3 gap-6">
        <li class="bg-white shadow rounded p-4">Total Services: {{ $stats['total_services'] }}</li>
        <li class="bg-green-100 shadow rounded p-4">Active Services: {{ $stats['active_services'] }}</li>
        <li class="bg-red-100 shadow rounded p-4">Inactive Services: {{ $stats['inactive_services'] }}</li>
    </ul>
</div>
@endsection

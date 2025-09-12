{{-- resources/views/admin/service/insights.blade.php --}}
@extends('layouts.app')

@section('content')
<div class="p-6 space-y-6">

    <h1 class="text-2xl font-bold text-gray-800">Service Insights</h1>

    {{-- Metrics --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="p-4 bg-white rounded shadow">
            <h3 class="text-sm text-gray-500">Total Services</h3>
            <p class="text-2xl font-bold">{{ $totalServices }}</p>
        </div>
        <div class="p-4 bg-white rounded shadow">
            <h3 class="text-sm text-gray-500">Active Services</h3>
            <p class="text-2xl font-bold">{{ $activeServices }}</p>
        </div>
        <div class="p-4 bg-white rounded shadow">
            <h3 class="text-sm text-gray-500">Services by Category</h3>
            <p class="text-2xl font-bold">{{ $categoriesCount }}</p>
        </div>
    </div>

    {{-- Chart --}}
    <div class="p-4 bg-white rounded shadow">
        <h3 class="text-lg font-semibold mb-4">Services Distribution by Category</h3>
        <canvas id="servicesChart" height="150"></canvas>
    </div>
</div>

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const ctx = document.getElementById('servicesChart').getContext('2d');
    const servicesChart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: @json($categoriesLabels),
            datasets: [{
                label: 'Number of Services',
                data: @json($servicesCount),
                backgroundColor: 'rgba(79, 70

@extends('layouts.app')

@section('content')
<div class="p-6">
    <h1 class="text-2xl font-bold mb-4">Financial Insights</h1>

    <ul class="grid grid-cols-3 gap-6">
        <li class="bg-white shadow rounded p-4">Total Revenue: ${{ number_format($stats['total_revenue'], 2) }}</li>
        <li class="bg-yellow-100 shadow rounded p-4">Pending Payouts: ${{ number_format($stats['pending_payouts'], 2) }}</li>
        <li class="bg-green-100 shadow rounded p-4">Completed Payouts: ${{ number_format($stats['completed_payouts'], 2) }}</li>
    </ul>
</div>
@endsection

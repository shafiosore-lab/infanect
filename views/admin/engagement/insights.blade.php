@extends('layouts.app')

@section('content')
<div class="container mx-auto p-6">
    <div class="mb-6">
        <h1 class="text-2xl font-bold">Engagement & Financial Insights</h1>
        <p class="text-sm text-gray-500">Overview of platform activity</p>
    </div>

    @php
        use Illuminate\Support\Facades\DB;
        use Illuminate\Support\Facades\Schema;

        $messagesCount = Schema::hasTable('messages') ? DB::table('messages')->count() : null;
        $notificationsCount = Schema::hasTable('notifications') ? DB::table('notifications')->count() : null;
        $providersCount = Schema::hasTable('providers') ? DB::table('providers')->count() : null;
        $usersCount = Schema::hasTable('users') ? DB::table('users')->count() : null;
        $transactionsTotal = Schema::hasTable('transactions') ? DB::table('transactions')->sum('amount') : null;
        $transactionsCount = Schema::hasTable('transactions') ? DB::table('transactions')->count() : null;
    @endphp

    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <div class="p-4 border rounded">
            <div class="text-sm text-gray-500">Messages</div>
            <div class="text-xl font-semibold">{{ $messagesCount !== null ? $messagesCount : 'N/A' }}</div>
        </div>

        <div class="p-4 border rounded">
            <div class="text-sm text-gray-500">Notifications</div>
            <div class="text-xl font-semibold">{{ $notificationsCount !== null ? $notificationsCount : 'N/A' }}</div>
        </div>

        <div class="p-4 border rounded">
            <div class="text-sm text-gray-500">Providers</div>
            <div class="text-xl font-semibold">{{ $providersCount !== null ? $providersCount : 'N/A' }}</div>
        </div>

        <div class="p-4 border rounded">
            <div class="text-sm text-gray-500">Users</div>
            <div class="text-xl font-semibold">{{ $usersCount !== null ? $usersCount : 'N/A' }}</div>
        </div>

        <div class="p-4 border rounded md:col-span-2">
            <div class="text-sm text-gray-500">Transactions</div>
            <div class="text-xl font-semibold">{{ $transactionsCount !== null ? $transactionsCount : 'N/A' }} transactions</div>
            <div class="text-sm text-gray-600">Total amount: {{ $transactionsTotal !== null ? number_format($transactionsTotal, 2) : 'N/A' }}</div>
        </div>
    </div>
</div>
@endsection

@extends('layouts.app')

@section('content')
<div class="flex">
    <aside class="w-64 bg-gray-100 p-4">
        <h3 class="font-semibold mb-3">Bonding Provider</h3>
        <ul>
            <li><a href="{{ route('activities.index') }}">My Activities</a></li>
            <li><a href="{{ route('user.bookings.index') }}">Bookings Overview</a></li>
            <li><a href="{{ route('user.bookings.index') }}">Financials</a></li>
        </ul>
    </aside>
    <main class="flex-1 p-6">
        @yield('content')
    </main>
</div>
@endsection

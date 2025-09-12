@extends('layouts.app')

@section('content')
<div class="flex">
    <aside class="w-64 bg-gray-100 p-4">
        <h3 class="font-semibold mb-3">Provider (Professional)</h3>
        <ul>
            <li><a href="{{ route('provider.services.index') }}">My Services</a></li>
            <li><a href="{{ route('ai.chat') }}">AI Documents</a></li>
            <li><a href="{{ route('user.bookings.index') }}">My Bookings</a></li>
        </ul>
    </aside>
    <main class="flex-1 p-6">
        @yield('content')
    </main>
</div>
@endsection

@extends('layouts.app')

@section('content')
<div class="flex">
    <aside class="w-64 bg-gray-100 p-4">
        <h3 class="font-semibold mb-3">Super Admin</h3>
        <ul>
            <li><a href="{{ route('dashboard.super-admin') }}">Overview</a></li>
            <li><a href="{{ route('admin.documents.index') }}">AI Documents</a></li>
            <li><a href="{{ route('admin.documents.index') }}">Approval Center</a></li>
            <li><a href="{{ route('user.bookings.index') }}">Bookings</a></li>
        </ul>
    </aside>
    <main class="flex-1 p-6">
        @yield('content')
    </main>
</div>
@endsection

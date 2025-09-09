{{-- resources/views/bookings/index.blade.php --}}
@extends('layouts.app')

@section('title', 'My Bookings')

@section('content')
<div class="container mx-auto p-6">

    <!-- Header -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900">My Bookings</h1>
        <p class="mt-2 text-gray-600">Manage your activity bookings and reservations</p>
    </div>

    <!-- Filters -->
    <div class="bg-white shadow rounded-lg p-6 mb-8">
        <form method="GET" action="{{ route('bookings.index') }}" class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div>
                <label for="status" class="block text-sm font-medium text-gray-700">Status</label>
                <select name="status" id="status" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                    <option value="">All Status</option>
                    @foreach(['pending','confirmed','completed','cancelled'] as $status)
                        <option value="{{ $status }}" {{ request('status') == $status ? 'selected' : '' }}>
                            {{ ucfirst($status) }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div>
                <label for="date_from" class="block text-sm font-medium text-gray-700">From Date</label>
                <input type="date" name="date_from" id="date_from" value="{{ request('date_from') }}"
                       class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
            </div>
            <div>
                <label for="date_to" class="block text-sm font-medium text-gray-700">To Date</label>
                <input type="date" name="date_to" id="date_to" value="{{ request('date_to') }}"
                       class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
            </div>
            <div class="flex items-end">
                <button type="submit" class="w-full bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500">
                    Filter
                </button>
            </div>
        </form>
    </div>

    <!-- Bookings List -->
    <div class="space-y-6">
        @forelse($bookings as $booking)
            <div class="bg-white shadow rounded-lg overflow-hidden">
                <div class="p-6">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-xl font-semibold text-gray-900">
                            {{ $booking->activity->title ?? ($booking->service->name ?? 'Booking') }}
                        </h3>
                        @php
                            $statusClasses = [
                                'pending' => 'bg-yellow-100 text-yellow-800',
                                'confirmed' => 'bg-green-100 text-green-800',
                                'completed' => 'bg-blue-100 text-blue-800',
                                'cancelled' => 'bg-red-100 text-red-800'
                            ];
                        @endphp
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $statusClasses[$booking->status] ?? 'bg-gray-100 text-gray-800' }}">
                            {{ ucfirst($booking->status) }}
                        </span>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
                        <div>
                            <p class="text-sm text-gray-600">Provider</p>
                            <p class="font-medium">{{ $booking->provider->name ?? 'N/A' }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600">Date & Time</p>
                            <p class="font-medium">{{ $booking->scheduled_at ? $booking->scheduled_at->format('M j, Y g:i A') : 'N/A' }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600">Amount</p>
                            <p class="font-medium">{{ $booking->currency_code ?? 'KES' }} {{ number_format($booking->amount, 2) }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600">Booked On</p>
                            <p class="font-medium">{{ $booking->created_at->format('M j, Y') }}</p>
                        </div>
                    </div>

                    @if($booking->notes)
                        <div class="mb-6">
                            <p class="text-sm text-gray-600">Notes</p>
                            <p class="text-sm bg-gray-50 p-3 rounded">{{ $booking->notes }}</p>
                        </div>
                    @endif

                    <div class="flex space-x-3">
                        <a href="{{ route('bookings.show', $booking) }}" class="bg-blue-600 text-white px-4 py-2 rounded-md text-sm hover:bg-blue-700 transition-colors">
                            View Details
                        </a>

                        @if($booking->status == 'confirmed' && $booking->scheduled_at && $booking->scheduled_at->isFuture())
                            <form method="POST" action="{{ route('bookings.cancel', $booking) }}" onsubmit="return confirm('Are you sure you want to cancel this booking?')" class="inline">
                                @csrf
                                <button type="submit" class="bg-red-600 text-white px-4 py-2 rounded-md text-sm hover:bg-red-700 transition-colors">
                                    Cancel Booking
                                </button>
                            </form>
                        @endif
                    </div>
                </div>
            </div>
        @empty
            <div class="bg-white shadow rounded-lg p-12">
                <div class="text-center">
                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                    </svg>
                    <h3 class="mt-2 text-sm font-medium text-gray-900">No bookings found</h3>
                    <p class="mt-1 text-sm text-gray-500">You haven't made any bookings yet.</p>
                    <div class="mt-6">
                        <a href="{{ route('activities.index') }}" class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700">
                            Browse Activities
                        </a>
                    </div>
                </div>
            </div>
        @endforelse
    </div>

    <!-- Pagination -->
    @if($bookings->hasPages())
        <div class="mt-8">
            {{ $bookings->links() }}
        </div>
    @endif

</div>
@endsection

@extends('layouts.app')

@section('title', 'My Bookings')

@section('content')
<div class="container mx-auto p-6 space-y-6">

    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-gray-900">My Bookings</h1>
        <a href="{{ route('bookings.create') }}" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700">
            + New Booking
        </a>
    </div>

    @if($bookings->count() > 0)
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($bookings as $booking)
                @include('bookings.partials._booking-card', ['booking' => $booking])
            @endforeach
        </div>

        <div class="mt-6">
            {{ $bookings->links() }} <!-- Pagination -->
        </div>
    @else
        <div class="text-center py-16">
            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
            </svg>
            <h3 class="mt-2 text-lg font-medium text-gray-900">No bookings found</h3>
            <p class="mt-1 text-sm text-gray-500">Start by creating a new booking.</p>
        </div>
    @endif

</div>

<script>
function downloadReceipt(bookingId) {
    const url = `/bookings/${bookingId}/receipt`; // Make sure this route exists
    window.open(url, '_blank');
}
</script>

@endsection

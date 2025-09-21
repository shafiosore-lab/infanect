{{-- resources/views/bookings/index.blade.php --}}
@extends('layouts.app')

@section('title', __('My Bookings'))

@section('content')
<div class="dashboard-container">
    <div class="dashboard-section">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h3 class="mb-1 fw-bold text-dark">
                    <i class="fas fa-calendar-check text-primary me-2"></i>
                    Family Bookings
                </h3>
                <p class="text-muted mb-0 small">Manage family registrations and activity bookings</p>
            </div>
        </div>
    </div>

    <div class="card shadow-sm border-0">
        <div class="card-body">
            @if($bookings->isEmpty())
                <div class="text-center py-5">
                    <i class="fas fa-calendar-times text-muted" style="font-size: 3rem;"></i>
                    <h5 class="mt-3 text-muted">No bookings yet</h5>
                    <p class="text-muted">When families book your activities, they'll appear here.</p>
                    <a href="{{ route('activities.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus me-1"></i> Create Your First Activity
                    </a>
                </div>
            @else
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead class="table-light">
                            <tr>
                                <th>Booking ID</th>
                                <th>Date</th>
                                <th>Participants</th>
                                <th>Status</th>
                                <th>Amount</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($bookings as $booking)
                                <tr>
                                    <td class="fw-semibold">#{{ substr($booking->reference ?? $booking->id, 0, 8) }}</td>
                                    <td>{{ \Carbon\Carbon::parse($booking->booking_date)->format('M j, Y') }}</td>
                                    <td>{{ $booking->participants ?? 1 }} people</td>
                                    <td>
                                        @switch($booking->status)
                                            @case('confirmed')
                                                <span class="badge bg-success">Confirmed</span>
                                                @break
                                            @case('pending')
                                                <span class="badge bg-warning">Pending</span>
                                                @break
                                            @case('completed')
                                                <span class="badge bg-primary">Completed</span>
                                                @break
                                            @default
                                                <span class="badge bg-secondary">{{ ucfirst($booking->status) }}</span>
                                        @endswitch
                                    </td>
                                    <td>KSh {{ number_format($booking->amount ?? 0, 2) }}</td>
                                    <td>
                                        <div class="btn-group btn-group-sm">
                                            <button class="btn btn-outline-primary" title="View Details">
                                                <i class="fas fa-eye"></i>
                                            </button>
                                            <button class="btn btn-outline-success" title="Contact Family">
                                                <i class="fas fa-phone"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="d-flex justify-content-center mt-3">
                    {{ $bookings->links() }}
                </div>
            @endif
        </div>
    </div>
</div>
@endsection

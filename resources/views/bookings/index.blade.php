{{-- resources/views/bookings/index.blade.php --}}
@extends('layouts.app')

@section('title', __('My Bookings'))

@section('content')
<div class="max-w-7xl mx-auto p-6">
    <div class="mb-6 flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold">{{ __('My Bookings') }}</h1>
            <p class="text-sm text-gray-500">{{ __('A list of your recent bookings and their status') }}</p>
        </div>
    </div>

    @php
        $bookingsList = $bookings ?? collect();
        if (! ($bookingsList instanceof \Illuminate\Support\Collection) && is_iterable($bookingsList)) {
            $bookingsList = collect($bookingsList);
        }
    @endphp

    @if($bookingsList->count() === 0)
        <div class="p-6 bg-white/5 border border-white/10 rounded">
            <p class="text-gray-400">{{ __('You have no bookings yet.') }}</p>
            <a href="{{ route('activities.index') }}" class="mt-3 inline-block text-primary hover:underline">{{ __('Browse activities') }}</a>
        </div>
    @else
        <div class="overflow-x-auto bg-white/5 rounded border border-white/10">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50/5">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-400">{{ __('Ref') }}</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-400">{{ __('Service') }}</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-400">{{ __('Provider') }}</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-400">{{ __('Date') }}</th>
                        <th class="px-4 py-3 text-right text-xs font-medium text-gray-400">{{ __('Amount') }}</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-400">{{ __('Status') }}</th>
                        <th class="px-4 py-3 text-right text-xs font-medium text-gray-400">{{ __('Actions') }}</th>
                    </tr>
                </thead>
                <tbody class="bg-transparent divide-y divide-gray-700">
                    @foreach($bookingsList as $booking)
                        @php
                            $ref = $booking->reference ?? $booking->transaction_reference ?? ('#'.$booking->id ?? '—');
                            $service = optional($booking)->service->title ?? $booking->activity_title ?? $booking->service_name ?? '—';
                            $provider = optional(optional($booking)->provider)->business_name ?? optional($booking)->provider_name ?? '—';
                            $date = optional($booking)->start_time ?? optional($booking)->scheduled_at ?? optional($booking)->created_at ?? null;
                            $amount = $booking->amount ?? $booking->total ?? 0;
                            $status = ucfirst($booking->status ?? 'unknown');
                        @endphp
                        <tr>
                            <td class="px-4 py-3 text-sm text-gray-200">{{ $ref }}</td>
                            <td class="px-4 py-3 text-sm text-gray-200">{{ $service }}</td>
                            <td class="px-4 py-3 text-sm text-gray-200">{{ $provider }}</td>
                            <td class="px-4 py-3 text-sm text-gray-200">{{ $date ? \Carbon\Carbon::parse($date)->format('Y-m-d H:i') : '—' }}</td>
                            <td class="px-4 py-3 text-sm text-gray-200 text-right">{{ number_format($amount, 2) }}</td>
                            <td class="px-4 py-3 text-sm text-gray-200">{{ $status }}</td>
                            <td class="px-4 py-3 text-sm text-right">
                                <a href="{{ route('user.bookings.show', $booking->id ?? $booking->booking_id ?? $booking->id) }}" class="text-primary hover:underline">{{ __('View') }}</a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="mt-4">
            @if(method_exists($bookings ?? null, 'links'))
                {{ $bookings->links() }}
            @endif
        </div>
    @endif
</div>
@endsection

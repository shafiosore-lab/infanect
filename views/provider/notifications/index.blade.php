@extends('layouts.app')
@section('content')
<div class="max-w-4xl mx-auto p-4">
    <h2 class="text-xl font-bold mb-3">My Notifications</h2>
    <div class="space-y-2">
        @foreach($logs as $log)
            <div class="p-3 bg-white rounded shadow">
                <div class="text-sm text-gray-500">{{ $log->created_at->diffForHumans() }} — {{ $log->channel }}</div>
                <div class="mt-1">{!! nl2br(e($log->message)) !!}</div>
            </div>
        @endforeach
    </div>
    <div class="mt-4">{{ $logs->links() }}</div>
</div>
@endsection

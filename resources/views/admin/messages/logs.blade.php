@extends('layouts.app')
@section('content')
<div class="max-w-6xl mx-auto p-4">
    <h2 class="text-xl font-bold mb-3">Communication Logs</h2>
    <table class="w-full bg-white rounded shadow">
        <thead class="bg-gray-100">
            <tr>
                <th class="p-2">ID</th>
                <th class="p-2">User</th>
                <th class="p-2">Channel</th>
                <th class="p-2">Type</th>
                <th class="p-2">Status</th>
                <th class="p-2">Sent At</th>
            </tr>
        </thead>
        <tbody>
            @foreach($logs as $log)
            <tr>
                <td class="p-2">{{ $log->id }}</td>
                <td class="p-2">{{ $log->user->name ?? $log->user_id }}</td>
                <td class="p-2">{{ $log->channel }}</td>
                <td class="p-2">{{ $log->type }}</td>
                <td class="p-2">{{ $log->status }}</td>
                <td class="p-2">{{ $log->sent_at }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
    <div class="mt-3">{{ $logs->links() }}</div>
</div>
@endsection

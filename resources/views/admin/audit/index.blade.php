@extends('layouts.app')

@section('content')
<div class="max-w-6xl mx-auto p-6">
    <h1 class="text-2xl font-semibold mb-4">Audit Logs</h1>

    <form method="GET" class="mb-4 flex gap-2">
        <input type="date" name="from" value="{{ request('from') }}" class="p-2 border rounded" />
        <input type="date" name="to" value="{{ request('to') }}" class="p-2 border rounded" />
        <select name="user_id" class="p-2 border rounded">
            <option value="">All users</option>
            @foreach($users as $u)
                <option value="{{ $u->id }}" {{ request('user_id') == $u->id ? 'selected' : '' }}>{{ $u->name }}</option>
            @endforeach
        </select>
        <input type="text" name="event" placeholder="Event contains..." value="{{ request('event') }}" class="p-2 border rounded" />
        <button class="px-3 py-2 bg-indigo-600 text-white rounded">Filter</button>
    </form>

    <table class="w-full bg-white rounded shadow">
        <thead>
            <tr class="text-left p-2">
                <th class="p-2">Time</th>
                <th class="p-2">User</th>
                <th class="p-2">Event</th>
                <th class="p-2">IP</th>
                <th class="p-2">Meta</th>
            </tr>
        </thead>
        <tbody>
            @foreach($logs as $log)
            <tr>
                <td class="p-2">{{ $log->created_at }}</td>
                <td class="p-2">{{ $log->user?->name ?? 'System' }}</td>
                <td class="p-2">{{ $log->event }}</td>
                <td class="p-2">{{ $log->ip }}</td>
                <td class="p-2"><pre class="text-xs">{{ json_encode($log->meta) }}</pre></td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="mt-4">{{ $logs->links() }}</div>
</div>
@endsection

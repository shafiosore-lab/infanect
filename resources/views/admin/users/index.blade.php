@extends('layouts.super-admin')

@section('content')
<div class="container mx-auto p-6">
    <h1 class="text-2xl font-bold mb-4">User Management</h1>

    @if(session('status'))
        <div class="p-2 bg-green-100 text-green-800 rounded mb-4">{{ session('status') }}</div>
    @endif

    <table class="min-w-full bg-white">
        <thead>
            <tr>
                <th class="px-4 py-2">ID</th>
                <th class="px-4 py-2">Name</th>
                <th class="px-4 py-2">Email</th>
                <th class="px-4 py-2">Role</th>
                <th class="px-4 py-2">Action</th>
            </tr>
        </thead>
        <tbody>
            @foreach($users as $u)
                <tr>
                    <td class="border px-4 py-2">{{ $u->id }}</td>
                    <td class="border px-4 py-2">{{ $u->name }}</td>
                    <td class="border px-4 py-2">{{ $u->email }}</td>
                    <td class="border px-4 py-2">
                        <form method="POST" action="{{ route('admin.users.update-role', $u->id) }}">@csrf
                            <select name="role_id">
                                @foreach($roles as $r)
                                    <option value="{{ $r->id }}" {{ $u->role_id == $r->id ? 'selected' : '' }}>{{ $r->name }}</option>
                                @endforeach
                            </select>
                            <button class="btn btn-sm">Save</button>
                        </form>
                    </td>
                    <td class="border px-4 py-2">&nbsp;</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    {{ $users->links() }}
</div>
@endsection

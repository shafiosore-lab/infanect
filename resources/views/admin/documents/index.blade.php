@extends('layouts.app')

@section('content')
<div class="container mx-auto p-6">
    <h1 class="text-2xl font-bold mb-4">Uploaded Documents</h1>

    @if(session('status'))
        <div class="p-2 bg-green-100 text-green-800 rounded mb-4">{{ session('status') }}</div>
    @endif

    <table class="min-w-full bg-white">
        <thead>
            <tr>
                <th class="px-4 py-2">ID</th>
                <th class="px-4 py-2">Name</th>
                <th class="px-4 py-2">Total Chunks</th>
                <th class="px-4 py-2">Indexed</th>
                <th class="px-4 py-2">Uploaded</th>
                <th class="px-4 py-2">Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($docs as $d)
                <tr>
                    <td class="border px-4 py-2">{{ $d->id }}</td>
                    <td class="border px-4 py-2">{{ $d->original_name }}</td>
                    <td class="border px-4 py-2">{{ $d->total_chunks_count }}</td>
                    <td class="border px-4 py-2">{{ $d->indexed_chunks_count }}</td>
                    <td class="border px-4 py-2">{{ $d->created_at }}</td>
                    <td class="border px-4 py-2">
                        <form method="POST" action="{{ route('admin.documents.destroy', $d->id) }}">@csrf @method('DELETE')<button class="btn btn-danger">Delete</button></form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    {{ $docs->links() }}
</div>
@endsection

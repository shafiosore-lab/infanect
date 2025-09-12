@extends('layouts.app')

@section('content')
<div class="max-w-3xl mx-auto">
    <h1 class="text-2xl font-semibold mb-4">Upload Documents</h1>

    @if(session('status'))
        <div class="mb-4 text-green-600">{{ session('status') }}</div>
    @endif

    <form method="POST" action="{{ route('provider.documents') }}" enctype="multipart/form-data">
        @csrf
        <div class="grid gap-4">
            <div>
                <label class="block text-sm font-medium">Business License</label>
                <input type="file" name="business_license" class="mt-1" />
            </div>
            <div>
                <label class="block text-sm font-medium">ID Document</label>
                <input type="file" name="id_document" class="mt-1" />
            </div>
            <div>
                <button class="px-4 py-2 bg-blue-600 text-white rounded">Upload</button>
            </div>
        </div>
    </form>
</div>
@endsection

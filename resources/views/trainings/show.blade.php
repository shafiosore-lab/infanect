@extends('layouts.app')

@section('content')
<div class="container mx-auto p-6">
    <h1 class="text-2xl font-bold mb-4">{{ $module->title ?? 'Training Module' }}</h1>
    <p class="text-gray-600">{{ $module->description ?? 'Details coming soon.' }}</p>
</div>
@endsection

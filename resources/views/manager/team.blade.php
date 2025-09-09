@extends('layouts.app')

@section('content')
<div class="p-6">
    <h1 class="text-2xl font-bold">This is the {{ ucfirst(basename(__FILE__, '.blade.php')) }} page.</h1>
</div>
@endsection

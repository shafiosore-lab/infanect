@extends('layouts.app')

@section('title', 'Mental Health Resources')

@section('content')
<div class="max-w-4xl mx-auto p-6">
    <h1 class="text-2xl font-bold mb-4">Mental Health Resources</h1>
    <p class="mb-4">Here are curated resources to support you. If you're in immediate danger, contact emergency services.</p>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <div class="p-4 rounded bg-white shadow">
            <h3 class="font-semibold">Crisis Lines</h3>
            <ul class="mt-2 text-sm">
                <li>US: 988</li>
                <li>UK: Samaritans - 116 123</li>
                <li>Australia: Lifeline - 13 11 14</li>
                <li>Other: check local services</li>
            </ul>
        </div>
        <div class="p-4 rounded bg-white shadow">
            <h3 class="font-semibold">Self-help</h3>
            <ul class="mt-2 text-sm">
                <li>Guided breathing and grounding exercises</li>
                <li>Short journaling prompts</li>
                <li>Professional therapy finders</li>
            </ul>
        </div>
    </div>

    <div class="mt-6 p-4 bg-white rounded shadow">More resources coming soon.</div>
</div>
@endsection

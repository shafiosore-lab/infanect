@extends('layouts.app')

@section('title', 'View Module')

@section('content')
<div class="container mx-auto p-6">
    <div class="max-w-4xl mx-auto">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-3xl font-bold text-gray-900">Module Details</h1>
            <div class="space-x-3">
                <a href="{{ route('modules.edit', $module) }}" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg">
                    Edit Module
                </a>
                <a href="{{ route('modules.index') }}" class="bg-gray-300 hover:bg-gray-400 text-gray-800 px-4 py-2 rounded-lg">
                    Back to List
                </a>
            </div>
        </div>

        <div class="bg-white shadow rounded-lg p-6">
            <dl class="grid grid-cols-1 gap-x-4 gap-y-6 sm:grid-cols-2">
                <div>
                    <dt class="text-sm font-medium text-gray-500">Title</dt>
                    <dd class="mt-1 text-sm text-gray-900">{{ $module->title }}</dd>
                </div>

                <div>
                    <dt class="text-sm font-medium text-gray-500">Level</dt>
                    <dd class="mt-1 text-sm text-gray-900">{{ $module->level ?? 'Not specified' }}</dd>
                </div>

                <div class="sm:col-span-2">
                    <dt class="text-sm font-medium text-gray-500">Description</dt>
                    <dd class="mt-1 text-sm text-gray-900">{{ $module->description ?? 'No description provided' }}</dd>
                </div>

                <div class="sm:col-span-2">
                    <dt class="text-sm font-medium text-gray-500">Tags</dt>
                    <dd class="mt-1 text-sm text-gray-900">
                        @if($module->tags && count($module->tags) > 0)
                            <div class="flex flex-wrap gap-2">
                                @foreach($module->tags as $tag)
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                        {{ $tag }}
                                    </span>
                                @endforeach
                            </div>
                        @else
                            No tags assigned
                        @endif
                    </dd>
                </div>

                <div>
                    <dt class="text-sm font-medium text-gray-500">Created At</dt>
                    <dd class="mt-1 text-sm text-gray-900">{{ $module->created_at->format('M d, Y H:i') }}</dd>
                </div>

                <div>
                    <dt class="text-sm font-medium text-gray-500">Updated At</dt>
                    <dd class="mt-1 text-sm text-gray-900">{{ $module->updated_at->format('M d, Y H:i') }}</dd>
                </div>
            </dl>
        </div>
    </div>
</div>
@endsection

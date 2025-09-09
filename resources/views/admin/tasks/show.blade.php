@extends('layouts.admin')

@section('title', 'Task Details')

@section('content')
<div class="container mx-auto p-6">
    <!-- Header -->
    <div class="mb-6">
        <div class="flex items-center justify-between">
            <div class="flex items-center space-x-4">
                <a href="{{ route('admin.tasks.index') }}"
                   class="text-indigo-600 hover:text-indigo-900">
                    <i class="fas fa-arrow-left mr-2"></i>Back to Tasks
                </a>
            </div>
            <div class="flex space-x-2">
                <a href="{{ route('admin.tasks.edit', $task) }}"
                   class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg font-medium">
                    <i class="fas fa-edit mr-2"></i>Edit
                </a>
                <form action="{{ route('admin.tasks.destroy', $task) }}"
                      method="POST"
                      class="inline"
                      onsubmit="return confirm('Are you sure you want to delete this task?')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg font-medium">
                        <i class="fas fa-trash mr-2"></i>Delete
                    </button>
                </form>
            </div>
        </div>
        <h1 class="text-3xl font-bold text-gray-900 mt-4">{{ $task->title }}</h1>
    </div>

    <!-- Task Details -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Main Content -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Task Description -->
            <div class="bg-white shadow rounded-lg p-6">
                <h2 class="text-lg font-semibold text-gray-900 mb-4">Description</h2>
                @if($task->description)
                    <p class="text-gray-700 whitespace-pre-wrap">{{ $task->description }}</p>
                @else
                    <p class="text-gray-500 italic">No description provided.</p>
                @endif
            </div>

            <!-- Task Timeline -->
            <div class="bg-white shadow rounded-lg p-6">
                <h2 class="text-lg font-semibold text-gray-900 mb-4">Timeline</h2>
                <div class="space-y-4">
                    <div class="flex items-center space-x-3">
                        <div class="flex-shrink-0">
                            <i class="fas fa-plus-circle text-green-500"></i>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-900">Task Created</p>
                            <p class="text-sm text-gray-500">{{ $task->created_at->format('M d, Y \a\t g:i A') }}</p>
                        </div>
                    </div>
                    @if($task->updated_at != $task->created_at)
                        <div class="flex items-center space-x-3">
                            <div class="flex-shrink-0">
                                <i class="fas fa-edit text-blue-500"></i>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-900">Last Updated</p>
                                <p class="text-sm text-gray-500">{{ $task->updated_at->format('M d, Y \a\t g:i A') }}</p>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="space-y-6">
            <!-- Task Status -->
            <div class="bg-white shadow rounded-lg p-6">
                <h2 class="text-lg font-semibold text-gray-900 mb-4">Status</h2>
                <div class="space-y-3">
                    <div>
                        <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full
                            @if($task->status === 'completed') bg-green-100 text-green-800
                            @elseif($task->status === 'in_progress') bg-blue-100 text-blue-800
                            @elseif($task->status === 'pending') bg-yellow-100 text-yellow-800
                            @else bg-gray-100 text-gray-800 @endif">
                            {{ ucfirst(str_replace('_', ' ', $task->status)) }}
                        </span>
                    </div>

                    <!-- Quick Status Update -->
                    <form action="{{ route('admin.tasks.update-status', $task) }}" method="POST" class="space-y-2">
                        @csrf
                        <label for="status_update" class="block text-sm font-medium text-gray-700">Update Status</label>
                        <select name="status" id="status_update" onchange="this.form.submit()"
                                class="block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 text-sm">
                            <option value="pending" {{ $task->status == 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="in_progress" {{ $task->status == 'in_progress' ? 'selected' : '' }}>In Progress</option>
                            <option value="completed" {{ $task->status == 'completed' ? 'selected' : '' }}>Completed</option>
                            <option value="cancelled" {{ $task->status == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                        </select>
                    </form>
                </div>
            </div>

            <!-- Task Details -->
            <div class="bg-white shadow rounded-lg p-6">
                <h2 class="text-lg font-semibold text-gray-900 mb-4">Details</h2>
                <dl class="space-y-3">
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Priority</dt>
                        <dd class="mt-1">
                            <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full
                                @if($task->priority === 'urgent') bg-red-100 text-red-800
                                @elseif($task->priority === 'high') bg-orange-100 text-orange-800
                                @elseif($task->priority === 'medium') bg-yellow-100 text-yellow-800
                                @else bg-green-100 text-green-800 @endif">
                                {{ ucfirst($task->priority) }}
                            </span>
                        </dd>
                    </div>

                    <div>
                        <dt class="text-sm font-medium text-gray-500">Assigned To</dt>
                        <dd class="mt-1 text-sm text-gray-900">
                            {{ $task->assignedUser ? $task->assignedUser->name : 'Unassigned' }}
                        </dd>
                    </div>

                    <div>
                        <dt class="text-sm font-medium text-gray-500">Due Date</dt>
                        <dd class="mt-1 text-sm text-gray-900">
                            {{ $task->due_date ? $task->due_date->format('M d, Y') : 'No due date' }}
                        </dd>
                    </div>

                    <div>
                        <dt class="text-sm font-medium text-gray-500">Created By</dt>
                        <dd class="mt-1 text-sm text-gray-900">
                            {{ $task->creator->name }}
                        </dd>
                    </div>
                </dl>
            </div>
        </div>
    </div>
</div>
@endsection

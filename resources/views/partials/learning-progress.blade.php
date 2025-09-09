<!-- Learning Progress -->
<div class="bg-white shadow rounded-lg p-6">
    <div class="flex items-center justify-between mb-4">
        <h3 class="text-lg font-semibold text-gray-900">Learning Progress</h3>
        <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
        </svg>
    </div>

    <div class="space-y-4">
        @php
            $modules = auth()->user()->moduleProgress() ?? collect();
            $totalModules = $modules->count();
            $completedModules = $modules->where('status', 'completed')->count();
            $progressPercentage = $totalModules > 0 ? round(($completedModules / $totalModules) * 100) : 0;
        @endphp

        <!-- Overall Progress -->
        <div>
            <div class="flex justify-between text-sm text-gray-600 mb-1">
                <span>Overall Progress</span>
                <span>{{ $progressPercentage }}%</span>
            </div>
            <div class="w-full bg-gray-200 rounded-full h-2">
                <div class="bg-blue-600 h-2 rounded-full" style="width: {{ $progressPercentage }}%"></div>
            </div>
        </div>

        <!-- Module List -->
        <div class="space-y-3">
            @forelse($modules->take(3) as $progress)
                <div class="flex items-center justify-between">
                    <div class="flex-1">
                        <div class="text-sm font-medium text-gray-900">{{ $progress->module->title ?? 'Module' }}</div>
                        <div class="text-xs text-gray-500">{{ $progress->is_completed ? 'Completed' : 'In Progress' }}</div>
                    </div>
                    <div class="text-sm font-semibold {{ $progress->is_completed ? 'text-green-600' : 'text-blue-600' }}">
                        {{ $progress->is_completed ? '✓' : '○' }}
                    </div>
                </div>
            @empty
                <div class="text-center py-4">
                    <div class="text-sm text-gray-500">No modules started yet</div>
                    <a href="{{ route('parenting-modules.index') }}" class="text-sm text-blue-600 hover:text-blue-800">Browse modules</a>
                </div>
            @endforelse
        </div>

        @if($modules->count() > 3)
            <div class="text-center">
                <a href="{{ route('parenting-modules.index') }}" class="text-sm text-blue-600 hover:text-blue-800">View all modules</a>
            </div>
        @endif
    </div>
</div>

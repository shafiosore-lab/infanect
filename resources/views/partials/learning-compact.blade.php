<!-- Learning Progress Compact -->
<div class="p-6">
    <div class="flex items-center justify-between mb-4">
        <h3 class="text-lg font-semibold text-gray-900">Learning Progress</h3>
        <a href="{{ route('parenting-modules.index') }}" class="text-sm text-blue-600 hover:text-blue-800">View all</a>
    </div>

    <div class="space-y-4">
        @php
            $modules = auth()->user()->moduleProgress() ?? collect();
            $totalModules = $modules->count();
            $completedModules = $modules->where('status', 'completed')->count();
            $progressPercentage = $totalModules > 0 ? round(($completedModules / $totalModules) * 100) : 0;
        @endphp

        <!-- Progress Circle -->
        <div class="flex items-center justify-center">
            <div class="relative w-20 h-20">
                <svg class="w-20 h-20 transform -rotate-90" viewBox="0 0 36 36">
                    <path d="M18 2.0845
                              a 15.9155 15.9155 0 0 1 0 31.831
                              a 15.9155 15.9155 0 0 1 0 -31.831"
                          fill="none"
                          stroke="#E5E7EB"
                          stroke-width="2"/>
                    <path d="M18 2.0845
                              a 15.9155 15.9155 0 0 1 0 31.831
                              a 15.9155 15.9155 0 0 1 0 -31.831"
                          fill="none"
                          stroke="#3B82F6"
                          stroke-width="2"
                          stroke-dasharray="{{ $progressPercentage }}, 100"/>
                </svg>
                <div class="absolute inset-0 flex items-center justify-center">
                    <span class="text-lg font-semibold text-gray-900">{{ $progressPercentage }}%</span>
                </div>
            </div>
        </div>

        <!-- Module Stats -->
        <div class="text-center">
            <div class="text-sm text-gray-600">{{ $completedModules }} of {{ $totalModules }} modules completed</div>
        </div>

        <!-- Current Module -->
        @php
            $currentModule = $modules->where('status', '!=', 'completed')->first();
        @endphp
        @if($currentModule)
            <div class="bg-blue-50 rounded-lg p-3">
                <div class="text-sm font-medium text-gray-900 mb-1">Current Module</div>
                <div class="text-sm text-gray-600">{{ $currentModule->module->title ?? 'Module' }}</div>
                <div class="mt-2">
                    <a href="{{ route('parenting-modules.show', $currentModule->module_id) }}" class="text-xs text-blue-600 hover:text-blue-800">Continue learning →</a>
                </div>
            </div>
        @else
            <div class="bg-gray-50 rounded-lg p-3 text-center">
                <div class="text-sm text-gray-600 mb-2">Ready for your next module?</div>
                <a href="{{ route('parenting-modules.index') }}" class="text-sm text-blue-600 hover:text-blue-800">Browse modules</a>
            </div>
        @endif
    </div>
</div>

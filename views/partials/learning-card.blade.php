@props([
    'title' => 'Learning Module',
    'description' => '',
    'iconColor' => 'from-blue-500 to-indigo-600',
    'progress' => 0,
    'browseRoute' => '#',
    'progressRoute' => null,
    'extraRoute' => null,
    'extraLabel' => null,
    'extraColor' => null,
])

<div class="bg-gradient-to-r from-gray-50 to-white border rounded-xl p-6 shadow-sm">
    <div class="flex items-center justify-between mb-4">
        <div class="flex items-center space-x-3">
            <div class="w-12 h-12 bg-gradient-to-r {{ $iconColor }} rounded-full flex items-center justify-center">
                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                </svg>
            </div>
            <div>
                <h3 class="text-lg font-semibold text-gray-900">{{ $title }}</h3>
                <p class="text-sm text-gray-600">{{ $description }}</p>
            </div>
        </div>
    </div>

    <!-- Progress bar -->
    <div class="space-y-3">
        <div class="flex items-center justify-between text-sm">
            <span class="text-gray-600">Progress</span>
            <span class="font-medium">{{ $progress }}%</span>
        </div>
        <div class="w-full bg-gray-200 rounded-full h-2">
            <div class="bg-gradient-to-r {{ $iconColor }} h-2 rounded-full"
                 style="width: {{ $progress }}%">
            </div>
        </div>
    </div>

    <!-- Buttons -->
    <div class="mt-4 flex space-x-2">
        <a href="{{ $browseRoute }}"
           class="flex-1 inline-flex items-center justify-center px-4 py-2 text-sm font-medium rounded-md text-white bg-gradient-to-r {{ $iconColor }} hover:opacity-90">
           Browse
        </a>

        @if($progressRoute)
            <a href="{{ $progressRoute }}"
               class="inline-flex items-center px-4 py-2 text-sm font-medium rounded-md text-gray-700 bg-white border border-gray-300 hover:bg-gray-50">
               My Progress
            </a>
        @endif

        @if($extraRoute && $extraLabel)
            <a href="{{ $extraRoute }}"
               class="inline-flex items-center px-4 py-2 text-sm font-medium rounded-md text-white bg-gradient-to-r {{ $extraColor }} hover:opacity-90">
               {{ $extraLabel }}
            </a>
        @endif
    </div>
</div>

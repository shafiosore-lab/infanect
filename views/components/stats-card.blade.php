<div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
    @if($icon)
        <div class="text-{{ $color ?? 'blue' }}-500 text-3xl mb-2">
            <i class="{{ is_array($icon) ? implode(' ', $icon) : $icon }}"></i>
        </div>
    @endif
    <div class="text-xl font-semibold text-gray-700">{{ is_array($title) ? implode(' ', $title) : $title }}</div>
    <div class="text-3xl font-bold text-{{ $color ?? 'blue' }}-600 mt-2">{{ is_array($value) ? implode(' ', $value) : $value }}</div>
</div>

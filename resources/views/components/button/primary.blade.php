@props([
    'variant' => 'primary',   // primary, secondary, outline, danger, success
    'size' => 'md',           // sm, md, lg
    'icon' => null,           // optional icon class (e.g., "fas fa-check")
    'iconPosition' => 'left'  // left, right
])

@php
    // Base styles for all buttons
    $baseClasses = 'inline-flex items-center justify-center font-medium rounded-lg focus:outline-none focus:ring-2 transition-colors';

    // Variant-specific styles using InfaNect color palette
    $variantClasses = match($variant) {
        'primary'   => 'bg-[#ea1c4d] text-white hover:bg-[#ea1c4d]/90 active:bg-[#ea1c4d]/95 focus:ring-[#ea1c4d]/50',
        'secondary' => 'bg-gray-200 text-gray-900 hover:bg-gray-300 active:bg-gray-250 focus:ring-gray-300/50',
        'outline'   => 'bg-transparent border border-[#ea1c4d] text-[#ea1c4d] hover:bg-[#ea1c4d]/10 active:bg-[#ea1c4d]/20 focus:ring-[#ea1c4d]/30',
        'danger'    => 'bg-red-600 text-white hover:bg-red-700 active:bg-red-800 focus:ring-red-500/50',
        'success'   => 'bg-[#65c16e] text-white hover:bg-[#65c16e]/90 active:bg-[#65c16e]/95 focus:ring-[#65c16e]/50',
        default     => 'bg-[#ea1c4d] text-white hover:bg-[#ea1c4d]/90 active:bg-[#ea1c4d]/95 focus:ring-[#ea1c4d]/50',
    };

    // Size-specific styles
    $sizeClasses = match($size) {
        'sm' => 'px-3 py-1.5 text-sm',
        'md' => 'px-4 py-2 text-base',
        'lg' => 'px-5 py-2.5 text-lg',
        default => 'px-4 py-2 text-base',
    };

    // Disabled state handling
    $disabledClasses = 'disabled:opacity-60 disabled:cursor-not-allowed';
@endphp

<button {{ $attributes->merge(['class' => "$baseClasses $variantClasses $sizeClasses $disabledClasses"]) }}>
    @if($icon && $iconPosition === 'left')
        <i class="{{ $icon }} mr-2"></i>
    @endif

    {{ $slot }}

    @if($icon && $iconPosition === 'right')
        <i class="{{ $icon }} ml-2"></i>
    @endif
</button>

<div {{ $attributes->merge(['class' => 'bg-accent text-neutral-text p-3 rounded-md']) }}>
    <strong class="text-primary">{{ $title ?? 'Notice' }}:</strong>
    <span class="ml-2">{{ $slot }}</span>
</div>

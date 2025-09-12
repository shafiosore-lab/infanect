<button {{ $attributes->merge(['class' => 'bg-primary text-white px-4 py-2 rounded-lg hover:bg-primary/80']) }}>
    {{ $slot }}
</button>

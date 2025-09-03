@props([
    'variant' => 'default',
    'padding' => 'default',
    'class' => ''
])

<div
    {{ $attributes->merge([
        'class' => 'rounded-xl border bg-card text-card-foreground shadow ' .
        match($variant) {
            'default' => '',
            'elevated' => 'shadow-lg',
            'outlined' => 'border-2',
            'ghost' => 'border-0 shadow-none',
            default => ''
        } . ' ' .
        match($padding) {
            'none' => 'p-0',
            'sm' => 'p-4',
            'default' => 'p-6',
            'lg' => 'p-8',
            default => 'p-6'
        } . ' ' . $class
    ]) }}
>
    {{ $slot }}
</div>
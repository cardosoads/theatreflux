@props([
    'variant' => 'default',
    'size' => 'default',
    'for' => null,
    'class' => ''
])

<label
    @if($for) for="{{ $for }}" @endif
    {{ $attributes->merge([
        'class' => 'text-sm font-medium leading-none peer-disabled:cursor-not-allowed peer-disabled:opacity-70 ' .
        match($variant) {
            'default' => 'text-foreground',
            'required' => 'text-foreground after:content-["*"] after:ml-0.5 after:text-destructive',
            'optional' => 'text-muted-foreground',
            default => 'text-foreground'
        } . ' ' .
        match($size) {
            'default' => 'text-sm',
            'sm' => 'text-xs',
            'lg' => 'text-base',
            default => 'text-sm'
        } . ' ' . $class
    ]) }}
>
    {{ $slot }}
</label>
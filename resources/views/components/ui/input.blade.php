@props([
    'variant' => 'default',
    'size' => 'default',
    'type' => 'text',
    'disabled' => false,
    'readonly' => false,
    'required' => false,
    'placeholder' => '',
    'value' => '',
    'class' => ''
])

<input
    type="{{ $type }}"
    {{ $disabled ? 'disabled' : '' }}
    {{ $readonly ? 'readonly' : '' }}
    {{ $required ? 'required' : '' }}
    @if($placeholder) placeholder="{{ $placeholder }}" @endif
    @if($value) value="{{ $value }}" @endif
    {{ $attributes->merge([
        'class' => 'flex h-9 w-full rounded-md border border-input bg-transparent px-3 py-1 text-sm shadow-sm transition-colors file:border-0 file:bg-transparent file:text-sm file:font-medium placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-ring disabled:cursor-not-allowed disabled:opacity-50 ' .
        match($variant) {
            'default' => '',
            'error' => 'border-destructive focus-visible:ring-destructive',
            'success' => 'border-green-500 focus-visible:ring-green-500',
            default => ''
        } . ' ' .
        match($size) {
            'default' => 'h-9',
            'sm' => 'h-8 text-xs',
            'lg' => 'h-10',
            default => 'h-9'
        } . ' ' . $class
    ]) }}
/>
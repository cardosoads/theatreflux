<template>
  <p :class="cn('text-sm text-muted-foreground', $attrs.class)">
    <slot />
  </p>
</template>

<script setup>
import { cn } from '@/utils/cn';

/**
 * CardDescription Component - Shadcn/UI Style
 * 
 * Description element for CardHeader
 * Renders as p with muted text styling
 */
</script>
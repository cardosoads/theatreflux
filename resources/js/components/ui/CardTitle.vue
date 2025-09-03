<template>
  <h3 :class="cn('font-semibold leading-none tracking-tight', $attrs.class)">
    <slot />
  </h3>
</template>

<script setup>
import { cn } from '@/utils/cn';

/**
 * CardTitle Component - Shadcn/UI Style
 * 
 * Title element for CardHeader
 * Renders as h3 with appropriate styling
 */
</script>
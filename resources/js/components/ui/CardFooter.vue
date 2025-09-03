<template>
  <div :class="cn('flex items-center p-6 pt-0', $attrs.class)">
    <slot />
  </div>
</template>

<script setup>
import { cn } from '@/utils/cn';

/**
 * CardFooter Component - Shadcn/UI Style
 * 
 * Footer section for Card component
 * Typically contains actions or additional information
 */
</script>
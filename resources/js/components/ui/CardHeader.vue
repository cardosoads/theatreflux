<template>
  <div :class="cn('flex flex-col space-y-1.5 p-6', $attrs.class)">
    <slot />
  </div>
</template>

<script setup>
import { cn } from '@/utils/cn';

/**
 * CardHeader Component - Shadcn/UI Style
 * 
 * Header section for Card component
 * Typically contains title and description
 */
</script>
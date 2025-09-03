<template>
  <div :class="cn('p-6 pt-0', $attrs.class)">
    <slot />
  </div>
</template>

<script setup>
import { cn } from '@/utils/cn';

/**
 * CardContent Component - Shadcn/UI Style
 * 
 * Main content section for Card component
 * Contains the primary card content
 */
</script>
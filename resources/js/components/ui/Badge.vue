<template>
  <div :class="cn(badgeVariants({ variant, size }), $attrs.class)">
    <slot />
  </div>
</template>

<script setup>
import { cn } from '@/utils/cn';
import { badgeVariants } from '@/utils/variants';

/**
 * Badge Component - Shadcn/UI Style
 * 
 * Small status indicator component with multiple variants
 * Used for displaying status, categories, or labels
 */

// Props definition
const props = defineProps({
  /**
   * Badge variant style
   * @type {'default' | 'secondary' | 'destructive' | 'outline' | 'success' | 'warning'}
   */
  variant: {
    type: String,
    default: 'default',
    validator: (value) => [
      'default', 
      'secondary', 
      'destructive', 
      'outline', 
      'success', 
      'warning'
    ].includes(value)
  },
  
  /**
   * Badge size
   * @type {'default' | 'sm' | 'lg'}
   */
  size: {
    type: String,
    default: 'default',
    validator: (value) => ['default', 'sm', 'lg'].includes(value)
  }
});
</script>
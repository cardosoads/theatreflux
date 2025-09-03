<template>
  <div :class="cn(cardVariants({ variant, padding }), $attrs.class)">
    <slot />
  </div>
</template>

<script setup>
import { cn } from '@/utils/cn';
import { cardVariants } from '@/utils/variants';

/**
 * Card Component - Shadcn/UI Style
 * 
 * A flexible card container component with multiple variants
 * Can be used with CardHeader, CardContent, and CardFooter subcomponents
 */

// Props definition
const props = defineProps({
  /**
   * Card variant style
   * @type {'default' | 'elevated' | 'outlined' | 'ghost'}
   */
  variant: {
    type: String,
    default: 'default',
    validator: (value) => ['default', 'elevated', 'outlined', 'ghost'].includes(value)
  },
  
  /**
   * Card padding size
   * @type {'none' | 'sm' | 'default' | 'lg'}
   */
  padding: {
    type: String,
    default: 'default',
    validator: (value) => ['none', 'sm', 'default', 'lg'].includes(value)
  }
});
</script>
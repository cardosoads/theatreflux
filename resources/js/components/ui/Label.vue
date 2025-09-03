<template>
  <label
    :for="htmlFor"
    :class="cn(labelVariants({ variant, size }), $attrs.class)"
    v-bind="labelProps"
  >
    <slot />
  </label>
</template>

<script setup>
import { computed, useAttrs } from 'vue';
import { cn } from '@/utils/cn';
import { labelVariants } from '@/utils/variants';

/**
 * Label Component - Shadcn/UI Style
 * 
 * A semantic label component with consistent styling
 * Supports accessibility features and form integration
 */

// Props definition
const props = defineProps({
  /**
   * Label variant style
   * @type {'default' | 'required' | 'optional'}
   */
  variant: {
    type: String,
    default: 'default',
    validator: (value) => ['default', 'required', 'optional'].includes(value)
  },
  
  /**
   * Label size
   * @type {'default' | 'sm' | 'lg'}
   */
  size: {
    type: String,
    default: 'default',
    validator: (value) => ['default', 'sm', 'lg'].includes(value)
  },
  
  /**
   * The id of the form element this label is associated with
   */
  htmlFor: {
    type: String,
    default: undefined
  }
});

// Get all attributes except class (handled separately)
const attrs = useAttrs();
const labelProps = computed(() => {
  const { class: _, ...rest } = attrs;
  return rest;
});

// Emits definition
defineEmits([
  /**
   * Emitted when label is clicked
   * @param {MouseEvent} event - Click event
   */
  'click'
]);
</script>

<style scoped>
/* Additional label-specific styles if needed */
/* Most styling is handled by Tailwind classes via variants */
</style>
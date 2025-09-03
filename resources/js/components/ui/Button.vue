<template>
  <component
    :is="asChild ? 'slot' : 'button'"
    :class="cn(buttonVariants({ variant, size }), $attrs.class)"
    :disabled="disabled"
    v-bind="buttonProps"
  >
    <slot />
  </component>
</template>

<script setup>
import { computed, useAttrs } from 'vue';
import { cn } from '@/utils/cn';
import { buttonVariants } from '@/utils/variants';

/**
 * Button Component - Shadcn/UI Style
 * 
 * A versatile button component with multiple variants and sizes
 * Supports all standard button functionality with shadcn/ui styling
 */

// Props definition
const props = defineProps({
  /**
   * Button variant style
   * @type {'default' | 'destructive' | 'outline' | 'secondary' | 'ghost' | 'link'}
   */
  variant: {
    type: String,
    default: 'default',
    validator: (value) => [
      'default',
      'destructive', 
      'outline',
      'secondary',
      'ghost',
      'link'
    ].includes(value)
  },
  
  /**
   * Button size
   * @type {'default' | 'sm' | 'lg' | 'icon'}
   */
  size: {
    type: String,
    default: 'default',
    validator: (value) => ['default', 'sm', 'lg', 'icon'].includes(value)
  },
  
  /**
   * Whether the button is disabled
   */
  disabled: {
    type: Boolean,
    default: false
  },
  
  /**
   * Render as child element (for composition)
   * When true, renders slot content directly without button wrapper
   */
  asChild: {
    type: Boolean,
    default: false
  },
  
  /**
   * Button type attribute
   * @type {'button' | 'submit' | 'reset'}
   */
  type: {
    type: String,
    default: 'button',
    validator: (value) => ['button', 'submit', 'reset'].includes(value)
  }
});

// Get all attributes except class (handled separately)
const attrs = useAttrs();
const buttonProps = computed(() => {
  const { class: _, ...rest } = attrs;
  return {
    ...rest,
    type: props.type,
    disabled: props.disabled
  };
});

// Emits definition
defineEmits([
  /**
   * Emitted when button is clicked
   * @param {MouseEvent} event - Click event
   */
  'click',
  
  /**
   * Emitted when button receives focus
   * @param {FocusEvent} event - Focus event
   */
  'focus',
  
  /**
   * Emitted when button loses focus
   * @param {FocusEvent} event - Blur event
   */
  'blur'
]);
</script>

<style scoped>
/* Additional button-specific styles if needed */
/* Most styling is handled by Tailwind classes via variants */
</style>
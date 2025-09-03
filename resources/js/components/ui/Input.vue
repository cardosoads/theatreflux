<template>
  <input
    :id="id"
    :type="type"
    :class="cn(inputVariants({ variant, size }), $attrs.class)"
    :placeholder="placeholder"
    :disabled="disabled"
    :readonly="readonly"
    :required="required"
    :value="modelValue"
    v-bind="inputProps"
    @input="handleInput"
    @blur="handleBlur"
    @focus="handleFocus"
    @keydown="handleKeydown"
  />
</template>

<script setup>
import { computed, useAttrs } from 'vue';
import { cn } from '@/utils/cn';
import { inputVariants } from '@/utils/variants';

/**
 * Input Component - Shadcn/UI Style
 * 
 * A versatile input component with validation states and multiple variants
 * Supports v-model and all standard input functionality
 */

// Props definition
const props = defineProps({
  /**
   * Input variant style
   * @type {'default' | 'error' | 'success'}
   */
  variant: {
    type: String,
    default: 'default',
    validator: (value) => ['default', 'error', 'success'].includes(value)
  },
  
  /**
   * Input size
   * @type {'default' | 'sm' | 'lg'}
   */
  size: {
    type: String,
    default: 'default',
    validator: (value) => ['default', 'sm', 'lg'].includes(value)
  },
  
  /**
   * Input type
   * @type {string}
   */
  type: {
    type: String,
    default: 'text'
  },
  
  /**
   * Input value (v-model)
   */
  modelValue: {
    type: [String, Number],
    default: ''
  },
  
  /**
   * Input placeholder text
   */
  placeholder: {
    type: String,
    default: ''
  },
  
  /**
   * Whether the input is disabled
   */
  disabled: {
    type: Boolean,
    default: false
  },
  
  /**
   * Whether the input is readonly
   */
  readonly: {
    type: Boolean,
    default: false
  },
  
  /**
   * Whether the input is required
   */
  required: {
    type: Boolean,
    default: false
  },
  
  /**
   * Input ID attribute
   */
  id: {
    type: String,
    default: undefined
  },
  
  /**
   * Input name attribute
   */
  name: {
    type: String,
    default: undefined
  },
  
  /**
   * Input autocomplete attribute
   */
  autocomplete: {
    type: String,
    default: undefined
  },
  
  /**
   * Maximum length for input
   */
  maxlength: {
    type: [String, Number],
    default: undefined
  },
  
  /**
   * Minimum length for input
   */
  minlength: {
    type: [String, Number],
    default: undefined
  },
  
  /**
   * Pattern for input validation
   */
  pattern: {
    type: String,
    default: undefined
  }
});

// Emits definition
const emit = defineEmits([
  /**
   * Emitted when input value changes (v-model)
   * @param {string|number} value - New input value
   */
  'update:modelValue',
  
  /**
   * Emitted on input event
   * @param {InputEvent} event - Input event
   */
  'input',
  
  /**
   * Emitted when input receives focus
   * @param {FocusEvent} event - Focus event
   */
  'focus',
  
  /**
   * Emitted when input loses focus
   * @param {FocusEvent} event - Blur event
   */
  'blur',
  
  /**
   * Emitted on keydown event
   * @param {KeyboardEvent} event - Keyboard event
   */
  'keydown',
  
  /**
   * Emitted on Enter key press
   * @param {KeyboardEvent} event - Keyboard event
   */
  'enter'
]);

// Get all attributes except class and handled props
const attrs = useAttrs();
const inputProps = computed(() => {
  const { 
    class: _, 
    onInput, 
    onFocus, 
    onBlur, 
    onKeydown,
    ...rest 
  } = attrs;
  
  return {
    ...rest,
    name: props.name,
    autocomplete: props.autocomplete,
    maxlength: props.maxlength,
    minlength: props.minlength,
    pattern: props.pattern
  };
});

// Event handlers
const handleInput = (event) => {
  const value = event.target.value;
  emit('update:modelValue', value);
  emit('input', event);
};

const handleFocus = (event) => {
  emit('focus', event);
};

const handleBlur = (event) => {
  emit('blur', event);
};

const handleKeydown = (event) => {
  emit('keydown', event);
  
  // Emit specific enter event
  if (event.key === 'Enter') {
    emit('enter', event);
  }
};
</script>

<style scoped>
/* Additional input-specific styles if needed */
/* Most styling is handled by Tailwind classes via variants */
</style>
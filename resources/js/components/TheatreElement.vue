<template>
  <div 
    class="theatre-element"
    :class="elementClasses"
    @click="selectElement"
    @dblclick="editElement"
  >
    <div class="element-icon">
      {{ iconEmoji }}
    </div>
    <div class="element-content">
      <h4 class="element-title">{{ element.name }}</h4>
      <p class="element-description" v-if="element.description">
        {{ element.description }}
      </p>
      <div class="element-meta" v-if="showMeta">
        <span v-if="element.duration" class="meta-item">
          ⏱ {{ element.duration }}s
        </span>
        <span v-if="element.dialogue" class="meta-item">
          💬 {{ element.dialogue.substring(0, 20) }}...
        </span>
      </div>
    </div>
    <div class="element-actions" v-if="showActions">
      <button @click.stop="editElement" class="action-btn edit">
        ✏️
      </button>
      <button @click.stop="deleteElement" class="action-btn delete">
        🗑️
      </button>
    </div>
  </div>
</template>

<script setup>
import { computed } from 'vue'

// Props
const props = defineProps({
  element: {
    type: Object,
    required: true
  },
  selected: {
    type: Boolean,
    default: false
  },
  showMeta: {
    type: Boolean,
    default: true
  },
  showActions: {
    type: Boolean,
    default: false
  }
})

// Emits
const emit = defineEmits(['select', 'edit', 'delete'])

// Computed
const elementClasses = computed(() => {
  const baseClasses = [
    'p-3',
    'rounded-lg',
    'border-2',
    'cursor-pointer',
    'transition-all',
    'duration-200',
    'hover:shadow-md'
  ]

  const typeClasses = {
    character: ['bg-blue-50', 'border-blue-200', 'hover:bg-blue-100'],
    scene: ['bg-green-50', 'border-green-200', 'hover:bg-green-100'],
    action: ['bg-purple-50', 'border-purple-200', 'hover:bg-purple-100'],
    prop: ['bg-yellow-50', 'border-yellow-200', 'hover:bg-yellow-100'],
    sound: ['bg-pink-50', 'border-pink-200', 'hover:bg-pink-100']
  }

  const selectedClasses = props.selected 
    ? ['ring-2', 'ring-blue-500', 'ring-opacity-50', 'shadow-lg']
    : []

  return [
    ...baseClasses,
    ...(typeClasses[props.element.type] || typeClasses.character),
    ...selectedClasses
  ]
})

const iconEmoji = computed(() => {
  const icons = {
    character: '👤',
    scene: '🎬', 
    action: '⚡',
    prop: '📦',
    sound: '🔊'
  }
  return icons[props.element.type] || '👤'
})

// Methods
const selectElement = () => {
  emit('select', props.element)
}

const editElement = () => {
  emit('edit', props.element)
}

const deleteElement = () => {
  emit('delete', props.element)
}
</script>

<style scoped>
.theatre-element {
  display: flex;
  align-items: flex-start;
  gap: 0.75rem;
  position: relative;
}

.element-icon {
  flex-shrink: 0;
  width: 2.5rem;
  height: 2.5rem;
  border-radius: 50%;
  display: flex;
  align-items: center;
  justify-content: center;
  font-size: 1.25rem;
}

.element-content {
  flex: 1;
  min-width: 0;
}

.element-title {
  font-weight: 600;
  font-size: 0.875rem;
  color: #374151;
  margin: 0 0 0.25rem 0;
}

.element-description {
  font-size: 0.75rem;
  color: #6b7280;
  margin: 0 0 0.5rem 0;
  line-height: 1.3;
}

.element-meta {
  display: flex;
  flex-wrap: wrap;
  gap: 0.5rem;
}

.meta-item {
  font-size: 0.625rem;
  color: #9ca3af;
  background: rgba(0, 0, 0, 0.05);
  padding: 0.125rem 0.375rem;
  border-radius: 0.25rem;
}

.element-actions {
  display: flex;
  gap: 0.25rem;
  opacity: 0;
  transition: opacity 0.2s;
}

.theatre-element:hover .element-actions {
  opacity: 1;
}

.action-btn {
  width: 1.5rem;
  height: 1.5rem;
  border: none;
  background: rgba(0, 0, 0, 0.1);
  border-radius: 0.25rem;
  cursor: pointer;
  display: flex;
  align-items: center;
  justify-content: center;
  font-size: 0.75rem;
  transition: background-color 0.2s;
}

.action-btn:hover {
  background: rgba(0, 0, 0, 0.2);
}

.action-btn.edit:hover {
  background: rgba(59, 130, 246, 0.2);
}

.action-btn.delete:hover {
  background: rgba(239, 68, 68, 0.2);
}
</style>
<template>
  <div class="save-status flex items-center gap-2 text-sm">
    <!-- Indicador de status -->
    <div class="flex items-center gap-1">
      <!-- Salvando -->
      <div v-if="isSaving" class="flex items-center gap-1 text-blue-600">
        <svg class="w-4 h-4 animate-spin" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
        </svg>
        <span>Salvando...</span>
      </div>
      
      <!-- Erro de salvamento -->
      <div v-else-if="saveError" class="flex items-center gap-1 text-red-600">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
        </svg>
        <span>Erro ao salvar</span>
      </div>
      
      <!-- Mudanças não salvas -->
      <div v-else-if="hasUnsavedChanges" class="flex items-center gap-1 text-yellow-600">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
        </svg>
        <span>Alterações não salvas</span>
      </div>
      
      <!-- Salvo com sucesso -->
      <div v-else class="flex items-center gap-1 text-green-600">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
        </svg>
        <span>{{ lastSavedText }}</span>
      </div>
    </div>
    
    <!-- Botão de salvamento manual -->
    <button
      v-if="showSaveButton && (hasUnsavedChanges || saveError)"
      @click="handleManualSave"
      :disabled="isSaving"
      class="px-2 py-1 text-xs bg-blue-600 text-white rounded hover:bg-blue-700 disabled:opacity-50 disabled:cursor-not-allowed transition-colors"
    >
      {{ isSaving ? 'Salvando...' : 'Salvar Agora' }}
    </button>
    
    <!-- Tooltip com detalhes do erro -->
    <div 
      v-if="saveError && showErrorDetails" 
      class="relative group"
    >
      <button class="text-red-600 hover:text-red-700">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
        </svg>
      </button>
      
      <!-- Tooltip -->
      <div class="absolute bottom-full left-1/2 transform -translate-x-1/2 mb-2 px-3 py-2 bg-gray-800 text-white text-xs rounded shadow-lg opacity-0 group-hover:opacity-100 transition-opacity pointer-events-none whitespace-nowrap z-50">
        {{ saveError }}
        <div class="absolute top-full left-1/2 transform -translate-x-1/2 border-4 border-transparent border-t-gray-800"></div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { computed } from 'vue'
import { formatLastSaved } from '../composables/useAutoSave'

// Props
const props = defineProps({
  isSaving: {
    type: Boolean,
    default: false
  },
  lastSaved: {
    type: Date,
    default: null
  },
  hasUnsavedChanges: {
    type: Boolean,
    default: false
  },
  saveError: {
    type: String,
    default: null
  },
  showSaveButton: {
    type: Boolean,
    default: true
  },
  showErrorDetails: {
    type: Boolean,
    default: true
  }
})

// Emits
const emit = defineEmits(['save'])

// Computed
const lastSavedText = computed(() => {
  return formatLastSaved(props.lastSaved)
})

// Methods
const handleManualSave = () => {
  emit('save')
}
</script>

<style scoped>
.save-status {
  user-select: none;
}

@keyframes pulse {
  0%, 100% {
    opacity: 1;
  }
  50% {
    opacity: 0.5;
  }
}

.animate-pulse {
  animation: pulse 2s cubic-bezier(0.4, 0, 0.6, 1) infinite;
}
</style>
<template>
  <div class="properties-panel" :class="{ 'actor-modal': isActorElement }">
    <div class="panel-header">
      <h3 class="panel-title">
        <span v-if="isActorElement" class="actor-icon">🎭</span>
        Propriedades
        <span v-if="isActorElement" class="actor-label">do Ator</span>
      </h3>
      <button 
        v-if="selectedElement" 
        @click="closePanel"
        class="close-btn"
      >
        ✕
      </button>
    </div>

    <!-- Botão de Salvar -->
    <div v-if="selectedElement" class="save-section">
      <button 
        @click="saveProperties" 
        class="save-btn"
        :class="{ 'has-changes': hasUnsavedChanges }"
        :disabled="!hasUnsavedChanges"
      >
        <span v-if="hasUnsavedChanges">💾 Salvar Alterações</span>
        <span v-else>✅ Salvo</span>
      </button>
    </div>

    <div v-if="!selectedElement" class="no-selection">
      <div class="no-selection-icon">🎭</div>
      <p>Selecione um elemento para editar suas propriedades</p>
    </div>

    <!-- Modal específico para Ator -->
    <div v-else-if="isActorElement" class="actor-properties-content">
      <!-- Propriedades do Ator -->
      <div class="actor-property-group">
        <h4 class="actor-group-title">📝 Informações Básicas</h4>
        
        <div class="actor-property-field">
          <label class="actor-label">Nome</label>
          <input 
            v-model="localElement.name" 
            type="text" 
            class="actor-input"
            placeholder="Digite o nome do ator..."
            @input="updateProperty('name', $event.target.value)"
          />
        </div>

        <div class="actor-property-field">
          <label class="actor-label">Descrição</label>
          <textarea 
            v-model="localElement.description" 
            class="actor-textarea"
            rows="3"
            placeholder="Descreva o personagem..."
            @input="updateProperty('description', $event.target.value)"
          ></textarea>
        </div>
      </div>

      <!-- Dimensões do Ator -->
      <div class="actor-property-group">
        <h4 class="actor-group-title">📐 Dimensões</h4>
        
        <div class="actor-property-row">
          <div class="actor-property-field">
            <label class="actor-label">Largura</label>
            <input 
              v-model.number="localElement.width" 
              type="number" 
              class="actor-input"
              placeholder="Largura"
              @input="updateProperty('width', parseFloat($event.target.value))"
            />
          </div>
          <div class="actor-property-field">
            <label class="actor-label">Altura</label>
            <input 
              v-model.number="localElement.height" 
              type="number" 
              class="actor-input"
              placeholder="Altura"
              @input="updateProperty('height', parseFloat($event.target.value))"
            />
          </div>
        </div>
      </div>

      <!-- Aparência do Ator -->
      <div class="actor-property-group">
        <h4 class="actor-group-title">🎨 Aparência</h4>
        
        <div class="actor-property-field">
          <label class="actor-label">Cor</label>
          <div class="actor-color-wrapper">
            <input 
              v-model="localElement.fill" 
              type="color" 
              class="actor-color-input"
              @input="updateProperty('fill', $event.target.value)"
            />
            <input 
              v-model="localElement.fill" 
              type="text" 
              class="actor-input"
              placeholder="#000000"
              @input="updateProperty('fill', $event.target.value)"
            />
          </div>
        </div>

        <div class="actor-property-field">
          <label class="actor-label">Opacidade</label>
          <div class="actor-range-wrapper">
            <input 
              v-model.number="localElement.opacity" 
              type="range" 
              min="0" 
              max="1" 
              step="0.1"
              class="actor-range-input"
              @input="updateProperty('opacity', parseFloat($event.target.value))"
            />
            <span class="actor-range-value">{{ Math.round((localElement.opacity || 1) * 100) }}%</span>
          </div>
        </div>
      </div>

      <!-- Diálogos do Ator -->
      <div class="actor-property-group">
        <h4 class="actor-group-title">💬 Diálogos</h4>
        
        <div class="actor-property-field">
          <label class="actor-label">Diálogo Principal</label>
          <textarea 
            v-model="localElement.dialogue" 
            class="actor-textarea"
            rows="4"
            placeholder="Digite o diálogo do personagem..."
            @input="updateProperty('dialogue', $event.target.value)"
          ></textarea>
        </div>
      </div>

      <!-- Animação do Ator -->
      <div class="actor-property-group">
        <h4 class="actor-group-title">🎬 Animação</h4>
        
        <!-- Quadros-chave com Diálogos -->
        <div class="actor-keyframes-section">
          <div class="actor-keyframes-header">
            <h5 class="actor-keyframes-title">Quadros-chave</h5>
            <button 
              @click="addKeyframeAtCurrentTime" 
              class="actor-add-keyframe-btn"
              :disabled="!localElement"
            >
              ➕ Adicionar Quadro
            </button>
          </div>
          
          <div v-if="sortedKeyframes.length > 0" class="actor-keyframes-list">
            <div 
              v-for="keyframe in sortedKeyframes" 
              :key="keyframe.id"
              class="actor-keyframe-item"
              :class="{ 'selected': selectedKeyframe?.id === keyframe.id }"
              @click="selectKeyframe(keyframe)"
            >
              <div class="actor-keyframe-info">
                <div class="actor-keyframe-time">⏱️ {{ keyframe.time }}s</div>
                <div class="actor-keyframe-dialogue">
                  <label class="actor-label">Diálogo neste quadro:</label>
                  <textarea 
                     :value="keyframe.dialogue || ''"
                     @input="updateKeyframeDialogue(keyframe.id, $event.target.value)"
                     class="actor-keyframe-textarea"
                     rows="2"
                     placeholder="Diálogo para este momento..."
                   ></textarea>
                </div>
              </div>
              <div class="actor-keyframe-actions">
                 <button @click.stop="goToKeyframe(keyframe)" class="actor-goto-btn">▶️ Ir</button>
                 <button @click.stop="deleteKeyframe(keyframe.id)" class="actor-delete-btn">🗑️</button>
               </div>
            </div>
          </div>
          
          <div v-else class="actor-no-keyframes">
            🎭 Nenhum quadro-chave definido para este ator
          </div>
        </div>

        <!-- Configurações de Animação -->
        <div class="actor-animation-settings">
          <div class="actor-property-row">
            <div class="actor-property-field">
              <label class="actor-label">Duração (s)</label>
              <input 
                v-model.number="localElement.duration" 
                type="number" 
                min="0" 
                step="0.1"
                class="actor-input"
                placeholder="1.0"
                @input="updateProperty('duration', parseFloat($event.target.value) || 1)"
              />
            </div>
            
            <div class="actor-property-field">
              <label class="actor-label">Atraso (s)</label>
              <input 
                v-model.number="localElement.delay" 
                type="number" 
                min="0" 
                step="0.1"
                class="actor-input"
                placeholder="0.0"
                @input="updateProperty('delay', parseFloat($event.target.value) || 0)"
              />
            </div>
          </div>
          
          <div class="actor-property-field">
            <label class="actor-label">Tipo de Animação</label>
            <select 
              v-model="localElement.easing" 
              class="actor-select"
              @change="updateProperty('easing', $event.target.value)"
            >
              <option value="none">🔄 Nenhum</option>
              <option value="power1.out">🌊 Suave</option>
              <option value="power2.out">⚡ Médio</option>
              <option value="power3.out">🚀 Forte</option>
              <option value="back.out(1.7)">🎯 Elástico</option>
              <option value="bounce.out">🏀 Quique</option>
            </select>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, computed, watch, onMounted } from 'vue'

// Props
const props = defineProps({
  selectedElement: {
    type: Object,
    default: null
  }
})

// Emits
const emit = defineEmits(['update', 'close'])

// Data
const localElement = ref({})
const hasUnsavedChanges = ref(false)
const selectedKeyframe = ref(null)
const currentTime = ref(0)

// Computed
const sortedKeyframes = computed(() => {
  if (!localElement.value.keyframes) return []
  return [...localElement.value.keyframes].sort((a, b) => a.time - b.time)
})

const typeSpecificTitle = computed(() => {
  const titles = {
    character: 'Personagem',
    scene: 'Cenário',
    action: 'Ação',
    prop: 'Objeto',
    sound: 'Som'
  }
  return titles[localElement.value.type] || 'Específico'
})

const isActorElement = computed(() => {
  return localElement.value.type === 'character'
})

const typeSpecificProperties = computed(() => {
  if (!localElement.value.type) return []

  const properties = {
    character: [
      {
        key: 'actorName',
        label: 'Nome do Ator',
        component: 'input',
        props: { type: 'text', class: 'property-input', placeholder: 'Digite o nome do ator...' }
      },
      {
        key: 'contentType',
        label: 'Tipo de Conteúdo',
        component: 'select',
        props: { 
          class: 'property-select',
          options: [
            { value: 'text', text: 'Texto' },
            { value: 'icon', text: 'Ícone' },
            { value: 'shape', text: 'Forma' },
            { value: 'text-icon', text: 'Texto + Ícone' },
            { value: 'text-shape', text: 'Texto + Forma' }
          ]
        }
      },
      {
        key: 'textContent',
        label: 'Texto',
        component: 'input',
        props: { type: 'text', class: 'property-input', placeholder: 'Digite o texto...' },
        show: ['text', 'text-icon', 'text-shape'].includes(localElement.value.contentType)
      },
      {
        key: 'iconContent',
        label: 'Ícone',
        component: 'select',
        props: { 
          class: 'property-select',
          options: [
            { value: '👤', text: '👤 Pessoa' },
            { value: '🎭', text: '🎭 Teatro' },
            { value: '👑', text: '👑 Rei/Rainha' },
            { value: '🧙', text: '🧙 Mago' },
            { value: '🦸', text: '🦸 Herói' },
            { value: '👮', text: '👮 Policial' },
            { value: '👨‍⚕️', text: '👨‍⚕️ Médico' },
            { value: '👩‍🎓', text: '👩‍🎓 Estudante' }
          ]
        },
        show: ['icon', 'text-icon'].includes(localElement.value.contentType)
      },
      {
        key: 'shapeType',
        label: 'Forma',
        component: 'select',
        props: { 
          class: 'property-select',
          options: [
            { value: 'circle', text: 'Círculo' },
            { value: 'rectangle', text: 'Retângulo' },
            { value: 'triangle', text: 'Triângulo' },
            { value: 'star', text: 'Estrela' },
            { value: 'diamond', text: 'Diamante' }
          ]
        },
        show: ['shape', 'text-shape'].includes(localElement.value.contentType)
      },
      {
        key: 'dialogue',
        label: 'Diálogo',
        component: 'textarea',
        props: { rows: 3, class: 'property-textarea', placeholder: 'Fala do personagem...' }
      }
    ],
    scene: [
      {
        key: 'contentType',
        label: 'Tipo de Conteúdo',
        component: 'select',
        props: { 
          class: 'property-select',
          options: [
            { value: 'text', text: 'Texto' },
            { value: 'icon', text: 'Ícone' },
            { value: 'shape', text: 'Forma' },
            { value: 'text-icon', text: 'Texto + Ícone' },
            { value: 'text-shape', text: 'Texto + Forma' }
          ]
        }
      },
      {
        key: 'textContent',
        label: 'Texto',
        component: 'input',
        props: { type: 'text', class: 'property-input', placeholder: 'Nome do cenário...' },
        show: ['text', 'text-icon', 'text-shape'].includes(localElement.value.contentType)
      },
      {
        key: 'iconContent',
        label: 'Ícone',
        component: 'select',
        props: { 
          class: 'property-select',
          options: [
            { value: '🏠', text: '🏠 Casa' },
            { value: '🏰', text: '🏰 Castelo' },
            { value: '🌳', text: '🌳 Floresta' },
            { value: '🏔️', text: '🏔️ Montanha' },
            { value: '🏖️', text: '🏖️ Praia' },
            { value: '🏙️', text: '🏙️ Cidade' },
            { value: '🌌', text: '🌌 Espaço' },
            { value: '🎪', text: '🎪 Circo' }
          ]
        },
        show: ['icon', 'text-icon'].includes(localElement.value.contentType)
      },
      {
        key: 'shapeType',
        label: 'Forma',
        component: 'select',
        props: { 
          class: 'property-select',
          options: [
            { value: 'rectangle', text: 'Retângulo' },
            { value: 'circle', text: 'Círculo' },
            { value: 'triangle', text: 'Triângulo' },
            { value: 'hexagon', text: 'Hexágono' }
          ]
        },
        show: ['shape', 'text-shape'].includes(localElement.value.contentType)
      },
      {
        key: 'lighting',
        label: 'Iluminação',
        component: 'select',
        props: { 
          class: 'property-select',
          options: [
            { value: 'bright', text: 'Claro' },
            { value: 'dim', text: 'Escuro' },
            { value: 'spotlight', text: 'Holofote' },
            { value: 'colored', text: 'Colorido' }
          ]
        }
      }
    ],
    prop: [
      {
        key: 'contentType',
        label: 'Tipo de Conteúdo',
        component: 'select',
        props: { 
          class: 'property-select',
          options: [
            { value: 'text', text: 'Texto' },
            { value: 'icon', text: 'Ícone' },
            { value: 'shape', text: 'Forma' },
            { value: 'text-icon', text: 'Texto + Ícone' },
            { value: 'text-shape', text: 'Texto + Forma' }
          ]
        }
      },
      {
        key: 'textContent',
        label: 'Texto',
        component: 'input',
        props: { type: 'text', class: 'property-input', placeholder: 'Nome do objeto...' },
        show: ['text', 'text-icon', 'text-shape'].includes(localElement.value.contentType)
      },
      {
        key: 'iconContent',
        label: 'Ícone',
        component: 'select',
        props: { 
          class: 'property-select',
          options: [
            { value: '📦', text: '📦 Caixa' },
            { value: '⚔️', text: '⚔️ Espada' },
            { value: '🔑', text: '🔑 Chave' },
            { value: '💎', text: '💎 Joia' },
            { value: '📚', text: '📚 Livros' },
            { value: '🪑', text: '🪑 Cadeira' },
            { value: '🚗', text: '🚗 Carro' },
            { value: '⚽', text: '⚽ Bola' }
          ]
        },
        show: ['icon', 'text-icon'].includes(localElement.value.contentType)
      },
      {
        key: 'shapeType',
        label: 'Forma',
        component: 'select',
        props: { 
          class: 'property-select',
          options: [
            { value: 'rectangle', text: 'Retângulo' },
            { value: 'circle', text: 'Círculo' },
            { value: 'triangle', text: 'Triângulo' },
            { value: 'star', text: 'Estrela' },
            { value: 'diamond', text: 'Diamante' }
          ]
        },
        show: ['shape', 'text-shape'].includes(localElement.value.contentType)
      }
    ],
    action: [
      {
        key: 'actionType',
        label: 'Tipo de Ação',
        component: 'select',
        props: { 
          class: 'property-select',
          options: [
            { value: 'move', text: 'Movimento' },
            { value: 'gesture', text: 'Gesto' },
            { value: 'interaction', text: 'Interação' },
            { value: 'effect', text: 'Efeito' }
          ]
        }
      }
    ],
    sound: [
      {
        key: 'soundType',
        label: 'Tipo de Som',
        component: 'select',
        props: { 
          class: 'property-select',
          options: [
            { value: 'music', text: 'Música' },
            { value: 'effect', text: 'Efeito Sonoro' },
            { value: 'ambient', text: 'Ambiente' },
            { value: 'voice', text: 'Voz' }
          ]
        }
      },
      {
        key: 'volume',
        label: 'Volume',
        component: 'input',
        props: { type: 'range', min: 0, max: 100, class: 'range-input' }
      }
    ]
  }

  return properties[localElement.value.type] || []
})

// Watch - Fixed to prevent infinite loops
watch(() => props.selectedElement, (newElement, oldElement) => {
  // Only update if the element actually changed (not just a property update)
  if (newElement && (!oldElement || newElement.id !== oldElement?.id)) {
    localElement.value = { ...newElement }
    selectedKeyframe.value = null
    hasUnsavedChanges.value = false
  } else if (!newElement) {
    localElement.value = {}
    selectedKeyframe.value = null
    hasUnsavedChanges.value = false
  }
})

// Removed debounce utility as we now use manual save

// Helper function to extract initials from actor name
const getActorInitials = (actorName) => {
  if (!actorName || typeof actorName !== 'string') return ''
  
  // For "Ator 1", "Ator 2", etc., return "A1", "A2", etc.
  const actorMatch = actorName.match(/^Ator\s+(\d+)$/i)
  if (actorMatch) {
    return `A${actorMatch[1]}`
  }
  
  // For other names, extract initials normally
  return actorName
    .trim()
    .split(' ')
    .filter(name => name.length > 0)
    .map(name => name.charAt(0).toUpperCase())
    .join('')
    .substring(0, 3) // Máximo 3 iniciais
}

// Methods
const updateProperty = (key, value) => {
  localElement.value[key] = value
  
  // If updating the name of a character, also update actorName
  if (key === 'name' && localElement.value.type === 'character') {
    localElement.value.actorName = value
  }
  
  // If we have a selected keyframe, update its properties
  if (selectedKeyframe.value && ['x', 'y', 'width', 'height', 'rotation', 'opacity', 'fill'].includes(key)) {
    selectedKeyframe.value.properties[key] = value
  }
  
  // Mark as having unsaved changes
  hasUnsavedChanges.value = true
}

const saveProperties = () => {
  emit('update', { ...localElement.value })
  hasUnsavedChanges.value = false
}

const addKeyframeAtCurrentTime = () => {
  if (!localElement.value) return
  
  // Initialize keyframes array if it doesn't exist
  if (!localElement.value.keyframes) {
    localElement.value.keyframes = []
  }
  
  // Get current element properties
  const currentProperties = {
    x: localElement.value.x || 0,
    y: localElement.value.y || 0,
    width: localElement.value.width || 100,
    height: localElement.value.height || 100,
    rotation: localElement.value.rotation || 0,
    opacity: localElement.value.opacity || 1,
    fill: localElement.value.fill || '#3b82f6'
  }
  
  // Check if keyframe already exists at current time
  const existingKeyframe = localElement.value.keyframes.find(kf => Math.abs(kf.time - currentTime.value) < 0.1)
  
  if (existingKeyframe) {
    // Update existing keyframe
    existingKeyframe.properties = { ...currentProperties }
    selectedKeyframe.value = existingKeyframe
  } else {
    // Add new keyframe
    const newKeyframe = {
      id: Date.now() + Math.random(),
      time: currentTime.value,
      properties: { ...currentProperties }
    }
    
    localElement.value.keyframes.push(newKeyframe)
    localElement.value.keyframes.sort((a, b) => a.time - b.time)
    selectedKeyframe.value = newKeyframe
  }
  
  // If this is the first keyframe and time > 0, add initial keyframe at time 0
  if (localElement.value.keyframes.length === 1 && localElement.value.keyframes[0].time > 0) {
    const initialKeyframe = {
      id: Date.now() + Math.random() + 1,
      time: 0,
      properties: { ...currentProperties }
    }
    localElement.value.keyframes.unshift(initialKeyframe)
  }
  
  emit('update', { ...localElement.value })
}

const selectKeyframe = (keyframe) => {
  selectedKeyframe.value = keyframe
  
  // Update current time to keyframe time
  currentTime.value = keyframe.time
  
  // Update element properties to keyframe properties
  Object.assign(localElement.value, keyframe.properties)
  
  emit('update', { ...localElement.value })
}

const goToKeyframe = (keyframe) => {
  selectKeyframe(keyframe)
  
  // Emit event to move timeline to this time
  emit('go-to-time', keyframe.time)
}

const deleteKeyframe = (keyframe) => {
  if (!localElement.value.keyframes) return
  
  const index = localElement.value.keyframes.findIndex(kf => kf.id === keyframe.id)
  if (index > -1) {
    localElement.value.keyframes.splice(index, 1)
    
    // Clear selection if deleted keyframe was selected
    if (selectedKeyframe.value?.id === keyframe.id) {
      selectedKeyframe.value = null
    }
    
    emit('update', { ...localElement.value })
  }
}

const updateKeyframeDialogue = (keyframe, dialogue) => {
  if (!keyframe) return
  
  keyframe.dialogue = dialogue
  emit('update', { ...localElement.value })
}

const closePanel = () => {
  emit('close')
}

// Initialize localElement on mount
onMounted(() => {
  if (props.selectedElement) {
    localElement.value = { ...props.selectedElement }
  }
  hasUnsavedChanges.value = false
})
</script>

<style scoped>
.properties-panel {
  height: 100%;
  display: flex;
  flex-direction: column;
  background: white;
  border-left: 1px solid #e5e7eb;
}

/* Modal específico para o Ator */
.properties-panel.actor-modal {
  width: 450px;
  background: linear-gradient(135deg, #f8f9fa 0%, #ffffff 100%);
  border-left: 3px solid #007bff;
  box-shadow: -2px 0 20px rgba(0, 123, 255, 0.1);
}

.actor-icon {
  margin-right: 0.5rem;
  font-size: 1.2em;
}

.actor-label {
  color: #007bff;
  font-weight: 600;
}

.actor-properties-content {
  flex: 1;
  overflow-y: auto;
  padding: 1.5rem;
}

.actor-property-group {
  margin-bottom: 2rem;
  background: white;
  border-radius: 12px;
  padding: 1.5rem;
  box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
  border: 1px solid #e9ecef;
}

.actor-group-title {
  font-size: 1rem;
  font-weight: 700;
  color: #495057;
  margin: 0 0 1.25rem 0;
  padding-bottom: 0.75rem;
  border-bottom: 2px solid #e9ecef;
  display: flex;
  align-items: center;
  gap: 0.5rem;
}

.actor-property-field {
  margin-bottom: 1.25rem;
}

.actor-property-row {
  display: grid;
  grid-template-columns: 1fr 1fr;
  gap: 1rem;
}

.actor-property-field .actor-label {
  display: block;
  font-size: 0.875rem;
  font-weight: 600;
  color: #495057;
  margin-bottom: 0.5rem;
}

.actor-input,
.actor-select,
.actor-textarea {
  width: 100%;
  padding: 0.75rem;
  border: 2px solid #dee2e6;
  border-radius: 8px;
  font-size: 0.875rem;
  transition: all 0.3s ease;
  background: white;
}

.actor-input:focus,
.actor-select:focus,
.actor-textarea:focus {
  outline: none;
  border-color: #007bff;
  box-shadow: 0 0 0 3px rgba(0, 123, 255, 0.1);
  transform: translateY(-1px);
}

.actor-color-wrapper {
  display: flex;
  gap: 0.75rem;
  align-items: center;
}

.actor-color-input {
  width: 4rem;
  height: 3rem;
  padding: 0;
  border: 2px solid #dee2e6;
  border-radius: 8px;
  cursor: pointer;
}

.actor-range-wrapper {
  display: flex;
  align-items: center;
  gap: 1rem;
}

.actor-range-input {
  flex: 1;
  height: 6px;
  background: #dee2e6;
  border-radius: 3px;
  outline: none;
  -webkit-appearance: none;
}

.actor-range-input::-webkit-slider-thumb {
  -webkit-appearance: none;
  width: 20px;
  height: 20px;
  background: #007bff;
  border-radius: 50%;
  cursor: pointer;
  box-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
}

.actor-range-value {
  font-size: 0.875rem;
  color: #6c757d;
  min-width: 3rem;
  text-align: right;
  font-weight: 600;
}

.actor-keyframes-section {
  margin-bottom: 1.5rem;
}

.actor-keyframes-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-bottom: 1rem;
}

.actor-keyframes-title {
  font-size: 1rem;
  font-weight: 600;
  color: #495057;
  margin: 0;
}

.actor-add-keyframe-btn {
  padding: 0.5rem 1rem;
  background: linear-gradient(135deg, #6b7280, #9ca3af);
  color: white;
  border: none;
  border-radius: 8px;
  font-size: 0.875rem;
  font-weight: 600;
  cursor: pointer;
  transition: all 0.3s ease;
  box-shadow: 0 2px 4px rgba(107, 114, 128, 0.3);
}

.actor-add-keyframe-btn:hover {
  transform: translateY(-2px);
  box-shadow: 0 4px 8px rgba(107, 114, 128, 0.4);
}

.actor-add-keyframe-btn:disabled {
  opacity: 0.6;
  cursor: not-allowed;
  transform: none;
}

.actor-keyframes-list {
  max-height: 300px;
  overflow-y: auto;
  border: 2px solid #e9ecef;
  border-radius: 12px;
  background: white;
}

.actor-keyframe-item {
  padding: 1rem;
  border-bottom: 1px solid #f8f9fa;
  cursor: pointer;
  transition: all 0.3s ease;
}

.actor-keyframe-item:last-child {
  border-bottom: none;
}

.actor-keyframe-item:hover {
  background: #f8f9fa;
  transform: translateX(4px);
}

.actor-keyframe-item.selected {
  background: linear-gradient(135deg, #f9fafb, #f3f4f6);
  border-left: 4px solid #6b7280;
}

.actor-keyframe-info {
  flex: 1;
}

.actor-keyframe-time {
  font-weight: 700;
  color: #495057;
  font-size: 0.875rem;
  margin-bottom: 0.5rem;
}

.actor-keyframe-dialogue {
  margin-top: 0.75rem;
}

.actor-keyframe-textarea {
  width: 100%;
  padding: 0.5rem;
  border: 1px solid #dee2e6;
  border-radius: 6px;
  font-size: 0.8rem;
  resize: vertical;
  min-height: 60px;
}

.actor-keyframe-actions {
  display: flex;
  gap: 0.5rem;
  margin-top: 0.75rem;
}

.actor-goto-btn,
.actor-delete-btn {
  padding: 0.375rem 0.75rem;
  border: none;
  border-radius: 6px;
  cursor: pointer;
  font-size: 0.75rem;
  font-weight: 600;
  transition: all 0.3s ease;
}

.actor-goto-btn {
  background: linear-gradient(135deg, #6b7280, #9ca3af);
  color: white;
}

.actor-goto-btn:hover {
  transform: translateY(-1px);
  box-shadow: 0 2px 4px rgba(107, 114, 128, 0.3);
}

.actor-delete-btn {
  background: linear-gradient(135deg, #374151, #1f2937);
  color: white;
}

.actor-delete-btn:hover {
  transform: translateY(-1px);
  box-shadow: 0 2px 4px rgba(55, 65, 81, 0.3);
}

.actor-no-keyframes {
  text-align: center;
  padding: 2rem;
  color: #6c757d;
  font-style: italic;
}

.actor-animation-settings {
  margin-top: 1.5rem;
  padding-top: 1.5rem;
  border-top: 2px solid #e9ecef;
}

.panel-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  padding: 1rem;
  border-bottom: 1px solid #e5e7eb;
  background: #f9fafb;
}

.panel-title {
  font-size: 1.125rem;
  font-weight: 600;
  color: #374151;
  margin: 0;
}

.close-btn {
  width: 2rem;
  height: 2rem;
  border: none;
  background: #e5e7eb;
  border-radius: 0.375rem;
  cursor: pointer;
  display: flex;
  align-items: center;
  justify-content: center;
  color: #6b7280;
  transition: all 0.2s;
}

.close-btn:hover {
  background: #d1d5db;
  color: #374151;
}

.no-selection {
  flex: 1;
  display: flex;
  flex-direction: column;
  align-items: center;
  justify-content: center;
  padding: 2rem;
  text-align: center;
  color: #6b7280;
}

.no-selection-icon {
  font-size: 3rem;
  margin-bottom: 1rem;
  opacity: 0.5;
}

.properties-content {
  flex: 1;
  overflow-y: auto;
  padding: 1rem;
}

.property-group {
  margin-bottom: 1.5rem;
}

.group-title {
  font-size: 0.875rem;
  font-weight: 600;
  color: #374151;
  margin: 0 0 0.75rem 0;
  padding-bottom: 0.5rem;
  border-bottom: 1px solid #e5e7eb;
}

.property-field {
  margin-bottom: 1rem;
}

.property-row {
  display: grid;
  grid-template-columns: 1fr 1fr;
  gap: 0.75rem;
}

.property-field label {
  display: block;
  font-size: 0.75rem;
  font-weight: 500;
  color: #374151;
  margin-bottom: 0.25rem;
}

.property-input,
.property-select,
.property-textarea {
  width: 100%;
  padding: 0.5rem;
  border: 1px solid #d1d5db;
  border-radius: 0.375rem;
  font-size: 0.875rem;
  transition: border-color 0.2s;
}

.property-input:focus,
.property-select:focus,
.property-textarea:focus {
  outline: none;
  border-color: #3b82f6;
  box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
}

.color-input-wrapper {
  display: flex;
  gap: 0.5rem;
  align-items: center;
}

.color-input {
  width: 3rem;
  height: 2.5rem;
  padding: 0;
  border: 1px solid #d1d5db;
  border-radius: 0.375rem;
  cursor: pointer;
}

.range-input-wrapper {
  display: flex;
  align-items: center;
  gap: 0.75rem;
}

.range-input {
  flex: 1;
}

.range-value {
  font-size: 0.875rem;
  color: #6b7280;
  min-width: 3rem;
  text-align: right;
}

/* Keyframes Styles */
.keyframes-section {
  margin-bottom: 1rem;
}

.keyframes-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-bottom: 0.75rem;
}

.keyframes-title {
  font-size: 0.875rem;
  font-weight: 600;
  color: #374151;
  margin: 0;
}

.add-keyframe-btn {
  padding: 0.375rem 0.75rem;
  background: #8b5cf6;
  color: white;
  border: none;
  border-radius: 0.375rem;
  font-size: 0.75rem;
  font-weight: 500;
  cursor: pointer;
  transition: all 0.2s;
}

.add-keyframe-btn:hover {
  background: #7c3aed;
  transform: translateY(-1px);
}

.keyframes-list {
  max-height: 200px;
  overflow-y: auto;
  border: 1px solid #e5e7eb;
  border-radius: 0.375rem;
}

.keyframe-item {
  display: flex;
  justify-content: space-between;
  align-items: center;
  padding: 0.75rem;
  border-bottom: 1px solid #f3f4f6;
  cursor: pointer;
  transition: all 0.2s;
}

.keyframe-item:last-child {
  border-bottom: none;
}

.keyframe-item:hover {
  background: #f9fafb;
}

.keyframe-item.active {
  background: #eff6ff;
  border-color: #3b82f6;
}

.keyframe-info {
  flex: 1;
}

.keyframe-time {
  display: block;
  font-weight: 600;
  color: #374151;
  font-size: 0.875rem;
}

.keyframe-properties {
  display: block;
  font-size: 0.75rem;
  color: #6b7280;
  margin-top: 0.25rem;
}

.keyframe-actions {
  display: flex;
  gap: 0.25rem;
}

.keyframe-action-btn {
  padding: 0.25rem;
  background: transparent;
  border: 1px solid #d1d5db;
  border-radius: 0.25rem;
  cursor: pointer;
  font-size: 0.75rem;
  transition: all 0.2s;
}

.keyframe-action-btn:hover {
  background: #f3f4f6;
  border-color: #9ca3af;
}

.keyframe-action-btn.delete:hover {
  background: #fee2e2;
  border-color: #dc2626;
}

.no-keyframes {
  text-align: center;
  padding: 2rem 1rem;
  color: #6b7280;
}

.no-keyframes p {
  margin: 0 0 0.5rem 0;
  font-size: 0.875rem;
}

.no-keyframes small {
  font-size: 0.75rem;
  opacity: 0.8;
}

.legacy-animation {
  margin-top: 1rem;
  padding-top: 1rem;
  border-top: 1px solid #e5e7eb;
}

/* Save Section Styles */
.save-section {
  padding: 1rem;
  border-bottom: 1px solid #e5e7eb;
  background: #f9fafb;
}

.save-btn {
  width: 100%;
  padding: 0.75rem 1rem;
  border: 2px solid #d1d5db;
  border-radius: 0.5rem;
  background: #f3f4f6;
  color: #6b7280;
  font-weight: 500;
  cursor: not-allowed;
  transition: all 0.2s ease;
  display: flex;
  align-items: center;
  justify-content: center;
  gap: 0.5rem;
}

.save-btn:disabled {
  opacity: 0.6;
}

.save-btn.has-changes {
  background: #3b82f6;
  color: white;
  border-color: #2563eb;
  cursor: pointer;
  box-shadow: 0 2px 4px rgba(59, 130, 246, 0.2);
}

.save-btn.has-changes:hover {
  background: #2563eb;
  border-color: #1d4ed8;
  transform: translateY(-1px);
  box-shadow: 0 4px 8px rgba(59, 130, 246, 0.3);
}

.save-btn.has-changes:active {
  transform: translateY(0);
  box-shadow: 0 2px 4px rgba(59, 130, 246, 0.2);
}
</style>
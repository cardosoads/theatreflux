<template>
  <div class="animation-timeline">
    <div class="timeline-header">
      <div class="timeline-header-left">
        <div class="timeline-icon">
          <svg width="14" height="14" viewBox="0 0 24 24" fill="none">
            <path d="M8 5v14l11-7z" fill="currentColor"/>
          </svg>
        </div>
        <div class="timeline-title-section">
          <h3 class="timeline-title">Timeline de Animação</h3>
          <p class="timeline-subtitle">{{ elements.length }} elementos • {{ formatTime(totalDuration) }}</p>
        </div>
      </div>
      <div class="timeline-controls">
        <div class="control-group">
          <button 
            class="timeline-btn"
            :class="{ active: isPlaying }"
            @click="togglePlayback"
          >
            <svg width="12" height="12" viewBox="0 0 24 24" fill="none">
              <path v-if="!isPlaying" d="M8 5v14l11-7z" fill="currentColor"/>
              <path v-else d="M6 19h4V5H6v14zm8-14v14h4V5h-4z" fill="currentColor"/>
            </svg>
            {{ isPlaying ? 'Pausar' : 'Reproduzir' }}
          </button>
          <button 
            class="timeline-btn"
            @click="stopPlayback"
          >
            <svg width="12" height="12" viewBox="0 0 24 24" fill="none">
              <rect x="6" y="6" width="12" height="12" fill="currentColor"/>
            </svg>
            Parar
          </button>
        </div>
        <div class="control-group">
          <button 
            class="timeline-btn success"
            @click="addKeyframe"
            :disabled="!selectedElement"
          >
            <svg width="12" height="12" viewBox="0 0 24 24" fill="none">
              <path d="M12 2L15.09 8.26L22 9L15.09 9.74L12 16L8.91 9.74L2 9L8.91 8.26L12 2Z" fill="currentColor"/>
            </svg>
            Adicionar Keyframe
          </button>
        </div>
      </div>
    </div>

    <div class="timeline-content">
      <!-- Ruler -->
      <div class="timeline-ruler">
        <div class="ruler-track">
          <div 
            v-for="second in totalSeconds" 
            :key="second"
            class="ruler-mark"
            :style="{ left: `${(second / totalSeconds) * 100}%` }"
            :class="{ 'major': second % 5 === 0, 'minor': second % 5 !== 0 }"
          >
            <span v-if="second % 5 === 0" class="ruler-label">{{ formatTime(second) }}</span>
          </div>
        </div>
        
        <!-- Time indicator -->
        <div class="time-indicator">
          <span class="current-time">{{ formatTime(currentTime) }}</span>
        </div>
        
        <!-- Playhead -->
        <div 
          class="playhead"
          :style="{ left: `${(currentTime / totalDuration) * 100}%` }"
          @mousedown="startDraggingPlayhead"
        >
          <div class="playhead-handle"></div>
          <div class="playhead-line"></div>
        </div>
      </div>

      <!-- Tracks -->
      <div class="timeline-tracks">
        <!-- Empty state -->
        <div v-if="elements.length === 0" class="empty-timeline">
          <div class="empty-icon">🎬</div>
          <div class="empty-title">Timeline vazia</div>
          <div class="empty-description">Adicione elementos ao canvas para começar a criar animações</div>
        </div>
        
        <div 
          v-for="element in elements" 
          :key="element.id"
          class="timeline-track"
          :class="{ 'selected': selectedElement?.id === element.id }"
        >
          <div class="track-header">
          <div class="track-icon" :style="{ backgroundColor: getElementColor(element.type) }">
             <svg width="8" height="8" viewBox="0 0 24 24" fill="none">
              <path v-if="element.type === 'character'" d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z" fill="currentColor"/>
              <path v-else-if="element.type === 'scene'" d="M14 6l-3.75 5 2.85 3.8-1.6 1.2C9.81 13.75 7 10 7 10l-6 8h22L14 6z" fill="currentColor"/>
              <path v-else-if="element.type === 'action'" d="M13 3c-4.97 0-9 4.03-9 9H1l3.89 3.89.07.14L9 12H6c0-3.87 3.13-7 7-7s7 3.13 7 7-3.13 7-7 7c-1.93 0-3.68-.79-4.94-2.06l-1.42 1.42C8.27 19.99 10.51 21 13 21c4.97 0 9-4.03 9-9s-4.03-9-9-9z" fill="currentColor"/>
              <path v-else-if="element.type === 'prop'" d="M12 2l3.09 6.26L22 9l-6.91 1.01L12 16l-3.09-5.99L2 9l6.91-1.74L12 2z" fill="currentColor"/>
              <path v-else-if="element.type === 'sound'" d="M3 9v6h4l5 5V4L7 9H3zm13.5 3c0-1.77-1.02-3.29-2.5-4.03v8.05c1.48-.73 2.5-2.25 2.5-4.02zM14 3.23v2.06c2.89.86 5 3.54 5 6.71s-2.11 5.85-5 6.71v2.06c4.01-.91 7-4.49 7-8.77s-2.99-7.86-7-8.77z" fill="currentColor"/>
              <circle v-else cx="12" cy="12" r="10" fill="currentColor"/>
            </svg>
          </div>
          <div class="track-info">
            <h4 class="track-name">{{ element.name }}</h4>
            <div class="track-type">{{ getElementTypeName(element.type) }}</div>
          </div>
        </div>
          
          <div class="track-content">
            <!-- Element duration block -->
            <div 
              v-if="element.keyframes && element.keyframes.length > 0"
              class="element-duration-block"
              :style="getElementTimelineStyle(element)"
              :class="{ 'selected': props.selectedElement === element }"
              @click="emit('select-element', element)"
            >
              <div class="duration-bar" :style="{ backgroundColor: getElementColor(element.type) }"></div>
            </div>
            
            <!-- Keyframes -->
            <div 
              v-for="(keyframe, index) in element.keyframes || []"
              :key="`keyframe-${index}`"
              class="keyframe-marker"
              :style="getKeyframeStyle(keyframe)"
              @click="selectKeyframe(element, keyframe, index)"
              @mousedown="startDraggingKeyframe(element, keyframe, index, $event)"
              :class="{ 'selected': selectedKeyframe?.element === element && selectedKeyframe?.index === index }"
              :title="`Keyframe em ${formatTime(keyframe.time)}`"
            >
              <div class="keyframe-diamond" :style="{ backgroundColor: getElementColor(element.type) }">
                <svg width="12" height="12" viewBox="0 0 24 24" fill="none">
                  <path d="M12 2L15.09 8.26L22 9L15.09 9.74L12 16L8.91 9.74L2 9L8.91 8.26L12 2Z" fill="white"/>
                </svg>
              </div>
            </div>

            <!-- Animation blocks (legacy) -->
            <div 
              v-for="(animation, index) in element.animations || []"
              :key="index"
              class="animation-block"
              :style="getAnimationBlockStyle(animation)"
              @click="selectAnimation(element, animation, index)"
              @mousedown="startDraggingBlock(element, animation, index, $event)"
            >
              <div class="block-content">
                <span class="block-label">{{ animation.property || 'Animação' }}</span>
                <span class="block-duration">{{ animation.duration }}s</span>
              </div>
              
              <!-- Resize handles -->
              <div class="resize-handle left" @mousedown.stop="startResizing(element, animation, index, 'left', $event)"></div>
              <div class="resize-handle right" @mousedown.stop="startResizing(element, animation, index, 'right', $event)"></div>
            </div>
            
            <!-- Add animation button -->
            <button 
              class="add-animation-btn"
              @click="addAnimation(element)"
              :style="{ left: `${getElementEndPosition(element)}%` }"
            >
              +
            </button>
          </div>
        </div>
      </div>
    </div>

    <!-- Timeline footer with zoom controls -->
    <div class="timeline-footer">
      <div class="zoom-controls">
        <button @click="zoomOut" class="zoom-btn">🔍-</button>
        <span class="zoom-level">{{ Math.round(zoomLevel * 100) }}%</span>
        <button @click="zoomIn" class="zoom-btn">🔍+</button>
      </div>
      
      <div class="timeline-info">
        <span>Duração Total: {{ totalDuration }}s</span>
        <span>Tempo Atual: {{ currentTime.toFixed(1) }}s</span>
      </div>
      
      <div class="keyframe-info" v-if="selectedKeyframe">
        <span>Keyframe selecionado: {{ selectedKeyframe.time }}s</span>
        <button @click="deleteKeyframe" class="delete-keyframe-btn">🗑️ Deletar</button>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, computed, onMounted, onUnmounted } from 'vue'
import { gsap } from 'gsap'

// Props
const props = defineProps({
  elements: {
    type: Array,
    default: () => []
  },
  selectedElement: {
    type: Object,
    default: null
  }
})

// Emits
const emit = defineEmits(['select-element', 'update-element', 'play', 'pause', 'stop'])

// Data
const isPlaying = ref(false)
const currentTime = ref(0)
const zoomLevel = ref(1)
const timeline = ref(null)
const isDragging = ref(false)
const dragData = ref(null)
const selectedKeyframe = ref(null)

// Computed
const totalDuration = computed(() => {
  let maxDuration = 10 // Minimum 10 seconds
  
  props.elements.forEach(element => {
    // Check keyframes
    if (element.keyframes) {
      element.keyframes.forEach(keyframe => {
        if (keyframe.time > maxDuration) {
          maxDuration = keyframe.time
        }
      })
    }
    
    // Check legacy animations
    if (element.animations) {
      element.animations.forEach(animation => {
        const endTime = (animation.delay || 0) + (animation.duration || 1)
        if (endTime > maxDuration) {
          maxDuration = endTime
        }
      })
    }
  })
  
  return Math.ceil(maxDuration)
})

const totalSeconds = computed(() => {
  return Math.ceil(totalDuration.value / zoomLevel.value)
})

// Methods
const getElementIcon = (type) => {
  const icons = {
    character: '👤',
    scene: '🎬',
    action: '⚡',
    prop: '📦',
    sound: '🔊'
  }
  return icons[type] || '📄'
}

const getElementTypeName = (type) => {
  const names = {
    character: 'Personagem',
    scene: 'Cenário',
    action: 'Ação',
    prop: 'Objeto',
    sound: 'Som'
  }
  return names[type] || 'Elemento'
}

const getElementColor = (type) => {
  const colors = {
    character: '#3b82f6',
    scene: '#10b981',
    action: '#8b5cf6',
    prop: '#f59e0b',
    sound: '#ef4444'
  }
  return colors[type] || '#6b7280'
}

const formatTime = (time) => {
  const minutes = Math.floor(time / 60)
  const seconds = Math.floor(time % 60)
  const milliseconds = Math.floor((time % 1) * 100)
  
  if (minutes > 0) {
    return `${minutes}:${seconds.toString().padStart(2, '0')}.${milliseconds.toString().padStart(2, '0')}`
  } else {
    return `${seconds}.${milliseconds.toString().padStart(2, '0')}s`
  }
}

const getKeyframeStyle = (keyframe) => {
  const leftPercent = (keyframe.time / totalDuration.value) * 100
  
  return {
    left: `${leftPercent}%`
  }
}

const getAnimationBlockStyle = (animation) => {
  const startPercent = ((animation.delay || 0) / totalDuration.value) * 100
  const widthPercent = ((animation.duration || 1) / totalDuration.value) * 100
  
  return {
    left: `${startPercent}%`,
    width: `${widthPercent}%`
  }
}

const getElementEndPosition = (element) => {
  let maxEnd = 0
  
  // Check keyframes
  if (element.keyframes && element.keyframes.length > 0) {
    element.keyframes.forEach(keyframe => {
      if (keyframe.time > maxEnd) {
        maxEnd = keyframe.time
      }
    })
  }
  
  // Check legacy animations
  if (element.animations && element.animations.length > 0) {
    element.animations.forEach(animation => {
      const end = (animation.delay || 0) + (animation.duration || 1)
      if (end > maxEnd) {
        maxEnd = end
      }
    })
  }
  
  return (maxEnd / totalDuration.value) * 100
}

const getElementTimelineStyle = (element) => {
  let startTime = 0
  let endTime = 0
  
  // Check keyframes
  if (element.keyframes && element.keyframes.length > 0) {
    const sortedKeyframes = [...element.keyframes].sort((a, b) => a.time - b.time)
    startTime = sortedKeyframes[0].time
    endTime = sortedKeyframes[sortedKeyframes.length - 1].time
  }
  
  // Check legacy animations
  if (element.animations && element.animations.length > 0) {
    element.animations.forEach(animation => {
      const animStart = animation.delay || 0
      const animEnd = animStart + (animation.duration || 1)
      
      if (startTime === 0 || animStart < startTime) {
        startTime = animStart
      }
      if (animEnd > endTime) {
        endTime = animEnd
      }
    })
  }
  
  // If no keyframes or animations, show a minimal block at current time
  if (startTime === 0 && endTime === 0) {
    startTime = 0
    endTime = 1 // 1 second default duration
  }
  
  const startPercent = (startTime / totalDuration.value) * 100
  const widthPercent = ((endTime - startTime) / totalDuration.value) * 100
  
  return {
    left: `${startPercent}%`,
    width: `${Math.max(widthPercent, 2)}%` // Minimum 2% width for visibility
  }
}

// Keyframe methods
const addKeyframe = () => {
  if (!props.selectedElement) return
  
  const element = props.selectedElement
  const time = currentTime.value
  
  // Initialize keyframes array if it doesn't exist
  if (!element.keyframes) {
    element.keyframes = []
  }
  
  // Check if keyframe already exists at this time
  const existingKeyframe = element.keyframes.find(kf => Math.abs(kf.time - time) < 0.1)
  if (existingKeyframe) {
    // Update existing keyframe
    existingKeyframe.properties = {
      x: element.x || 0,
      y: element.y || 0,
      width: element.width || 100,
      height: element.height || 100,
      rotation: element.rotation || 0,
      opacity: element.opacity || 1,
      fill: element.fill || '#000000'
    }
  } else {
    // Create new keyframe
    const newKeyframe = {
      time: time,
      properties: {
        x: element.x || 0,
        y: element.y || 0,
        width: element.width || 100,
        height: element.height || 100,
        rotation: element.rotation || 0,
        opacity: element.opacity || 1,
        fill: element.fill || '#000000'
      }
    }
    
    element.keyframes.push(newKeyframe)
    
    // Sort keyframes by time
    element.keyframes.sort((a, b) => a.time - b.time)
  }
  
  emit('update-element', element)
}

const selectKeyframe = (element, keyframe, index) => {
  selectedKeyframe.value = { element, keyframe, index, time: keyframe.time }
  emit('select-element', element)
}

const deleteKeyframe = () => {
  if (!selectedKeyframe.value) return
  
  const element = selectedKeyframe.value.element
  const index = selectedKeyframe.value.index
  
  element.keyframes.splice(index, 1)
  selectedKeyframe.value = null
  
  emit('update-element', element)
}

const startDraggingKeyframe = (element, keyframe, index, event) => {
  isDragging.value = true
  dragData.value = { 
    type: 'keyframe', 
    element, 
    keyframe, 
    index,
    startX: event.clientX,
    startTime: keyframe.time
  }
  
  const handleMouseMove = (e) => {
    if (isDragging.value && dragData.value.type === 'keyframe') {
      const deltaX = e.clientX - dragData.value.startX
      const rect = event.target.closest('.track-content').getBoundingClientRect()
      const deltaTime = (deltaX / rect.width) * totalDuration.value
      
      const newTime = Math.max(0, dragData.value.startTime + deltaTime)
      keyframe.time = newTime
      
      // Sort keyframes by time
      element.keyframes.sort((a, b) => a.time - b.time)
      
      emit('update-element', element)
    }
  }
  
  const handleMouseUp = () => {
    isDragging.value = false
    dragData.value = null
    document.removeEventListener('mousemove', handleMouseMove)
    document.removeEventListener('mouseup', handleMouseUp)
  }
  
  document.addEventListener('mousemove', handleMouseMove)
  document.addEventListener('mouseup', handleMouseUp)
}

const togglePlayback = () => {
  if (isPlaying.value) {
    pauseAnimation()
  } else {
    playAnimation()
  }
}

const stopPlayback = () => {
  stopAnimation()
}

const playAnimation = () => {
  // Always start from time 0
  if (timeline.value) {
    timeline.value.progress(0)
    timeline.value.play()
  } else {
    createTimeline()
    if (timeline.value) {
      timeline.value.progress(0)
      timeline.value.play()
    }
  }
  currentTime.value = 0
  isPlaying.value = true
  emit('play')
}

const pauseAnimation = () => {
  if (timeline.value) {
    timeline.value.pause()
  }
  isPlaying.value = false
  emit('pause')
}

const stopAnimation = () => {
  if (timeline.value) {
    timeline.value.pause()
    timeline.value.progress(0)
  }
  currentTime.value = 0
  isPlaying.value = false
  emit('stop')
}

const resetAnimation = () => {
  stopAnimation()
  createTimeline()
}

const createTimeline = () => {
  timeline.value = gsap.timeline({
    onUpdate: () => {
      currentTime.value = timeline.value.time()
      updateElementsAtCurrentTime()
    },
    onComplete: () => {
      isPlaying.value = false
    }
  })
  
  // Add a dummy animation to ensure timeline has the correct duration
  timeline.value.to({}, { duration: totalDuration.value }, 0)
  
  // Create keyframe-based animations
  props.elements.forEach(element => {
    if (element.keyframes && element.keyframes.length > 0) {
      createKeyframeAnimation(element)
    }
    
    // Legacy animation support
    if (element.animations) {
      element.animations.forEach(animation => {
        const target = `#element-${element.id}`
        const delay = animation.delay || 0
        const duration = animation.duration || 1
        
        timeline.value.to(target, {
          duration: duration,
          ease: animation.ease || 'none',
          ...animation.properties
        }, delay)
      })
    }
  })
}

const createKeyframeAnimation = (element) => {
  if (!element.keyframes || element.keyframes.length === 0) return
  
  const sortedKeyframes = [...element.keyframes].sort((a, b) => a.time - b.time)
  
  // Set initial state - element invisible at time 0
  timeline.value.set(`#element-${element.id}`, {
    opacity: 0
  })
  
  // If only one keyframe, animate from current position to keyframe
  if (sortedKeyframes.length === 1) {
    const keyframe = sortedKeyframes[0]
    timeline.value.to(`#element-${element.id}`, {
      duration: 0.5,
      ease: 'power1.inOut',
      x: keyframe.properties.x,
      y: keyframe.properties.y,
      width: keyframe.properties.width,
      height: keyframe.properties.height,
      rotation: keyframe.properties.rotation,
      opacity: keyframe.properties.opacity,
      fill: keyframe.properties.fill
    }, keyframe.time)
    return
  }
  
  // Animate to first keyframe (element appears)
  if (sortedKeyframes.length > 0) {
    const firstKeyframe = sortedKeyframes[0]
    timeline.value.to(`#element-${element.id}`, {
      duration: 0.1, // Quick fade in
      ease: 'power1.inOut',
      x: firstKeyframe.properties.x,
      y: firstKeyframe.properties.y,
      width: firstKeyframe.properties.width,
      height: firstKeyframe.properties.height,
      rotation: firstKeyframe.properties.rotation,
      opacity: firstKeyframe.properties.opacity,
      fill: firstKeyframe.properties.fill
    }, firstKeyframe.time)
  }
  
  // Create animations between subsequent keyframes
  for (let i = 0; i < sortedKeyframes.length - 1; i++) {
    const currentKeyframe = sortedKeyframes[i]
    const nextKeyframe = sortedKeyframes[i + 1]
    
    const duration = nextKeyframe.time - currentKeyframe.time
    const delay = currentKeyframe.time
    
    // Create animation between keyframes
    timeline.value.to(`#element-${element.id}`, {
      duration: duration,
      ease: 'power1.inOut',
      x: nextKeyframe.properties.x,
      y: nextKeyframe.properties.y,
      width: nextKeyframe.properties.width,
      height: nextKeyframe.properties.height,
      rotation: nextKeyframe.properties.rotation,
      opacity: nextKeyframe.properties.opacity,
      fill: nextKeyframe.properties.fill
    }, delay)
  }
}

const updateElementsAtCurrentTime = () => {
  // Update element properties based on current time and keyframes
  props.elements.forEach(element => {
    if (element.keyframes && element.keyframes.length > 0) {
      const interpolatedProperties = interpolateKeyframes(element.keyframes, currentTime.value)
      if (interpolatedProperties && element.konvaObject) {
        // Update Konva object
        element.konvaObject.x(interpolatedProperties.x)
        element.konvaObject.y(interpolatedProperties.y)
        element.konvaObject.width(interpolatedProperties.width)
        element.konvaObject.height(interpolatedProperties.height)
        element.konvaObject.rotation(interpolatedProperties.rotation)
        element.konvaObject.opacity(interpolatedProperties.opacity)
        if (element.konvaObject.fill) {
          element.konvaObject.fill(interpolatedProperties.fill)
        }
        
        // Update element data
        Object.assign(element, interpolatedProperties)
      }
    } else {
      // Elements without keyframes should be invisible during animation
      if (element.konvaObject) {
        element.konvaObject.opacity(0)
      }
    }
  })
}

const interpolateKeyframes = (keyframes, time) => {
  if (!keyframes || keyframes.length === 0) return null
  
  const sortedKeyframes = [...keyframes].sort((a, b) => a.time - b.time)
  
  // If time is before first keyframe, element should be invisible
  if (time < sortedKeyframes[0].time) {
    return {
      ...sortedKeyframes[0].properties,
      opacity: 0 // Element is invisible before first keyframe
    }
  }
  
  // If time is exactly at first keyframe
  if (time <= sortedKeyframes[0].time) {
    return sortedKeyframes[0].properties
  }
  
  // If time is after last keyframe
  if (time >= sortedKeyframes[sortedKeyframes.length - 1].time) {
    return sortedKeyframes[sortedKeyframes.length - 1].properties
  }
  
  // Find keyframes to interpolate between
  for (let i = 0; i < sortedKeyframes.length - 1; i++) {
    const currentKeyframe = sortedKeyframes[i]
    const nextKeyframe = sortedKeyframes[i + 1]
    
    if (time >= currentKeyframe.time && time <= nextKeyframe.time) {
      const progress = (time - currentKeyframe.time) / (nextKeyframe.time - currentKeyframe.time)
      
      // Linear interpolation
      return {
        x: lerp(currentKeyframe.properties.x, nextKeyframe.properties.x, progress),
        y: lerp(currentKeyframe.properties.y, nextKeyframe.properties.y, progress),
        width: lerp(currentKeyframe.properties.width, nextKeyframe.properties.width, progress),
        height: lerp(currentKeyframe.properties.height, nextKeyframe.properties.height, progress),
        rotation: lerp(currentKeyframe.properties.rotation, nextKeyframe.properties.rotation, progress),
        opacity: lerp(currentKeyframe.properties.opacity, nextKeyframe.properties.opacity, progress),
        fill: progress < 0.5 ? currentKeyframe.properties.fill : nextKeyframe.properties.fill
      }
    }
  }
  
  return null
}

const lerp = (start, end, progress) => {
  return start + (end - start) * progress
}

const startDraggingPlayhead = (event) => {
  isDragging.value = true
  dragData.value = { type: 'playhead' }
  
  const handleMouseMove = (e) => {
    if (isDragging.value && dragData.value.type === 'playhead') {
      const rect = event.target.closest('.timeline-ruler').getBoundingClientRect()
      const percent = Math.max(0, Math.min(1, (e.clientX - rect.left) / rect.width))
      const newTime = percent * totalDuration.value
      
      currentTime.value = newTime
      if (timeline.value) {
        timeline.value.progress(percent)
      }
      
      // Update elements at current time
      updateElementsAtCurrentTime()
    }
  }
  
  const handleMouseUp = () => {
    isDragging.value = false
    dragData.value = null
    document.removeEventListener('mousemove', handleMouseMove)
    document.removeEventListener('mouseup', handleMouseUp)
  }
  
  document.addEventListener('mousemove', handleMouseMove)
  document.addEventListener('mouseup', handleMouseUp)
}

const startDraggingBlock = (element, animation, index, event) => {
  isDragging.value = true
  dragData.value = { 
    type: 'block', 
    element, 
    animation, 
    index,
    startX: event.clientX,
    startDelay: animation.delay || 0
  }
  
  const handleMouseMove = (e) => {
    if (isDragging.value && dragData.value.type === 'block') {
      const deltaX = e.clientX - dragData.value.startX
      const rect = event.target.closest('.track-content').getBoundingClientRect()
      const deltaTime = (deltaX / rect.width) * totalDuration.value
      
      const newDelay = Math.max(0, dragData.value.startDelay + deltaTime)
      animation.delay = newDelay
      
      emit('update-element', element)
    }
  }
  
  const handleMouseUp = () => {
    isDragging.value = false
    dragData.value = null
    document.removeEventListener('mousemove', handleMouseMove)
    document.removeEventListener('mouseup', handleMouseUp)
  }
  
  document.addEventListener('mousemove', handleMouseMove)
  document.addEventListener('mouseup', handleMouseUp)
}

const startResizing = (element, animation, index, side, event) => {
  event.stopPropagation()
  isDragging.value = true
  dragData.value = { 
    type: 'resize', 
    element, 
    animation, 
    index, 
    side,
    startX: event.clientX,
    startDelay: animation.delay || 0,
    startDuration: animation.duration || 1
  }
  
  const handleMouseMove = (e) => {
    if (isDragging.value && dragData.value.type === 'resize') {
      const deltaX = e.clientX - dragData.value.startX
      const rect = event.target.closest('.track-content').getBoundingClientRect()
      const deltaTime = (deltaX / rect.width) * totalDuration.value
      
      if (side === 'left') {
        const newDelay = Math.max(0, dragData.value.startDelay + deltaTime)
        const newDuration = Math.max(0.1, dragData.value.startDuration - deltaTime)
        animation.delay = newDelay
        animation.duration = newDuration
      } else {
        const newDuration = Math.max(0.1, dragData.value.startDuration + deltaTime)
        animation.duration = newDuration
      }
      
      emit('update-element', element)
    }
  }
  
  const handleMouseUp = () => {
    isDragging.value = false
    dragData.value = null
    document.removeEventListener('mousemove', handleMouseMove)
    document.removeEventListener('mouseup', handleMouseUp)
  }
  
  document.addEventListener('mousemove', handleMouseMove)
  document.addEventListener('mouseup', handleMouseUp)
}

const zoomIn = () => {
  zoomLevel.value = Math.min(3, zoomLevel.value * 1.2)
}

const zoomOut = () => {
  zoomLevel.value = Math.max(0.2, zoomLevel.value / 1.2)
}

// Lifecycle
onMounted(() => {
  createTimeline()
})

onUnmounted(() => {
  if (timeline.value) {
    timeline.value.kill()
  }
})
</script>

<style scoped>
.animation-timeline {
  height: 100%;
  min-height: 250px;
  display: flex;
  flex-direction: column;
  background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
  border-top: 1px solid #e2e8f0;
  border-radius: 12px 12px 0 0;
  overflow: hidden;
  box-shadow: 0 -4px 6px -1px rgba(0, 0, 0, 0.05);
}

.timeline-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  padding: 0.5rem 0.75rem;
  background: linear-gradient(135deg, #ffffff 0%, #f8fafc 100%);
  border-bottom: 1px solid #e2e8f0;
  backdrop-filter: blur(10px);
}

.timeline-header-left {
  display: flex;
  align-items: center;
  gap: 0.5rem;
}

.timeline-icon {
  display: flex;
  align-items: center;
  justify-content: center;
  width: 24px;
  height: 24px;
  background: linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%);
  border-radius: 6px;
  color: white;
  box-shadow: 0 2px 4px -1px rgba(59, 130, 246, 0.3);
}

.timeline-title-section {
  display: flex;
  flex-direction: column;
  gap: 0.25rem;
}

.timeline-title {
  font-size: 0.875rem;
  font-weight: 600;
  color: #1e293b;
  margin: 0;
  letter-spacing: -0.025em;
}

.timeline-subtitle {
  font-size: 0.75rem;
  color: #64748b;
  margin: 0;
  font-weight: 400;
}

.timeline-controls {
  display: flex;
  gap: 0.5rem;
}

.timeline-btn {
  display: flex;
  align-items: center;
  gap: 0.25rem;
  padding: 0.375rem 0.5rem;
  border: none;
  background: linear-gradient(135deg, #ffffff 0%, #f8fafc 100%);
  color: #475569;
  border-radius: 6px;
  cursor: pointer;
  font-size: 0.75rem;
  font-weight: 500;
  transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
  box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.05);
  border: 1px solid #e2e8f0;
}

.timeline-btn:hover:not(:disabled) {
  background: linear-gradient(135deg, #f1f5f9 0%, #e2e8f0 100%);
  transform: translateY(-1px);
  box-shadow: 0 2px 4px -1px rgba(0, 0, 0, 0.1);
  border-color: #cbd5e1;
}

.timeline-btn:active {
  transform: translateY(0);
  box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.05);
}

.timeline-btn:disabled {
  opacity: 0.5;
  cursor: not-allowed;
  transform: none;
}

.timeline-btn.active {
  background: linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%);
  color: white;
  border-color: #1d4ed8;
}

.timeline-btn.success {
  background: linear-gradient(135deg, #10b981 0%, #059669 100%);
  color: white;
  border-color: #059669;
}

.timeline-btn.success:hover:not(:disabled) {
  background: linear-gradient(135deg, #059669 0%, #047857 100%);
}

.control-group {
  display: flex;
  gap: 0.5rem;
  padding: 0.5rem;
  background: rgba(255, 255, 255, 0.8);
  border-radius: 8px;
  border: 1px solid #e2e8f0;
  backdrop-filter: blur(10px);
}

.control-btn {
  display: flex;
  align-items: center;
  gap: 0.5rem;
  padding: 0.75rem 1rem;
  border: none;
  background: linear-gradient(135deg, #ffffff 0%, #f8fafc 100%);
  color: #475569;
  border-radius: 8px;
  cursor: pointer;
  font-size: 0.875rem;
  font-weight: 500;
  transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
  box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.1), 0 1px 2px 0 rgba(0, 0, 0, 0.06);
  border: 1px solid #e2e8f0;
}

.control-btn:hover:not(:disabled) {
  background: linear-gradient(135deg, #f1f5f9 0%, #e2e8f0 100%);
  transform: translateY(-1px);
  box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
  border-color: #cbd5e1;
}

.control-btn:active {
  transform: translateY(0);
  box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.05);
}

.control-btn:disabled {
  opacity: 0.5;
  cursor: not-allowed;
  transform: none;
}

.control-btn.play:hover:not(:disabled) {
  background: linear-gradient(135deg, #f9fafb 0%, #f3f4f6 100%);
  border-color: #9ca3af;
  color: #4b5563;
}

.control-btn.pause:hover:not(:disabled) {
  background: linear-gradient(135deg, #f3f4f6 0%, #e5e7eb 100%);
  border-color: #6b7280;
  color: #374151;
}

.control-btn.stop:hover:not(:disabled) {
  background: linear-gradient(135deg, #f3f4f6 0%, #e5e7eb 100%);
  border-color: #374151;
  color: #1f2937;
}

.control-btn.keyframe:hover:not(:disabled) {
  background: linear-gradient(135deg, #f9fafb 0%, #f3f4f6 100%);
  border-color: #6b7280;
  color: #374151;
}

.timeline-content {
  flex: 1;
  overflow: auto;
}

.timeline-ruler {
  position: relative;
  height: 2rem;
  background: linear-gradient(180deg, #ffffff 0%, #f8fafc 100%);
  border-bottom: 1px solid #e2e8f0;
  overflow: hidden;
  backdrop-filter: blur(10px);
}

.ruler-track {
  position: relative;
  height: 100%;
}

.ruler-mark {
  position: absolute;
  top: 0;
  height: 100%;
  border-left: 1px solid #e2e8f0;
  transition: border-color 0.2s ease;
}

.ruler-mark.major {
  border-left: 2px solid #64748b;
}

.ruler-mark.minor {
  border-left: 1px solid #cbd5e1;
}

.ruler-label {
  position: absolute;
  top: 0.25rem;
  left: 0.25rem;
  font-size: 0.625rem;
  font-weight: 500;
  color: #475569;
  background: rgba(255, 255, 255, 0.9);
  padding: 0.125rem 0.25rem;
  border-radius: 3px;
  border: 1px solid #e2e8f0;
  backdrop-filter: blur(5px);
}

.time-indicator {
  position: absolute;
  top: 0.25rem;
  right: 0.5rem;
}

.current-time {
  font-size: 0.75rem;
  font-weight: 500;
  color: #1e293b;
  background: linear-gradient(135deg, #ffffff 0%, #f1f5f9 100%);
  padding: 0.25rem 0.5rem;
  border-radius: 4px;
  border: 1px solid #e2e8f0;
  box-shadow: 0 1px 2px -1px rgba(0, 0, 0, 0.1);
}

.playhead {
  position: absolute;
  top: 0;
  width: 3px;
  height: 100%;
  background: linear-gradient(180deg, #374151 0%, #1f2937 100%);
  cursor: ew-resize;
  z-index: 20;
  border-radius: 1.5px;
  box-shadow: 0 0 8px rgba(55, 65, 81, 0.5);
}

.playhead-handle {
  position: absolute;
  top: -6px;
  left: -6px;
  width: 15px;
  height: 15px;
  background: linear-gradient(135deg, #374151 0%, #1f2937 100%);
  border-radius: 50%;
  border: 2px solid white;
  box-shadow: 0 2px 8px rgba(55, 65, 81, 0.4);
}

.playhead-line {
  position: absolute;
  top: 0;
  bottom: 0;
  left: 50%;
  width: 1px;
  background: rgba(55, 65, 81, 0.3);
  transform: translateX(-50%);
}

.timeline-tracks {
  min-height: 100px;
  flex: 1;
  overflow-y: auto;
  background: linear-gradient(180deg, #ffffff 0%, #f8fafc 100%);
}

.empty-timeline {
  display: flex;
  flex-direction: column;
  align-items: center;
  justify-content: center;
  height: 100px;
  color: #64748b;
  background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
  border-radius: 8px;
  margin: 0.5rem;
  border: 2px dashed #cbd5e1;
}

.empty-icon {
  font-size: 2rem;
  margin-bottom: 0.5rem;
  opacity: 0.6;
  background: linear-gradient(135deg, #6b7280 0%, #9ca3af 100%);
  -webkit-background-clip: text;
  -webkit-text-fill-color: transparent;
  background-clip: text;
}

.empty-title {
  font-size: 0.875rem;
  font-weight: 600;
  margin-bottom: 0.25rem;
  color: #1e293b;
}

.empty-description {
  font-size: 0.75rem;
  text-align: center;
  max-width: 250px;
  color: #64748b;
  line-height: 1.4;
}

.timeline-track {
  display: flex;
  border-bottom: 1px solid #f1f5f9;
  min-height: 12px;
  height: 12px;
  transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
  position: relative;
}

.timeline-track:hover {
  background: linear-gradient(90deg, #f8fafc 0%, #f1f5f9 100%);
  transform: translateX(2px);
  box-shadow: 0 2px 4px -1px rgba(0, 0, 0, 0.05);
}

.timeline-track.selected {
  background: linear-gradient(90deg, #f9fafb 0%, #f3f4f6 100%);
  border-color: #d1d5db;
  box-shadow: inset 3px 0 0 #6b7280, 0 4px 6px -1px rgba(107, 114, 128, 0.1);
}

.track-header {
  width: 220px;
  display: flex;
  align-items: center;
  gap: 0.25rem;
  padding: 0.125rem 0.5rem;
  border-right: 1px solid #e2e8f0;
  background: linear-gradient(135deg, #ffffff 0%, #f8fafc 100%);
  backdrop-filter: blur(10px);
  height: 12px;
}

.track-icon {
  width: 10px;
  height: 10px;
  border-radius: 2px;
  display: flex;
  align-items: center;
  justify-content: center;
  color: white;
  font-size: 0.5rem;
  font-weight: 600;
  box-shadow: 0 1px 2px -1px rgba(0, 0, 0, 0.1);
  flex-shrink: 0;
}

.track-info {
  flex: 1;
  min-width: 0;
  display: flex;
  align-items: center;
  gap: 0.25rem;
}

.track-name {
  font-weight: 500;
  font-size: 0.625rem;
  color: #1e293b;
  white-space: nowrap;
  overflow: hidden;
  text-overflow: ellipsis;
  margin: 0;
  line-height: 1;
}

.track-type {
  font-size: 0.5rem;
  color: #64748b;
  font-weight: 400;
  text-transform: uppercase;
  letter-spacing: 0.05em;
  white-space: nowrap;
  line-height: 1;
}

.track-content {
  flex: 1;
  position: relative;
  padding: 0;
  background: linear-gradient(90deg, rgba(255, 255, 255, 0.8) 0%, rgba(248, 250, 252, 0.8) 100%);
  backdrop-filter: blur(10px);
  height: 12px;
}

.animation-block {
  position: absolute;
  height: 10px;
  top: 1px;
  background: linear-gradient(135deg, #6b7280 0%, #4b5563 100%);
  border-radius: 2px;
  cursor: move;
  display: flex;
  align-items: center;
  padding: 0 0.25rem;
  color: white;
  font-size: 0.5rem;
  font-weight: 600;
  transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
  box-shadow: 0 1px 2px -1px rgba(107, 114, 128, 0.3);
  border: 1px solid rgba(255, 255, 255, 0.2);
}

.animation-block:hover {
  background: linear-gradient(135deg, #4b5563 0%, #374151 100%);
  transform: translateY(-2px);
  box-shadow: 0 8px 16px -4px rgba(107, 114, 128, 0.4), 0 4px 6px -1px rgba(107, 114, 128, 0.2);
}

.block-content {
  flex: 1;
  display: flex;
  justify-content: space-between;
  align-items: center;
  pointer-events: none;
}

.block-label {
  font-weight: 500;
}

.block-duration {
  opacity: 0.8;
}

.resize-handle {
  position: absolute;
  top: 0;
  width: 4px;
  height: 100%;
  cursor: ew-resize;
  background: rgba(255, 255, 255, 0.3);
  opacity: 0;
  transition: opacity 0.2s;
}

.resize-handle.left {
  left: 0;
}

.resize-handle.right {
  right: 0;
}

.animation-block:hover .resize-handle {
  opacity: 1;
}

.element-duration-block {
  position: absolute;
  height: 8px;
  top: 2px;
  border-radius: 4px;
  cursor: pointer;
  transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
  opacity: 0.6;
  z-index: 1;
}

.element-duration-block:hover {
  opacity: 0.8;
  transform: translateY(-1px);
}

.element-duration-block.selected {
  opacity: 1;
  box-shadow: 0 0 0 2px rgba(107, 114, 128, 0.5);
}

.duration-bar {
  width: 100%;
  height: 100%;
  border-radius: 4px;
  background: linear-gradient(135deg, currentColor 0%, currentColor 100%);
  opacity: 0.7;
}

.keyframe-marker {
  position: absolute;
  top: 0;
  width: 12px;
  height: 12px;
  cursor: pointer;
  z-index: 10;
  transform: translateX(-6px);
  transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
}

.keyframe-marker:hover {
  transform: translateX(-6px) scale(1.2);
}

.keyframe-marker.selected {
  transform: translateX(-6px) scale(1.3);
  z-index: 15;
}

.keyframe-diamond {
  width: 12px;
  height: 12px;
  border-radius: 2px;
  transform: rotate(45deg);
  display: flex;
  align-items: center;
  justify-content: center;
  border: 2px solid white;
  box-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
}

.keyframe-diamond svg {
  transform: rotate(-45deg);
  width: 8px;
  height: 8px;
}

.add-animation-btn {
  position: absolute;
  top: 0.5rem;
  width: 1.5rem;
  height: 1.5rem;
  border: 1px dashed #9ca3af;
  background: white;
  border-radius: 50%;
  cursor: pointer;
  display: flex;
  align-items: center;
  justify-content: center;
  font-size: 0.875rem;
  color: #6b7280;
  transition: all 0.2s;
}

.add-animation-btn:hover {
  border-color: #6b7280;
  color: #6b7280;
  background: #f9fafb;
}

.timeline-footer {
  display: flex;
  justify-content: space-between;
  align-items: center;
  padding: 0.5rem 0.75rem;
  border-top: 1px solid #e2e8f0;
  background: linear-gradient(135deg, #ffffff 0%, #f8fafc 100%);
  backdrop-filter: blur(10px);
  box-shadow: 0 -2px 4px -1px rgba(0, 0, 0, 0.05);
}

.zoom-controls {
  display: flex;
  align-items: center;
  gap: 0.5rem;
}

.zoom-btn {
  width: 2rem;
  height: 2rem;
  border: 1px solid #d1d5db;
  background: white;
  border-radius: 0.25rem;
  cursor: pointer;
  display: flex;
  align-items: center;
  justify-content: center;
  transition: all 0.2s;
}

.zoom-btn:hover {
  background: #f3f4f6;
  border-color: #9ca3af;
}

.zoom-level {
  font-size: 0.875rem;
  color: #6b7280;
  min-width: 3rem;
  text-align: center;
}

.timeline-info {
  display: flex;
  gap: 1rem;
  font-size: 0.875rem;
  color: #6b7280;
}

.keyframe-info {
  display: flex;
  align-items: center;
  gap: 0.5rem;
  font-size: 0.875rem;
  color: #374151;
}

.delete-keyframe-btn {
  padding: 0.25rem 0.5rem;
  border: 1px solid #374151;
  background: white;
  color: #374151;
  border-radius: 0.25rem;
  cursor: pointer;
  font-size: 0.75rem;
  transition: all 0.2s;
}

.delete-keyframe-btn:hover {
  background: #374151;
  color: white;
}
</style>
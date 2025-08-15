<template>
  <div class="test-properties-panel p-4 bg-gray-50 rounded-lg">
    <h3 class="text-lg font-semibold mb-4">Teste de Propriedades Editáveis</h3>
    
    <div v-if="testElement" class="space-y-4">
      <div class="bg-white p-4 rounded border">
        <h4 class="font-medium mb-3">Elemento de Teste</h4>
        
        <div class="grid grid-cols-2 gap-4">
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Posição X</label>
            <input 
              id="test-position-x"
              v-model.number="testElement.x" 
              type="number" 
              class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
              placeholder="X"
              @input="updateTestElement"
            />
          </div>
          
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Posição Y</label>
            <input 
              id="test-position-y"
              v-model.number="testElement.y" 
              type="number" 
              class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
              placeholder="Y"
              @input="updateTestElement"
            />
          </div>
          
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Largura</label>
            <input 
              id="test-width"
              v-model.number="testElement.width" 
              type="number" 
              class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
              placeholder="Largura"
              @input="updateTestElement"
            />
          </div>
          
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Altura</label>
            <input 
              id="test-height"
              v-model.number="testElement.height" 
              type="number" 
              class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
              placeholder="Altura"
              @input="updateTestElement"
            />
          </div>
        </div>
        
        <div class="mt-4">
          <label class="block text-sm font-medium text-gray-700 mb-1">Cor</label>
          <input 
            id="test-color"
            v-model="testElement.fill" 
            type="color" 
            class="w-full h-10 border border-gray-300 rounded-md"
            @input="updateTestElement"
          />
        </div>
        
        <div class="mt-4 p-3 bg-gray-100 rounded">
          <h5 class="font-medium text-sm mb-2">Valores Atuais:</h5>
          <pre class="text-xs">{{ JSON.stringify(testElement, null, 2) }}</pre>
        </div>
      </div>
      
      <div class="flex space-x-2">
        <button 
          @click="resetElement" 
          class="px-4 py-2 bg-gray-500 text-white rounded hover:bg-gray-600 transition-colors"
        >
          Resetar
        </button>
        <button 
          @click="randomizeElement" 
          class="px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600 transition-colors"
        >
          Valores Aleatórios
        </button>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, reactive } from 'vue'

// Test element
const testElement = reactive({
  id: 'test-element-1',
  name: 'Elemento de Teste',
  type: 'character',
  x: 100,
  y: 100,
  width: 150,
  height: 100,
  rotation: 0,
  opacity: 1,
  fill: '#3b82f6'
})

// Update counter to track changes
const updateCount = ref(0)

// Debounce utility
let updateTimeout = null

const updateTestElement = () => {
  updateCount.value++
  
  // Clear previous timeout
  if (updateTimeout) {
    clearTimeout(updateTimeout)
  }
  
  // Debounce the update
  updateTimeout = setTimeout(() => {
    console.log('Elemento atualizado:', updateCount.value, testElement)
  }, 100)
}

const resetElement = () => {
  testElement.x = 100
  testElement.y = 100
  testElement.width = 150
  testElement.height = 100
  testElement.rotation = 0
  testElement.opacity = 1
  testElement.fill = '#3b82f6'
  updateTestElement()
}

const randomizeElement = () => {
  testElement.x = Math.floor(Math.random() * 400)
  testElement.y = Math.floor(Math.random() * 300)
  testElement.width = Math.floor(Math.random() * 200) + 50
  testElement.height = Math.floor(Math.random() * 150) + 50
  testElement.fill = `#${Math.floor(Math.random()*16777215).toString(16)}`
  updateTestElement()
}
</script>
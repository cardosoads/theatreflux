<template>
  <div class="h-screen bg-gray-100 flex flex-col">
    <!-- Header -->
    <div class="bg-white border-b border-gray-300 px-4 py-2 flex items-center justify-between">
      <div class="flex items-center gap-4">
        <h1 class="text-lg font-semibold text-gray-800">Theatre Flux</h1>
        <div class="flex gap-2">
          <button 
            @click="toggleSidePanel"
            class="flex items-center gap-1 px-3 py-1 text-sm rounded transition-colors"
            :class="showSidePanel ? 'bg-purple-600 text-white hover:bg-purple-700' : 'bg-gray-600 text-white hover:bg-gray-700'"
          >
            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16"></path>
            </svg>
            {{ showSidePanel ? 'Ocultar' : 'Mostrar' }} Painel
          </button>
          <button 
            @click="saveFlow" 
            class="flex items-center gap-1 px-3 py-1 bg-gray-600 text-white text-sm rounded hover:bg-gray-700"
          >
            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3-3m0 0l-3 3m3-3v12"></path>
            </svg>
            Salvar
          </button>
          <button 
            @click="loadFlow" 
            class="flex items-center gap-1 px-3 py-1 bg-gray-600 text-white text-sm rounded hover:bg-gray-700"
          >
            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-5l-2-2H5a2 2 0 00-2 2z"></path>
            </svg>
            Carregar
          </button>
          <button 
            @click="exportFlow" 
            class="flex items-center gap-1 px-3 py-1 bg-gray-600 text-white text-sm rounded hover:bg-gray-700"
          >
            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
            </svg>
            Exportar
          </button>
        </div>
      </div>

      <div class="flex items-center gap-2">
        <button
          @click="playScenes"
          :disabled="isPlaying"
          class="flex items-center gap-1 px-3 py-1 bg-gray-600 text-white text-sm rounded hover:bg-gray-700 disabled:opacity-50"
        >
          <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 24 24">
            <path d="M8 5v14l11-7z"></path>
          </svg>
          Reproduzir
        </button>
        <button
          @click="pausePlay"
          :disabled="!isPlaying"
          class="flex items-center gap-1 px-3 py-1 bg-gray-600 text-white text-sm rounded hover:bg-gray-700 disabled:opacity-50"
        >
          <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 24 24">
            <path d="M6 19h4V5H6v14zm8-14v14h4V5h-4z"></path>
          </svg>
          Pausar
        </button>
        <button
          @click="stopPlay"
          :disabled="!isPlaying && !isPaused"
          class="flex items-center gap-1 px-3 py-1 bg-gray-600 text-white text-sm rounded hover:bg-gray-700 disabled:opacity-50"
        >
          <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 24 24">
            <path d="M6 6h12v12H6z"></path>
          </svg>
          Parar
        </button>
      </div>
    </div>

    <div class="flex flex-1">
      <!-- Toolbar -->
      <div class="w-14 bg-gray-200 border-r border-gray-300 flex flex-col">
        <button
          v-for="tool in tools"
          :key="tool.id"
          @click="setSelectedTool(tool.id)"
          :class="[
            'w-full h-12 flex items-center justify-center border-b border-gray-300 hover:bg-gray-300',
            selectedTool === tool.id ? 'bg-gray-400' : ''
          ]"
          :title="tool.label"
        >
          <component :is="tool.icon" class="w-5 h-5 text-gray-700" />
        </button>

        <div class="flex-1"></div>

        <!-- Zoom controls -->
        <div class="border-t border-gray-300 p-2">
          <button
            @click="handleZoom(10)"
            class="w-full mb-1 p-1 hover:bg-gray-300 rounded text-xs"
            title="Zoom In"
          >
            +
          </button>
          <div class="text-xs text-center mb-1">{{ zoom }}%</div>
          <button
            @click="handleZoom(-10)"
            class="w-full p-1 hover:bg-gray-300 rounded text-xs"
            title="Zoom Out"
          >
            -
          </button>
        </div>
      </div>

      <!-- Main Canvas Area -->
      <div class="flex-1 flex flex-col">
        <!-- Secondary Toolbar -->
        <div class="bg-gray-50 border-b border-gray-300 px-4 py-2 flex items-center gap-4">
          <div class="flex items-center gap-4">
            <button
              @click="addScene"
              class="p-1 hover:bg-gray-200 rounded"
              title="Nova Cena (cópia da atual)"
            >
              <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
              </svg>
            </button>
            <button
              @click="duplicateScene"
              class="p-1 hover:bg-gray-200 rounded"
              title="Duplicar Cena"
            >
              <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
              </svg>
            </button>
            <button
              @click="removeScene"
              :disabled="scenes.length === 1"
              class="p-1 hover:bg-gray-200 rounded disabled:opacity-50"
              title="Remover Cena"
            >
              <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
              </svg>
            </button>
            <button
              @click="showActorDialog = true"
              class="px-3 py-1 bg-gray-600 text-white text-sm rounded hover:bg-gray-700"
            >
              Cadastrar Ator
            </button>
            <button
              @click="showStageEditor = true"
              class="px-3 py-1 bg-gray-600 text-white text-sm rounded hover:bg-gray-700"
            >
              Editor de Palco
            </button>
          </div>
        </div>

        <!-- Canvas -->
        <div class="flex-1 relative overflow-hidden bg-gray-100">
          <div 
            ref="canvasContainer"
            class="absolute inset-0 flex items-center justify-center"
            @click="handleCanvasClick"
            @mousedown="handlePanStart"
          >
            <div
              ref="canvas"
              class="relative bg-white border-2 border-gray-300 shadow-lg"
              :style="{
                width: `${currentScene.stage.width || 600}px`,
                height: `${currentScene.stage.height || 400}px`,
                transform: `scale(${zoom / 100}) translate(${canvasOffset.x}px, ${canvasOffset.y}px)`,
                cursor: selectedTool === 'hand' ? (isPanning ? 'grabbing' : 'grab') : 'crosshair',
                transformOrigin: 'center center'
              }"
            >
              <!-- Stage Background -->
              <div
                class="absolute inset-0"
                :style="getStageStyle(currentScene.stage)"
              ></div>

              <!-- Elements -->
              <div 
                v-for="element in allElements" 
                :key="element.id" 
                :style="{ zIndex: element.zIndex }"
                class="absolute"
              >
                <!-- Speech Bubble -->
                <div
                  v-if="isActor(element) && element.speech"
                  class="absolute group"
                  :style="{
                    left: element.x + 20 + 'px',
                    top: element.y - 50 + 'px',
                    zIndex: element.zIndex + 10
                  }"
                >
                  <div
                    class="relative bg-white border-2 rounded-lg px-3 py-2 text-xs shadow-lg transition-all duration-200 hover:shadow-xl"
                    :class="{
                      'border-blue-300 bg-blue-50': element.speechStyle === 'thought',
                      'border-red-300 bg-red-50': element.speechStyle === 'shout',
                      'border-green-300 bg-green-50': element.speechStyle === 'whisper',
                      'border-gray-300': !element.speechStyle || element.speechStyle === 'normal'
                    }"
                    :style="{
                      maxWidth: element.speechWidth || '120px',
                      wordWrap: 'break-word',
                      fontSize: element.speechSize || '12px'
                    }"
                  >
                    <!-- Speech Text -->
                    <div class="pr-6">{{ element.speech }}</div>
                    
                    <!-- Speech Controls -->
                    <div class="absolute top-1 right-1 opacity-0 group-hover:opacity-100 transition-opacity duration-200">
                      <button
                        @click="editSpeechBubble(element)"
                        class="w-4 h-4 bg-blue-500 text-white rounded-full text-xs hover:bg-blue-600 mr-1"
                        title="Editar fala"
                      >
                        ✎
                      </button>
                      <button
                        @click="removeSpeechBubble(element)"
                        class="w-4 h-4 bg-red-500 text-white rounded-full text-xs hover:bg-red-600"
                        title="Remover fala"
                      >
                        ×
                      </button>
                    </div>
                    
                    <!-- Speech Tail -->
                    <div
                      class="absolute"
                      :class="{
                        'w-0 h-0 border-l-4 border-r-4 border-t-4 border-transparent border-t-white': !element.speechStyle || element.speechStyle === 'normal',
                        'w-2 h-2 bg-blue-50 border border-blue-300 rounded-full': element.speechStyle === 'thought',
                        'w-0 h-0 border-l-6 border-r-6 border-t-6 border-transparent border-t-red-50': element.speechStyle === 'shout',
                        'w-0 h-0 border-l-2 border-r-2 border-t-2 border-transparent border-t-green-50': element.speechStyle === 'whisper'
                      }"
                      :style="{
                        left: '15px',
                        bottom: element.speechStyle === 'thought' ? '-6px' : '-4px'
                      }"
                    ></div>
                  </div>
                </div>

                <!-- Actor -->
                <div
                  v-if="isActor(element)"
                  class="absolute w-8 h-8 rounded-full border-2 border-white shadow-md cursor-pointer flex items-center justify-center text-white font-bold text-xs"
                  :class="{
                    'ring-2 ring-gray-400': selectedElement && selectedElement.id === element.id
                  }"
                  :style="{
                    left: element.x + 'px',
                    top: element.y + 'px',
                    backgroundColor: element.color
                  }"
                  @mousedown="handleElementMouseDown(element, $event)"
                >
                  {{ element.name.charAt(0) }}
                </div>

                <!-- Object -->
                <div
                  v-else
                  class="absolute border-2 border-gray-400 cursor-pointer flex items-center justify-center"
                  :class="{
                    'ring-2 ring-gray-400': selectedElement && selectedElement.id === element.id,
                    'rounded-full': element.type === 'circle',
                    'rounded': element.type === 'rectangle'
                  }"
                  :style="{
                    left: element.x + 'px',
                    top: element.y + 'px',
                    width: element.width + 'px',
                    height: element.height + 'px',
                    backgroundColor: element.color,
                    clipPath: element.type === 'triangle' ? 'polygon(50% 0%, 0% 100%, 100% 100%)' : 'none'
                  }"
                  @mousedown="handleElementMouseDown(element, $event)"
                >
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Sidebar -->
      <div class="w-80 bg-white border-l border-gray-300 flex flex-col">
        <!-- Scene List -->
        <div class="border-b border-gray-300 p-4">
          <h3 class="font-semibold mb-3 text-gray-800">Cenas</h3>
          <div class="space-y-2 max-h-40 overflow-y-auto">
            <div
              v-for="(scene, index) in scenes"
              :key="scene.id"
              class="p-2 border rounded cursor-pointer flex items-center justify-between"
              :class="{
                'bg-gray-100 border-gray-400': currentSceneIndex === index,
                'hover:bg-gray-50': currentSceneIndex !== index
              }"
              @click="setCurrentSceneIndex(index)"
              draggable="true"
              @dragstart="handleSceneDragStart(index, $event)"
              @dragover.prevent
              @drop="handleSceneDrop(index, $event)"
            >
              <span class="text-sm font-medium">{{ scene.name }}</span>
              <div class="flex items-center gap-1">
                <span class="text-xs text-gray-500">{{ scene.actors.length + scene.objects.length }}</span>
                <button
                  @click.stop="editSceneName(index)"
                  class="text-xs text-gray-400 hover:text-gray-600"
                >
                  ✏️
                </button>
              </div>
            </div>
          </div>
        </div>

        <!-- Available Actors -->
        <div class="border-b border-gray-300 p-4">
          <h3 class="font-semibold mb-3 text-gray-800">Atores Disponíveis</h3>
          <div class="space-y-2 max-h-32 overflow-y-auto">
            <div
              v-for="actor in availableActors"
              :key="actor.id"
              class="p-2 border rounded hover:bg-gray-50 cursor-pointer flex items-center space-x-3"
              @click="addActorToScene(actor)"
            >
              <div 
                class="w-6 h-6 rounded-full flex items-center justify-center text-white text-xs font-bold"
                :style="{ backgroundColor: actor.color }"
              >
                {{ actor.name.charAt(0) }}
              </div>
              <span class="text-sm">{{ actor.name }}</span>
            </div>
          </div>
        </div>

        <!-- Properties Panel -->
        <div class="flex-1 p-4 overflow-y-auto">
          <h3 class="font-semibold mb-3 text-gray-800">Propriedades</h3>
          <div v-if="selectedElement" class="space-y-3">
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-1">Nome</label>
              <input
                v-model="selectedElement.name"
                type="text"
                class="w-full px-3 py-1 border border-gray-300 rounded text-sm"
              />
            </div>
            <div class="grid grid-cols-2 gap-2">
              <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">X</label>
                <input
                  v-model.number="selectedElement.x"
                  type="number"
                  class="w-full px-3 py-1 border border-gray-300 rounded text-sm"
                />
              </div>
              <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Y</label>
                <input
                  v-model.number="selectedElement.y"
                  type="number"
                  class="w-full px-3 py-1 border border-gray-300 rounded text-sm"
                />
              </div>
            </div>
            <div v-if="!isActor(selectedElement)" class="grid grid-cols-2 gap-2">
              <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Largura</label>
                <input
                  v-model.number="selectedElement.width"
                  type="number"
                  class="w-full px-3 py-1 border border-gray-300 rounded text-sm"
                />
              </div>
              <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Altura</label>
                <input
                  v-model.number="selectedElement.height"
                  type="number"
                  class="w-full px-3 py-1 border border-gray-300 rounded text-sm"
                />
              </div>
            </div>
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-1">Cor</label>
              <input
                v-model="selectedElement.color"
                type="color"
                class="w-full h-8 border border-gray-300 rounded"
              />
            </div>
            <div v-if="isActor(selectedElement)">
              <label class="block text-sm font-medium text-gray-700 mb-1">Fala</label>
              <textarea
                v-model="selectedElement.speech"
                class="w-full px-3 py-1 border border-gray-300 rounded text-sm"
                rows="3"
                placeholder="Digite a fala do personagem..."
              ></textarea>
            </div>
            <div class="flex gap-2">
              <button
                @click="moveElementLayer(selectedElement, 'up')"
                class="flex-1 px-3 py-1 bg-gray-600 text-white text-sm rounded hover:bg-gray-700"
              >
                ↑ Frente
              </button>
              <button
                @click="moveElementLayer(selectedElement, 'down')"
                class="flex-1 px-3 py-1 bg-gray-600 text-white text-sm rounded hover:bg-gray-700"
              >
                ↓ Trás
              </button>
            </div>
            <button
              @click="deleteElement(selectedElement)"
              class="w-full px-3 py-1 bg-gray-600 text-white text-sm rounded hover:bg-gray-700"
            >
              🗑️ Excluir
            </button>
          </div>
          <div v-else class="text-sm text-gray-500">
            Selecione um elemento para editar suas propriedades.
          </div>
        </div>
      </div>

      <!-- Side Panel -->
      <div v-if="showSidePanel" class="w-80 bg-white border-l border-gray-300 flex flex-col">
        <!-- Panel Header -->
        <div class="bg-gray-50 border-b border-gray-300 px-4 py-3">
          <h3 class="text-lg font-semibold text-gray-800">Gerenciador de Cenas</h3>
        </div>

        <!-- Scenes List -->
        <div class="flex-1 overflow-y-auto p-4">
          <div class="space-y-3">
            <div
              v-for="(scene, index) in scenes"
              :key="scene.id"
              class="border border-gray-200 rounded-lg p-3 cursor-pointer transition-colors"
              :class="{
                'bg-blue-50 border-blue-300': currentSceneIndex === index,
                'hover:bg-gray-50': currentSceneIndex !== index
              }"
              @click="setCurrentScene(index)"
            >
              <!-- Scene Header -->
              <div class="flex items-center justify-between mb-2">
                <div class="flex items-center gap-2">
                  <span class="text-sm font-medium text-gray-700">Cena {{ index + 1 }}</span>
                  <span v-if="scene.name" class="text-xs text-gray-500">- {{ scene.name }}</span>
                </div>
                <div class="flex items-center gap-1">
                  <button
                    @click.stop="editSceneName(index)"
                    class="p-1 hover:bg-gray-200 rounded"
                    title="Editar nome"
                  >
                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                    </svg>
                  </button>
                  <button
                    @click.stop="duplicateSceneFromPanel(index)"
                    class="p-1 hover:bg-gray-200 rounded"
                    title="Duplicar cena"
                  >
                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"></path>
                    </svg>
                  </button>
                  <button
                    @click.stop="removeSceneFromPanel(index)"
                    :disabled="scenes.length === 1"
                    class="p-1 hover:bg-gray-200 rounded disabled:opacity-50"
                    title="Remover cena"
                  >
                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                    </svg>
                  </button>
                </div>
              </div>

              <!-- Scene Info -->
              <div class="text-xs text-gray-600 space-y-1">
                <div class="flex justify-between">
                  <span>Atores:</span>
                  <span>{{ scene.elements.filter(el => isActor(el)).length }}</span>
                </div>
                <div class="flex justify-between">
                  <span>Objetos:</span>
                  <span>{{ scene.elements.filter(el => !isActor(el)).length }}</span>
                </div>
                <div class="flex justify-between">
                  <span>Palco:</span>
                  <span class="capitalize">{{ getStageShapeName(scene.stage.shape) }}</span>
                </div>
              </div>

              <!-- Scene Preview -->
              <div class="mt-2 h-16 bg-gray-100 rounded border relative overflow-hidden">
                <!-- Mini Stage -->
                <div
                  class="absolute inset-2"
                  :style="getMiniStageStyle(scene.stage)"
                >
                  <!-- Mini Elements -->
                  <div
                    v-for="element in scene.elements"
                    :key="element.id"
                    class="absolute"
                    :style="getMiniElementStyle(element)"
                  >
                    <div
                      v-if="isActor(element)"
                      class="w-2 h-2 rounded-full border"
                      :style="{ backgroundColor: element.color }"
                    ></div>
                    <div
                      v-else
                      class="w-2 h-2 border border-gray-400"
                      :class="{
                        'rounded-full': element.type === 'circle',
                        'rounded': element.type === 'rectangle'
                      }"
                    ></div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- Panel Footer -->
        <div class="border-t border-gray-300 p-4 space-y-2">
          <button
            @click="addSceneFromPanel"
            class="w-full px-3 py-2 bg-blue-600 text-white text-sm rounded hover:bg-blue-700 flex items-center justify-center gap-2"
          >
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
            </svg>
            Nova Cena
          </button>
          <div class="flex gap-2">
            <button
              @click="moveSceneUp(currentSceneIndex)"
              :disabled="currentSceneIndex === 0"
              class="flex-1 px-3 py-2 bg-gray-600 text-white text-sm rounded hover:bg-gray-700 disabled:opacity-50 flex items-center justify-center gap-1"
            >
              <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7"></path>
              </svg>
              Subir
            </button>
            <button
              @click="moveSceneDown(currentSceneIndex)"
              :disabled="currentSceneIndex === scenes.length - 1"
              class="flex-1 px-3 py-2 bg-gray-600 text-white text-sm rounded hover:bg-gray-700 disabled:opacity-50 flex items-center justify-center gap-1"
            >
              <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
              </svg>
              Descer
            </button>
          </div>
        </div>
      </div>
    </div>

    <!-- Actor Dialog -->
    <div v-if="showActorDialog" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
      <div class="bg-white rounded-lg p-6 w-96">
        <h3 class="text-lg font-semibold mb-4">Cadastrar Novo Ator</h3>
        <div class="space-y-4">
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Nome do Ator</label>
            <input
              v-model="newActorName"
              type="text"
              class="w-full px-3 py-2 border border-gray-300 rounded"
              placeholder="Digite o nome do ator..."
              @keyup.enter="addNewActor"
            />
          </div>
        </div>
        <div class="flex gap-3 mt-6">
          <button
            @click="addNewActor"
            class="px-4 py-2 bg-gray-600 text-white rounded hover:bg-gray-700"
          >
            Adicionar
          </button>
          <button
            @click="showActorDialog = false; newActorName = ''"
            class="px-4 py-2 bg-gray-600 text-white rounded hover:bg-gray-700"
          >
            Cancelar
          </button>
        </div>
      </div>
    </div>

    <!-- Stage Editor Dialog -->
    <div v-if="showStageEditor" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
      <div class="bg-white rounded-lg p-6 w-[500px] max-h-[90vh] overflow-y-auto">
        <h3 class="text-lg font-semibold mb-4">Editor de Palco</h3>
        <div class="space-y-6">
          <!-- Forma do Palco -->
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-3">Forma do Palco</label>
            <div class="grid grid-cols-2 gap-3">
              <div 
                v-for="shape in stageShapes" 
                :key="shape.id"
                @click="currentScene.stage.shape = shape.id"
                class="border-2 rounded-lg p-3 cursor-pointer transition-all hover:bg-gray-50"
                :class="currentScene.stage.shape === shape.id ? 'border-blue-500 bg-blue-50' : 'border-gray-200'"
              >
                <div class="text-center">
                  <div class="text-2xl mb-1">{{ shape.preview }}</div>
                  <div class="text-xs font-medium">{{ shape.name }}</div>
                  <div class="text-xs text-gray-500 mt-1">{{ shape.description }}</div>
                </div>
              </div>
            </div>
          </div>

          <!-- Prévia da Forma -->
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Prévia</label>
            <div class="border border-gray-300 rounded-lg p-4 bg-gray-50">
              <div class="w-32 h-20 mx-auto relative">
                <div 
                  class="w-full h-full"
                  :style="getStagePreviewStyle(currentScene.stage)"
                ></div>
              </div>
            </div>
          </div>

          <!-- Configurações de Cor e Opacidade -->
          <div class="grid grid-cols-2 gap-4">
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-1">Cor do Palco</label>
              <input
                v-model="currentScene.stage.color"
                type="color"
                class="w-full h-10 border border-gray-300 rounded"
              />
            </div>
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-1">Opacidade</label>
              <input
                v-model.number="currentScene.stage.opacity"
                type="range"
                min="0.1"
                max="1"
                step="0.1"
                class="w-full"
              />
              <div class="text-xs text-gray-500 text-center mt-1">{{ Math.round(currentScene.stage.opacity * 100) }}%</div>
            </div>
          </div>

          <!-- Dimensões -->
          <div class="grid grid-cols-2 gap-4">
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-1">Largura</label>
              <input
                v-model.number="currentScene.stage.width"
                type="number"
                min="300"
                max="1200"
                step="50"
                class="w-full px-3 py-2 border border-gray-300 rounded"
              />
              <div class="text-xs text-gray-500 mt-1">300px - 1200px</div>
            </div>
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-1">Altura</label>
              <input
                v-model.number="currentScene.stage.height"
                type="number"
                min="200"
                max="800"
                step="50"
                class="w-full px-3 py-2 border border-gray-300 rounded"
              />
              <div class="text-xs text-gray-500 mt-1">200px - 800px</div>
            </div>
          </div>

          <!-- Configurações Avançadas -->
          <div v-if="currentScene.stage.shape === 'ellipse' || currentScene.stage.shape === 'traverse'">
            <label class="block text-sm font-medium text-gray-700 mb-2">Configurações Especiais</label>
            <div v-if="currentScene.stage.shape === 'ellipse'">
              <label class="block text-xs text-gray-600 mb-1">Escala Horizontal</label>
              <input
                v-model.number="currentScene.stage.scaleX"
                type="range"
                min="0.5"
                max="2"
                step="0.1"
                class="w-full"
              />
              <div class="text-xs text-gray-500 text-center">{{ currentScene.stage.scaleX }}x</div>
            </div>
            <div v-if="currentScene.stage.shape === 'traverse'">
              <label class="block text-xs text-gray-600 mb-1">Altura do Palco (%)</label>
              <input
                v-model.number="currentScene.stage.traverseHeight"
                type="range"
                min="40"
                max="80"
                step="5"
                class="w-full"
              />
              <div class="text-xs text-gray-500 text-center">{{ currentScene.stage.traverseHeight }}%</div>
            </div>
          </div>
        </div>
        
        <div class="flex gap-3 mt-6 pt-4 border-t border-gray-200">
          <button
            @click="resetStageToDefault"
            class="px-4 py-2 bg-gray-500 text-white rounded hover:bg-gray-600"
          >
            Resetar
          </button>
          <div class="flex-1"></div>
          <button
            @click="showStageEditor = false"
            class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700"
          >
            Aplicar e Fechar
          </button>
        </div>
      </div>
    </div>

    <!-- Speech Dialog -->
    <div v-if="showSpeechDialog" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
      <div class="bg-white rounded-lg p-6 w-[500px] max-h-[90vh] overflow-y-auto">
        <h3 class="text-lg font-semibold mb-4">
          {{ editingSpeech ? 'Editar Fala' : 'Adicionar Fala' }}
        </h3>
        
        <div class="space-y-4">
          <!-- Personagem -->
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Personagem:</label>
            <p class="text-gray-600 font-medium">{{ selectedActorType?.name || 'Ator selecionado' }}</p>
          </div>
          
          <!-- Texto da Fala -->
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Texto da Fala:</label>
            <textarea
              v-model="speechText"
              class="w-full px-3 py-2 border border-gray-300 rounded resize-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
              rows="3"
              placeholder="Digite a fala do personagem..."
              @keyup.enter.ctrl="addSpeechBubble"
            ></textarea>
            <div class="text-xs text-gray-500 mt-1">Ctrl+Enter para salvar</div>
          </div>
          
          <!-- Estilo da Fala -->
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Estilo da Fala:</label>
            <div class="grid grid-cols-2 gap-2">
              <button
                v-for="style in speechStyles"
                :key="style.id"
                @click="speechStyle = style.id"
                class="p-3 border-2 rounded-lg text-left transition-all hover:bg-gray-50"
                :class="speechStyle === style.id ? 'border-blue-500 bg-blue-50' : 'border-gray-200'"
              >
                <div class="font-medium text-sm">{{ style.icon }} {{ style.name }}</div>
                <div class="text-xs text-gray-500">{{ style.description }}</div>
              </button>
            </div>
          </div>
          
          <!-- Configurações Avançadas -->
          <div class="border-t pt-4">
            <label class="block text-sm font-medium text-gray-700 mb-3">Configurações Avançadas:</label>
            
            <div class="grid grid-cols-2 gap-4">
              <!-- Largura do Balão -->
              <div>
                <label class="block text-xs text-gray-600 mb-1">Largura do Balão</label>
                <select v-model="speechWidth" class="w-full px-2 py-1 border border-gray-300 rounded text-sm">
                  <option value="100px">Pequeno (100px)</option>
                  <option value="120px">Médio (120px)</option>
                  <option value="150px">Grande (150px)</option>
                  <option value="200px">Extra Grande (200px)</option>
                </select>
              </div>
              
              <!-- Tamanho da Fonte -->
              <div>
                <label class="block text-xs text-gray-600 mb-1">Tamanho da Fonte</label>
                <select v-model="speechSize" class="w-full px-2 py-1 border border-gray-300 rounded text-sm">
                  <option value="10px">Pequeno (10px)</option>
                  <option value="12px">Normal (12px)</option>
                  <option value="14px">Grande (14px)</option>
                  <option value="16px">Extra Grande (16px)</option>
                </select>
              </div>
            </div>
          </div>
          
          <!-- Prévia -->
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Prévia:</label>
            <div class="border border-gray-300 rounded-lg p-4 bg-gray-50">
              <div class="relative inline-block">
                <div
                  class="relative bg-white border-2 rounded-lg px-3 py-2 text-xs shadow-lg"
                  :class="{
                    'border-blue-300 bg-blue-50': speechStyle === 'thought',
                    'border-red-300 bg-red-50': speechStyle === 'shout',
                    'border-green-300 bg-green-50': speechStyle === 'whisper',
                    'border-gray-300': !speechStyle || speechStyle === 'normal'
                  }"
                  :style="{
                    maxWidth: speechWidth,
                    fontSize: speechSize,
                    wordWrap: 'break-word'
                  }"
                >
                  {{ speechText || 'Texto de exemplo...' }}
                  <div
                    class="absolute"
                    :class="{
                      'w-0 h-0 border-l-4 border-r-4 border-t-4 border-transparent border-t-white': !speechStyle || speechStyle === 'normal',
                      'w-2 h-2 bg-blue-50 border border-blue-300 rounded-full': speechStyle === 'thought',
                      'w-0 h-0 border-l-6 border-r-6 border-t-6 border-transparent border-t-red-50': speechStyle === 'shout',
                      'w-0 h-0 border-l-2 border-r-2 border-t-2 border-transparent border-t-green-50': speechStyle === 'whisper'
                    }"
                    :style="{
                      left: '15px',
                      bottom: speechStyle === 'thought' ? '-6px' : '-4px'
                    }"
                  ></div>
                </div>
              </div>
            </div>
          </div>
        </div>
        
        <div class="flex gap-3 mt-6 pt-4 border-t border-gray-200">
          <button 
            @click="addSpeechBubble" 
            class="px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600 disabled:opacity-50"
            :disabled="!speechText.trim()"
          >
            {{ editingSpeech ? 'Salvar Alterações' : 'Adicionar Fala' }}
          </button>
          <button @click="cancelSpeechDialog" class="px-4 py-2 bg-gray-500 text-white rounded hover:bg-gray-600">
            Cancelar
          </button>
          <div class="flex-1"></div>
          <button 
            v-if="editingSpeech"
            @click="removeSpeechBubble(selectedActorType)"
            class="px-4 py-2 bg-red-500 text-white rounded hover:bg-red-600"
          >
            Remover Fala
          </button>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, computed, onMounted, onUnmounted, nextTick, watch } from 'vue'

// Icon components (simplified SVG icons)
const MousePointer = { template: '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 15l-2 5L9 9l11 4-5 2zm0 0l5 5M7.188 2.239l.777 2.897M5.136 7.965l-2.898-.777M13.95 4.05l-2.122 2.122m-5.657 5.656l-2.12 2.122"></path></svg>' }
const Hand = { template: '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 11.5V14m0-2.5v-6a1.5 1.5 0 113 0m-3 6a1.5 1.5 0 00-3 0v2a7.5 7.5 0 0015 0v-5a1.5 1.5 0 00-3 0m-6-3V11m0-5.5v-1a1.5 1.5 0 013 0v1m0 0V11m0-5.5a1.5 1.5 0 013 0v3m0 0V11"></path></svg>' }
const MessageCircle = { template: '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path></svg>' }
const Square = { template: '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h12a2 2 0 012 2v12a2 2 0 01-2 2H6a2 2 0 01-2-2V6z"></path></svg>' }
const Circle = { template: '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"></circle></svg>' }
const Triangle = { template: '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 2l9 20H3l9-20z"></path></svg>' }

// State
const scenes = ref([
  {
    id: 1,
    name: 'Cena 1',
    actors: [],
    objects: [],
    stage: {
      type: 'proscenium',
      color: '#8B4513',
      width: 600,
      height: 400,
      shape: 'rectangle',
      opacity: 0.8,
      scaleX: 1,
      traverseHeight: 60
    }
  }
])

const currentSceneIndex = ref(0)
const selectedElement = ref(null)
const selectedTool = ref('select')
const isPlaying = ref(false)
const isPaused = ref(false)
const draggedElement = ref(null)
const zoom = ref(100)
const canvasOffset = ref({ x: 0, y: 0 })
const isPanning = ref(false)
const panStart = ref({ x: 0, y: 0 })
const availableActors = ref([])
const showActorDialog = ref(false)
const newActorName = ref('')
const showStageEditor = ref(false)
const draggedScene = ref(null)
const showSpeechDialog = ref(false)
const speechText = ref('')
const selectedActorType = ref(null)
const editingSpeech = ref(false)
const speechStyle = ref('normal')
const speechWidth = ref('120px')
const speechSize = ref('12px')
const showSidePanel = ref(false)

// Speech styles configuration
const speechStyles = [
  { id: 'normal', name: 'Normal', icon: '💬', description: 'Fala comum' },
  { id: 'thought', name: 'Pensamento', icon: '💭', description: 'Balão de pensamento' },
  { id: 'shout', name: 'Grito', icon: '📢', description: 'Fala alta/grito' },
  { id: 'whisper', name: 'Sussurro', icon: '🤫', description: 'Fala baixa/sussurro' }
]

// Refs
const canvasContainer = ref(null)
const canvas = ref(null)

// Computed
const currentScene = computed(() => scenes.value[currentSceneIndex.value])
const allElements = computed(() => {
  return [...currentScene.value.actors, ...currentScene.value.objects].sort((a, b) => a.zIndex - b.zIndex)
})

// Tools configuration
const tools = [
  { id: 'select', icon: MousePointer, label: 'Selecionar' },
  { id: 'hand', icon: Hand, label: 'Mover Canvas' },
  { id: 'speech', icon: MessageCircle, label: 'Balão de Fala' },
  { id: 'rectangle', icon: Square, label: 'Retângulo' },
  { id: 'circle', icon: Circle, label: 'Círculo' },
  { id: 'triangle', icon: Triangle, label: 'Triângulo' }
]

const stageShapes = [
  { 
    id: 'rectangle', 
    name: 'Retangular', 
    preview: '⬜', 
    description: 'Palco tradicional retangular'
  },
  { 
    id: 'circle', 
    name: 'Circular', 
    preview: '⭕', 
    description: 'Palco circular arena'
  },
  { 
    id: 'ellipse', 
    name: 'Elíptico', 
    preview: '⭕', 
    description: 'Palco oval ajustável'
  },
  { 
    id: 'hexagon', 
    name: 'Hexágono', 
    preview: '⬡', 
    description: 'Palco hexagonal moderno'
  },
  { 
    id: 'octagon', 
    name: 'Octágono', 
    preview: '⭘', 
    description: 'Palco octogonal versátil'
  },
  { 
    id: 'thrust', 
    name: 'Thrust', 
    preview: '🏛️', 
    description: 'Palco que se projeta na plateia'
  },
  { 
    id: 'horseshoe', 
    name: 'Ferradura', 
    preview: '🧲', 
    description: 'Palco em formato de ferradura'
  },
  { 
    id: 'traverse', 
    name: 'Transversal', 
    preview: '➖', 
    description: 'Palco com plateia dos dois lados'
  }
]

const actorColors = ['#3b82f6', '#ef4444', '#8b5cf6', '#10b981', '#f59e0b', '#ec4899', '#14b8a6']

// Methods
const isActor = (element) => {
  return element.name !== undefined && element.color !== undefined && !element.width
}

const setSelectedTool = (toolId) => {
  selectedTool.value = toolId
}

const setCurrentSceneIndex = (index) => {
  currentSceneIndex.value = index
  selectedElement.value = null
}

const addScene = () => {
  const previousScene = scenes.value[currentSceneIndex.value]
  const newScene = {
    id: Date.now(),
    name: `Cena ${scenes.value.length + 1}`,
    actors: previousScene ? [...previousScene.actors.map(actor => ({
      ...actor,
      id: Date.now() + Math.random(),
      speech: actor.speech || ''
    }))] : [],
    objects: previousScene ? [...previousScene.objects.map(obj => ({
      ...obj,
      id: Date.now() + Math.random()
    }))] : [],
    stage: previousScene ? { ...previousScene.stage } : { type: 'proscenium', color: '#8B4513', width: 600, height: 400, shape: 'rectangle', opacity: 0.8, scaleX: 1, traverseHeight: 60 }
  }

  const newScenes = [...scenes.value]
  newScenes.splice(currentSceneIndex.value + 1, 0, newScene)
  scenes.value = newScenes
  currentSceneIndex.value = currentSceneIndex.value + 1
}

const removeScene = () => {
  if (scenes.value.length > 1) {
    const newScenes = scenes.value.filter((_, i) => i !== currentSceneIndex.value)
    scenes.value = newScenes
    currentSceneIndex.value = Math.max(0, currentSceneIndex.value - 1)
  }
}

const duplicateScene = () => {
  const sceneToClone = scenes.value[currentSceneIndex.value]
  const newScene = {
    ...sceneToClone,
    id: Date.now(),
    name: `${sceneToClone.name} (Cópia)`,
    actors: sceneToClone.actors.map(actor => ({
      ...actor,
      id: Date.now() + Math.random()
    })),
    objects: sceneToClone.objects.map(obj => ({
      ...obj,
      id: Date.now() + Math.random()
    }))
  }

  const newScenes = [...scenes.value]
  newScenes.splice(currentSceneIndex.value + 1, 0, newScene)
  scenes.value = newScenes
  currentSceneIndex.value = currentSceneIndex.value + 1
}

const handleCanvasClick = (e) => {
  if (!canvas.value) return

  const rect = canvas.value.getBoundingClientRect()
  const x = e.clientX - rect.left
  const y = e.clientY - rect.top

  if (selectedTool.value === 'rectangle') {
    addObject('rectangle', x, y)
  } else if (selectedTool.value === 'circle') {
    addObject('circle', x, y)
  } else if (selectedTool.value === 'triangle') {
    addObject('triangle', x, y)
  }
}

const addNewActor = () => {
  if (newActorName.value.trim()) {
    const newActor = {
      id: Date.now(),
      name: newActorName.value.trim(),
      color: actorColors[availableActors.value.length % actorColors.length]
    }
    availableActors.value.push(newActor)
    newActorName.value = ''
    showActorDialog.value = false
  }
}

const addActorToScene = (actorType) => {
  const newActor = {
    id: Date.now(),
    name: actorType.name,
    x: 300 + Math.random() * 100,
    y: 200 + Math.random() * 100,
    color: actorType.color,
    zIndex: currentScene.value.actors.length + currentScene.value.objects.length,
    speech: ''
  }

  const newScenes = [...scenes.value]
  newScenes[currentSceneIndex.value].actors.push(newActor)
  scenes.value = newScenes
  selectedElement.value = newActor
}

const updateStage = (newStageProps) => {
  const newScenes = [...scenes.value]
  newScenes[currentSceneIndex.value].stage = { ...newScenes[currentSceneIndex.value].stage, ...newStageProps }
  scenes.value = newScenes
}

const addObject = (type, x, y) => {
  const newObject = {
    id: Date.now(),
    type,
    x: x - 25,
    y: y - 25,
    width: 50,
    height: 50,
    color: '#666666',
    zIndex: currentScene.value.actors.length + currentScene.value.objects.length
  }

  const newScenes = [...scenes.value]
  newScenes[currentSceneIndex.value].objects.push(newObject)
  scenes.value = newScenes
  selectedElement.value = newObject
}

const moveElementLayer = (element, direction) => {
  const newScenes = [...scenes.value]
  const isActorElement = isActor(element)
  const elements = [...newScenes[currentSceneIndex.value].actors, ...newScenes[currentSceneIndex.value].objects]

  const currentZ = element.zIndex
  const targetZ = direction === 'up' ? currentZ + 1 : currentZ - 1

  const targetElement = elements.find(el => el.zIndex === targetZ)
  if (targetElement) {
    element.zIndex = targetZ
    targetElement.zIndex = currentZ
  }

  scenes.value = newScenes
}

const deleteElement = (element) => {
  const newScenes = [...scenes.value]
  const isActorElement = isActor(element)

  if (isActorElement) {
    newScenes[currentSceneIndex.value].actors = newScenes[currentSceneIndex.value].actors.filter(a => a.id !== element.id)
  } else {
    newScenes[currentSceneIndex.value].objects = newScenes[currentSceneIndex.value].objects.filter(o => o.id !== element.id)
  }

  scenes.value = newScenes
  selectedElement.value = null
}

const handleElementMouseDown = (element, e) => {
  e.stopPropagation()
  selectedElement.value = element
  if (selectedTool.value === 'select') {
    draggedElement.value = element
  } else if (selectedTool.value === 'speech' && isActor(element)) {
    selectedActorType.value = element
    showSpeechDialog.value = true
  }
}

const handleMouseMove = (e) => {
  if (draggedElement.value && canvas.value) {
    const rect = canvas.value.getBoundingClientRect()
    const canvasWidth = currentScene.value.stage.width || 600
    const canvasHeight = currentScene.value.stage.height || 400
    
    let x = e.clientX - rect.left
    let y = e.clientY - rect.top
    
    const newScenes = [...scenes.value]
    const isActorElement = isActor(draggedElement.value)

    if (isActorElement) {
      // For actors (32px diameter circles)
      x = Math.max(16, Math.min(canvasWidth - 16, x))
      y = Math.max(16, Math.min(canvasHeight - 16, y))
      
      const actorIndex = newScenes[currentSceneIndex.value].actors.findIndex(a => a.id === draggedElement.value.id)
      if (actorIndex !== -1) {
        // Check collision with other elements
        const actor = newScenes[currentSceneIndex.value].actors[actorIndex]
        const newPosition = { x, y }
        
        if (!checkCollision(newPosition, actor, newScenes[currentSceneIndex.value])) {
          newScenes[currentSceneIndex.value].actors[actorIndex].x = x
          newScenes[currentSceneIndex.value].actors[actorIndex].y = y
        }
      }
    } else {
      // For objects
      const objWidth = draggedElement.value.width || 50
      const objHeight = draggedElement.value.height || 50
      x = Math.max(0, Math.min(canvasWidth - objWidth, x - objWidth/2))
      y = Math.max(0, Math.min(canvasHeight - objHeight, y - objHeight/2))
      
      const objIndex = newScenes[currentSceneIndex.value].objects.findIndex(o => o.id === draggedElement.value.id)
      if (objIndex !== -1) {
        // Check collision with other elements
        const obj = newScenes[currentSceneIndex.value].objects[objIndex]
        const newPosition = { x, y }
        
        if (!checkCollision(newPosition, obj, newScenes[currentSceneIndex.value])) {
          newScenes[currentSceneIndex.value].objects[objIndex].x = x
          newScenes[currentSceneIndex.value].objects[objIndex].y = y
        }
      }
    }

    scenes.value = newScenes
  }
}

const handleMouseUp = () => {
  draggedElement.value = null
}

let playInterval = null

const play = () => {
  isPlaying.value = true
  isPaused.value = false
  setCurrentSceneIndex(0) // Sempre começar da primeira cena

  let sceneIndex = 0

  const playNext = () => {
    if (sceneIndex < scenes.value.length && isPlaying.value && !isPaused.value) {
      setCurrentSceneIndex(sceneIndex)
      sceneIndex++
      if (sceneIndex < scenes.value.length) {
        playInterval = setTimeout(playNext, 2500) // 2.5 segundos por cena
      } else {
        isPlaying.value = false
        playInterval = null
      }
    }
  }

  playInterval = setTimeout(playNext, 100) // Pequeno delay para mostrar a primeira cena
}

const pause = () => {
  isPaused.value = !isPaused.value
  
  if (isPaused.value && playInterval) {
    clearTimeout(playInterval)
    playInterval = null
  } else if (!isPaused.value && isPlaying.value) {
    // Retomar reprodução da cena atual
    let sceneIndex = currentSceneIndex.value + 1
    
    const playNext = () => {
      if (sceneIndex < scenes.value.length && isPlaying.value && !isPaused.value) {
        setCurrentSceneIndex(sceneIndex)
        sceneIndex++
        if (sceneIndex < scenes.value.length) {
          playInterval = setTimeout(playNext, 2500)
        } else {
          isPlaying.value = false
          playInterval = null
        }
      }
    }
    
    if (sceneIndex < scenes.value.length) {
      playInterval = setTimeout(playNext, 2500)
    } else {
      isPlaying.value = false
    }
  }
}

const stopPlay = () => {
  isPlaying.value = false
  isPaused.value = false
  
  if (playInterval) {
    clearTimeout(playInterval)
    playInterval = null
  }
}

const handleZoom = (delta) => {
  const newZoom = Math.max(50, Math.min(200, zoom.value + delta))
  zoom.value = newZoom
}

const handlePanStart = (e) => {
  if (selectedTool.value === 'hand') {
    isPanning.value = true
    panStart.value = { x: e.clientX - canvasOffset.value.x, y: e.clientY - canvasOffset.value.y }
  }
}

const handlePanMove = (e) => {
  if (isPanning.value && selectedTool.value === 'hand') {
    canvasOffset.value = {
      x: e.clientX - panStart.value.x,
      y: e.clientY - panStart.value.y
    }
  }
}

const handlePanEnd = () => {
  isPanning.value = false
}

const getStageStyle = (stage) => {
  const baseStyle = {
    backgroundColor: stage.color,
    opacity: stage.opacity || 0.3,
    position: 'relative',
    transition: 'all 0.3s ease'
  }

  switch (stage.shape) {
    case 'circle':
      return { ...baseStyle, borderRadius: '50%' }
    case 'ellipse':
      return { 
        ...baseStyle, 
        borderRadius: '50%', 
        transform: `scaleX(${stage.scaleX || 1.5})`,
        transformOrigin: 'center'
      }
    case 'hexagon':
      return { 
        ...baseStyle, 
        clipPath: 'polygon(30% 0%, 70% 0%, 100% 50%, 70% 100%, 30% 100%, 0% 50%)'
      }
    case 'octagon':
      return { 
        ...baseStyle, 
        clipPath: 'polygon(30% 0%, 70% 0%, 100% 30%, 100% 70%, 70% 100%, 30% 100%, 0% 70%, 0% 30%)'
      }
    case 'thrust':
      return { 
        ...baseStyle, 
        clipPath: 'polygon(0% 20%, 100% 20%, 80% 100%, 20% 100%)',
        borderRadius: '0 0 20px 20px'
      }
    case 'horseshoe':
      return { 
        ...baseStyle, 
        borderRadius: '50% 50% 0 0', 
        clipPath: 'polygon(0% 0%, 100% 0%, 100% 70%, 80% 100%, 20% 100%, 0% 70%)'
      }
    case 'traverse':
      return { 
        ...baseStyle, 
        height: `${stage.traverseHeight || 60}%`, 
        top: `${(100 - (stage.traverseHeight || 60)) / 2}%`,
        borderRadius: '8px'
      }
    default:
      return baseStyle
  }
}

const handleSceneDragStart = (index, e) => {
  draggedScene.value = index
  e.dataTransfer.effectAllowed = 'move'
}

const handleSceneDrop = (index, e) => {
  e.preventDefault()
  if (draggedScene.value !== null && draggedScene.value !== index) {
    moveScene(draggedScene.value, index)
  }
  draggedScene.value = null
}

const moveScene = (fromIndex, toIndex) => {
  const newScenes = [...scenes.value]
  const [movedScene] = newScenes.splice(fromIndex, 1)
  newScenes.splice(toIndex, 0, movedScene)
  scenes.value = newScenes
  currentSceneIndex.value = toIndex
}

const editSceneName = (index) => {
  const newName = prompt('Nome da cena:', scenes.value[index].name)
  if (newName && newName.trim()) {
    const newScenes = [...scenes.value]
    newScenes[index].name = newName.trim()
    scenes.value = newScenes
  }
}

const saveFlow = () => {
  const flowData = {
    scenes: scenes.value,
    timestamp: new Date().toISOString(),
    version: '2.0'
  }
  
  const dataStr = JSON.stringify(flowData, null, 2)
  const dataBlob = new Blob([dataStr], { type: 'application/json' })
  const url = URL.createObjectURL(dataBlob)
  const link = document.createElement('a')
  link.href = url
  link.download = 'theatre-flow.json'
  link.click()
  URL.revokeObjectURL(url)
}

const loadFlow = () => {
  const input = document.createElement('input')
  input.type = 'file'
  input.accept = '.json'
  input.onchange = (e) => {
    const file = e.target.files[0]
    if (file) {
      const reader = new FileReader()
      reader.onload = (e) => {
        try {
          const flowData = JSON.parse(e.target.result)
          if (flowData.scenes) {
            scenes.value = flowData.scenes
            currentSceneIndex.value = 0
            selectedElement.value = null
          }
        } catch (error) {
          alert('Erro ao carregar arquivo: ' + error.message)
        }
      }
      reader.readAsText(file)
    }
  }
  input.click()
}

const getStagePreviewStyle = (stage) => {
  const baseStyle = {
    backgroundColor: stage.color,
    opacity: stage.opacity || 0.3,
    border: '1px solid #ccc'
  }

  switch (stage.shape) {
    case 'circle':
      return { ...baseStyle, borderRadius: '50%' }
    case 'ellipse':
      return { ...baseStyle, borderRadius: '50%', transform: `scaleX(${stage.scaleX || 1.5})` }
    case 'hexagon':
      return { ...baseStyle, clipPath: 'polygon(30% 0%, 70% 0%, 100% 50%, 70% 100%, 30% 100%, 0% 50%)' }
    case 'octagon':
      return { ...baseStyle, clipPath: 'polygon(30% 0%, 70% 0%, 100% 30%, 100% 70%, 70% 100%, 30% 100%, 0% 70%, 0% 30%)' }
    case 'thrust':
      return { ...baseStyle, clipPath: 'polygon(0% 20%, 100% 20%, 80% 100%, 20% 100%)' }
    case 'horseshoe':
      return { ...baseStyle, borderRadius: '50% 50% 0 0', clipPath: 'polygon(0% 0%, 100% 0%, 100% 70%, 80% 100%, 20% 100%, 0% 70%)' }
    case 'traverse':
      return { ...baseStyle, height: `${stage.traverseHeight || 60}%`, top: `${(100 - (stage.traverseHeight || 60)) / 2}%` }
    default:
      return baseStyle
  }
}

const resetStageToDefault = () => {
  const newScenes = [...scenes.value]
  newScenes[currentSceneIndex.value].stage = {
    shape: 'rectangle',
    color: '#8B4513',
    width: 600,
    height: 400,
    opacity: 0.8,
    scaleX: 1,
    traverseHeight: 60
  }
  scenes.value = newScenes
}

const exportFlow = () => {
  // Simplified export - just save as JSON for now
  saveFlow()
}

const addSpeechBubble = () => {
  if (selectedActorType.value && speechText.value.trim()) {
    const newScenes = [...scenes.value]
    const actorIndex = newScenes[currentSceneIndex.value].actors.findIndex(a => a.id === selectedActorType.value.id)
    if (actorIndex !== -1) {
      newScenes[currentSceneIndex.value].actors[actorIndex].speech = speechText.value.trim()
      newScenes[currentSceneIndex.value].actors[actorIndex].speechStyle = speechStyle.value
      newScenes[currentSceneIndex.value].actors[actorIndex].speechWidth = speechWidth.value
      newScenes[currentSceneIndex.value].actors[actorIndex].speechSize = speechSize.value
    }
    scenes.value = newScenes
    resetSpeechDialog()
  }
}

const editSpeechBubble = (actor) => {
  selectedActorType.value = actor
  speechText.value = actor.speech || ''
  speechStyle.value = actor.speechStyle || 'normal'
  speechWidth.value = actor.speechWidth || '120px'
  speechSize.value = actor.speechSize || '12px'
  editingSpeech.value = true
  showSpeechDialog.value = true
}

const removeSpeechBubble = (actor) => {
  const newScenes = [...scenes.value]
  const actorIndex = newScenes[currentSceneIndex.value].actors.findIndex(a => a.id === actor.id)
  if (actorIndex !== -1) {
    newScenes[currentSceneIndex.value].actors[actorIndex].speech = ''
    newScenes[currentSceneIndex.value].actors[actorIndex].speechStyle = 'normal'
    newScenes[currentSceneIndex.value].actors[actorIndex].speechWidth = '120px'
    newScenes[currentSceneIndex.value].actors[actorIndex].speechSize = '12px'
  }
  scenes.value = newScenes
  resetSpeechDialog()
}

const resetSpeechDialog = () => {
  speechText.value = ''
  speechStyle.value = 'normal'
  speechWidth.value = '120px'
  speechSize.value = '12px'
  editingSpeech.value = false
  showSpeechDialog.value = false
  selectedActorType.value = null
}

const cancelSpeechDialog = () => {
  resetSpeechDialog()
}

// Collision detection function
const checkCollision = (newPosition, currentElement, scene) => {
  const isCurrentActor = isActor(currentElement)
  const currentSize = isCurrentActor ? 32 : { width: currentElement.width || 50, height: currentElement.height || 50 }
  
  // Check collision with actors
  for (const actor of scene.actors) {
    if (actor.id === currentElement.id) continue
    
    const distance = Math.sqrt(
      Math.pow(newPosition.x - actor.x, 2) + Math.pow(newPosition.y - actor.y, 2)
    )
    
    if (isCurrentActor) {
      // Actor to actor collision (both are circles with 32px diameter)
      if (distance < 32) return true
    } else {
      // Object to actor collision
      const objCenterX = newPosition.x + currentSize.width / 2
      const objCenterY = newPosition.y + currentSize.height / 2
      const distanceToActor = Math.sqrt(
        Math.pow(objCenterX - actor.x, 2) + Math.pow(objCenterY - actor.y, 2)
      )
      if (distanceToActor < 16 + Math.min(currentSize.width, currentSize.height) / 2) return true
    }
  }
  
  // Check collision with objects
  for (const obj of scene.objects) {
    if (obj.id === currentElement.id) continue
    
    if (isCurrentActor) {
      // Actor to object collision
      const objCenterX = obj.x + obj.width / 2
      const objCenterY = obj.y + obj.height / 2
      const distanceToObj = Math.sqrt(
        Math.pow(newPosition.x - objCenterX, 2) + Math.pow(newPosition.y - objCenterY, 2)
      )
      if (distanceToObj < 16 + Math.min(obj.width, obj.height) / 2) return true
    } else {
      // Object to object collision (rectangle collision)
      if (newPosition.x < obj.x + obj.width &&
          newPosition.x + currentSize.width > obj.x &&
          newPosition.y < obj.y + obj.height &&
          newPosition.y + currentSize.height > obj.y) {
        return true
      }
    }
  }
  
  return false
}

// Event listeners
onMounted(() => {
  document.addEventListener('mousemove', handleMouseMove)
  document.addEventListener('mouseup', handleMouseUp)
  document.addEventListener('mousemove', handlePanMove)
  document.addEventListener('mouseup', handlePanEnd)
})

// Side Panel Functions
const toggleSidePanel = () => {
  showSidePanel.value = !showSidePanel.value
}

const setCurrentScene = (index) => {
  currentSceneIndex.value = index
}

const addSceneFromPanel = () => {
  addScene()
}

const duplicateSceneFromPanel = (index) => {
  const originalIndex = currentSceneIndex.value
  currentSceneIndex.value = index
  duplicateScene()
  currentSceneIndex.value = originalIndex
}

const removeSceneFromPanel = (index) => {
  if (scenes.value.length === 1) return
  
  scenes.value.splice(index, 1)
  
  if (currentSceneIndex.value >= scenes.value.length) {
    currentSceneIndex.value = scenes.value.length - 1
  } else if (currentSceneIndex.value > index) {
    currentSceneIndex.value--
  }
}

const moveSceneUp = (index) => {
  if (index === 0) return
  
  const scene = scenes.value.splice(index, 1)[0]
  scenes.value.splice(index - 1, 0, scene)
  
  if (currentSceneIndex.value === index) {
    currentSceneIndex.value = index - 1
  } else if (currentSceneIndex.value === index - 1) {
    currentSceneIndex.value = index
  }
}

const moveSceneDown = (index) => {
  if (index === scenes.value.length - 1) return
  
  const scene = scenes.value.splice(index, 1)[0]
  scenes.value.splice(index + 1, 0, scene)
  
  if (currentSceneIndex.value === index) {
    currentSceneIndex.value = index + 1
  } else if (currentSceneIndex.value === index + 1) {
    currentSceneIndex.value = index
  }
}

const getStageShapeName = (shape) => {
  const shapeNames = {
    rectangle: 'Retangular',
    circle: 'Circular',
    ellipse: 'Elíptico',
    hexagon: 'Hexagonal',
    octagon: 'Octogonal',
    thrust: 'Thrust',
    horseshoe: 'Ferradura',
    traverse: 'Traverse'
  }
  return shapeNames[shape] || shape
}

const getMiniStageStyle = (stage) => {
  const baseStyle = {
    backgroundColor: stage.color,
    opacity: stage.opacity || 1,
    width: '100%',
    height: '100%'
  }
  
  switch (stage.shape) {
    case 'circle':
      return { ...baseStyle, borderRadius: '50%' }
    case 'ellipse':
      return { 
        ...baseStyle, 
        borderRadius: '50%',
        transform: `scaleX(${stage.scaleX || 1.5})`
      }
    case 'hexagon':
      return { 
        ...baseStyle, 
        clipPath: 'polygon(25% 0%, 75% 0%, 100% 50%, 75% 100%, 25% 100%, 0% 50%)'
      }
    case 'octagon':
      return { 
        ...baseStyle, 
        clipPath: 'polygon(30% 0%, 70% 0%, 100% 30%, 100% 70%, 70% 100%, 30% 100%, 0% 70%, 0% 30%)'
      }
    case 'thrust':
      return { 
        ...baseStyle, 
        clipPath: 'polygon(0% 0%, 100% 0%, 80% 100%, 20% 100%)'
      }
    case 'horseshoe':
      return { 
        ...baseStyle, 
        borderRadius: '50% 50% 0 0',
        clipPath: 'polygon(0% 0%, 100% 0%, 100% 70%, 80% 100%, 20% 100%, 0% 70%)'
      }
    case 'traverse':
      return { 
        ...baseStyle, 
        height: `${stage.traverseHeight || 60}%`,
        top: '20%'
      }
    default:
      return baseStyle
  }
}

const getMiniElementStyle = (element) => {
  const scaleX = 48 / 400 // Mini canvas scale
  const scaleY = 48 / 300
  
  return {
    left: `${element.x * scaleX}px`,
    top: `${element.y * scaleY}px`
  }
}

onUnmounted(() => {
  document.removeEventListener('mousemove', handleMouseMove)
  document.removeEventListener('mouseup', handleMouseUp)
  document.removeEventListener('mousemove', handlePanMove)
  document.removeEventListener('mouseup', handlePanEnd)
})

// Watch for tool changes to handle pan mode
watch(selectedTool, (newTool) => {
  if (newTool !== 'hand') {
    isPanning.value = false
  }
})
</script>

<style scoped>
/* Additional styles if needed */
</style>
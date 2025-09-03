import { ref, watch, onUnmounted } from 'vue'
import axios from 'axios'

/**
 * Composable para salvamento automático de projetos
 * @param {Object} options - Configurações do autosave
 * @param {number} options.delay - Delay em ms para debounce (padrão: 3000)
 * @param {boolean} options.enabled - Se o autosave está habilitado (padrão: true)
 * @param {string} options.projectId - ID do projeto (opcional para novos projetos)
 */
export function useAutoSave(options = {}) {
  const {
    delay = 3000, // 3 segundos de delay
    enabled = true,
    projectId = null
  } = options

  // Estados
  const isSaving = ref(false)
  const lastSaved = ref(null)
  const hasUnsavedChanges = ref(false)
  const saveError = ref(null)
  const currentProjectId = ref(projectId)

  // Timer para debounce
  let saveTimer = null

  /**
   * Salva o projeto na API
   * @param {Object} projectData - Dados do projeto
   * @param {boolean} isManual - Se é um salvamento manual
   */
  const saveProject = async (projectData, isManual = false) => {
    if (!enabled && !isManual) return
    if (isSaving.value) return

    try {
      isSaving.value = true
      saveError.value = null

      // Ensure we have at least one scene
      const scenesToSave = projectData.scenes && projectData.scenes.length > 0 
        ? projectData.scenes 
        : [{
            id: Date.now(),
            name: 'Cena 1',
            actors: [],
            objects: [],
            stage: {
              type: 'proscenium',
              color: '#8B4513',
              width: 600,
              height: 400,
              shape: 'ellipse',
              opacity: 0.8,
              scaleX: 1,
              traverseHeight: 60
            },
            order: 0
          }]

      const payload = {
        title: projectData.title || 'Projeto sem título',
        description: projectData.description || '',
        data: {
          scenes: scenesToSave,
          settings: projectData.settings || {},
          version: '2.0'
        },
        settings: projectData.globalSettings || {},
        scenes: scenesToSave.map(scene => {
        const sceneData = {
          name: scene.name,
          description: scene.description || '',
          order: scene.order,
          data: {
            actors: scene.actors || [],
            objects: scene.objects || [],
            stage: scene.stage || {}
          },
          stage_config: scene.stage || {}
        }
        
        // Only include ID if it's a valid existing scene ID (not a timestamp)
        if (scene.id && scene.id < 1000000000000) {
          sceneData.id = scene.id
        }
        
        return sceneData
      })
      }

      let response
      if (currentProjectId.value) {
        // Atualizar projeto existente usando autosave endpoint
        response = await axios.post(`/api/projects/${currentProjectId.value}/autosave`, payload)
      } else {
        // Criar novo projeto
        response = await axios.post('/api/projects', payload)
        currentProjectId.value = response.data.project.id
      }

      lastSaved.value = new Date()
      hasUnsavedChanges.value = false

      console.log(`Projeto ${isManual ? 'salvo manualmente' : 'salvo automaticamente'} às ${lastSaved.value.toLocaleTimeString()}`)
      
      return response.data
    } catch (error) {
      console.error('Erro ao salvar projeto:', error)
      saveError.value = error.response?.data?.message || 'Erro ao salvar projeto'
      throw error
    } finally {
      isSaving.value = false
    }
  }

  /**
   * Agenda um salvamento automático
   * @param {Object} projectData - Dados do projeto
   */
  const scheduleAutoSave = (projectData) => {
    if (!enabled) return

    hasUnsavedChanges.value = true

    // Limpa timer anterior
    if (saveTimer) {
      clearTimeout(saveTimer)
    }

    // Agenda novo salvamento
    saveTimer = setTimeout(() => {
      saveProject(projectData, false).catch(error => {
        console.warn('Falha no salvamento automático:', error)
      })
    }, delay)
  }

  /**
   * Força um salvamento manual imediato
   * @param {Object} projectData - Dados do projeto
   */
  const saveNow = async (projectData) => {
    // Cancela salvamento automático pendente
    if (saveTimer) {
      clearTimeout(saveTimer)
      saveTimer = null
    }

    return await saveProject(projectData, true)
  }

  /**
   * Carrega um projeto da API
   * @param {string} id - ID do projeto
   */
  const loadProject = async (id) => {
    try {
      const response = await axios.get(`/api/projects/${id}`)
      currentProjectId.value = id
      hasUnsavedChanges.value = false
      lastSaved.value = new Date(response.data.project.updated_at)
      return response.data.project
    } catch (error) {
      console.error('Erro ao carregar projeto:', error)
      throw error
    }
  }

  /**
   * Lista projetos do usuário
   */
  const listProjects = async () => {
    try {
      const response = await axios.get('/api/projects')
      return response.data.projects
    } catch (error) {
      console.error('Erro ao listar projetos:', error)
      throw error
    }
  }

  /**
   * Cria um watcher para mudanças nos dados
   * @param {Ref} dataRef - Referência reativa dos dados
   * @param {Object} options - Opções do watcher
   */
  const watchForChanges = (dataRef, watchOptions = {}) => {
    return watch(
      dataRef,
      (newData) => {
        if (newData && Object.keys(newData).length > 0) {
          scheduleAutoSave(newData)
        }
      },
      {
        deep: true,
        flush: 'post',
        ...watchOptions
      }
    )
  }

  /**
   * Limpa recursos quando o componente é desmontado
   */
  const cleanup = () => {
    if (saveTimer) {
      clearTimeout(saveTimer)
      saveTimer = null
    }
  }

  // Cleanup automático
  onUnmounted(cleanup)

  // Aviso antes de sair da página se há mudanças não salvas
  const handleBeforeUnload = (event) => {
    if (hasUnsavedChanges.value) {
      event.preventDefault()
      event.returnValue = 'Você tem alterações não salvas. Deseja realmente sair?'
      return event.returnValue
    }
  }

  // Adiciona listener para beforeunload
  if (typeof window !== 'undefined') {
    window.addEventListener('beforeunload', handleBeforeUnload)
    
    onUnmounted(() => {
      window.removeEventListener('beforeunload', handleBeforeUnload)
    })
  }

  return {
    // Estados
    isSaving,
    lastSaved,
    hasUnsavedChanges,
    saveError,
    currentProjectId,
    
    // Métodos
    saveProject,
    scheduleAutoSave,
    saveNow,
    loadProject,
    listProjects,
    watchForChanges,
    cleanup
  }
}

/**
 * Utilitário para formatar timestamp de salvamento
 * @param {Date} date - Data do último salvamento
 */
export function formatLastSaved(date) {
  if (!date) return 'Nunca salvo'
  
  const now = new Date()
  const diff = now - date
  const seconds = Math.floor(diff / 1000)
  const minutes = Math.floor(seconds / 60)
  const hours = Math.floor(minutes / 60)
  
  if (seconds < 60) {
    return 'Salvo agora'
  } else if (minutes < 60) {
    return `Salvo há ${minutes} minuto${minutes > 1 ? 's' : ''}`
  } else if (hours < 24) {
    return `Salvo há ${hours} hora${hours > 1 ? 's' : ''}`
  } else {
    return `Salvo em ${date.toLocaleDateString()} às ${date.toLocaleTimeString()}`
  }
}
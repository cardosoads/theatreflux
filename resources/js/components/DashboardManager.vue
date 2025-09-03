<template>
  <div>
    <!-- Modal para Criar Novo Projeto -->
    <div v-if="showCreateModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
      <div class="bg-white rounded-lg p-6 w-full max-w-md mx-4">
        <div class="flex justify-between items-center mb-4">
          <h3 class="text-lg font-semibold text-gray-900">Criar Novo Projeto</h3>
          <button @click="closeCreateProjectModal" class="text-gray-400 hover:text-gray-600">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
            </svg>
          </button>
        </div>
        
        <form @submit.prevent="handleCreateProject">
          <div class="space-y-4">
            <div>
              <label for="projectTitle" class="block text-sm font-medium text-gray-700 mb-1">Nome do Projeto *</label>
              <input 
                type="text" 
                id="projectTitle" 
                v-model="projectForm.title" 
                required 
                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                placeholder="Ex: Hamlet - Temporada 2025"
              >
            </div>
            
            <div>
              <label for="projectDescription" class="block text-sm font-medium text-gray-700 mb-1">Descrição</label>
              <textarea 
                id="projectDescription" 
                v-model="projectForm.description" 
                rows="3"
                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                placeholder="Descreva brevemente o projeto..."
              ></textarea>
            </div>
          </div>
          
          <div class="flex justify-end space-x-3 mt-6">
            <button 
              type="button" 
              @click="closeCreateProjectModal"
              class="px-4 py-2 text-gray-700 bg-gray-100 hover:bg-gray-200 rounded-md transition-colors duration-200"
            >
              Cancelar
            </button>
            <button 
              type="submit" 
              :disabled="isCreating"
              class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-md transition-colors duration-200"
            >
              <span>{{ isCreating ? 'Criando...' : 'Criar Projeto' }}</span>
              <svg v-if="isCreating" class="animate-spin -ml-1 mr-3 h-5 w-5 text-white inline" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
              </svg>
            </button>
          </div>
        </form>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  name: 'DashboardManager',
  data() {
    return {
      showCreateModal: false,
      isCreating: false,
      projectForm: {
        title: '',
        description: ''
      }
    }
  },
  mounted() {
    // Configurar listener para fechar modal com ESC
    document.addEventListener('keydown', (e) => {
      if (e.key === 'Escape' && this.showCreateModal) {
        this.closeCreateProjectModal();
      }
    });

    // Expor funções no objeto global para compatibilidade com código inline
    window.dashboardManager = {
      createNewProject: this.createNewProject,
      toggleUserMenu: this.toggleUserMenu,
      openProject: this.openProject
    };
  },
   beforeUnmount() {
     // Limpar listener de ESC
     document.removeEventListener('keydown', (e) => {
       if (e.key === 'Escape' && this.showCreateModal) {
         this.closeCreateProjectModal();
       }
     });
     
     // Limpar objeto global
     if (window.dashboardManager) {
       delete window.dashboardManager;
     }
   },
  methods: {
    createNewProject() {
      this.showCreateModal = true;
      this.$nextTick(() => {
        document.getElementById('projectTitle')?.focus();
      });
    },
    
    closeCreateProjectModal() {
      this.showCreateModal = false;
      this.projectForm.title = '';
      this.projectForm.description = '';
      this.isCreating = false;
    },
    
    async handleCreateProject() {
      const { title, description } = this.projectForm;
      
      if (!title.trim()) {
        alert('Por favor, insira um nome para o projeto.');
        return;
      }
      
      this.isCreating = true;
      
      try {
        // Debug: Verificar se o token CSRF está configurado
        console.log('CSRF Token:', document.querySelector('meta[name="csrf-token"]')?.getAttribute('content'));
        console.log('Axios headers:', window.axios.defaults.headers.common);
        console.log('Dados enviados:', { title, description });
        
        const response = await window.axios.post('/api/projects', {
          title: title,
          description: description,
          data: {
            scenes: [],
            settings: {
              canvas: {
                width: 1920,
                height: 1080
              }
            }
          }
        });
        
        console.log('Resposta recebida:', response.data);
        
        if (response.data.success) {
          // Redirecionar para o editor com o ID do projeto
          const projectId = response.data.id || response.data.project?.id;
          if (projectId) {
            window.location.href = `/editor?project=${projectId}`;
          } else {
            alert('Erro: ID do projeto não encontrado na resposta');
          }
        } else {
          alert('Erro ao criar projeto: ' + (response.data.message || 'Erro desconhecido'));
        }
      } catch (error) {
        console.error('Erro na requisição:', error);
        
        if (error.response) {
          const status = error.response.status;
          const data = error.response.data;
          
          switch (status) {
            case 401:
              alert('Sua sessão expirou. Você será redirecionado para o login.');
              window.location.href = '/login';
              break;
            case 422:
              if (data.errors) {
                const errorMessages = Object.values(data.errors).flat();
                alert('Dados inválidos:\n' + errorMessages.join('\n'));
              } else {
                alert('Dados inválidos: ' + (data.message || 'Verifique os campos e tente novamente.'));
              }
              break;
            case 500:
              alert('Erro interno do servidor. Tente novamente em alguns minutos.');
              break;
            default:
              alert('Erro ao criar projeto: ' + (data.message || error.message || 'Erro desconhecido'));
          }
        } else {
          alert('Erro de conexão. Verifique sua internet e tente novamente.');
        }
      } finally {
        this.isCreating = false;
      }
    },
    
    openProject(projectId) {
      window.location.href = `/editor?project=${projectId}`;
    },
    
    toggleUserMenu() {
      const menu = document.getElementById('userMenu');
      if (menu) {
        menu.classList.toggle('hidden');
      }
    },
    
    handleEscKey(e) {
      if (e.key === 'Escape' && this.showCreateModal) {
        this.closeCreateProjectModal();
      }
    }
  }
}
</script>

<style scoped>
/* Estilos específicos do componente se necessário */
</style>
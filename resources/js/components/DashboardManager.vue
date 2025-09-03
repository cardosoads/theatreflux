<template>
  <div>
    <!-- Dashboard Header -->
    <DashboardHeader
      :search-query="searchQuery"
      :sort-type="sortType"
      :sort-direction="sortDirection"
      :search-results-count="filteredProjects.length"
      :total-projects="projects.length"
      @search="handleSearch"
      @clear-search="clearSearch"
      @sort="toggleSort"
      @create-project="createNewProject"
    />
    
    <!-- Dashboard Grid -->
    <DashboardGrid
      :projects="filteredProjects"
      :loading="loading"
      :loading-more="loadingMore"
      :show-create-card="filteredProjects.length > 0 && !searchQuery"
      :show-load-more="showLoadMore"
      @open-project="openProject"
      @create-project="createNewProject"
      @load-more="loadMoreProjects"
      @delete-project="confirmDeleteProject"
    />
    
    <!-- Create Project Modal -->
    <div 
      v-if="showCreateModal" 
      class="fixed inset-0 bg-black/50 backdrop-blur-sm flex items-center justify-center z-50 p-4"
      @click.self="closeCreateProjectModal"
    >
      <Card class="w-full max-w-md mx-auto animate-in fade-in-0 zoom-in-95 duration-200">
        <CardHeader class="pb-4">
          <div class="flex items-center justify-between">
            <CardTitle class="text-xl font-semibold">
              Criar Novo Projeto
            </CardTitle>
            <Button
              variant="ghost"
              size="icon"
              @click="closeCreateProjectModal"
              class="h-8 w-8 rounded-full"
            >
              <XIcon class="w-4 h-4" />
            </Button>
          </div>
          <p class="text-sm text-muted-foreground mt-1">
            Crie um novo projeto teatral para começar a trabalhar
          </p>
        </CardHeader>
        
        <form @submit.prevent="handleCreateProject">
          <CardContent class="space-y-4">
            <div class="space-y-2">
              <Label for="projectTitle">
                Nome do Projeto *
              </Label>
              <Input
                id="projectTitle"
                v-model="projectForm.title"
                placeholder="Ex: Hamlet - Temporada 2025"
                required
                :disabled="isCreating"
              />
            </div>
            
            <div class="space-y-2">
              <Label for="projectDescription">
                Descrição
              </Label>
              <textarea
                id="projectDescription"
                v-model="projectForm.description"
                rows="3"
                :disabled="isCreating"
                class="flex min-h-[80px] w-full rounded-md border border-input bg-background px-3 py-2 text-sm ring-offset-background placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50 resize-none"
                placeholder="Descreva brevemente o projeto..."
              />
            </div>
          </CardContent>
          
          <CardFooter class="flex justify-end space-x-3 pt-4">
            <Button
              type="button"
              variant="outline"
              @click="closeCreateProjectModal"
              :disabled="isCreating"
            >
              Cancelar
            </Button>
            <Button
              type="submit"
              :disabled="isCreating || !projectForm.title.trim()"
              class="min-w-[120px]"
            >
              <template v-if="isCreating">
                <div class="w-4 h-4 border-2 border-white/30 border-t-white rounded-full animate-spin mr-2" />
                Criando...
              </template>
              <template v-else>
                <PlusIcon class="w-4 h-4 mr-2" />
                Criar Projeto
              </template>
            </Button>
          </CardFooter>
        </form>
      </Card>
    </div>
    
    <!-- Delete Confirmation Modal -->
    <div 
      v-if="showDeleteModal" 
      class="fixed inset-0 bg-black/50 backdrop-blur-sm flex items-center justify-center z-50 p-4"
      @click.self="closeDeleteModal"
    >
      <Card class="w-full max-w-md mx-auto animate-in fade-in-0 zoom-in-95 duration-200">
        <CardHeader class="pb-4">
          <div class="flex items-center justify-between">
            <CardTitle class="text-xl font-semibold text-destructive">
              Confirmar Exclusão
            </CardTitle>
            <Button
              variant="ghost"
              size="icon"
              @click="closeDeleteModal"
              class="h-8 w-8 rounded-full"
            >
              <XIcon class="w-4 h-4" />
            </Button>
          </div>
        </CardHeader>
        
        <CardContent class="pb-4">
          <p class="text-sm text-muted-foreground mb-4">
            Tem certeza que deseja excluir o projeto?
          </p>
          <div class="bg-muted/50 rounded-lg p-3 border">
            <h4 class="font-medium text-sm mb-1">{{ projectToDelete?.title }}</h4>
            <p class="text-xs text-muted-foreground" v-if="projectToDelete?.description">
              {{ projectToDelete.description }}
            </p>
          </div>
          <p class="text-xs text-destructive/80 mt-3">
            ⚠️ Esta ação não pode ser desfeita. Todos os dados do projeto serão perdidos permanentemente.
          </p>
        </CardContent>
        
        <CardFooter class="flex justify-end space-x-3 pt-4">
          <Button
            type="button"
            variant="outline"
            @click="closeDeleteModal"
            :disabled="isDeleting"
          >
            Cancelar
          </Button>
          <Button
            type="button"
            variant="destructive"
            @click="deleteProject"
            :disabled="isDeleting"
            class="min-w-[120px]"
          >
            <template v-if="isDeleting">
              <div class="w-4 h-4 border-2 border-white/30 border-t-white rounded-full animate-spin mr-2" />
              Excluindo...
            </template>
            <template v-else>
              Excluir Projeto
            </template>
          </Button>
        </CardFooter>
      </Card>
    </div>
  </div>
</template>

<script>
import { ref, computed, onMounted, onBeforeUnmount } from 'vue';
import { 
  Card,
  CardHeader,
  CardContent,
  CardFooter,
  CardTitle,
  Button,
  Input,
  Label
} from '@/components/ui';
import DashboardHeader from './dashboard/DashboardHeader.vue';
import DashboardGrid from './dashboard/DashboardGrid.vue';
import { XIcon, PlusIcon } from 'lucide-vue-next';

export default {
  name: 'DashboardManager',
  components: {
    Card,
    CardHeader,
    CardContent,
    CardFooter,
    CardTitle,
    Button,
    Input,
    Label,
    DashboardHeader,
    DashboardGrid,
    XIcon,
    PlusIcon
  },
  data() {
    return {
      // Modal state
      showCreateModal: false,
      isCreating: false,
      projectForm: {
        title: '',
        description: ''
      },
      
      // Delete confirmation modal
      showDeleteModal: false,
      isDeleting: false,
      projectToDelete: null,
      
      // Dashboard state
      projects: [],
      loading: false,
      loadingMore: false,
      searchQuery: '',
      sortType: 'updated',
      sortDirection: 'desc',
      showLoadMore: false
    }
  },
  
  computed: {
    filteredProjects() {
      let filtered = [...this.projects];
      
      // Apply search filter
      if (this.searchQuery.trim()) {
        const query = this.searchQuery.toLowerCase();
        filtered = filtered.filter(project => 
          project.title.toLowerCase().includes(query) ||
          (project.description && project.description.toLowerCase().includes(query)) ||
          new Date(project.created_at).toLocaleDateString('pt-BR').includes(query) ||
          new Date(project.updated_at).toLocaleDateString('pt-BR').includes(query)
        );
      }
      
      // Apply sorting
      filtered.sort((a, b) => {
        let aValue, bValue;
        
        switch (this.sortType) {
          case 'name':
            aValue = a.title.toLowerCase();
            bValue = b.title.toLowerCase();
            break;
          case 'date':
            aValue = new Date(a.created_at);
            bValue = new Date(b.created_at);
            break;
          case 'updated':
          default:
            aValue = new Date(a.updated_at);
            bValue = new Date(b.updated_at);
            break;
        }
        
        if (this.sortDirection === 'asc') {
          return aValue > bValue ? 1 : -1;
        } else {
          return aValue < bValue ? 1 : -1;
        }
      });
      
      return filtered;
    }
  },
  async mounted() {
    // Configurar listener para fechar modal com ESC
    document.addEventListener('keydown', this.handleEscKey);

    // Expor funções no objeto global para compatibilidade com código inline
    window.dashboardManager = {
      createNewProject: this.createNewProject,
      toggleUserMenu: this.toggleUserMenu,
      openProject: this.openProject
    };
    
    // Carregar projetos iniciais
    await this.loadProjects();
  },
   beforeUnmount() {
     // Limpar listener de ESC
     document.removeEventListener('keydown', this.handleEscKey);
     
     // Limpar objeto global
     if (window.dashboardManager) {
       delete window.dashboardManager;
     }
   },
   
  methods: {
    // Dashboard methods
    async loadProjects() {
      this.loading = true;
      try {
        // Simular carregamento de projetos - substituir pela API real
        await new Promise(resolve => setTimeout(resolve, 1000));
        
        // Mock data - substituir pela chamada real da API
        this.projects = [
          {
            id: 1,
            title: 'Hamlet - Temporada 2025',
            description: 'Adaptação moderna da clássica peça de Shakespeare',
            created_at: '2024-01-15T10:30:00Z',
            updated_at: '2024-01-20T14:45:00Z',
            status: 'active'
          },
          {
            id: 2,
            title: 'Romeu e Julieta',
            description: 'Romance trágico em cenário contemporâneo',
            created_at: '2024-01-10T09:15:00Z',
            updated_at: '2024-01-18T16:20:00Z',
            status: 'draft'
          },
          {
            id: 3,
            title: 'O Rei Leão Musical',
            description: 'Espetáculo musical familiar baseado no filme da Disney',
            created_at: '2024-01-05T11:00:00Z',
            updated_at: '2024-01-22T13:30:00Z',
            status: 'completed'
          }
        ];
      } catch (error) {
        console.error('Erro ao carregar projetos:', error);
        this.projects = [];
      } finally {
        this.loading = false;
      }
    },
    
    async loadMoreProjects() {
      this.loadingMore = true;
      try {
        // Implementar paginação aqui
        await new Promise(resolve => setTimeout(resolve, 1000));
        // Adicionar mais projetos à lista
      } catch (error) {
        console.error('Erro ao carregar mais projetos:', error);
      } finally {
        this.loadingMore = false;
      }
    },
    
    handleSearch(query) {
      this.searchQuery = query;
    },
    
    clearSearch() {
      this.searchQuery = '';
    },
    
    toggleSort() {
      // Cycle through sort types and directions
      if (this.sortType === 'updated') {
        if (this.sortDirection === 'desc') {
          this.sortDirection = 'asc';
        } else {
          this.sortType = 'name';
          this.sortDirection = 'asc';
        }
      } else if (this.sortType === 'name') {
        if (this.sortDirection === 'asc') {
          this.sortDirection = 'desc';
        } else {
          this.sortType = 'date';
          this.sortDirection = 'desc';
        }
      } else if (this.sortType === 'date') {
        if (this.sortDirection === 'desc') {
          this.sortDirection = 'asc';
        } else {
          this.sortType = 'updated';
          this.sortDirection = 'desc';
        }
      }
    },
    
    handleEscKey(e) {
      if (e.key === 'Escape') {
        if (this.showCreateModal) {
          this.closeCreateProjectModal();
        } else if (this.showDeleteModal) {
          this.closeDeleteModal();
        }
      }
    },
    
    // Project methods
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
    
    // Delete project methods
    confirmDeleteProject(projectId) {
      const project = this.projects.find(p => p.id === projectId);
      if (project) {
        this.projectToDelete = project;
        this.showDeleteModal = true;
      }
    },
    
    closeDeleteModal() {
      this.showDeleteModal = false;
      this.projectToDelete = null;
      this.isDeleting = false;
    },
    
    async deleteProject() {
      if (!this.projectToDelete) return;
      
      this.isDeleting = true;
      
      try {
        const response = await window.axios.delete(`/api/projects/${this.projectToDelete.id}`);
        
        if (response.data.success) {
          // Remove o projeto da lista local
          this.projects = this.projects.filter(p => p.id !== this.projectToDelete.id);
          this.closeDeleteModal();
        } else {
          alert('Erro ao excluir projeto: ' + (response.data.message || 'Erro desconhecido'));
        }
      } catch (error) {
        console.error('Erro ao excluir projeto:', error);
        
        if (error.response) {
          const status = error.response.status;
          const data = error.response.data;
          
          switch (status) {
            case 401:
              alert('Sua sessão expirou. Você será redirecionado para o login.');
              window.location.href = '/login';
              break;
            case 404:
              alert('Projeto não encontrado.');
              break;
            case 500:
              alert('Erro interno do servidor. Tente novamente em alguns minutos.');
              break;
            default:
              alert('Erro ao excluir projeto: ' + (data.message || error.message || 'Erro desconhecido'));
          }
        } else {
          alert('Erro de conexão. Verifique sua internet e tente novamente.');
        }
      } finally {
        this.isDeleting = false;
      }
    }
  }
}
</script>

<style scoped>
/* Estilos específicos do componente se necessário */
</style>
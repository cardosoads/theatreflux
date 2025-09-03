<template>
  <div class="space-y-6">
    <!-- Projects Grid -->
    <div 
      :class="cn(
        'grid gap-6 transition-all duration-300',
        gridCols
      )"
    >
      <!-- Project Cards -->
      <DashboardCard
        v-for="(project, index) in projects"
        :key="project.id"
        :title="project.title"
        :description="project.description"
        :updated-at="project.updated_at"
        :project-id="project.id"
        :color-index="index"
        :status="project.status"
        @click="openProject"
        @delete="deleteProject"
      />
      
      <!-- Create New Project Card -->
      <CreateProjectCard
        v-if="showCreateCard"
        @click="createNewProject"
      />
    </div>
    
    <!-- Empty State -->
    <div 
      v-if="projects.length === 0 && !loading"
      class="col-span-full flex flex-col items-center justify-center py-16 px-4"
    >
      <div class="text-center max-w-md">
        <!-- Empty State Icon -->
        <div class="w-20 h-20 mx-auto mb-6 rounded-full bg-gray-100 flex items-center justify-center">
          <FileTextIcon class="w-10 h-10 text-gray-400" />
        </div>
        
        <!-- Empty State Content -->
        <h3 class="text-xl font-semibold text-gray-900 mb-3">
          {{ emptyStateTitle }}
        </h3>
        
        <p class="text-gray-600 mb-8 leading-relaxed">
          {{ emptyStateDescription }}
        </p>
        
        <!-- Empty State Action -->
        <Button
          @click="createNewProject"
          size="lg"
          class="inline-flex items-center space-x-2"
        >
          <PlusIcon class="w-5 h-5" />
          <span>{{ emptyStateButtonText }}</span>
        </Button>
      </div>
    </div>
    
    <!-- Loading State -->
    <div 
      v-if="loading"
      :class="cn(
        'grid gap-6',
        gridCols
      )"
    >
      <div
        v-for="n in skeletonCount"
        :key="n"
        class="animate-pulse"
      >
        <Card class="h-48">
          <CardHeader class="pb-3">
            <div class="flex items-start justify-between">
              <div class="w-12 h-12 bg-gray-200 rounded-xl" />
              <div class="w-16 h-5 bg-gray-200 rounded" />
            </div>
          </CardHeader>
          
          <CardContent class="space-y-3">
            <div class="w-3/4 h-6 bg-gray-200 rounded" />
            <div class="w-full h-4 bg-gray-200 rounded" />
            <div class="w-2/3 h-4 bg-gray-200 rounded" />
          </CardContent>
          
          <CardFooter class="pt-0 flex justify-between">
            <div class="w-20 h-4 bg-gray-200 rounded" />
            <div class="w-12 h-6 bg-gray-200 rounded" />
          </CardFooter>
        </Card>
      </div>
    </div>
    
    <!-- Load More Button -->
    <div 
      v-if="showLoadMore"
      class="flex justify-center pt-8"
    >
      <Button
        variant="outline"
        @click="loadMore"
        :disabled="loadingMore"
        class="flex items-center space-x-2"
      >
        <template v-if="loadingMore">
          <div class="w-4 h-4 border-2 border-gray-300 border-t-gray-600 rounded-full animate-spin" />
          <span>Carregando...</span>
        </template>
        <template v-else>
          <RefreshCwIcon class="w-4 h-4" />
          <span>Carregar Mais</span>
        </template>
      </Button>
    </div>
  </div>
</template>

<script setup>
import { computed } from 'vue';
import { cn } from '@/utils/cn';
import { 
  Card,
  CardHeader,
  CardContent,
  CardFooter,
  Button 
} from '@/components/ui';
import DashboardCard from './DashboardCard.vue';
import CreateProjectCard from './CreateProjectCard.vue';
import { 
  FileTextIcon,
  PlusIcon,
  RefreshCwIcon 
} from 'lucide-vue-next';

/**
 * DashboardGrid Component
 * 
 * Grid responsivo para exibir projetos com estados de loading e empty
 * Inclui paginação e skeleton loading
 */

const props = defineProps({
  /**
   * Lista de projetos
   */
  projects: {
    type: Array,
    default: () => []
  },
  
  /**
   * Estado de carregamento inicial
   */
  loading: {
    type: Boolean,
    default: false
  },
  
  /**
   * Estado de carregamento de mais itens
   */
  loadingMore: {
    type: Boolean,
    default: false
  },
  
  /**
   * Se deve mostrar o card de criar projeto
   */
  showCreateCard: {
    type: Boolean,
    default: true
  },
  
  /**
   * Se deve mostrar o botão de carregar mais
   */
  showLoadMore: {
    type: Boolean,
    default: false
  },
  
  /**
   * Número de colunas no grid
   */
  columns: {
    type: String,
    default: 'auto',
    validator: (value) => ['auto', '1', '2', '3', '4', '5', '6'].includes(value)
  },
  
  /**
   * Título do estado vazio
   */
  emptyStateTitle: {
    type: String,
    default: 'Nenhum projeto encontrado'
  },
  
  /**
   * Descrição do estado vazio
   */
  emptyStateDescription: {
    type: String,
    default: 'Você ainda não criou nenhum projeto. Comece criando seu primeiro projeto teatral!'
  },
  
  /**
   * Texto do botão no estado vazio
   */
  emptyStateButtonText: {
    type: String,
    default: 'Criar Primeiro Projeto'
  }
});

const emit = defineEmits([
  'open-project',
  'create-project',
  'load-more',
  'delete-project'
]);

// Computed properties
const gridCols = computed(() => {
  if (props.columns === 'auto') {
    return 'grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4';
  }
  
  const colsMap = {
    '1': 'grid-cols-1',
    '2': 'grid-cols-1 md:grid-cols-2',
    '3': 'grid-cols-1 md:grid-cols-2 lg:grid-cols-3',
    '4': 'grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4',
    '5': 'grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 2xl:grid-cols-5',
    '6': 'grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 2xl:grid-cols-6'
  };
  
  return colsMap[props.columns] || colsMap['4'];
});

const skeletonCount = computed(() => {
  // Número de skeletons baseado no número de colunas
  const colsCount = {
    '1': 3,
    '2': 4,
    '3': 6,
    '4': 8,
    '5': 10,
    '6': 12
  };
  
  return colsCount[props.columns] || 8;
});

// Methods
const openProject = (projectId) => {
  emit('open-project', projectId);
};

const createNewProject = () => {
  emit('create-project');
};

const loadMore = () => {
  emit('load-more');
};

const deleteProject = (projectId) => {
  emit('delete-project', projectId);
};
</script>
<template>
  <div class="mb-8">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
      <!-- Title Section -->
      <div class="space-y-1">
        <h2 class="text-3xl font-bold tracking-tight text-gray-900">
          PROJETOS
        </h2>
        <p class="text-sm text-muted-foreground">
          Gerencie seus projetos teatrais
        </p>
      </div>
      
      <!-- Actions Section -->
      <div class="flex items-center space-x-3">
        <!-- Search Input -->
        <div class="relative flex-1 max-w-md">
          <SearchIcon class="absolute left-3 top-1/2 transform -translate-y-1/2 w-4 h-4 text-muted-foreground" />
          <Input
            :value="searchQuery"
            @input="handleSearchInput"
            placeholder="Buscar projetos por nome ou data..."
            class="pl-10 pr-10 w-full"
          />
          <Button
            v-if="searchQuery"
            variant="ghost"
            size="icon"
            class="absolute right-1 top-1/2 transform -translate-y-1/2 h-7 w-7"
            @click="clearSearch"
          >
            <XIcon class="w-3 h-3" />
          </Button>
        </div>
        
        <!-- Sort Button -->
        <Button
          variant="outline"
          size="sm"
          @click="toggleSort"
          class="flex items-center space-x-2"
        >
          <component :is="sortIcon" class="w-4 h-4" />
          <span class="hidden sm:inline">{{ sortText }}</span>
        </Button>
        
        <!-- Create Project Button -->
        <Button
          @click="createProject"
          class="flex items-center space-x-2"
        >
          <PlusIcon class="w-4 h-4" />
          <span class="hidden sm:inline">Novo Projeto</span>
        </Button>
      </div>
    </div>
    
    <!-- Search Results Info -->
    <div 
      v-if="showSearchResults" 
      class="mt-4 p-3 bg-blue-50 border border-blue-200 rounded-lg flex items-center space-x-2"
    >
      <InfoIcon class="w-4 h-4 text-blue-500 flex-shrink-0" />
      <span class="text-sm text-blue-700">
        {{ searchResultsText }}
      </span>
      <Button
        variant="ghost"
        size="sm"
        @click="clearSearch"
        class="ml-auto text-blue-600 hover:text-blue-800"
      >
        Limpar
      </Button>
    </div>
  </div>
</template>

<script setup>
import { computed, ref } from 'vue';
import { cn } from '@/utils/cn';
import { 
  Button,
  Input 
} from '@/components/ui';
import { 
  SearchIcon,
  XIcon,
  PlusIcon,
  InfoIcon,
  ArrowUpDownIcon,
  CalendarIcon,
  SortAscIcon
} from 'lucide-vue-next';

/**
 * DashboardHeader Component
 * 
 * Cabeçalho modernizado do dashboard com busca, ordenação e ações
 * Utiliza componentes Shadcn/UI para consistência visual
 */

const props = defineProps({
  /**
   * Query de busca atual
   */
  searchQuery: {
    type: String,
    default: ''
  },
  
  /**
   * Tipo de ordenação atual
   */
  sortType: {
    type: String,
    default: 'date',
    validator: (value) => ['date', 'name', 'updated'].includes(value)
  },
  
  /**
   * Direção da ordenação
   */
  sortDirection: {
    type: String,
    default: 'desc',
    validator: (value) => ['asc', 'desc'].includes(value)
  },
  
  /**
   * Número de resultados encontrados na busca
   */
  searchResultsCount: {
    type: Number,
    default: 0
  },
  
  /**
   * Total de projetos
   */
  totalProjects: {
    type: Number,
    default: 0
  }
});

const emit = defineEmits([
  'search',
  'clear-search', 
  'sort',
  'create-project'
]);

// Computed properties
const showSearchResults = computed(() => {
  return props.searchQuery.length > 0;
});

const searchResultsText = computed(() => {
  if (props.searchResultsCount === 0) {
    return `Nenhum projeto encontrado para "${props.searchQuery}"`;
  }
  
  const plural = props.searchResultsCount === 1 ? 'projeto encontrado' : 'projetos encontrados';
  return `${props.searchResultsCount} ${plural} para "${props.searchQuery}"`;
});

const sortIcon = computed(() => {
  switch (props.sortType) {
    case 'name':
      return SortAscIcon;
    case 'date':
    case 'updated':
      return CalendarIcon;
    default:
      return ArrowUpDownIcon;
  }
});

const sortText = computed(() => {
  const direction = props.sortDirection === 'asc' ? 'Crescente' : 'Decrescente';
  
  switch (props.sortType) {
    case 'name':
      return `Nome ${direction}`;
    case 'date':
      return `Data ${direction}`;
    case 'updated':
      return `Atualização ${direction}`;
    default:
      return 'Ordenar';
  }
});

// Methods
const handleSearchInput = (event) => {
  const value = event.target.value;
  emit('search', value);
};

const clearSearch = () => {
  emit('clear-search');
};

const toggleSort = () => {
  emit('sort');
};

const createProject = () => {
  emit('create-project');
};
</script>
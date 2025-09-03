<template>
  <Card 
    :variant="variant" 
    :class="cn(
      'group cursor-pointer transition-all duration-300 hover:shadow-lg hover:-translate-y-1',
      'bg-gradient-to-br from-card to-muted/20',
      'border-border hover:border-primary/30',
      className
    )"
    @click="handleClick"
  >
    <CardHeader class="pb-3">
      <div class="flex items-start justify-between">
        <div class="relative">
          <div 
            :class="cn(
              'w-12 h-12 rounded-xl flex items-center justify-center shadow-md',
              'group-hover:shadow-lg transition-all duration-300',
              iconGradient
            )"
          >
            <component 
              :is="iconComponent" 
              class="w-6 h-6 text-white" 
            />
          </div>
        </div>
        
        <div class="flex items-center gap-2">
          <!-- Status Badge (opcional) -->
          <Badge v-if="status" :variant="statusVariant" class="text-xs">
            {{ status }}
          </Badge>
          
          <!-- Delete Button -->
          <Button
            variant="ghost"
            size="icon"
            class="h-8 w-8 opacity-0 group-hover:opacity-100 transition-opacity duration-200 hover:bg-destructive/10 hover:text-destructive"
            @click.stop="handleDelete"
          >
            <TrashIcon class="w-4 h-4" />
          </Button>
        </div>
      </div>
    </CardHeader>
    
    <CardContent class="flex-1 pb-4">
      <div class="space-y-2">
        <CardTitle class="text-lg font-semibold text-foreground group-hover:text-primary transition-colors">
          {{ title }}
        </CardTitle>
        
        <p class="text-sm text-muted-foreground line-clamp-2">
          {{ description || 'Projeto Teatral' }}
        </p>
      </div>
    </CardContent>
    
    <CardFooter class="pt-0 flex items-center justify-between">
      <div class="flex items-center space-x-2 text-xs text-muted-foreground">
        <CalendarIcon class="w-3 h-3" />
        <span>{{ formattedDate }}</span>
      </div>
      
      <div class="flex items-center gap-2 opacity-0 group-hover:opacity-100 transition-opacity duration-200">
        <Button variant="ghost" size="sm" class="h-7 px-2 text-xs">
          Abrir
          <ArrowRightIcon class="w-3 h-3 ml-1" />
        </Button>
      </div>
    </CardFooter>
  </Card>
</template>

<script setup>
import { computed } from 'vue';
import { cn } from '@/utils/cn';
import { 
  Card, 
  CardHeader, 
  CardContent, 
  CardFooter, 
  CardTitle,
  Button,
  Badge 
} from '@/components/ui';
import { 
  FileTextIcon, 
  CalendarIcon, 
  ArrowRightIcon,
  TrashIcon 
} from 'lucide-vue-next';

/**
 * DashboardCard Component
 * 
 * Card component otimizado para exibir projetos no dashboard
 * Utiliza componentes Shadcn/UI para consistência visual
 */

const props = defineProps({
  /**
   * Título do projeto
   */
  title: {
    type: String,
    required: true
  },
  
  /**
   * Descrição do projeto
   */
  description: {
    type: String,
    default: ''
  },
  
  /**
   * Data de atualização do projeto
   */
  updatedAt: {
    type: String,
    required: true
  },
  
  /**
   * ID do projeto para navegação
   */
  projectId: {
    type: [String, Number],
    required: true
  },
  
  /**
   * Variante do card
   */
  variant: {
    type: String,
    default: 'default'
  },
  
  /**
   * Classes CSS adicionais
   */
  className: {
    type: String,
    default: ''
  },
  
  /**
   * Status do projeto (opcional)
   */
  status: {
    type: String,
    default: ''
  },
  
  /**
   * Índice para variação de cores
   */
  colorIndex: {
    type: Number,
    default: 0
  }
});

const emit = defineEmits(['click', 'delete']);

// Computed properties
const iconComponent = computed(() => FileTextIcon);

const iconGradient = computed(() => {
  const gradients = [
    'bg-gradient-to-br from-primary to-primary/80',
    'bg-gradient-to-br from-secondary to-secondary/80', 
    'bg-gradient-to-br from-accent to-accent/80',
    'bg-gradient-to-br from-muted to-muted/80',
    'bg-gradient-to-br from-primary/80 to-primary',
    'bg-gradient-to-br from-secondary/80 to-secondary'
  ];
  return gradients[props.colorIndex % gradients.length];
});

const statusVariant = computed(() => {
  switch (props.status?.toLowerCase()) {
    case 'ativo':
    case 'publicado':
      return 'default';
    case 'rascunho':
      return 'secondary';
    case 'arquivado':
      return 'outline';
    default:
      return 'secondary';
  }
});

const formattedDate = computed(() => {
  try {
    const date = new Date(props.updatedAt);
    return date.toLocaleDateString('pt-BR', {
      day: '2-digit',
      month: 'short',
      year: 'numeric'
    });
  } catch (error) {
    return props.updatedAt;
  }
});

// Methods
const handleClick = () => {
  emit('click', props.projectId);
};

const handleDelete = () => {
  emit('delete', props.projectId);
};
</script>

<style scoped>
.line-clamp-2 {
  display: -webkit-box;
  -webkit-line-clamp: 2;
  -webkit-box-orient: vertical;
  overflow: hidden;
}
</style>
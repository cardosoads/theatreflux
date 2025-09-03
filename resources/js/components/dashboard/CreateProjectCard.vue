<template>
  <Card 
    :class="cn(
      'group cursor-pointer transition-all duration-300 hover:shadow-lg hover:-translate-y-1',
      'bg-gradient-to-br from-muted/30 to-muted/10',
      'border-2 border-dashed border-border hover:border-primary/50',
      'hover:bg-gradient-to-br hover:from-muted/50 hover:to-muted/20',
      className
    )"
    @click="handleClick"
  >
    <CardContent class="flex flex-col items-center justify-center text-center p-8 min-h-[200px]">
      <!-- Animated Plus Icon -->
      <div class="relative mb-6">
        <div 
          :class="cn(
            'w-16 h-16 rounded-2xl flex items-center justify-center',
            'bg-gradient-to-br from-primary to-primary/80',
            'shadow-lg group-hover:shadow-xl transition-all duration-300',
            'group-hover:scale-110 group-hover:rotate-3'
          )"
        >
          <PlusIcon 
            class="w-8 h-8 text-primary-foreground transition-transform duration-300 group-hover:rotate-90" 
          />
        </div>
        
        <!-- Floating Animation Dots -->
        <div class="absolute -top-2 -right-2 w-3 h-3 bg-primary/60 rounded-full opacity-60 animate-ping" />
        <div class="absolute -bottom-2 -left-2 w-2 h-2 bg-primary/40 rounded-full opacity-40 animate-pulse" />
      </div>
      
      <!-- Content -->
      <div class="space-y-3">
        <CardTitle class="text-xl font-bold text-foreground">
          Criar Novo Projeto
        </CardTitle>
        
        <p class="text-sm text-muted-foreground max-w-xs leading-relaxed">
          Comece um novo projeto teatral do zero com nossas ferramentas avançadas
        </p>
      </div>
      
      <!-- Action Button -->
      <div 
        :class="cn(
          'mt-6 opacity-0 group-hover:opacity-100 transition-all duration-300',
          'transform translate-y-2 group-hover:translate-y-0'
        )"
      >
        <Button 
          variant="outline" 
          size="sm" 
          class="bg-background/80 backdrop-blur-sm border-border hover:bg-background hover:border-primary/50"
        >
          <SparklesIcon class="w-4 h-4 mr-2" />
          Começar Agora
        </Button>
      </div>
      
      <!-- Background Pattern -->
      <div class="absolute inset-0 opacity-5 pointer-events-none overflow-hidden rounded-xl">
        <div class="absolute top-4 left-4 w-2 h-2 bg-muted-foreground rounded-full" />
        <div class="absolute top-8 right-6 w-1 h-1 bg-muted-foreground rounded-full" />
        <div class="absolute bottom-6 left-8 w-1.5 h-1.5 bg-muted-foreground rounded-full" />
        <div class="absolute bottom-4 right-4 w-2 h-2 bg-muted-foreground rounded-full" />
        
        <!-- Subtle Grid Pattern -->
        <svg class="absolute inset-0 w-full h-full" xmlns="http://www.w3.org/2000/svg">
          <defs>
            <pattern id="grid" width="20" height="20" patternUnits="userSpaceOnUse">
              <path d="M 20 0 L 0 0 0 20" fill="none" stroke="currentColor" stroke-width="0.5" opacity="0.1"/>
            </pattern>
          </defs>
          <rect width="100%" height="100%" fill="url(#grid)" class="text-muted-foreground" />
        </svg>
      </div>
    </CardContent>
  </Card>
</template>

<script setup>
import { cn } from '@/utils/cn';
import { 
  Card, 
  CardContent, 
  CardTitle,
  Button 
} from '@/components/ui';
import { 
  PlusIcon,
  SparklesIcon 
} from 'lucide-vue-next';

/**
 * CreateProjectCard Component
 * 
 * Card especial para criação de novos projetos
 * Design diferenciado com animações e call-to-action
 */

const props = defineProps({
  /**
   * Classes CSS adicionais
   */
  className: {
    type: String,
    default: ''
  }
});

const emit = defineEmits(['click']);

// Methods
const handleClick = () => {
  emit('click');
};
</script>

<style scoped>
/* Animações customizadas se necessário */
@keyframes float {
  0%, 100% {
    transform: translateY(0px);
  }
  50% {
    transform: translateY(-4px);
  }
}

.animate-float {
  animation: float 3s ease-in-out infinite;
}
</style>
<template>
  <div class="p-8 space-y-6">
    <!-- Theme Toggle Test -->
    <div class="flex items-center justify-between">
      <h1 class="text-2xl font-bold text-foreground">Shadcn UI Test Component</h1>
      <div class="flex items-center gap-2">
        <span class="text-sm text-muted-foreground">Tema: {{ theme }}</span>
        <Button @click="toggleTheme" variant="outline">
          {{ isDark ? '☀️ Light' : '🌙 Dark' }}
        </Button>
      </div>
    </div>

    <!-- Card Test -->
    <Card class="w-full max-w-md">
      <CardHeader>
        <CardTitle class="text-card-foreground">Test Card</CardTitle>
        <CardDescription class="text-muted-foreground">
          Este é um teste do componente Card do Shadcn UI
        </CardDescription>
      </CardHeader>
      <CardContent>
        <div class="space-y-4">
          <!-- Input Test -->
          <div>
            <label class="text-sm font-medium text-foreground">Test Input:</label>
            <Input 
              v-model="testInput" 
              placeholder="Digite algo aqui..."
              class="mt-1"
            />
          </div>
          
          <!-- Display input value -->
          <p class="text-sm text-muted-foreground">
            Valor: {{ testInput || 'Nenhum valor digitado' }}
          </p>
        </div>
      </CardContent>
      <CardFooter class="space-x-2">
        <Button variant="default">Primary</Button>
        <Button variant="secondary">Secondary</Button>
        <Button variant="outline">Outline</Button>
      </CardFooter>
    </Card>

    <!-- Button Variants Test -->
    <div class="space-y-4">
      <h2 class="text-lg font-semibold text-foreground">Button Variants</h2>
      <div class="flex flex-wrap gap-2">
        <Button variant="default">Default</Button>
        <Button variant="destructive">Destructive</Button>
        <Button variant="outline">Outline</Button>
        <Button variant="secondary">Secondary</Button>
        <Button variant="ghost">Ghost</Button>
        <Button variant="link">Link</Button>
      </div>
    </div>

    <!-- Button Sizes Test -->
    <div class="space-y-4">
      <h2 class="text-lg font-semibold text-foreground">Button Sizes</h2>
      <div class="flex items-center gap-2">
        <Button size="sm">Small</Button>
        <Button size="default">Default</Button>
        <Button size="lg">Large</Button>
        <Button size="icon">🎯</Button>
      </div>
    </div>

    <!-- Input Variants Test -->
    <div class="space-y-4">
      <h2 class="text-lg font-semibold text-foreground">Input Variants</h2>
      <div class="space-y-2">
        <Input placeholder="Default input" />
        <Input placeholder="Small input" size="sm" />
        <Input placeholder="Large input" size="lg" />
        <Input placeholder="Disabled input" disabled />
      </div>
    </div>

    <!-- Theme Colors Test -->
    <div class="space-y-4">
      <h2 class="text-lg font-semibold text-foreground">Theme Colors</h2>
      <div class="grid grid-cols-2 gap-4 text-sm">
        <div class="p-3 bg-background text-foreground border border-border rounded">
          Background + Foreground
        </div>
        <div class="p-3 bg-card text-card-foreground border border-border rounded">
          Card + Card Foreground
        </div>
        <div class="p-3 bg-primary text-primary-foreground rounded">
          Primary + Primary Foreground
        </div>
        <div class="p-3 bg-secondary text-secondary-foreground rounded">
          Secondary + Secondary Foreground
        </div>
        <div class="p-3 bg-muted text-muted-foreground rounded">
          Muted + Muted Foreground
        </div>
        <div class="p-3 bg-accent text-accent-foreground rounded">
          Accent + Accent Foreground
        </div>
        <div class="p-3 bg-destructive text-destructive-foreground rounded">
          Destructive + Destructive Foreground
        </div>
        <div class="p-3 border border-input bg-background text-foreground rounded">
          Input Border + Background
        </div>
      </div>
    </div>

    <!-- Debug Info -->
    <div class="space-y-4 mt-8 p-4 bg-muted rounded-lg">
      <h2 class="text-lg font-semibold text-foreground">Debug Info</h2>
      <div class="text-sm text-muted-foreground space-y-1">
        <p>Tema atual: <span class="font-mono text-foreground">{{ theme }}</span></p>
        <p>isDark: <span class="font-mono text-foreground">{{ isDark }}</span></p>
        <p>Classe no HTML: <span class="font-mono text-foreground">{{ htmlClass }}</span></p>
        <p>CSS Variables funcionando: <span class="font-mono" :class="cssVarsWorking ? 'text-green-600' : 'text-red-600'">{{ cssVarsWorking ? 'Sim' : 'Não' }}</span></p>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue';
import { useTheme } from '@/composables/useTheme';
import { 
  Button, 
  Input, 
  Card, 
  CardHeader, 
  CardContent, 
  CardFooter, 
  CardTitle, 
  CardDescription 
} from '@/components/ui';

// Theme management
const { theme, toggleTheme, isDark } = useTheme();

// Test data
const testInput = ref('');

// Debug info
const htmlClass = computed(() => {
  if (typeof document !== 'undefined') {
    return document.documentElement.className || 'nenhuma classe';
  }
  return 'SSR';
});

const cssVarsWorking = computed(() => {
  if (typeof window !== 'undefined' && typeof document !== 'undefined') {
    const style = getComputedStyle(document.documentElement);
    const bgColor = style.getPropertyValue('--background').trim();
    return bgColor !== '';
  }
  return false;
});

// Initialize theme on mount
onMounted(() => {
  console.log('ShadcnTestComponent mounted');
  console.log('Current theme:', theme.value);
  console.log('HTML classes:', document.documentElement.className);
});
</script>
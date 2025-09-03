# Análise Técnica: Implementação do Canvas SVG com Konva.js

## Visão Geral

Esta análise técnica detalha a implementação do Canvas SVG interativo usando Konva.js para o projeto MarcaeDeixa.com. O Canvas é a funcionalidade central do sistema, permitindo a criação e manipulação de elementos teatrais (atores e objetos cênicos) em um ambiente visual interativo.

## Estado Atual

Atualmente, o sistema possui:
- Estrutura base do editor (`TheatreFlowEditor.vue`)
- Painel de propriedades (`PropertiesPanel.vue`)
- Timeline de animação (`AnimationTimeline.vue`)
- Status de salvamento (`SaveStatus.vue`)
- Sistema de autosave (`useAutoSave.js`)

No entanto, a implementação do Canvas SVG interativo com Konva.js ainda não foi realizada, representando 0% de conclusão desta funcionalidade crítica.

## Requisitos Técnicos

Segundo o contrato, o Canvas SVG deve permitir:

1. **Inserção e movimentação** de até 30 elementos arrastáveis (atores e objetos cênicos)
2. **Agrupamento e desagrupamento** de elementos para movimentação conjunta
3. **Nomeação de elementos** com até 2 caracteres exibidos no elemento e legenda lateral
4. **Guias de alinhamento** com linhas tracejadas para facilitar o posicionamento
5. **Opções de alinhamento**: horizontal, vertical, centralizado, distribuído

## Arquitetura Proposta

### Componentes Principais

1. **CanvasEditor.vue**
   - Componente principal que encapsula o Konva.Stage
   - Gerencia o estado do canvas (zoom, pan, seleção)
   - Coordena a interação entre as ferramentas e o canvas

2. **KonvaLayer.vue**
   - Wrapper para Konva.Layer
   - Gerencia camadas do canvas (elementos, guias, grid)

3. **TheatreElement.vue**
   - Representa um elemento teatral no canvas
   - Implementa comportamentos de drag, resize, rotate
   - Gerencia aparência visual e estado de seleção

4. **ElementFactory.js**
   - Cria diferentes tipos de elementos teatrais
   - Aplica propriedades padrão baseadas no tipo

5. **SelectionManager.js**
   - Gerencia seleção única e múltipla de elementos
   - Implementa transformações em grupo (mover, redimensionar)

6. **AlignmentGuides.vue**
   - Renderiza guias de alinhamento dinâmicas
   - Implementa snap-to-grid e snap-to-element

7. **GroupManager.js**
   - Gerencia agrupamento e desagrupamento de elementos
   - Mantém hierarquia de grupos e elementos

### Fluxo de Dados

```
+----------------+      +----------------+      +----------------+
|                |      |                |      |                |
|  User Actions  +----->+  Store/State   +----->+  Konva Canvas  |
|                |      |                |      |                |
+----------------+      +----------------+      +----------------+
                              ^                       |
                              |                       |
                              |                       v
                        +----------------+      +----------------+
                        |                |      |                |
                        |  API/Backend   |<-----+  Events        |
                        |                |      |                |
                        +----------------+      +----------------+
```

## Implementação Detalhada

### 1. Setup Inicial do Konva.js

```javascript
// CanvasEditor.vue
import { Stage, Layer, Rect, Circle, Text } from 'konva/lib/index';
import { ref, onMounted, watch } from 'vue';

export default {
  setup() {
    const stageRef = ref(null);
    const containerRef = ref(null);
    let stage = null;
    let layer = null;
    
    onMounted(() => {
      // Inicializar o Stage Konva
      stage = new Stage({
        container: containerRef.value,
        width: 800,
        height: 600,
        draggable: false
      });
      
      // Criar camada principal
      layer = new Layer();
      stage.add(layer);
      
      // Configurar eventos básicos
      stage.on('click', handleStageClick);
      stage.on('dragstart', handleDragStart);
      stage.on('dragend', handleDragEnd);
    });
    
    // Funções de manipulação de eventos...
    
    return {
      containerRef,
      stageRef
    };
  }
};
```

### 2. Criação de Elementos Teatrais

```javascript
// ElementFactory.js
export default {
  createActor(config) {
    return {
      type: 'actor',
      x: config.x || 0,
      y: config.y || 0,
      width: config.width || 50,
      height: config.height || 50,
      fill: config.fill || '#FF9A3C',
      stroke: config.stroke || '#E86A33',
      strokeWidth: config.strokeWidth || 2,
      draggable: true,
      name: config.name || 'A',
      id: config.id || `actor-${Date.now()}`
    };
  },
  
  createProp(config) {
    return {
      type: 'prop',
      x: config.x || 0,
      y: config.y || 0,
      width: config.width || 40,
      height: config.height || 40,
      fill: config.fill || '#61A3BA',
      stroke: config.stroke || '#2D4356',
      strokeWidth: config.strokeWidth || 2,
      draggable: true,
      name: config.name || 'P',
      id: config.id || `prop-${Date.now()}`
    };
  },
  
  // Outros tipos de elementos...
};
```

### 3. Sistema de Seleção

```javascript
// SelectionManager.js
export default class SelectionManager {
  constructor(stage, layer) {
    this.stage = stage;
    this.layer = layer;
    this.selectedElements = [];
    this.transformer = new Konva.Transformer({
      nodes: [],
      enabledAnchors: ['top-left', 'top-right', 'bottom-left', 'bottom-right'],
      rotateEnabled: true,
      borderStroke: '#0D99FF',
      borderStrokeWidth: 2,
      anchorStroke: '#0D99FF',
      anchorFill: '#FFFFFF',
      anchorSize: 8
    });
    
    this.layer.add(this.transformer);
    this.setupEvents();
  }
  
  setupEvents() {
    this.stage.on('click', (e) => {
      // Deselecionar quando clicar no stage vazio
      if (e.target === this.stage) {
        this.clearSelection();
        return;
      }
      
      // Verificar se o elemento clicado é selecionável
      const clickedElement = e.target;
      if (clickedElement.hasName('selectable')) {
        // Verificar tecla shift para seleção múltipla
        if (e.evt.shiftKey) {
          this.toggleSelection(clickedElement);
        } else {
          this.selectSingle(clickedElement);
        }
      }
    });
  }
  
  selectSingle(element) {
    this.clearSelection();
    this.selectedElements = [element];
    this.transformer.nodes([element]);
    this.layer.draw();
  }
  
  toggleSelection(element) {
    const index = this.selectedElements.indexOf(element);
    if (index >= 0) {
      // Remover da seleção
      this.selectedElements.splice(index, 1);
    } else {
      // Adicionar à seleção
      this.selectedElements.push(element);
    }
    this.transformer.nodes(this.selectedElements);
    this.layer.draw();
  }
  
  clearSelection() {
    this.selectedElements = [];
    this.transformer.nodes([]);
    this.layer.draw();
  }
  
  // Outros métodos de seleção...
}
```

### 4. Movimentação e Redimensionamento

```javascript
// TheatreElement.vue
import { computed, onMounted, watch } from 'vue';
import { Rect, Circle, Text } from 'konva/lib/index';

export default {
  props: {
    element: {
      type: Object,
      required: true
    },
    selected: {
      type: Boolean,
      default: false
    }
  },
  
  setup(props, { emit }) {
    const konvaElement = ref(null);
    
    onMounted(() => {
      // Criar elemento Konva baseado no tipo
      if (props.element.type === 'actor') {
        konvaElement.value = new Rect({
          ...props.element,
          name: 'selectable',
          cornerRadius: 5
        });
      } else if (props.element.type === 'prop') {
        konvaElement.value = new Circle({
          ...props.element,
          name: 'selectable',
          radius: props.element.width / 2
        });
      }
      
      // Configurar eventos de drag
      konvaElement.value.on('dragstart', () => {
        emit('dragstart', props.element.id);
      });
      
      konvaElement.value.on('dragmove', () => {
        emit('update', {
          id: props.element.id,
          x: konvaElement.value.x(),
          y: konvaElement.value.y()
        });
      });
      
      konvaElement.value.on('dragend', () => {
        emit('dragend', props.element.id);
      });
      
      // Adicionar texto de identificação
      const nameText = new Text({
        text: props.element.name,
        fontSize: 14,
        fill: '#000000',
        align: 'center',
        verticalAlign: 'middle',
        width: props.element.width,
        height: props.element.height,
        x: 0,
        y: 0,
        listening: false
      });
      
      konvaElement.value.add(nameText);
    });
    
    // Observar mudanças nas propriedades
    watch(() => props.element, (newVal) => {
      if (konvaElement.value) {
        konvaElement.value.setAttrs({
          x: newVal.x,
          y: newVal.y,
          width: newVal.width,
          height: newVal.height,
          fill: newVal.fill,
          stroke: newVal.stroke,
          strokeWidth: newVal.strokeWidth
        });
      }
    }, { deep: true });
    
    return {
      konvaElement
    };
  }
};
```

### 5. Sistema de Agrupamento

```javascript
// GroupManager.js
export default class GroupManager {
  constructor(stage, layer, selectionManager) {
    this.stage = stage;
    this.layer = layer;
    this.selectionManager = selectionManager;
    this.groups = [];
  }
  
  createGroup() {
    const selectedElements = this.selectionManager.selectedElements;
    if (selectedElements.length < 2) return;
    
    // Criar novo grupo
    const group = new Konva.Group({
      draggable: true,
      name: 'selectable',
      id: `group-${Date.now()}`
    });
    
    // Adicionar elementos ao grupo
    selectedElements.forEach(element => {
      const parent = element.getParent();
      element.remove();
      group.add(element);
    });
    
    this.layer.add(group);
    this.groups.push(group);
    
    // Selecionar o novo grupo
    this.selectionManager.selectSingle(group);
    this.layer.draw();
    
    return group;
  }
  
  ungroup() {
    const selectedElements = this.selectionManager.selectedElements;
    const groupsToUngroup = selectedElements.filter(el => el instanceof Konva.Group);
    
    if (groupsToUngroup.length === 0) return;
    
    const newSelection = [];
    
    groupsToUngroup.forEach(group => {
      const children = group.getChildren();
      const parent = group.getParent();
      
      children.forEach(child => {
        // Ajustar posição absoluta
        const absPos = child.getAbsolutePosition();
        child.remove();
        parent.add(child);
        child.setAbsolutePosition(absPos);
        newSelection.push(child);
      });
      
      // Remover grupo vazio
      const groupIndex = this.groups.indexOf(group);
      if (groupIndex >= 0) {
        this.groups.splice(groupIndex, 1);
      }
      group.destroy();
    });
    
    // Selecionar elementos desagrupados
    this.selectionManager.selectedElements = newSelection;
    this.selectionManager.transformer.nodes(newSelection);
    this.layer.draw();
  }
}
```

### 6. Guias de Alinhamento

```javascript
// AlignmentGuides.vue
import { Line } from 'konva/lib/index';
import { ref, onMounted, watch } from 'vue';

export default {
  props: {
    stage: {
      type: Object,
      required: true
    },
    layer: {
      type: Object,
      required: true
    },
    elements: {
      type: Array,
      required: true
    },
    snapThreshold: {
      type: Number,
      default: 10
    }
  },
  
  setup(props) {
    const guides = ref([]);
    
    onMounted(() => {
      // Criar linhas guias
      const verticalGuide = new Line({
        points: [0, 0, 0, 0],
        stroke: '#0D99FF',
        strokeWidth: 1,
        dash: [5, 5],
        visible: false,
        listening: false
      });
      
      const horizontalGuide = new Line({
        points: [0, 0, 0, 0],
        stroke: '#0D99FF',
        strokeWidth: 1,
        dash: [5, 5],
        visible: false,
        listening: false
      });
      
      guides.value = [verticalGuide, horizontalGuide];
      guides.value.forEach(guide => props.layer.add(guide));
      
      // Configurar eventos de drag para mostrar guias
      props.stage.on('dragmove', handleDragMove);
      props.stage.on('dragend', hideGuides);
    });
    
    const handleDragMove = (e) => {
      const dragElement = e.target;
      if (!dragElement.hasName('selectable')) return;
      
      const dragRect = dragElement.getClientRect();
      const dragCenterX = dragRect.x + dragRect.width / 2;
      const dragCenterY = dragRect.y + dragRect.height / 2;
      
      let nearestX = null;
      let nearestY = null;
      let minXDistance = props.snapThreshold;
      let minYDistance = props.snapThreshold;
      
      // Verificar alinhamento com outros elementos
      props.elements.forEach(element => {
        if (element.id === dragElement.id()) return;
        
        const elementNode = props.stage.findOne(`#${element.id}`);
        if (!elementNode) return;
        
        const elementRect = elementNode.getClientRect();
        const elementCenterX = elementRect.x + elementRect.width / 2;
        const elementCenterY = elementRect.y + elementRect.height / 2;
        
        // Verificar alinhamento central
        const xDistance = Math.abs(dragCenterX - elementCenterX);
        const yDistance = Math.abs(dragCenterY - elementCenterY);
        
        if (xDistance < minXDistance) {
          minXDistance = xDistance;
          nearestX = elementCenterX;
        }
        
        if (yDistance < minYDistance) {
          minYDistance = yDistance;
          nearestY = elementCenterY;
        }
      });
      
      // Mostrar guias de alinhamento
      if (nearestX !== null) {
        const verticalGuide = guides.value[0];
        verticalGuide.points([nearestX, 0, nearestX, props.stage.height()]);
        verticalGuide.visible(true);
        
        // Snap to guide
        const newX = nearestX - dragRect.width / 2;
        dragElement.x(newX);
      } else {
        guides.value[0].visible(false);
      }
      
      if (nearestY !== null) {
        const horizontalGuide = guides.value[1];
        horizontalGuide.points([0, nearestY, props.stage.width(), nearestY]);
        horizontalGuide.visible(true);
        
        // Snap to guide
        const newY = nearestY - dragRect.height / 2;
        dragElement.y(newY);
      } else {
        guides.value[1].visible(false);
      }
      
      props.layer.batchDraw();
    };
    
    const hideGuides = () => {
      guides.value.forEach(guide => guide.visible(false));
      props.layer.batchDraw();
    };
    
    return {
      guides
    };
  }
};
```

## Integração com o Sistema Existente

Para integrar o Canvas SVG com Konva.js ao sistema existente, será necessário:

1. **Substituir o Canvas Atual**: Substituir a div que atualmente representa o canvas no `TheatreFlowEditor.vue` pelo componente `CanvasEditor.vue`.

2. **Conectar com o Painel de Propriedades**: Estabelecer comunicação entre o canvas e o `PropertiesPanel.vue` para edição de propriedades dos elementos selecionados.

3. **Integrar com a Timeline**: Conectar eventos do canvas com o componente `AnimationTimeline.vue` para sincronização de animações.

4. **Implementar Autosave**: Utilizar o composable `useAutoSave.js` existente para salvar automaticamente as alterações no canvas.

## Plano de Implementação

### Fase 1: Setup Básico (1-2 semanas)
1. Instalar Konva.js e configurar imports
2. Criar componente CanvasEditor.vue básico
3. Implementar renderização de stage e layer
4. Integrar com o TheatreFlowEditor.vue existente

### Fase 2: Elementos Básicos (2-3 semanas)
1. Implementar ElementFactory.js
2. Criar componentes para diferentes tipos de elementos
3. Implementar inserção de elementos via toolbar
4. Conectar com o painel de propriedades

### Fase 3: Interatividade (2-3 semanas)
1. Implementar SelectionManager.js
2. Adicionar suporte para seleção única e múltipla
3. Implementar drag & drop de elementos
4. Adicionar redimensionamento e rotação

### Fase 4: Funcionalidades Avançadas (2-3 semanas)
1. Implementar GroupManager.js
2. Adicionar guias de alinhamento
3. Implementar opções de alinhamento (horizontal, vertical, etc.)
4. Adicionar sistema de nomeação de elementos

## Conclusão

A implementação do Canvas SVG com Konva.js é a funcionalidade mais crítica do projeto MarcaeDeixa.com, representando aproximadamente 60% do desenvolvimento restante. Esta análise técnica fornece um plano detalhado para sua implementação, dividido em fases incrementais que permitem validação contínua e ajustes conforme necessário.

O foco inicial deve ser na criação de um protótipo funcional que valide a integração entre Vue.js e Konva.js, seguido pela implementação incremental das funcionalidades requeridas no contrato. A arquitetura proposta segue os princípios de componentização, separação de responsabilidades e reatividade, alinhados com as melhores práticas de desenvolvimento Vue.js.
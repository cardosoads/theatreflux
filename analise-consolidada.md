# Análise Consolidada do Projeto MarcaeDeixa.com

Este documento consolida a análise do projeto MarcaeDeixa.com, comparando o contrato original (`contrato.md`) com o estado atual do sistema (analisado em `analise-projeto.md`). O objetivo é identificar claramente as funcionalidades implementadas, as funcionalidades pendentes e os elementos que estão fora do escopo.

## 1. Funcionalidades Implementadas

Com base na análise de `analise-projeto.md`, as seguintes funcionalidades foram implementadas:

-   Sistema de Autenticação (Login, Logout, Registro)
-   Dashboard (Listagem de projetos, Busca, Filtros, Ordenação, Criação/Exclusão de projetos)
-   Sistema de Projetos (CRUD completo de projetos, Autosave, Compartilhamento com tokens, API RESTful)
-   Área Administrativa (Gerenciamento de usuários, Controle de roles/permissões, CRUD de usuários)
-   Sistema de Perfil (Edição de perfil, Upload/remoção de imagem, Atualização de dados)
-   Componentes Shadcn/UI (Base e Utilitários)
-   Estrutura Base do Editor de Fluxos Teatrais (Canvas, Painel de propriedades, Timeline, Sistema de elementos, Status de salvamento)

## 2. Funcionalidades Pendentes (Com Base no Contrato)

As seguintes funcionalidades, especificadas no `contrato.md`, ainda não foram implementadas:

### 2.1 Canvas Interativo com SVG
-   Inserção e movimentação de até 30 elementos arrastáveis (atores e objetos cênicos)
-   Agrupamento e desagrupamento de elementos para movimentação conjunta
-   Nomeação de elementos com até 2 caracteres exibidos no elemento e legenda lateral
-   Guias de alinhamento com linhas tracejadas para facilitar o posicionamento
-   Opções de alinhamento: horizontal, vertical, centralizado, distribuído

### 2.2 Criação de Cenas e Atos
-   Slides representando cenas individuais
-   Possibilidade de adicionar títulos para agrupar cenas em "Atos"
-   Navegação entre cenas com estrutura organizada por Ato

### 2.3 Desenhos
-   Desenhos de palco: fixos e repetidos em todos os slides (ex: estrutura do cenário)
-   Desenhos de cena: exclusivos de cada slide (ex: marcações temporárias, anotações visuais)

### 2.4 Exportação e Download
-   Exportar slides como imagens (JPG ou PNG)
-   Exportar como SVG animado

### 2.5 Notas e Anotações
-   Campo de descrição por cena
-   Anotações gerais por coreografia

## 3. Funcionalidades Pendentes (Melhorias e Adições)

As seguintes funcionalidades são melhorias ou adições que não estão explicitamente no contrato, mas foram identificadas como pendentes em `analise-projeto.md`:

-   Página Inicial (Seções "Como Funciona", Depoimentos, Preços, Footer completo, Animações avançadas, Call-to-actions otimizados)
-   Dashboard (Filtros avançados, Visualização em lista/grid, Ações em lote, Estatísticas/métricas, Projetos recentes/favoritos, Sistema de tags/categorias)
-   Sistema de Colaboração (Convites, Permissões, Comentários, Histórico, Notificações)
-   Sistema Premium (Assinaturas, Gateway de pagamento, Controle de limites, Funcionalidades exclusivas, Suporte prioritário, Colaboração em Tempo Real)
-   Testes (Unit, Feature, Browser, API)

## 4. Funcionalidades Fora do Escopo (Identificadas)

Com base na análise, as seguintes funcionalidades foram identificadas como fora do escopo do contrato atual:

-   Sistema Premium (Assinaturas, Gateway de pagamento, Controle de limites)
-   Colaboração em Tempo Real (WebSockets/Socket.io, Cursores colaborativos, Edição simultânea)
-   Funcionalidades avançadas do editor (Biblioteca de avatars/ícones, Templates de cenários pré-definidos, Biblioteca de ações teatrais)
-   Integrações externas e Analytics
-   Suporte multilíngue

## 5. Análise do Editor de Fluxos Teatrais Existente

O editor de fluxos teatrais existente possui a seguinte estrutura (arquivos):

-   `resources/views/editor.blade.php` - Template Blade para a página do editor
-   `resources/js/components/TheatreFlowEditor.vue` - Componente principal do editor
-   `resources/js/components/TheatreElement.vue` - Componente para elementos teatrais
-   `resources/js/components/PropertiesPanel.vue` - Painel de propriedades para edição de elementos
-   `resources/js/components/AnimationTimeline.vue` - Timeline para animações
-   `resources/js/components/SaveStatus.vue` - Componente para status de salvamento
-   `resources/js/composables/useAutoSave.js` - Composable para autosave

Atualmente, o editor possui a estrutura básica implementada, incluindo:

-   Canvas principal para edição (mas sem SVG interativo completo)
-   Painel de propriedades para edição de elementos
-   Timeline para animações
-   Sistema básico de elementos teatrais
-   Status de salvamento automático

No entanto, faltam as seguintes funcionalidades essenciais:

-   Implementação completa do Canvas SVG interativo com Konva.js
-   Sistema de agrupamento e desagrupamento de elementos
-   Guias de alinhamento e opções de alinhamento
-   Sistema completo de cenas e atos
-   Ferramentas de desenho para palco e cena
-   Exportação para diferentes formatos
-   Sistema de notas e anotações

## 6. Próximos Passos

Com base na análise, os próximos passos recomendados são:

1.  Implementar o Canvas Interativo com SVG usando Konva.js
    -   Inserção e movimentação de elementos
    -   Agrupamento e desagrupamento
    -   Nomeação de elementos
    -   Guias de alinhamento

2.  Desenvolver o sistema de Cenas e Atos
    -   Estrutura de slides para cenas
    -   Agrupamento em atos
    -   Navegação entre cenas

3.  Implementar as ferramentas de Desenho
    -   Desenhos de palco (fixos)
    -   Desenhos de cena (por slide)

4.  Adicionar funcionalidade de Exportação
    -   Exportação como imagens (JPG/PNG)
    -   Exportação como SVG animado

5.  Implementar o sistema de Notas e Anotações
    -   Descrição por cena
    -   Anotações gerais

6.  Desenvolver testes automatizados para garantir a qualidade do código
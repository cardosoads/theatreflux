# Arquitetura do Projeto MarcaeDeixa.com

## Visão Geral
O MarcaeDeixa.com é uma plataforma web para criação e gerenciamento de fluxos teatrais, permitindo que diretores e coreógrafos organizem suas produções através de um canvas interativo. O sistema possibilita a inserção e manipulação de elementos cênicos (atores e objetos), criação de cenas e atos, adição de anotações, e exportação do trabalho em diferentes formatos.

## Decisões Arquiteturais

### Padrão Arquitetural
- **Escolha:** Monolito Modular (Laravel + Vue.js)
- **Rationale:** O projeto é um MVP com escopo bem definido e sem necessidade inicial de alta escalabilidade. A arquitetura monolítica modular permite desenvolvimento rápido e manutenção simplificada, enquanto mantém a separação de responsabilidades através de módulos bem definidos.

### Stack Tecnológica
- **Backend:** Laravel (PHP)
- **Frontend:** Vue.js
- **Canvas Interativo:** Konva.js
- **Banco de Dados:** MySQL
- **Infraestrutura:** VPS

## Estrutura do Projeto

### Frontend (Vue.js)
```
/resources/js/
├── components/           # Componentes reutilizáveis
│   ├── editor/          # Componentes específicos do editor
│   │   ├── Canvas.vue   # Componente principal do canvas
│   │   ├── elements/    # Elementos teatrais (atores, objetos)
│   │   ├── scenes/      # Gerenciamento de cenas
│   │   ├── tools/       # Ferramentas de edição
│   │   ├── export/      # Funcionalidades de exportação
│   │   └── annotations/ # Sistema de anotações
├── views/               # Páginas da aplicação
├── composables/         # Lógica reutilizável (hooks)
├── services/            # Serviços para lógica de negócio
├── store/               # Gerenciamento de estado global
├── api/                 # Comunicação com o backend
└── utils/               # Funções utilitárias
```

### Backend (Laravel)
```
/app/
├── Http/
│   └── Controllers/     # Controllers para endpoints da API
├── Services/            # Serviços para lógica de negócio
├── Repositories/        # Acesso a dados
├── Models/              # Modelos de dados
└── DTOs/                # Objetos de transferência de dados
/database/
└── migrations/          # Estrutura do banco de dados
```

## Camadas e Responsabilidades

### Frontend

#### Componentes de UI
- **Responsabilidade:** Renderização e interação com o usuário
- **Componentes:** Formulários, botões, modais, cards, etc.

#### Editor de Fluxos Teatrais
- **Responsabilidade:** Manipulação do canvas SVG e elementos teatrais
- **Componentes:**
  - **Canvas:** Área de trabalho principal usando Konva.js
  - **Elementos:** Atores e objetos cênicos arrastáveis
  - **Cenas:** Gerenciamento de slides e atos
  - **Ferramentas:** Inserção, seleção, agrupamento, alinhamento
  - **Exportação:** Geração de imagens e SVG animado
  - **Anotações:** Sistema de notas por cena e gerais

#### Serviços
- **Responsabilidade:** Lógica de negócio no frontend
- **Componentes:** Manipulação de dados, validações, transformações

#### Store
- **Responsabilidade:** Gerenciamento de estado global
- **Componentes:** Estado do editor, projetos, usuário, etc.

#### API
- **Responsabilidade:** Comunicação com o backend
- **Componentes:** Requisições HTTP, tratamento de respostas

### Backend

#### Controllers
- **Responsabilidade:** Receber requisições e retornar respostas
- **Componentes:** Endpoints da API, validação de inputs

#### Services
- **Responsabilidade:** Lógica de negócio
- **Componentes:** Regras de negócio, orquestração de operações

#### Repositories
- **Responsabilidade:** Acesso a dados
- **Componentes:** Queries, operações CRUD

#### Models
- **Responsabilidade:** Representação dos dados
- **Componentes:** Entidades, relacionamentos, validações

## Padrões de Design Utilizados

### Frontend
- **Component Pattern:** Encapsulamento de UI e lógica em componentes reutilizáveis
- **Composition API:** Para gerenciamento de estado e lógica complexa
- **Observer Pattern:** Para reatividade e sincronização de estado
- **Command Pattern:** Para operações de edição no canvas (undo/redo)
- **Factory Pattern:** Para criação de elementos teatrais

### Backend
- **Repository Pattern:** Para abstração do acesso a dados
- **Service Layer:** Para encapsular lógica de negócio
- **DTO (Data Transfer Objects):** Para transferência de dados entre camadas

## TODO List

### 🟡 Em Andamento
- [ ] Setup Konva.js - Instalar Konva.js, configurar imports Vue, criar componente CanvasEditor.vue básico
- [ ] Elementos Básicos Canvas - Implementar inserção de retângulos, círculos, texto e toolbar
- [ ] Sistema de Seleção - Implementar seleção única, múltipla e indicadores visuais
- [ ] Movimentação e Redimensionamento - Drag & drop, resize com handles, snap to grid

### 📋 Backlog
- [ ] Sistema de Agrupamento - Agrupar/desagrupar elementos, hierarquia, indicadores visuais
- [ ] Sistema de Nomeação - Nomear elementos, edição inline, painel hierarquia, busca
- [ ] Backend de Cenas - Migration scenes, model Scene, relacionamentos, API endpoints
- [ ] Interface de Navegação Cenas - Painel cenas, tabs, previews, reordenação
- [ ] Sistema de Desenhos - Ferramentas pincel, linha, borracha, formas de palco
- [ ] Exportação PNG/JPG/SVG - Export imagens, SVG animado, configurações qualidade

### ✅ Concluído
- [x] Estrutura base do projeto - Laravel + Vue.js
- [x] Sistema de Autenticação - Login, Logout, Registro
- [x] Dashboard - Listagem de projetos, Busca, Filtros, Ordenação, Criação/Exclusão
- [x] Sistema de Projetos - CRUD completo, Autosave, Compartilhamento com tokens
- [x] Área Administrativa - Gerenciamento de usuários, Controle de roles/permissões
- [x] Sistema de Perfil - Edição de perfil, Upload/remoção de imagem
- [x] Componentes Shadcn/UI - Base e Utilitários
- [x] Estrutura Base do Editor - Canvas, Painel de propriedades, Timeline, Status de salvamento

## Considerações de Qualidade

### Performance
- Otimização do Canvas SVG para suportar até 30 elementos sem lag
- Lazy loading para carregamento eficiente de projetos grandes
- Compressão de assets e minificação de código
- Monitoramento de performance do canvas em tempo real

### Segurança
- Autenticação e autorização robustas
- Validação de inputs no frontend e backend
- Proteção contra CSRF, XSS e SQL Injection
- Tokens de acesso para compartilhamento seguro

### Escalabilidade
- Estrutura modular que permite crescimento
- Separação clara entre frontend e backend via API
- Possibilidade futura de migração para microserviços se necessário
- Otimização de queries para suportar crescimento da base de dados

### Maintibilidade
- Código limpo e bem documentado
- Testes automatizados
- Padrões consistentes de nomenclatura e estrutura
- Componentização para facilitar manutenção e evolução

## Riscos e Mitigações

### Riscos Técnicos

1. **Complexidade do Canvas SVG com Konva.js**
   - **Mitigação:** Protótipos iniciais para validar a integração Konva.js + Vue.js
   - **Mitigação:** Implementação incremental das funcionalidades do canvas

2. **Performance com muitos elementos no canvas**
   - **Mitigação:** Testes de performance com diferentes quantidades de elementos
   - **Mitigação:** Otimização de renderização (layer caching, object pooling)

3. **Exportação de SVG animado**
   - **Mitigação:** Pesquisa de bibliotecas especializadas para exportação
   - **Mitigação:** Implementação de fallbacks para formatos mais simples

4. **Integração entre Vue.js e Konva.js**
   - **Mitigação:** Criação de composables específicos para gerenciar a integração
   - **Mitigação:** Encapsulamento da lógica Konva.js em componentes dedicados
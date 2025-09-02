# Arquitetura do Projeto TheatreFlux

## Visão Geral
O TheatreFlux é uma plataforma web para criação e edição de projetos teatrais interativos, permitindo aos usuários criar cenas, gerenciar atores, configurar palcos e exportar seus projetos. O sistema combina um backend Laravel robusto com um frontend Vue.js moderno utilizando Shadcn UI.

## Decisões Arquiteturais

### Padrão Arquitetural
- **Arquitetura Escolhida:** Clean Architecture Simplificada com Modular Monolith
- **Rationale:** O projeto possui complexidade média com potencial de crescimento. A arquitetura modular permite organização clara das funcionalidades (autenticação, projetos, editor) mantendo a simplicidade de um monolito.

### Stack Tecnológica
- **Backend:** Laravel 12 (PHP 8.3+)
- **Frontend:** Vue.js 3 + Shadcn UI + Tailwind CSS
- **Banco de Dados:** MySQL 8.0+
- **Animações:** GSAP (Green Sock Animation Platform)
- **Canvas:** Konva.js para renderização do editor
- **Ambiente:** Laravel Herd (desenvolvimento local)
- **Timezone:** America/Sao_Paulo
- **Locale:** pt_BR

## Estrutura do Projeto

### Backend (Laravel)
```
/app
├── Http/
│   ├── Controllers/
│   │   ├── Auth/           # Autenticação
│   │   ├── Dashboard/      # Dashboard do cliente
│   │   ├── Project/        # Gerenciamento de projetos
│   │   └── Editor/         # Editor de projetos
│   ├── Middleware/         # Middlewares customizados
│   └── Requests/           # Form Requests
├── Models/
│   ├── User.php           # Modelo de usuário
│   ├── Project.php        # Modelo de projeto
│   ├── Scene.php          # Modelo de cena
│   └── Actor.php          # Modelo de ator
├── Services/              # Lógica de negócio
│   ├── ProjectService.php
│   ├── EditorService.php
│   └── ExportService.php
└── Repositories/          # Camada de dados
    ├── ProjectRepository.php
    └── UserRepository.php
```

### Frontend (Vue.js + Shadcn UI)
```
/resources
├── js/
│   ├── components/
│   │   ├── ui/            # Componentes Shadcn UI
│   │   ├── auth/          # Componentes de autenticação
│   │   ├── dashboard/     # Componentes do dashboard
│   │   ├── editor/        # Componentes do editor
│   │   │   ├── TheatreFlowEditor.vue
│   │   │   ├── TheatreElement.vue
│   │   │   ├── PropertiesPanel.vue
│   │   │   └── AnimationTimeline.vue
│   │   └── shared/        # Componentes compartilhados
│   ├── composables/       # Composables Vue
│   ├── stores/            # Pinia stores
│   └── utils/             # Utilitários
└── css/
    └── app.css           # Tailwind + Shadcn UI styles
```

## Camadas e Responsabilidades

### Domain Layer (Models + Services)
- **Responsabilidade:** Regras de negócio e entidades do domínio
- **Componentes:** Models Eloquent, Services para lógica complexa

### Application Layer (Controllers + Requests)
- **Responsabilidade:** Orquestração de casos de uso e validação
- **Componentes:** Controllers RESTful, Form Requests, Middlewares

### Infrastructure Layer (Repositories + External Services)
- **Responsabilidade:** Persistência de dados e integrações externas
- **Componentes:** Repositories, APIs externas, File Storage

### Presentation Layer (Views + Components)
- **Responsabilidade:** Interface do usuário e experiência
- **Componentes:** Blade templates, Vue components, Shadcn UI

## Migração para Shadcn UI

### Decisão Estratégica
- **Substituição Completa:** Todos os estilos atuais serão substituídos por Shadcn UI
- **Manutenção da Estrutura:** A lógica e estrutura do editor permanecerão intactas
- **Benefícios:** Consistência visual, acessibilidade, manutenibilidade

### Componentes Shadcn UI a Implementar
- **Navegação:** Navigation Menu, Breadcrumb
- **Formulários:** Input, Button, Select, Textarea, Checkbox
- **Feedback:** Alert, Toast, Dialog, Progress
- **Layout:** Card, Separator, Tabs, Sheet
- **Dados:** Table, Pagination, Badge

## Padrões de Design Utilizados
- **Repository Pattern:** Para abstração da camada de dados
- **Service Pattern:** Para lógica de negócio complexa
- **Observer Pattern:** Para eventos do Laravel
- **Factory Pattern:** Para criação de objetos complexos
- **Singleton Pattern:** Para serviços compartilhados

## TODO List

### 🟡 Em Andamento
- [ ] Criar arquivo arquiteto.md documentando decisões arquiteturais

### ✅ Concluído
- [x] Configurar ambiente Laravel - timezone America/Sao_Paulo e locale pt_BR

### 📋 Backlog Prioritário
- [ ] Migrar frontend para Shadcn UI - substituir estilos atuais mantendo estrutura do editor
- [ ] Atualizar .env.example com todas as variáveis necessárias
- [ ] Implementar área logada do cliente (autenticação e perfil)
- [ ] Reestruturar diretórios seguindo padrões PSR e boas práticas
- [ ] Criar dashboard do cliente com Shadcn UI
- [ ] Implementar fluxo de criação de projetos
- [ ] Implementar logs e tratamento de erros adequados
- [ ] Configurar testes automatizados para validar arquitetura

## Considerações de Qualidade

### Performance
- Lazy loading de componentes Vue
- Otimização de queries com Eloquent
- Cache de dados frequentemente acessados
- Compressão de assets com Vite

### Segurança
- Autenticação Laravel Sanctum
- Validação rigorosa de inputs
- CSRF protection habilitado
- Rate limiting em APIs
- Sanitização de dados do editor

### Escalabilidade
- Estrutura modular para fácil expansão
- APIs RESTful bem definidas
- Separação clara de responsabilidades
- Possibilidade futura de microserviços

### Maintibilidade
- Código autodocumentado com nomes descritivos
- Testes automatizados (Feature + Unit)
- Logs estruturados para debugging
- Documentação técnica atualizada
- Padrões PSR-12 seguidos rigorosamente

## Riscos e Mitigações

### Risco: Complexidade do Editor
- **Mitigação:** Manter componentes pequenos e focados, usar composables para lógica compartilhada

### Risco: Performance com Animações
- **Mitigação:** Otimizar GSAP timelines, usar requestAnimationFrame, implementar throttling

### Risco: Compatibilidade de Browsers
- **Mitigação:** Polyfills necessários, testes em múltiplos browsers, fallbacks para funcionalidades avançadas

### Risco: Migração Shadcn UI
- **Mitigação:** Migração incremental por módulos, testes visuais, backup dos estilos atuais

## Próximos Passos

1. **Configuração Shadcn UI:** Instalar e configurar Shadcn UI no projeto
2. **Migração Incremental:** Começar pelos componentes mais simples (botões, inputs)
3. **Testes Visuais:** Validar cada componente migrado
4. **Documentação:** Atualizar guias de desenvolvimento
5. **Treinamento:** Documentar padrões Shadcn UI para a equipe

---

**Última Atualização:** Janeiro 2025  
**Versão:** 1.0  
**Responsável:** Arquiteto de Software Sênior
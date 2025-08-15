# Deploy do TheatreFlux na Vercel

Este guia fornece instruções passo a passo para fazer o deploy do TheatreFlux na Vercel.

## Pré-requisitos

- Conta na [Vercel](https://vercel.com)
- Repositório Git (GitHub, GitLab, ou Bitbucket)
- Banco de dados MySQL/PostgreSQL (recomendado: PlanetScale, Railway, ou Supabase)

## Configuração do Projeto

### 1. Preparação dos Arquivos

Os seguintes arquivos foram criados/configurados para o deploy:

- `vercel.json` - Configurações da Vercel
- `api/index.php` - Ponto de entrada do Laravel
- `.env.production` - Variáveis de ambiente para produção
- Scripts de build otimizados no `package.json`

### 2. Configuração do Banco de Dados

#### Opção A: PlanetScale (Recomendado)
1. Crie uma conta em [PlanetScale](https://planetscale.com)
2. Crie um novo banco de dados
3. Obtenha as credenciais de conexão

#### Opção B: Railway
1. Crie uma conta em [Railway](https://railway.app)
2. Crie um projeto MySQL
3. Obtenha as credenciais de conexão

#### Opção C: Supabase
1. Crie uma conta em [Supabase](https://supabase.com)
2. Crie um novo projeto
3. Obtenha as credenciais PostgreSQL

### 3. Deploy na Vercel

#### Método 1: Via Dashboard da Vercel

1. Acesse [vercel.com](https://vercel.com) e faça login
2. Clique em "New Project"
3. Conecte seu repositório Git
4. Selecione o repositório do TheatreFlux
5. Configure as variáveis de ambiente (veja seção abaixo)
6. Clique em "Deploy"

#### Método 2: Via CLI da Vercel

```bash
# Instalar CLI da Vercel
npm i -g vercel

# Fazer login
vercel login

# Deploy
vercel --prod
```

### 4. Configuração das Variáveis de Ambiente

No dashboard da Vercel, vá em Settings > Environment Variables e adicione:

#### Variáveis Obrigatórias:
```
APP_NAME=TheatreFlux
APP_ENV=production
APP_KEY=base64:SUA_CHAVE_AQUI
APP_DEBUG=false
APP_URL=https://seu-dominio.vercel.app

# Banco de Dados
DB_CONNECTION=mysql
DB_HOST=seu-host-db
DB_PORT=3306
DB_DATABASE=seu-banco
DB_USERNAME=seu-usuario
DB_PASSWORD=sua-senha

# Configurações de Sessão
SESSION_DRIVER=cookie
SESSION_SECURE_COOKIE=true
SESSION_SAME_SITE=lax

# Cache e Queue
CACHE_STORE=array
QUEUE_CONNECTION=sync

# Logs
LOG_CHANNEL=stderr
LOG_LEVEL=error
```

#### Variáveis Opcionais (para recursos avançados):
```
# AWS S3 (para upload de arquivos)
AWS_ACCESS_KEY_ID=sua-chave
AWS_SECRET_ACCESS_KEY=sua-chave-secreta
AWS_DEFAULT_REGION=us-east-1
AWS_BUCKET=seu-bucket

# Email (se necessário)
MAIL_MAILER=smtp
MAIL_HOST=seu-smtp
MAIL_PORT=587
MAIL_USERNAME=seu-email
MAIL_PASSWORD=sua-senha
MAIL_FROM_ADDRESS=noreply@seudominio.com
```

### 5. Gerar APP_KEY

Para gerar uma nova `APP_KEY`:

```bash
# Localmente
php artisan key:generate --show

# Ou use um gerador online
# https://generate-random.org/laravel-key-generator
```

### 6. Executar Migrações

Após o deploy, execute as migrações do banco:

```bash
# Via Vercel CLI (se configurado localmente)
vercel env pull .env.local
php artisan migrate --force

# Ou configure um script de deploy automático
```

## Configurações Avançadas

### Domínio Personalizado

1. No dashboard da Vercel, vá em Settings > Domains
2. Adicione seu domínio personalizado
3. Configure os DNS conforme instruções
4. Atualize `APP_URL` nas variáveis de ambiente

### Otimizações de Performance

1. **Cache de Configuração**: Adicione ao build script
```bash
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

2. **Compressão de Assets**: Já configurado no `vite.config.js`

3. **CDN**: Configure AWS CloudFront ou similar para assets estáticos

### Monitoramento

1. **Logs**: Acesse via dashboard da Vercel > Functions
2. **Performance**: Use Vercel Analytics
3. **Erros**: Configure Sentry ou similar

## Troubleshooting

### Problemas Comuns

1. **Erro 500**: Verifique logs na Vercel e variáveis de ambiente
2. **Assets não carregam**: Verifique configuração do Vite
3. **Banco não conecta**: Verifique credenciais e whitelist de IPs
4. **Sessões não funcionam**: Verifique configurações de cookie

### Comandos Úteis

```bash
# Ver logs em tempo real
vercel logs

# Testar build localmente
vercel dev

# Listar deployments
vercel ls

# Rollback para versão anterior
vercel rollback [deployment-url]
```

## Estrutura de Arquivos para Deploy

```
theatreflux/
├── api/
│   └── index.php          # Entry point para Vercel
├── public/
│   ├── build/             # Assets compilados
│   └── index.php          # Laravel public index
├── vercel.json            # Configurações da Vercel
├── .env.production        # Variáveis de produção
└── ...
```

## Próximos Passos

1. Configure monitoramento de erros
2. Implemente backup automático do banco
3. Configure CI/CD para deploys automáticos
4. Otimize performance com cache Redis
5. Configure domínio personalizado e SSL

## Suporte

Para problemas específicos:
- [Documentação da Vercel](https://vercel.com/docs)
- [Documentação do Laravel](https://laravel.com/docs)
- [Issues do projeto](https://github.com/seu-usuario/theatreflux/issues)
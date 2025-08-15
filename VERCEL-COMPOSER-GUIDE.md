# Guia: Resolvendo Problemas do Composer na Vercel

## 🚨 Problemas Comuns e Soluções

### 1. **Erro: "Composer install failed"**

**Causa:** Versão do PHP incompatível ou dependências conflitantes.

**Solução:**
```json
// vercel.json
{
  "functions": {
    "api/index.php": {
      "runtime": "vercel-php@0.7.0"
    }
  },
  "build": {
    "env": {
      "COMPOSER_CACHE_DIR": "/tmp/.composer",
      "PHP_VERSION": "8.2"
    }
  },
  "installCommand": "composer install --no-dev --optimize-autoloader --no-interaction --prefer-dist"
}
```

### 2. **Erro: "Memory limit exceeded"**

**Causa:** Composer precisa de mais memória para resolver dependências.

**Solução:**
```json
// vercel.json - installCommand otimizado
"installCommand": "php -d memory_limit=512M /usr/local/bin/composer install --no-dev --optimize-autoloader --no-interaction --prefer-dist --no-scripts"
```

### 3. **Erro: "Platform requirements not satisfied"**

**Causa:** Extensões PHP não disponíveis na Vercel.

**Solução:**
```json
// composer.json - adicionar na seção config
{
  "config": {
    "platform": {
      "php": "8.2"
    },
    "platform-check": false,
    "optimize-autoloader": true,
    "preferred-install": "dist",
    "sort-packages": true
  }
}
```

### 4. **Erro: "Timeout during composer install"**

**Causa:** Download de dependências muito lento.

**Solução:**
```json
// vercel.json
{
  "build": {
    "env": {
      "COMPOSER_PROCESS_TIMEOUT": "600",
      "COMPOSER_HTACCESS_PROTECT": "0"
    }
  },
  "installCommand": "composer install --no-dev --optimize-autoloader --no-interaction --prefer-dist --no-progress"
}
```

## 🔧 Configuração Otimizada

### vercel.json Completo
```json
{
  "version": 2,
  "functions": {
    "api/index.php": {
      "runtime": "vercel-php@0.7.0",
      "maxDuration": 30
    }
  },
  "routes": [
    {
      "src": "/(css|js|images)/(.*)",
      "dest": "/public/$1/$2"
    },
    {
      "src": "/build/(.*)",
      "dest": "/public/build/$1"
    },
    {
      "src": "/favicon.ico",
      "dest": "/public/favicon.ico"
    },
    {
      "src": "/robots.txt",
      "dest": "/public/robots.txt"
    },
    {
      "src": "/(.*)",
      "dest": "/api/index.php"
    }
  ],
  "env": {
    "APP_ENV": "production",
    "APP_DEBUG": "false",
    "LOG_CHANNEL": "stderr",
    "SESSION_DRIVER": "cookie",
    "CACHE_STORE": "array",
    "QUEUE_CONNECTION": "sync"
  },
  "build": {
    "env": {
      "NODE_VERSION": "18",
      "PHP_VERSION": "8.2",
      "COMPOSER_CACHE_DIR": "/tmp/.composer",
      "COMPOSER_PROCESS_TIMEOUT": "600",
      "COMPOSER_MEMORY_LIMIT": "512M"
    }
  },
  "buildCommand": "npm run build",
  "outputDirectory": "public",
  "installCommand": "composer install --no-dev --optimize-autoloader --no-interaction --prefer-dist --no-progress && npm ci"
}
```

### composer.json Otimizado
```json
{
  "config": {
    "platform": {
      "php": "8.2"
    },
    "platform-check": false,
    "optimize-autoloader": true,
    "preferred-install": "dist",
    "sort-packages": true,
    "allow-plugins": {
      "pestphp/pest-plugin": true,
      "php-http/discovery": true
    },
    "process-timeout": 600,
    "cache-dir": "/tmp/.composer"
  },
  "scripts": {
    "post-autoload-dump": [
      "Illuminate\\Foundation\\ComposerScripts::postAutoloadDump",
      "@php artisan package:discover --ansi || true"
    ]
  }
}
```

## 🚀 Scripts de Deploy Otimizados

### deploy-vercel.sh
```bash
#!/bin/bash
echo "🚀 Preparando deploy para Vercel..."

# Limpar cache do Composer
composer clear-cache

# Instalar dependências otimizadas
composer install --no-dev --optimize-autoloader --no-interaction --prefer-dist

# Gerar autoloader otimizado
composer dump-autoload --optimize --no-dev

# Build dos assets
npm ci
npm run build

echo "✅ Projeto preparado para deploy!"
echo "📋 Próximos passos:"
echo "   1. Commit e push das alterações"
echo "   2. Deploy via Vercel Dashboard ou CLI"
echo "   3. Configurar variáveis de ambiente"
```

### deploy-vercel.ps1 (Windows)
```powershell
Write-Host "🚀 Preparando deploy para Vercel..." -ForegroundColor Green

# Limpar cache do Composer
composer clear-cache

# Instalar dependências otimizadas
composer install --no-dev --optimize-autoloader --no-interaction --prefer-dist

# Gerar autoloader otimizado
composer dump-autoload --optimize --no-dev

# Build dos assets
npm ci
npm run build

Write-Host "✅ Projeto preparado para deploy!" -ForegroundColor Green
Write-Host "📋 Próximos passos:" -ForegroundColor Yellow
Write-Host "   1. Commit e push das alterações" -ForegroundColor White
Write-Host "   2. Deploy via Vercel Dashboard ou CLI" -ForegroundColor White
Write-Host "   3. Configurar variáveis de ambiente" -ForegroundColor White
```

## 🔍 Debugging

### Verificar Logs da Vercel
```bash
# Via CLI da Vercel
npx vercel logs [deployment-url]

# Ou no Dashboard
# Vercel Dashboard > Project > Functions > Logs
```

### Testar Localmente
```bash
# Simular ambiente de produção
composer install --no-dev --optimize-autoloader
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Testar servidor
php artisan serve
```

## 📋 Checklist de Deploy

- [ ] PHP 8.2+ configurado no vercel.json
- [ ] Composer.json otimizado com platform-check: false
- [ ] InstallCommand com flags corretas
- [ ] Variáveis de ambiente configuradas
- [ ] Cache do Composer limpo
- [ ] Build dos assets funcionando
- [ ] Logs verificados após deploy

## 🆘 Comandos de Emergência

```bash
# Forçar reinstalação completa
rm -rf vendor composer.lock
composer install --no-dev --optimize-autoloader

# Limpar todos os caches
composer clear-cache
php artisan config:clear
php artisan cache:clear
php artisan route:clear
php artisan view:clear

# Redeploy forçado na Vercel
npx vercel --prod --force
```

## 📚 Recursos Úteis

- [Vercel PHP Runtime](https://vercel.com/docs/runtimes/php)
- [Laravel Deployment](https://laravel.com/docs/deployment)
- [Composer Optimization](https://getcomposer.org/doc/articles/autoloader-optimization.md)
#!/bin/bash

# Script de Deploy para TheatreFlux na Vercel
# Execute este script antes do primeiro deploy

echo "🚀 Preparando TheatreFlux para deploy na Vercel..."

# Verificar se as dependências estão instaladas
echo "📦 Verificando dependências..."
if [ ! -d "vendor" ]; then
    echo "Instalando dependências do Composer..."
    composer install --no-dev --optimize-autoloader
fi

if [ ! -d "node_modules" ]; then
    echo "Instalando dependências do NPM..."
    npm ci
fi

# Gerar chave da aplicação se não existir
echo "🔑 Verificando APP_KEY..."
if [ ! -f ".env" ]; then
    cp .env.example .env
    php artisan key:generate
fi

# Build dos assets
echo "🏗️ Compilando assets..."
npm run build

# Otimizar autoloader
echo "⚡ Otimizando autoloader..."
composer dump-autoload --optimize

# Cache de configuração (para produção)
echo "💾 Gerando cache de configuração..."
php artisan config:cache
php artisan route:cache
php artisan view:cache

echo "✅ Projeto preparado para deploy!"
echo ""
echo "📋 Próximos passos:"
echo "1. Faça commit das alterações"
echo "2. Push para seu repositório Git"
echo "3. Configure as variáveis de ambiente na Vercel"
echo "4. Faça o deploy via dashboard ou CLI da Vercel"
echo ""
echo "📖 Consulte DEPLOY.md para instruções detalhadas"
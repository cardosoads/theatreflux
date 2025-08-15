#!/bin/bash

# Script de Deploy para TheatreFlux na Vercel
# Execute este script antes do primeiro deploy

echo "🚀 Preparando projeto TheatreFlux para deploy na Vercel..."

# Verificar se o Composer está instalado
if ! command -v composer &> /dev/null; then
    echo "❌ Composer não encontrado. Instale o Composer primeiro."
    exit 1
fi

# Verificar se o Node.js está instalado
if ! command -v node &> /dev/null; then
    echo "❌ Node.js não encontrado. Instale o Node.js primeiro."
    exit 1
fi

echo "🧹 Limpando cache do Composer..."
composer clear-cache

echo "📦 Instalando dependências do Composer (otimizado para Vercel)..."
composer install --no-dev --optimize-autoloader --no-interaction --prefer-dist

if [ $? -ne 0 ]; then
    echo "❌ Erro ao instalar dependências do Composer"
    echo "💡 Tente: composer install --no-dev --optimize-autoloader --no-interaction --prefer-dist --ignore-platform-reqs"
    exit 1
fi

echo "⚡ Gerando autoloader otimizado..."
composer dump-autoload --optimize --no-dev

echo "📦 Instalando dependências do NPM..."
npm ci

if [ $? -ne 0 ]; then
    echo "❌ Erro ao instalar dependências do NPM"
    exit 1
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
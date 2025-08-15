# Script de Deploy para TheatreFlux na Vercel (PowerShell)
# Execute este script antes do primeiro deploy

Write-Host "🚀 Preparando projeto TheatreFlux para deploy na Vercel..." -ForegroundColor Green

# Verificar se o Composer está instalado
if (-not (Get-Command composer -ErrorAction SilentlyContinue)) {
    Write-Host "❌ Composer não encontrado. Instale o Composer primeiro." -ForegroundColor Red
    exit 1
}

# Verificar se o Node.js está instalado
if (-not (Get-Command node -ErrorAction SilentlyContinue)) {
    Write-Host "❌ Node.js não encontrado. Instale o Node.js primeiro." -ForegroundColor Red
    exit 1
}

Write-Host "🧹 Limpando cache do Composer..." -ForegroundColor Yellow
composer clear-cache

Write-Host "📦 Instalando dependências do Composer (otimizado para Vercel)..." -ForegroundColor Yellow
composer install --no-dev --optimize-autoloader --no-interaction --prefer-dist

if ($LASTEXITCODE -ne 0) {
    Write-Host "❌ Erro ao instalar dependências do Composer" -ForegroundColor Red
    Write-Host "💡 Tente: composer install --no-dev --optimize-autoloader --no-interaction --prefer-dist --ignore-platform-reqs" -ForegroundColor Cyan
    exit 1
}

Write-Host "⚡ Gerando autoloader otimizado..." -ForegroundColor Yellow
composer dump-autoload --optimize --no-dev

Write-Host "📦 Instalando dependências do NPM..." -ForegroundColor Yellow
npm ci

if ($LASTEXITCODE -ne 0) {
    Write-Host "❌ Erro ao instalar dependências do NPM" -ForegroundColor Red
    exit 1
}

# Gerar chave da aplicação se não existir
Write-Host "🔑 Verificando APP_KEY..." -ForegroundColor Yellow
if (-not (Test-Path ".env")) {
    Copy-Item ".env.example" ".env"
    php artisan key:generate
}

# Build dos assets
Write-Host "🏗️ Compilando assets..." -ForegroundColor Yellow
npm run build

# Otimizar autoloader
Write-Host "⚡ Otimizando autoloader..." -ForegroundColor Yellow
composer dump-autoload --optimize

# Cache de configuração (para produção)
Write-Host "💾 Gerando cache de configuração..." -ForegroundColor Yellow
php artisan config:cache
php artisan route:cache
php artisan view:cache

Write-Host "✅ Projeto preparado para deploy!" -ForegroundColor Green
Write-Host ""
Write-Host "📋 Próximos passos:" -ForegroundColor Cyan
Write-Host "1. Faça commit das alterações" -ForegroundColor White
Write-Host "2. Push para seu repositório Git" -ForegroundColor White
Write-Host "3. Configure as variáveis de ambiente na Vercel" -ForegroundColor White
Write-Host "4. Faça o deploy via dashboard ou CLI da Vercel" -ForegroundColor White
Write-Host ""
Write-Host "📖 Consulte DEPLOY.md para instruções detalhadas" -ForegroundColor Magenta
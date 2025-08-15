# Script de Deploy para TheatreFlux na Vercel (PowerShell)
# Execute este script antes do primeiro deploy

Write-Host "🚀 Preparando TheatreFlux para deploy na Vercel..." -ForegroundColor Green

# Verificar se as dependências estão instaladas
Write-Host "📦 Verificando dependências..." -ForegroundColor Yellow
if (-not (Test-Path "vendor")) {
    Write-Host "Instalando dependências do Composer..." -ForegroundColor Cyan
    composer install --no-dev --optimize-autoloader
}

if (-not (Test-Path "node_modules")) {
    Write-Host "Instalando dependências do NPM..." -ForegroundColor Cyan
    npm ci
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
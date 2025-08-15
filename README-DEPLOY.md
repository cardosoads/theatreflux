# 🚀 Deploy TheatreFlux na Vercel

## Início Rápido

### 1. Preparar o Projeto

**Windows:**
```powershell
.\deploy.ps1
```

**Linux/Mac:**
```bash
chmod +x deploy.sh
./deploy.sh
```

### 2. Configurar Vercel

1. **Criar conta na [Vercel](https://vercel.com)**
2. **Conectar repositório Git**
3. **Configurar variáveis de ambiente:**

```env
APP_NAME=TheatreFlux
APP_ENV=production
APP_KEY=base64:SUA_CHAVE_AQUI
APP_DEBUG=false
APP_URL=https://seu-app.vercel.app

# Banco de Dados (PlanetScale recomendado)
DB_CONNECTION=mysql
DB_HOST=seu-host
DB_PORT=3306
DB_DATABASE=seu-banco
DB_USERNAME=seu-usuario
DB_PASSWORD=sua-senha

# Configurações essenciais
SESSION_DRIVER=cookie
CACHE_STORE=array
QUEUE_CONNECTION=sync
LOG_CHANNEL=stderr
```

### 3. Deploy

**Via Dashboard:**
- Clique em "Deploy" na Vercel

**Via CLI:**
```bash
npm i -g vercel
vercel login
vercel --prod
```

## 📋 Checklist de Deploy

- [ ] Dependências instaladas (`composer install`, `npm ci`)
- [ ] Assets compilados (`npm run build`)
- [ ] Variáveis de ambiente configuradas na Vercel
- [ ] Banco de dados configurado (PlanetScale/Railway/Supabase)
- [ ] APP_KEY gerada
- [ ] Domínio configurado (opcional)

## 🔧 Arquivos de Configuração

- `vercel.json` - Configurações da Vercel
- `api/index.php` - Entry point do Laravel
- `.env.production` - Template de variáveis
- `deploy.sh` / `deploy.ps1` - Scripts de preparação

## 📚 Documentação Completa

Veja [DEPLOY.md](./DEPLOY.md) para instruções detalhadas.

## 🆘 Problemas Comuns

**Erro 500:** Verifique logs na Vercel e variáveis de ambiente

**Assets não carregam:** Verifique se `npm run build` foi executado

**Banco não conecta:** Verifique credenciais e whitelist de IPs

## 🎯 Próximos Passos

1. Configure domínio personalizado
2. Implemente monitoramento (Sentry)
3. Configure backup automático
4. Otimize performance com CDN

---

**🎭 TheatreFlux** - Editor de Fluxos Teatrais
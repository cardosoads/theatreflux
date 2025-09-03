<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Marca e Deixa - Editor de Fluxos Teatrais</title>
    
    <!-- Inter Font -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <style>
        body {
            font-family: 'Inter', sans-serif;
        }
        
        /* Smooth scrolling */
        html {
            scroll-behavior: smooth;
        }
        
        /* Custom animations */
        @keyframes fade-in {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        
        .animate-fade-in {
            animation: fade-in 0.6s ease-out;
        }
        
        /* Gradient backgrounds */
        .gradient-bg {
            background: linear-gradient(135deg, hsl(var(--background)) 0%, hsl(var(--muted)) 100%);
        }
        
        .gradient-text {
            background: linear-gradient(135deg, hsl(var(--primary)) 0%, hsl(var(--accent)) 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }
    </style>
</head>
<body class="antialiased bg-background text-foreground">
    <!-- Navigation -->
    <nav class="fixed top-0 w-full bg-background/80 backdrop-blur-md border-b border-border z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16">
                <div class="flex items-center space-x-3">
                    <div class="w-8 h-8 bg-primary rounded-lg flex items-center justify-center">
                        <svg class="w-5 h-5 text-primary-foreground" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                        </svg>
                    </div>
                    <span class="text-xl font-bold text-foreground">Marca e Deixa</span>
                </div>
                <div class="hidden md:flex items-center space-x-8">
                    <a href="#hero" class="text-muted-foreground hover:text-foreground transition-colors font-medium">Início</a>
                    <a href="#features" class="text-muted-foreground hover:text-foreground transition-colors font-medium">Recursos</a>
                    <a href="#about" class="text-muted-foreground hover:text-foreground transition-colors font-medium">Sobre</a>
                    <a href="#faq" class="text-muted-foreground hover:text-foreground transition-colors font-medium">FAQ</a>
                    <a href="#contact" class="text-muted-foreground hover:text-foreground transition-colors font-medium">Contato</a>
                </div>
                <div class="flex items-center space-x-4">
                    @if (Route::has('login'))
                        @auth
                            <x-ui.button variant="ghost" size="sm">
                                <a href="{{ url('/dashboard') }}">Dashboard</a>
                            </x-ui.button>
                        @else
                            <x-ui.button variant="ghost" size="sm">
                                <a href="{{ route('login') }}">Entrar</a>
                            </x-ui.button>
                            @if (Route::has('register'))
                                <x-ui.button variant="default" size="sm">
                                    <a href="{{ route('register') }}">Cadastrar</a>
                                </x-ui.button>
                            @endif
                        @endauth
                    @endif
                </div>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section id="hero" class="pt-16 min-h-screen flex items-center gradient-bg">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-20">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
                <div class="animate-fade-in">
                    <h1 class="text-4xl md:text-6xl font-bold text-foreground mb-6 leading-tight">
                        Crie Fluxos Teatrais
                        <span class="gradient-text">Incríveis</span>
                    </h1>
                    <p class="text-xl text-muted-foreground mb-8 leading-relaxed">
                        Marca e Deixa é a plataforma definitiva para criação, edição e gerenciamento de fluxos teatrais. 
                        Transforme suas ideias em espetáculos visuais interativos.
                    </p>
                    <div class="flex flex-col sm:flex-row gap-4">
                        <x-ui.button variant="default" size="lg">
                            <a href="{{ route('register') }}">Começar Gratuitamente</a>
                        </x-ui.button>
                        <x-ui.button variant="outline" size="lg">
                            <a href="#features">Conhecer Recursos</a>
                        </x-ui.button>
                    </div>
                </div>
                <div class="flex justify-center animate-fade-in">
                    <div class="bg-card border border-border w-full max-w-md h-80 rounded-lg flex items-center justify-center shadow-lg">
                        <div class="text-center">
                            <div class="w-16 h-16 bg-primary rounded-full mx-auto mb-4 flex items-center justify-center">
                                <svg class="w-8 h-8 text-primary-foreground" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.828 14.828a4 4 0 01-5.656 0M9 10h1m4 0h1m-6 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                            <p class="text-card-foreground font-medium">Editor Visual</p>
                            <p class="text-sm text-muted-foreground mt-2">Interface intuitiva para<br>criação de fluxos</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section id="features" class="py-20 bg-background">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <h2 class="text-3xl md:text-4xl font-bold text-foreground mb-4">Recursos Poderosos</h2>
                <p class="text-xl text-muted-foreground max-w-3xl mx-auto">
                    Descubra as ferramentas que tornam o Marca e Deixa a escolha ideal para profissionais do teatro
                </p>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                <!-- Feature 1 -->
                <x-ui.card variant="default" padding="lg" class="hover:shadow-lg transition-all duration-300 hover:scale-105">
                    <div class="w-12 h-12 bg-primary rounded-lg flex items-center justify-center mb-6">
                        <svg class="w-6 h-6 text-primary-foreground" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 4a2 2 0 114 0v1a1 1 0 001 1h3a1 1 0 011 1v3a1 1 0 01-1 1h-1a2 2 0 100 4h1a1 1 0 011 1v3a1 1 0 01-1 1h-3a1 1 0 01-1-1v-1a2 2 0 10-4 0v1a1 1 0 01-1 1H7a1 1 0 01-1-1v-3a1 1 0 00-1-1H4a1 1 0 01-1-1V9a1 1 0 011-1h1a2 2 0 100-4H4a1 1 0 01-1-1V4a1 1 0 011-1h3a1 1 0 001-1z"></path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-card-foreground mb-4">Editor Visual Intuitivo</h3>
                    <p class="text-muted-foreground leading-relaxed">
                        Interface drag-and-drop para criar fluxos complexos sem necessidade de programação
                    </p>
                </x-ui.card>
                
                <!-- Feature 2 -->
                <x-ui.card variant="default" padding="lg" class="hover:shadow-lg transition-all duration-300 hover:scale-105">
                    <div class="w-12 h-12 bg-primary rounded-lg flex items-center justify-center mb-6">
                        <svg class="w-6 h-6 text-primary-foreground" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-card-foreground mb-4">Colaboração em Tempo Real</h3>
                    <p class="text-muted-foreground leading-relaxed">
                        Trabalhe em equipe com sincronização instantânea e controle de versões
                    </p>
                </x-ui.card>
                
                <!-- Feature 3 -->
                <x-ui.card variant="default" padding="lg" class="hover:shadow-lg transition-all duration-300 hover:scale-105">
                    <div class="w-12 h-12 bg-primary rounded-lg flex items-center justify-center mb-6">
                        <svg class="w-6 h-6 text-primary-foreground" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"></path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-card-foreground mb-4">Templates Profissionais</h3>
                    <p class="text-muted-foreground leading-relaxed">
                        Biblioteca extensa de modelos pré-construídos para diferentes tipos de espetáculos
                    </p>
                </x-ui.card>
                
                <!-- Feature 4 -->
                <x-ui.card variant="default" padding="lg" class="hover:shadow-lg transition-all duration-300 hover:scale-105">
                    <div class="w-12 h-12 bg-primary rounded-lg flex items-center justify-center mb-6">
                        <svg class="w-6 h-6 text-primary-foreground" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-card-foreground mb-4">Performance Otimizada</h3>
                    <p class="text-muted-foreground leading-relaxed">
                        Renderização rápida e fluida mesmo com fluxos complexos e múltiplos elementos
                    </p>
                </x-ui.card>
                
                <!-- Feature 5 -->
                <x-ui.card variant="default" padding="lg" class="hover:shadow-lg transition-all duration-300 hover:scale-105">
                    <div class="w-12 h-12 bg-primary rounded-lg flex items-center justify-center mb-6">
                        <svg class="w-6 h-6 text-primary-foreground" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-card-foreground mb-4">Segurança Avançada</h3>
                    <p class="text-muted-foreground leading-relaxed">
                        Seus projetos protegidos com criptografia e backup automático na nuvem
                    </p>
                </x-ui.card>
                
                <!-- Feature 6 -->
                <x-ui.card variant="default" padding="lg" class="hover:shadow-lg transition-all duration-300 hover:scale-105">
                    <div class="w-12 h-12 bg-primary rounded-lg flex items-center justify-center mb-6">
                        <svg class="w-6 h-6 text-primary-foreground" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M9 19l3 3m0 0l3-3m-3 3V10"></path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-card-foreground mb-4">Exportação Flexível</h3>
                    <p class="text-muted-foreground leading-relaxed">
                        Exporte seus fluxos em múltiplos formatos: PDF, PNG, SVG e mais
                    </p>
                </x-ui.card>
            </div>
        </div>
    </section>

    <!-- About Section -->
    <section id="about" class="py-20 bg-muted/30">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
                <div>
                    <h2 class="text-3xl md:text-4xl font-bold text-foreground mb-6">Sobre o Marca e Deixa</h2>
                    <p class="text-lg text-muted-foreground mb-6 leading-relaxed">
                        Nascido da paixão pelo teatro e pela tecnologia, o Marca e Deixa revoluciona a forma como 
                        profissionais do teatro criam e gerenciam seus fluxos de trabalho.
                    </p>
                    <p class="text-lg text-muted-foreground mb-8 leading-relaxed">
                        Nossa plataforma combina a simplicidade de uso com recursos avançados, permitindo que 
                        diretores, produtores e equipes técnicas colaborem de forma eficiente e criativa.
                    </p>
                    <div class="grid grid-cols-2 gap-6">
                        <x-ui.card variant="default" padding="default" class="text-center">
                            <div class="text-3xl font-bold text-primary mb-2">500+</div>
                            <div class="text-muted-foreground">Usuários Ativos</div>
                        </x-ui.card>
                        <x-ui.card variant="default" padding="default" class="text-center">
                            <div class="text-3xl font-bold text-primary mb-2">1000+</div>
                            <div class="text-muted-foreground">Projetos Criados</div>
                        </x-ui.card>
                        <x-ui.card variant="default" padding="default" class="text-center">
                            <div class="text-3xl font-bold text-primary mb-2">50+</div>
                            <div class="text-muted-foreground">Templates</div>
                        </x-ui.card>
                        <x-ui.card variant="default" padding="default" class="text-center">
                            <div class="text-3xl font-bold text-primary mb-2">24/7</div>
                            <div class="text-muted-foreground">Suporte</div>
                        </x-ui.card>
                    </div>
                </div>
                <div class="flex justify-center">
                    <x-ui.card variant="default" padding="lg" class="w-full max-w-lg h-96 flex flex-col items-center justify-center shadow-lg">
                        <div class="grid grid-cols-3 gap-4 w-full mb-6">
                            <div class="h-8 bg-muted rounded"></div>
                            <div class="h-8 bg-primary rounded"></div>
                            <div class="h-8 bg-muted rounded"></div>
                        </div>
                        <div class="w-full h-32 bg-muted rounded mb-4 flex items-center justify-center">
                            <svg class="w-12 h-12 text-muted-foreground" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                            </svg>
                        </div>
                        <p class="text-center text-muted-foreground font-medium">Visualização de Projetos</p>
                    </x-ui.card>
                </div>
            </div>
        </div>
    </section>

    <!-- FAQ Section -->
    <section id="faq" class="py-20 bg-background">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <h2 class="text-3xl md:text-4xl font-bold text-foreground mb-4">Perguntas Frequentes</h2>
                <p class="text-xl text-muted-foreground">
                    Tire suas dúvidas sobre o Marca e Deixa
                </p>
            </div>
            
            <div class="space-y-6">
                <x-ui.card variant="default" padding="lg">
                    <h3 class="text-lg font-semibold text-card-foreground mb-3">Como funciona o editor visual?</h3>
                    <p class="text-muted-foreground">
                        O editor visual permite criar fluxos teatrais através de uma interface intuitiva de arrastar e soltar. 
                        Você pode adicionar elementos, conectá-los e configurar suas propriedades sem necessidade de programação.
                    </p>
                </x-ui.card>
                
                <x-ui.card variant="default" padding="lg">
                    <h3 class="text-lg font-semibold text-card-foreground mb-3">Posso colaborar com minha equipe?</h3>
                    <p class="text-muted-foreground">
                        Sim! O Marca e Deixa oferece colaboração em tempo real, permitindo que múltiplos usuários trabalhem 
                        no mesmo projeto simultaneamente, com controle de versões e histórico de alterações.
                    </p>
                </x-ui.card>
                
                <x-ui.card variant="default" padding="lg">
                    <h3 class="text-lg font-semibold text-card-foreground mb-3">Quais formatos de exportação estão disponíveis?</h3>
                    <p class="text-muted-foreground">
                        Você pode exportar seus fluxos em diversos formatos: PDF para impressão, PNG e SVG para uso digital, 
                        e formatos específicos para integração com outras ferramentas teatrais.
                    </p>
                </x-ui.card>
                
                <x-ui.card variant="default" padding="lg">
                    <h3 class="text-lg font-semibold text-card-foreground mb-3">Existe uma versão gratuita?</h3>
                    <p class="text-muted-foreground">
                        Sim! Oferecemos um plano gratuito com recursos básicos para você experimentar a plataforma. 
                        Planos pagos incluem recursos avançados como colaboração ilimitada e templates premium.
                    </p>
                </x-ui.card>
            </div>
        </div>
    </section>

    <!-- Contact Section -->
    <section id="contact" class="py-20 bg-muted/30">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <h2 class="text-3xl md:text-4xl font-bold text-foreground mb-4">Entre em Contato</h2>
                <p class="text-xl text-muted-foreground">
                    Tem dúvidas ou sugestões? Adoraríamos ouvir você!
                </p>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <x-ui.card variant="default" padding="lg" class="text-center">
                    <div class="w-12 h-12 bg-primary rounded-lg flex items-center justify-center mx-auto mb-4">
                        <svg class="w-6 h-6 text-primary-foreground" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                        </svg>
                    </div>
                    <h3 class="text-lg font-semibold text-card-foreground mb-2">Email</h3>
                    <p class="text-muted-foreground">contato@marcaedeixa.com</p>
                </x-ui.card>
                
                <x-ui.card variant="default" padding="lg" class="text-center">
                    <div class="w-12 h-12 bg-primary rounded-lg flex items-center justify-center mx-auto mb-4">
                        <svg class="w-6 h-6 text-primary-foreground" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <h3 class="text-lg font-semibold text-card-foreground mb-2">Suporte</h3>
                    <p class="text-muted-foreground">suporte@marcaedeixa.com</p>
                </x-ui.card>
                
                <x-ui.card variant="default" padding="lg" class="text-center">
                    <div class="w-12 h-12 bg-primary rounded-lg flex items-center justify-center mx-auto mb-4">
                        <svg class="w-6 h-6 text-primary-foreground" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                        </svg>
                    </div>
                    <h3 class="text-lg font-semibold text-card-foreground mb-2">Localização</h3>
                    <p class="text-muted-foreground">São Paulo, Brasil</p>
                </x-ui.card>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-card border-t border-border py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
                <div class="col-span-1 md:col-span-2">
                    <div class="flex items-center space-x-3 mb-4">
                        <div class="w-8 h-8 bg-primary rounded-lg flex items-center justify-center">
                            <svg class="w-5 h-5 text-primary-foreground" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                            </svg>
                        </div>
                        <span class="text-xl font-bold text-card-foreground">Marca e Deixa</span>
                    </div>
                    <p class="text-muted-foreground mb-4 max-w-md">
                        A plataforma definitiva para criação e gerenciamento de fluxos teatrais. 
                        Transforme suas ideias em espetáculos visuais interativos.
                    </p>
                </div>
                
                <div>
                    <h3 class="text-lg font-semibold text-card-foreground mb-4">Produto</h3>
                    <ul class="space-y-2">
                        <li><a href="#features" class="text-muted-foreground hover:text-foreground transition-colors">Recursos</a></li>
                        <li><a href="#about" class="text-muted-foreground hover:text-foreground transition-colors">Sobre</a></li>
                        <li><a href="#faq" class="text-muted-foreground hover:text-foreground transition-colors">FAQ</a></li>
                    </ul>
                </div>
                
                <div>
                    <h3 class="text-lg font-semibold text-card-foreground mb-4">Suporte</h3>
                    <ul class="space-y-2">
                        <li><a href="#contact" class="text-muted-foreground hover:text-foreground transition-colors">Contato</a></li>
                        <li><a href="#" class="text-muted-foreground hover:text-foreground transition-colors">Documentação</a></li>
                        <li><a href="#" class="text-muted-foreground hover:text-foreground transition-colors">Tutoriais</a></li>
                    </ul>
                </div>
            </div>
            
            <div class="border-t border-border mt-8 pt-8 text-center">
                <p class="text-muted-foreground">
                    © {{ date('Y') }} Marca e Deixa. Todos os direitos reservados.
                </p>
            </div>
        </div>
    </footer>
</body>
</html>

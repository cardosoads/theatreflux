<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>TheatreFlux - Editor de Fluxos Teatrais</title>
    
    <!-- Open Sans Font -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <style>
        body {
            font-family: 'Open Sans', sans-serif;
        }
        
        /* Custom color palette */
        .bg-primary { background-color: #242734; }
        .bg-secondary { background-color: #4B5563; }
        .bg-tertiary { background-color: #9CA3AF; }
        .bg-light { background-color: #E5E7EB; }
        .bg-lighter { background-color: #F3F4F6; }
        
        .text-primary { color: #242734; }
        .text-secondary { color: #4B5563; }
        .text-tertiary { color: #9CA3AF; }
        .text-light { color: #E5E7EB; }
        .text-lighter { color: #F3F4F6; }
        
        .border-primary { border-color: #242734; }
        .border-secondary { border-color: #4B5563; }
        .border-tertiary { border-color: #9CA3AF; }
        
        /* Smooth scrolling */
        html {
            scroll-behavior: smooth;
        }
        
        /* Wireframe sketch style */
        .wireframe {
            border: 2px dashed #9CA3AF;
            background: repeating-linear-gradient(
                45deg,
                transparent,
                transparent 10px,
                rgba(156, 163, 175, 0.1) 10px,
                rgba(156, 163, 175, 0.1) 20px
            );
        }
    </style>
</head>
<body class="antialiased">
    <!-- Navigation -->
    <nav class="fixed top-0 w-full bg-white/90 backdrop-blur-sm border-b border-tertiary z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16">
                <div class="flex items-center space-x-3">
                    <div class="w-8 h-8 bg-primary rounded-lg flex items-center justify-center">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                        </svg>
                    </div>
                    <span class="text-xl font-bold text-primary">TheatreFlux</span>
                </div>
                <div class="hidden md:flex items-center space-x-8">
                    <a href="#hero" class="text-secondary hover:text-primary transition-colors font-medium">Início</a>
                    <a href="#features" class="text-secondary hover:text-primary transition-colors font-medium">Recursos</a>
                    <a href="#about" class="text-secondary hover:text-primary transition-colors font-medium">Sobre</a>
                    <a href="#wireframes" class="text-secondary hover:text-primary transition-colors font-medium">Exemplos</a>
                    <a href="#contact" class="text-secondary hover:text-primary transition-colors font-medium">Contato</a>
                </div>
                <div class="flex items-center space-x-4">
                    <a href="{{ route('login') }}" class="text-secondary hover:text-primary transition-colors font-medium">Entrar</a>
                    <a href="{{ route('register') }}" class="bg-primary text-white px-4 py-2 rounded-lg hover:bg-secondary transition-colors font-medium">Cadastrar</a>
                </div>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section id="hero" class="pt-16 min-h-screen flex items-center bg-gradient-to-br from-lighter to-light">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-20">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
                <div>
                    <h1 class="text-4xl md:text-6xl font-bold text-primary mb-6 leading-tight">
                        Crie Fluxos Teatrais
                        <span class="text-secondary">Incríveis</span>
                    </h1>
                    <p class="text-xl text-secondary mb-8 leading-relaxed">
                        TheatreFlux é a plataforma definitiva para criação, edição e gerenciamento de fluxos teatrais. 
                        Transforme suas ideias em espetáculos visuais interativos.
                    </p>
                    <div class="flex flex-col sm:flex-row gap-4">
                        <a href="{{ route('register') }}" class="bg-primary text-white px-8 py-4 rounded-lg hover:bg-secondary transition-colors font-semibold text-center">
                            Começar Gratuitamente
                        </a>
                        <a href="#features" class="border-2 border-primary text-primary px-8 py-4 rounded-lg hover:bg-primary hover:text-white transition-colors font-semibold text-center">
                            Conhecer Recursos
                        </a>
                    </div>
                </div>
                <div class="flex justify-center">
                    <div class="wireframe w-full max-w-md h-80 rounded-2xl flex items-center justify-center">
                        <div class="text-center">
                            <div class="w-16 h-16 bg-tertiary rounded-full mx-auto mb-4 flex items-center justify-center">
                                <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.828 14.828a4 4 0 01-5.656 0M9 10h1m4 0h1m-6 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                            <p class="text-tertiary font-medium">Editor Visual</p>
                            <p class="text-sm text-tertiary mt-2">Interface intuitiva para<br>criação de fluxos</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section id="features" class="py-20 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <h2 class="text-3xl md:text-4xl font-bold text-primary mb-4">Recursos Poderosos</h2>
                <p class="text-xl text-secondary max-w-3xl mx-auto">
                    Descubra as ferramentas que tornam o TheatreFlux a escolha ideal para profissionais do teatro
                </p>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                <!-- Feature 1 -->
                <div class="bg-lighter p-8 rounded-2xl hover:shadow-lg transition-shadow">
                    <div class="w-12 h-12 bg-primary rounded-xl flex items-center justify-center mb-6">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 4a2 2 0 114 0v1a1 1 0 001 1h3a1 1 0 011 1v3a1 1 0 01-1 1h-1a2 2 0 100 4h1a1 1 0 011 1v3a1 1 0 01-1 1h-3a1 1 0 01-1-1v-1a2 2 0 10-4 0v1a1 1 0 01-1 1H7a1 1 0 01-1-1v-3a1 1 0 00-1-1H4a1 1 0 01-1-1V9a1 1 0 011-1h1a2 2 0 100-4H4a1 1 0 01-1-1V4a1 1 0 011-1h3a1 1 0 001-1z"></path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-primary mb-4">Editor Visual Intuitivo</h3>
                    <p class="text-secondary leading-relaxed">
                        Interface drag-and-drop para criar fluxos complexos sem necessidade de programação
                    </p>
                </div>
                
                <!-- Feature 2 -->
                <div class="bg-lighter p-8 rounded-2xl hover:shadow-lg transition-shadow">
                    <div class="w-12 h-12 bg-primary rounded-xl flex items-center justify-center mb-6">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-primary mb-4">Colaboração em Tempo Real</h3>
                    <p class="text-secondary leading-relaxed">
                        Trabalhe em equipe com sincronização instantânea e controle de versões
                    </p>
                </div>
                
                <!-- Feature 3 -->
                <div class="bg-lighter p-8 rounded-2xl hover:shadow-lg transition-shadow">
                    <div class="w-12 h-12 bg-primary rounded-xl flex items-center justify-center mb-6">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"></path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-primary mb-4">Templates Profissionais</h3>
                    <p class="text-secondary leading-relaxed">
                        Biblioteca extensa de modelos pré-construídos para diferentes tipos de espetáculos
                    </p>
                </div>
                
                <!-- Feature 4 -->
                <div class="bg-lighter p-8 rounded-2xl hover:shadow-lg transition-shadow">
                    <div class="w-12 h-12 bg-primary rounded-xl flex items-center justify-center mb-6">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-primary mb-4">Performance Otimizada</h3>
                    <p class="text-secondary leading-relaxed">
                        Renderização rápida e fluida mesmo com fluxos complexos e múltiplos elementos
                    </p>
                </div>
                
                <!-- Feature 5 -->
                <div class="bg-lighter p-8 rounded-2xl hover:shadow-lg transition-shadow">
                    <div class="w-12 h-12 bg-primary rounded-xl flex items-center justify-center mb-6">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-primary mb-4">Segurança Avançada</h3>
                    <p class="text-secondary leading-relaxed">
                        Seus projetos protegidos com criptografia e backup automático na nuvem
                    </p>
                </div>
                
                <!-- Feature 6 -->
                <div class="bg-lighter p-8 rounded-2xl hover:shadow-lg transition-shadow">
                    <div class="w-12 h-12 bg-primary rounded-xl flex items-center justify-center mb-6">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M9 19l3 3m0 0l3-3m-3 3V10"></path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-primary mb-4">Exportação Flexível</h3>
                    <p class="text-secondary leading-relaxed">
                        Exporte seus fluxos em múltiplos formatos: PDF, PNG, SVG e mais
                    </p>
                </div>
            </div>
        </div>
    </section>

    <!-- About Section -->
    <section id="about" class="py-20 bg-light">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
                <div>
                    <h2 class="text-3xl md:text-4xl font-bold text-primary mb-6">Sobre o TheatreFlux</h2>
                    <p class="text-lg text-secondary mb-6 leading-relaxed">
                        Nascido da paixão pelo teatro e pela tecnologia, o TheatreFlux revoluciona a forma como 
                        profissionais do teatro criam e gerenciam seus fluxos de trabalho.
                    </p>
                    <p class="text-lg text-secondary mb-8 leading-relaxed">
                        Nossa plataforma combina a simplicidade de uso com recursos avançados, permitindo que 
                        diretores, produtores e equipes técnicas colaborem de forma eficiente e criativa.
                    </p>
                    <div class="grid grid-cols-2 gap-6">
                        <div class="text-center">
                            <div class="text-3xl font-bold text-primary mb-2">500+</div>
                            <div class="text-secondary">Usuários Ativos</div>
                        </div>
                        <div class="text-center">
                            <div class="text-3xl font-bold text-primary mb-2">1000+</div>
                            <div class="text-secondary">Projetos Criados</div>
                        </div>
                        <div class="text-center">
                            <div class="text-3xl font-bold text-primary mb-2">50+</div>
                            <div class="text-secondary">Templates</div>
                        </div>
                        <div class="text-center">
                            <div class="text-3xl font-bold text-primary mb-2">24/7</div>
                            <div class="text-secondary">Suporte</div>
                        </div>
                    </div>
                </div>
                <div class="flex justify-center">
                    <div class="wireframe w-full max-w-lg h-96 rounded-2xl flex flex-col items-center justify-center p-8">
                        <div class="grid grid-cols-3 gap-4 w-full mb-6">
                            <div class="h-8 bg-tertiary rounded"></div>
                            <div class="h-8 bg-secondary rounded"></div>
                            <div class="h-8 bg-tertiary rounded"></div>
                        </div>
                        <div class="w-full h-32 bg-primary/20 rounded-lg mb-4 flex items-center justify-center">
                            <span class="text-secondary font-medium">Fluxo Principal</span>
                        </div>
                        <div class="grid grid-cols-2 gap-4 w-full">
                            <div class="h-16 bg-tertiary/50 rounded"></div>
                            <div class="h-16 bg-tertiary/50 rounded"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- FAQ Section -->
    <section id="faq" class="py-20 bg-white">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <h2 class="text-3xl md:text-4xl font-bold text-primary mb-4">Perguntas Frequentes</h2>
                <p class="text-xl text-secondary max-w-3xl mx-auto">
                    Tire suas dúvidas sobre o TheatreFlux e descubra como nossa plataforma pode transformar seus fluxos de trabalho
                </p>
            </div>
            
            <div class="space-y-6">
                <!-- FAQ Item 1 -->
                <div class="faq-item bg-light rounded-2xl p-6 border border-tertiary/20 hover:border-secondary/30 transition-all duration-300">
                    <button class="faq-question w-full text-left flex justify-between items-center" onclick="toggleFAQ(this)">
                        <h3 class="text-lg font-semibold text-primary pr-4">O que é o TheatreFlux e como ele funciona?</h3>
                        <svg class="faq-icon w-6 h-6 text-secondary transform transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                        </svg>
                    </button>
                    <div class="faq-answer mt-4 text-secondary leading-relaxed hidden">
                        <p>O TheatreFlux é uma plataforma inovadora de criação de fluxos visuais que permite criar, editar e gerenciar workflows de forma intuitiva. Com nossa interface drag-and-drop, você pode conectar elementos, definir lógicas complexas e visualizar todo o processo em tempo real.</p>
                    </div>
                </div>

                <!-- FAQ Item 2 -->
                <div class="faq-item bg-light rounded-2xl p-6 border border-tertiary/20 hover:border-secondary/30 transition-all duration-300">
                    <button class="faq-question w-full text-left flex justify-between items-center" onclick="toggleFAQ(this)">
                        <h3 class="text-lg font-semibold text-primary pr-4">Preciso ter conhecimento técnico para usar a plataforma?</h3>
                        <svg class="faq-icon w-6 h-6 text-secondary transform transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                        </svg>
                    </button>
                    <div class="faq-answer mt-4 text-secondary leading-relaxed hidden">
                        <p>Não! O TheatreFlux foi desenvolvido para ser acessível a todos os níveis de usuários. Nossa interface visual e intuitiva permite que qualquer pessoa crie fluxos complexos sem necessidade de programação. Oferecemos também tutoriais e documentação completa para ajudar você a começar.</p>
                    </div>
                </div>

                <!-- FAQ Item 3 -->
                <div class="faq-item bg-light rounded-2xl p-6 border border-tertiary/20 hover:border-secondary/30 transition-all duration-300">
                    <button class="faq-question w-full text-left flex justify-between items-center" onclick="toggleFAQ(this)">
                        <h3 class="text-lg font-semibold text-primary pr-4">Quais tipos de fluxos posso criar?</h3>
                        <svg class="faq-icon w-6 h-6 text-secondary transform transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                        </svg>
                    </button>
                    <div class="faq-answer mt-4 text-secondary leading-relaxed hidden">
                        <p>Com o TheatreFlux você pode criar diversos tipos de fluxos: processos de negócio, workflows de aprovação, automações, diagramas de decisão, mapas de jornada do usuário, fluxogramas técnicos e muito mais. A plataforma é flexível e se adapta às suas necessidades específicas.</p>
                    </div>
                </div>

                <!-- FAQ Item 4 -->
                <div class="faq-item bg-light rounded-2xl p-6 border border-tertiary/20 hover:border-secondary/30 transition-all duration-300">
                    <button class="faq-question w-full text-left flex justify-between items-center" onclick="toggleFAQ(this)">
                        <h3 class="text-lg font-semibold text-primary pr-4">É possível colaborar em tempo real com minha equipe?</h3>
                        <svg class="faq-icon w-6 h-6 text-secondary transform transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                        </svg>
                    </button>
                    <div class="faq-answer mt-4 text-secondary leading-relaxed hidden">
                        <p>Sim! O TheatreFlux oferece colaboração em tempo real, permitindo que múltiplos usuários trabalhem no mesmo projeto simultaneamente. Você pode ver as alterações dos colegas instantaneamente, deixar comentários, e gerenciar permissões de acesso para diferentes membros da equipe.</p>
                    </div>
                </div>

                <!-- FAQ Item 5 -->
                <div class="faq-item bg-light rounded-2xl p-6 border border-tertiary/20 hover:border-secondary/30 transition-all duration-300">
                    <button class="faq-question w-full text-left flex justify-between items-center" onclick="toggleFAQ(this)">
                        <h3 class="text-lg font-semibold text-primary pr-4">Como posso exportar ou integrar meus fluxos?</h3>
                        <svg class="faq-icon w-6 h-6 text-secondary transform transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                        </svg>
                    </button>
                    <div class="faq-answer mt-4 text-secondary leading-relaxed hidden">
                        <p>O TheatreFlux oferece múltiplas opções de exportação: PNG, SVG, PDF para documentação, e JSON para integrações técnicas. Também fornecemos APIs robustas para integrar seus fluxos com outras ferramentas e sistemas da sua empresa, garantindo total flexibilidade no seu workflow.</p>
                    </div>
                </div>

                <!-- FAQ Item 6 -->
                <div class="faq-item bg-light rounded-2xl p-6 border border-tertiary/20 hover:border-secondary/30 transition-all duration-300">
                    <button class="faq-question w-full text-left flex justify-between items-center" onclick="toggleFAQ(this)">
                        <h3 class="text-lg font-semibold text-primary pr-4">Existe um plano gratuito disponível?</h3>
                        <svg class="faq-icon w-6 h-6 text-secondary transform transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                        </svg>
                    </button>
                    <div class="faq-answer mt-4 text-secondary leading-relaxed hidden">
                        <p>Sim! Oferecemos um plano gratuito que inclui funcionalidades essenciais para você começar a criar seus fluxos. Para recursos avançados como colaboração ilimitada, integrações premium e suporte prioritário, temos planos pagos com preços acessíveis e flexíveis.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <script>
        function toggleFAQ(button) {
            const faqItem = button.closest('.faq-item');
            const answer = faqItem.querySelector('.faq-answer');
            const icon = button.querySelector('.faq-icon');
            
            // Toggle answer visibility
            answer.classList.toggle('hidden');
            
            // Rotate icon
            if (answer.classList.contains('hidden')) {
                icon.style.transform = 'rotate(0deg)';
            } else {
                icon.style.transform = 'rotate(180deg)';
            }
            
            // Close other FAQ items
            const allFaqItems = document.querySelectorAll('.faq-item');
            allFaqItems.forEach(item => {
                if (item !== faqItem) {
                    const otherAnswer = item.querySelector('.faq-answer');
                    const otherIcon = item.querySelector('.faq-icon');
                    otherAnswer.classList.add('hidden');
                    otherIcon.style.transform = 'rotate(0deg)';
                }
            });
        }
    </script>

    <!-- Contact Section -->
    <section id="contact" class="py-20 bg-primary text-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <h2 class="text-3xl md:text-4xl font-bold mb-4">Pronto para Começar?</h2>
                <p class="text-xl text-light max-w-3xl mx-auto">
                    Junte-se a centenas de profissionais que já transformaram seus fluxos teatrais com o TheatreFlux
                </p>
            </div>
            
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
                <div>
                    <h3 class="text-2xl font-bold mb-6">Entre em Contato</h3>
                    <div class="space-y-4">
                        <div class="flex items-center space-x-4">
                            <div class="w-10 h-10 bg-white/20 rounded-lg flex items-center justify-center">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                                </svg>
                            </div>
                            <div>
                                <div class="font-semibold">Email</div>
                                <div class="text-light">contato@theatreflux.com</div>
                            </div>
                        </div>
                        <div class="flex items-center space-x-4">
                            <div class="w-10 h-10 bg-white/20 rounded-lg flex items-center justify-center">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                                </svg>
                            </div>
                            <div>
                                <div class="font-semibold">Telefone</div>
                                <div class="text-light">+55 (11) 9999-9999</div>
                            </div>
                        </div>
                        <div class="flex items-center space-x-4">
                            <div class="w-10 h-10 bg-white/20 rounded-lg flex items-center justify-center">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                </svg>
                            </div>
                            <div>
                                <div class="font-semibold">Endereço</div>
                                <div class="text-light">São Paulo, SP - Brasil</div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="bg-white/10 backdrop-blur-sm rounded-2xl p-8">
                    <form class="space-y-6">
                        <div>
                            <label class="block text-sm font-semibold mb-2">Nome</label>
                            <input type="text" class="w-full px-4 py-3 bg-white/20 border border-white/30 rounded-lg focus:outline-none focus:ring-2 focus:ring-white/50 text-white placeholder-white/70" placeholder="Seu nome completo">
                        </div>
                        <div>
                            <label class="block text-sm font-semibold mb-2">Email</label>
                            <input type="email" class="w-full px-4 py-3 bg-white/20 border border-white/30 rounded-lg focus:outline-none focus:ring-2 focus:ring-white/50 text-white placeholder-white/70" placeholder="seu@email.com">
                        </div>
                        <div>
                            <label class="block text-sm font-semibold mb-2">Mensagem</label>
                            <textarea rows="4" class="w-full px-4 py-3 bg-white/20 border border-white/30 rounded-lg focus:outline-none focus:ring-2 focus:ring-white/50 text-white placeholder-white/70 resize-none" placeholder="Como podemos ajudar?"></textarea>
                        </div>
                        <button type="submit" class="w-full bg-white text-primary px-6 py-3 rounded-lg hover:bg-light transition-colors font-semibold">
                            Enviar Mensagem
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-secondary text-light py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
                <div>
                    <div class="flex items-center space-x-3 mb-4">
                        <div class="w-8 h-8 bg-primary rounded-lg flex items-center justify-center">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                            </svg>
                        </div>
                        <span class="text-xl font-bold text-white">TheatreFlux</span>
                    </div>
                    <p class="text-sm leading-relaxed">
                        Transformando ideias teatrais em realidade através da tecnologia.
                    </p>
                </div>
                
                <div>
                    <h4 class="font-semibold text-white mb-4">Produto</h4>
                    <ul class="space-y-2 text-sm">
                        <li><a href="#features" class="hover:text-white transition-colors">Recursos</a></li>
                        <li><a href="#" class="hover:text-white transition-colors">Preços</a></li>
                        <li><a href="#" class="hover:text-white transition-colors">Templates</a></li>
                        <li><a href="#" class="hover:text-white transition-colors">Integrações</a></li>
                    </ul>
                </div>
                
                <div>
                    <h4 class="font-semibold text-white mb-4">Suporte</h4>
                    <ul class="space-y-2 text-sm">
                        <li><a href="#" class="hover:text-white transition-colors">Documentação</a></li>
                        <li><a href="#" class="hover:text-white transition-colors">Tutoriais</a></li>
                        <li><a href="#contact" class="hover:text-white transition-colors">Contato</a></li>
                        <li><a href="#" class="hover:text-white transition-colors">FAQ</a></li>
                    </ul>
                </div>
                
                <div>
                    <h4 class="font-semibold text-white mb-4">Empresa</h4>
                    <ul class="space-y-2 text-sm">
                        <li><a href="#about" class="hover:text-white transition-colors">Sobre</a></li>
                        <li><a href="#" class="hover:text-white transition-colors">Blog</a></li>
                        <li><a href="#" class="hover:text-white transition-colors">Carreiras</a></li>
                        <li><a href="#" class="hover:text-white transition-colors">Privacidade</a></li>
                    </ul>
                </div>
            </div>
            
            <div class="border-t border-tertiary mt-8 pt-8 text-center text-sm">
                <p>&copy; 2025 TheatreFlux. Todos os direitos reservados.</p>
            </div>
        </div>
    </footer>

    <!-- Smooth scroll script -->
    <script>
        // Mobile menu toggle (if needed)
        document.addEventListener('DOMContentLoaded', function() {
            // Add any interactive functionality here
            console.log('TheatreFlux Landing Page loaded successfully!');
        });
    </script>
</body>
</html>

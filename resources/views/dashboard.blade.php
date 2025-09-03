@extends('layouts.app')

@section('title', 'Dashboard - Projetos')

@section('content')
<!-- Inter Font -->
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

<style>
    :root {
        --primary-dark: #242734;
        --secondary-gray: #4B5563;
        --tertiary-gray: #9CA3AF;
        --light-gray: #E5E7EB;
        --white: #F3F4F6;
    }
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

<div class="min-h-screen bg-background text-foreground antialiased">
    <!-- Top Navigation Bar -->
    <nav class="fixed top-0 w-full bg-background/80 backdrop-blur-md border-b border-border z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16">
                <!-- Left side - Logo -->
                <div class="flex items-center space-x-3">
                    <div class="w-8 h-8 bg-primary rounded-lg flex items-center justify-center">
                        <svg class="w-5 h-5 text-primary-foreground" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                        </svg>
                    </div>
                    <span class="text-xl font-bold text-foreground">Marca e Deixa</span>
                </div>
                
                <!-- Right side - User menu -->
                <div class="flex items-center space-x-4">
                    <span class="text-muted-foreground text-sm">Olá, {{ Auth::user()->name }}!</span>
                    <div class="relative">
                        <button class="flex items-center space-x-2 text-foreground hover:bg-muted px-3 py-2 rounded-md transition-colors duration-200" onclick="window.dashboardManager?.toggleUserMenu()">
                            <div class="w-8 h-8 bg-muted rounded-full flex items-center justify-center overflow-hidden">
                                @if(Auth::user()->profile_image)
                                    <img src="{{ asset('storage/' . Auth::user()->profile_image) }}" alt="Foto de perfil" class="w-full h-full object-cover rounded-full">
                                @else
                                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"></path>
                                    </svg>
                                @endif
                            </div>
                        </button>
                        
                        <!-- Dropdown menu -->
                        <div id="userMenu" class="hidden absolute right-0 mt-2 w-48 bg-background rounded-lg shadow-xl py-2 z-50 border border-border">
                            <a href="{{ route('profile.edit') }}" class="block px-4 py-3 text-sm text-foreground hover:bg-muted transition-colors duration-150">Configurações de perfil</a>
                            <a href="#" class="block px-4 py-3 text-sm text-foreground hover:bg-muted transition-colors duration-150">Plano premium</a>
                            <div class="border-t border-border"></div>
                            <form method="POST" action="{{ route('logout') }}" class="block">
                                @csrf
                                <button type="submit" class="w-full text-left px-4 py-3 text-sm text-foreground hover:bg-muted transition-colors duration-150">
                                    Fazer logout
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <div class="pt-16 min-h-screen gradient-bg">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <!-- Componente Vue para gerenciar o dashboard -->
            <dashboard-manager></dashboard-manager>
        </div>
    </div>


@endsection
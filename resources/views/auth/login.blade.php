@extends('layouts.app')

@section('title', 'Login')

@section('content')
<div class="min-h-screen flex items-center justify-center bg-background py-4 px-4 sm:px-6 lg:px-8">
    <div class="max-w-md w-full">
        <!-- Logo/Brand Section -->
        <div class="text-center mb-6">
            <div class="mx-auto w-12 h-12 bg-primary rounded-xl flex items-center justify-center mb-3 shadow-lg">
                <svg class="w-6 h-6 text-primary-foreground" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                </svg>
            </div>
            <h2 class="text-2xl font-bold text-foreground mb-1">
                Bem-vindo de volta
            </h2>
            <p class="text-sm text-muted-foreground">
                Faça login para acessar sua conta
            </p>
        </div>

        <!-- Login Form -->
        <x-ui.card variant="elevated" class="shadow-xl">
            <form class="space-y-6" action="{{ route('login') }}" method="POST">
                @csrf

                <!-- Email Field -->
                <div class="space-y-2">
                    <x-ui.label for="email" variant="required">
                        Endereço de email
                    </x-ui.label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="h-5 w-5 text-muted-foreground" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207"></path>
                            </svg>
                        </div>
                        <x-ui.input 
                            id="email" 
                            name="email" 
                            type="email" 
                            autocomplete="email" 
                            required 
                            placeholder="seu@email.com" 
                            value="{{ old('email') }}"
                            class="pl-10"
                        />
                    </div>
                    @error('email')
                        <p class="mt-2 text-sm text-red-600 flex items-center">
                            <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                            </svg>
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                <!-- Password Field -->
                <div class="space-y-2">
                    <x-ui.label for="password" variant="required">
                        Senha
                    </x-ui.label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="h-5 w-5 text-muted-foreground" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                            </svg>
                        </div>
                        <x-ui.input 
                            id="password" 
                            name="password" 
                            type="password" 
                            autocomplete="current-password" 
                            required 
                            placeholder="Digite sua senha"
                            class="pl-10"
                        />
                    </div>
                    @error('password')
                        <p class="mt-2 text-sm text-red-600 flex items-center">
                            <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                            </svg>
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                <!-- Remember Me -->
                <div class="flex items-center justify-between">
                    <div class="flex items-center">
                        <input id="remember-me" name="remember" type="checkbox" 
                               class="h-4 w-4 text-primary focus:ring-ring border-input rounded">
                        <label for="remember-me" class="ml-3 block text-sm text-foreground font-medium">
                            Lembrar de mim
                        </label>
                    </div>
                    <div class="text-sm">
                        <a href="#" class="font-medium text-primary hover:text-primary/80 transition-colors">
                            Esqueceu a senha?
                        </a>
                    </div>
                </div>

                <!-- Submit Button -->
                <x-ui.button type="submit" size="lg" class="w-full">
                    Entrar
                </x-ui.button>

                <!-- Links -->
                <div class="flex items-center justify-between pt-4 border-t border-border">
                    <a href="{{ route('register') }}" class="text-sm text-muted-foreground hover:text-foreground transition-colors">
                        Criar conta
                    </a>
                    <a href="#" class="text-sm text-muted-foreground hover:text-foreground transition-colors">
                        Esqueci minha senha
                    </a>
                </div>
            </form>
        </x-ui.card>

        <!-- Footer -->
        <div class="text-center mt-4">
            <p class="text-xs text-muted-foreground">
                © 2024 Marca e Deixa. Todos os direitos reservados.
            </p>
        </div>
    </div>
</div>
@endsection
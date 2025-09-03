@extends('layouts.app')

@section('title', 'Registro')

@section('content')
<div class="min-h-screen flex items-center justify-center bg-background py-4 px-4 sm:px-6 lg:px-8">
    <div class="max-w-md w-full">
        <!-- Logo and Header -->
        <div class="text-center mb-6">
            <div class="w-12 h-12 bg-primary rounded-2xl mx-auto mb-3 flex items-center justify-center shadow-lg">
                <svg class="w-6 h-6 text-primary-foreground" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                </svg>
            </div>
            <h2 class="text-2xl font-bold text-foreground mb-1">
                Crie sua conta
            </h2>
            <p class="text-sm text-muted-foreground">
                Junte-se ao Marca e Deixa hoje mesmo
            </p>
        </div>

        <!-- Register Form -->
        <x-ui.card variant="elevated" class="shadow-xl">
            <form class="space-y-6" action="{{ route('register') }}" method="POST">
                @csrf

                <!-- Name Field -->
                <div class="space-y-2">
                    <x-ui.label for="name" variant="required">
                        Nome completo
                    </x-ui.label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="h-5 w-5 text-muted-foreground" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                            </svg>
                        </div>
                        <x-ui.input 
                            id="name" 
                            name="name" 
                            type="text" 
                            autocomplete="name" 
                            required 
                            placeholder="Digite seu nome completo" 
                            value="{{ old('name') }}"
                            class="pl-10"
                        />
                    </div>
                    @error('name')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                
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
                            placeholder="Digite seu email" 
                            value="{{ old('email') }}"
                            class="pl-10"
                        />
                    </div>
                    @error('email')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
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
                            autocomplete="new-password" 
                            required 
                            placeholder="Digite sua senha"
                            class="pl-10"
                        />
                    </div>
                    @error('password')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Confirm Password Field -->
                <div class="space-y-2">
                    <x-ui.label for="password_confirmation" variant="required">
                        Confirmar senha
                    </x-ui.label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="h-5 w-5 text-muted-foreground" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                            </svg>
                        </div>
                        <x-ui.input 
                            id="password_confirmation" 
                            name="password_confirmation" 
                            type="password" 
                            autocomplete="new-password" 
                            required 
                            placeholder="Confirme sua senha"
                            class="pl-10"
                        />
                    </div>
                </div>

                <!-- Submit Button -->
                <x-ui.button type="submit" size="lg" class="w-full">
                    Criar conta
                </x-ui.button>

                <!-- Links -->
                <div class="flex items-center justify-between pt-4 border-t border-border">
                    <a href="{{ route('login') }}" class="text-sm text-muted-foreground hover:text-foreground transition-colors">
                        Já tenho conta
                    </a>
                    <a href="#" class="text-sm text-muted-foreground hover:text-foreground transition-colors">
                        Termos de uso
                    </a>
                </div>
            </form>
        </x-ui.card>

        <!-- Footer -->
        <div class="text-center mt-6">
            <p class="text-xs text-muted-foreground">
                © 2024 Marca e Deixa. Todos os direitos reservados.
            </p>
        </div>
    </div>
</div>
@endsection
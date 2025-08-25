@extends('layouts.app')

@section('title', 'Registro')

@section('content')
<style>
    :root {
        --primary: #242734;
        --secondary: #4B5563;
        --tertiary: #9CA3AF;
        --light: #E5E7EB;
        --lighter: #F3F4F6;
    }
    
    .text-primary { color: var(--primary); }
    .text-secondary { color: var(--secondary); }
    .text-tertiary { color: var(--tertiary); }
    .text-light { color: var(--light); }
    .text-lighter { color: var(--lighter); }
    
    .bg-primary { background-color: var(--primary); }
    .bg-secondary { background-color: var(--secondary); }
    .bg-tertiary { background-color: var(--tertiary); }
    .bg-light { background-color: var(--light); }
    .bg-lighter { background-color: var(--lighter); }
    
    .border-primary { border-color: var(--primary); }
    .border-secondary { border-color: var(--secondary); }
    .border-tertiary { border-color: var(--tertiary); }
    .border-light { border-color: var(--light); }
    .border-lighter { border-color: var(--lighter); }
    
    .hover\:bg-secondary:hover { background-color: var(--secondary); }
    .hover\:text-secondary:hover { color: var(--secondary); }
    .hover\:border-secondary:hover { border-color: var(--secondary); }
    
    .focus\:border-primary:focus { border-color: var(--primary); }
    .focus\:ring-primary:focus { --tw-ring-color: var(--primary); }
    
    .gradient-bg {
        background: linear-gradient(135deg, var(--lighter) 0%, var(--light) 100%);
    }
</style>

<div class="min-h-screen flex items-center justify-center gradient-bg py-4 px-4 sm:px-6 lg:px-8">
    <div class="max-w-md w-full">
        <!-- Logo and Header -->
        <div class="text-center mb-6">
            <div class="w-12 h-12 bg-primary rounded-2xl mx-auto mb-3 flex items-center justify-center shadow-lg">
                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                </svg>
            </div>
            <h2 class="text-2xl font-bold text-primary mb-1">
                Crie sua conta
            </h2>
            <p class="text-sm text-secondary">
                Junte-se ao TheatreFlux hoje mesmo
            </p>
        </div>

        <!-- Register Form -->
        <div class="bg-white rounded-2xl shadow-xl p-6">
            <form class="space-y-4" action="{{ route('register') }}" method="POST">
                @csrf
                
                <!-- Name Field -->
                <div>
                    <label for="name" class="block text-sm font-semibold text-primary mb-2">
                        Nome completo
                    </label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="h-5 w-5 text-tertiary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                            </svg>
                        </div>
                        <input id="name" name="name" type="text" autocomplete="name" required 
                               class="block w-full pl-10 pr-3 py-2.5 border border-light rounded-xl text-primary placeholder-tertiary focus:outline-none focus:ring-2 focus:ring-primary focus:border-primary transition-all duration-200" 
                               placeholder="Digite seu nome completo" value="{{ old('name') }}">
                    </div>
                    @error('name')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                
                <!-- Email Field -->
                <div>
                    <label for="email" class="block text-sm font-semibold text-primary mb-2">
                        Endereço de email
                    </label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="h-5 w-5 text-tertiary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207"></path>
                            </svg>
                        </div>
                        <input id="email" name="email" type="email" autocomplete="email" required 
                               class="block w-full pl-10 pr-3 py-2.5 border border-light rounded-xl text-primary placeholder-tertiary focus:outline-none focus:ring-2 focus:ring-primary focus:border-primary transition-all duration-200" 
                               placeholder="Digite seu email" value="{{ old('email') }}">
                    </div>
                    @error('email')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Password Field -->
                <div>
                    <label for="password" class="block text-sm font-semibold text-primary mb-2">
                        Senha
                    </label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="h-5 w-5 text-tertiary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                            </svg>
                        </div>
                        <input id="password" name="password" type="password" autocomplete="new-password" required 
                               class="block w-full pl-10 pr-3 py-2.5 border border-light rounded-xl text-primary placeholder-tertiary focus:outline-none focus:ring-2 focus:ring-primary focus:border-primary transition-all duration-200" 
                               placeholder="Digite sua senha">
                    </div>
                    @error('password')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Password Confirmation Field -->
                <div>
                    <label for="password_confirmation" class="block text-sm font-semibold text-primary mb-2">
                        Confirmar senha
                    </label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="h-5 w-5 text-tertiary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <input id="password_confirmation" name="password_confirmation" type="password" autocomplete="new-password" required 
                               class="block w-full pl-10 pr-3 py-2.5 border border-light rounded-xl text-primary placeholder-tertiary focus:outline-none focus:ring-2 focus:ring-primary focus:border-primary transition-all duration-200" 
                               placeholder="Confirme sua senha">
                    </div>
                </div>

                <!-- Submit Button -->
                <div class="pt-2">
                    <button type="submit" 
                             class="group relative w-full flex justify-center items-center py-2.5 px-4 border border-transparent text-sm font-semibold rounded-xl text-white hover:bg-secondary focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary transition-all duration-200 shadow-lg hover:shadow-xl" style="background-color: #242734;">
                        <span class="absolute left-0 inset-y-0 flex items-center pl-3">
                            <svg class="h-5 w-5 text-white/80 group-hover:text-white transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"></path>
                            </svg>
                        </span>
                        Criar conta
                    </button>
                </div>

                <!-- Login Link -->
                 <div class="text-center pt-3 border-t border-light">
                     <p class="text-sm text-secondary">
                         Já tem uma conta?
                         <a href="{{ route('login') }}" class="font-semibold text-primary hover:text-secondary transition-colors ml-1">
                             Faça login aqui
                         </a>
                     </p>
                 </div>
             </form>
        </div>

        <!-- Footer -->
        <div class="text-center mt-4">
            <p class="text-xs text-tertiary">
                © 2024 TheatreFlux. Todos os direitos reservados.
            </p>
        </div>
    </div>
</div>
@endsection
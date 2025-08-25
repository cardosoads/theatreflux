@extends('layouts.app')

@section('title', 'Login')

@section('content')
<style>
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
    .border-light { border-color: #E5E7EB; }
    
    .focus\:ring-primary:focus { --tw-ring-color: #242734; }
    .focus\:border-primary:focus { border-color: #242734; }
    .hover\:bg-secondary:hover { background-color: #4B5563; }
</style>

<div class="min-h-screen flex items-center justify-center bg-lighter py-4 px-4 sm:px-6 lg:px-8">
    <div class="max-w-md w-full">
        <!-- Logo/Brand Section -->
        <div class="text-center mb-6">
            <div class="mx-auto w-12 h-12 bg-primary rounded-xl flex items-center justify-center mb-3">
                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                </svg>
            </div>
            <h2 class="text-2xl font-bold text-primary mb-1">
                Bem-vindo de volta
            </h2>
            <p class="text-sm text-secondary">
                Faça login para acessar sua conta
            </p>
        </div>

        <!-- Login Form -->
        <div class="bg-white rounded-2xl shadow-xl p-6">
            <form class="space-y-4" action="{{ route('login') }}" method="POST">
                @csrf
                
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
                                class="block w-full pl-10 pr-3 py-2.5 border border-light rounded-xl text-primary placeholder-tertiary focus:outline-none focus:ring-2 focus:ring-primary focus:border-primary transition-all duration-200 bg-lighter/50" 
                                placeholder="seu@email.com" value="{{ old('email') }}">
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
                        <input id="password" name="password" type="password" autocomplete="current-password" required 
                                class="block w-full pl-10 pr-3 py-2.5 border border-light rounded-xl text-primary placeholder-tertiary focus:outline-none focus:ring-2 focus:ring-primary focus:border-primary transition-all duration-200 bg-lighter/50" 
                                placeholder="••••••••">
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
                               class="h-4 w-4 text-primary focus:ring-primary border-tertiary rounded">
                        <label for="remember-me" class="ml-3 block text-sm text-secondary font-medium">
                            Lembrar de mim
                        </label>
                    </div>
                    <div class="text-sm">
                        <a href="#" class="font-medium text-primary hover:text-secondary transition-colors">
                            Esqueceu a senha?
                        </a>
                    </div>
                </div>

                <!-- Submit Button -->
                 <div>
                     <button type="submit" 
                             class="group relative w-full flex justify-center py-2.5 px-4 border border-transparent text-sm font-semibold rounded-xl text-white hover:bg-secondary focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary transition-all duration-200 transform hover:scale-[1.02]" style="background-color: #242734;">
                        <span class="absolute left-0 inset-y-0 flex items-center pl-3">
                            <svg class="h-5 w-5 text-white/80 group-hover:text-white transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"></path>
                            </svg>
                        </span>
                        Entrar na conta
                    </button>
                </div>

                <!-- Register Link -->
                 <div class="text-center pt-3 border-t border-light">
                     <p class="text-sm text-secondary">
                         Não tem uma conta?
                         <a href="{{ route('register') }}" class="font-semibold text-primary hover:text-secondary transition-colors ml-1">
                             Registre-se gratuitamente
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
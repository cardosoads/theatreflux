@extends('layouts.app')

@section('title', 'Configurações de Perfil')

@section('content')
<style>
    :root {
        --primary-dark: #242734;
        --secondary-gray: #4B5563;
        --tertiary-gray: #9CA3AF;
        --light-gray: #E5E7EB;
        --white: #F3F4F6;
    }
    
    body {
        font-family: 'Open Sans', sans-serif;
    }
</style>

<div class="min-h-screen" style="background-color: var(--white);">
    <!-- Top Navigation Bar -->
    <nav class="border-b" style="background-color: #242734; border-color: var(--light-gray); box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16">
                <!-- Left side - Back button -->
                <div class="flex items-center">
                    <a href="{{ route('dashboard') }}" class="flex items-center space-x-2 text-white hover:bg-gray-600 px-3 py-2 rounded-md transition-colors duration-200">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                        </svg>
                        <span class="text-sm">Voltar</span>
                    </a>
                </div>
                
                <!-- Center - Page Title -->
                <div class="absolute left-1/2 transform -translate-x-1/2">
                    <h1 class="text-xl font-semibold text-white">Configurações de Perfil</h1>
                </div>
                
                <!-- Right side - User info -->
                <div class="flex items-center space-x-4">
                    <span class="text-white text-sm">Olá, {{ Auth::user()->name }}!</span>
                </div>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Success Message -->
        @if(session('success'))
            <div class="mb-6 bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg">
                {{ session('success') }}
            </div>
        @endif

        <!-- Error Messages -->
        @if($errors->any())
            <div class="mb-6 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg">
                <ul class="list-disc list-inside">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('profile.update') }}" enctype="multipart/form-data" class="space-y-8">
            @csrf
            @method('PATCH')

            <!-- Profile Image Section -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <h3 class="text-lg font-semibold mb-4" style="color: var(--primary-dark);">Foto de Perfil</h3>
                
                <div class="flex items-center space-x-6">
                    <div class="relative">
                        @if($user->profile_image)
                            <img src="{{ Storage::url($user->profile_image) }}" alt="Foto de perfil" class="w-24 h-24 rounded-full object-cover border-4 border-gray-200">
                        @else
                            <div class="w-24 h-24 bg-gray-300 rounded-full flex items-center justify-center border-4 border-gray-200">
                                <svg class="w-12 h-12 text-gray-500" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"></path>
                                </svg>
                            </div>
                        @endif
                    </div>
                    
                    <div class="flex-1">
                        <label for="profile_image" class="block text-sm font-medium text-gray-700 mb-2">
                            Escolher nova foto
                        </label>
                        <input type="file" id="profile_image" name="profile_image" accept="image/*" class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-medium file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                        <p class="text-xs text-gray-500 mt-1">PNG, JPG, GIF até 2MB</p>
                        
                        @if($user->profile_image)
                            <button type="button" onclick="deleteProfileImage()" class="mt-2 text-sm text-red-600 hover:text-red-800">
                                Remover foto atual
                            </button>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Personal Information Section -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <h3 class="text-lg font-semibold mb-4" style="color: var(--primary-dark);">Informações Pessoais</h3>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700 mb-2">
                            Nome completo
                        </label>
                        <input type="text" id="name" name="name" value="{{ old('name', $user->name) }}" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    </div>
                    
                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700 mb-2">
                            E-mail
                        </label>
                        <input type="email" id="email" name="email" value="{{ old('email', $user->email) }}" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    </div>
                </div>
            </div>

            <!-- Password Section -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <h3 class="text-lg font-semibold mb-4" style="color: var(--primary-dark);">Alterar Senha</h3>
                <p class="text-sm text-gray-600 mb-4">Deixe em branco se não quiser alterar a senha</p>
                
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div>
                        <label for="current_password" class="block text-sm font-medium text-gray-700 mb-2">
                            Senha atual
                        </label>
                        <input type="password" id="current_password" name="current_password" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    </div>
                    
                    <div>
                        <label for="password" class="block text-sm font-medium text-gray-700 mb-2">
                            Nova senha
                        </label>
                        <input type="password" id="password" name="password" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    </div>
                    
                    <div>
                        <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-2">
                            Confirmar nova senha
                        </label>
                        <input type="password" id="password_confirmation" name="password_confirmation" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    </div>
                </div>
            </div>

            <!-- Save Button -->
            <div class="flex justify-end">
                <button type="submit" class="px-6 py-3 text-white font-medium rounded-lg transition-colors duration-200" style="background-color: #242734;" onmouseover="this.style.backgroundColor='#1a1d2e'" onmouseout="this.style.backgroundColor='#242734'">
                    Salvar Alterações
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Delete Profile Image Form -->
<form id="deleteImageForm" method="POST" action="{{ route('profile.delete-image') }}" style="display: none;">
    @csrf
    @method('DELETE')
</form>

<script>
function deleteProfileImage() {
    if (confirm('Tem certeza que deseja remover sua foto de perfil?')) {
        document.getElementById('deleteImageForm').submit();
    }
}
</script>
@endsection
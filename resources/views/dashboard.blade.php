@extends('layouts.app')

@section('title', 'Dashboard - Projetos')

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
                <!-- Left side - Logo -->
                <div class="flex items-center">
                    <div class="flex items-center space-x-3">
                        <!-- Logo da empresa -->
                        <div class="w-8 h-8 bg-white rounded-lg flex items-center justify-center">
                            <svg class="w-5 h-5 text-gray-800" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M12 2L2 7v10c0 5.55 3.84 9.739 9 11 5.16-1.261 9-5.45 9-11V7l-10-5z"/>
                            </svg>
                        </div>
                    </div>
                </div>
                
                <!-- Center - Brand Name -->
                <div class="absolute left-1/2 transform -translate-x-1/2">
                    <h1 class="text-xl font-semibold text-white">Marca e Deixa</h1>
                </div>
                
                <!-- Right side - User menu -->
                <div class="flex items-center space-x-4">
                    <span class="text-white text-sm">Olá, Usuario1!</span>
                    <div class="relative">
                        <button class="flex items-center space-x-2 text-white hover:bg-gray-600 px-3 py-2 rounded-md transition-colors duration-200" onclick="toggleUserMenu()">
                            <div class="w-8 h-8 bg-gray-600 rounded-full flex items-center justify-center">
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"></path>
                                </svg>
                            </div>
                        </button>
                        
                        <!-- Dropdown menu -->
                        <div id="userMenu" class="hidden absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-xl py-2 z-50 border border-gray-200">
                            <a href="#" class="block px-4 py-3 text-sm text-gray-700 hover:bg-gray-50 transition-colors duration-150">Configurações de perfil</a>
                            <a href="#" class="block px-4 py-3 text-sm text-gray-700 hover:bg-gray-50 transition-colors duration-150">Plano premium</a>
                            <div class="border-t border-gray-100"></div>
                            <form method="POST" action="{{ route('logout') }}" class="block">
                                @csrf
                                <button type="submit" class="w-full text-left px-4 py-3 text-sm text-gray-700 hover:bg-gray-50 transition-colors duration-150">
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
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8" style="background: linear-gradient(135deg, #F9FAFB 0%, #F3F4F6 100%); min-height: calc(100vh - 64px);">
        <!-- Projects Header -->
        <div class="mb-8">
            <div class="flex items-center justify-between">
                <div>
                    <h2 class="text-3xl font-bold" style="color: var(--primary-dark);">PROJETOS</h2>
                    <p class="text-sm mt-1" style="color: var(--tertiary-gray);">Gerencie seus projetos teatrais</p>
                </div>
                <div class="flex items-center space-x-3">
                    <div class="relative flex-1 max-w-md">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                            </svg>
                        </div>
                        <input 
                            type="text" 
                            id="search-projects" 
                            placeholder="Buscar projetos por nome ou data..." 
                            class="w-full pl-10 pr-4 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors duration-200"
                            oninput="filterProjectsRealTime(this.value)"
                        >
                        <div class="absolute inset-y-0 right-0 pr-3 flex items-center">
                            <button 
                                id="clear-search" 
                                onclick="clearSearch()" 
                                class="text-gray-400 hover:text-gray-600 transition-colors duration-200 hidden"
                            >
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                </svg>
                            </button>
                        </div>
                    </div>
                    <button id="sort-button" onclick="toggleSort()" class="px-4 py-2 text-sm font-medium text-gray-600 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors duration-200">
                        <svg id="sort-icon" class="w-4 h-4 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16V4m0 0L3 8m4-4l4 4m6 0v12m0 0l4-4m-4 4l-4-4"></path>
                        </svg>
                        <span id="sort-text">Ordenar por Data</span>
                    </button>
                </div>
            </div>
        </div>
        
        <!-- Search Results Info -->
        <div id="search-results-info" class="hidden mt-4 p-3 bg-blue-50 border border-blue-200 rounded-lg">
            <div class="flex items-center">
                <svg class="w-4 h-4 text-blue-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <span id="search-results-text" class="text-sm text-blue-700"></span>
            </div>
        </div>
        
        <!-- Projects Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-8">
            <!-- Project Card 1 - Hamlet SP -->
            <div class="project-card group cursor-pointer" onclick="openProject('hamlet-sp')">
                <div class="relative p-5 h-full flex flex-col justify-between">
                    <!-- Header with Icon -->
                    <div class="flex items-start justify-between mb-4">
                        <div class="relative">
                            <div class="w-12 h-12 rounded-xl flex items-center justify-center shadow-lg group-hover:shadow-xl transition-all duration-300" style="background: linear-gradient(135deg, #242734 0%, #4B5563 100%);">
                                <svg class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M14,2H6A2,2 0 0,0 4,4V20A2,2 0 0,0 6,22H18A2,2 0 0,0 20,20V8L14,2M18,20H6V4H13V9H18V20Z"/>
                                </svg>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Content -->
                    <div class="flex-1 flex flex-col justify-between">
                        <div>
                            <h3 class="font-semibold mb-1" style="color: var(--primary-dark);">Hamlet SP</h3>
                            <p class="mb-3" style="color: var(--tertiary-gray);">Tragédia Shakespeariana</p>
                        </div>
                        
                        <!-- Creation Date -->
                        <div class="flex items-center justify-end mt-auto">
                            <p class="text-sm" style="color: var(--tertiary-gray);">15 Jan, 2024</p>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Project Card 2 - Macbeth Rio -->
            <div class="project-card group cursor-pointer" onclick="openProject('macbeth-rio')">
                <div class="relative p-5 h-full flex flex-col justify-between">
                    <!-- Header with Icon -->
                    <div class="flex items-start justify-between mb-4">
                        <div class="relative">
                            <div class="w-12 h-12 rounded-xl flex items-center justify-center shadow-lg group-hover:shadow-xl transition-all duration-300" style="background: linear-gradient(135deg, #4B5563 0%, #6B7280 100%);">
                                <svg class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M14,2H6A2,2 0 0,0 4,4V20A2,2 0 0,0 6,22H18A2,2 0 0,0 20,20V8L14,2M18,20H6V4H13V9H18V20Z"/>
                                </svg>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Content -->
                    <div class="flex-1 flex flex-col justify-between">
                        <div>
                            <h3 class="font-semibold mb-1" style="color: var(--primary-dark);">Macbeth Rio</h3>
                            <p class="mb-3" style="color: var(--tertiary-gray);">Drama Psicológico</p>
                        </div>
                        
                        <!-- Creation Date -->
                        <div class="flex items-center justify-end mt-auto">
                            <p class="text-sm" style="color: var(--tertiary-gray);">8 Fev, 2024</p>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Project Card 3 - Otelo Porto Alegre -->
            <div class="project-card group cursor-pointer" onclick="openProject('otelo-poa')">
                <div class="relative p-5 h-full flex flex-col justify-between">
                    <!-- Header with Icon -->
                    <div class="flex items-start justify-between mb-4">
                        <div class="relative">
                            <div class="w-12 h-12 rounded-xl flex items-center justify-center shadow-lg group-hover:shadow-xl transition-all duration-300" style="background: linear-gradient(135deg, #6B7280 0%, #9CA3AF 100%);">
                                <svg class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M14,2H6A2,2 0 0,0 4,4V20A2,2 0 0,0 6,22H18A2,2 0 0,0 20,20V8L14,2M18,20H6V4H13V9H18V20Z"/>
                                </svg>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Content -->
                    <div class="flex-1 flex flex-col justify-between">
                        <div>
                            <h3 class="font-semibold mb-1" style="color: var(--primary-dark);">Otelo Porto Alegre</h3>
                            <p class="mb-3" style="color: var(--tertiary-gray);">Tragédia do Ciúme</p>
                        </div>
                        
                        <!-- Creation Date -->
                        <div class="flex items-center justify-end mt-auto">
                            <p class="text-sm" style="color: var(--tertiary-gray);">22 Mar, 2024</p>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Project Card 4 - Romeo e Julieta -->
            <div class="project-card group cursor-pointer" onclick="openProject('romeo-julieta')">
                <div class="relative p-5 h-full flex flex-col justify-between">
                    <!-- Header with Icon -->
                    <div class="flex items-start justify-between mb-4">
                        <div class="relative">
                            <div class="w-12 h-12 rounded-xl flex items-center justify-center shadow-lg group-hover:shadow-xl transition-all duration-300" style="background: linear-gradient(135deg, #374151 0%, #4B5563 100%);">
                                <svg class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M14,2H6A2,2 0 0,0 4,4V20A2,2 0 0,0 6,22H18A2,2 0 0,0 20,20V8L14,2M18,20H6V4H13V9H18V20Z"/>
                                </svg>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Content -->
                    <div class="flex-1 flex flex-col justify-between">
                        <div>
                            <h3 class="font-semibold mb-1" style="color: var(--primary-dark);">Romeo e Julieta</h3>
                            <p class="mb-3" style="color: var(--tertiary-gray);">Romance Trágico</p>
                        </div>
                        
                        <!-- Creation Date -->
                        <div class="flex items-center justify-end mt-auto">
                            <p class="text-sm" style="color: var(--tertiary-gray);">5 Abr, 2024</p>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Project Card 5 - A Tempestade -->
            <div class="project-card group cursor-pointer" onclick="openProject('tempestade')">
                <div class="relative p-5 h-full flex flex-col justify-between">
                    <!-- Header with Icon -->
                    <div class="flex items-start justify-between mb-4">
                        <div class="relative">
                            <div class="w-12 h-12 rounded-xl flex items-center justify-center shadow-lg group-hover:shadow-xl transition-all duration-300" style="background: linear-gradient(135deg, #1F2937 0%, #374151 100%);">
                                <svg class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M14,2H6A2,2 0 0,0 4,4V20A2,2 0 0,0 6,22H18A2,2 0 0,0 20,20V8L14,2M18,20H6V4H13V9H18V20Z"/>
                                </svg>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Content -->
                    <div class="flex-1 flex flex-col justify-between">
                        <div>
                            <h3 class="font-semibold mb-1" style="color: var(--primary-dark);">A Tempestade</h3>
                            <p class="mb-3" style="color: var(--tertiary-gray);">Fantasia Mágica</p>
                        </div>
                        
                        <!-- Creation Date -->
                        <div class="flex items-center justify-end mt-auto">
                            <p class="text-sm" style="color: var(--tertiary-gray);">12 Mai, 2024</p>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Create New Project Card -->
            <div class="create-project-card group cursor-pointer" onclick="createNewProject()">
                <div class="relative p-8 h-full flex flex-col items-center justify-center text-center">
                    <!-- Animated Plus Icon -->
                    <div class="relative mb-6">
                        <div class="w-16 h-16 rounded-2xl flex items-center justify-center transition-all duration-300 group-hover:scale-110" style="background: linear-gradient(135deg, #4B5563 0%, #6B7280 100%); box-shadow: 0 8px 25px rgba(75, 85, 99, 0.3);">
                            <svg class="w-8 h-8 text-white transition-transform duration-300 group-hover:rotate-90" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                            </svg>
                        </div>
                        <!-- Floating Dots Animation -->
                        <div class="absolute -top-2 -right-2 w-3 h-3 rounded-full opacity-60 animate-ping" style="background-color: #9CA3AF;"></div>
                        <div class="absolute -bottom-2 -left-2 w-2 h-2 rounded-full opacity-40 animate-pulse" style="background-color: #6B7280;"></div>
                    </div>
                    
                    <!-- Content -->
                    <div class="space-y-2">
                        <h3 class="font-bold text-xl" style="color: var(--primary-dark);">Criar Novo Projeto</h3>
                        <p class="text-sm max-w-xs" style="color: var(--tertiary-gray);">Comece um novo projeto teatral do zero com nossas ferramentas avançadas</p>
                    </div>
                    
                    <!-- Action Button -->
                    <div class="mt-6 opacity-0 group-hover:opacity-100 transition-all duration-300 transform translate-y-2 group-hover:translate-y-0">
                        <div class="px-4 py-2 bg-white rounded-lg shadow-md text-sm font-medium transition-colors duration-200" style="border: 1px solid #E5E7EB; color: #4B5563;" onmouseover="this.style.backgroundColor='#F9FAFB'" onmouseout="this.style.backgroundColor='white'">
                            Começar Agora
                        </div>
                    </div>
                    
                    <!-- Background Pattern -->
                    <div class="absolute inset-0 opacity-5 pointer-events-none">
                        <div class="absolute top-4 left-4 w-2 h-2 rounded-full" style="background-color: #9CA3AF;"></div>
                        <div class="absolute top-8 right-6 w-1 h-1 rounded-full" style="background-color: #6B7280;"></div>
                        <div class="absolute bottom-6 left-8 w-1.5 h-1.5 rounded-full" style="background-color: #4B5563;"></div>
                        <div class="absolute bottom-4 right-4 w-2 h-2 rounded-full" style="background-color: #374151;"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>



<script>
    let searchTimeout;
    
    function toggleUserMenu() {
        const menu = document.getElementById('userMenu');
        menu.classList.toggle('hidden');
    }
    
    function openProject(projectId) {
        // Redirect to editor with project
        window.location.href = '/editor?project=' + projectId;
    }
    
    function createNewProject() {
        // Redirect to editor for new project
        window.location.href = '/editor';
    }
    
    function filterProjectsRealTime(searchValue) {
        // Clear previous timeout
        clearTimeout(searchTimeout);
        
        // Show/hide clear button
        const clearButton = document.getElementById('clear-search');
        if (searchValue.trim()) {
            clearButton.classList.remove('hidden');
        } else {
            clearButton.classList.add('hidden');
        }
        
        // Debounce search to avoid too many calls
        searchTimeout = setTimeout(() => {
            performSearch(searchValue.toLowerCase().trim());
        }, 300);
    }
    
    function performSearch(searchValue) {
        const projectCards = document.querySelectorAll('.project-card:not(.create-project-card)');
        const searchResultsInfo = document.getElementById('search-results-info');
        const searchResultsText = document.getElementById('search-results-text');
        
        let visibleCount = 0;
        let totalCount = projectCards.length;
        
        projectCards.forEach(card => {
            let shouldShow = true;
            
            if (searchValue) {
                const projectName = card.querySelector('h3');
                const projectDate = card.querySelector('p.text-sm'); // Date element
                
                let nameMatch = false;
                let dateMatch = false;
                
                // Check name match
                if (projectName) {
                    const nameText = projectName.textContent.toLowerCase();
                    nameMatch = nameText.includes(searchValue);
                }
                
                // Check date match (if the search looks like a date or partial date)
                if (projectDate) {
                    const dateText = projectDate.textContent.toLowerCase();
                    dateMatch = dateText.includes(searchValue);
                }
                
                // Show if either name or date matches
                shouldShow = nameMatch || dateMatch;
            }
            
            // Animate show/hide
            if (shouldShow) {
                visibleCount++;
                card.style.display = 'block';
                card.style.opacity = '1';
                card.style.transform = 'scale(1)';
                card.style.transition = 'all 0.3s ease';
            } else {
                card.style.opacity = '0';
                card.style.transform = 'scale(0.95)';
                card.style.transition = 'all 0.3s ease';
                setTimeout(() => {
                    if (card.style.opacity === '0') {
                        card.style.display = 'none';
                    }
                }, 300);
            }
        });
        
        // Update search results info
        if (searchValue) {
            searchResultsInfo.classList.remove('hidden');
            if (visibleCount === 0) {
                searchResultsText.textContent = 'Nenhum projeto encontrado para "' + searchValue + '"';
                searchResultsInfo.className = 'mt-4 p-3 bg-red-50 border border-red-200 rounded-lg';
                searchResultsText.className = 'text-sm text-red-700';
            } else if (visibleCount === totalCount) {
                searchResultsInfo.classList.add('hidden');
            } else {
                searchResultsText.textContent = visibleCount + ' de ' + totalCount + ' projetos encontrados para "' + searchValue + '"';
                searchResultsInfo.className = 'mt-4 p-3 bg-blue-50 border border-blue-200 rounded-lg';
                searchResultsText.className = 'text-sm text-blue-700';
            }
        } else {
            searchResultsInfo.classList.add('hidden');
        }
    }
    
    function clearSearch() {
        const searchInput = document.getElementById('search-projects');
        const clearButton = document.getElementById('clear-search');
        const searchResultsInfo = document.getElementById('search-results-info');
        
        searchInput.value = '';
        clearButton.classList.add('hidden');
        searchResultsInfo.classList.add('hidden');
        
        // Show all projects
        const projectCards = document.querySelectorAll('.project-card');
        projectCards.forEach(card => {
            card.style.display = 'block';
            card.style.opacity = '1';
            card.style.transform = 'scale(1)';
            card.style.transition = 'all 0.3s ease';
        });
        
        // Focus back on search input
        searchInput.focus();
    }
    
    // Add keyboard shortcut for search (Ctrl+F or Cmd+F)
    document.addEventListener('keydown', function(e) {
        if ((e.ctrlKey || e.metaKey) && e.key === 'f') {
            e.preventDefault();
            document.getElementById('search-projects').focus();
        }
        
        // ESC to clear search
        if (e.key === 'Escape') {
            const searchInput = document.getElementById('search-projects');
            if (searchInput.value) {
                clearSearch();
            }
        }
    });
    
    // Sort functionality
    let currentSortOrder = 'none'; // 'none', 'asc', 'desc'
    
    function toggleSort() {
        const sortButton = document.getElementById('sort-button');
        const sortIcon = document.getElementById('sort-icon');
        const sortText = document.getElementById('sort-text');
        
        // Cycle through sort states: none -> asc -> desc -> none
        if (currentSortOrder === 'none') {
            currentSortOrder = 'asc';
            sortText.textContent = 'Data: Mais Antigos';
            sortButton.classList.add('bg-blue-50', 'border-blue-300', 'text-blue-700');
            sortButton.classList.remove('text-gray-600', 'bg-white', 'border-gray-300');
            // Icon for ascending (oldest first)
            sortIcon.innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4h13M3 8h9m-9 4h6m4 0l4-4m0 0l4 4m-4-4v12"></path>';
        } else if (currentSortOrder === 'asc') {
            currentSortOrder = 'desc';
            sortText.textContent = 'Data: Mais Recentes';
            // Icon for descending (newest first)
            sortIcon.innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4h13M3 8h9m-9 4h9m5-4v12m0 0l-4-4m4 4l4-4"></path>';
        } else {
            currentSortOrder = 'none';
            sortText.textContent = 'Ordenar por Data';
            sortButton.classList.remove('bg-blue-50', 'border-blue-300', 'text-blue-700');
            sortButton.classList.add('text-gray-600', 'bg-white', 'border-gray-300');
            // Default sort icon
            sortIcon.innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16V4m0 0L3 8m4-4l4 4m6 0v12m0 0l4-4m-4 4l-4-4"></path>';
        }
        
        sortProjects();
    }
    
    function sortProjects() {
        const projectsGrid = document.querySelector('.grid');
        const projectCards = Array.from(document.querySelectorAll('.project-card:not(.create-project-card)'));
        const createProjectCard = document.querySelector('.create-project-card');
        
        if (currentSortOrder === 'none') {
            // Reset to original order - no sorting needed, just return
            return;
        }
        
        // Parse dates and sort
        const sortedCards = projectCards.sort((a, b) => {
            const dateA = parseDateFromCard(a);
            const dateB = parseDateFromCard(b);
            
            if (currentSortOrder === 'asc') {
                return dateA - dateB; // Oldest first
            } else {
                return dateB - dateA; // Newest first
            }
        });
        
        // Remove all project cards from DOM
        projectCards.forEach(card => card.remove());
        
        // Re-add sorted cards before the create project card
        sortedCards.forEach(card => {
            projectsGrid.insertBefore(card, createProjectCard);
        });
        
        // Add smooth animation
        sortedCards.forEach((card, index) => {
            card.style.opacity = '0';
            card.style.transform = 'translateY(20px)';
            setTimeout(() => {
                card.style.transition = 'all 0.3s ease';
                card.style.opacity = '1';
                card.style.transform = 'translateY(0)';
            }, index * 50);
        });
    }
    
    function parseDateFromCard(card) {
        const dateElement = card.querySelector('p.text-sm');
        if (!dateElement) return new Date(0);
        
        const dateText = dateElement.textContent.trim();
        
        // Parse dates in format "15 Jan, 2024"
        const months = {
            'jan': 0, 'fev': 1, 'mar': 2, 'abr': 3, 'mai': 4, 'jun': 5,
            'jul': 6, 'ago': 7, 'set': 8, 'out': 9, 'nov': 10, 'dez': 11
        };
        
        const parts = dateText.toLowerCase().replace(',', '').split(' ');
        if (parts.length >= 3) {
            const day = parseInt(parts[0]);
            const month = months[parts[1]];
            const year = parseInt(parts[2]);
            
            if (!isNaN(day) && month !== undefined && !isNaN(year)) {
                return new Date(year, month, day);
            }
        }
        
        return new Date(0); // Fallback date
    }
    
    // Close dropdown when clicking outside
    document.addEventListener('click', function(event) {
        const userMenu = document.getElementById('userMenu');
        const userButton = event.target.closest('button');
        
        if (!userButton || !userButton.onclick) {
            userMenu.classList.add('hidden');
        }
    });
</script>
@endsection
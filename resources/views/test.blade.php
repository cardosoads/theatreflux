<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Marca e Deixa - Teste de Melhorias</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-100">
    <div id="app">
        <div class="min-h-screen">
            <!-- Header -->
            <header class="bg-white shadow-sm border-b border-gray-200">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div class="flex justify-between items-center py-4">
                        <div class="flex items-center">
                            <h1 class="text-2xl font-bold text-gray-900">Marca e Deixa</h1>
                            <span class="ml-2 px-2 py-1 bg-blue-100 text-blue-800 text-xs font-medium rounded">Teste</span>
                        </div>
                        <nav class="flex space-x-4">
                            <a href="/" class="text-gray-600 hover:text-gray-900 px-3 py-2 rounded-md text-sm font-medium">Início</a>
                            <a href="/editor" class="text-gray-600 hover:text-gray-900 px-3 py-2 rounded-md text-sm font-medium">Editor</a>
                            <a href="/test" class="bg-blue-100 text-blue-700 px-3 py-2 rounded-md text-sm font-medium">Teste</a>
                        </nav>
                    </div>
                </div>
            </header>

            <!-- Main Content -->
            <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
                <div class="mb-8">
                    <h2 class="text-3xl font-bold text-gray-900 mb-4">Teste de Melhorias</h2>
                    <p class="text-gray-600 mb-6">
                        Esta página testa as melhorias implementadas no Marca e Deixa, incluindo:
                    </p>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 mb-8">
                        <div class="bg-white p-4 rounded-lg shadow">
                            <div class="flex items-center mb-2">
                                <div class="w-3 h-3 bg-green-500 rounded-full mr-2"></div>
                                <h3 class="font-semibold">Loops Infinitos Corrigidos</h3>
                            </div>
                            <p class="text-sm text-gray-600">Watchers otimizados para evitar recursão</p>
                        </div>
                        
                        <div class="bg-white p-4 rounded-lg shadow">
                            <div class="flex items-center mb-2">
                                <div class="w-3 h-3 bg-green-500 rounded-full mr-2"></div>
                                <h3 class="font-semibold">Debounce Implementado</h3>
                            </div>
                            <p class="text-sm text-gray-600">Atualizações com delay para melhor performance</p>
                        </div>
                        
                        <div class="bg-white p-4 rounded-lg shadow">
                            <div class="flex items-center mb-2">
                                <div class="w-3 h-3 bg-green-500 rounded-full mr-2"></div>
                                <h3 class="font-semibold">Seletores Melhorados</h3>
                            </div>
                            <p class="text-sm text-gray-600">IDs e classes específicas para campos de entrada</p>
                        </div>
                    </div>
                </div>

                <!-- Shadcn UI Test Section -->
                <div class="mb-8">
                    <div class="bg-white rounded-lg shadow p-6">
                        <h3 class="text-xl font-semibold mb-4">Shadcn UI Components Test</h3>
                        <div class="border rounded-lg p-4">
                            <shadcn-test-component></shadcn-test-component>
                        </div>
                    </div>
                </div>

                <!-- Test Components -->
                <div class="max-w-2xl mx-auto">
                    <!-- Test Properties Panel -->
                    <div class="bg-white rounded-lg shadow-lg p-6">
                        <h3 class="text-xl font-semibold mb-4">Painel de Propriedades de Teste</h3>
                        <test-properties-panel></test-properties-panel>
                    </div>
                </div>

                <!-- Instructions -->
                <div class="mt-8 bg-blue-50 border border-blue-200 rounded-lg p-6">
                    <h3 class="text-lg font-semibold text-blue-900 mb-3">Como Testar:</h3>
                    <ol class="list-decimal list-inside space-y-2 text-blue-800">
                        <li>Use os campos de entrada nos painéis acima para modificar propriedades</li>
                        <li>Observe que não há mais erros de "Maximum call stack size exceeded" no console</li>
                        <li>Teste os seletores específicos (IDs como #test-position-x, #test-position-y)</li>
                        <li>Verifique que as atualizações são suaves e não causam travamentos</li>
                        <li>Use as ferramentas de desenvolvedor para inspecionar os elementos</li>
                    </ol>
                </div>

                <!-- Console Log Display -->
                <div class="mt-8 bg-gray-900 text-green-400 rounded-lg p-4 font-mono text-sm">
                    <h4 class="text-white mb-2">Console de Teste:</h4>
                    <div id="console-output" class="h-32 overflow-y-auto">
                        <div>Aguardando interações...</div>
                    </div>
                </div>
            </main>
        </div>
    </div>

    <script type="module">
        // Console logging function
        window.logToConsole = function(message, data = null) {
            const consoleOutput = document.getElementById('console-output');
            if (consoleOutput) {
                const timestamp = new Date().toLocaleTimeString();
                const logEntry = document.createElement('div');
                logEntry.innerHTML = `<span class="text-gray-400">[${timestamp}]</span> ${message} ${data ? JSON.stringify(data) : ''}`;
                consoleOutput.appendChild(logEntry);
                consoleOutput.scrollTop = consoleOutput.scrollHeight;
            }
        };

        // Override console methods to display in our custom console
        const originalLog = console.log;
        const originalError = console.error;
        const originalWarn = console.warn;

        console.log = function(...args) {
            originalLog.apply(console, args);
            if (window.logToConsole) {
                window.logToConsole('LOG: ' + args.join(' '));
            }
        };

        console.error = function(...args) {
            originalError.apply(console, args);
            if (window.logToConsole) {
                window.logToConsole('<span class="text-red-400">ERROR: ' + args.join(' ') + '</span>');
            }
        };

        console.warn = function(...args) {
            originalWarn.apply(console, args);
            if (window.logToConsole) {
                window.logToConsole('<span class="text-yellow-400">WARN: ' + args.join(' ') + '</span>');
            }
        };

        // Log initial message
        document.addEventListener('DOMContentLoaded', function() {
            if (window.logToConsole) {
                window.logToConsole('Página de teste carregada com sucesso!');
            }
        });
    </script>
</body>
</html>
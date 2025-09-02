<?php

use App\Http\Controllers\ProjectController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth');

// Rotas de projetos protegidas por autenticação
Route::middleware('auth')->group(function () {
    // CRUD de projetos
    Route::apiResource('projects', ProjectController::class);
    
    // Salvamento automático
    Route::post('projects/{project}/autosave', [ProjectController::class, 'autosave']);
    
    // Gerar token de compartilhamento
    Route::post('projects/{project}/share', [ProjectController::class, 'generateShareToken']);
});

// Rota pública para acessar projetos compartilhados
Route::get('projects/shared/{token}', [ProjectController::class, 'showByToken']);

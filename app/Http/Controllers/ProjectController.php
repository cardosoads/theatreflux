<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\Scene;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class ProjectController extends Controller
{
    /**
     * Lista todos os projetos do usuário autenticado
     */
    public function index(Request $request): JsonResponse
    {
        $user = Auth::user();
        
        $projects = Project::forUser($user)
            ->with('scenes')
            ->orderBy('updated_at', 'desc')
            ->paginate(10);

        return response()->json($projects);
    }

    /**
     * Cria um novo projeto
     */
    public function store(Request $request): JsonResponse
    {
        try {
            // Debug: Verificar autenticação
            \Log::info('ProjectController@store - Iniciando criação de projeto');
            \Log::info('ProjectController@store - User authenticated:', [
                'user_id' => Auth::id(), 
                'user' => Auth::user() ? Auth::user()->toArray() : null,
                'is_authenticated' => Auth::check()
            ]);
            \Log::info('ProjectController@store - Request data:', $request->all());
            \Log::info('ProjectController@store - Request headers:', $request->headers->all());
            
            // Verificar se usuário está autenticado
            if (!Auth::check()) {
                \Log::error('ProjectController@store - Usuário não autenticado');
                return response()->json([
                    'message' => 'Usuário não autenticado',
                    'error' => 'UNAUTHENTICATED'
                ], 401);
            }
            
            $validated = $request->validate([
                'title' => 'required|string|max:255',
                'description' => 'nullable|string',
                'data' => 'nullable|array',
                'settings' => 'nullable|array',
                'is_public' => 'boolean'
            ]);
            
            \Log::info('ProjectController@store - Dados validados:', $validated);

            $project = Project::create([
                ...$validated,
                'user_id' => Auth::id(),
                'last_saved_at' => now()
            ]);
            
            \Log::info('ProjectController@store - Projeto criado:', ['project_id' => $project->id]);

            // Criar cena inicial
            $scene = $project->scenes()->create([
                'name' => 'Cena 1',
                'description' => 'Cena inicial',
                'order' => 1,
                'data' => [],
                'stage_config' => []
            ]);
            
            \Log::info('ProjectController@store - Cena inicial criada:', ['scene_id' => $scene->id]);

            $result = $project->load('scenes');
            \Log::info('ProjectController@store - Sucesso:', ['project' => $result->toArray()]);

            return response()->json([
                'success' => true,
                'project' => $result,
                'id' => $project->id
            ], 201);
            
        } catch (\Illuminate\Validation\ValidationException $e) {
            \Log::error('ProjectController@store - Erro de validação:', [
                'errors' => $e->errors(),
                'message' => $e->getMessage()
            ]);
            return response()->json([
                'message' => 'Dados inválidos',
                'errors' => $e->errors(),
                'error' => 'VALIDATION_ERROR'
            ], 422);
            
        } catch (\Exception $e) {
            \Log::error('ProjectController@store - Erro interno:', [
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString()
            ]);
            return response()->json([
                'message' => 'Erro interno do servidor',
                'error' => 'INTERNAL_ERROR',
                'debug' => config('app.debug') ? $e->getMessage() : null
            ], 500);
        }
    }

    /**
     * Exibe um projeto específico
     */
    public function show(Project $project): JsonResponse
    {
        $user = Auth::user();
        
        if (!$project->canBeAccessedBy($user)) {
            return response()->json(['message' => 'Acesso negado'], 403);
        }

        return response()->json([
            'project' => $project->load('scenes')
        ]);
    }

    /**
     * Atualiza um projeto
     */
    public function update(Request $request, Project $project): JsonResponse
    {
        $user = Auth::user();
        
        if ($project->user_id !== $user->id) {
            return response()->json(['message' => 'Acesso negado'], 403);
        }

        $validated = $request->validate([
            'title' => 'sometimes|required|string|max:255',
            'description' => 'nullable|string',
            'data' => 'nullable|array',
            'settings' => 'nullable|array',
            'is_public' => 'boolean'
        ]);

        $project->update([
            ...$validated,
            'last_saved_at' => now()
        ]);

        return response()->json([
            'project' => $project->load('scenes')
        ]);
    }

    /**
     * Remove um projeto
     */
    public function destroy(Project $project): JsonResponse
    {
        $user = Auth::user();
        
        if ($project->user_id !== $user->id) {
            return response()->json(['message' => 'Acesso negado'], 403);
        }

        $project->delete();

        return response()->json(['message' => 'Projeto removido com sucesso']);
    }

    /**
     * Salva o estado completo do projeto (autosave)
     */
    public function autosave(Request $request, Project $project): JsonResponse
    {
        $user = Auth::user();
        
        if ($project->user_id !== $user->id) {
            return response()->json(['message' => 'Acesso negado'], 403);
        }
        
        $validated = $request->validate([
            'data' => 'required|array',
            'scenes' => 'required|array',
            'scenes.*.id' => 'nullable|integer|exists:scenes,id',
            'scenes.*.name' => 'required|string|max:255',
            'scenes.*.description' => 'nullable|string',
            'scenes.*.order' => 'required|integer',
            'scenes.*.data' => 'required|array',
            'scenes.*.stage_config' => 'nullable|array'
        ]);

        DB::transaction(function () use ($project, $validated) {
            // Atualizar dados do projeto
            $project->update([
                'data' => $validated['data'],
                'last_saved_at' => now()
            ]);

            // Atualizar ou criar cenas
            foreach ($validated['scenes'] as $sceneData) {
                if (isset($sceneData['id'])) {
                    // Atualizar cena existente
                    $scene = $project->scenes()->find($sceneData['id']);
                    if ($scene) {
                        $scene->update($sceneData);
                    }
                } else {
                    // Criar nova cena
                    $project->scenes()->create($sceneData);
                }
            }
        });
        
        return response()->json([
            'message' => 'Projeto salvo automaticamente',
            'last_saved_at' => $project->fresh()->last_saved_at
        ]);
    }

    /**
     * Gera token de compartilhamento
     */
    public function generateShareToken(Project $project): JsonResponse
    {
        $user = Auth::user();
        
        if ($project->user_id !== $user->id) {
            return response()->json(['message' => 'Acesso negado'], 403);
        }

        $token = $project->generateShareToken();

        return response()->json([
            'share_token' => $token,
            'share_url' => url("/projects/shared/{$token}")
        ]);
    }

    /**
     * Acessa projeto via token de compartilhamento
     */
    public function showByToken(string $token): JsonResponse
    {
        $project = Project::where('share_token', $token)
            ->where('is_public', true)
            ->with('scenes')
            ->first();

        if (!$project) {
            return response()->json(['message' => 'Projeto não encontrado'], 404);
        }

        return response()->json($project);
    }
}

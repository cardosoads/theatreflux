<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Scene extends Model
{
    protected $fillable = [
        'name',
        'description',
        'order',
        'data',
        'stage_config',
        'project_id'
    ];

    protected $casts = [
        'data' => 'array',
        'stage_config' => 'array',
        'order' => 'integer'
    ];

    /**
     * Relacionamento com o projeto
     */
    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }

    /**
     * Scope para ordenar cenas
     */
    public function scopeOrdered($query)
    {
        return $query->orderBy('order');
    }

    /**
     * Reordena as cenas do projeto
     */
    public static function reorderForProject(int $projectId, array $sceneIds): void
    {
        foreach ($sceneIds as $index => $sceneId) {
            self::where('id', $sceneId)
                ->where('project_id', $projectId)
                ->update(['order' => $index + 1]);
        }
    }

    /**
     * Duplica uma cena
     */
    public function duplicate(string $newName = null): Scene
    {
        $newScene = $this->replicate();
        $newScene->name = $newName ?? $this->name . ' (Cópia)';
        $newScene->order = $this->project->scenes()->max('order') + 1;
        $newScene->save();
        
        return $newScene;
    }
}

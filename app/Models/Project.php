<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class Project extends Model
{
    protected $fillable = [
        'title',
        'description',
        'data',
        'settings',
        'is_public',
        'share_token',
        'user_id',
        'last_saved_at'
    ];

    protected $casts = [
        'data' => 'array',
        'settings' => 'array',
        'is_public' => 'boolean',
        'last_saved_at' => 'datetime'
    ];

    protected $dates = [
        'last_saved_at'
    ];

    /**
     * Relacionamento com o usuário proprietário
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relacionamento com as cenas do projeto
     */
    public function scenes(): HasMany
    {
        return $this->hasMany(Scene::class)->orderBy('order');
    }

    /**
     * Gera um token único para compartilhamento
     */
    public function generateShareToken(): string
    {
        $this->share_token = Str::random(32);
        $this->save();
        return $this->share_token;
    }

    /**
     * Atualiza o timestamp de último salvamento
     */
    public function updateLastSaved(): void
    {
        $this->last_saved_at = now();
        $this->save();
    }

    /**
     * Verifica se o projeto pode ser acessado pelo usuário
     */
    public function canBeAccessedBy(?User $user): bool
    {
        if (!$user) {
            return $this->is_public;
        }

        return $this->user_id === $user->id || $this->is_public;
    }

    /**
     * Scope para projetos públicos
     */
    public function scopePublic($query)
    {
        return $query->where('is_public', true);
    }

    /**
     * Scope para projetos do usuário
     */
    public function scopeForUser($query, User $user)
    {
        return $query->where('user_id', $user->id);
    }
}

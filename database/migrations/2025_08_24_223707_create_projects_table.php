<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('projects', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('description')->nullable();
            $table->json('data')->nullable(); // Dados completos do projeto (cenas, configurações, etc.)
            $table->json('settings')->nullable(); // Configurações específicas do projeto
            $table->boolean('is_public')->default(false);
            $table->string('share_token')->nullable()->unique();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->timestamp('last_saved_at')->nullable();
            $table->timestamps();
            
            $table->index(['user_id', 'created_at']);
            $table->index('share_token');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('projects');
    }
};

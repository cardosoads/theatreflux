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
        Schema::create('scenes', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description')->nullable();
            $table->integer('order')->default(0);
            $table->json('data'); // Dados da cena (atores, objetos, configurações do palco, etc.)
            $table->json('stage_config')->nullable(); // Configurações específicas do palco
            $table->foreignId('project_id')->constrained()->onDelete('cascade');
            $table->timestamps();
            
            $table->index(['project_id', 'order']);
            $table->index('project_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('scenes');
    }
};

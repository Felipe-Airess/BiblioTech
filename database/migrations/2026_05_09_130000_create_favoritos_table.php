<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('favoritos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('membro_id')->constrained('membros')->cascadeOnDelete();
            $table->foreignId('livro_id')->constrained('livros')->cascadeOnDelete();
            $table->timestamps();

            $table->unique(['membro_id', 'livro_id']);
            $table->index(['membro_id', 'created_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('favoritos');
    }
};

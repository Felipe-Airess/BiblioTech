<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('comentarios', function (Blueprint $table) {
            $table->id();
            $table->foreignId('livro_id')->constrained('livros')->cascadeOnDelete();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('membro_id')->nullable()->constrained('membros')->nullOnDelete();
            $table->unsignedTinyInteger('nota');
            $table->text('comentario');
            $table->timestamps();

            $table->index('livro_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('comentarios');
    }
};

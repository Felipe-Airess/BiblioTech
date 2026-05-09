<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('reservas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('membro_id')->constrained('membros')->cascadeOnDelete();
            $table->foreignId('livro_id')->constrained('livros')->cascadeOnDelete();
            $table->string('status')->default('ativa');
            $table->timestamp('cancelada_em')->nullable();
            $table->timestamps();

            $table->index(['livro_id', 'status', 'created_at']);
            $table->index(['membro_id', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('reservas');
    }
};

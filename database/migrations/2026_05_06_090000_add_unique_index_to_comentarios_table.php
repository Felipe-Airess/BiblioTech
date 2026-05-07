<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('comentarios', function (Blueprint $table) {
            $table->unique(['livro_id', 'user_id'], 'comentarios_livro_user_unique');
            $table->unique(['livro_id', 'membro_id'], 'comentarios_livro_membro_unique');
        });
    }

    public function down(): void
    {
        Schema::table('comentarios', function (Blueprint $table) {
            $table->dropUnique('comentarios_livro_user_unique');
            $table->dropUnique('comentarios_livro_membro_unique');
        });
    }
};

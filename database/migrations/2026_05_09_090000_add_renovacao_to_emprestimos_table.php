<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('emprestimos', function (Blueprint $table) {
            $table->unsignedTinyInteger('renovacoes_count')->default(0)->after('valor_multa');
            $table->timestamp('ultima_renovacao_em')->nullable()->after('renovacoes_count');
        });
    }

    public function down(): void
    {
        Schema::table('emprestimos', function (Blueprint $table) {
            $table->dropColumn(['renovacoes_count', 'ultima_renovacao_em']);
        });
    }
};

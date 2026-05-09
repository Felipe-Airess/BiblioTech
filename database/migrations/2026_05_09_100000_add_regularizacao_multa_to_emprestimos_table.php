<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('emprestimos', function (Blueprint $table) {
            $table->timestamp('multa_paga_em')->nullable()->after('valor_multa');
            $table->foreignId('multa_regularizada_por')->nullable()->after('multa_paga_em')->constrained('users')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('emprestimos', function (Blueprint $table) {
            $table->dropConstrainedForeignId('multa_regularizada_por');
            $table->dropColumn('multa_paga_em');
        });
    }
};

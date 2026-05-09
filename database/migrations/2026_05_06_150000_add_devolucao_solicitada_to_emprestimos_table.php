<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (DB::getDriverName() !== 'sqlite') {
            DB::statement("ALTER TABLE emprestimos MODIFY status ENUM('solicitado','aprovado','retirado','em_uso','devolucao_solicitada','devolvido','encerrado','rejeitado') NOT NULL DEFAULT 'solicitado'");
        }

        Schema::table('emprestimos', function (Blueprint $table) {
            if (!Schema::hasColumn('emprestimos', 'return_requested_at')) {
                $table->dateTime('return_requested_at')->nullable()->after('rejected_at');
            }
        });
    }

    public function down(): void
    {
        Schema::table('emprestimos', function (Blueprint $table) {
            if (Schema::hasColumn('emprestimos', 'return_requested_at')) {
                $table->dropColumn('return_requested_at');
            }
        });

        if (DB::getDriverName() !== 'sqlite') {
            DB::statement("ALTER TABLE emprestimos MODIFY status ENUM('solicitado','aprovado','retirado','em_uso','devolvido','encerrado','rejeitado') NOT NULL DEFAULT 'solicitado'");
        }
    }
};

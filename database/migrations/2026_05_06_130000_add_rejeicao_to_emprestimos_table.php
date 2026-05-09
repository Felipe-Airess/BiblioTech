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
            DB::statement("ALTER TABLE emprestimos MODIFY status ENUM('solicitado','aprovado','retirado','em_uso','devolvido','encerrado','rejeitado') NOT NULL DEFAULT 'solicitado'");
        }

        Schema::table('emprestimos', function (Blueprint $table) {
            if (!Schema::hasColumn('emprestimos', 'rejected_reason')) {
                $table->text('rejected_reason')->nullable()->after('valor_multa');
            }

            if (!Schema::hasColumn('emprestimos', 'rejected_at')) {
                $table->dateTime('rejected_at')->nullable()->after('rejected_reason');
            }
        });
    }

    public function down(): void
    {
        Schema::table('emprestimos', function (Blueprint $table) {
            if (Schema::hasColumn('emprestimos', 'rejected_at')) {
                $table->dropColumn('rejected_at');
            }

            if (Schema::hasColumn('emprestimos', 'rejected_reason')) {
                $table->dropColumn('rejected_reason');
            }
        });

        if (DB::getDriverName() !== 'sqlite') {
            DB::statement("ALTER TABLE emprestimos MODIFY status ENUM('solicitado','aprovado','retirado','em_uso','devolvido','encerrado') NOT NULL DEFAULT 'solicitado'");
        }
    }
};

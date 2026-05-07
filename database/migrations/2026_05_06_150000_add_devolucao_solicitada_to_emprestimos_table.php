<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement("ALTER TABLE emprestimos MODIFY status ENUM('solicitado','aprovado','retirado','em_uso','devolucao_solicitada','devolvido','encerrado','rejeitado') NOT NULL DEFAULT 'solicitado'");
        DB::statement("ALTER TABLE emprestimos ADD COLUMN return_requested_at DATETIME NULL AFTER rejected_at");
    }

    public function down(): void
    {
        DB::statement("ALTER TABLE emprestimos DROP COLUMN return_requested_at");
        DB::statement("ALTER TABLE emprestimos MODIFY status ENUM('solicitado','aprovado','retirado','em_uso','devolvido','encerrado','rejeitado') NOT NULL DEFAULT 'solicitado'");
    }
};

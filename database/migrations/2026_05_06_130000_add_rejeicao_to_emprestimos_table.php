<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement("ALTER TABLE emprestimos MODIFY status ENUM('solicitado','aprovado','retirado','em_uso','devolvido','encerrado','rejeitado') NOT NULL DEFAULT 'solicitado'");
        DB::statement("ALTER TABLE emprestimos ADD COLUMN rejected_reason TEXT NULL AFTER valor_multa");
        DB::statement("ALTER TABLE emprestimos ADD COLUMN rejected_at DATETIME NULL AFTER rejected_reason");
    }

    public function down(): void
    {
        DB::statement("ALTER TABLE emprestimos DROP COLUMN rejected_at");
        DB::statement("ALTER TABLE emprestimos DROP COLUMN rejected_reason");
        DB::statement("ALTER TABLE emprestimos MODIFY status ENUM('solicitado','aprovado','retirado','em_uso','devolvido','encerrado') NOT NULL DEFAULT 'solicitado'");
    }
};

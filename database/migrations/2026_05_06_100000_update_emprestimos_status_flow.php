<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement("ALTER TABLE emprestimos MODIFY status ENUM('solicitado','aprovado','retirado','em_uso','devolvido','encerrado') NOT NULL DEFAULT 'solicitado'");
        DB::statement("ALTER TABLE emprestimos MODIFY data_emprestimo DATE NULL");
        DB::statement("ALTER TABLE emprestimos MODIFY data_devolucao_prevista DATE NULL");

        DB::statement("UPDATE emprestimos SET status = CASE WHEN data_devolucao_real IS NULL THEN 'em_uso' ELSE 'devolvido' END");
    }

    public function down(): void
    {
        DB::statement("UPDATE emprestimos SET data_emprestimo = COALESCE(data_emprestimo, CURDATE())");
        DB::statement("UPDATE emprestimos SET data_devolucao_prevista = COALESCE(data_devolucao_prevista, CURDATE())");
        DB::statement("ALTER TABLE emprestimos MODIFY status ENUM('ativo','devolvido','atrasado') NOT NULL DEFAULT 'ativo'");
        DB::statement("ALTER TABLE emprestimos MODIFY data_emprestimo DATE NOT NULL");
        DB::statement("ALTER TABLE emprestimos MODIFY data_devolucao_prevista DATE NOT NULL");
    }
};

<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Emprestimos;
use App\Notifications\EmprestimoVencendo;
use Carbon\Carbon;

class EnviarLembretesEmprestimo extends Command
{
    protected $signature   = 'emprestimos:lembrar';
    protected $description = 'Envia e-mail para membros com empréstimo vencendo amanhã';

    public function handle(): void
    {
        $amanha = Carbon::tomorrow()->toDateString();

        $emprestimos = Emprestimos::with(['membro', 'livro'])
            ->whereNull('data_devolucao_real')
            ->whereDate('data_devolucao_prevista', $amanha)
            ->get();

        foreach ($emprestimos as $emprestimo) {
            if ($emprestimo->membro) {
                $emprestimo->membro->notify(new EmprestimoVencendo($emprestimo));
                $this->info("Lembrete enviado para: {$emprestimo->membro->email}");
            }
        }

        $this->info("Total: {$emprestimos->count()} lembretes enviados.");
    }
}
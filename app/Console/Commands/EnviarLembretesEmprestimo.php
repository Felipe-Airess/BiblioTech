<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Emprestimos;
use App\Notifications\EmprestimoVencendo;
use Carbon\Carbon;

class EnviarLembretesEmprestimo extends Command
{
    protected $signature   = 'emprestimos:lembrar';
    protected $description = 'Envia e-mail para membros com empréstimo vencendo em 2 dias';

    public function handle(): void
    {
        $dataLembrete = Carbon::today()
            ->addDays(Emprestimos::DIAS_ANTECEDENCIA_LEMBRETE)
            ->toDateString();

        $emprestimos = Emprestimos::with(['membro', 'livro'])
            ->whereNull('data_devolucao_real')
            ->whereIn('status', Emprestimos::STATUS_EM_ANDAMENTO)
            ->whereDate('data_devolucao_prevista', $dataLembrete)
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

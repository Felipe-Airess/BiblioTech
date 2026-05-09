<?php

namespace App\Http\Controllers;

use App\Models\Emprestimos;
use App\Models\Reserva;
use Barryvdh\DomPDF\Facade\Pdf;

class CarteirinhaController extends Controller
{
    public function show()
    {
        $membro = auth()->guard('membro')->user();
        $dados = $this->dadosCarteirinha($membro->id);

        return view('membros.carteirinha', array_merge(['membro' => $membro], $dados));
    }

    public function pdf()
    {
        $membro = auth()->guard('membro')->user();
        $dados = $this->dadosCarteirinha($membro->id);

        $pdf = Pdf::loadView('membros.carteirinha-pdf', array_merge(['membro' => $membro], $dados))
            ->setPaper('a4', 'portrait');

        return $pdf->stream("carteirinha-{$membro->numero_carteirinha}.pdf");
    }

    private function dadosCarteirinha(int $membroId): array
    {
        $emprestimos = Emprestimos::where('membro_id', $membroId)->get();

        return [
            'ativos' => $emprestimos->whereIn('status', Emprestimos::STATUS_EM_ANDAMENTO)->count(),
            'atrasados' => $emprestimos->filter(fn (Emprestimos $emprestimo) => $emprestimo->isAtrasado())->count(),
            'multasPendentes' => $emprestimos->filter(fn (Emprestimos $emprestimo) => $emprestimo->multaPendente())->sum('valor_multa'),
            'reservasAtivas' => Reserva::ativas()->where('membro_id', $membroId)->count(),
        ];
    }
}

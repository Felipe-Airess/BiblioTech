<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Emprestimos; // Usando o seu Model no plural!
use App\Notifications\EmprestimoRejeitado;
use App\Notifications\EmprestimoAprovado;
use App\Notifications\EmprestimoRetirado;
use Carbon\Carbon;

class EmprestimoAdminController extends Controller
{
    /**
     * Lista todos os empréstimos para o bibliotecário administrar
     */
    public function index()
    {
        // Traz os empréstimos e já carrega os relacionamentos (livro, e o user dentro de membro)
        // Ordena para que os livros NÃO devolvidos apareçam no topo da lista
        $emprestimos = Emprestimos::with(['livro', 'membro.user'])
            ->orderByRaw("FIELD(status, 'solicitado','aprovado','retirado','em_uso','devolucao_solicitada','devolvido','encerrado','rejeitado')")
            ->orderBy('data_devolucao_prevista', 'asc')
            ->get();

        return view('admin.emprestimos.index', compact('emprestimos'));
    }

    /**
     * Dá baixa no livro (Membro devolveu no balcão) e calcula multa (RN003)
     */
    public function devolver($id)
    {
        // 1. Acha o empréstimo no banco
        $emprestimo = Emprestimos::findOrFail($id);

        // Se a data_devolucao_real já estiver preenchida, o livro já foi devolvido
        if (in_array($emprestimo->status, [Emprestimos::STATUS_DEVOLVIDO, Emprestimos::STATUS_ENCERRADO], true)) {
            return redirect()->back()->with('erro', 'Este livro já consta como devolvido no sistema!');
        }

        if ($emprestimo->status !== Emprestimos::STATUS_DEVOLUCAO_SOLICITADA) {
            return redirect()->back()->with('erro', 'A devolução precisa ser solicitada pelo membro.');
        }

        $hoje = Carbon::today();
        $valorMulta = 0;

        // 2. Lógica da Multa (RN003)
        // Verifica se a data de hoje passou da data prevista de devolução
        if ($hoje->greaterThan($emprestimo->data_devolucao_prevista)) {
            // Calcula quantos dias de atraso
            $diasAtraso = $hoje->diffInDays($emprestimo->data_devolucao_prevista);
            
            // Exemplo: Multa de R$ 2,00 por dia de atraso. 
            // Pode alterar esse 2.00 para o valor que você definiu nas suas regras!
            $valorMulta = $diasAtraso * 2.00; 
        }

        // 3. Atualiza o registro preenchendo a data real e a multa
        $emprestimo->update([
            'data_devolucao_real' => $hoje,
            'valor_multa'         => $valorMulta,
            'status'              => Emprestimos::STATUS_DEVOLVIDO,
        ]);

        // 4. Devolve o livro para a prateleira (Aumenta a quantidade do estoque em +1)
        $emprestimo->livro->increment('quantidade');

        // 5. Prepara a mensagem de sucesso (Avisa se teve multa)
        $mensagem = 'Livro devolvido com sucesso!';
        if ($valorMulta > 0) {
            $mensagem .= " Atenção: O membro atrasou $diasAtraso dia(s) e gerou uma multa de R$ " . number_format($valorMulta, 2, ',', '.') . ".";
        }

        return redirect()->back()->with('sucesso', $mensagem);
    }

    public function aprovar($id)
    {
        $emprestimo = Emprestimos::with('livro')->findOrFail($id);

        if ($emprestimo->status !== Emprestimos::STATUS_SOLICITADO) {
            return redirect()->back()->with('erro', 'Somente solicitações podem ser aprovadas.');
        }

        if ($emprestimo->livro && $emprestimo->livro->quantidade <= 0) {
            return redirect()->back()->with('erro', 'Livro sem estoque para aprovar esta solicitação.');
        }

        $emprestimo->update([
            'status' => Emprestimos::STATUS_APROVADO,
            'approved_by' => auth()->id(),
            'approved_at' => Carbon::now(),
        ]);

        if ($emprestimo->livro) {
            $emprestimo->livro->decrement('quantidade');
        }

        // Notifica o membro sobre aprovação
        if ($emprestimo->membro) {
            $emprestimo->membro->notify(new EmprestimoAprovado($emprestimo));
        }

        return redirect()->back()->with('sucesso', 'Solicitação aprovada com sucesso.');
    }

    public function retirar(Request $request, $id)
    {
        $emprestimo = Emprestimos::findOrFail($id);

        if ($emprestimo->status !== Emprestimos::STATUS_APROVADO) {
            return redirect()->back()->with('erro', 'Somente empréstimos aprovados podem ser retirados.');
        }

        $prazoDias = (int) $request->input('prazo_dias', 7);
        if ($prazoDias < 1 || $prazoDias > 60) {
            return redirect()->back()->with('erro', 'Prazo inválido.');
        }

        $hoje = Carbon::today();
        $emprestimo->update([
            'status' => Emprestimos::STATUS_RETIRADO,
            'data_emprestimo' => $hoje,
            'data_devolucao_prevista' => $hoje->copy()->addDays($prazoDias),
        ]);

        // Notifica o membro que o empréstimo foi retirado
        if ($emprestimo->membro) {
            $emprestimo->membro->notify(new EmprestimoRetirado($emprestimo));
        }

        return redirect()->back()->with('sucesso', 'Retirada confirmada.');
    }

    public function iniciarUso($id)
    {
        $emprestimo = Emprestimos::findOrFail($id);

        if ($emprestimo->status !== Emprestimos::STATUS_RETIRADO) {
            return redirect()->back()->with('erro', 'Somente empréstimos retirados podem entrar em uso.');
        }

        $emprestimo->update([
            'status' => Emprestimos::STATUS_EM_USO,
        ]);

        return redirect()->back()->with('sucesso', 'Empréstimo marcado como em uso.');
    }

    public function encerrar($id)
    {
        $emprestimo = Emprestimos::findOrFail($id);

        if ($emprestimo->status !== Emprestimos::STATUS_DEVOLVIDO) {
            return redirect()->back()->with('erro', 'Somente empréstimos devolvidos podem ser encerrados.');
        }

        $emprestimo->update([
            'status' => Emprestimos::STATUS_ENCERRADO,
        ]);

        return redirect()->back()->with('sucesso', 'Empréstimo encerrado.');
    }

    public function rejeitar(Request $request, $id)
    {
        $emprestimo = Emprestimos::findOrFail($id);

        if ($emprestimo->status !== Emprestimos::STATUS_SOLICITADO) {
            return redirect()->back()->with('erro', 'Somente solicitações podem ser rejeitadas.');
        }

        $request->validate([
            'motivo' => 'nullable|string|max:500',
        ]);

        $emprestimo->update([
            'status' => Emprestimos::STATUS_REJEITADO,
            'rejected_reason' => $request->input('motivo'),
            'rejected_at' => Carbon::now(),
            'rejected_by' => auth()->id(),
        ]);

        if ($emprestimo->membro) {
            $emprestimo->membro->notify(new EmprestimoRejeitado($emprestimo));
        }

        return redirect()->back()->with('sucesso', 'Solicitação rejeitada.');
    }
}
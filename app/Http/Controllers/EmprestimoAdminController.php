<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Emprestimos; // Usando o seu Model no plural!
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
            ->orderByRaw('data_devolucao_real IS NULL DESC')
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
        if ($emprestimo->data_devolucao_real) {
            return redirect()->back()->with('erro', 'Este livro já consta como devolvido no sistema!');
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
            'valor_multa'         => $valorMulta
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
}
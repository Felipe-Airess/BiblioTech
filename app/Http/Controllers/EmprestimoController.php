<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Livros;
use App\Models\Emprestimos;
use App\Models\Membros; // Precisamos importar o model do Membro!
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;

class EmprestimoController extends Controller
{

    public function comprovante($id)
    {
        $membro = auth()->guard('membro')->user();

        // Garante que o membro só acessa o próprio comprovante
        $emprestimo = Emprestimos::with(['livro.autor', 'membro'])
            ->where('id', $id)
            ->where('membro_id', $membro->id)
            ->firstOrFail();

        $pdf = Pdf::loadView('membros.comprovante-pdf', compact('emprestimo'))
            ->setPaper('a4', 'portrait');

        return $pdf->stream("comprovante-{$emprestimo->id}.pdf");
    }
    public function alugar($id)
    {
        // 1. Acha o livro no banco
        $livro = Livros::findOrFail($id);

        // 2. Verifica se o membro está autenticado (usando o guard 'membro')
        if (!auth()->guard('membro')->check()) {
            return redirect()->route('login')->with('erro', 'Você precisa estar logado como membro para alugar livros.');
        }

        // 3. Pega o membro autenticado (auth()->user() aqui retorna um Membros, não um User)
        $membro = auth()->guard('membro')->user();

        // 3.1. Checa se já existe empréstimo ativo deste livro para o membro
        $jaTem = Emprestimos::where('membro_id', $membro->id)
            ->where('livro_id', $livro->id)
            ->whereNull('data_devolucao_real')
            ->exists();
        if ($jaTem) {
            return redirect()->back()->with('erro', 'Você já está com este livro emprestado. Devolva antes de pegar novamente.');
        }

        // 3.2. Limite de empréstimos ativos
        $ativos = Emprestimos::where('membro_id', $membro->id)
            ->whereNull('data_devolucao_real')
            ->count();
        if ($ativos >= 3) {
            return redirect()->back()->with('erro', 'Você atingiu o limite de 3 empréstimos ativos. Devolva algum livro para pegar outro.');
        }

        // 3.3. Bloqueio por pendências (empréstimos vencidos ou multas)
        $temVencido = Emprestimos::where('membro_id', $membro->id)
            ->whereNull('data_devolucao_real')
            ->where('data_devolucao_prevista', '<', Carbon::today())
            ->exists();
        if ($temVencido) {
            return redirect()->back()->with('erro', 'Você possui empréstimos vencidos. Regularize sua situação para pegar novos livros.');
        }
        // Aqui você pode adicionar lógica para checar multas, se houver campo/flag para isso

        // 4. Verifica o estoque
        if ($livro->quantidade <= 0) {
            return redirect()->back()->with('erro', 'Putz! Esse livro está fora de estoque no momento.');
        }

        // 5. Cria o registro usando os nomes exatos da sua migration
        Emprestimos::create([
            'membro_id' => $membro->id,
            'livro_id' => $livro->id,
            'data_emprestimo' => Carbon::today(), // Pega a data de hoje
            'data_devolucao_prevista' => Carbon::today()->addDays(7), // Regra dos 7 dias
            'data_devolucao_real' => null, // Fica nulo até ele devolver
            'valor_multa' => 0, // Começa sem multa
        ]);

        // 6. Diminui o estoque do livro (O Laravel tem essa função pronta que é maravilhosa!)
        $livro->decrement('quantidade');

        return redirect()->back()->with('sucesso', 'Livro alugado com sucesso! A devolução está prevista para daqui a 7 dias.');
    }
    public function historico()
    {
        $membro = auth()->guard('membro')->user();

        $emprestimos = Emprestimos::with('livro.autor')
            ->where('membro_id', $membro->id)
            ->orderByRaw('data_devolucao_real IS NULL DESC') // ativos primeiro
            ->orderBy('data_devolucao_prevista', 'asc')
            ->get();

        return view('membros.historico', compact('emprestimos'));
    }
}

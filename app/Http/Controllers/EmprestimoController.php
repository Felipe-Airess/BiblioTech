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

class EmprestimoController extends Controller
{
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

        // 4. Verifica o estoque
        if ($livro->quantidade <= 0) {
            return redirect()->back()->with('erro', 'Putz! Esse livro está fora de estoque no momento.');
        }

        // 5. Cria o registro usando os nomes exatos da sua migration
        Emprestimos::create([
            'membro_id'               => $membro->id,
            'livro_id'                => $livro->id,
            'data_emprestimo'         => Carbon::today(), // Pega a data de hoje
            'data_devolucao_prevista' => Carbon::today()->addDays(7), // Regra dos 7 dias
            'data_devolucao_real'     => null, // Fica nulo até ele devolver
            'valor_multa'             => 0, // Começa sem multa
        ]);

        // 6. Diminui o estoque do livro (O Laravel tem essa função pronta que é maravilhosa!)
        $livro->decrement('quantidade');

        return redirect()->back()->with('sucesso', 'Livro alugado com sucesso! A devolução está prevista para daqui a 7 dias.');
    }
}

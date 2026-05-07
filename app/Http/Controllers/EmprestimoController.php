<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Livros;
use App\Models\Emprestimos;
use App\Models\Membros; // Precisamos importar o model do Membro!
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Notifications\DevolucaoSolicitada;
use App\Notifications\EmprestimoSolicitado;
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
            ->whereIn('status', Emprestimos::STATUS_ATIVOS)
            ->exists();
        if ($jaTem) {
            return redirect()->back()->with('erro', 'Você já tem uma solicitação ou empréstimo ativo deste livro.');
        }

        // 3.2. Limite de empréstimos ativos
        $ativos = Emprestimos::where('membro_id', $membro->id)
            ->whereIn('status', Emprestimos::STATUS_ATIVOS)
            ->count();
        if ($ativos >= 3) {
            return redirect()->back()->with('erro', 'Você atingiu o limite de 3 empréstimos ativos. Devolva algum livro para pegar outro.');
        }

        // 3.3. Bloqueio por pendências (empréstimos vencidos ou multas)
        $temVencido = Emprestimos::where('membro_id', $membro->id)
            ->whereIn('status', Emprestimos::STATUS_EM_ANDAMENTO)
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
        $emprestimo = Emprestimos::create([
            'membro_id' => $membro->id,
            'livro_id' => $livro->id,
            'status' => Emprestimos::STATUS_SOLICITADO,
            'data_emprestimo' => null,
            'data_devolucao_prevista' => null,
            'data_devolucao_real' => null,
            'valor_multa' => 0, // Começa sem multa
        ]);

        $emprestimo->load('livro');

        // Notifica os administradores (gerente e bibliotecario) sobre o novo pedido
        User::whereIn('tipo_usuario', ['gerente', 'bibliotecario'])
            ->get()
            ->each(function (User $admin) use ($emprestimo) {
                $admin->notify(new EmprestimoSolicitado($emprestimo));
            });

        return redirect()->back()->with('sucesso', 'Solicitação enviada! Aguarde a aprovação do bibliotecário.');
    }
    public function historico()
    {
        $membro = auth()->guard('membro')->user();

        $emprestimos = Emprestimos::with('livro.autor')
            ->where('membro_id', $membro->id)
            ->orderByRaw("FIELD(status, 'solicitado','aprovado','retirado','em_uso','devolucao_solicitada','devolvido','encerrado','rejeitado')")
            ->orderBy('data_devolucao_prevista', 'asc')
            ->get();

        return view('membros.historico', compact('emprestimos'));
    }

    public function solicitarDevolucao($id)
    {
        $membro = auth()->guard('membro')->user();

        $emprestimo = Emprestimos::where('id', $id)
            ->where('membro_id', $membro->id)
            ->firstOrFail();

        if (!in_array($emprestimo->status, [Emprestimos::STATUS_RETIRADO, Emprestimos::STATUS_EM_USO], true)) {
            return redirect()->back()->with('erro', 'Este empréstimo não pode solicitar devolução agora.');
        }

        $emprestimo->update([
            'status' => Emprestimos::STATUS_DEVOLUCAO_SOLICITADA,
            'return_requested_at' => Carbon::now(),
        ]);

        // Notifica os administradores (gerente e bibliotecario) sobre a solicitação
        User::whereIn('tipo_usuario', ['gerente', 'bibliotecario'])
            ->get()
            ->each(function (User $admin) use ($emprestimo) {
                $admin->notify(new DevolucaoSolicitada($emprestimo));
            });

        return redirect()->back()->with('sucesso', 'Solicitação de devolução enviada.');
    }
}

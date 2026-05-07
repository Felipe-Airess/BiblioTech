<?php

namespace App\Http\Controllers;

use App\Models\Membros;
use App\Models\Emprestimos;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;

class PerfilMembrosController extends Controller
{
    public function index()
    {
        // Apenas administradores (gerente e bibliotecário) podem acessar
        $user = auth()->user();
        if (!$user || !in_array($user->tipo_usuario, ['gerente', 'bibliotecario'])) {
            abort(403, 'Não autorizado');
        }

        // Buscar todos os membros com seus empréstimos
        $membros = Membros::with('comentarios')
            ->get()
            ->map(function ($membro) {
                // Empréstimos atrasados (sem devolução e data_devolucao_prevista vencida)
                $atrasados = Emprestimos::where('membro_id', $membro->id)
                    ->whereNull('data_devolucao_real')
                    ->where('data_devolucao_prevista', '<', now()->startOfDay())
                    ->whereIn('status', ['retirado', 'em_uso', 'devolucao_solicitada'])
                    ->get();

                // Total de multas não pagas (valor_multa > 0 e não devolvido)
                $multasNaoPagas = Emprestimos::where('membro_id', $membro->id)
                    ->where('valor_multa', '>', 0)
                    ->whereNull('data_devolucao_real')
                    ->sum('valor_multa');

                // Total de empréstimos já completados com sucesso
                $emprestimosCompletados = Emprestimos::where('membro_id', $membro->id)
                    ->where('status', 'encerrado')
                    ->count();

                // Total de empréstimos ativos
                $emprestimosAtivos = Emprestimos::where('membro_id', $membro->id)
                    ->whereIn('status', ['retirado', 'em_uso', 'devolucao_solicitada'])
                    ->count();

                // Definir status do perfil
                $perfil = 'bom';
                if ($multasNaoPagas > 0) {
                    $perfil = 'com_multa';
                } elseif (count($atrasados) > 0) {
                    $perfil = 'devendo';
                }

                return [
                    'membro' => $membro,
                    'emprestimosAtrasados' => $atrasados,
                    'multasNaoPagas' => $multasNaoPagas,
                    'emprestimosCompletados' => $emprestimosCompletados,
                    'emprestimosAtivos' => $emprestimosAtivos,
                    'perfil' => $perfil,
                ];
            });

        // Agrupar por perfil para exibição
        $membrosDevendo = $membros->filter(fn($m) => $m['perfil'] === 'devendo');
        $membrosComMulta = $membros->filter(fn($m) => $m['perfil'] === 'com_multa');
        $membrosBom = $membros->filter(fn($m) => $m['perfil'] === 'bom');

        $allMembros = Membros::select('id', 'nome', 'email', 'numero_carteirinha', 'created_at')->get();

        return view('admin.perfil-membros-dashboard', [
            'membrosDevendo' => $membrosDevendo,
            'membrosComMulta' => $membrosComMulta,
            'membrosBom' => $membrosBom,
            'totalMembros' => $membros->count(),
            'allMembros' => $allMembros,
        ]);
    }

    public function show(Membros $membro)
    {
        // Apenas administradores
        $user = auth()->user();
        if (!$user || !in_array($user->tipo_usuario, ['gerente', 'bibliotecario'])) {
            abort(403, 'Não autorizado');
        }

        // Detalhes do membro
        $emprestimos = Emprestimos::where('membro_id', $membro->id)
            ->with('livro')
            ->orderBy('created_at', 'desc')
            ->get();

        $atrasados = $emprestimos->filter(fn($e) => $e->isAtrasado());
        $multasTotal = $emprestimos->sum('valor_multa');

        return view('admin.membro-detalhes-dashboard', [
            'membro' => $membro,
            'emprestimos' => $emprestimos,
            'atrasados' => $atrasados,
            'multasTotal' => $multasTotal,
        ]);
    }

    public function sendMessage(Request $request, Membros $membro)
    {
        $user = auth()->user();
        if (!$user || !in_array($user->tipo_usuario, ['gerente', 'bibliotecario'])) {
            return response()->json(['error' => 'Não autorizado'], 403);
        }

        $data = $request->validate([
            'subject' => 'required|string|max:191',
            'message' => 'required|string|max:2000',
        ]);

        // Enviar notificação de banco de dados
        try {
            $membro->notify(new \App\Notifications\MessageToMember($data['subject'], $data['message'], $user));
        } catch (\Exception $e) {
            return response()->json(['error' => 'Falha ao enviar mensagem'], 500);
        }

        return response()->json(['ok' => true]);
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\Membros;
use App\Models\Emprestimos;
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

        $membros = Membros::with(['comentarios', 'emprestimos'])
            ->get()
            ->map(function ($membro) {
                $emprestimos = $membro->emprestimos;
                $atrasados = $emprestimos->filter(fn($emprestimo) => $emprestimo->isAtrasado());
                $multasNaoPagas = $emprestimos
                    ->where('status', Emprestimos::STATUS_DEVOLVIDO)
                    ->where('valor_multa', '>', 0)
                    ->whereNull('multa_paga_em')
                    ->sum('valor_multa');
                $emprestimosCompletados = $emprestimos
                    ->where('status', Emprestimos::STATUS_ENCERRADO)
                    ->count();
                $emprestimosAtivos = $emprestimos
                    ->whereIn('status', Emprestimos::STATUS_EM_ANDAMENTO)
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
                    'totalEmprestimos' => $emprestimos->count(),
                    'ultimoEmprestimo' => $emprestimos->sortByDesc('created_at')->first(),
                    'perfil' => $perfil,
                ];
            });

        // Agrupar por perfil para exibição
        $membrosDevendo = $membros->filter(fn($m) => $m['perfil'] === 'devendo');
        $membrosComMulta = $membros->filter(fn($m) => $m['perfil'] === 'com_multa');
        $membrosBom = $membros->filter(fn($m) => $m['perfil'] === 'bom');

        $allMembros = $membros->sortBy(fn($item) => $item['membro']->nome)->values();

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
            ->with('livro.autor')
            ->orderBy('created_at', 'desc')
            ->get();

        $atrasados = $emprestimos->filter(fn($e) => $e->isAtrasado());
        $multasTotal = $emprestimos
            ->filter(fn($emprestimo) => $emprestimo->multaPendente())
            ->sum('valor_multa');

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

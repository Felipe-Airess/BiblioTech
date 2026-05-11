<?php

namespace App\Http\Controllers;

use App\Models\AuditLog;
use App\Models\User;
use App\Rules\RealisticDate;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;

class AuditLogController extends Controller
{
    public function index(Request $request)
    {
        return view('admin.auditoria.index', $this->dadosAuditoria($request, true));
    }

    public function exportarPdf(Request $request)
    {
        $dados = $this->dadosAuditoria($request, false);
        $nomeArquivo = 'auditoria-bibliotech-' . now()->format('Y-m-d-His') . '.pdf';

        return Pdf::loadView('admin.auditoria.pdf', $dados)
            ->setPaper('a4', 'landscape')
            ->download($nomeArquivo);
    }

    public function exportarCsv(Request $request)
    {
        $dados = $this->dadosAuditoria($request, false);
        $nomeArquivo = 'auditoria-bibliotech-' . now()->format('Y-m-d-His') . '.csv';

        return response()->streamDownload(function () use ($dados) {
            $handle = fopen('php://output', 'w');
            fputcsv($handle, ['Data', 'Usuário', 'E-mail', 'Ação', 'Entidade', 'Descrição', 'IP']);

            foreach ($dados['logs'] as $log) {
                fputcsv($handle, [
                    $log->created_at?->format('d/m/Y H:i'),
                    $log->user?->name ?? 'Sistema',
                    $log->user?->email,
                    $log->action,
                    $log->auditable_type ? class_basename($log->auditable_type) . ' #' . $log->auditable_id : 'Sistema',
                    $log->description,
                    $log->ip_address,
                ]);
            }

            fclose($handle);
        }, $nomeArquivo, ['Content-Type' => 'text/csv; charset=UTF-8']);
    }

    private function dadosAuditoria(Request $request, bool $paginated): array
    {
        $request->validate([
            'inicio' => ['nullable', 'date_format:Y-m-d', new RealisticDate('period')],
            'fim' => ['nullable', 'date_format:Y-m-d', 'after_or_equal:inicio', new RealisticDate('period')],
        ], [
            'fim.after_or_equal' => 'A data final precisa ser igual ou posterior à data inicial.',
        ]);

        $hasAuditTable = Schema::hasTable('audit_logs');
        $actions = collect();
        $users = User::whereIn('tipo_usuario', ['gerente', 'bibliotecario'])
            ->orderBy('name')
            ->get(['id', 'name', 'email', 'tipo_usuario']);

        if (!$hasAuditTable) {
            $logs = collect();
            $metricas = [
                'total' => 0,
                'hoje' => 0,
                'usuarios' => 0,
                'acao_top' => 'Sem dados',
            ];

            return compact('logs', 'metricas', 'users', 'actions', 'hasAuditTable');
        }

        $actions = AuditLog::query()
            ->select('action')
            ->distinct()
            ->orderBy('action')
            ->pluck('action');

        $query = AuditLog::with('user')
            ->latest()
            ->when($request->filled('user_id'), fn ($q) => $q->where('user_id', $request->integer('user_id')))
            ->when($request->filled('action'), fn ($q) => $q->where('action', $request->input('action')))
            ->when($request->filled('entidade'), fn ($q) => $q->where('auditable_type', 'like', '%' . $request->input('entidade') . '%'))
            ->when($request->filled('inicio'), fn ($q) => $q->whereDate('created_at', '>=', $request->date('inicio')))
            ->when($request->filled('fim'), fn ($q) => $q->whereDate('created_at', '<=', $request->date('fim')));

        $logs = $paginated
            ? $query->paginate(20)->withQueryString()
            : $query->limit(1000)->get();

        $topAction = AuditLog::query()
            ->selectRaw('action, COUNT(*) as total')
            ->groupBy('action')
            ->orderByDesc('total')
            ->first();

        $metricas = [
            'total' => AuditLog::count(),
            'hoje' => AuditLog::whereDate('created_at', today())->count(),
            'usuarios' => AuditLog::whereNotNull('user_id')->distinct('user_id')->count('user_id'),
            'acao_top' => $topAction?->action ? str_replace('_', ' ', $topAction->action) : 'Sem dados',
        ];

        return compact('logs', 'metricas', 'users', 'actions', 'hasAuditTable');
    }
}

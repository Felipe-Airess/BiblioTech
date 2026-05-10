<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Auditoria BiblioTech</title>
    <style>
        @page { margin: 24px; }
        body { font-family: DejaVu Sans, sans-serif; color: #1f2937; font-size: 10px; line-height: 1.35; }
        h1, p { margin: 0; }
        .header { border-bottom: 3px solid #1E3A8A; padding-bottom: 12px; margin-bottom: 16px; }
        .brand { color: #1E3A8A; font-size: 22px; font-weight: bold; }
        .brand span { color: #F59E0B; }
        .subtitle { color: #64748b; margin-top: 3px; }
        .grid { width: 100%; border-collapse: collapse; margin-bottom: 14px; }
        .metric { width: 25%; border: 1px solid #cbd5e1; padding: 10px; background: #f8fafc; vertical-align: top; }
        .metric-label { color: #64748b; text-transform: uppercase; font-size: 9px; letter-spacing: .04em; }
        .metric-value { color: #0f172a; font-size: 15px; font-weight: bold; margin-top: 5px; }
        table.report { width: 100%; border-collapse: collapse; }
        table.report th { background: #dbeafe; color: #334155; text-align: left; font-size: 8px; text-transform: uppercase; padding: 6px; border: 1px solid #bfdbfe; }
        table.report td { padding: 6px; border: 1px solid #e5e7eb; vertical-align: top; }
        table.report tr:nth-child(even) td { background: #f8fafc; }
        .muted { color: #64748b; }
    </style>
</head>
<body>
    <div class="header">
        <div class="brand">BIBLIO<span>TECH</span></div>
        <h1>Relatório de Auditoria</h1>
        <p class="subtitle">Emitido em {{ now()->format('d/m/Y H:i') }}</p>
    </div>

    <table class="grid">
        <tr>
            <td class="metric"><div class="metric-label">Total</div><div class="metric-value">{{ $metricas['total'] }}</div></td>
            <td class="metric"><div class="metric-label">Hoje</div><div class="metric-value">{{ $metricas['hoje'] }}</div></td>
            <td class="metric"><div class="metric-label">Usuários</div><div class="metric-value">{{ $metricas['usuarios'] }}</div></td>
            <td class="metric"><div class="metric-label">Ação frequente</div><div class="metric-value">{{ $metricas['acao_top'] }}</div></td>
        </tr>
    </table>

    <table class="report">
        <thead>
            <tr>
                <th>Data</th>
                <th>Usuário</th>
                <th>Ação</th>
                <th>Entidade</th>
                <th>Descrição</th>
                <th>IP</th>
            </tr>
        </thead>
        <tbody>
            @forelse($logs as $log)
                <tr>
                    <td>{{ $log->created_at?->format('d/m/Y H:i') }}</td>
                    <td>{{ $log->user?->name ?? 'Sistema' }}<br><span class="muted">{{ $log->user?->email }}</span></td>
                    <td>{{ str_replace('_', ' ', $log->action) }}</td>
                    <td>{{ $log->auditable_type ? class_basename($log->auditable_type) . ' #' . $log->auditable_id : 'Sistema' }}</td>
                    <td>{{ $log->description }}</td>
                    <td>{{ $log->ip_address }}</td>
                </tr>
            @empty
                <tr><td colspan="6" class="muted">Nenhum log encontrado.</td></tr>
            @endforelse
        </tbody>
    </table>
</body>
</html>

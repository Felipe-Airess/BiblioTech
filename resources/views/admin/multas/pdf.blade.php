<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Multas BiblioTech</title>
    <style>
        @page { margin: 24px; }
        body { font-family: DejaVu Sans, sans-serif; color: #1f2937; font-size: 11px; line-height: 1.35; }
        h1, p { margin: 0; }
        .header { border-bottom: 3px solid #1E3A8A; padding-bottom: 12px; margin-bottom: 16px; }
        .brand { color: #1E3A8A; font-size: 22px; font-weight: bold; }
        .brand span { color: #F59E0B; }
        .subtitle { color: #64748b; margin-top: 3px; }
        .grid { width: 100%; border-collapse: collapse; margin-bottom: 14px; }
        .metric { width: 25%; border: 1px solid #cbd5e1; padding: 10px; background: #f8fafc; vertical-align: top; }
        .metric-label { color: #64748b; text-transform: uppercase; font-size: 9px; letter-spacing: .04em; }
        .metric-value { color: #0f172a; font-size: 17px; font-weight: bold; margin-top: 5px; }
        table.report { width: 100%; border-collapse: collapse; }
        table.report th { background: #fee2e2; color: #7f1d1d; text-align: left; font-size: 9px; text-transform: uppercase; padding: 7px; border: 1px solid #fecaca; }
        table.report td { padding: 7px; border: 1px solid #e5e7eb; vertical-align: top; }
        table.report tr:nth-child(even) td { background: #f8fafc; }
        .red { color: #dc2626; font-weight: bold; }
        .green { color: #047857; font-weight: bold; }
        .muted { color: #64748b; }
    </style>
</head>
<body>
    <div class="header">
        <div class="brand">BIBLIO<span>TECH</span></div>
        <h1>Relatório de Multas</h1>
        <p class="subtitle">Emitido em {{ now()->format('d/m/Y H:i') }}</p>
    </div>

    <table class="grid">
        <tr>
            <td class="metric"><div class="metric-label">Pendente</div><div class="metric-value">R$ {{ number_format($metricas['total_pendente'], 2, ',', '.') }}</div></td>
            <td class="metric"><div class="metric-label">Arrecadado</div><div class="metric-value">R$ {{ number_format($metricas['total_arrecadado'], 2, ',', '.') }}</div></td>
            <td class="metric"><div class="metric-label">Inadimplentes</div><div class="metric-value">{{ $metricas['membros_inadimplentes'] }}</div></td>
            <td class="metric"><div class="metric-label">Maior multa</div><div class="metric-value">R$ {{ number_format($metricas['maior_multa'], 2, ',', '.') }}</div></td>
        </tr>
    </table>

    <table class="report">
        <thead>
            <tr>
                <th>Membro</th>
                <th>Livro</th>
                <th>Prazo</th>
                <th>Devolução</th>
                <th>Valor</th>
                <th>Situação</th>
            </tr>
        </thead>
        <tbody>
            @forelse($multas as $multa)
                <tr>
                    <td><strong>{{ $multa->membro?->nome ?? 'Membro removido' }}</strong><br><span class="muted">{{ $multa->membro?->email }}</span></td>
                    <td>{{ $multa->livro?->titulo ?? 'Livro removido' }}<br><span class="muted">{{ $multa->livro?->autor?->nome }}</span></td>
                    <td>{{ $multa->data_devolucao_prevista?->format('d/m/Y') ?? '--' }}</td>
                    <td>{{ $multa->data_devolucao_real?->format('d/m/Y') ?? '--' }}</td>
                    <td class="{{ $multa->multaPendente() ? 'red' : 'green' }}">R$ {{ number_format($multa->valor_multa, 2, ',', '.') }}</td>
                    <td>{{ $multa->multaPendente() ? 'Pendente' : 'Regularizada' }}</td>
                </tr>
            @empty
                <tr><td colspan="6" class="muted">Nenhuma multa encontrada.</td></tr>
            @endforelse
        </tbody>
    </table>
</body>
</html>

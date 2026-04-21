<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-3">
            <i class="ph ph-books text-[#F59E0B] text-2xl"></i>
            <h2 class="font-semibold text-xl text-white leading-tight tracking-tight">
                Painel de Empréstimos
            </h2>
        </div>
    </x-slot>

    {{-- DataTables CDN (sem CSS padrão — tema custom abaixo) --}}
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.8/js/jquery.dataTables.min.js"></script>

    <style>
        /* ═══════════════════════════════════
           DATATABLES — tema escuro custom
           ═══════════════════════════════════ */
        #tabelaEmprestimos_wrapper {
            color: #94a3b8;
            font-size: 0.8rem;
            font-family: 'Inter', sans-serif;
        }

        /* Controles: length + search */
        #tabelaEmprestimos_wrapper .dataTables_length,
        #tabelaEmprestimos_wrapper .dataTables_filter {
            display: flex;
            align-items: center;
        }
        #tabelaEmprestimos_wrapper .dataTables_length label,
        #tabelaEmprestimos_wrapper .dataTables_filter label {
            display: flex;
            align-items: center;
            gap: 6px;
            color: #64748b;
            font-size: 0.72rem;
        }
        #tabelaEmprestimos_wrapper .dataTables_length select,
        #tabelaEmprestimos_wrapper .dataTables_filter input {
            background: #0f172a;
            border: 1px solid #1e293b;
            color: #e2e8f0;
            border-radius: 6px;
            padding: 5px 10px;
            font-size: 0.72rem;
            outline: none;
            transition: border-color .15s;
        }
        #tabelaEmprestimos_wrapper .dataTables_filter input { min-width: 180px; }
        #tabelaEmprestimos_wrapper .dataTables_filter input:focus,
        #tabelaEmprestimos_wrapper .dataTables_length select:focus { border-color: #F59E0B; }

        /* Tabela */
        #tabelaEmprestimos_wrapper table.dataTable {
            width: 100% !important;
            border-collapse: collapse;
            margin: 0 !important;
        }
        #tabelaEmprestimos_wrapper table.dataTable thead th {
            background: transparent;
            color: #475569;
            font-size: 0.68rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: .07em;
            padding: 10px 12px;
            border-top: none;
            border-bottom: 1px solid #1e293b;
            white-space: nowrap;
            cursor: pointer;
            user-select: none;
            position: relative;
            padding-right: 20px;
        }
        /* Ícone de sort */
        #tabelaEmprestimos_wrapper table.dataTable thead th.sorting::after    { content: '↕'; position:absolute; right:5px; color:#334155; font-size:.6rem; top:50%; transform:translateY(-50%); }
        #tabelaEmprestimos_wrapper table.dataTable thead th.sorting_asc::after  { content: '↑'; position:absolute; right:5px; color:#F59E0B; font-size:.6rem; top:50%; transform:translateY(-50%); }
        #tabelaEmprestimos_wrapper table.dataTable thead th.sorting_desc::after { content: '↓'; position:absolute; right:5px; color:#F59E0B; font-size:.6rem; top:50%; transform:translateY(-50%); }
        #tabelaEmprestimos_wrapper table.dataTable thead th:before { display:none; }

        #tabelaEmprestimos_wrapper table.dataTable tbody td {
            padding: 11px 12px;
            border-bottom: 1px solid #1e293b;
            vertical-align: middle;
        }
        #tabelaEmprestimos_wrapper table.dataTable tbody tr:last-child td { border-bottom: none; }
        #tabelaEmprestimos_wrapper table.dataTable tbody tr:hover td { background: rgba(255,255,255,.025); }
        #tabelaEmprestimos_wrapper table.dataTable tbody tr:nth-child(even) td { background: rgba(255,255,255,.012); }
        #tabelaEmprestimos_wrapper table.dataTable.no-footer { border-bottom: none; }

        /* Rodapé: info + paginação */
        #tabelaEmprestimos_wrapper .dataTables_info {
            font-size: 0.7rem;
            color: #475569;
            padding: 0;
        }
        #tabelaEmprestimos_wrapper .dataTables_paginate { padding: 0; }
        #tabelaEmprestimos_wrapper .dataTables_paginate .paginate_button {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-width: 28px;
            height: 26px;
            padding: 0 7px;
            margin: 0 2px;
            border: 1px solid #1e293b !important;
            border-radius: 5px;
            font-size: 0.7rem;
            color: #64748b !important;
            cursor: pointer;
            transition: all .15s;
            text-decoration: none;
            background: transparent !important;
        }
        #tabelaEmprestimos_wrapper .dataTables_paginate .paginate_button:hover:not(.disabled) {
            background: #1e293b !important;
            border-color: #F59E0B !important;
            color: #F59E0B !important;
        }
        #tabelaEmprestimos_wrapper .dataTables_paginate .paginate_button.current,
        #tabelaEmprestimos_wrapper .dataTables_paginate .paginate_button.current:hover {
            background: #1E3A8A !important;
            border-color: #1E3A8A !important;
            color: #fff !important;
        }
        #tabelaEmprestimos_wrapper .dataTables_paginate .paginate_button.disabled { opacity: .3; cursor: not-allowed; }

        /* Card ativo */
        .card-filtro.ativo { outline: 2px solid; outline-offset: -2px; }
        .card-filtro.ativo.c-all  { outline-color: #3b82f6; }
        .card-filtro.ativo.c-ok   { outline-color: #10b981; }
        .card-filtro.ativo.c-late { outline-color: #ef4444; }
        .card-filtro.ativo.c-done { outline-color: #10b981; }
    </style>

    @php
        $ativos     = $emprestimos->where('data_devolucao_real', null)->count();
        $atrasados  = $emprestimos->where('data_devolucao_real', null)
                        ->filter(fn($e) => \Carbon\Carbon::today()->greaterThan($e->data_devolucao_prevista))
                        ->count();
        $concluidos = $emprestimos->whereNotNull('data_devolucao_real')->count();
        $multas     = $emprestimos->sum('valor_multa');
    @endphp

    <div class="max-w-7xl mx-auto space-y-5">

        {{-- ── Cards compactos clicáveis ── --}}
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-3">

            <button onclick="filtrarCard(this, 'todos', 'Todos os registros')"
                    class="card-filtro c-all ativo bg-[#111827] border border-[#1e293b] rounded-xl px-4 py-3
                           flex items-center gap-3 hover:border-blue-600/60 transition-all text-left w-full">
                <span class="w-8 h-8 rounded-lg bg-slate-800 flex items-center justify-center shrink-0">
                    <i class="ph ph-stack text-slate-400 text-base"></i>
                </span>
                <div>
                    <p class="text-[10px] font-bold uppercase tracking-widest text-slate-500">Total</p>
                    <p class="text-xl font-black text-white leading-tight font-serif">{{ $emprestimos->count() }}</p>
                </div>
            </button>

            <button onclick="filtrarCard(this, 'Ativo', 'Empréstimos ativos')"
                    class="card-filtro c-ok bg-[#111827] border border-[#1e293b] rounded-xl px-4 py-3
                           flex items-center gap-3 hover:border-blue-700/60 transition-all text-left w-full">
                <span class="w-8 h-8 rounded-lg bg-blue-900/30 flex items-center justify-center shrink-0">
                    <i class="ph ph-book-open text-blue-400 text-base"></i>
                </span>
                <div>
                    <p class="text-[10px] font-bold uppercase tracking-widest text-slate-500">Ativos</p>
                    <p class="text-xl font-black text-white leading-tight font-serif">{{ $ativos }}</p>
                </div>
            </button>

            <button onclick="filtrarCard(this, 'Atrasado', 'Empréstimos atrasados')"
                    class="card-filtro c-late bg-[#111827] border {{ $atrasados > 0 ? 'border-red-900/50' : 'border-[#1e293b]' }} rounded-xl px-4 py-3
                           flex items-center gap-3 hover:border-red-700/60 transition-all text-left w-full">
                <span class="w-8 h-8 rounded-lg bg-red-900/30 flex items-center justify-center shrink-0">
                    <i class="ph ph-warning-circle text-red-400 text-base"></i>
                </span>
                <div>
                    <p class="text-[10px] font-bold uppercase tracking-widest text-slate-500">Atrasados</p>
                    <p class="text-xl font-black {{ $atrasados > 0 ? 'text-red-400' : 'text-white' }} leading-tight font-serif">{{ $atrasados }}</p>
                </div>
            </button>

            <button onclick="filtrarCard(this, 'Concluído', 'Empréstimos concluídos')"
                    class="card-filtro c-done bg-[#111827] border border-[#1e293b] rounded-xl px-4 py-3
                           flex items-center gap-3 hover:border-emerald-700/60 transition-all text-left w-full">
                <span class="w-8 h-8 rounded-lg bg-emerald-900/30 flex items-center justify-center shrink-0">
                    <i class="ph ph-check-circle text-emerald-400 text-base"></i>
                </span>
                <div>
                    <p class="text-[10px] font-bold uppercase tracking-widest text-slate-500">Concluídos</p>
                    <p class="text-xl font-black text-white leading-tight font-serif">{{ $concluidos }}</p>
                </div>
            </button>

        </div>

        {{-- ── Painel da tabela ── --}}
        <div class="bg-[#111827] border border-[#1e293b] rounded-xl overflow-hidden">

            {{-- Header --}}
            <div class="flex items-center justify-between px-5 py-3 border-b border-[#1e293b]">
                <div class="flex items-center gap-2">
                    <i class="ph ph-funnel text-[#F59E0B] text-sm"></i>
                    <span id="tituloFiltro" class="text-xs font-semibold uppercase tracking-wider text-slate-400">
                        Todos os registros
                    </span>
                </div>
                @if($multas > 0)
                <span class="inline-flex items-center gap-1.5 text-xs font-semibold text-amber-400 bg-amber-900/20 border border-amber-800/40 rounded-lg px-3 py-1">
                    <i class="ph ph-coins"></i>
                    R$&nbsp;{{ number_format($multas, 2, ',', '.') }} em multas
                </span>
                @endif
            </div>

            <div class="p-5">
                <table id="tabelaEmprestimos" style="width:100%">
                    <thead>
                        <tr>
                            <th>Membro</th>
                            <th>Livro</th>
                            <th>Prazo</th>
                            <th>Status</th>
                            <th>Ação</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($emprestimos as $emprestimo)
                            @php
                                $atrasado  = !$emprestimo->data_devolucao_real
                                             && \Carbon\Carbon::today()->greaterThan($emprestimo->data_devolucao_prevista);
                                $concluido = (bool) $emprestimo->data_devolucao_real;
                                if ($concluido)    $statusLabel = 'Concluído';
                                elseif ($atrasado) $statusLabel = 'Atrasado';
                                else               $statusLabel = 'Ativo';
                            @endphp
                            <tr>
                                <td>
                                    @if($emprestimo->membro && $emprestimo->membro->name)
                                        <div class="flex items-center gap-2">
                                            <span class="w-7 h-7 rounded-full bg-[#1E3A8A] flex items-center justify-center text-white text-xs font-bold shrink-0">
                                                {{ strtoupper(substr($emprestimo->membro->name, 0, 1)) }}
                                            </span>
                                            <span class="text-slate-200 font-medium text-sm">{{ $emprestimo->membro->name }}</span>
                                        </div>
                                    @elseif($emprestimo->membro)
                                        <span class="text-red-400 text-xs">ID {{ $emprestimo->membro->user_id }} sem nome</span>
                                    @else
                                        <span class="text-orange-400 text-xs">Membro #{{ $emprestimo->membro_id }} não encontrado</span>
                                    @endif
                                </td>

                                <td>
                                    @if($emprestimo->livro)
                                        <span class="text-slate-400 italic text-sm">{{ $emprestimo->livro->titulo }}</span>
                                    @else
                                        <span class="text-orange-400 text-xs not-italic">Livro #{{ $emprestimo->livro_id }} não encontrado</span>
                                    @endif
                                </td>

                                <td class="text-center tabular-nums text-slate-400 text-xs">
                                    {{ $emprestimo->data_devolucao_prevista->format('d/m/Y') }}
                                </td>

                                <td class="text-center">
                                    @if($concluido)
                                        <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-semibold bg-emerald-900/40 text-emerald-400 border border-emerald-800/50">
                                            <i class="ph ph-check"></i> Concluído
                                        </span>
                                    @elseif($atrasado)
                                        <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-semibold bg-red-900/40 text-red-400 border border-red-800/50">
                                            <i class="ph ph-clock"></i> Atrasado
                                        </span>
                                    @else
                                        <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-semibold bg-blue-900/40 text-blue-400 border border-blue-800/50">
                                            <i class="ph ph-arrow-clockwise"></i> Ativo
                                        </span>
                                    @endif
                                </td>

                                <td class="text-right">
                                    @if(!$concluido)
                                        <form action="{{ route('admin.emprestimos.devolver', $emprestimo->id) }}" method="POST">
                                            @csrf
                                            <button type="button"
                                                    onclick="confirmarDevolucao(event, this.closest('form'))"
                                                    class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-semibold uppercase tracking-wider rounded-lg
                                                           bg-[#1E3A8A] text-white border border-blue-800/80 hover:bg-blue-700 hover:border-blue-500 transition-all">
                                                <i class="ph ph-arrow-u-up-left"></i> Receber
                                            </button>
                                        </form>
                                    @else
                                        <span class="text-xs text-emerald-500 font-semibold flex items-center justify-end gap-1">
                                            <i class="ph ph-check-circle"></i> Devolvido
                                        </span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center py-16">
                                    <i class="ph ph-tray text-slate-600 text-4xl block mb-2"></i>
                                    <p class="text-slate-500 text-sm">Nenhum empréstimo registrado.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script>
        let tabela;

        $(document).ready(function () {
            tabela = $('#tabelaEmprestimos').DataTable({
                language: {
                    search: "",
                    searchPlaceholder: "Buscar...",
                    lengthMenu:   "Mostrar _MENU_ linhas",
                    info:         "_START_–_END_ de _TOTAL_",
                    infoEmpty:    "0 registros",
                    infoFiltered: "(de _MAX_)",
                    paginate:     { next: "→", previous: "←" },
                    emptyTable:   "Nenhum registro encontrado",
                },
                columnDefs: [
                    { orderable: false, targets: [4] },
                    { className: 'dt-center', targets: [2, 3] },
                    { className: 'dt-right',  targets: [4] },
                ],
                order:      [[2, 'asc']],
                pageLength: 15,
                // DOM: controles em cima (flex), tabela, rodapé (flex)
                dom: '<"flex items-center justify-between mb-3"lf>t<"flex items-center justify-between mt-4 pt-3 border-t border-[#1e293b]"ip>',
            });
        });

        function filtrarCard(btn, status, titulo) {
            document.querySelectorAll('.card-filtro').forEach(c => c.classList.remove('ativo'));
            btn.classList.add('ativo');
            document.getElementById('tituloFiltro').textContent = titulo;
            if (!tabela) return;
            tabela.column(3).search(status === 'todos' ? '' : status, false, false).draw();
        }

        function confirmarDevolucao(event, form) {
            event.preventDefault();
            darkSwal.fire({
                title: 'Registrar Devolução?',
                text: 'Confirme o recebimento do livro.',
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'CONFIRMAR',
                cancelButtonText:  'CANCELAR',
            }).then(r => { if (r.isConfirmed) form.submit(); });
        }
    </script>

</x-app-layout>
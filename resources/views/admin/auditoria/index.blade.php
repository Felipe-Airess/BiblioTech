<x-app-layout>
    <x-slot name="header">
        <div class="flex w-full items-center justify-between gap-4">
            <div class="flex items-center gap-3">
                <a href="{{ route('dashboard') }}" class="flex flex-col items-center justify-center gap-1 shrink-0">
                    <i class="ph ph-library text-4xl text-[#1E3A8A] dark:text-blue-400"></i>
                    <div class="text-center text-[11px] font-black leading-tight tracking-tight">
                        <span class="text-[#1E3A8A] dark:text-blue-400">BIBLIO</span><br>
                        <span class="text-[#F59E0B]">TECH</span>
                    </div>
                </a>
                <div>
                    <p class="text-[10px] font-black uppercase tracking-[.18em] text-slate-500 dark:text-slate-400">Administração</p>
                    <h1 class="font-serif text-2xl font-black text-slate-950 dark:text-white">Auditoria</h1>
                    <p class="text-xs text-slate-500 dark:text-slate-400">Rastreabilidade das ações do sistema</p>
                </div>
            </div>
            <button type="button" @click="dark = !dark" class="h-10 w-10 rounded-md border border-slate-200 bg-white text-slate-600 transition hover:bg-slate-50 dark:border-white/10 dark:bg-white/5 dark:text-slate-300 dark:hover:bg-white/10" aria-label="Alternar tema">
                <i class="ph text-sm" :class="dark ? 'ph-sun' : 'ph-moon'"></i>
            </button>
        </div>
    </x-slot>

    <div class="-mx-4 min-h-screen bg-gradient-to-b from-slate-100 via-blue-50 to-slate-100 px-4 py-8 dark:from-[#0f172a] dark:via-[#0f172a] dark:to-[#0b1120] sm:-mx-6 sm:px-6 lg:-mx-8 lg:px-8">
        <main class="mx-auto max-w-7xl space-y-6">
            <div class="flex flex-wrap justify-end gap-2">
                <a href="{{ route('admin.auditoria.pdf', request()->query()) }}" class="inline-flex h-10 items-center gap-2 rounded-md bg-[#F59E0B] px-4 text-[11px] font-black uppercase tracking-widest text-slate-950 transition hover:bg-amber-400">
                    <i class="ph ph-file-pdf"></i>
                    PDF
                </a>
                <a href="{{ route('admin.auditoria.csv', request()->query()) }}" class="inline-flex h-10 items-center gap-2 rounded-md border border-slate-200 bg-white px-4 text-[11px] font-black uppercase tracking-widest text-slate-700 transition hover:bg-slate-50 dark:border-white/10 dark:bg-white/5 dark:text-slate-300 dark:hover:bg-white/10">
                    <i class="ph ph-file-csv"></i>
                    CSV
                </a>
            </div>

            @unless($hasAuditTable)
                <section class="rounded-md border border-amber-200 bg-amber-50 p-5 text-amber-900 shadow-sm dark:border-amber-500/30 dark:bg-amber-500/10 dark:text-amber-100">
                    <p class="text-[10px] font-black uppercase tracking-[.18em]">Migração pendente</p>
                    <h2 class="mt-2 font-serif text-xl font-black">A tabela de auditoria ainda não existe.</h2>
                    <p class="mt-1 text-sm">Rode as migrations para ativar o registro e a visualização completa dos logs.</p>
                </section>
            @endunless

            <section class="grid grid-cols-1 gap-3 md:grid-cols-2 xl:grid-cols-4">
                @foreach([
                    ['label' => 'Total de logs', 'value' => $metricas['total'], 'icon' => 'ph-list-checks', 'tone' => 'text-blue-600 dark:text-blue-300'],
                    ['label' => 'Hoje', 'value' => $metricas['hoje'], 'icon' => 'ph-calendar-check', 'tone' => 'text-emerald-600 dark:text-emerald-300'],
                    ['label' => 'Usuários ativos', 'value' => $metricas['usuarios'], 'icon' => 'ph-users-three', 'tone' => 'text-amber-600 dark:text-amber-300'],
                    ['label' => 'Ação frequente', 'value' => $metricas['acao_top'], 'icon' => 'ph-chart-line-up', 'tone' => 'text-slate-600 dark:text-slate-300'],
                ] as $card)
                    <div class="rounded-md border border-slate-200 bg-white/95 p-4 shadow-sm dark:border-white/10 dark:bg-[#0d1420]/95">
                        <div class="flex items-center justify-between gap-3">
                            <p class="text-[10px] font-black uppercase tracking-widest text-slate-500 dark:text-slate-400">{{ $card['label'] }}</p>
                            <i class="ph {{ $card['icon'] }} text-xl {{ $card['tone'] }}"></i>
                        </div>
                        <p class="mt-3 truncate text-2xl font-black text-slate-950 dark:text-white">{{ $card['value'] }}</p>
                    </div>
                @endforeach
            </section>

            <section class="rounded-md border border-slate-200 bg-white/95 p-4 shadow-sm dark:border-white/10 dark:bg-[#0d1420]/95">
                <form method="GET" class="grid grid-cols-1 gap-3 lg:grid-cols-[180px_190px_minmax(0,1fr)_150px_150px_auto_auto] lg:items-end">
                    <div>
                        <label for="user_id" class="mb-1 block text-[10px] font-black uppercase tracking-widest text-slate-500 dark:text-slate-400">Usuário</label>
                        <select id="user_id" name="user_id" class="h-11 w-full rounded-md border border-slate-200 bg-white px-3 text-sm text-slate-800 dark:border-white/10 dark:bg-[#080d14] dark:text-slate-200">
                            <option value="">Todos</option>
                            @foreach($users as $user)
                                <option value="{{ $user->id }}" @selected(request('user_id') == $user->id)>{{ $user->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label for="action" class="mb-1 block text-[10px] font-black uppercase tracking-widest text-slate-500 dark:text-slate-400">Ação</label>
                        <select id="action" name="action" class="h-11 w-full rounded-md border border-slate-200 bg-white px-3 text-sm text-slate-800 dark:border-white/10 dark:bg-[#080d14] dark:text-slate-200">
                            <option value="">Todas</option>
                            @foreach($actions as $action)
                                <option value="{{ $action }}" @selected(request('action') === $action)>{{ str_replace('_', ' ', ucfirst($action)) }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label for="entidade" class="mb-1 block text-[10px] font-black uppercase tracking-widest text-slate-500 dark:text-slate-400">Entidade</label>
                        <input id="entidade" name="entidade" value="{{ request('entidade') }}" placeholder="Livro, Membros, Emprestimos..." class="h-11 w-full rounded-md border border-slate-200 bg-white px-3 text-sm text-slate-800 dark:border-white/10 dark:bg-[#080d14] dark:text-slate-200">
                    </div>
                    <div>
                        <label for="inicio" class="mb-1 block text-[10px] font-black uppercase tracking-widest text-slate-500 dark:text-slate-400">Início</label>
                        <input id="inicio" name="inicio" value="{{ request('inicio') }}" type="date" class="h-11 w-full rounded-md border border-slate-200 bg-white px-3 text-sm text-slate-800 dark:border-white/10 dark:bg-[#080d14] dark:text-slate-200">
                    </div>
                    <div>
                        <label for="fim" class="mb-1 block text-[10px] font-black uppercase tracking-widest text-slate-500 dark:text-slate-400">Fim</label>
                        <input id="fim" name="fim" value="{{ request('fim') }}" type="date" class="h-11 w-full rounded-md border border-slate-200 bg-white px-3 text-sm text-slate-800 dark:border-white/10 dark:bg-[#080d14] dark:text-slate-200">
                    </div>
                    <button class="inline-flex h-11 items-center justify-center gap-2 rounded-md bg-[#1E3A8A] px-4 text-[11px] font-black uppercase tracking-widest text-white transition hover:bg-blue-800">
                        <i class="ph ph-funnel"></i>
                        Filtrar
                    </button>
                    <a href="{{ route('admin.auditoria.index') }}" class="inline-flex h-11 items-center justify-center gap-2 rounded-md border border-slate-200 bg-slate-50 px-4 text-[11px] font-black uppercase tracking-widest text-slate-700 transition hover:bg-slate-100 dark:border-white/10 dark:bg-white/5 dark:text-slate-300 dark:hover:bg-white/10">
                        Limpar
                    </a>
                </form>
            </section>

            <section class="overflow-hidden rounded-md border border-slate-200 bg-white/95 shadow-sm dark:border-white/10 dark:bg-[#0d1420]/95">
                <div class="border-b border-slate-200 px-5 py-4 dark:border-white/10">
                    <p class="text-[10px] font-black uppercase tracking-[.18em] text-blue-700 dark:text-blue-300">Timeline</p>
                    <h2 class="text-sm font-black uppercase tracking-widest text-slate-900 dark:text-white">Últimas ações</h2>
                </div>

                <div class="divide-y divide-slate-200 dark:divide-white/10">
                    @forelse($logs as $log)
                        @php
                            $icon = match (true) {
                                str_contains($log->action, 'multa') => 'ph-currency-circle-dollar',
                                str_contains($log->action, 'reserva') => 'ph-bookmark-simple',
                                str_contains($log->action, 'emprestimo') => 'ph-handshake',
                                str_contains($log->action, 'livro') => 'ph-book-open',
                                str_contains($log->action, 'membro') => 'ph-user',
                                default => 'ph-shield-check',
                            };
                            $entity = $log->auditable_type ? class_basename($log->auditable_type) . ' #' . $log->auditable_id : 'Sistema';
                        @endphp
                        <article class="grid grid-cols-1 gap-4 p-5 lg:grid-cols-[48px_minmax(0,1fr)_220px] lg:items-start">
                            <div class="flex h-12 w-12 items-center justify-center rounded-md border border-blue-200 bg-blue-50 text-blue-700 dark:border-blue-500/30 dark:bg-blue-500/10 dark:text-blue-300">
                                <i class="ph {{ $icon }} text-xl"></i>
                            </div>
                            <div class="min-w-0">
                                <div class="flex flex-wrap items-center gap-2">
                                    <span class="rounded-md border border-slate-200 bg-slate-50 px-2 py-1 text-[10px] font-black uppercase tracking-widest text-slate-600 dark:border-white/10 dark:bg-white/5 dark:text-slate-300">{{ str_replace('_', ' ', $log->action) }}</span>
                                    <span class="text-xs text-slate-500 dark:text-slate-400">{{ $entity }}</span>
                                </div>
                                <p class="mt-2 text-sm font-semibold text-slate-900 dark:text-white">{{ $log->description }}</p>
                                @if($log->metadata)
                                    <div class="mt-3 flex flex-wrap gap-2">
                                        @foreach($log->metadata as $key => $value)
                                            @if(is_scalar($value))
                                                <span class="rounded-md bg-slate-100 px-2 py-1 text-[10px] font-bold text-slate-500 dark:bg-white/5 dark:text-slate-400">{{ $key }}: {{ $value }}</span>
                                            @endif
                                        @endforeach
                                    </div>
                                @endif
                            </div>
                            <div class="text-left lg:text-right">
                                <p class="text-sm font-black text-slate-950 dark:text-white">{{ $log->user?->name ?? 'Sistema' }}</p>
                                <p class="mt-1 text-xs text-slate-500 dark:text-slate-400">{{ $log->created_at->format('d/m/Y H:i') }}</p>
                                <p class="mt-1 text-[10px] text-slate-400">{{ $log->ip_address }}</p>
                            </div>
                        </article>
                    @empty
                        <div class="p-10 text-center">
                            <div class="mx-auto flex h-14 w-14 items-center justify-center rounded-md border border-slate-200 bg-slate-50 text-slate-400 dark:border-white/10 dark:bg-white/5">
                                <i class="ph ph-shield-check text-2xl"></i>
                            </div>
                            <p class="mt-4 text-sm font-bold text-slate-700 dark:text-slate-300">Nenhum log encontrado.</p>
                            <p class="mt-1 text-xs text-slate-500 dark:text-slate-400">Os registros aparecem após ações administrativas.</p>
                        </div>
                    @endforelse
                </div>

                @if(method_exists($logs, 'links'))
                    <div class="border-t border-slate-200 px-5 py-4 dark:border-white/10">
                        {{ $logs->links() }}
                    </div>
                @endif
            </section>
        </main>
    </div>
</x-app-layout>

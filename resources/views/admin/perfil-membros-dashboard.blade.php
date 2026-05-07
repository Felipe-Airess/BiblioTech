<x-app-layout>
    {{-- ══ LIBS ══ --}}
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/tom-select@2.3.1/dist/css/tom-select.min.css">
    <script src="https://cdn.jsdelivr.net/npm/tom-select@2.3.1/dist/js/tom-select.complete.min.js"></script>

    <x-slot name="header">
        <div class="flex items-center justify-between w-full gap-4">
            <div class="flex items-center gap-3">
                <a href="{{ route('dashboard') }}" class="flex flex-col items-center justify-center gap-1 shrink-0">
                    <i class="ph ph-library text-[#1E3A8A] dark:text-blue-400 text-4xl"></i>
                    <div class="text-[11px] font-black tracking-tight text-center leading-tight">
                        <span class="text-[#1E3A8A] dark:text-blue-400">BIBLIO</span><br>
                        <span class="text-[#F59E0B]">TECH</span>
                    </div>
                </a>
                <div>
                    <p class="text-[10px] font-bold uppercase tracking-[.15em] text-purple-500 mb-0.5">Admin</p>
                    <h1 class="text-lg font-black text-slate-900 dark:text-white">Perfil de Membros</h1>
                </div>
            </div>
            <div class="flex items-center gap-2">
                <a href="{{ route('membros.create') }}" class="inline-flex items-center gap-2 px-4 py-2 rounded-md bg-[#1E3A8A] text-white text-[11px] font-black uppercase tracking-widest hover:bg-blue-700 transition">
                    <i class="ph ph-user-plus text-sm"></i>
                    Novo Membro
                </a>
                <button type="button" @click="dark = !dark" class="w-9 h-9 rounded-md bg-white dark:bg-white/5 border border-slate-200 dark:border-white/10 text-slate-600 dark:text-gray-300 hover:text-slate-900 dark:hover:text-white hover:bg-slate-50 dark:hover:bg-white/10 transition">
                    <i class="ph text-sm" :class="dark ? 'ph-sun' : 'ph-moon'"></i>
                </button>
                <div class="w-9 h-9 rounded-full bg-gradient-to-br from-[#1E3A8A] to-blue-700 flex items-center justify-center ring-1 ring-blue-500/30 shrink-0">
                    <span class="text-white text-[10px] font-black tracking-tight select-none">{{ auth()->user()->nome ? collect(explode(' ', auth()->user()->nome))->map(fn($p) => strtoupper(mb_substr($p,0,1)))->take(2)->join('') : (auth()->user()->name ? collect(explode(' ', auth()->user()->name))->map(fn($p) => strtoupper(mb_substr($p,0,1)))->take(2)->join('') : 'AD') }}</span>
                </div>
            </div>
        </div>
    </x-slot>

    {{-- ══ STYLES ══ --}}
    <style>
        .bg-shelf { background: linear-gradient(90deg, transparent, rgba(147,197,253,.07) 20%, rgba(147,197,253,.07) 80%, transparent); }
        .bg-icon  { color: rgba(147,197,253,.07); pointer-events: none; user-select: none; }
        #bg-glow-1 { background: radial-gradient(circle, rgba(30,58,138,.3) 0%, transparent 70%); }
        #bg-glow-2 { background: radial-gradient(circle, rgba(245,158,11,.15) 0%, transparent 70%); }

        /* TomSelect dark theme override */
        .ts-wrapper .ts-control {
            background: transparent !important;
            border-color: rgba(255,255,255,.1) !important;
            color: #e2e8f0 !important;
            border-radius: 6px !important;
            padding: 6px 10px !important;
            font-size: 0.75rem !important;
            min-height: 36px !important;
        }
        .ts-dropdown { background: #0d1420 !important; border-color: rgba(255,255,255,.1) !important; border-radius: 6px !important; z-index: 9999 !important; }
        .ts-dropdown .option { color: #cbd5e1 !important; font-size: 0.75rem; padding: 6px 10px; }
        .ts-dropdown .option:hover, .ts-dropdown .option.active { background: rgba(255,255,255,.07) !important; color: #fff !important; }
        .ts-dropdown .option.selected { background: rgba(30,58,138,.4) !important; color: #93c5fd !important; }
        .ts-wrapper.single .ts-control:after { border-top-color: #64748b !important; }

        /* Filter pill active */
        .filter-pill.active { outline: 2px solid; outline-offset: -2px; }
        .filter-pill-all.active  { outline-color: #3b82f6; background: rgba(59,130,246,.15) !important; }
        .filter-pill-bom.active  { outline-color: #10b981; background: rgba(16,185,129,.15) !important; }
        .filter-pill-dev.active  { outline-color: #f59e0b; background: rgba(245,158,11,.15) !important; }
        .filter-pill-mul.active  { outline-color: #ef4444; background: rgba(239,68,68,.15) !important; }

        /* member cards animation */
        .member-card { transition: opacity .2s, transform .2s; }
        .member-card.hidden-card { opacity: 0; pointer-events: none; height: 0; overflow: hidden; padding: 0; margin: 0; border: none; }
    </style>

    <div class="-mx-4 px-4 py-10 bg-slate-50 dark:bg-[#0f172a] sm:-mx-6 sm:px-6 lg:-mx-8 lg:px-8 min-h-screen relative">

        {{-- ══ DECORATIVE BACKGROUND ══ --}}
        <div class="fixed inset-0 z-0 pointer-events-none overflow-hidden" aria-hidden="true">
            <svg class="absolute inset-0 w-full h-full" xmlns="http://www.w3.org/2000/svg">
                <defs>
                    <pattern id="bg-dots-members" width="28" height="28" patternUnits="userSpaceOnUse">
                        <circle cx="1" cy="1" r="1" fill="#93c5fd" opacity="0.08"/>
                    </pattern>
                </defs>
                <rect width="100%" height="100%" fill="url(#bg-dots-members)"/>
            </svg>
            <div id="bg-glow-1" class="absolute -top-28 -left-20 w-96 h-96 rounded-full blur-[90px]"></div>
            <div id="bg-glow-2" class="absolute -bottom-20 -right-14 w-72 h-72 rounded-full blur-[80px]"></div>
            <div class="bg-shelf absolute left-0 right-0 h-px top-[22%]"></div>
            <div class="bg-shelf absolute left-0 right-0 h-px top-[58%]"></div>
            <div class="absolute top-0 left-0 w-[3px] h-32 bg-purple-500 opacity-40"></div>
            <i class="ph ph-users-three bg-icon absolute left-[3%] top-[5%] text-[28px]"></i>
            <i class="ph ph-book bg-icon absolute left-[87%] top-[8%] text-[22px]"></i>
            <i class="ph ph-book-open bg-icon absolute left-[14%] top-[58%] text-[34px]"></i>
            <i class="ph ph-identification-card bg-icon absolute left-[74%] top-[54%] text-[26px]"></i>
            <i class="ph ph-bookmark bg-icon absolute left-[44%] top-[78%] text-[18px]"></i>
            <i class="ph ph-books bg-icon absolute left-[91%] top-[72%] text-[30px]"></i>
            <i class="ph ph-graduation-cap bg-icon absolute left-[59%] top-[12%] text-[24px]"></i>
            <i class="ph ph-scroll bg-icon absolute left-[29%] top-[30%] text-[16px]"></i>
            <i class="ph ph-library bg-icon absolute left-[68%] top-[36%] text-[28px]"></i>
            <i class="ph ph-notebook bg-icon absolute left-[80%] top-[22%] text-[20px]"></i>
            <i class="ph ph-user-circle bg-icon absolute left-[8%] top-[80%] text-[22px]"></i>
            <i class="ph ph-book-open bg-icon absolute left-[50%] top-[44%] text-[14px]"></i>
        </div>

        <div class="max-w-7xl mx-auto relative z-10 space-y-8">

            {{-- ══ HERO STATS ROW ══ --}}
            <div class="bg-white dark:bg-[#0d1420] border border-slate-200 dark:border-white/5 rounded-md p-5 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div>
                    <p class="text-[10px] font-bold uppercase tracking-[.15em] text-purple-500 mb-1">Painel Administrativo</p>
                    <h2 class="text-xl md:text-2xl font-black text-slate-900 dark:text-white" style="font-family: 'Merriweather', serif;">Membros da Biblioteca</h2>
                    <p class="text-slate-500 dark:text-gray-500 text-sm mt-1">Monitore, filtre e gerencie perfis em tempo real.</p>
                </div>
                <div class="flex gap-2 flex-wrap items-center">
                    <div class="px-4 py-3 rounded-md bg-slate-50 dark:bg-white/5 border border-slate-200 dark:border-white/10">
                        <p class="text-[10px] uppercase tracking-widest text-slate-500 dark:text-gray-500">Total</p>
                        <p class="text-2xl font-black text-slate-900 dark:text-white">{{ $totalMembros }}</p>
                    </div>
                    <div class="px-4 py-3 rounded-md bg-emerald-50 dark:bg-emerald-900/20 border border-emerald-200 dark:border-emerald-800/40">
                        <p class="text-[10px] uppercase tracking-widest text-emerald-700 dark:text-emerald-400">Bom Perfil</p>
                        <p class="text-2xl font-black text-emerald-900 dark:text-emerald-200">{{ count($membrosBom) }}</p>
                    </div>
                    <div class="px-4 py-3 rounded-md bg-amber-50 dark:bg-amber-900/20 border border-amber-200 dark:border-amber-800/40">
                        <p class="text-[10px] uppercase tracking-widest text-amber-700 dark:text-amber-400">Devendo</p>
                        <p class="text-2xl font-black text-amber-900 dark:text-amber-200">{{ count($membrosDevendo) }}</p>
                    </div>
                    <div class="px-4 py-3 rounded-md bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800/40">
                        <p class="text-[10px] uppercase tracking-widest text-red-700 dark:text-red-400">Com Multa</p>
                        <p class="text-2xl font-black text-red-900 dark:text-red-200">{{ count($membrosComMulta) }}</p>
                    </div>
                </div>
            </div>

            {{-- ══ ASYMMETRIC MAIN GRID: 1/3 sidebar | 2/3 cards ══ --}}
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

                {{-- SIDEBAR 1/3: Filter controls --}}
                <div class="lg:col-span-1 space-y-4">

                    {{-- Filter Pills --}}
                    <div class="bg-white dark:bg-[#0d1420] border border-slate-200 dark:border-white/5 rounded-md p-5">
                        <p class="text-[10px] font-black uppercase tracking-[.15em] text-slate-500 dark:text-gray-500 mb-3">Filtrar por Perfil</p>
                        <div class="flex flex-col gap-2">

                            <button type="button"
                                data-filter="all"
                                class="filter-pill filter-pill-all active w-full flex items-center justify-between gap-3 px-4 py-3 rounded-md bg-slate-50 dark:bg-white/5 border border-slate-200 dark:border-white/10 hover:border-blue-400/50 transition text-left">
                                <span class="flex items-center gap-2">
                                    <span class="w-7 h-7 rounded-md bg-blue-500/10 flex items-center justify-center">
                                        <i class="ph ph-users text-blue-500 text-sm"></i>
                                    </span>
                                    <span class="text-sm font-bold text-slate-900 dark:text-white">Todos os Membros</span>
                                </span>
                                <span class="text-xs font-black text-slate-500 dark:text-gray-400 bg-slate-100 dark:bg-white/10 px-2 py-0.5 rounded-md">{{ $totalMembros }}</span>
                            </button>

                            <button type="button"
                                data-filter="bom"
                                class="filter-pill filter-pill-bom w-full flex items-center justify-between gap-3 px-4 py-3 rounded-md bg-slate-50 dark:bg-white/5 border border-slate-200 dark:border-white/10 hover:border-emerald-400/50 transition text-left">
                                <span class="flex items-center gap-2">
                                    <span class="w-7 h-7 rounded-md bg-emerald-500/10 flex items-center justify-center">
                                        <i class="ph ph-check-circle text-emerald-500 text-sm"></i>
                                    </span>
                                    <span class="text-sm font-bold text-slate-900 dark:text-white">Bom Perfil</span>
                                </span>
                                <span class="text-xs font-black text-emerald-700 dark:text-emerald-300 bg-emerald-100 dark:bg-emerald-900/30 px-2 py-0.5 rounded-md">{{ count($membrosBom) }}</span>
                            </button>

                            <button type="button"
                                data-filter="devendo"
                                class="filter-pill filter-pill-dev w-full flex items-center justify-between gap-3 px-4 py-3 rounded-md bg-slate-50 dark:bg-white/5 border border-slate-200 dark:border-white/10 hover:border-amber-400/50 transition text-left">
                                <span class="flex items-center gap-2">
                                    <span class="w-7 h-7 rounded-md bg-amber-500/10 flex items-center justify-center">
                                        <i class="ph ph-warning-circle text-amber-500 text-sm"></i>
                                    </span>
                                    <span class="text-sm font-bold text-slate-900 dark:text-white">Devendo</span>
                                </span>
                                <span class="text-xs font-black text-amber-700 dark:text-amber-300 bg-amber-100 dark:bg-amber-900/30 px-2 py-0.5 rounded-md">{{ count($membrosDevendo) }}</span>
                            </button>

                            <button type="button"
                                data-filter="multa"
                                class="filter-pill filter-pill-mul w-full flex items-center justify-between gap-3 px-4 py-3 rounded-md bg-slate-50 dark:bg-white/5 border border-slate-200 dark:border-white/10 hover:border-red-400/50 transition text-left">
                                <span class="flex items-center gap-2">
                                    <span class="w-7 h-7 rounded-md bg-red-500/10 flex items-center justify-center">
                                        <i class="ph ph-coins text-red-500 text-sm"></i>
                                    </span>
                                    <span class="text-sm font-bold text-slate-900 dark:text-white">Com Multa</span>
                                </span>
                                <span class="text-xs font-black text-red-700 dark:text-red-300 bg-red-100 dark:bg-red-900/30 px-2 py-0.5 rounded-md">{{ count($membrosComMulta) }}</span>
                            </button>
                        </div>
                    </div>

                    {{-- Quick search --}}
                    <div class="bg-white dark:bg-[#0d1420] border border-slate-200 dark:border-white/5 rounded-md p-5">
                        <p class="text-[10px] font-black uppercase tracking-[.15em] text-slate-500 dark:text-gray-500 mb-3">Busca Rápida</p>
                        <div class="relative">
                            <i class="ph ph-magnifying-glass pointer-events-none absolute left-3 top-1/2 -translate-y-1/2 text-slate-500 dark:text-gray-500 text-sm"></i>
                            <input
                                type="text"
                                id="memberSearch"
                                placeholder="Nome, email ou carteirinha..."
                                class="w-full bg-slate-50 dark:bg-white/5 border border-slate-200 dark:border-white/10 rounded-md pl-9 pr-3 py-2 text-xs text-slate-700 dark:text-gray-200 placeholder:text-slate-400 dark:placeholder:text-gray-600 focus:outline-none focus:border-[#1E3A8A] focus:ring-1 focus:ring-[#1E3A8A]/30 transition"
                            >
                        </div>
                        <p id="searchCount" class="text-[10px] text-slate-400 dark:text-gray-600 mt-2 text-right hidden"></p>
                    </div>

                    {{-- Legend --}}
                    <div class="bg-white dark:bg-[#0d1420] border border-slate-200 dark:border-white/5 rounded-md p-5">
                        <p class="text-[10px] font-black uppercase tracking-[.15em] text-slate-500 dark:text-gray-500 mb-3">Legenda</p>
                        <div class="space-y-2">
                            <div class="flex items-center gap-2">
                                <span class="w-2 h-2 rounded-full bg-emerald-500 shrink-0"></span>
                                <span class="text-xs text-slate-600 dark:text-gray-400">Bom Perfil — sem pendências</span>
                            </div>
                            <div class="flex items-center gap-2">
                                <span class="w-2 h-2 rounded-full bg-amber-500 shrink-0"></span>
                                <span class="text-xs text-slate-600 dark:text-gray-400">Devendo — prazo vencido</span>
                            </div>
                            <div class="flex items-center gap-2">
                                <span class="w-2 h-2 rounded-full bg-red-500 shrink-0"></span>
                                <span class="text-xs text-slate-600 dark:text-gray-400">Multa pendente — valor em aberto</span>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- MAIN AREA 2/3: Member Cards --}}
                <div class="lg:col-span-2">
                    {{-- Filter label --}}
                    <div class="flex items-center justify-between mb-4">
                        <div class="flex items-center gap-2">
                            <i class="ph ph-funnel text-purple-500 text-sm"></i>
                            <span id="filterLabel" class="text-xs font-black uppercase tracking-widest text-slate-500 dark:text-gray-400">Todos os Membros</span>
                        </div>
                        <span id="visibleCount" class="text-[10px] text-slate-400 dark:text-gray-600 font-bold">{{ $totalMembros }} resultados</span>
                    </div>

                    {{-- Cards Grid --}}
                    <div id="cardsGrid" class="grid grid-cols-1 sm:grid-cols-2 gap-4">

                        {{-- BOM PERFIL --}}
                        @foreach($membrosBom as $item)
                        <a href="{{ route('admin.membros.show', $item['membro']->id) }}"
                            class="member-card group block bg-white dark:bg-[#0d1420] border border-emerald-200/60 dark:border-emerald-900/30 rounded-md p-4 hover:border-emerald-400 dark:hover:border-emerald-700 hover:shadow-lg hover:-translate-y-0.5 transition-all"
                            data-perfil="bom"
                            data-nome="{{ strtolower($item['membro']->nome) }}"
                            data-email="{{ strtolower($item['membro']->email) }}"
                            data-carteirinha="{{ strtolower($item['membro']->numero_carteirinha ?? '') }}">
                            <div class="flex items-start justify-between gap-2 mb-3">
                                <div class="flex items-center gap-3 min-w-0">
                                    <div class="w-9 h-9 rounded-full bg-gradient-to-br from-emerald-500 to-teal-600 flex items-center justify-center shrink-0 text-white text-[11px] font-black">
                                        {{ collect(explode(' ', $item['membro']->nome))->map(fn($p) => strtoupper(mb_substr($p,0,1)))->take(2)->join('') }}
                                    </div>
                                    <div class="min-w-0">
                                        <p class="text-sm font-bold text-slate-900 dark:text-white truncate">{{ $item['membro']->nome }}</p>
                                        <p class="text-[11px] text-slate-500 dark:text-gray-500 truncate">{{ $item['membro']->email }}</p>
                                    </div>
                                </div>
                                <span class="shrink-0 inline-flex items-center gap-1 px-2 py-0.5 rounded-md text-[10px] font-black uppercase tracking-widest bg-emerald-100 text-emerald-700 dark:bg-emerald-900/30 dark:text-emerald-300">
                                    <i class="ph ph-check"></i> Ativo
                                </span>
                            </div>
                            <div class="grid grid-cols-3 gap-2 pt-3 border-t border-slate-100 dark:border-white/5">
                                <div class="text-center">
                                    <p class="text-[10px] text-slate-500 dark:text-gray-500 uppercase tracking-wider">Completos</p>
                                    <p class="text-lg font-black text-slate-900 dark:text-white">{{ $item['emprestimosCompletados'] }}</p>
                                </div>
                                <div class="text-center">
                                    <p class="text-[10px] text-slate-500 dark:text-gray-500 uppercase tracking-wider">Ativos</p>
                                    <p class="text-lg font-black text-emerald-600 dark:text-emerald-400">{{ $item['emprestimosAtivos'] }}</p>
                                </div>
                                <div class="text-center">
                                    <p class="text-[10px] text-slate-500 dark:text-gray-500 uppercase tracking-wider">Multa</p>
                                    <p class="text-lg font-black text-slate-900 dark:text-white">R$ 0</p>
                                </div>
                            </div>
                            <div class="mt-3 flex items-center justify-end gap-1 text-emerald-600 dark:text-emerald-400 opacity-0 group-hover:opacity-100 transition text-[11px] font-bold uppercase tracking-widest">
                                Ver perfil <i class="ph ph-arrow-right text-sm"></i>
                            </div>
                        </a>
                        @endforeach

                        {{-- DEVENDO --}}
                        @foreach($membrosDevendo as $item)
                        <a href="{{ route('admin.membros.show', $item['membro']->id) }}"
                            class="member-card group block bg-white dark:bg-[#0d1420] border border-amber-200/60 dark:border-amber-900/30 rounded-md p-4 hover:border-amber-400 dark:hover:border-amber-700 hover:shadow-lg hover:-translate-y-0.5 transition-all"
                            data-perfil="devendo"
                            data-nome="{{ strtolower($item['membro']->nome) }}"
                            data-email="{{ strtolower($item['membro']->email) }}"
                            data-carteirinha="{{ strtolower($item['membro']->numero_carteirinha ?? '') }}">
                            <div class="flex items-start justify-between gap-2 mb-3">
                                <div class="flex items-center gap-3 min-w-0">
                                    <div class="w-9 h-9 rounded-full bg-gradient-to-br from-amber-500 to-orange-600 flex items-center justify-center shrink-0 text-white text-[11px] font-black">
                                        {{ collect(explode(' ', $item['membro']->nome))->map(fn($p) => strtoupper(mb_substr($p,0,1)))->take(2)->join('') }}
                                    </div>
                                    <div class="min-w-0">
                                        <p class="text-sm font-bold text-slate-900 dark:text-white truncate">{{ $item['membro']->nome }}</p>
                                        <p class="text-[11px] text-slate-500 dark:text-gray-500 truncate">{{ $item['membro']->email }}</p>
                                    </div>
                                </div>
                                <span class="shrink-0 inline-flex items-center gap-1 px-2 py-0.5 rounded-md text-[10px] font-black uppercase tracking-widest bg-amber-100 text-amber-700 dark:bg-amber-900/30 dark:text-amber-300">
                                    <i class="ph ph-warning"></i> Atrasado
                                </span>
                            </div>
                            <div class="mb-3 px-3 py-2 rounded-md bg-amber-50 dark:bg-amber-900/10 border border-amber-200 dark:border-amber-900/30 text-[11px] font-bold text-amber-800 dark:text-amber-300 flex items-center gap-2">
                                <i class="ph ph-clock-countdown text-sm"></i>
                                {{ count($item['emprestimosAtrasados']) }} livro(s) com prazo vencido
                            </div>
                            <div class="grid grid-cols-3 gap-2 pt-3 border-t border-slate-100 dark:border-white/5">
                                <div class="text-center">
                                    <p class="text-[10px] text-slate-500 dark:text-gray-500 uppercase tracking-wider">Atrasados</p>
                                    <p class="text-lg font-black text-amber-600 dark:text-amber-400">{{ count($item['emprestimosAtrasados']) }}</p>
                                </div>
                                <div class="text-center">
                                    <p class="text-[10px] text-slate-500 dark:text-gray-500 uppercase tracking-wider">Ativos</p>
                                    <p class="text-lg font-black text-slate-900 dark:text-white">{{ $item['emprestimosAtivos'] }}</p>
                                </div>
                                <div class="text-center">
                                    <p class="text-[10px] text-slate-500 dark:text-gray-500 uppercase tracking-wider">Multa</p>
                                    <p class="text-lg font-black text-red-600 dark:text-red-400">R$ {{ number_format($item['multasNaoPagas'], 0, ',', '.') }}</p>
                                </div>
                            </div>
                            <div class="mt-3 flex items-center justify-end gap-1 text-amber-600 dark:text-amber-400 opacity-0 group-hover:opacity-100 transition text-[11px] font-bold uppercase tracking-widest">
                                Ver perfil <i class="ph ph-arrow-right text-sm"></i>
                            </div>
                        </a>
                        @endforeach

                        {{-- COM MULTA --}}
                        @foreach($membrosComMulta as $item)
                        <a href="{{ route('admin.membros.show', $item['membro']->id) }}"
                            class="member-card group block bg-white dark:bg-[#0d1420] border border-red-200/60 dark:border-red-900/30 rounded-md p-4 hover:border-red-400 dark:hover:border-red-700 hover:shadow-lg hover:-translate-y-0.5 transition-all"
                            data-perfil="multa"
                            data-nome="{{ strtolower($item['membro']->nome) }}"
                            data-email="{{ strtolower($item['membro']->email) }}"
                            data-carteirinha="{{ strtolower($item['membro']->numero_carteirinha ?? '') }}">
                            <div class="flex items-start justify-between gap-2 mb-3">
                                <div class="flex items-center gap-3 min-w-0">
                                    <div class="w-9 h-9 rounded-full bg-gradient-to-br from-red-500 to-rose-600 flex items-center justify-center shrink-0 text-white text-[11px] font-black">
                                        {{ collect(explode(' ', $item['membro']->nome))->map(fn($p) => strtoupper(mb_substr($p,0,1)))->take(2)->join('') }}
                                    </div>
                                    <div class="min-w-0">
                                        <p class="text-sm font-bold text-slate-900 dark:text-white truncate">{{ $item['membro']->nome }}</p>
                                        <p class="text-[11px] text-slate-500 dark:text-gray-500 truncate">{{ $item['membro']->email }}</p>
                                    </div>
                                </div>
                                <span class="shrink-0 inline-flex items-center gap-1 px-2 py-0.5 rounded-md text-[10px] font-black uppercase tracking-widest bg-red-100 text-red-700 dark:bg-red-900/30 dark:text-red-300">
                                    <i class="ph ph-coins"></i> Multa
                                </span>
                            </div>
                            <div class="mb-3 px-3 py-2 rounded-md bg-red-50 dark:bg-red-900/10 border border-red-200 dark:border-red-900/30 text-[11px] font-bold text-red-800 dark:text-red-300 flex items-center gap-2">
                                <i class="ph ph-money text-sm"></i>
                                R$ {{ number_format($item['multasNaoPagas'], 2, ',', '.') }} em aberto
                            </div>
                            <div class="grid grid-cols-3 gap-2 pt-3 border-t border-slate-100 dark:border-white/5">
                                <div class="text-center">
                                    <p class="text-[10px] text-slate-500 dark:text-gray-500 uppercase tracking-wider">Multa Total</p>
                                    <p class="text-lg font-black text-red-600 dark:text-red-400">R$ {{ number_format($item['multasNaoPagas'], 0, ',', '.') }}</p>
                                </div>
                                <div class="text-center">
                                    <p class="text-[10px] text-slate-500 dark:text-gray-500 uppercase tracking-wider">Ativos</p>
                                    <p class="text-lg font-black text-slate-900 dark:text-white">{{ $item['emprestimosAtivos'] }}</p>
                                </div>
                                <div class="text-center">
                                    <p class="text-[10px] text-slate-500 dark:text-gray-500 uppercase tracking-wider">Status</p>
                                    <i class="ph ph-x-circle text-lg text-red-500"></i>
                                </div>
                            </div>
                            <div class="mt-3 flex items-center justify-end gap-1 text-red-600 dark:text-red-400 opacity-0 group-hover:opacity-100 transition text-[11px] font-bold uppercase tracking-widest">
                                Ver perfil <i class="ph ph-arrow-right text-sm"></i>
                            </div>
                        </a>
                        @endforeach

                    </div>

                    {{-- Empty state: fora do grid, centralização garantida --}}
                    <div id="emptyState" class="hidden py-20 flex-col items-center justify-center text-center w-full">
                        <i class="ph ph-users-three text-slate-300 dark:text-slate-700 text-5xl mb-3"></i>
                        <p class="text-slate-400 dark:text-slate-600 font-bold">Nenhum membro encontrado</p>
                        <p class="text-slate-400 dark:text-slate-600 text-sm mt-1">Tente outro filtro ou busca</p>
                    </div>
                </div>
            </div>

            {{-- ══ TABELA COMPLETA COM TOMSELECT ══ --}}
            <div class="bg-white dark:bg-[#0d1420] border border-slate-200 dark:border-white/5 rounded-md overflow-hidden">
                {{-- Header --}}
                <div class="px-5 py-4 border-b border-slate-100 dark:border-white/5 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                    <div class="flex items-center gap-2">
                        <i class="ph ph-table text-[#F59E0B] text-sm"></i>
                        <h3 class="text-sm font-black text-slate-900 dark:text-white uppercase tracking-widest">Todos os Membros</h3>
                        <span class="text-[10px] text-slate-400 dark:text-gray-500 bg-slate-100 dark:bg-white/5 px-2 py-0.5 rounded-md font-bold">{{ $allMembros->count() }}</span>
                    </div>

                    {{-- TomSelect search --}}
                    <div class="flex items-center gap-2 w-full sm:w-auto">
                        <div class="relative flex-1 sm:w-64">
                            <select id="tableSearch" placeholder="Buscar membro...">
                                <option value="">Todos os membros</option>
                                @foreach($allMembros as $m)
                                    <option value="{{ $m->id }}" data-nome="{{ $m->nome }}" data-email="{{ $m->email }}">
                                        {{ $m->nome }} — {{ $m->email }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <button type="button" id="clearTableSearch" class="inline-flex items-center gap-1.5 px-3 py-2 rounded-md bg-slate-100 dark:bg-white/5 border border-slate-200 dark:border-white/10 text-slate-600 dark:text-gray-400 hover:text-slate-900 dark:hover:text-white text-[11px] font-bold uppercase tracking-widest transition">
                            <i class="ph ph-x text-sm"></i> Limpar
                        </button>
                    </div>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-sm text-left" id="membrosTable">
                        <thead>
                            <tr class="text-[10px] uppercase tracking-wider text-slate-500 dark:text-gray-500 bg-slate-50 dark:bg-[#0d1420] border-b border-slate-100 dark:border-white/5">
                                <th class="px-4 py-3 font-bold">Membro</th>
                                <th class="px-4 py-3 font-bold hidden md:table-cell">Carteirinha</th>
                                <th class="px-4 py-3 font-bold hidden lg:table-cell">Cadastro</th>
                                <th class="px-4 py-3 font-bold text-right">Ações</th>
                            </tr>
                        </thead>
                        <tbody id="membrosTableBody">
                            @foreach($allMembros as $m)
                            <tr class="table-row border-t border-slate-100 dark:border-white/5 hover:bg-slate-50 dark:hover:bg-white/5 transition"
                                data-id="{{ $m->id }}"
                                data-nome="{{ $m->nome }}">
                                <td class="px-4 py-3">
                                    <div class="flex items-center gap-3">
                                        <div class="w-8 h-8 rounded-full bg-gradient-to-br from-[#1E3A8A] to-blue-600 flex items-center justify-center text-white text-[10px] font-black shrink-0">
                                            {{ collect(explode(' ', $m->nome))->map(fn($p) => strtoupper(mb_substr($p,0,1)))->take(2)->join('') }}
                                        </div>
                                        <div>
                                            <p class="text-sm font-semibold text-slate-900 dark:text-white">{{ $m->nome }}</p>
                                            <p class="text-[11px] text-slate-500 dark:text-gray-500">{{ $m->email }}</p>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-4 py-3 hidden md:table-cell">
                                    <span class="text-xs font-mono text-blue-600 dark:text-blue-400 bg-blue-50 dark:bg-blue-900/20 px-2 py-0.5 rounded-md">{{ $m->numero_carteirinha ?? '—' }}</span>
                                </td>
                                <td class="px-4 py-3 hidden lg:table-cell text-xs text-slate-500 dark:text-gray-500">
                                    {{ $m->created_at->format('d/m/Y') }}
                                </td>
                                <td class="px-4 py-3">
                                    <div class="flex items-center justify-end gap-2">
                                        <a href="{{ route('admin.membros.show', $m->id) }}" class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-md bg-[#1E3A8A]/10 border border-[#1E3A8A]/30 text-blue-700 dark:text-blue-400 hover:bg-[#1E3A8A]/20 text-[11px] font-bold uppercase tracking-widest transition">
                                            <i class="ph ph-eye text-sm"></i>
                                            Ver
                                        </a>
                                        <button type="button"
                                            class="sendMsgBtn inline-flex items-center gap-1.5 px-3 py-1.5 rounded-md bg-purple-500/10 border border-purple-500/30 text-purple-700 dark:text-purple-400 hover:bg-purple-500/20 text-[11px] font-bold uppercase tracking-widest transition"
                                            data-id="{{ $m->id }}"
                                            data-nome="{{ htmlspecialchars($m->nome, ENT_QUOTES) }}">
                                            <i class="ph ph-envelope-simple text-sm"></i>
                                            Mensagem
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                {{-- Empty state for table --}}
                <div id="tableEmpty" class="hidden py-12 flex flex-col items-center text-center">
                    <i class="ph ph-magnifying-glass text-slate-300 dark:text-slate-700 text-4xl mb-2"></i>
                    <p class="text-slate-400 dark:text-slate-600 text-sm font-bold">Nenhum resultado encontrado</p>
                </div>
            </div>

        </div>
    </div>

    {{-- ══ MODAL ENVIAR MENSAGEM ══ --}}
    <div id="messageModal" class="fixed inset-0 z-50 hidden items-center justify-center bg-black/60 backdrop-blur-sm">
        <div class="w-full max-w-lg mx-4">
            <div class="bg-white dark:bg-[#0d1420] rounded-md p-6 border border-slate-200 dark:border-white/10 shadow-2xl">
                <div class="flex items-center justify-between mb-5">
                    <div class="flex items-center gap-2">
                        <i class="ph ph-envelope-simple text-purple-500 text-lg"></i>
                        <h4 class="text-base font-black text-slate-900 dark:text-white">Enviar Mensagem</h4>
                    </div>
                    <button type="button" id="cancelMsg" class="w-8 h-8 rounded-md bg-slate-100 dark:bg-white/5 text-slate-500 hover:text-slate-900 dark:hover:text-white transition flex items-center justify-center">
                        <i class="ph ph-x text-sm"></i>
                    </button>
                </div>
                <p class="text-[11px] text-slate-500 dark:text-gray-500 mb-4 uppercase tracking-wider">Para: <strong id="modalMemberName" class="text-slate-900 dark:text-white normal-case tracking-normal text-sm"></strong></p>
                <form id="messageForm" class="space-y-4">
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                    <div>
                        <label class="text-xs font-bold uppercase tracking-widest text-slate-500 dark:text-gray-500 mb-1 block">Assunto</label>
                        <input type="text" name="subject" id="msgSubject"
                            class="w-full px-3 py-2 rounded-md border border-slate-200 dark:border-white/10 bg-slate-50 dark:bg-white/5 text-slate-900 dark:text-white text-sm focus:outline-none focus:border-purple-500 focus:ring-1 focus:ring-purple-500/30 transition" required>
                    </div>
                    <div>
                        <label class="text-xs font-bold uppercase tracking-widest text-slate-500 dark:text-gray-500 mb-1 block">Mensagem</label>
                        <textarea name="message" id="msgBody" rows="5"
                            class="w-full px-3 py-2 rounded-md border border-slate-200 dark:border-white/10 bg-slate-50 dark:bg-white/5 text-slate-900 dark:text-white text-sm focus:outline-none focus:border-purple-500 focus:ring-1 focus:ring-purple-500/30 transition resize-none" required></textarea>
                    </div>
                    <div class="flex items-center justify-end gap-2 pt-2">
                        <button type="button" id="cancelMsgBtn2" class="px-4 py-2 rounded-md bg-slate-100 dark:bg-white/5 border border-slate-200 dark:border-white/10 text-slate-700 dark:text-gray-300 text-[11px] font-bold uppercase tracking-widest hover:bg-slate-200 dark:hover:bg-white/10 transition">Cancelar</button>
                        <button type="submit" class="px-5 py-2 rounded-md bg-purple-600 border border-purple-500 text-white text-[11px] font-black uppercase tracking-widest hover:bg-purple-700 transition">
                            <i class="ph ph-paper-plane-right mr-1"></i> Enviar
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
    document.addEventListener('DOMContentLoaded', () => {

        // ── Filter Pills ──────────────────────────────────────────────
        const pills = document.querySelectorAll('.filter-pill');
        const cards = document.querySelectorAll('.member-card');
        const emptyState = document.getElementById('emptyState');
        const filterLabel = document.getElementById('filterLabel');
        const visibleCount = document.getElementById('visibleCount');
        const searchInput = document.getElementById('memberSearch');
        const searchCount = document.getElementById('searchCount');

        const labels = {
            all: 'Todos os Membros',
            bom: 'Bom Perfil',
            devendo: 'Devendo',
            multa: 'Com Multa',
        };

        let currentFilter = 'all';
        let currentSearch = '';

        function applyFilters() {
            let visible = 0;
            cards.forEach(card => {
                const perfil = card.dataset.perfil;
                const nome = card.dataset.nome;
                const email = card.dataset.email;
                const cart = card.dataset.carteirinha;

                const matchesPerfil = currentFilter === 'all' || perfil === currentFilter;
                const matchesSearch = !currentSearch ||
                    nome.includes(currentSearch) ||
                    email.includes(currentSearch) ||
                    cart.includes(currentSearch);

                if (matchesPerfil && matchesSearch) {
                    card.style.display = '';
                    visible++;
                } else {
                    card.style.display = 'none';
                }
            });

            if (visible > 0) {
                    emptyState.classList.add('hidden');
                    emptyState.classList.remove('flex');
                } else {
                    emptyState.classList.remove('hidden');
                    emptyState.classList.add('flex');
                }
            filterLabel.textContent = labels[currentFilter] || labels.all;
            visibleCount.textContent = visible + ' resultado' + (visible !== 1 ? 's' : '');

            if (currentSearch) {
                searchCount.textContent = visible + ' encontrado(s)';
                searchCount.classList.remove('hidden');
            } else {
                searchCount.classList.add('hidden');
            }
        }

        pills.forEach(pill => {
            pill.addEventListener('click', () => {
                pills.forEach(p => p.classList.remove('active'));
                pill.classList.add('active');
                currentFilter = pill.dataset.filter;
                applyFilters();
            });
        });

        searchInput.addEventListener('input', () => {
            currentSearch = searchInput.value.toLowerCase().trim();
            applyFilters();
        });

        // ── TomSelect for table search ────────────────────────────────
        const tsTable = new TomSelect('#tableSearch', {
            allowEmptyOption: true,
            create: false,
            maxOptions: 999,
            searchField: ['text'],
            placeholder: 'Buscar membro...',
            onChange(val) {
                const rows = document.querySelectorAll('#membrosTableBody .table-row');
                let visibleRows = 0;
                rows.forEach(row => {
                    if (!val || row.dataset.id === String(val)) {
                        row.style.display = '';
                        visibleRows++;
                    } else {
                        row.style.display = 'none';
                    }
                });
                document.getElementById('tableEmpty').classList.toggle('hidden', visibleRows > 0);
            }
        });

        document.getElementById('clearTableSearch').addEventListener('click', () => {
            tsTable.setValue('');
            document.querySelectorAll('#membrosTableBody .table-row').forEach(r => r.style.display = '');
            document.getElementById('tableEmpty').classList.add('hidden');
        });

        // ── Messaging modal ────────────────────────────────────────────
        const sendButtons = document.querySelectorAll('.sendMsgBtn');
        const messageModal = document.getElementById('messageModal');
        const modalMemberName = document.getElementById('modalMemberName');
        const messageForm = document.getElementById('messageForm');
        let currentMemberId = null;

        function openModal(id, name) {
            currentMemberId = id;
            modalMemberName.textContent = name;
            document.getElementById('msgSubject').value = '';
            document.getElementById('msgBody').value = '';
            messageModal.classList.remove('hidden');
            messageModal.classList.add('flex');
        }

        function closeModal() {
            currentMemberId = null;
            messageModal.classList.remove('flex');
            messageModal.classList.add('hidden');
        }

        sendButtons.forEach(btn => btn.addEventListener('click', () => {
            openModal(btn.dataset.id, btn.dataset.nome);
        }));

        document.getElementById('cancelMsg').addEventListener('click', closeModal);
        document.getElementById('cancelMsgBtn2').addEventListener('click', closeModal);
        messageModal.addEventListener('click', e => { if (e.target === messageModal) closeModal(); });

        messageForm.addEventListener('submit', async e => {
            e.preventDefault();
            if (!currentMemberId) return;
            const formData = new FormData(messageForm);
            try {
                const res = await fetch(`/admin/membros/${currentMemberId}/message`, {
                    method: 'POST',
                    headers: { 'X-CSRF-TOKEN': formData.get('_token'), 'Accept': 'application/json', 'Content-Type': 'application/json' },
                    body: JSON.stringify({ subject: formData.get('subject'), message: formData.get('message') }),
                });
                if (res.ok) {
                    closeModal();
                    if (typeof Swal !== 'undefined') {
                        Swal.mixin({ toast: true, position: 'top-end', showConfirmButton: false, timer: 3000, background: '#0d1420', color: '#fff' })
                            .fire({ icon: 'success', title: 'Mensagem enviada!' });
                    }
                }
            } catch(err) { console.error(err); }
        });

    });
    </script>

</x-app-layout>
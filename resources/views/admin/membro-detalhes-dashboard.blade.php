<x-app-layout>

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
                    <p class="text-[10px] font-bold uppercase tracking-[.15em] text-purple-500 mb-0.5">Perfil do Membro</p>
                    <h1 class="text-lg font-black text-slate-900 dark:text-white">{{ $membro->nome }}</h1>
                    <p class="text-[11px] text-slate-500 dark:text-gray-500">
                        Carteirinha <span class="font-mono text-blue-600 dark:text-blue-400">{{ $membro->numero_carteirinha }}</span>
                    </p>
                </div>
            </div>

            <div class="flex items-center gap-2">
                <a href="{{ route('admin.membros.perfis') }}" class="inline-flex items-center gap-2 px-4 py-2 rounded-md bg-slate-100 dark:bg-white/5 border border-slate-200 dark:border-white/10 text-slate-700 dark:text-gray-300 hover:text-slate-900 dark:hover:text-white text-[11px] font-bold uppercase tracking-widest transition">
                    <i class="ph ph-arrow-left text-sm"></i> Voltar
                </a>
                <button type="button" @click="dark = !dark" class="w-9 h-9 rounded-md bg-white dark:bg-white/5 border border-slate-200 dark:border-white/10 text-slate-600 dark:text-gray-300 hover:text-slate-900 dark:hover:text-white hover:bg-slate-50 dark:hover:bg-white/10 transition">
                    <i class="ph text-sm" :class="dark ? 'ph-sun' : 'ph-moon'"></i>
                </button>
            </div>
        </div>
    </x-slot>

    {{-- Styles --}}
    <style>
        .bg-shelf { background: linear-gradient(90deg, transparent, rgba(147,197,253,.07) 20%, rgba(147,197,253,.07) 80%, transparent); }
        .bg-icon  { color: rgba(147,197,253,.07); pointer-events: none; user-select: none; }
        #bg-glow-det-1 { background: radial-gradient(circle, rgba(30,58,138,.25) 0%, transparent 70%); }
        #bg-glow-det-2 { background: radial-gradient(circle, rgba(139,92,246,.12) 0%, transparent 70%); }
    </style>

    <div class="-mx-4 px-4 py-10 bg-slate-50 dark:bg-[#0f172a] sm:-mx-6 sm:px-6 lg:-mx-8 lg:px-8 min-h-screen relative">

        {{-- DECORATIVE BACKGROUND --}}
        <div class="fixed inset-0 z-0 pointer-events-none overflow-hidden" aria-hidden="true">
            <svg class="absolute inset-0 w-full h-full" xmlns="http://www.w3.org/2000/svg">
                <defs>
                    <pattern id="bg-dots-det" width="28" height="28" patternUnits="userSpaceOnUse">
                        <circle cx="1" cy="1" r="1" fill="#93c5fd" opacity="0.07"/>
                    </pattern>
                </defs>
                <rect width="100%" height="100%" fill="url(#bg-dots-det)"/>
            </svg>
            <div id="bg-glow-det-1" class="absolute -top-28 -left-20 w-96 h-96 rounded-full blur-[90px]"></div>
            <div id="bg-glow-det-2" class="absolute -bottom-20 right-10 w-80 h-80 rounded-full blur-[80px]"></div>
            <div class="bg-shelf absolute left-0 right-0 h-px top-[30%]"></div>
            <div class="bg-shelf absolute left-0 right-0 h-px top-[65%]"></div>
            <div class="absolute top-0 left-0 w-[3px] h-32 bg-purple-500 opacity-40"></div>
            <i class="ph ph-book bg-icon absolute left-[5%] top-[6%] text-[28px]"></i>
            <i class="ph ph-books bg-icon absolute left-[85%] top-[10%] text-[22px]"></i>
            <i class="ph ph-book-open bg-icon absolute left-[12%] top-[55%] text-[34px]"></i>
            <i class="ph ph-identification-card bg-icon absolute left-[72%] top-[50%] text-[26px]"></i>
            <i class="ph ph-bookmark bg-icon absolute left-[43%] top-[76%] text-[18px]"></i>
            <i class="ph ph-bookmarks bg-icon absolute left-[90%] top-[70%] text-[30px]"></i>
            <i class="ph ph-graduation-cap bg-icon absolute left-[58%] top-[14%] text-[24px]"></i>
            <i class="ph ph-scroll bg-icon absolute left-[27%] top-[32%] text-[16px]"></i>
        </div>

        <div class="max-w-6xl mx-auto relative z-10 space-y-6">

            {{-- STATUS BANNER --}}
            @php
                $ativos   = $emprestimos->whereIn('status', ['retirado', 'em_uso', 'devolucao_solicitada'])->count();
                $encerrados = $emprestimos->where('status', 'encerrado')->count();
                $totalAtrasados = $atrasados->count();
                $statusGeral = $multasTotal > 0 ? 'multa' : ($totalAtrasados > 0 ? 'devendo' : 'bom');
            @endphp
            <div class="rounded-md px-5 py-3 flex items-center gap-3 border
                @if($statusGeral === 'multa') bg-red-50 dark:bg-red-900/10 border-red-200 dark:border-red-900/30
                @elseif($statusGeral === 'devendo') bg-amber-50 dark:bg-amber-900/10 border-amber-200 dark:border-amber-900/30
                @else bg-emerald-50 dark:bg-emerald-900/10 border-emerald-200 dark:border-emerald-900/30
                @endif">
                @if($statusGeral === 'multa')
                    <i class="ph ph-coins text-red-500 text-xl shrink-0"></i>
                    <div>
                        <p class="text-sm font-bold text-red-800 dark:text-red-200">Membro com multa pendente</p>
                        <p class="text-xs text-red-700 dark:text-red-400">R$ {{ number_format($multasTotal, 2, ',', '.') }} em aberto · requer atenção</p>
                    </div>
                @elseif($statusGeral === 'devendo')
                    <i class="ph ph-warning-circle text-amber-500 text-xl shrink-0"></i>
                    <div>
                        <p class="text-sm font-bold text-amber-800 dark:text-amber-200">Membro com empréstimo(s) atrasado(s)</p>
                        <p class="text-xs text-amber-700 dark:text-amber-400">{{ $totalAtrasados }} livro(s) com prazo vencido</p>
                    </div>
                @else
                    <i class="ph ph-check-circle text-emerald-500 text-xl shrink-0"></i>
                    <div>
                        <p class="text-sm font-bold text-emerald-800 dark:text-emerald-200">Perfil em dia — sem pendências</p>
                        <p class="text-xs text-emerald-700 dark:text-emerald-400">Nenhuma multa ou atraso registrado</p>
                    </div>
                @endif
            </div>

            {{-- MAIN GRID: 1/3 | 2/3 ──────────────────────────────────── --}}
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

                {{-- LEFT: Perfil + Stats --}}
                <div class="lg:col-span-1 space-y-4">

                    {{-- Avatar + Info --}}
                    <div class="bg-white dark:bg-[#0d1420] border border-slate-200 dark:border-white/5 rounded-md p-5">
                        <div class="flex flex-col items-center text-center mb-5">
                            <div class="w-16 h-16 rounded-full bg-gradient-to-br from-[#1E3A8A] to-blue-700 flex items-center justify-center ring-2 ring-blue-500/30 mb-3">
                                <span class="text-white text-xl font-black">
                                    {{ collect(explode(' ', $membro->nome))->map(fn($p) => strtoupper(mb_substr($p,0,1)))->take(2)->join('') }}
                                </span>
                            </div>
                            <h2 class="text-base font-black text-slate-900 dark:text-white">{{ $membro->nome }}</h2>
                            <span class="mt-1 inline-flex items-center gap-1 px-2 py-0.5 rounded-md text-[10px] font-black uppercase tracking-widest
                                @if($statusGeral === 'multa') bg-red-100 text-red-700 dark:bg-red-900/30 dark:text-red-300
                                @elseif($statusGeral === 'devendo') bg-amber-100 text-amber-700 dark:bg-amber-900/30 dark:text-amber-300
                                @else bg-emerald-100 text-emerald-700 dark:bg-emerald-900/30 dark:text-emerald-300
                                @endif">
                                @if($statusGeral === 'multa') <i class="ph ph-coins"></i> Multa
                                @elseif($statusGeral === 'devendo') <i class="ph ph-warning"></i> Atrasado
                                @else <i class="ph ph-check"></i> Ativo
                                @endif
                            </span>
                        </div>

                        <div class="space-y-3">
                            <div class="flex items-start gap-3">
                                <i class="ph ph-envelope-simple text-slate-400 dark:text-gray-500 text-base shrink-0 mt-0.5"></i>
                                <div class="min-w-0">
                                    <p class="text-[10px] uppercase tracking-wider text-slate-500 dark:text-gray-500 font-bold">Email</p>
                                    <p class="text-sm text-slate-900 dark:text-white truncate">{{ $membro->email }}</p>
                                </div>
                            </div>
                            <div class="flex items-start gap-3">
                                <i class="ph ph-phone text-slate-400 dark:text-gray-500 text-base shrink-0 mt-0.5"></i>
                                <div>
                                    <p class="text-[10px] uppercase tracking-wider text-slate-500 dark:text-gray-500 font-bold">Telefone</p>
                                    <p class="text-sm text-slate-900 dark:text-white">{{ $membro->telefone ?? 'Não informado' }}</p>
                                </div>
                            </div>
                            <div class="flex items-start gap-3">
                                <i class="ph ph-identification-card text-slate-400 dark:text-gray-500 text-base shrink-0 mt-0.5"></i>
                                <div>
                                    <p class="text-[10px] uppercase tracking-wider text-slate-500 dark:text-gray-500 font-bold">CPF</p>
                                    <p class="text-sm text-slate-900 dark:text-white font-mono">{{ $membro->cpf ?? 'Não informado' }}</p>
                                </div>
                            </div>
                            <div class="flex items-start gap-3">
                                <i class="ph ph-map-pin text-slate-400 dark:text-gray-500 text-base shrink-0 mt-0.5"></i>
                                <div>
                                    <p class="text-[10px] uppercase tracking-wider text-slate-500 dark:text-gray-500 font-bold">Endereço</p>
                                    <p class="text-sm text-slate-900 dark:text-white">{{ $membro->endereco ?? 'Não informado' }}</p>
                                </div>
                            </div>
                            <div class="flex items-start gap-3">
                                <i class="ph ph-cake text-slate-400 dark:text-gray-500 text-base shrink-0 mt-0.5"></i>
                                <div>
                                    <p class="text-[10px] uppercase tracking-wider text-slate-500 dark:text-gray-500 font-bold">Nascimento</p>
                                    <p class="text-sm text-slate-900 dark:text-white">
                                        @if($membro->data_nascimento)
                                            @php try { $nasc = $membro->data_nascimento instanceof \Carbon\Carbon ? $membro->data_nascimento->format('d/m/Y') : \Carbon\Carbon::parse($membro->data_nascimento)->format('d/m/Y'); } catch(\Exception $e) { $nasc = '—'; } @endphp
                                            {{ $nasc }}
                                        @else Não informado @endif
                                    </p>
                                </div>
                            </div>
                            <div class="flex items-start gap-3 pt-3 border-t border-slate-100 dark:border-white/5">
                                <i class="ph ph-ticket text-blue-400 text-base shrink-0 mt-0.5"></i>
                                <div>
                                    <p class="text-[10px] uppercase tracking-wider text-slate-500 dark:text-gray-500 font-bold">Carteirinha</p>
                                    <p class="text-sm font-mono font-bold text-blue-600 dark:text-blue-400">{{ $membro->numero_carteirinha }}</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Stats cards --}}
                    <div class="grid grid-cols-2 gap-3">
                        <div class="bg-white dark:bg-[#0d1420] border border-blue-200/60 dark:border-blue-900/30 rounded-md p-4 text-center">
                            <i class="ph ph-book-open text-blue-500 text-xl mb-1"></i>
                            <p class="text-2xl font-black text-blue-900 dark:text-blue-100">{{ $ativos }}</p>
                            <p class="text-[10px] uppercase tracking-wider text-blue-700 dark:text-blue-400 font-bold mt-0.5">Ativos</p>
                        </div>
                        <div class="bg-white dark:bg-[#0d1420] border border-emerald-200/60 dark:border-emerald-900/30 rounded-md p-4 text-center">
                            <i class="ph ph-check-circle text-emerald-500 text-xl mb-1"></i>
                            <p class="text-2xl font-black text-emerald-900 dark:text-emerald-100">{{ $encerrados }}</p>
                            <p class="text-[10px] uppercase tracking-wider text-emerald-700 dark:text-emerald-400 font-bold mt-0.5">Completos</p>
                        </div>
                        <div class="bg-white dark:bg-[#0d1420] border border-red-200/60 dark:border-red-900/30 rounded-md p-4 text-center">
                            <i class="ph ph-coins text-red-500 text-xl mb-1"></i>
                            <p class="text-2xl font-black text-red-900 dark:text-red-100">R${{ number_format($multasTotal, 0, ',', '.') }}</p>
                            <p class="text-[10px] uppercase tracking-wider text-red-700 dark:text-red-400 font-bold mt-0.5">Multa</p>
                        </div>
                        <div class="bg-white dark:bg-[#0d1420] border @if($totalAtrasados > 0) border-amber-200/60 dark:border-amber-900/30 @else border-slate-200 dark:border-white/10 @endif rounded-md p-4 text-center">
                            <i class="ph ph-clock-countdown @if($totalAtrasados > 0) text-amber-500 @else text-slate-400 @endif text-xl mb-1"></i>
                            <p class="text-2xl font-black @if($totalAtrasados > 0) text-amber-900 dark:text-amber-100 @else text-slate-900 dark:text-white @endif">{{ $totalAtrasados }}</p>
                            <p class="text-[10px] uppercase tracking-wider @if($totalAtrasados > 0) text-amber-700 dark:text-amber-400 @else text-slate-500 dark:text-gray-500 @endif font-bold mt-0.5">Atrasados</p>
                        </div>
                    </div>

                    {{-- Actions --}}
                    <div class="bg-white dark:bg-[#0d1420] border border-slate-200 dark:border-white/5 rounded-md p-4 space-y-2">
                        <p class="text-[10px] uppercase tracking-wider text-slate-500 dark:text-gray-500 font-black mb-3">Ações Rápidas</p>
                        <button type="button" id="openMsgModalBtn"
                            data-id="{{ $membro->id }}"
                            data-nome="{{ $membro->nome }}"
                            class="w-full inline-flex items-center justify-center gap-2 px-4 py-2.5 rounded-md bg-purple-600 border border-purple-500 text-white text-[11px] font-black uppercase tracking-widest hover:bg-purple-700 transition">
                            <i class="ph ph-envelope-simple text-sm"></i>
                            Enviar Mensagem
                        </button>
                        <a href="{{ route('admin.membros.perfis') }}" class="w-full inline-flex items-center justify-center gap-2 px-4 py-2.5 rounded-md bg-slate-100 dark:bg-white/5 border border-slate-200 dark:border-white/10 text-slate-700 dark:text-gray-300 text-[11px] font-bold uppercase tracking-widest hover:bg-slate-200 dark:hover:bg-white/10 transition">
                            <i class="ph ph-arrow-left text-sm"></i>
                            Voltar à Lista
                        </a>
                    </div>
                </div>

                {{-- RIGHT: Loan history 2/3 --}}
                <div class="lg:col-span-2">
                    <div class="bg-white dark:bg-[#0d1420] border border-slate-200 dark:border-white/5 rounded-md overflow-hidden">
                        <div class="px-5 py-4 border-b border-slate-100 dark:border-white/5 flex items-center justify-between">
                            <div class="flex items-center gap-2">
                                <i class="ph ph-book-bookmark text-[#F59E0B] text-sm"></i>
                                <h3 class="text-sm font-black text-slate-900 dark:text-white uppercase tracking-widest">Histórico de Empréstimos</h3>
                            </div>
                            <span class="text-[10px] text-slate-400 dark:text-gray-500 bg-slate-100 dark:bg-white/5 px-2 py-0.5 rounded-md font-bold">
                                {{ $emprestimos->count() }} registro(s)
                            </span>
                        </div>

                        @if($emprestimos->count() > 0)
                            <div class="divide-y divide-slate-100 dark:divide-white/5">
                                @foreach($emprestimos as $emp)
                                    @php
                                        $isAtrasado = $emp->isAtrasado();
                                        $statusMap = [
                                            'retirado'             => ['label' => 'Retirado',    'cls' => 'bg-blue-100 text-blue-800 dark:bg-blue-900/40 dark:text-blue-200',    'bar' => 'bg-blue-500'],
                                            'em_uso'               => ['label' => 'Em uso',      'cls' => 'bg-indigo-100 text-indigo-800 dark:bg-indigo-900/40 dark:text-indigo-200','bar' => 'bg-indigo-500'],
                                            'devolucao_solicitada' => ['label' => 'Dev. Solicit.','cls' => 'bg-sky-100 text-sky-800 dark:bg-sky-900/40 dark:text-sky-200',         'bar' => 'bg-sky-500'],
                                            'encerrado'            => ['label' => 'Encerrado',   'cls' => 'bg-emerald-100 text-emerald-800 dark:bg-emerald-900/40 dark:text-emerald-200','bar' => 'bg-emerald-500'],
                                            'devolvido'            => ['label' => 'Devolvido',   'cls' => 'bg-emerald-100 text-emerald-800 dark:bg-emerald-900/40 dark:text-emerald-200','bar' => 'bg-emerald-500'],
                                            'rejeitado'            => ['label' => 'Rejeitado',   'cls' => 'bg-red-100 text-red-800 dark:bg-red-900/40 dark:text-red-200',         'bar' => 'bg-red-500'],
                                            'solicitado'           => ['label' => 'Solicitado',  'cls' => 'bg-slate-100 text-slate-800 dark:bg-slate-800 dark:text-slate-200',    'bar' => 'bg-slate-400'],
                                        ];
                                        $st = $statusMap[$emp->status] ?? ['label' => ucfirst(str_replace('_',' ',$emp->status)), 'cls' => 'bg-slate-100 text-slate-800 dark:bg-slate-800 dark:text-slate-200', 'bar' => 'bg-slate-400'];
                                    @endphp
                                    <div class="p-4 flex gap-4 {{ $isAtrasado ? 'bg-amber-50/50 dark:bg-amber-900/5' : '' }}">
                                        {{-- Book cover mini --}}
                                        <div class="w-12 h-16 rounded-md overflow-hidden bg-slate-100 dark:bg-white/10 shrink-0 flex items-center justify-center">
                                            @if($emp->livro?->capa)
                                                <img src="{{ asset('storage/' . $emp->livro->capa) }}" alt="{{ $emp->livro?->titulo }}" class="w-full h-full object-cover">
                                            @else
                                                <i class="ph ph-book text-slate-400 text-base"></i>
                                            @endif
                                        </div>
                                        {{-- Content --}}
                                        <div class="flex-1 min-w-0">
                                            <div class="flex items-start justify-between gap-2 mb-1.5">
                                                <h4 class="text-sm font-bold text-slate-900 dark:text-white truncate">{{ $emp->livro?->titulo ?? 'Livro não encontrado' }}</h4>
                                                <div class="flex items-center gap-1 shrink-0">
                                                    @if($isAtrasado)
                                                        <span class="inline-flex items-center gap-0.5 px-1.5 py-0.5 rounded-md text-[9px] font-black uppercase tracking-widest bg-amber-200 text-amber-900 dark:bg-amber-900/50 dark:text-amber-200">
                                                            <i class="ph ph-clock-countdown"></i> Atrasado
                                                        </span>
                                                    @endif
                                                    <span class="inline-flex items-center px-1.5 py-0.5 rounded-md text-[9px] font-black uppercase tracking-widest {{ $st['cls'] }}">
                                                        {{ $st['label'] }}
                                                    </span>
                                                </div>
                                            </div>
                                            <p class="text-[11px] text-slate-500 dark:text-gray-500 mb-2">
                                                {{ $emp->livro?->autor?->nome ?? 'Autor desconhecido' }}
                                                @if($emp->livro?->categoria)
                                                    · <span class="text-blue-500">{{ $emp->livro->categoria }}</span>
                                                @endif
                                            </p>
                                            <div class="grid grid-cols-2 md:grid-cols-4 gap-x-4 gap-y-1">
                                                <div>
                                                    <p class="text-[10px] text-slate-500 dark:text-gray-500">Empréstimo</p>
                                                    <p class="text-xs font-semibold text-slate-900 dark:text-white">{{ $emp->data_emprestimo?->format('d/m/Y') ?? '—' }}</p>
                                                </div>
                                                <div>
                                                    <p class="text-[10px] text-slate-500 dark:text-gray-500">Prev. devolução</p>
                                                    <p class="text-xs font-semibold {{ $isAtrasado ? 'text-amber-600 dark:text-amber-400' : 'text-slate-900 dark:text-white' }}">{{ $emp->data_devolucao_prevista?->format('d/m/Y') ?? '—' }}</p>
                                                </div>
                                                <div>
                                                    <p class="text-[10px] text-slate-500 dark:text-gray-500">Devolvido em</p>
                                                    <p class="text-xs font-semibold text-slate-900 dark:text-white">{{ $emp->data_devolucao_real?->format('d/m/Y') ?? 'Pendente' }}</p>
                                                </div>
                                                <div>
                                                    <p class="text-[10px] text-slate-500 dark:text-gray-500">Multa</p>
                                                    @if($emp->valor_multa > 0)
                                                        <p class="text-xs font-bold text-red-600 dark:text-red-400">R$ {{ number_format($emp->valor_multa, 2, ',', '.') }}</p>
                                                    @else
                                                        <p class="text-xs font-semibold text-emerald-600 dark:text-emerald-400">Sem multa</p>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="py-16 flex flex-col items-center justify-center text-center">
                                <i class="ph ph-books text-slate-300 dark:text-slate-700 text-5xl mb-3"></i>
                                <p class="text-slate-400 dark:text-slate-600 font-bold">Nenhum empréstimo registrado</p>
                                <p class="text-slate-400 dark:text-slate-600 text-sm mt-1">Este membro ainda não realizou nenhum empréstimo</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- MODAL ENVIAR MENSAGEM --}}
    <div id="messageModal" class="fixed inset-0 z-50 hidden items-center justify-center bg-black/60 backdrop-blur-sm">
        <div class="w-full max-w-lg mx-4">
            <div class="bg-white dark:bg-[#0d1420] rounded-md p-6 border border-slate-200 dark:border-white/10 shadow-2xl">
                <div class="flex items-center justify-between mb-5">
                    <div class="flex items-center gap-2">
                        <i class="ph ph-envelope-simple text-purple-500 text-lg"></i>
                        <h4 class="text-base font-black text-slate-900 dark:text-white">Enviar Mensagem</h4>
                    </div>
                    <button type="button" id="closeModal" class="w-8 h-8 rounded-md bg-slate-100 dark:bg-white/5 text-slate-500 hover:text-slate-900 dark:hover:text-white transition flex items-center justify-center">
                        <i class="ph ph-x text-sm"></i>
                    </button>
                </div>
                <p class="text-[11px] text-slate-500 dark:text-gray-500 mb-4 uppercase tracking-wider">Para: <strong class="text-slate-900 dark:text-white normal-case tracking-normal text-sm">{{ $membro->nome }}</strong></p>
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
                        <button type="button" id="cancelMsgBtn" class="px-4 py-2 rounded-md bg-slate-100 dark:bg-white/5 border border-slate-200 dark:border-white/10 text-slate-700 dark:text-gray-300 text-[11px] font-bold uppercase tracking-widest hover:bg-slate-200 dark:hover:bg-white/10 transition">Cancelar</button>
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
        const modal = document.getElementById('messageModal');
        const form  = document.getElementById('messageForm');

        function openModal() { modal.classList.remove('hidden'); modal.classList.add('flex'); }
        function closeModal() { modal.classList.remove('flex'); modal.classList.add('hidden'); }

        document.getElementById('openMsgModalBtn')?.addEventListener('click', openModal);
        document.getElementById('closeModal')?.addEventListener('click', closeModal);
        document.getElementById('cancelMsgBtn')?.addEventListener('click', closeModal);
        modal.addEventListener('click', e => { if (e.target === modal) closeModal(); });

        form?.addEventListener('submit', async e => {
            e.preventDefault();
            const fd = new FormData(form);
            try {
                const res = await fetch(`/admin/membros/{{ $membro->id }}/message`, {
                    method: 'POST',
                    headers: { 'X-CSRF-TOKEN': fd.get('_token'), 'Accept': 'application/json', 'Content-Type': 'application/json' },
                    body: JSON.stringify({ subject: fd.get('subject'), message: fd.get('message') }),
                });
                if (res.ok) {
                    closeModal();
                    if (typeof Swal !== 'undefined') {
                        Swal.mixin({ toast: true, position: 'top-end', showConfirmButton: false, timer: 3000, background: '#0d1420', color: '#fff' })
                            .fire({ icon: 'success', title: 'Mensagem enviada com sucesso!' });
                    }
                }
            } catch(err) { console.error(err); }
        });
    });
    </script>

</x-app-layout>
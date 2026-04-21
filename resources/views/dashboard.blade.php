<x-app-layout>

    {{-- ══ LIBS ══ --}}
    <script src="https://cdn.jsdelivr.net/npm/gsap@3.12.5/dist/gsap.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/gsap@3.12.5/dist/ScrollTrigger.min.js"></script>
    <link  rel="stylesheet" href="https://cdn.jsdelivr.net/npm/tom-select@2.3.1/dist/css/tom-select.min.css">
    <script src="https://cdn.jsdelivr.net/npm/tom-select@2.3.1/dist/js/tom-select.complete.min.js"></script>

    {{-- ══ ESTILOS ══ --}}
    <style>
        /* ── Hero: ocupa até a borda e funde com o header ── */
        .hero-band {
            position: relative;
            background-color: #080d14;
            background-image:
                radial-gradient(ellipse 80% 60% at 10% 100%, rgba(30,58,138,.28) 0%, transparent 60%),
                radial-gradient(ellipse 50% 50% at 90% 0%,   rgba(245,158,11,.08) 0%, transparent 55%),
                url("data:image/svg+xml,%3Csvg width='40' height='40' viewBox='0 0 40 40' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='%23ffffff' fill-opacity='0.012' fill-rule='evenodd'%3E%3Cpath d='M0 40L40 0H20L0 20M40 40V20L20 40'/%3E%3C/g%3E%3C/svg%3E");
            margin: 0 -1rem;
            padding: 2.5rem 1rem 5rem;
        }
        @media(min-width:640px)  { .hero-band { margin:0 -1.5rem; padding-left:1.5rem; padding-right:1.5rem; } }
        @media(min-width:1024px) { .hero-band { margin:0 -2rem;   padding-left:2rem;   padding-right:2rem; padding-bottom:6rem; } }

        /* Degradê de saída do hero para o conteúdo */
        .hero-band::after {
            content:''; position:absolute; bottom:0; left:0; right:0; height:100px;
            background: linear-gradient(to bottom, transparent 0%, #0f172a 100%);
            pointer-events:none;
        }

        /* ── KPI pills ── */
        .stat-pill {
            display:flex; flex-direction:column; align-items:center; gap:3px;
            padding:14px 22px; background:rgba(255,255,255,.04);
            border:1px solid rgba(255,255,255,.07); border-radius:16px; min-width:108px;
            transition:background .2s, border-color .2s, transform .2s; position:relative; z-index:1;
        }
        .stat-pill:hover { background:rgba(30,58,138,.22); border-color:rgba(30,58,138,.45); transform:translateY(-3px); }

        /* ── Section numbers ── */
        .section-num {
            font-family:'Merriweather',serif; font-size:5rem; font-weight:900; line-height:1;
            color:transparent; -webkit-text-stroke:1px rgba(255,255,255,.05); user-select:none; pointer-events:none;
        }

        /* ── Book cards ── */
        .book-cover-wrap { width:100%; height:220px; overflow:hidden; background:#080d14; position:relative; }
        .book-cover-wrap img { width:100%; height:100%; object-fit:cover; transition:transform .5s cubic-bezier(.4,0,.2,1); display:block; }
        .book-card:hover .book-cover-wrap img { transform:scale(1.06); }

        /* ── Author avatar ── */
        .author-avatar {
            width:68px; height:68px; border-radius:50%; overflow:hidden;
            border:2px solid rgba(37,99,235,.5); background:#111827;
            margin:-34px auto 10px; position:relative; z-index:2; flex-shrink:0;
        }

        /* ── Swiper ── */
        .swiper { overflow:hidden; }
        .swiper-wrapper { display:flex; align-items:stretch; }
        .swiper-slide { height:auto; flex-shrink:0; }

        /* ── Content area (fundo mais claro) ── */
        .content-area { background:#0f172a; margin:0 -1rem; padding:2.5rem 1rem; }
        @media(min-width:640px)  { .content-area { margin:0 -1.5rem; padding:2.5rem 1.5rem; } }
        @media(min-width:1024px) { .content-area { margin:0 -2rem;   padding:2.5rem 2rem; } }

        /* ── Filter bar ── */
        .filter-label { display:block; font-size:.625rem; font-weight:700; text-transform:uppercase; letter-spacing:.12em; color:#6b7280; margin-bottom:6px; }
        .filter-input-wrap { position:relative; display:flex; align-items:center; }
        .filter-icon { position:absolute; left:10px; font-size:1rem; color:#4b5563; pointer-events:none; z-index:1; }
        .filter-input { width:100%; background:#080d14; border:1px solid rgba(255,255,255,.09); color:#e5e7eb; border-radius:8px; padding:8px 12px 8px 34px; font-size:.8125rem; transition:border-color .2s,box-shadow .2s; outline:none; }
        .filter-input:focus { border-color:#1E3A8A; box-shadow:0 0 0 3px rgba(30,58,138,.25); }
        .filter-chip { display:inline-flex; align-items:center; gap:5px; padding:3px 10px 3px 12px; border-radius:999px; background:rgba(30,58,138,.3); border:1px solid rgba(30,58,138,.5); color:#93c5fd; font-size:.625rem; font-weight:700; text-transform:uppercase; letter-spacing:.08em; cursor:pointer; transition:background .15s; }
        .filter-chip:hover { background:rgba(30,58,138,.5); }

        /* ── Tom Select dark theme ── */
        .ts-wrapper.single .ts-control,.ts-wrapper .ts-control { background:#080d14!important; border:1px solid rgba(255,255,255,.09)!important; border-radius:8px!important; color:#e5e7eb!important; padding:8px 36px 8px 12px!important; min-height:0!important; box-shadow:none!important; cursor:pointer; transition:border-color .2s,box-shadow .2s!important; }
        .ts-wrapper.single.focus .ts-control,.ts-wrapper.focus .ts-control { border-color:#1E3A8A!important; box-shadow:0 0 0 3px rgba(30,58,138,.25)!important; }
        .ts-wrapper .ts-control input { color:#e5e7eb!important; font-size:.8125rem!important; }
        .ts-wrapper .ts-control input::placeholder { color:#4b5563!important; }
        .ts-wrapper.single .ts-control::after { border-color:#6b7280 transparent transparent!important; }
        .ts-dropdown { background:#0d1420!important; border:1px solid rgba(255,255,255,.09)!important; border-radius:10px!important; margin-top:4px!important; box-shadow:0 20px 40px rgba(0,0,0,.6)!important; }
        .ts-dropdown .option { color:#9ca3af!important; padding:9px 12px!important; font-size:.8125rem!important; border-radius:6px!important; margin:2px 4px!important; width:calc(100% - 8px)!important; }
        .ts-dropdown .option:hover,.ts-dropdown .option.active { background:rgba(30,58,138,.4)!important; color:#fff!important; }
        .ts-dropdown .option.selected { background:rgba(30,58,138,.25)!important; color:#93c5fd!important; }
        .ts-dropdown-content { padding:4px!important; }

        .acervo-card { transition:opacity .25s,transform .25s; }
        .bestseller-badge { position:absolute; top:8px; left:8px; padding:2px 8px; border-radius:4px; background:#F59E0B; color:#0f172a; font-size:.5625rem; font-weight:900; text-transform:uppercase; letter-spacing:.06em; }
        #empty-state { display:none; }
        [x-cloak] { display:none!important; }
    </style>

    {{-- ══ PHP DATA ══ --}}
    @php
        $totalLivros        = $livros->count();
        $totalAutores       = $autores->count();
        $emprestimosAtivos  = \App\Models\Emprestimos::whereNull('data_devolucao_real')->count();
        $totalMembros       = \App\Models\Membros::count();
        $nomeCompleto = auth()->check() ? (auth()->user()->name ?? auth()->user()->nome ?? 'Visitante') : 'Visitante';
        $primeiroNome = explode(' ', $nomeCompleto)[0];
        $iniciais = collect(explode(' ', $nomeCompleto))->map(fn($p) => strtoupper(mb_substr($p,0,1)))->take(2)->join('');
        $hora     = now()->hour;
        $saudacao = $hora < 12 ? 'Bom dia' : ($hora < 18 ? 'Boa tarde' : 'Boa noite');
        $isAdmin  = auth()->check() && in_array(auth()->user()->tipo_usuario, ['gerente','bibliotecario']);
        $categorias = $livros->pluck('categoria')->filter()->unique()->sort()->values();
    @endphp

    {{-- ══════════════════════════════════════════════════════
         HEADER SLOT — slim, transparente, integrado ao hero
         ══════════════════════════════════════════════════════ --}}
    <x-slot name="header">
        <div class="flex items-center justify-between w-full">

            {{-- Esquerda: logo micro + separador + contexto --}}
            <div class="flex items-center gap-3">
                <a href="{{ route('dashboard') }}" class="hover:opacity-80 transition shrink-0">
                    <span class="font-black text-[15px] text-white tracking-tighter font-serif leading-none">
                        Biblio<span class="text-[#F59E0B]">Tech</span>
                    </span>
                </a>
                <span class="text-white/15 text-xl font-thin select-none">/</span>
                <span class="text-[11px] font-medium text-gray-600 tracking-wide">Acervo</span>
            </div>

            {{-- Direita: ações rápidas + usuário --}}
            <div class="flex items-center gap-2">
                @auth
                    @if(in_array(auth()->user()->tipo_usuario, ['gerente','bibliotecario']))
                    <div class="hidden sm:flex items-center gap-1 mr-1">
                        @if(auth()->user()->tipo_usuario === 'gerente')
                        <a href="{{ route('bibliotecarios.create') }}"
                           class="flex items-center gap-1.5 px-2.5 py-1.5 rounded-lg text-[11px] font-semibold
                                  text-amber-400/60 hover:text-amber-400 hover:bg-amber-500/10
                                  border border-transparent hover:border-amber-500/20 transition-all">
                            <i class="ph ph-user-plus text-xs"></i>
                            <span class="hidden lg:inline">Bibliotecário</span>
                        </a>
                        @endif
                        <a href="{{ route('livros.create') }}"
                           class="flex items-center gap-1.5 px-2.5 py-1.5 rounded-lg text-[11px] font-semibold
                                  text-blue-400/60 hover:text-blue-400 hover:bg-blue-500/10
                                  border border-transparent hover:border-blue-500/20 transition-all">
                            <i class="ph ph-book-bookmark text-xs"></i>
                            <span class="hidden lg:inline">Novo Livro</span>
                        </a>
                        <a href="{{ route('autores.create') }}"
                           class="flex items-center gap-1.5 px-2.5 py-1.5 rounded-lg text-[11px] font-semibold
                                  text-blue-400/60 hover:text-blue-400 hover:bg-blue-500/10
                                  border border-transparent hover:border-blue-500/20 transition-all">
                            <i class="ph ph-user-plus text-xs"></i>
                            <span class="hidden lg:inline">Novo Autor</span>
                        </a>
                    </div>
                    @endif
                    <div class="h-5 w-px bg-white/10 hidden sm:block"></div>
                    <div class="flex items-center gap-2.5">
                        <div class="hidden sm:flex flex-col items-end leading-none gap-0.5">
                            <span class="text-[11px] font-semibold text-gray-300">{{ $primeiroNome }}</span>
                            <span class="text-[9px] font-bold uppercase tracking-widest {{ $isAdmin ? 'text-amber-500/60' : 'text-blue-500/60' }}">
                                {{ ucfirst(auth()->user()->tipo_usuario) }}
                            </span>
                        </div>
                        <div class="w-8 h-8 rounded-lg bg-gradient-to-br from-[#1E3A8A] to-blue-700
                                    flex items-center justify-center ring-1 ring-blue-500/30
                                    shadow-md shadow-blue-900/40 shrink-0">
                            <span class="text-white text-[10px] font-black tracking-tight select-none">{{ $iniciais }}</span>
                        </div>
                    </div>
                @endauth
            </div>

        </div>
    </x-slot>

    {{-- ══════════════════════════════════════════════════════
         HERO BAND — mesmo bg do header → conexão perfeita
         ══════════════════════════════════════════════════════ --}}
    <div class="hero-band">
        <div class="max-w-7xl mx-auto">
            <div class="flex flex-col md:flex-row items-start md:items-end justify-between gap-8 relative z-[1]">

                <div id="hero-txt">
                    <p class="text-[11px] font-bold uppercase tracking-[.18em] text-[#F59E0B] mb-3 flex items-center gap-2">
                        <span class="inline-block w-6 h-px bg-[#F59E0B]"></span>
                        {{ $saudacao }}
                    </p>
                    <h2 class="text-4xl md:text-5xl font-black text-white tracking-tight font-serif leading-[1.05]">
                        @if($isAdmin)
                            Painel do<br><span class="text-[#1E3A8A]">Acervo</span>
                        @else
                            Olá,<br><span class="text-[#F59E0B]">{{ $primeiroNome }}</span>
                        @endif
                    </h2>
                    <p class="text-slate-500 text-sm mt-3 max-w-sm leading-relaxed">
                        @if($isAdmin)
                            Gerencie títulos, autores, empréstimos e membros num só lugar.
                        @else
                            Descubra sua próxima leitura entre
                            <span class="text-gray-300 font-semibold">{{ $totalLivros }}</span>
                            títulos disponíveis.
                        @endif
                    </p>
                </div>

                <div id="hero-stats" class="flex flex-wrap gap-3">
                    <div class="stat-pill">
                        <i class="ph ph-books text-blue-400 text-xl"></i>
                        <span class="text-[10px] font-bold uppercase tracking-widest text-slate-600 mt-1">Livros</span>
                        <span class="text-2xl font-black text-white font-serif counter" data-target="{{ $totalLivros }}">0</span>
                    </div>
                    <div class="stat-pill">
                        <i class="ph ph-pen-nib text-purple-400 text-xl"></i>
                        <span class="text-[10px] font-bold uppercase tracking-widest text-slate-600 mt-1">Autores</span>
                        <span class="text-2xl font-black text-white font-serif counter" data-target="{{ $totalAutores }}">0</span>
                    </div>
                    @if($isAdmin)
                    <div class="stat-pill">
                        <i class="ph ph-handshake text-amber-400 text-xl"></i>
                        <span class="text-[10px] font-bold uppercase tracking-widest text-slate-600 mt-1">Em Aberto</span>
                        <span class="text-2xl font-black font-serif counter {{ $emprestimosAtivos > 0 ? 'text-amber-400' : 'text-white' }}"
                              data-target="{{ $emprestimosAtivos }}">0</span>
                    </div>
                    <div class="stat-pill">
                        <i class="ph ph-users text-emerald-400 text-xl"></i>
                        <span class="text-[10px] font-bold uppercase tracking-widest text-slate-600 mt-1">Membros</span>
                        <span class="text-2xl font-black text-white font-serif counter" data-target="{{ $totalMembros }}">0</span>
                    </div>
                    @endif
                </div>

            </div>
        </div>
    </div>

    {{-- ══ CONTENT AREA ══ --}}
    <div class="content-area">
    <div class="max-w-7xl mx-auto space-y-16">

        {{-- ── Recomendações ── --}}
        @if(isset($recomendados) && $recomendados->count())
        <section class="gs-section">
            <div class="flex items-end justify-between mb-7">
                <div class="flex items-end gap-4">
                    <span class="section-num select-none">★</span>
                    <div class="pb-1">
                        <p class="text-[10px] font-bold uppercase tracking-[.15em] text-amber-500 mb-1">Curadoria pessoal</p>
                        <h2 class="text-xl md:text-2xl font-black text-white font-serif">Recomendações para você</h2>
                    </div>
                </div>
            </div>
            <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-4">
                @foreach($recomendados as $livro)
                <div class="book-card group bg-[#0d1420] rounded-xl overflow-hidden border border-white/5 hover:border-amber-500/30 transition-all flex flex-col">
                    <a href="{{ route('livros.show', $livro->id) }}" class="flex-grow flex flex-col">
                        <div class="book-cover-wrap">
                            @if($livro->capa)
                                <img src="{{ asset('storage/' . $livro->capa) }}" alt="{{ $livro->titulo }}" loading="lazy">
                            @else
                                <div class="w-full h-full flex items-center justify-center bg-gradient-to-br from-amber-900/20 to-[#080d14]">
                                    <i class="ph ph-book text-3xl text-amber-900/40"></i>
                                </div>
                            @endif
                        </div>
                        <div class="p-3 flex-grow">
                            <span class="text-[9px] font-bold uppercase tracking-widest text-amber-500/70">{{ $livro->categoria ?? 'Geral' }}</span>
                            <h4 class="text-white text-xs font-semibold truncate mt-0.5 group-hover:text-amber-400 transition-colors">{{ $livro->titulo }}</h4>
                            <p class="text-gray-500 text-[11px] truncate mt-0.5">{{ $livro->autor->nome ?? '' }}</p>
                        </div>
                    </a>
                </div>
                @endforeach
            </div>
        </section>
        @endif

        {{-- ── Últimos Lançamentos ── --}}
        <section class="gs-section">
            <div class="flex items-end justify-between mb-7">
                <div class="flex items-end gap-4">
                    <span class="section-num select-none">01</span>
                    <div class="pb-1">
                        <p class="text-[10px] font-bold uppercase tracking-[.15em] text-blue-500 mb-1">Acervo</p>
                        <h2 class="text-xl md:text-2xl font-black text-white font-serif">Últimos Lançamentos</h2>
                    </div>
                </div>
                <div class="flex gap-2 pb-1">
                    <button id="swiper-livros-prev" class="w-9 h-9 shrink-0 rounded-lg flex items-center justify-center bg-white/5 border border-white/10 text-gray-400 hover:bg-[#1E3A8A] hover:border-[#1E3A8A] hover:text-white transition-all"><i class="ph ph-caret-left"></i></button>
                    <button id="swiper-livros-next" class="w-9 h-9 shrink-0 rounded-lg flex items-center justify-center bg-white/5 border border-white/10 text-gray-400 hover:bg-[#1E3A8A] hover:border-[#1E3A8A] hover:text-white transition-all"><i class="ph ph-caret-right"></i></button>
                </div>
            </div>
            <div class="swiper swiperLivros">
                <div class="swiper-wrapper">
                    @foreach($livros as $livro)
                    <div class="swiper-slide">
                        <div class="book-card group bg-[#0d1420] rounded-xl overflow-hidden border border-white/5 hover:border-[#1E3A8A]/50 transition-all flex flex-col h-full">
                            <a href="{{ route('livros.show', $livro->id) }}" class="flex-grow flex flex-col">
                                <div class="book-cover-wrap">
                                    @if($livro->capa)
                                        <img src="{{ asset('storage/' . $livro->capa) }}" alt="{{ $livro->titulo }}" loading="lazy">
                                    @else
                                        <div class="w-full h-full flex items-center justify-center bg-gradient-to-br from-blue-900/20 to-[#080d14]"><i class="ph ph-book text-3xl text-blue-900/40"></i></div>
                                    @endif
                                    <div class="absolute inset-0 bg-[#080d14]/94 opacity-0 group-hover:opacity-100 transition-all duration-300 flex flex-col items-center justify-center text-center p-5 gap-3">
                                        <p class="text-gray-300 text-[11px] leading-relaxed line-clamp-5">{{ $livro->sinopse ?? 'Sinopse não disponível.' }}</p>
                                        <span class="px-4 py-1.5 bg-[#1E3A8A] text-white rounded-lg text-[11px] font-semibold mt-1 shrink-0">Ver obra</span>
                                    </div>
                                </div>
                                <div class="p-4 flex-grow flex flex-col gap-1">
                                    <div class="flex items-center justify-between">
                                        <span class="text-[9px] font-bold uppercase tracking-widest px-2 py-0.5 bg-[#1E3A8A]/20 text-blue-400 rounded border border-[#1E3A8A]/30">{{ $livro->categoria ?? 'Geral' }}</span>
                                        <span class="text-[10px] text-gray-600">{{ \Carbon\Carbon::parse($livro->data_publicacao)->format('Y') }}</span>
                                    </div>
                                    <h4 class="text-white text-sm font-semibold truncate group-hover:text-blue-400 transition-colors">{{ $livro->titulo }}</h4>
                                    <p class="text-gray-500 text-xs truncate flex items-center gap-1">
                                        <i class="ph ph-pen-nib text-xs shrink-0"></i>
                                        <a href="{{ route('autores.show', $livro->autor->id) }}" class="hover:text-[#F59E0B] transition truncate">{{ $livro->autor->nome }}</a>
                                    </p>
                                </div>
                            </a>
                            @if($isAdmin)
                            <div class="px-4 pb-3 pt-2 border-t border-white/5 flex items-center justify-between shrink-0">
                                <a href="{{ route('livros.edit', $livro->id) }}" class="text-[11px] text-gray-500 hover:text-[#F59E0B] transition flex items-center gap-1"><i class="ph ph-pencil-simple"></i> Editar</a>
                                <form action="{{ route('livros.destroy', $livro->id) }}" method="POST" class="form-delete">
                                    @csrf @method('DELETE')
                                    <button type="button" class="btn-delete text-[11px] text-gray-600 hover:text-red-400 transition flex items-center gap-1"><i class="ph ph-trash"></i> Excluir</button>
                                </form>
                            </div>
                            @endif
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </section>

        {{-- ══ EXPLORAR ACERVO ══ --}}
        <section id="acervo-section" class="gs-section">
            <div class="flex items-end justify-between mb-7">
                <div class="flex items-end gap-4">
                    <span class="section-num select-none">02</span>
                    <div class="pb-1">
                        <p class="text-[10px] font-bold uppercase tracking-[.15em] text-emerald-500 mb-1">Busca & Filtros</p>
                        <h2 class="text-xl md:text-2xl font-black text-white font-serif">Explorar Acervo</h2>
                    </div>
                </div>
                <div class="pb-1 flex items-center gap-3">
                    <span id="results-count" class="text-[11px] text-gray-600 font-medium tabular-nums"></span>
                    <button id="clear-all-btn" class="hidden text-[10px] font-bold uppercase tracking-widest text-red-400/70 hover:text-red-400 transition px-2 py-1 rounded-md hover:bg-red-500/10">
                        <i class="ph ph-x mr-1"></i>Limpar
                    </button>
                </div>
            </div>
            <div class="mb-8 p-4 sm:p-5 bg-[#0d1420] rounded-2xl border border-white/[.06] shadow-xl shadow-black/30" id="filter-bar">
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                    <div class="sm:col-span-2 lg:col-span-1">
                        <label class="filter-label" for="filter-search">Buscar</label>
                        <div class="filter-input-wrap">
                            <i class="ph ph-magnifying-glass filter-icon"></i>
                            <input type="text" id="filter-search" placeholder="Título, autor..." class="filter-input" autocomplete="off">
                        </div>
                    </div>
                    <div>
                        <label class="filter-label" for="filter-categoria">Categoria</label>
                        <select id="filter-categoria" placeholder="Todas...">
                            <option value="">Todas as categorias</option>
                            @foreach($categorias as $cat)
                            <option value="{{ $cat }}">{{ $cat }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="filter-label" for="filter-autor">Autor</label>
                        <select id="filter-autor" placeholder="Todos...">
                            <option value="">Todos os autores</option>
                            @foreach($autores as $autor)
                            <option value="{{ $autor->id }}">{{ $autor->nome }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="filter-label" for="filter-sort">Ordenar por</label>
                        <select id="filter-sort">
                            <option value="recente">Mais Recentes</option>
                            <option value="titulo_az">Título A → Z</option>
                            <option value="titulo_za">Título Z → A</option>
                            <option value="bestseller">Bestsellers</option>
                        </select>
                    </div>
                </div>
                <div id="active-filters" class="mt-4 flex flex-wrap gap-2" style="display:none!important"></div>
            </div>
            <div id="acervo-grid" class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-4">
                @foreach($livros as $livro)
                <div class="book-card acervo-card group bg-[#0d1420] rounded-xl overflow-hidden border border-white/5 hover:border-emerald-500/30 transition-all flex flex-col"
                     data-titulo="{{ strtolower($livro->titulo) }}"
                     data-autor-nome="{{ strtolower($livro->autor->nome ?? '') }}"
                     data-autor-id="{{ $livro->autor_id }}"
                     data-categoria="{{ $livro->categoria ?? 'Geral' }}"
                     data-bestseller="{{ $livro->e_bestseller ? '1' : '0' }}"
                     data-data="{{ $livro->data_publicacao ?? '0000-00-00' }}">
                    <a href="{{ route('livros.show', $livro->id) }}" class="flex-grow flex flex-col">
                        <div class="book-cover-wrap">
                            @if($livro->capa)
                                <img src="{{ asset('storage/' . $livro->capa) }}" alt="{{ $livro->titulo }}" loading="lazy">
                            @else
                                <div class="w-full h-full flex items-center justify-center bg-gradient-to-br from-emerald-900/20 to-[#080d14]"><i class="ph ph-book text-3xl text-emerald-900/40"></i></div>
                            @endif
                            @if($livro->e_bestseller)
                            <div class="bestseller-badge">Bestseller</div>
                            @endif
                        </div>
                        <div class="p-3 flex-grow flex flex-col gap-0.5">
                            <span class="text-[9px] font-bold uppercase tracking-widest text-emerald-500/70">{{ $livro->categoria ?? 'Geral' }}</span>
                            <h4 class="text-white text-xs font-semibold truncate group-hover:text-emerald-400 transition-colors">{{ $livro->titulo }}</h4>
                            <p class="text-gray-500 text-[11px] truncate">{{ $livro->autor->nome ?? '' }}</p>
                        </div>
                    </a>
                    @if($isAdmin)
                    <div class="px-3 pb-3 pt-2 border-t border-white/5 flex items-center justify-between shrink-0">
                        <a href="{{ route('livros.edit', $livro->id) }}" class="text-[10px] text-gray-600 hover:text-emerald-400 transition flex items-center gap-1"><i class="ph ph-pencil-simple"></i> Editar</a>
                        <form action="{{ route('livros.destroy', $livro->id) }}" method="POST" class="form-delete">
                            @csrf @method('DELETE')
                            <button type="button" class="btn-delete text-[10px] text-gray-700 hover:text-red-400 transition flex items-center gap-1"><i class="ph ph-trash"></i> Excluir</button>
                        </form>
                    </div>
                    @endif
                </div>
                @endforeach
            </div>
            <div id="empty-state" class="text-center py-20">
                <div class="w-16 h-16 rounded-2xl bg-[#0d1420] border border-white/[.06] flex items-center justify-center mx-auto mb-4">
                    <i class="ph ph-magnifying-glass text-2xl text-gray-700"></i>
                </div>
                <p class="text-gray-500 font-semibold text-sm">Nenhum livro encontrado</p>
                <p class="text-gray-700 text-xs mt-1">Tente ajustar os filtros</p>
                <button id="clear-filters-btn" class="mt-5 px-5 py-2 text-xs font-bold uppercase tracking-widest text-emerald-400 border border-emerald-500/30 rounded-lg hover:bg-emerald-500/10 transition">Limpar filtros</button>
            </div>
        </section>

        {{-- ── Autores em Destaque ── --}}
        <section class="gs-section">
            <div class="flex items-end justify-between mb-7">
                <div class="flex items-end gap-4">
                    <span class="section-num select-none">03</span>
                    <div class="pb-1">
                        <p class="text-[10px] font-bold uppercase tracking-[.15em] text-blue-500 mb-1">Criadores</p>
                        <h2 class="text-xl md:text-2xl font-black text-white font-serif">Autores em Destaque</h2>
                    </div>
                </div>
                <div class="flex gap-2 pb-1">
                    <button id="swiper-autores-prev" class="w-9 h-9 shrink-0 rounded-lg flex items-center justify-center bg-white/5 border border-white/10 text-gray-400 hover:bg-[#2563EB] hover:border-[#2563EB] hover:text-white transition-all"><i class="ph ph-caret-left"></i></button>
                    <button id="swiper-autores-next" class="w-9 h-9 shrink-0 rounded-lg flex items-center justify-center bg-white/5 border border-white/10 text-gray-400 hover:bg-[#2563EB] hover:border-[#2563EB] hover:text-white transition-all"><i class="ph ph-caret-right"></i></button>
                </div>
            </div>
            <div class="swiper swiperAutores" style="padding-top:42px;">
                <div class="swiper-wrapper">
                    @foreach($autores as $autor)
                    <div class="swiper-slide">
                        <div class="group bg-[#0d1420] rounded-xl border border-white/5 hover:border-[#2563EB]/40 transition-all flex flex-col text-center pb-4 px-4 h-full">
                            <div class="author-avatar shrink-0">
                                @if($autor->foto)
                                    <img src="{{ asset('storage/' . $autor->foto) }}" class="w-full h-full object-cover" alt="{{ $autor->nome }}">
                                @else
                                    <div class="w-full h-full flex items-center justify-center bg-[#111827]"><i class="ph ph-user text-2xl text-gray-600"></i></div>
                                @endif
                            </div>
                            <a href="{{ route('autores.show', $autor->id) }}" class="flex-grow flex flex-col">
                                <h4 class="text-white font-bold text-sm tracking-tight mt-1 group-hover:text-blue-400 transition-colors">{{ $autor->nome }}</h4>
                                <p class="text-[10px] uppercase tracking-widest text-gray-600 mt-0.5 mb-2">{{ $autor->nacionalidade ?? 'N/A' }}</p>
                                <p class="text-gray-500 text-xs line-clamp-3 leading-relaxed px-1">{{ Str::limit($autor->biografia ?? 'Biografia não cadastrada.', 90) }}</p>
                            </a>
                            <div class="mt-3 pt-3 border-t border-white/5 flex items-center justify-between shrink-0">
                                <span class="text-[10px] text-gray-600 flex items-center gap-1">
                                    <i class="ph ph-books text-xs"></i>
                                    {{ $autor->livros_count }} {{ $autor->livros_count === 1 ? 'obra' : 'obras' }}
                                </span>
                                @if($isAdmin)
                                <div class="flex gap-3">
                                    <a href="{{ route('autores.edit', $autor->id) }}" class="text-gray-600 hover:text-[#F59E0B] transition"><i class="ph ph-pencil-simple text-xs"></i></a>
                                    <form action="{{ route('autores.destroy', $autor->id) }}" method="POST" class="form-delete inline">
                                        @csrf @method('DELETE')
                                        <button type="button" class="btn-delete text-gray-600 hover:text-red-400 transition"><i class="ph ph-trash text-xs"></i></button>
                                    </form>
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </section>

    </div>{{-- /max-w --}}
    </div>{{-- /content-area --}}

    {{-- ══ SCRIPTS ══ --}}
    <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
    <script>
    document.addEventListener('DOMContentLoaded', function () {

        // CountUp
        function animateCounter(el) {
            const target = parseInt(el.dataset.target,10)||0;
            if(!target){el.textContent='0';return;}
            const dur=1600,start=performance.now();
            function step(now){const e=Math.min(now-start,dur),p=1-Math.pow(1-e/dur,3);el.textContent=Math.round(p*target).toLocaleString('pt-BR');if(e<dur)requestAnimationFrame(step);else el.textContent=target.toLocaleString('pt-BR');}
            requestAnimationFrame(step);
        }
        document.querySelectorAll('.counter').forEach(animateCounter);

        // GSAP
        gsap.registerPlugin(ScrollTrigger);
        gsap.from('#hero-txt',        { opacity:0, y:28, duration:.8, ease:'power3.out', delay:.05 });
        gsap.from('#hero-stats .stat-pill', { opacity:0, y:20, duration:.55, stagger:.1, ease:'power2.out', delay:.2 });
        gsap.from('#filter-bar',      { scrollTrigger:{trigger:'#filter-bar',start:'top 88%',once:true}, opacity:0, y:20, duration:.55, ease:'power2.out' });
        document.querySelectorAll('.gs-section h2').forEach(el=>{
            gsap.from(el,{scrollTrigger:{trigger:el,start:'top 88%',once:true},opacity:0,x:-18,duration:.55,ease:'power2.out'});
        });

        // Swipers
        new Swiper('.swiperLivros',{loop:true,grabCursor:true,slidesPerView:1.3,spaceBetween:14,breakpoints:{480:{slidesPerView:2.2,spaceBetween:16},768:{slidesPerView:3,spaceBetween:18},1024:{slidesPerView:4,spaceBetween:20}},autoplay:{delay:3200,disableOnInteraction:false,pauseOnMouseEnter:true},navigation:{nextEl:'#swiper-livros-next',prevEl:'#swiper-livros-prev'}});
        new Swiper('.swiperAutores',{loop:true,grabCursor:true,slidesPerView:1.4,spaceBetween:14,breakpoints:{480:{slidesPerView:2.5,spaceBetween:16},768:{slidesPerView:3.5,spaceBetween:18},1024:{slidesPerView:5,spaceBetween:20}},autoplay:{delay:3600,disableOnInteraction:false,pauseOnMouseEnter:true},navigation:{nextEl:'#swiper-autores-next',prevEl:'#swiper-autores-prev'}});

        // Tom Select
        const tsCfg={allowEmptyOption:true,create:false,maxOptions:100};
        const tsCategoria=new TomSelect('#filter-categoria',{...tsCfg});
        const tsAutor    =new TomSelect('#filter-autor',    {...tsCfg,searchField:['text']});
        const tsSort     =new TomSelect('#filter-sort',     {create:false,allowEmptyOption:false});

        // Filtro
        const grid=document.getElementById('acervo-grid');
        const allCards=[...grid.querySelectorAll('.acervo-card')];
        const emptyEl=document.getElementById('empty-state');
        const countEl=document.getElementById('results-count');
        const clearBtn=document.getElementById('clear-all-btn');
        const clearBtn2=document.getElementById('clear-filters-btn');
        const chipsEl=document.getElementById('active-filters');

        function applyFilters(){
            const search=document.getElementById('filter-search').value.toLowerCase().trim();
            const categoria=tsCategoria.getValue();
            const autorId=String(tsAutor.getValue());
            const sort=tsSort.getValue();
            let visible=0;
            allCards.forEach(c=>{
                const ok=(!search||c.dataset.titulo.includes(search)||c.dataset.autorNome.includes(search))&&(!categoria||c.dataset.categoria===categoria)&&(!autorId||c.dataset.autorId===autorId);
                c.style.display=ok?'':'none';if(ok)visible++;
            });
            [...allCards].filter(c=>c.style.display!=='none').sort((a,b)=>{
                if(sort==='titulo_az')return a.dataset.titulo.localeCompare(b.dataset.titulo,'pt-BR');
                if(sort==='titulo_za')return b.dataset.titulo.localeCompare(a.dataset.titulo,'pt-BR');
                if(sort==='bestseller')return parseInt(b.dataset.bestseller)-parseInt(a.dataset.bestseller);
                return b.dataset.data.localeCompare(a.dataset.data);
            }).forEach(c=>grid.appendChild(c));
            countEl.textContent=`${visible.toLocaleString('pt-BR')} título${visible!==1?'s':''}`;
            emptyEl.style.display=visible===0?'block':'none';
            clearBtn.classList.toggle('hidden',!(search||categoria||autorId));
            // chips
            chipsEl.innerHTML='';
            const chips=[];
            if(search)   chips.push({label:`"${search}"`,  clear:()=>{document.getElementById('filter-search').value='';applyFilters();}});
            if(categoria)chips.push({label:categoria,       clear:()=>tsCategoria.setValue('')});
            if(autorId)  chips.push({label:tsAutor.getOption(autorId)?.textContent?.trim()||'Autor',clear:()=>tsAutor.setValue('')});
            if(chips.length){chips.forEach(({label,clear})=>{const b=document.createElement('button');b.className='filter-chip';b.innerHTML=`<span>${label}</span><i class="ph ph-x" style="font-size:.75rem;color:#60a5fa"></i>`;b.addEventListener('click',clear);chipsEl.appendChild(b);});chipsEl.style.setProperty('display','flex','important');}
            else{chipsEl.style.setProperty('display','none','important');}
            if(visible>0)gsap.fromTo([...allCards].filter(c=>c.style.display!=='none'),{opacity:0,y:8},{opacity:1,y:0,duration:.28,stagger:.025,ease:'power2.out',clearProps:'transform'});
        }

        function clearAll(){document.getElementById('filter-search').value='';tsCategoria.setValue('');tsAutor.setValue('');tsSort.setValue('recente');applyFilters();}

        let dbTimer;
        document.getElementById('filter-search').addEventListener('input',()=>{clearTimeout(dbTimer);dbTimer=setTimeout(applyFilters,250);});
        tsCategoria.on('change',applyFilters);tsAutor.on('change',applyFilters);tsSort.on('change',applyFilters);
        clearBtn.addEventListener('click',clearAll);clearBtn2.addEventListener('click',clearAll);
        applyFilters();

        // Delete confirm
        document.querySelectorAll('.btn-delete').forEach(btn=>{
            btn.addEventListener('click',function(){
                const form=this.closest('.form-delete');
                Swal.fire({title:'Excluir registro?',text:'Esta ação não pode ser desfeita.',icon:'warning',showCancelButton:true,background:'#0d1420',color:'#fff',confirmButtonColor:'#ef4444',cancelButtonColor:'#1e293b',confirmButtonText:'Excluir',cancelButtonText:'Cancelar',customClass:{popup:'border border-white/10 rounded-xl'}}).then(r=>{if(r.isConfirmed)form.submit();});
            });
        });
    });
    </script>

    @if(session('success'))
    <script>
        document.addEventListener('DOMContentLoaded',function(){
            Swal.mixin({toast:true,position:'top-end',showConfirmButton:false,timer:3000,timerProgressBar:true,background:'#0d1420',color:'#fff',didOpen:t=>{t.onmouseenter=Swal.stopTimer;t.onmouseleave=Swal.resumeTimer;}}).fire({icon:'success',title:"{{ session('success') }}"});
        });
    </script>
    @endif

</x-app-layout>
<x-app-layout>

    {{-- ══ LIBS ══ --}}
    <script src="https://cdn.jsdelivr.net/npm/gsap@3.12.5/dist/gsap.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/gsap@3.12.5/dist/ScrollTrigger.min.js"></script>
    <link  rel="stylesheet" href="https://cdn.jsdelivr.net/npm/tom-select@2.3.1/dist/css/tom-select.min.css">
    <script src="https://cdn.jsdelivr.net/npm/tom-select@2.3.1/dist/js/tom-select.complete.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/list.js/2.3.1/list.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/list.js/2.3.1/list.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/list.js/2.3.1/list.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/list.js/2.3.1/list.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/list.js/2.3.1/list.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/list.js/2.3.1/list.min.js"></script>
    
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
        $notifiableTop = auth()->guard('membro')->check() ? auth()->guard('membro')->user() : auth()->user();
        $unreadCount = $notifiableTop ? $notifiableTop->unreadNotifications()->count() : 0;
    @endphp

    {{-- ══════════════════════════════════════════════════════
         HEADER SLOT — topo compacto
         ══════════════════════════════════════════════════════ --}}
    <x-slot name="header">
        <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between w-full">
            <div class="flex items-center gap-3 w-full sm:max-w-xl">
                <a href="{{ route('dashboard') }}" class="flex flex-col items-center justify-center gap-1 shrink-0">
                    <i class="ph ph-library text-[#1E3A8A] dark:text-blue-400 text-4xl"></i>
                    <div class="text-[11px] font-black tracking-tight text-center leading-tight">
                        <span class="text-[#1E3A8A] dark:text-blue-400">BIBLIO</span><br>
                        <span class="text-[#F59E0B]">TECH</span>
                    </div>
                </a>
                <div class="relative w-full">
                    <i class="ph ph-magnifying-glass pointer-events-none absolute left-3 top-1/2 -translate-y-1/2 text-slate-500 dark:text-gray-500 text-sm"></i>
                    <input
                        id="top-filter"
                        type="text"
                        placeholder="Buscar titulo, autor..."
                        class="w-full bg-white dark:bg-[#0d1420] border border-slate-200 dark:border-white/10 rounded-lg pl-9 pr-3 py-2 text-xs text-slate-700 dark:text-gray-200 placeholder:text-slate-400 dark:placeholder:text-gray-600 focus:outline-none focus:border-[#1E3A8A] focus:ring-2 focus:ring-[#1E3A8A]/30"
                    >
                </div>
            </div>

            <div class="flex items-center justify-between sm:justify-end gap-2">
                <div class="flex items-center gap-2">
                    <button type="button" @click="dark = !dark" class="w-9 h-9 rounded-lg bg-white dark:bg-white/5 border border-slate-200 dark:border-white/10 text-slate-600 dark:text-gray-300 hover:text-slate-900 dark:hover:text-white hover:bg-slate-50 dark:hover:bg-white/10 transition">
                        <i class="ph text-sm" :class="dark ? 'ph-sun' : 'ph-moon'"></i>
                    </button>
                    <button type="button" id="notifications-toggle" class="relative w-9 h-9 rounded-lg bg-white dark:bg-white/5 border border-slate-200 dark:border-white/10 text-slate-600 dark:text-gray-300 hover:text-slate-900 dark:hover:text-white hover:bg-slate-50 dark:hover:bg-white/10 transition" aria-controls="notifications-sidebar" aria-label="Notificacoes">
                        <i class="ph ph-bell text-sm"></i>
                        @if($unreadCount)
                            <span id="notifications-badge" class="absolute -top-1 -right-1 inline-flex items-center justify-center w-5 h-5 text-[10px] font-black text-white bg-red-600 rounded-full">{{ $unreadCount > 9 ? '9+' : $unreadCount }}</span>
                        @endif
                    </button>
                    @if(auth()->guard('membro')->check())
                    <button type="button" id="loans-toggle" class="h-9 px-3 rounded-lg bg-white dark:bg-white/5 border border-slate-200 dark:border-white/10 text-slate-600 dark:text-gray-300 hover:text-slate-900 dark:hover:text-white hover:bg-slate-50 dark:hover:bg-white/10 transition flex items-center gap-2" aria-controls="loans-sidebar" aria-expanded="false">
                        <i class="ph ph-ticket text-sm"></i>
                        <span class="hidden sm:inline text-[11px] font-bold uppercase tracking-widest">Meus alugueis</span>
                    </button>
                    @endif
                </div>

                @auth
                <div class="flex items-center gap-2 pl-2 border-l border-slate-200 dark:border-white/10">
                    <div class="w-9 h-9 rounded-full bg-gradient-to-br from-[#1E3A8A] to-blue-700 flex items-center justify-center ring-1 ring-blue-500/30 shrink-0">
                        <span class="text-white text-[10px] font-black tracking-tight select-none">{{ $iniciais }}</span>
                    </div>
                </div>
                @endauth
            </div>
        </div>
    </x-slot>

    {{-- ══ CONTENT AREA ══ --}}
    <div class="-mx-4 px-4 py-10 bg-slate-50 dark:bg-[#0f172a] sm:-mx-6 sm:px-6 lg:-mx-8 lg:px-8">
        <div class="fixed inset-0 z-0 pointer-events-none overflow-hidden" aria-hidden="true">

    {{-- Dot grid --}}
    <svg class="absolute inset-0 w-full h-full" xmlns="http://www.w3.org/2000/svg">
        <defs>
            <pattern id="bg-dots" width="28" height="28" patternUnits="userSpaceOnUse">
                <circle id="bg-dot-circle" cx="1" cy="1" r="1" fill="#93c5fd" opacity="0.08"/>
            </pattern>
        </defs>
        <rect width="100%" height="100%" fill="url(#bg-dots)"/>
    </svg>

    {{-- Glows --}}
    <div id="bg-glow-1" class="absolute -top-28 -left-20 w-96 h-96 rounded-full blur-[90px]"></div>
    <div id="bg-glow-2" class="absolute -bottom-20 -right-14 w-72 h-72 rounded-full blur-[80px]"></div>

    {{-- Prateleiras --}}
    <div class="bg-shelf absolute left-0 right-0 h-px top-[22%]"></div>
    <div class="bg-shelf absolute left-0 right-0 h-px top-[58%]"></div>

    {{-- Acento âmbar --}}
    <div class="absolute top-0 left-0 w-[3px] h-32 bg-[#F59E0B] opacity-50"></div>

    {{-- Ícones --}}
    <i class="ph ph-book bg-icon absolute left-[3%] top-[5%] text-[28px]"></i>
    <i class="ph ph-books bg-icon absolute left-[87%] top-[8%] text-[22px]"></i>
    <i class="ph ph-book-open bg-icon absolute left-[14%] top-[58%] text-[34px]"></i>
    <i class="ph ph-book-bookmark bg-icon absolute left-[74%] top-[54%] text-[26px]"></i>
    <i class="ph ph-bookmark bg-icon absolute left-[44%] top-[78%] text-[18px]"></i>
    <i class="ph ph-bookmarks bg-icon absolute left-[91%] top-[72%] text-[30px]"></i>
    <i class="ph ph-graduation-cap bg-icon absolute left-[59%] top-[12%] text-[24px]"></i>
    <i class="ph ph-scroll bg-icon absolute left-[29%] top-[30%] text-[16px]"></i>
    <i class="ph ph-library bg-icon absolute left-[68%] top-[36%] text-[28px]"></i>
    <i class="ph ph-notebook bg-icon absolute left-[80%] top-[22%] text-[20px]"></i>
    <i class="ph ph-book bg-icon absolute left-[8%] top-[80%] text-[22px]"></i>
    <i class="ph ph-book-open bg-icon absolute left-[50%] top-[44%] text-[14px]"></i>
</div>
    <div class="max-w-7xl mx-auto space-y-16 z-10">

        <div id="dashboard-home" class="space-y-16">
        <section class="gs-section">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <div class="lg:col-span-3 bg-white dark:bg-[#0d1420] border border-slate-200 dark:border-white/5 rounded-md p-5 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                    <div>
                        <p class="text-[10px] font-bold uppercase tracking-[.15em] text-blue-500 mb-1">Bem-vindo</p>
                        <h2 class="text-xl md:text-2xl font-black text-slate-900 dark:text-white font-serif">Ola, {{ $primeiroNome }}</h2>
                        <p class="text-slate-600 dark:text-gray-500 text-sm mt-1">Seu painel do acervo esta pronto para hoje.</p>
                    </div>
                    <div class="flex gap-3 flex-wrap">
                        <div class="px-4 py-3 rounded-xl bg-slate-50 dark:bg-white/5 border border-slate-200 dark:border-white/10">
                            <p class="text-[10px] uppercase tracking-widest text-slate-500 dark:text-gray-500">Titulos</p>
                            <p class="text-lg font-black text-slate-900 dark:text-white">{{ $totalLivros }}</p>
                        </div>
                        <div class="px-4 py-3 rounded-xl bg-slate-50 dark:bg-white/5 border border-slate-200 dark:border-white/10">
                            <p class="text-[10px] uppercase tracking-widest text-slate-500 dark:text-gray-500">Emprestimos</p>
                            <p class="text-lg font-black text-slate-900 dark:text-white">{{ $emprestimosAtivos }}</p>
                        </div>
                        @if($isAdmin)
                        <a href="{{ route('membros.create') }}" class="px-4 py-3 rounded-xl bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800/50 hover:bg-blue-100 dark:hover:bg-blue-900/40 transition flex items-center justify-center gap-2">
                            <i class="ph ph-user-plus text-sm text-blue-600 dark:text-blue-400"></i>
                            <span class="text-[10px] uppercase tracking-widest font-bold text-blue-600 dark:text-blue-400">Cadastrar Membro</span>
                        </a>
                        @endif
                    </div>
                </div>

                <div class="lg:col-span-2 bg-white dark:bg-[#0d1420] border border-slate-200 dark:border-white/5 rounded-md p-5">
                    <div class="flex items-center justify-between mb-5">
                        <h3 class="text-sm font-black text-slate-900 dark:text-white uppercase tracking-widest">Livros</h3>
                        <span class="text-[10px] text-slate-500 dark:text-gray-500">Populares e recentes</span>
                    </div>

                    <div class="space-y-7">
                        <div>
                            <div class="flex items-center justify-between mb-3">
                                <p class="text-[11px] font-bold uppercase tracking-[.15em] text-amber-500">Populares</p>
                                <div class="flex items-center gap-2">
                                    <button id="swiper-populares-prev" class="w-8 h-8 shrink-0 rounded-lg flex items-center justify-center bg-white/5 border border-white/10 text-gray-400 hover:bg-[#2563EB] hover:border-[#2563EB] hover:text-white transition-all" aria-label="Anterior">
                                        <i class="ph ph-caret-left"></i>
                                    </button>
                                    <button id="swiper-populares-next" class="w-8 h-8 shrink-0 rounded-lg flex items-center justify-center bg-white/5 border border-white/10 text-gray-400 hover:bg-[#2563EB] hover:border-[#2563EB] hover:text-white transition-all" aria-label="Proximo">
                                        <i class="ph ph-caret-right"></i>
                                    </button>
                                    <a href="#acervo-section" class="px-3 py-2 rounded-lg text-[10px] text-amber-500 uppercase tracking-widest font-bold bg-amber-500/10 border border-amber-500/30 hover:bg-amber-500/20 hover:border-amber-500/50 transition-all">Ver mais</a>
                                </div>
                            </div>
                            <div class="swiper overflow-hidden" id="swiper-populares">
                                <div class="swiper-wrapper flex items-stretch">
                                    @php
                                        $populares = (isset($bestsellers) && $bestsellers->count()) ? $bestsellers->take(10) : $livros->take(10);
                                    @endphp
                                    @foreach($populares as $livro)
                                    <div class="swiper-slide h-auto shrink-0 !w-36 sm:!w-40 lg:!w-44">
                                        <a href="{{ route('livros.show', $livro->id) }}" class="group block">
                                            <div class="relative w-full h-52 rounded-xl overflow-hidden bg-slate-100 dark:bg-white/10">
                                                @if($livro->capa)
                                                    <img src="{{ asset('storage/' . $livro->capa) }}" alt="{{ $livro->titulo }}" class="w-full h-full object-cover">
                                                @else
                                                    <div class="w-full h-full flex items-center justify-center">
                                                        <i class="ph ph-book text-2xl text-slate-400"></i>
                                                    </div>
                                                @endif
                                                <div class="absolute inset-0 bg-black/50 opacity-0 group-hover:opacity-100 transition flex items-center justify-center">
                                                    <span class="px-3 py-1.5 rounded-lg text-[10px] font-bold uppercase tracking-widest bg-white text-slate-900">Ver mais</span>
                                                </div>
                                            </div>
                                            <div class="mt-2">
                                                <p class="text-xs font-semibold text-slate-900 dark:text-white truncate">{{ $livro->titulo }}</p>
                                                <p class="text-[10px] text-slate-500 dark:text-gray-500 truncate">{{ $livro->autor->nome ?? '' }}</p>
                                            </div>
                                        </a>
                                    </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>

                        <div>
                            <div class="flex items-center justify-between mb-3">
                                <p class="text-[11px] font-bold uppercase tracking-[.15em] text-blue-500">Recentes</p>
                                <div class="flex items-center gap-2">
                                    <button id="swiper-recentes-prev" class="w-8 h-8 shrink-0 rounded-lg flex items-center justify-center bg-white/5 border border-white/10 text-gray-400 hover:bg-[#2563EB] hover:border-[#2563EB] hover:text-white transition-all" aria-label="Anterior">
                                        <i class="ph ph-caret-left"></i>
                                    </button>
                                    <button id="swiper-recentes-next" class="w-8 h-8 shrink-0 rounded-lg flex items-center justify-center bg-white/5 border border-white/10 text-gray-400 hover:bg-[#2563EB] hover:border-[#2563EB] hover:text-white transition-all" aria-label="Proximo">
                                        <i class="ph ph-caret-right"></i>
                                    </button>
                                    <a href="#acervo-section" class="px-3 py-2 rounded-lg text-[10px] text-blue-500 uppercase tracking-widest font-bold bg-blue-500/10 border border-blue-500/30 hover:bg-blue-500/20 hover:border-blue-500/50 transition-all">Ver mais</a>
                                </div>
                            </div>
                            <div class="swiper overflow-hidden" id="swiper-recentes">
                                <div class="swiper-wrapper flex items-stretch">
                                    @php
                                        $recentes = (isset($livrosRecentes) && $livrosRecentes->count()) ? $livrosRecentes->take(10) : $livros->take(10);
                                    @endphp
                                    @foreach($recentes as $livro)
                                    <div class="swiper-slide h-auto shrink-0 !w-36 sm:!w-40 lg:!w-44">
                                        <a href="{{ route('livros.show', $livro->id) }}" class="group block">
                                            <div class="relative w-full h-52 rounded-xl overflow-hidden bg-slate-100 dark:bg-white/10">
                                                @if($livro->capa)
                                                    <img src="{{ asset('storage/' . $livro->capa) }}" alt="{{ $livro->titulo }}" class="w-full h-full object-cover">
                                                @else
                                                    <div class="w-full h-full flex items-center justify-center">
                                                        <i class="ph ph-book text-2xl text-slate-400"></i>
                                                    </div>
                                                @endif
                                                <div class="absolute inset-0 bg-black/50 opacity-0 group-hover:opacity-100 transition flex items-center justify-center">
                                                    <span class="px-3 py-1.5 rounded-lg text-[10px] font-bold uppercase tracking-widest bg-white text-slate-900">Ver mais</span>
                                                </div>
                                            </div>
                                            <div class="mt-2">
                                                <p class="text-xs font-semibold text-slate-900 dark:text-white truncate">{{ $livro->titulo }}</p>
                                                <p class="text-[10px] text-slate-500 dark:text-gray-500 truncate">{{ $livro->autor->nome ?? '' }}</p>
                                            </div>
                                        </a>
                                    </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="lg:col-span-1 bg-white dark:bg-[#0d1420] border border-slate-200 dark:border-white/5 rounded-md p-5">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-sm font-black text-slate-900 dark:text-white uppercase tracking-widest">Categorias</h3>
                        <span class="text-[10px] text-slate-400 uppercase tracking-widest font-bold">Mais acessadas</span>
                    </div>
                    @php
                        $categoriaIcones = [
                            'Romance' => 'ph-heart',
                            'Aventura' => 'ph-compass',
                            'Fantasia' => 'ph-magic-wand',
                            'Ficcao Cientifica' => 'ph-planet',
                            'Ficcao' => 'ph-mask-happy',
                            'Biografia' => 'ph-pen-nib',
                            'Historia' => 'ph-scroll',
                            'Autoajuda' => 'ph-lightbulb-filament',
                            'Didatico' => 'ph-graduation-cap',
                            'Terror' => 'ph-ghost',
                            'Poesia' => 'ph-feather',
                            'HQ/Comic' => 'ph-chat-circle-text',
                            'Outros' => 'ph-book',
                            'Ciencia' => 'ph-atom',
                            'Tecnologia' => 'ph-cpu',
                            'Literatura' => 'ph-book-open-text',
                            'Natureza' => 'ph-leaf',
                        ];
                        $categoriaCores = [
                            'Romance' => 'border-rose-300 bg-rose-50 text-rose-800 hover:bg-rose-100 dark:border-rose-900/50 dark:bg-rose-900/25 dark:text-rose-200 dark:hover:bg-rose-900/35',
                            'Aventura' => 'border-amber-300 bg-amber-50 text-amber-800 hover:bg-amber-100 dark:border-amber-900/50 dark:bg-amber-900/20 dark:text-amber-200 dark:hover:bg-amber-900/30',
                            'Fantasia' => 'border-violet-300 bg-violet-50 text-violet-800 hover:bg-violet-100 dark:border-violet-900/50 dark:bg-violet-900/20 dark:text-violet-200 dark:hover:bg-violet-900/30',
                            'Ficcao Cientifica' => 'border-sky-300 bg-sky-50 text-sky-800 hover:bg-sky-100 dark:border-sky-900/50 dark:bg-sky-900/20 dark:text-sky-200 dark:hover:bg-sky-900/30',
                            'Ficcao' => 'border-fuchsia-300 bg-fuchsia-50 text-fuchsia-800 hover:bg-fuchsia-100 dark:border-fuchsia-900/50 dark:bg-fuchsia-900/20 dark:text-fuchsia-200 dark:hover:bg-fuchsia-900/30',
                            'Biografia' => 'border-orange-300 bg-orange-50 text-orange-800 hover:bg-orange-100 dark:border-orange-900/50 dark:bg-orange-900/20 dark:text-orange-200 dark:hover:bg-orange-900/30',
                            'Historia' => 'border-stone-300 bg-stone-50 text-stone-800 hover:bg-stone-100 dark:border-stone-900/50 dark:bg-stone-900/20 dark:text-stone-200 dark:hover:bg-stone-900/30',
                            'Autoajuda' => 'border-lime-300 bg-lime-50 text-lime-800 hover:bg-lime-100 dark:border-lime-900/50 dark:bg-lime-900/20 dark:text-lime-200 dark:hover:bg-lime-900/30',
                            'Didatico' => 'border-emerald-300 bg-emerald-50 text-emerald-800 hover:bg-emerald-100 dark:border-emerald-900/50 dark:bg-emerald-900/20 dark:text-emerald-200 dark:hover:bg-emerald-900/30',
                            'Terror' => 'border-purple-300 bg-purple-50 text-purple-800 hover:bg-purple-100 dark:border-purple-950/50 dark:bg-purple-950/30 dark:text-purple-200 dark:hover:bg-purple-950/40',
                            'Poesia' => 'border-indigo-300 bg-indigo-50 text-indigo-800 hover:bg-indigo-100 dark:border-indigo-900/50 dark:bg-indigo-900/20 dark:text-indigo-200 dark:hover:bg-indigo-900/30',
                            'HQ/Comic' => 'border-teal-300 bg-teal-50 text-teal-800 hover:bg-teal-100 dark:border-teal-900/50 dark:bg-teal-900/20 dark:text-teal-200 dark:hover:bg-teal-900/30',
                            'Outros' => 'border-slate-300 bg-slate-50 text-slate-800 hover:bg-slate-100 dark:border-slate-800/60 dark:bg-slate-900/30 dark:text-slate-200 dark:hover:bg-slate-900/40',
                            'Ciencia' => 'border-cyan-300 bg-cyan-50 text-cyan-800 hover:bg-cyan-100 dark:border-cyan-900/50 dark:bg-cyan-900/20 dark:text-cyan-200 dark:hover:bg-cyan-900/30',
                            'Tecnologia' => 'border-blue-300 bg-blue-50 text-blue-800 hover:bg-blue-100 dark:border-blue-900/50 dark:bg-blue-900/20 dark:text-blue-200 dark:hover:bg-blue-900/30',
                            'Literatura' => 'border-yellow-300 bg-yellow-50 text-yellow-800 hover:bg-yellow-100 dark:border-yellow-900/50 dark:bg-yellow-900/20 dark:text-yellow-200 dark:hover:bg-yellow-900/30',
                            'Natureza' => 'border-green-300 bg-green-50 text-green-800 hover:bg-green-100 dark:border-green-900/50 dark:bg-green-900/20 dark:text-green-200 dark:hover:bg-green-900/30',
                        ];
                    @endphp
                    @if(isset($categoriasMaisAcessadas) && $categoriasMaisAcessadas->count())
                        <div class="flex flex-col gap-2">
                            @foreach($categoriasMaisAcessadas as $cat)
                                @php
                                    $nomeCat = $cat->categoria ?? 'Outros';
                                    $nomeCatKey = \Illuminate\Support\Str::ascii($nomeCat);
                                    $icone = $categoriaIcones[$nomeCatKey] ?? $categoriaIcones[$nomeCat] ?? 'ph-book';
                                    $cor = $categoriaCores[$nomeCatKey] ?? $categoriaCores[$nomeCat] ?? 'border-slate-200 bg-slate-50 text-slate-700 hover:bg-slate-100';
                                @endphp
                                <button type="button" data-cat-filter="{{ $nomeCat }}" class="w-full flex items-center justify-between gap-3 rounded-md border px-3 py-2 text-left transition {{ $cor }}">
                                    <span class="flex items-center gap-2">
                                        <i class="ph {{ $icone }}"></i>
                                        <span class="text-xs font-semibold">{{ $nomeCat }}</span>
                                    </span>
                                    <span class="text-[10px] font-bold uppercase tracking-widest text-slate-400">{{ $cat->total }}</span>
                                </button>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-6 text-slate-500 dark:text-gray-500 text-sm">
                            Ainda nao ha acessos suficientes.
                        </div>
                    @endif
                </div>
            </div>
        </section>

        {{-- ── Autores em Destaque ── --}}
        <section class="gs-section">
            <div class="flex items-end justify-between mb-7">
                <div class="flex items-end gap-4">
                    <div class="">
                        <p class="text-[10px] font-bold uppercase tracking-[.15em] text-blue-500 mb-1">Criadores</p>
                        <h2 class="text-xl md:text-2xl font-black text-slate-900 dark:text-white font-serif">Autores em Destaque</h2>
                    </div>
                </div>
                <div class="flex gap-2 pb-1">
                    <button id="swiper-autores-prev" class="w-9 h-9 shrink-0 rounded-lg flex items-center justify-center bg-white/5 border border-white/10 text-gray-400 hover:bg-[#2563EB] hover:border-[#2563EB] hover:text-white transition-all"><i class="ph ph-caret-left"></i></button>
                    <button id="swiper-autores-next" class="w-9 h-9 shrink-0 rounded-lg flex items-center justify-center bg-white/5 border border-white/10 text-gray-400 hover:bg-[#2563EB] hover:border-[#2563EB] hover:text-white transition-all"><i class="ph ph-caret-right"></i></button>
                </div>
            </div>
            <div class="swiper swiperAutores pt-[42px] overflow-hidden">
                <div class="swiper-wrapper flex items-stretch">
                    @foreach($autores as $autor)
                    <div class="swiper-slide h-auto">
                        <div class="group bg-white dark:bg-[#0d1420] rounded-xl border border-slate-200 dark:border-white/5 hover:border-[#2563EB]/40 transition-all flex flex-col text-center pb-4 px-4 h-full">
                            <div class="shrink-0 w-[68px] h-[68px] rounded-full overflow-hidden border-2 border-blue-600/50 bg-[#111827] -mt-[34px] mx-auto mb-2.5 relative z-10">
                                @if($autor->foto)
                                    <img src="{{ asset('storage/' . $autor->foto) }}" class="w-full h-full object-cover" alt="{{ $autor->nome }}">
                                @else
                                    <div class="w-full h-full flex items-center justify-center bg-[#111827]"><i class="ph ph-user text-2xl text-gray-600"></i></div>
                                @endif
                            </div>
                            <a href="{{ route('autores.show', $autor->id) }}" class="flex-grow flex flex-col">
                                <h4 class="text-slate-900 dark:text-white font-bold text-sm tracking-tight mt-1 group-hover:text-blue-400 transition-colors">{{ $autor->nome }}</h4>
                                <p class="text-[10px] uppercase tracking-widest text-slate-600 dark:text-gray-600 mt-0.5 mb-2">{{ $autor->nacionalidade ?? 'N/A' }}</p>
                                <p class="text-slate-600 dark:text-gray-500 text-xs line-clamp-3 leading-relaxed px-1">{{ Str::limit($autor->biografia ?? 'Biografia não cadastrada.', 90) }}</p>
                            </a>
                            <div class="mt-3 pt-3 border-t border-white/5 flex items-center justify-between shrink-0">
                                <span class="text-[10px] text-slate-600 dark:text-gray-600 flex items-center gap-1">
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
        </div>

        {{-- ══ ACERVO (busca global) ══ --}}
        <section id="acervo-section" class="gs-section hidden">
            <div class="flex items-end justify-between mb-7">
                <div class="flex items-end gap-4">
                    
                    <div class="">
                        <p class="text-[10px] font-bold uppercase tracking-[.15em] text-emerald-500 mb-1">Busca & Filtros</p>
                        <h2 class="text-xl md:text-2xl font-black text-slate-900 dark:text-white font-serif">Acervo</h2>
                    </div>
                </div>
                <div class="pb-1 flex items-center gap-3">
                    <span id="results-count" class="text-[11px] text-gray-600 font-medium tabular-nums"></span>
                    <button id="clear-all-btn" class="hidden text-[10px] font-bold uppercase tracking-widest text-red-400/70 hover:text-red-400 transition px-2 py-1 rounded-md hover:bg-red-500/10">
                        <i class="ph ph-x mr-1"></i>Limpar
                    </button>
                </div>
            </div>
            <div class="mb-8 p-4 sm:p-5 bg-white/90 dark:bg-[#0d1420]/90 rounded-md border border-slate-200 dark:border-white/[.06] shadow-xl shadow-black/30 relative z-20" id="filter-bar">
                <div class="absolute inset-0 pointer-events-none">
                    <i class="ph ph-book-open absolute left-6 top-4 text-2xl text-slate-200/60 dark:text-white/5"></i>
                    <i class="ph ph-book-open-text absolute right-10 top-6 text-xl text-slate-200/50 dark:text-white/5"></i>
                    <i class="ph ph-books absolute left-10 bottom-6 text-3xl text-slate-200/50 dark:text-white/5"></i>
                    <i class="ph ph-bookmark absolute right-16 bottom-5 text-2xl text-slate-200/50 dark:text-white/5"></i>
                </div>
                <div class="relative z-20 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                    <div class="sm:col-span-2 lg:col-span-1">
                        <label class="block text-[10px] font-bold uppercase tracking-[.12em] text-slate-500 mb-1.5" for="filter-search">Buscar</label>
                        <div class="relative flex items-center">
                            <i class="ph ph-magnifying-glass absolute left-2.5 text-base text-slate-600 pointer-events-none z-10"></i>
                            <input type="text" id="filter-search" placeholder="Título, autor..." class="w-full bg-[#080d14] border border-white/10 text-slate-200 rounded-lg py-2 pl-8 pr-3 text-[13px] transition focus:border-[#1E3A8A] focus:ring-2 focus:ring-[#1E3A8A]/25 outline-none" autocomplete="off">
                        </div>
                    </div>
                    <div>
                        <label class="block text-[10px] font-bold uppercase tracking-[.12em] text-slate-500 mb-1.5" for="filter-categoria">Categoria</label>
                        <select id="filter-categoria" placeholder="Todas...">
                            <option value="">Todas as categorias</option>
                            @foreach($categorias as $cat)
                            <option value="{{ $cat }}">{{ $cat }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-[10px] font-bold uppercase tracking-[.12em] text-slate-500 mb-1.5" for="filter-autor">Autor</label>
                        <select id="filter-autor" placeholder="Todos...">
                            <option value="">Todos os autores</option>
                            @foreach($autores as $autor)
                            <option value="{{ $autor->id }}">{{ $autor->nome }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-[10px] font-bold uppercase tracking-[.12em] text-slate-500 mb-1.5" for="filter-sort">Ordenar por</label>
                        <select id="filter-sort">
                            <option value="recente">Mais Recentes</option>
                            <option value="titulo_az">Título A → Z</option>
                            <option value="titulo_za">Título Z → A</option>
                            <option value="bestseller">Bestsellers</option>
                        </select>
                    </div>
                </div>
                <div id="active-filters" class="mt-4 flex flex-wrap gap-2 relative z-20 hidden"></div>
            </div>

            {{-- Grid com máximo 8 livros + Swiper para livros além dos 8 primeiros --}}
            <div class="space-y-6">
                <div id="acervo-grid" class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-3">
                    @foreach($livros->take(8) as $livro)
                    <div class="book-card acervo-card group bg-white dark:bg-[#0d1420] rounded-md overflow-hidden border border-slate-200 dark:border-white/5 hover:border-emerald-500/30 transition-[opacity,transform,border-color] duration-200 flex flex-col"
                         data-titulo="{{ strtolower($livro->titulo) }}"
                         data-autor-nome="{{ strtolower($livro->autor->nome ?? '') }}"
                         data-autor-id="{{ $livro->autor_id }}"
                         data-categoria="{{ $livro->categoria ?? 'Geral' }}"
                         data-bestseller="{{ $livro->e_bestseller ? '1' : '0' }}"
                         data-data="{{ $livro->data_publicacao ?? '0000-00-00' }}">
                        <a href="{{ route('livros.show', $livro->id) }}" class="flex-grow flex flex-col">
                            <div class="relative w-full h-48 overflow-hidden bg-[#080d14]">
                                @if($livro->capa)
                                    <img src="{{ asset('storage/' . $livro->capa) }}" alt="{{ $livro->titulo }}" loading="lazy" class="w-full h-full object-cover transition-transform duration-500 ease-[cubic-bezier(.4,0,.2,1)] group-hover:scale-[1.06]">
                                @else
                                    <div class="w-full h-full flex items-center justify-center bg-gradient-to-br from-emerald-900/20 to-[#080d14]"><i class="ph ph-book text-3xl text-emerald-900/40"></i></div>
                                @endif
                                @if($livro->e_bestseller)
                                <div class="absolute top-2 left-2 px-2 py-0.5 rounded bg-amber-500 text-slate-900 text-[9px] font-black uppercase tracking-[.06em]">Bestseller</div>
                                @endif
                            </div>
                            <div class="p-3 flex-grow flex flex-col gap-0.5">
                                <span class="text-[9px] font-bold uppercase tracking-widest text-emerald-500/70">{{ $livro->categoria ?? 'Geral' }}</span>
                                <h4 class="text-slate-900 dark:text-white text-xs font-semibold truncate group-hover:text-emerald-400 transition-colors">{{ $livro->titulo }}</h4>
                                <p class="text-slate-600 dark:text-gray-500 text-[11px] truncate">{{ $livro->autor->nome ?? '' }}</p>
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

                {{-- Swiper para livros além dos 8 primeiros --}}
                @if($livros->count() > 8)
                <div id="swiper-acervo-block">
                    <div class="flex items-center justify-between mb-3">
                        <div class="flex items-center gap-2">
                            <button id="swiper-acervo-prev" class="w-8 h-8 shrink-0 rounded-lg flex items-center justify-center bg-white/5 border border-white/10 text-gray-400 hover:bg-[#2563EB] hover:border-[#2563EB] hover:text-white transition-all" aria-label="Anterior">
                                <i class="ph ph-caret-left"></i>
                            </button>
                            <button id="swiper-acervo-next" class="w-8 h-8 shrink-0 rounded-lg flex items-center justify-center bg-white/5 border border-white/10 text-gray-400 hover:bg-[#2563EB] hover:border-[#2563EB] hover:text-white transition-all" aria-label="Proximo">
                                <i class="ph ph-caret-right"></i>
                            </button>
                        </div>
                    </div>
                    <div class="swiper overflow-hidden" id="swiper-acervo">
                        <div class="swiper-wrapper flex items-stretch">
                            @foreach($livros->slice(8) as $livro)
                            <div class="swiper-slide h-auto shrink-0 !w-36 sm:!w-40 lg:!w-44 acervo-carousel-card"
                                 data-titulo="{{ strtolower($livro->titulo) }}"
                                 data-autor-nome="{{ strtolower($livro->autor->nome ?? '') }}"
                                 data-autor-id="{{ $livro->autor_id }}"
                                 data-categoria="{{ $livro->categoria ?? 'Geral' }}"
                                 data-bestseller="{{ $livro->e_bestseller ? '1' : '0' }}"
                                 data-data="{{ $livro->data_publicacao ?? '0000-00-00' }}">
                                <a href="{{ route('livros.show', $livro->id) }}" class="group block h-full">
                                    <div class="relative w-full h-52 rounded-xl overflow-hidden bg-slate-100 dark:bg-white/10">
                                        @if($livro->capa)
                                            <img src="{{ asset('storage/' . $livro->capa) }}" alt="{{ $livro->titulo }}" class="w-full h-full object-cover">
                                        @else
                                            <div class="w-full h-full flex items-center justify-center">
                                                <i class="ph ph-book text-2xl text-slate-400"></i>
                                            </div>
                                        @endif
                                        <div class="absolute inset-0 bg-black/50 opacity-0 group-hover:opacity-100 transition flex items-center justify-center">
                                            <span class="px-3 py-1.5 rounded-lg text-[10px] font-bold uppercase tracking-widest bg-white text-slate-900">Ver mais</span>
                                        </div>
                                    </div>
                                    <div class="mt-2">
                                        <p class="text-xs font-semibold text-slate-900 dark:text-white truncate">{{ $livro->titulo }}</p>
                                        <p class="text-[10px] text-slate-500 dark:text-gray-500 truncate">{{ $livro->autor->nome ?? '' }}</p>
                                    </div>
                                </a>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>
                @endif

            {{-- Empty state --}}
                <div class="w-16 h-16 rounded-2xl bg-white dark:bg-[#0d1420] border border-slate-200 dark:border-white/[.06] flex items-center justify-center mx-auto mb-4">
                    <i class="ph ph-magnifying-glass text-2xl text-slate-500 dark:text-gray-700"></i>
                </div>
                <p class="text-slate-700 dark:text-gray-500 font-semibold text-sm">Nenhum livro encontrado</p>
                <p class="text-slate-500 dark:text-gray-700 text-xs mt-1">Tente ajustar os filtros</p>
                <button id="clear-filters-btn" class="mt-5 px-5 py-2 text-xs font-bold uppercase tracking-widest text-emerald-400 border border-emerald-500/30 rounded-lg hover:bg-emerald-500/10 transition">Limpar filtros</button>
            </div>
        </section>

    </div>{{-- /max-w --}}
    </div>{{-- /content-area --}}

    @if(auth()->guard('membro')->check())
    {{-- ══ SIDEBAR: MEUS ALUGUEIS ══ --}}
    <div id="loans-backdrop" class="fixed inset-0 bg-slate-950/60 opacity-0 pointer-events-none transition-opacity duration-200 z-50" aria-hidden="true"></div>
    <aside id="loans-sidebar" class="fixed top-0 right-[-420px] w-[380px] max-w-[90vw] h-screen bg-[#0d1420] border-l border-white/10 shadow-2xl transition-[right] duration-200 z-[60] flex flex-col" role="dialog" aria-modal="true" aria-label="Meus alugueis">
        <div class="p-5 border-b border-white/10 flex items-center justify-between">
            <div>
                <h3 class="text-sm font-black text-white uppercase tracking-widest">Meus alugueis</h3>
                <p class="text-[11px] text-gray-400">Acompanhe seus prazos</p>
            </div>
            <button type="button" id="loans-close" class="w-9 h-9 rounded-lg bg-white/5 border border-white/10 text-gray-300 hover:text-white hover:bg-white/10 transition" aria-label="Fechar">
                <i class="ph ph-x text-sm"></i>
            </button>
        </div>
        <div class="p-5 overflow-y-auto flex-1">
            @if(isset($emprestimosDoMembro) && $emprestimosDoMembro->count())
                <div class="space-y-4">
                    @foreach($emprestimosDoMembro as $emp)
                        @php
                            $hoje = \Carbon\Carbon::today();
                            $inicio = $emp->data_emprestimo;
                            $fim = $emp->data_devolucao_prevista;
                            $total = max(1, $inicio->diffInDays($fim));
                            $passado = $inicio->diffInDays($hoje);
                            $progress = min(100, round($passado / $total * 100));
                            $diasRestantes = $hoje->diffInDays($fim, false);
                            $atrasado = $diasRestantes < 0;
                            $progressClass = $atrasado ? 'bg-red-500' : ($progress > 75 ? 'bg-amber-500' : 'bg-blue-500');
                        @endphp
                        <div class="flex gap-3">
                            <div class="w-12 h-16 rounded-md overflow-hidden bg-white/10 flex items-center justify-center shrink-0">
                                @if($emp->livro?->capa)
                                    <img src="{{ asset('storage/' . $emp->livro->capa) }}" alt="{{ $emp->livro?->titulo }}" class="w-full h-full object-cover">
                                @else
                                    <i class="ph ph-book text-sm text-slate-400"></i>
                                @endif
                            </div>
                            <div class="min-w-0 flex-1">
                                <div class="flex items-center justify-between gap-2">
                                    <p class="text-sm font-semibold text-white truncate">{{ $emp->livro?->titulo ?? '—' }}</p>
                                    <span class="text-[10px] font-bold uppercase tracking-widest {{ $atrasado ? 'text-red-400' : 'text-emerald-400' }}">
                                        {{ $atrasado ? 'Vencido' : 'Ativo' }}
                                    </span>
                                </div>
                                <p class="text-[11px] text-gray-400">Expira em {{ $fim->format('d/m') }}</p>
                                <div class="mt-2 h-1.5 rounded-full bg-white/10 overflow-hidden">
                                    <div class="progress-fill {{ $progressClass }} h-full" data-progress="{{ $progress }}"></div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-10 text-gray-400 text-sm">
                    Nenhum aluguel ativo.
                </div>
            @endif
        </div>
        <div class="p-5 border-t border-white/10">
            <a href="{{ route('emprestimos.historico') }}" class="w-full inline-flex items-center justify-center gap-2 h-10 rounded-lg bg-white/5 border border-white/10 text-gray-200 hover:text-white hover:bg-white/10 transition text-[11px] font-bold uppercase tracking-widest">
                Ver historico completo
            </a>
        </div>
    </aside>
    @endif

    @auth
    {{-- ══ SIDEBAR: NOTIFICAÇÕES (baseado em Meus alugueis) ══ --}}
    @php
        $notifiable = auth()->guard('membro')->check() ? auth()->guard('membro')->user() : auth()->user();
        $unreads = $notifiable ? $notifiable->unreadNotifications()->latest()->get() : collect();
        $reads = $notifiable ? $notifiable->readNotifications()->latest()->take(30)->get() : collect();
    @endphp
    <div id="notifications-backdrop" class="fixed inset-0 bg-slate-950/60 opacity-0 pointer-events-none transition-opacity duration-200 z-50" aria-hidden="true"></div>
    <aside id="notifications-sidebar" class="fixed top-0 right-[-420px] w-[380px] max-w-[90vw] h-screen bg-[#0d1420] border-l border-white/10 shadow-2xl transition-[right] duration-200 z-[60] flex flex-col" role="dialog" aria-modal="true" aria-label="Notificações">
        <div class="p-5 border-b border-white/10 flex items-center justify-between">
            <div>
                <h3 class="text-sm font-black text-white uppercase tracking-widest">Notificações</h3>
                <p class="text-[11px] text-gray-400">Últimas atualizações do seu acervo</p>
            </div>
            <button type="button" id="notifications-close" class="w-9 h-9 rounded-lg bg-white/5 border border-white/10 text-gray-300 hover:text-white hover:bg-white/10 transition" aria-label="Fechar">
                <i class="ph ph-x text-sm"></i>
            </button>
        </div>
        <div class="p-4 overflow-y-auto flex-1 space-y-3">
            @if($unreads->isEmpty() && $reads->isEmpty())
                <div class="text-center py-6 text-gray-400 text-sm">Sem notificações por enquanto.</div>
            @endif

            @foreach($unreads as $n)
                <div class="notification-unread p-3 rounded-md bg-slate-50 dark:bg-white/5 border border-slate-700/20">
                    <div class="flex items-start justify-between">
                        <div class="text-sm text-white">{!! $n->data['message'] ?? ($n->data['title'] ?? 'Notificação') !!}</div>
                        <div class="text-xs text-slate-400">{{ $n->created_at->diffForHumans() }}</div>
                    </div>
                </div>
            @endforeach

            @foreach($reads as $n)
                <div class="notification-read p-3 rounded-md bg-transparent border border-white/5 text-slate-400">
                    <div class="flex items-start justify-between">
                        <div class="text-sm">{!! $n->data['message'] ?? ($n->data['title'] ?? 'Notificação') !!}</div>
                        <div class="text-xs">{{ $n->created_at->diffForHumans() }}</div>
                    </div>
                </div>
            @endforeach
        </div>
        <div class="p-4 border-t border-white/10">
            <button id="mark-all-read" class="w-full inline-flex items-center justify-center gap-2 h-10 rounded-lg bg-white/5 border border-white/10 text-gray-200 hover:text-white hover:bg-white/10 transition text-[11px] font-bold uppercase tracking-widest">
                Marcar todas como lidas
            </button>
        </div>
    </aside>
    @endauth

    {{-- ══ SCRIPTS ══ --}}
    <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    
    <style>
        .ts-dropdown {
            z-index: 1000 !important;
        }
    </style>
    
    @vite('resources/js/dashboard.js')

    @if(session('success'))
    <script>
        document.addEventListener('DOMContentLoaded',function(){
            Swal.mixin({toast:true,position:'top-end',showConfirmButton:false,timer:3000,timerProgressBar:true,background:'#0d1420',color:'#fff',didOpen:t=>{t.onmouseenter=Swal.stopTimer;t.onmouseleave=Swal.resumeTimer;}}).fire({icon:'success',title:"{{ session('success') }}"});
        });
    </script>
    @endif


    {{-- ══════════════════════════════════════════════════════
         MODAL: "VER TUDO" — com List.js e filtros avançados
         ══════════════════════════════════════════════════════ --}}
    <x-modal-todos-livros :categorias="$categorias" :autores="$autores" :livros="$livros" />


    {{-- ══════════════════════════════════════════════════════
         MODAL: "VER TUDO" — com List.js e filtros avançados
         ══════════════════════════════════════════════════════ --}}
    <x-modal-todos-livros :categorias="$categorias" :autores="$autores" :livros="$livros" />

</x-app-layout>
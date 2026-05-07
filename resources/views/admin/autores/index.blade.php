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
                    <p class="text-[10px] font-bold uppercase tracking-[.15em] text-blue-500 mb-0.5">Admin</p>
                    <h1 class="text-lg font-black text-slate-900 dark:text-white">Gerenciar Autores</h1>
                </div>
            </div>
            <div class="flex items-center gap-2">
                <a href="{{ route('autores.create') }}" class="inline-flex items-center gap-2 px-4 py-2 rounded-md bg-[#1E3A8A] text-white text-[11px] font-black uppercase tracking-widest hover:bg-blue-700 transition">
                    <i class="ph ph-plus text-sm"></i>
                    Novo Autor
                </a>
                <button type="button" @click="dark = !dark" class="w-9 h-9 rounded-md bg-white dark:bg-white/5 border border-slate-200 dark:border-white/10 text-slate-600 dark:text-gray-300 hover:text-slate-900 dark:hover:text-white hover:bg-slate-50 dark:hover:bg-white/10 transition">
                    <i class="ph text-sm" :class="dark ? 'ph-sun' : 'ph-moon'"></i>
                </button>
                <div class="w-9 h-9 rounded-full bg-gradient-to-br from-[#1E3A8A] to-blue-700 flex items-center justify-center ring-1 ring-blue-500/30 shrink-0">
                    <span class="text-white text-[10px] font-black tracking-tight select-none">{{ auth()->user()->nome ? collect(explode(' ', auth()->user()->nome))->map(fn($p) => strtoupper(mb_substr($p,0,1)))->take(2)->join('') : 'AD' }}</span>
                </div>
            </div>
        </div>
    </x-slot>

    <style>
        .bg-shelf { background: linear-gradient(90deg, transparent, rgba(147,197,253,.07) 20%, rgba(147,197,253,.07) 80%, transparent); }
        .bg-icon  { color: rgba(147,197,253,.07); pointer-events: none; user-select: none; }
        #bg-glow-1 { background: radial-gradient(circle, rgba(30,58,138,.3) 0%, transparent 70%); }
        #bg-glow-2 { background: radial-gradient(circle, rgba(245,158,11,.15) 0%, transparent 70%); }
    </style>

    <div class="-mx-4 px-4 py-10 bg-slate-50 dark:bg-[#0f172a] sm:-mx-6 sm:px-6 lg:-mx-8 lg:px-8 min-h-screen relative">

        {{-- ══ DECORATIVE BACKGROUND ══ --}}
        <div class="fixed inset-0 z-0 pointer-events-none overflow-hidden" aria-hidden="true">
            <svg class="absolute inset-0 w-full h-full" xmlns="http://www.w3.org/2000/svg">
                <defs>
                    <pattern id="bg-dots-autores" width="28" height="28" patternUnits="userSpaceOnUse">
                        <circle cx="1" cy="1" r="1" fill="#93c5fd" opacity="0.08"/>
                    </pattern>
                </defs>
                <rect width="100%" height="100%" fill="url(#bg-dots-autores)"/>
            </svg>
            <div id="bg-glow-1" class="absolute -top-28 -left-20 w-96 h-96 rounded-full blur-[90px]"></div>
            <div id="bg-glow-2" class="absolute -bottom-20 -right-14 w-72 h-72 rounded-full blur-[80px]"></div>
            <div class="bg-shelf absolute left-0 right-0 h-px top-[22%]"></div>
            <div class="bg-shelf absolute left-0 right-0 h-px top-[58%]"></div>
            <i class="ph ph-book bg-icon absolute left-[3%] top-[5%] text-[28px]"></i>
            <i class="ph ph-pen-nib bg-icon absolute left-[87%] top-[8%] text-[22px]"></i>
            <i class="ph ph-bookmark bg-icon absolute left-[14%] top-[58%] text-[34px]"></i>
            <i class="ph ph-graduation-cap bg-icon absolute left-[74%] top-[54%] text-[26px]"></i>
            <i class="ph ph-scroll bg-icon absolute left-[44%] top-[78%] text-[18px]"></i>
            <i class="ph ph-library bg-icon absolute left-[91%] top-[72%] text-[30px]"></i>
        </div>

        <div class="max-w-6xl mx-auto relative z-10 space-y-8">

            {{-- ══ HERO SECTION ══ --}}
            <div class="bg-white dark:bg-[#0d1420] border border-slate-200 dark:border-white/5 rounded-md p-5">
                <div>
                    <p class="text-[10px] font-bold uppercase tracking-[.15em] text-blue-500 mb-1">Painel Administrativo</p>
                    <h2 class="text-2xl font-black text-slate-900 dark:text-white" style="font-family: 'Merriweather', serif;">Gerenciar Autores</h2>
                    <p class="text-slate-500 dark:text-gray-500 text-sm mt-1">Adicione, edite ou remova autores da biblioteca.</p>
                </div>
            </div>

            @if(session('sucesso'))
                <div
                    class="mb-6 text-sm text-green-400 bg-green-900/30 border border-green-500/30 p-4 rounded-md font-semibold">
                    {{ session('sucesso') }}
                </div>
            @endif

            {{-- ══ AUTORES GRID ══ --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                @forelse($autores as $autor)
                    <div class="bg-white dark:bg-[#0d1420] border border-slate-200 dark:border-white/5 rounded-md p-5 hover:border-blue-400 dark:hover:border-blue-600 transition">
                        <div class="flex justify-between items-start gap-3">
                            <div>
                                <h3 class="text-lg font-bold text-slate-900 dark:text-white">{{ $autor->nome }}</h3>
                                <p class="text-slate-500 dark:text-gray-400 text-sm mt-1">{{ $autor->nacionalidade ?? 'Nacionalidade não informada' }}</p>
                            </div>
                            <div class="flex items-center gap-2 flex-wrap justify-end">
                                <a href="{{ route('autores.show', $autor->id) }}" class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-md bg-blue-600/10 border border-blue-600/30 text-blue-700 dark:text-blue-400 hover:bg-blue-600/20 text-[11px] font-bold uppercase tracking-widest transition">
                                    <i class="ph ph-eye text-sm"></i> Ver
                                </a>
                                <a href="{{ route('autores.edit', $autor->id) }}" class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-md bg-amber-600/10 border border-amber-600/30 text-amber-700 dark:text-amber-400 hover:bg-amber-600/20 text-[11px] font-bold uppercase tracking-widest transition">
                                    <i class="ph ph-pencil text-sm"></i> Editar
                                </a>
                                <form method="POST" action="{{ route('autores.destroy', $autor->id) }}" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-md bg-red-600/10 border border-red-600/30 text-red-700 dark:text-red-400 hover:bg-red-600/20 text-[11px] font-bold uppercase tracking-widest transition" onclick="return confirm('Tem certeza que deseja excluir este autor?')">
                                        <i class="ph ph-trash text-sm"></i> Excluir
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-span-2 py-12 flex flex-col items-center justify-center text-center">
                        <i class="ph ph-book text-slate-300 dark:text-slate-500 text-5xl mb-3"></i>
                        <p class="text-slate-500 dark:text-slate-400 font-bold">Nenhum autor cadastrado</p>
                        <p class="text-slate-400 dark:text-slate-500 text-sm mt-1">Comece criando um novo autor clicando no botão acima</p>
                    </div>
                @endforelse
            </div>

        </div>
    </div>

</x-app-layout>
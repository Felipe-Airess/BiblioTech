<x-app-layout>
    @php
        $totalLivros = $autor->livros->count();
        $categoriasAutor = $autor->livros->pluck('categoria')->filter()->unique()->values();
        $bestsellersAutor = $autor->livros->where('e_bestseller', true)->count();
        $primeiroLivro = $autor->livros->first();
    @endphp

    <div class="-mx-4 min-h-screen bg-gradient-to-b from-slate-100 via-blue-50 to-slate-100 px-4 py-8 dark:from-[#0f172a] dark:via-[#0f172a] dark:to-[#0b1120] sm:-mx-6 sm:px-6 lg:-mx-8 lg:px-8">
        <div class="fixed inset-0 z-0 pointer-events-none overflow-hidden" aria-hidden="true">
            <svg class="absolute inset-0 h-full w-full" xmlns="http://www.w3.org/2000/svg">
                <defs>
                    <pattern id="author-show-dots" width="28" height="28" patternUnits="userSpaceOnUse">
                        <circle cx="1" cy="1" r="1" fill="#1E3A8A" opacity="0.08"/>
                    </pattern>
                </defs>
                <rect width="100%" height="100%" fill="url(#author-show-dots)"/>
            </svg>
            <i class="ph ph-pen-nib absolute left-[7%] top-[12%] text-[42px] text-amber-500/15 dark:text-blue-300/10"></i>
            <i class="ph ph-books absolute right-[10%] top-[18%] text-[38px] text-blue-800/10 dark:text-amber-300/10"></i>
            <i class="ph ph-scroll absolute right-[20%] bottom-[14%] text-[46px] text-amber-500/15 dark:text-blue-300/10"></i>
        </div>

        <div class="relative z-10 mx-auto max-w-7xl space-y-6">
            <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
                <a href="{{ route('dashboard') }}" class="inline-flex h-10 items-center gap-2 rounded-md border border-slate-200 bg-white px-4 text-[11px] font-black uppercase tracking-widest text-slate-700 transition hover:bg-slate-50 dark:border-white/10 dark:bg-white/5 dark:text-slate-300 dark:hover:bg-white/10">
                    <i class="ph ph-arrow-left"></i>
                    Voltar ao acervo
                </a>

                @if(auth()->check())
                    <a href="{{ route('autores.edit', $autor->id) }}" class="inline-flex h-10 items-center gap-2 rounded-md bg-[#1E3A8A] px-4 text-[11px] font-black uppercase tracking-widest text-white transition hover:bg-blue-800">
                        <i class="ph ph-pencil-simple"></i>
                        Editar autor
                    </a>
                @endif
            </div>

            <section class="overflow-hidden rounded-md border border-slate-200 bg-white/95 shadow-sm dark:border-white/[.06] dark:bg-[#0d1420]/95">
                <div class="grid grid-cols-1 lg:grid-cols-[340px_minmax(0,1fr)]">
                    <div class="border-b border-slate-200 bg-slate-50 p-6 dark:border-white/[.06] dark:bg-white/[.03] lg:border-b-0 lg:border-r">
                        <div class="mx-auto max-w-[260px] text-center">
                            <div class="relative mx-auto h-44 w-44 overflow-hidden rounded-full bg-slate-100 shadow-2xl shadow-slate-950/10 ring-4 ring-amber-300/70 dark:bg-white/10 dark:ring-amber-500/30">
                                @if($autor->foto)
                                    <img src="{{ asset('storage/' . $autor->foto) }}" alt="{{ $autor->nome }}" class="h-full w-full object-cover">
                                @else
                                    <div class="flex h-full w-full items-center justify-center bg-gradient-to-br from-blue-100 to-amber-50 text-slate-400 dark:from-blue-950/40 dark:to-amber-950/20">
                                        <i class="ph ph-user text-6xl"></i>
                                    </div>
                                @endif
                            </div>

                            <div class="mt-5">
                                <p class="text-[10px] font-black uppercase tracking-[.18em] text-amber-700 dark:text-amber-300">Perfil literário</p>
                                <h1 class="mt-2 text-3xl font-black leading-tight text-slate-950 dark:text-white font-serif">{{ $autor->nome }}</h1>
                                @if($autor->nacionalidade)
                                    <p class="mt-1 text-sm font-semibold text-blue-700 dark:text-blue-300">{{ $autor->nacionalidade }}</p>
                                @endif
                            </div>

                            <div class="mt-5 grid grid-cols-3 gap-2">
                                <div class="rounded-md border border-slate-200 bg-white p-3 dark:border-white/10 dark:bg-[#0d1420]">
                                    <p class="text-[10px] uppercase tracking-widest text-slate-500">Obras</p>
                                    <p class="mt-1 text-lg font-black text-slate-950 dark:text-white">{{ $totalLivros }}</p>
                                </div>
                                <div class="rounded-md border border-slate-200 bg-white p-3 dark:border-white/10 dark:bg-[#0d1420]">
                                    <p class="text-[10px] uppercase tracking-widest text-slate-500">Gêneros</p>
                                    <p class="mt-1 text-lg font-black text-slate-950 dark:text-white">{{ $categoriasAutor->count() }}</p>
                                </div>
                                <div class="rounded-md border border-slate-200 bg-white p-3 dark:border-white/10 dark:bg-[#0d1420]">
                                    <p class="text-[10px] uppercase tracking-widest text-slate-500">Selos</p>
                                    <p class="mt-1 text-lg font-black text-slate-950 dark:text-white">{{ $bestsellersAutor }}</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="p-6 sm:p-8 lg:p-10">
                        <div class="flex flex-wrap items-center gap-2">
                            @if($autor->data_nascimento)
                                <span class="inline-flex items-center gap-1.5 rounded-md border border-slate-200 bg-slate-50 px-2.5 py-1 text-[10px] font-bold uppercase tracking-widest text-slate-600 dark:border-white/10 dark:bg-white/5 dark:text-slate-300">
                                    <i class="ph ph-calendar-blank"></i>
                                    {{ $autor->data_nascimento->format('d/m/Y') }}
                                </span>
                            @endif
                            @foreach($categoriasAutor->take(4) as $categoria)
                                <span class="inline-flex items-center rounded-md border border-blue-200 bg-blue-50 px-2.5 py-1 text-[10px] font-bold uppercase tracking-widest text-blue-700 dark:border-blue-500/30 dark:bg-blue-500/10 dark:text-blue-300">
                                    {{ $categoria }}
                                </span>
                            @endforeach
                        </div>

                        <h2 class="mt-4 max-w-4xl text-3xl font-black leading-tight text-slate-950 dark:text-white font-serif md:text-5xl">
                            {{ $autor->nome }} no acervo da biblioteca
                        </h2>
                        <p class="mt-4 max-w-3xl text-sm leading-relaxed text-slate-600 dark:text-slate-400 md:text-base">
                            {{ $autor->biografia ?: 'Biografia ainda nao cadastrada. As obras deste autor aparecem abaixo para consulta no acervo.' }}
                        </p>

                        @if($primeiroLivro)
                            <div class="mt-8 rounded-md border border-amber-200 bg-amber-50 p-4 dark:border-amber-500/20 dark:bg-amber-500/10">
                                <div class="flex flex-col gap-4 sm:flex-row sm:items-center">
                                    <div class="h-24 w-16 shrink-0 overflow-hidden rounded-md bg-white shadow-sm dark:bg-[#0d1420]">
                                        @if($primeiroLivro->capa)
                                            <img src="{{ asset('storage/' . $primeiroLivro->capa) }}" alt="{{ $primeiroLivro->titulo }}" class="h-full w-full object-cover">
                                        @else
                                            <div class="flex h-full w-full items-center justify-center">
                                                <i class="ph ph-book text-2xl text-slate-400"></i>
                                            </div>
                                        @endif
                                    </div>
                                    <div class="min-w-0">
                                        <p class="text-[10px] font-black uppercase tracking-[.16em] text-amber-800 dark:text-amber-300">Obra em destaque</p>
                                        <h3 class="mt-1 text-lg font-black text-slate-950 dark:text-white">{{ $primeiroLivro->titulo }}</h3>
                                        <p class="mt-1 line-clamp-2 text-sm text-amber-900 dark:text-amber-100">{{ $primeiroLivro->sinopse ?: 'Veja os detalhes desta obra no acervo.' }}</p>
                                    </div>
                                    <a href="{{ route('livros.show', $primeiroLivro->id) }}" class="inline-flex h-10 shrink-0 items-center justify-center gap-2 rounded-md bg-[#1E3A8A] px-4 text-[11px] font-black uppercase tracking-widest text-white transition hover:bg-blue-800 sm:ml-auto">
                                        Ver obra
                                    </a>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </section>

            <section class="rounded-md border border-slate-200 bg-white/95 p-5 shadow-sm dark:border-white/[.06] dark:bg-[#0d1420]/95 sm:p-6">
                <div class="mb-6 flex flex-col gap-3 sm:flex-row sm:items-end sm:justify-between">
                    <div>
                        <p class="text-[10px] font-black uppercase tracking-[.18em] text-blue-700 dark:text-blue-300">Prateleira do autor</p>
                        <h2 class="text-2xl font-black text-slate-950 dark:text-white font-serif">Obras cadastradas</h2>
                    </div>
                    <span class="text-sm font-bold text-slate-500 dark:text-slate-400">{{ $totalLivros }} título{{ $totalLivros === 1 ? '' : 's' }}</span>
                </div>

                @if($autor->livros->count() > 0)
                    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4">
                        @foreach($autor->livros as $livro)
                            <article class="group flex flex-col overflow-hidden rounded-md border border-slate-200 bg-white shadow-sm transition hover:border-blue-300 hover:shadow-lg hover:shadow-slate-950/10 dark:border-white/5 dark:bg-[#0d1420] dark:hover:border-blue-500/40">
                                <a href="{{ route('livros.show', $livro->id) }}" class="flex flex-1 flex-col">
                                    <div class="relative h-64 overflow-hidden bg-slate-100 dark:bg-white/10">
                                        @if($livro->capa)
                                            <img src="{{ asset('storage/' . $livro->capa) }}" alt="{{ $livro->titulo }}" class="h-full w-full object-cover transition duration-500 group-hover:scale-105">
                                        @else
                                            <div class="flex h-full w-full items-center justify-center bg-gradient-to-br from-blue-50 to-amber-50 dark:from-blue-950/30 dark:to-amber-950/20">
                                                <i class="ph ph-book text-4xl text-slate-400"></i>
                                            </div>
                                        @endif
                                        <div class="absolute inset-x-0 bottom-0 h-24 bg-gradient-to-t from-slate-950/70 to-transparent"></div>
                                        @if($livro->e_bestseller)
                                            <span class="absolute left-3 top-3 rounded-md bg-[#F59E0B] px-2 py-1 text-[10px] font-black uppercase tracking-widest text-slate-950">Bestseller</span>
                                        @endif
                                        <span class="absolute bottom-3 left-3 rounded-md bg-white/90 px-2 py-1 text-[10px] font-black uppercase tracking-widest text-slate-800">{{ $livro->categoria ?? 'Acervo' }}</span>
                                    </div>
                                    <div class="flex flex-1 flex-col p-4">
                                        <h3 class="line-clamp-2 text-sm font-black leading-snug text-slate-950 transition group-hover:text-blue-700 dark:text-white dark:group-hover:text-blue-300">{{ $livro->titulo }}</h3>
                                        <p class="mt-2 line-clamp-3 text-xs leading-relaxed text-slate-500 dark:text-slate-400">{{ $livro->sinopse ?: 'Detalhes disponíveis na página do livro.' }}</p>
                                        <div class="mt-4 flex items-center justify-between gap-3 text-[10px] font-black uppercase tracking-widest text-slate-500">
                                            <span>{{ (int) $livro->quantidade }} ex.</span>
                                            <span>{{ $livro->data_publicacao ? \Carbon\Carbon::parse($livro->data_publicacao)->format('Y') : '—' }}</span>
                                        </div>
                                    </div>
                                </a>
                            </article>
                        @endforeach
                    </div>
                @else
                    <div class="rounded-md border border-slate-200 bg-slate-50 p-8 text-center dark:border-white/10 dark:bg-white/[.03]">
                        <i class="ph ph-books mb-3 block text-5xl text-slate-300 dark:text-slate-600"></i>
                        <p class="text-sm font-bold text-slate-600 dark:text-slate-400">Este autor ainda não tem livros cadastrados.</p>
                    </div>
                @endif
            </section>
        </div>
    </div>
</x-app-layout>

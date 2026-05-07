<x-app-layout>
    <div class="min-h-screen bg-[#09090b] text-gray-300 py-8 font-sans selection:bg-gray-100 selection:text-black">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
            
            <nav class="mb-8">
                <a href="{{ route('dashboard') }}" class="text-sm text-gray-500 hover:text-white transition-colors flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" /></svg>
                    Voltar ao acervo
                </a>
            </nav>

            <div class="border border-gray-800 bg-[#0f1115] rounded-sm flex flex-col md:flex-row">
                
                <div class="md:w-1/3 p-8 border-b md:border-b-0 md:border-r border-gray-800 flex items-start justify-center bg-[#09090b]/50">
                    @if($livro->capa)
                        <img src="{{ asset('storage/' . $livro->capa) }}" alt="{{ $livro->titulo }}" class="w-full max-w-[280px] h-auto object-cover rounded-sm border border-gray-800 shadow-sm">
                    @else
                        <div class="w-full aspect-[2/3] max-w-[280px] bg-[#1a1d24] border border-gray-800 rounded-sm flex flex-col items-center justify-center text-gray-600">
                            <svg class="w-12 h-12 mb-3 opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-width="1" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" /></svg>
                            <span class="text-xs font-medium tracking-wide">Sem Capa</span>
                        </div>
                    @endif
                </div>

                <div class="md:w-2/3 p-8 md:p-12 flex flex-col">
                    
                    <div class="flex flex-wrap items-center gap-3 text-xs text-gray-500 mb-4 font-medium">
                        <span class="text-gray-300">{{ $livro->categoria }}</span>
                        <span>&mdash;</span>
                        <span>{{ \Carbon\Carbon::parse($livro->data_publicacao)->format('Y') }}</span>
                        
                        @if($livro->e_bestseller)
                            <span>&mdash;</span>
                            <span class="text-gray-300 flex items-center gap-1">
                                <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                                Bestseller
                            </span>
                        @endif
                    </div>

                    <h1 class="text-3xl md:text-4xl font-bold text-white tracking-tight mb-2">{{ $livro->titulo }}</h1>
                    <p class="text-lg text-gray-400 mb-4">
                        por <a href="{{ route('autores.show', $livro->autor->id) }}" class="hover:text-[#F59E0B] transition-colors">{{ $livro->autor->nome }}</a>
                    </p>

                    @if($livro->editora || $livro->paginas)
                        <div class="flex flex-wrap items-center gap-4 text-sm text-gray-500 mb-4">
                            @if($livro->editora)
                                <span>Editora: <span class="text-gray-300">{{ $livro->editora }}</span></span>
                            @endif
                            @if($livro->paginas)
                                <span>Páginas: <span class="text-gray-300">{{ $livro->paginas }}</span></span>
                            @endif
                        </div>
                    @endif
                    
                    <hr class="border-gray-800 mb-8">

                    <div class="mb-10 flex-grow">
                        <h3 class="text-sm font-semibold text-white mb-3">Sobre a obra</h3>
                        <p class="text-gray-400 text-sm leading-relaxed">
                            {{ $livro->sinopse ?? 'Nenhuma sinopse disponível para esta obra.' }}
                        </p>

                        @if($livro->preview)
                            <div class="mt-6">
                                <h3 class="text-sm font-semibold text-white mb-3">Prévia das Páginas</h3>
                                <div class="bg-[#1a1d24] border border-gray-800 rounded-sm p-4">
                                    <p class="text-gray-300 text-sm leading-relaxed italic">
                                        "{{ Str::limit($livro->preview, 500) }}"
                                    </p>
                                </div>
                            </div>
                        @endif
                    </div>

                    <div class="mt-auto flex flex-col sm:flex-row items-center gap-4">
                        @if(auth()->guard('membro')->check())
                            @if($livro->quantidade > 0)
                                <form action="{{ route('livros.alugar', $livro->id) }}" method="POST" class="w-full sm:w-auto flex-1">
                                    @csrf
                                    <button type="submit" class="w-full bg-white text-black hover:bg-gray-200 px-8 py-3.5 rounded-sm font-semibold text-sm transition-colors flex items-center justify-center gap-2">
                                        Solicitar Empréstimo
                                    </button>
                                </form>
                                <div class="w-full sm:w-auto px-6 py-3.5 border border-gray-800 rounded-sm text-center">
                                    <span class="text-sm font-medium text-gray-300">{{ $livro->quantidade }} em estoque</span>
                                </div>
                            @else
                                <button disabled class="w-full bg-[#1a1d24] text-gray-500 px-8 py-3.5 rounded-sm font-medium text-sm border border-gray-800 cursor-not-allowed">
                                    Obra Indisponível
                                </button>
                            @endif
                        @elseif(auth()->check())
                            <div class="w-full p-4 bg-[#1a1d24] border border-gray-800 rounded-sm flex items-center justify-between">
                                <span class="text-sm text-gray-400">Modo Administrador</span>
                                <a href="{{ route('livros.edit', $livro->id) }}" class="text-sm text-white hover:underline">Editar Obra</a>
                            </div>
                        @else
                            <a href="{{ route('login') }}" class="w-full sm:w-auto bg-gray-800 hover:bg-gray-700 text-white px-8 py-3.5 rounded-sm font-medium text-sm transition-colors text-center border border-gray-700">
                                Fazer login para alugar
                            </a>
                        @endif
                    </div>

                    <div class="mt-10 border-t border-gray-800 pt-8">
                        <div class="flex flex-wrap items-center justify-between gap-3 mb-6">
                            <div>
                                <h3 class="text-sm font-semibold text-white">Comentários</h3>
                                <p class="text-xs text-gray-500">
                                    {{ $totalComentarios }} comentário{{ $totalComentarios === 1 ? '' : 's' }}
                                </p>
                            </div>
                            <div class="flex items-center gap-2 text-sm text-gray-400">
                                <div class="flex items-center gap-1">
                                    @for($i = 1; $i <= 5; $i++)
                                        <i class="ph {{ $mediaNota && $mediaNota >= $i ? 'ph-fill ph-star text-amber-400' : 'ph-star text-gray-600' }}"></i>
                                    @endfor
                                </div>
                                <span>
                                    {{ $mediaNota ? number_format($mediaNota, 1, ',', '.') : '0,0' }} / 5
                                </span>
                            </div>
                        </div>

                        @if(session('success'))
                            <div class="mb-4 rounded-sm border border-emerald-500/30 bg-emerald-900/10 px-4 py-3 text-xs text-emerald-300">
                                {{ session('success') }}
                            </div>
                        @endif

                        @if($errors->any())
                            <div class="mb-4 rounded-sm border border-red-500/30 bg-red-900/10 px-4 py-3 text-xs text-red-300">
                                {{ $errors->first() }}
                            </div>
                        @endif

                        @if(auth()->guard('membro')->check() || auth()->check())
                            @if($comentarioExistente)
                                <div class="mb-8 rounded-sm border border-amber-500/30 bg-amber-900/10 px-4 py-3 text-xs text-amber-300">
                                    Voce ja comentou este livro. Edite seu comentario abaixo.
                                </div>
                            @elseif(!($podeComentar ?? false))
                                <div class="mb-8 rounded-sm border border-gray-800 bg-[#1a1d24] px-4 py-3 text-xs text-gray-400">
                                    Só é possível comentar livros que você já devolveu.
                                </div>
                            @else
                                <form action="{{ route('livros.comentarios.store', $livro->id) }}" method="POST" class="mb-8">
                                    @csrf
                                    <div class="grid grid-cols-1 gap-4">
                                        <div>
                                            <label for="nota" class="block text-[10px] font-bold uppercase tracking-[.12em] text-gray-500 mb-2">Nota</label>
                                            <select id="nota" name="nota" class="w-full bg-[#1a1d24] border border-gray-800 text-gray-200 rounded-sm px-3 py-2 text-sm">
                                                <option value="">Selecione</option>
                                                <option value="5" @selected(old('nota') == 5)>5 - Excelente</option>
                                                <option value="4" @selected(old('nota') == 4)>4 - Muito bom</option>
                                                <option value="3" @selected(old('nota') == 3)>3 - Bom</option>
                                                <option value="2" @selected(old('nota') == 2)>2 - Regular</option>
                                                <option value="1" @selected(old('nota') == 1)>1 - Ruim</option>
                                            </select>
                                        </div>
                                        <div>
                                            <label for="comentario" class="block text-[10px] font-bold uppercase tracking-[.12em] text-gray-500 mb-2">Comentario</label>
                                            <textarea id="comentario" name="comentario" rows="4" class="w-full bg-[#1a1d24] border border-gray-800 text-gray-200 rounded-sm px-3 py-2 text-sm" placeholder="Compartilhe sua opiniao...">{{ old('comentario') }}</textarea>
                                        </div>
                                    </div>
                                    <div class="mt-4">
                                        <button type="submit" class="bg-white text-black hover:bg-gray-200 px-6 py-2.5 rounded-sm font-semibold text-xs uppercase tracking-widest">Enviar comentario</button>
                                    </div>
                                </form>
                            @endif
                        @else
                            <div class="mb-8 rounded-sm border border-gray-800 bg-[#1a1d24] px-4 py-3 text-xs text-gray-400">
                                <a href="{{ route('login') }}" class="text-white hover:underline">Faca login</a> para comentar.
                            </div>
                        @endif

                        <div class="space-y-4">
                            @forelse($comentarios as $comentario)
                                <div class="border border-gray-800 bg-[#0f1115] rounded-sm p-4">
                                    <div class="flex items-center justify-between gap-3 mb-2">
                                        <div class="text-sm text-gray-200 font-semibold">
                                            {{ $comentario->membro->nome ?? $comentario->user->name ?? 'Leitor' }}
                                        </div>
                                        <div class="flex items-center gap-1 text-xs text-amber-400">
                                            @for($i = 1; $i <= 5; $i++)
                                                <i class="ph {{ $comentario->nota >= $i ? 'ph-fill ph-star' : 'ph-star text-gray-600' }}"></i>
                                            @endfor
                                        </div>
                                    </div>
                                    <p class="text-sm text-gray-400 leading-relaxed">{{ $comentario->comentario }}</p>
                                    <p class="mt-3 text-[10px] uppercase tracking-widest text-gray-600">
                                        {{ $comentario->created_at->format('d/m/Y H:i') }}
                                    </p>

                                    @php
                                        $isOwner = (auth()->guard('membro')->check() && auth()->guard('membro')->id() === $comentario->membro_id)
                                            || (auth()->check() && auth()->id() === $comentario->user_id);
                                    @endphp
                                    @if($isOwner)
                                        <details class="mt-4">
                                            <summary class="text-[11px] uppercase tracking-widest text-amber-400 cursor-pointer">Editar</summary>
                                            <form action="{{ route('livros.comentarios.update', [$livro->id, $comentario->id]) }}" method="POST" class="mt-3">
                                                @csrf
                                                @method('PUT')
                                                <div class="grid grid-cols-1 gap-4">
                                                    <div>
                                                        <label class="block text-[10px] font-bold uppercase tracking-[.12em] text-gray-500 mb-2" for="nota-{{ $comentario->id }}">Nota</label>
                                                        <select id="nota-{{ $comentario->id }}" name="nota" class="w-full bg-[#1a1d24] border border-gray-800 text-gray-200 rounded-sm px-3 py-2 text-sm">
                                                            <option value="5" @selected($comentario->nota == 5)>5 - Excelente</option>
                                                            <option value="4" @selected($comentario->nota == 4)>4 - Muito bom</option>
                                                            <option value="3" @selected($comentario->nota == 3)>3 - Bom</option>
                                                            <option value="2" @selected($comentario->nota == 2)>2 - Regular</option>
                                                            <option value="1" @selected($comentario->nota == 1)>1 - Ruim</option>
                                                        </select>
                                                    </div>
                                                    <div>
                                                        <label class="block text-[10px] font-bold uppercase tracking-[.12em] text-gray-500 mb-2" for="comentario-{{ $comentario->id }}">Comentario</label>
                                                        <textarea id="comentario-{{ $comentario->id }}" name="comentario" rows="4" class="w-full bg-[#1a1d24] border border-gray-800 text-gray-200 rounded-sm px-3 py-2 text-sm">{{ $comentario->comentario }}</textarea>
                                                    </div>
                                                </div>
                                                <div class="mt-4">
                                                    <button type="submit" class="bg-white text-black hover:bg-gray-200 px-5 py-2 rounded-sm font-semibold text-[11px] uppercase tracking-widest">Salvar</button>
                                                </div>
                                            </form>
                                            <form action="{{ route('livros.comentarios.destroy', [$livro->id, $comentario->id]) }}" method="POST" class="mt-3" onsubmit="return confirm('Excluir comentario?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-[11px] uppercase tracking-widest text-red-400 hover:text-red-300">Excluir</button>
                                            </form>
                                        </details>
                                    @endif
                                </div>
                            @empty
                                <div class="text-sm text-gray-500">Ainda nao ha comentarios.</div>
                            @endforelse
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>
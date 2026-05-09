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
                    <h1 class="text-lg font-black text-slate-900 dark:text-white">Editar Livro</h1>
                </div>
            </div>
            <div class="flex items-center gap-2">
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
                    <pattern id="bg-dots-livros-edit" width="28" height="28" patternUnits="userSpaceOnUse">
                        <circle cx="1" cy="1" r="1" fill="#93c5fd" opacity="0.08"/>
                    </pattern>
                </defs>
                <rect width="100%" height="100%" fill="url(#bg-dots-livros-edit)"/>
            </svg>
            <div id="bg-glow-1" class="absolute -top-28 -left-20 w-96 h-96 rounded-full blur-[90px]"></div>
            <div id="bg-glow-2" class="absolute -bottom-20 -right-14 w-72 h-72 rounded-full blur-[80px]"></div>
            <div class="bg-shelf absolute left-0 right-0 h-px top-[22%]"></div>
            <div class="bg-shelf absolute left-0 right-0 h-px top-[58%]"></div>
            <i class="ph ph-book-bookmark bg-icon absolute left-[3%] top-[5%] text-[28px]"></i>
            <i class="ph ph-pencil bg-icon absolute left-[87%] top-[8%] text-[22px]"></i>
            <i class="ph ph-book bg-icon absolute left-[14%] top-[58%] text-[34px]"></i>
        </div>

        <div class="max-w-6xl mx-auto relative z-10">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
        
        <div class="lg:col-span-7 flex flex-col justify-center">
            
            <div class="mb-8 border-b border-slate-200 dark:border-gray-700 pb-4">
                <h2 class="text-3xl text-slate-900 dark:text-white tracking-tight uppercase font-black" style="font-family: 'Merriweather', serif;">
                    Editar <span class="text-[#F59E0B]">Livro</span>
                </h2>
                <p class="text-slate-600 dark:text-gray-500 text-[10px] font-bold uppercase tracking-[0.2em] mt-1">{{ $livro->titulo }}</p>
            </div>

            @if(session('sucesso'))
                <div class="mb-6 text-sm text-green-400 bg-green-900/30 border border-green-500/30 p-4 rounded-md font-semibold">
                    {{ session('sucesso') }}
                </div>
            @endif

            <form method="POST" action="{{ route('livros.update', $livro->id) }}" enctype="multipart/form-data" class="space-y-6">
                @csrf
                @method('PUT')

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="group">
                        <label for="titulo" class="block text-xs font-bold text-slate-600 dark:text-gray-400 uppercase tracking-wider mb-2 group-focus-within:text-[#F59E0B] transition-colors">Título do Livro</label>
                        <input id="titulo" type="text" name="titulo" value="{{ old('titulo', $livro->titulo) }}" required autofocus
                            class="block w-full bg-white dark:bg-gray-900 border border-slate-200 dark:border-gray-700 text-slate-900 dark:text-white focus:border-[#F59E0B] focus:ring-1 focus:ring-[#F59E0B] rounded-md shadow-sm transition-all duration-300 hover:border-slate-300 dark:hover:border-gray-500" />
                        <x-input-error :messages="$errors->get('titulo')" class="mt-2" />
                    </div>

                    <div class="group">
                        <label for="autor_id" class="block text-xs font-bold text-slate-600 dark:text-gray-400 uppercase tracking-wider mb-2 group-focus-within:text-[#F59E0B] transition-colors">Autor da Obra</label>
                        <select id="autor_id" name="autor_id" required
                            class="block w-full bg-white dark:bg-gray-900 border border-slate-200 dark:border-gray-700 text-slate-900 dark:text-white focus:border-[#F59E0B] focus:ring-1 focus:ring-[#F59E0B] rounded-md shadow-sm transition-all duration-300 hover:border-slate-300 dark:hover:border-gray-500">
                            <option value="">Selecione um autor</option>
                            @foreach($autores as $autor)
                                <option value="{{ $autor->id }}" {{ old('autor_id', $livro->autor_id) == $autor->id ? 'selected' : '' }}>{{ $autor->nome }}</option>
                            @endforeach
                        </select>
                        <x-input-error :messages="$errors->get('autor_id')" class="mt-2" />
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div class="group">
                        <label for="categoria" class="block text-xs font-bold text-slate-600 dark:text-gray-400 uppercase tracking-wider mb-2 group-focus-within:text-[#F59E0B] transition-colors">Categoria</label>
                        <select id="categoria" name="categoria" required
                            class="block w-full bg-white dark:bg-gray-900 border border-slate-200 dark:border-gray-700 text-slate-900 dark:text-white focus:border-[#F59E0B] focus:ring-1 focus:ring-[#F59E0B] rounded-md shadow-sm transition-all duration-300 hover:border-slate-300 dark:hover:border-gray-500">
                            <option value="">Selecione uma categoria</option>
                            @foreach($categorias as $cat)
                                <option value="{{ $cat }}" {{ old('categoria', $livro->categoria) == $cat ? 'selected' : '' }}>{{ $cat }}</option>
                            @endforeach
                        </select>
                        <a href="{{ route('categorias.index') }}" class="mt-2 inline-flex items-center gap-1 text-[11px] font-bold uppercase tracking-wider text-amber-600 dark:text-amber-300 hover:underline">
                            <i class="ph ph-tag"></i> Gerenciar categorias
                        </a>
                    </div>
                    
                    <div class="group">
                        <label for="quantidade" class="block text-xs font-bold text-slate-600 dark:text-gray-400 uppercase tracking-wider mb-2 group-focus-within:text-[#F59E0B] transition-colors">Estoque</label>
                        <input id="quantidade" type="number" name="quantidade" min="0" value="{{ old('quantidade', $livro->quantidade) }}" required
                            class="block w-full bg-white dark:bg-gray-900 border border-slate-200 dark:border-gray-700 text-slate-900 dark:text-white focus:border-[#F59E0B] focus:ring-1 focus:ring-[#F59E0B] rounded-md shadow-sm transition-all duration-300 hover:border-slate-300 dark:hover:border-gray-500" />
                    </div>

                    <div class="group">
                        <label for="data_publicacao" class="block text-xs font-bold text-slate-600 dark:text-gray-400 uppercase tracking-wider mb-2 group-focus-within:text-[#F59E0B] transition-colors">Lançamento</label>
                        <input id="data_publicacao" type="date" name="data_publicacao" value="{{ old('data_publicacao', $livro->data_publicacao ? (is_string($livro->data_publicacao) ? $livro->data_publicacao : $livro->data_publicacao->format('Y-m-d')) : '') }}" required
                            class="block w-full bg-white dark:bg-gray-900 border border-slate-200 dark:border-gray-700 text-slate-900 dark:text-white focus:border-[#F59E0B] focus:ring-1 focus:ring-[#F59E0B] rounded-md shadow-sm transition-all duration-300 hover:border-slate-300 dark:hover:border-gray-500" />
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="group">
                        <label for="estante" class="block text-xs font-bold text-slate-600 dark:text-gray-400 uppercase tracking-wider mb-2 group-focus-within:text-[#F59E0B] transition-colors">Estante</label>
                        <input id="estante" type="text" name="estante" value="{{ old('estante', $livro->estante) }}" placeholder="Ex: A3"
                            class="block w-full bg-white dark:bg-gray-900 border border-slate-200 dark:border-gray-700 text-slate-900 dark:text-white focus:border-[#F59E0B] focus:ring-1 focus:ring-[#F59E0B] rounded-md shadow-sm transition-all duration-300 hover:border-slate-300 dark:hover:border-gray-500" />
                    </div>

                    <div class="group">
                        <label for="localizacao" class="block text-xs font-bold text-slate-600 dark:text-gray-400 uppercase tracking-wider mb-2 group-focus-within:text-[#F59E0B] transition-colors">Localização física</label>
                        <input id="localizacao" type="text" name="localizacao" value="{{ old('localizacao', $livro->localizacao) }}" placeholder="Ex: Corredor 2, prateleira superior"
                            class="block w-full bg-white dark:bg-gray-900 border border-slate-200 dark:border-gray-700 text-slate-900 dark:text-white focus:border-[#F59E0B] focus:ring-1 focus:ring-[#F59E0B] rounded-md shadow-sm transition-all duration-300 hover:border-slate-300 dark:hover:border-gray-500" />
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 items-end">
                    <div class="md:col-span-2 group">
                        <label for="isbn" class="block text-xs font-bold text-slate-600 dark:text-gray-400 uppercase tracking-wider mb-2 group-focus-within:text-[#F59E0B] transition-colors">ISBN</label>
                        <input id="isbn" type="text" name="isbn" value="{{ old('isbn', $livro->isbn) }}" required placeholder="000-00-000-0000-0"
                            class="block w-full bg-white dark:bg-gray-900 border border-slate-200 dark:border-gray-700 text-slate-900 dark:text-white focus:border-[#F59E0B] focus:ring-1 focus:ring-[#F59E0B] rounded-md shadow-sm transition-all duration-300 hover:border-slate-300 dark:hover:border-gray-500" />
                    </div>

                    <div class="flex items-center bg-white dark:bg-gray-900 p-3 border border-slate-200 dark:border-gray-700 rounded-md h-[42px] hover:border-slate-300 dark:hover:border-gray-500 transition-colors">
                        <input id="e_bestseller" type="checkbox" name="e_bestseller" value="1" {{ old('e_bestseller', $livro->e_bestseller) ? 'checked' : '' }}
                            class="border-slate-300 dark:border-gray-600 bg-white dark:bg-gray-800 text-[#F59E0B] shadow-sm focus:ring-[#F59E0B] rounded-sm transition-all duration-300 cursor-pointer">
                        <label for="e_bestseller" class="ms-2 text-xs font-bold uppercase tracking-wider text-slate-700 dark:text-gray-300 cursor-pointer">Bestseller?</label>
                    </div>
                </div>

                <div class="group">
                    <label for="sinopse" class="block text-xs font-bold text-slate-600 dark:text-gray-400 uppercase tracking-wider mb-2 group-focus-within:text-[#F59E0B] transition-colors">Sinopse</label>
                    <textarea id="sinopse" name="sinopse" rows="4" 
                        class="block w-full bg-white dark:bg-gray-900 border border-slate-200 dark:border-gray-700 text-slate-900 dark:text-white focus:border-[#F59E0B] focus:ring-1 focus:ring-[#F59E0B] rounded-md shadow-sm transition-all duration-300 hover:border-slate-300 dark:hover:border-gray-500">{{ old('sinopse', $livro->sinopse) }}</textarea>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="group">
                        <label for="editora" class="block text-xs font-bold text-slate-600 dark:text-gray-400 uppercase tracking-wider mb-2 group-focus-within:text-[#F59E0B] transition-colors">Editora</label>
                        <input id="editora" type="text" name="editora" value="{{ old('editora', $livro->editora) }}"
                            class="block w-full bg-white dark:bg-gray-900 border border-slate-200 dark:border-gray-700 text-slate-900 dark:text-white focus:border-[#F59E0B] focus:ring-1 focus:ring-[#F59E0B] rounded-md shadow-sm transition-all duration-300 hover:border-slate-300 dark:hover:border-gray-500" />
                    </div>

                    <div class="group">
                        <label for="paginas" class="block text-xs font-bold text-slate-600 dark:text-gray-400 uppercase tracking-wider mb-2 group-focus-within:text-[#F59E0B] transition-colors">Número de Páginas</label>
                        <input id="paginas" type="number" name="paginas" min="1" value="{{ old('paginas', $livro->paginas) }}"
                            class="block w-full bg-white dark:bg-gray-900 border border-slate-200 dark:border-gray-700 text-slate-900 dark:text-white focus:border-[#F59E0B] focus:ring-1 focus:ring-[#F59E0B] rounded-md shadow-sm transition-all duration-300 hover:border-slate-300 dark:hover:border-gray-500" />
                    </div>
                </div>

                <div class="group">
                    <label for="preview" class="block text-xs font-bold text-slate-600 dark:text-gray-400 uppercase tracking-wider mb-2 group-focus-within:text-[#F59E0B] transition-colors">Prévia das Páginas</label>
                    <textarea id="preview" name="preview" rows="6" placeholder="Insira um trecho do livro para prévia..."
                        class="block w-full bg-white dark:bg-gray-900 border border-slate-200 dark:border-gray-700 text-slate-900 dark:text-white focus:border-[#F59E0B] focus:ring-1 focus:ring-[#F59E0B] rounded-md shadow-sm transition-all duration-300 hover:border-slate-300 dark:hover:border-gray-500">{{ old('preview', $livro->preview) }}</textarea>
                </div>

                <div class="bg-slate-100 dark:bg-gray-900/50 p-4 border border-dashed border-slate-300 dark:border-gray-600 rounded-md hover:border-[#F59E0B] transition-colors duration-300">
                    <label for="capa" class="block text-xs font-bold text-slate-600 dark:text-gray-400 uppercase tracking-wider mb-3">Trocar Capa (Opcional)</label>
                    <input id="capa" type="file" name="capa" accept="image/*" 
                        class="block w-full text-sm text-slate-600 dark:text-gray-400 file:mr-4 file:py-2 file:px-4 file:border-0 file:text-xs file:font-bold file:uppercase file:bg-[#1E3A8A] file:text-white hover:file:bg-[#2563EB] cursor-pointer rounded-md transition-colors" />
                </div>

                <div class="flex flex-col sm:flex-row items-center justify-end gap-4 pt-6 border-t border-slate-200 dark:border-gray-700">
                    <a href="{{ route('dashboard') }}" 
                       class="w-full sm:w-auto text-center px-6 py-3 bg-slate-200 hover:bg-slate-300 dark:bg-gray-700 dark:hover:bg-gray-600 border border-slate-300 dark:border-gray-600 font-bold text-xs text-slate-900 dark:text-white uppercase tracking-wider shadow-sm rounded-md transition-all duration-300">
                        Cancelar
                    </a>

                    <button type="submit" 
                            class="w-full sm:w-auto px-8 py-3 bg-[#1E3A8A] hover:bg-[#2563EB] border border-transparent font-bold text-xs text-white uppercase tracking-wider shadow-md shadow-blue-900/20 rounded-md transition-all duration-300 transform hover:-translate-y-1">
                        Atualizar Dados
                    </button>
                </div>
            </form>
        </div>

        <div class="lg:col-span-5 flex flex-col justify-center">
            <div class="sticky top-6">
                <label class="block text-xs font-bold text-slate-600 dark:text-gray-500 uppercase tracking-wider mb-4 border-b border-slate-200 dark:border-gray-700 pb-2">Pré-visualização da Edição</label>
                
                <div class="bg-white dark:bg-gray-900 border border-slate-200 dark:border-gray-700 shadow-xl rounded-md overflow-hidden flex flex-col max-w-sm mx-auto h-[480px] transition-all duration-500 hover:shadow-2xl hover:border-slate-300 dark:hover:border-gray-500 hover:-translate-y-2">
                    
                    <div class="relative w-full h-56 bg-slate-100 dark:bg-gray-800 border-b border-slate-200 dark:border-gray-700 flex items-center justify-center overflow-hidden group">
                        
                        <span id="prev-placeholder" class="text-slate-500 dark:text-gray-500 text-xs font-bold uppercase tracking-widest flex flex-col items-center transition-all duration-300 {{ $livro->capa ? 'hidden' : '' }}">
                            <svg class="w-8 h-8 mb-2 opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                            Sem Capa
                        </span>
                        
                        <img id="prev-img" 
                             src="{{ $livro->capa ? asset('storage/' . $livro->capa) : '' }}" 
                             alt="Capa" 
                             class="absolute inset-0 w-full h-full object-cover transition-all duration-500 hover:scale-105 {{ $livro->capa ? '' : 'hidden' }}" />
                        
                        <div id="prev-badge" class="absolute top-0 right-0 bg-[#F59E0B] text-white text-[10px] font-black px-3 py-1 uppercase tracking-wider {{ old('e_bestseller', $livro->e_bestseller) ? 'animate-fade-in' : 'hidden' }} shadow-md rounded-bl-md">
                            Bestseller
                        </div>
                    </div>

                    <div class="p-5 flex-1 flex flex-col">
                        <div class="flex justify-between items-start mb-2">
                            <span id="prev-cat" class="text-[10px] font-bold text-[#F59E0B] uppercase tracking-widest border border-[#F59E0B]/50 bg-[#F59E0B]/10 px-2 py-0.5 rounded-md transition-all">{{ old('categoria', $livro->categoria) ?: 'Categoria' }}</span>
                            <span id="prev-ano" class="text-[10px] font-bold text-gray-500">{{ old('data_publicacao', $livro->data_publicacao) ? date('Y', strtotime(is_string(old('data_publicacao', $livro->data_publicacao)) ? old('data_publicacao', $livro->data_publicacao) : $livro->data_publicacao->format('Y-m-d'))) : '----' }}</span>
                        </div>
                        
                        <h3 id="prev-title" class="text-xl font-black text-white leading-tight mb-1 transition-all" style="font-family: 'Merriweather', serif;">{{ old('titulo', $livro->titulo) ?: 'Título do Livro' }}</h3>
                        <p id="prev-author" class="text-sm font-semibold text-gray-400 mb-4 transition-all">{{ old('autor', $livro->autor) ?: 'Autor da Obra' }}</p>
                        
                        <p id="prev-synopsis" class="text-xs text-gray-500 line-clamp-4 mb-4 flex-1 transition-all">
                            {{ old('sinopse', $livro->sinopse) ?: 'Digite a sinopse ao lado para visualizar como o texto ficará ajustado no formato final do card do livro.' }}
                        </p>
                        
                        <div class="mt-auto pt-4 border-t border-gray-800 flex justify-between items-center">
                            <div class="text-[10px] text-gray-500 font-mono">ISBN: <span id="prev-isbn" class="text-gray-400">{{ old('isbn', $livro->isbn) ?: '000-00-000-0000-0' }}</span></div>
                            <div class="text-[10px] font-bold text-gray-400 uppercase bg-gray-800 px-2 py-1 rounded-md">Est: <span id="prev-stock" class="text-white">{{ old('quantidade', $livro->quantidade) ?: '0' }}</span></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>

    <script src="https://unpkg.com/imask"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const isbnInput = document.getElementById('isbn');
            if (isbnInput) IMask(isbnInput, { mask: '000-00-000-0000-0' });

            const syncText = (inputId, prevId, fallback) => {
                const input = document.getElementById(inputId);
                const prev = document.getElementById(prevId);
                if(input && prev) {
                    input.addEventListener('input', e => prev.textContent = e.target.value.trim() || fallback);
                }
            };

            syncText('titulo', 'prev-title', 'Título do Livro');
            syncText('autor', 'prev-author', 'Autor da Obra');
            syncText('categoria', 'prev-cat', 'Categoria');
            syncText('sinopse', 'prev-synopsis', 'Digite a sinopse ao lado para visualizar como o texto ficará ajustado...');
            syncText('isbn', 'prev-isbn', '000-00-000-0000-0');
            syncText('quantidade', 'prev-stock', '0');

            const dateInput = document.getElementById('data_publicacao');
            if(dateInput) {
                dateInput.addEventListener('input', e => {
                    document.getElementById('prev-ano').textContent = e.target.value ? new Date(e.target.value).getFullYear() : '----';
                });
            }

            const bestsellerInput = document.getElementById('e_bestseller');
            if(bestsellerInput) {
                bestsellerInput.addEventListener('change', e => {
                    const badge = document.getElementById('prev-badge');
                    if (e.target.checked) {
                        badge.classList.remove('hidden');
                        badge.classList.add('animate-fade-in');
                    } else {
                        badge.classList.add('hidden');
                        badge.classList.remove('animate-fade-in');
                    }
                });
            }

            const capaInput = document.getElementById('capa');
            if(capaInput) {
                capaInput.addEventListener('change', e => {
                    const file = e.target.files[0];
                    const img = document.getElementById('prev-img');
                    const placeholder = document.getElementById('prev-placeholder');
                    
                    if (file) {
                        const reader = new FileReader();
                        reader.onload = e => {
                            img.src = e.target.result;
                            img.classList.remove('hidden');
                            placeholder.classList.add('hidden');
                        }
                        reader.readAsDataURL(file);
                    } else {
                        // Se o usuário cancelar a seleção do arquivo novo
                        // A lógica volta para mostrar o placeholder. Se você quiser que
                        // ele retorne para a imagem antiga que estava no BD, você pode 
                        // ajustar aqui, mas por padrão deixamos como "Sem Capa".
                        img.src = '';
                        img.classList.add('hidden');
                        placeholder.classList.remove('hidden');
                    }
                });
            }
        });
    </script>
            </div>
        </div>
    </div>

</x-app-layout>

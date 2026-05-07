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
                    <h1 class="text-lg font-black text-slate-900 dark:text-white">Editar Autor</h1>
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
                    <pattern id="bg-dots-autores-edit" width="28" height="28" patternUnits="userSpaceOnUse">
                        <circle cx="1" cy="1" r="1" fill="#93c5fd" opacity="0.08"/>
                    </pattern>
                </defs>
                <rect width="100%" height="100%" fill="url(#bg-dots-autores-edit)"/>
            </svg>
            <div id="bg-glow-1" class="absolute -top-28 -left-20 w-96 h-96 rounded-full blur-[90px]"></div>
            <div id="bg-glow-2" class="absolute -bottom-20 -right-14 w-72 h-72 rounded-full blur-[80px]"></div>
            <div class="bg-shelf absolute left-0 right-0 h-px top-[22%]"></div>
            <div class="bg-shelf absolute left-0 right-0 h-px top-[58%]"></div>
            <i class="ph ph-pencil bg-icon absolute left-[3%] top-[5%] text-[28px]"></i>
            <i class="ph ph-book bg-icon absolute left-[87%] top-[8%] text-[22px]"></i>
        </div>

        <div class="max-w-6xl mx-auto relative z-10">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">

        <div class="lg:col-span-7 flex flex-col justify-center">

            <div class="mb-8 border-b border-slate-200 dark:border-gray-700 pb-4">
                <h2 class="text-3xl text-slate-900 dark:text-white tracking-tight uppercase font-black" style="font-family: 'Merriweather', serif;">
                    Editar <span class="text-[#F59E0B]">Autor</span>
                </h2>
                <p class="text-slate-600 dark:text-gray-500 text-[10px] font-bold uppercase tracking-[0.2em] mt-1">{{ $autor->nome }}</p>
            </div>

            @if(session('sucesso'))
                <div class="mb-6 text-sm text-green-400 bg-green-900/30 border border-green-500/30 p-4 rounded-md font-semibold">
                    {{ session('sucesso') }}
                </div>
            @endif

            <form method="POST" action="{{ route('autores.update', $autor->id) }}" enctype="multipart/form-data" class="space-y-6">
                @csrf
                @method('PUT')

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="group">
                        <label for="nome" class="block text-xs font-bold text-slate-600 dark:text-gray-400 uppercase tracking-wider mb-2 group-focus-within:text-[#F59E0B] transition-colors">Nome do Autor</label>
                        <input id="nome" type="text" name="nome" value="{{ old('nome', $autor->nome) }}" required autofocus
                            class="block w-full bg-white dark:bg-gray-900 border border-slate-200 dark:border-gray-700 text-slate-900 dark:text-white focus:border-[#F59E0B] focus:ring-1 focus:ring-[#F59E0B] rounded-md shadow-sm transition-all duration-300 hover:border-slate-300 dark:hover:border-gray-500" />
                        <x-input-error :messages="$errors->get('nome')" class="mt-2" />
                    </div>

                    <div class="group">
                        <label for="nacionalidade" class="block text-xs font-bold text-slate-600 dark:text-gray-400 uppercase tracking-wider mb-2 group-focus-within:text-[#F59E0B] transition-colors">Nacionalidade</label>
                        <input id="nacionalidade" type="text" name="nacionalidade" value="{{ old('nacionalidade', $autor->nacionalidade) }}"
                            class="block w-full bg-white dark:bg-gray-900 border border-slate-200 dark:border-gray-700 text-slate-900 dark:text-white focus:border-[#F59E0B] focus:ring-1 focus:ring-[#F59E0B] rounded-md shadow-sm transition-all duration-300 hover:border-slate-300 dark:hover:border-gray-500" />
                    </div>
                </div>

                <div class="group">
                    <label for="data_nascimento" class="block text-xs font-bold text-slate-600 dark:text-gray-400 uppercase tracking-wider mb-2 group-focus-within:text-[#F59E0B] transition-colors">Data de Nascimento</label>
                    <input id="data_nascimento" type="date" name="data_nascimento" value="{{ old('data_nascimento', $autor->data_nascimento ? $autor->data_nascimento->format('Y-m-d') : '') }}"
                        class="block w-full bg-white dark:bg-gray-900 border border-slate-200 dark:border-gray-700 text-slate-900 dark:text-white focus:border-[#F59E0B] focus:ring-1 focus:ring-[#F59E0B] rounded-md shadow-sm transition-all duration-300 hover:border-slate-300 dark:hover:border-gray-500" />
                </div>

                <div class="group">
                    <label for="biografia" class="block text-xs font-bold text-slate-600 dark:text-gray-400 uppercase tracking-wider mb-2 group-focus-within:text-[#F59E0B] transition-colors">Biografia</label>
                    <textarea id="biografia" name="biografia" rows="4"
                        class="block w-full bg-white dark:bg-gray-900 border border-slate-200 dark:border-gray-700 text-slate-900 dark:text-white focus:border-[#F59E0B] focus:ring-1 focus:ring-[#F59E0B] rounded-md shadow-sm transition-all duration-300 hover:border-slate-300 dark:hover:border-gray-500">{{ old('biografia', $autor->biografia) }}</textarea>
                </div>

                <div class="bg-slate-100 dark:bg-gray-900/50 p-4 border border-dashed border-slate-300 dark:border-gray-600 rounded-md hover:border-[#F59E0B] transition-colors duration-300">
                    <label for="foto" class="block text-xs font-bold text-slate-600 dark:text-gray-400 uppercase tracking-wider mb-3">Trocar Foto (Opcional)</label>
                    <input id="foto" type="file" name="foto" accept="image/*"
                    class="block w-full text-sm text-slate-600 dark:text-gray-400 file:mr-4 file:py-2 file:px-4 file:border-0 file:text-xs file:font-bold file:uppercase file:bg-[#1E3A8A] file:text-white hover:file:bg-[#2563EB] cursor-pointer rounded-md transition-colors" />
                </div>

                <div class="flex flex-col sm:flex-row items-center justify-end gap-4 pt-6 border-t border-slate-200 dark:border-gray-700">
                          <a href="{{ route('autores.index') }}"
                              class="w-full sm:w-auto text-center px-6 py-3 bg-slate-200 hover:bg-slate-300 dark:bg-gray-700 dark:hover:bg-gray-600 border border-slate-300 dark:border-gray-600 font-bold text-xs text-slate-900 dark:text-white uppercase tracking-wider shadow-sm rounded-md transition-all duration-300">
                        Cancelar
                    </a>

                    <button type="submit"
                            class="w-full sm:w-auto px-8 py-3 bg-[#1E3A8A] hover:bg-[#2563EB] border border-transparent font-bold text-xs text-white uppercase tracking-wider shadow-md shadow-blue-900/20 rounded-md transition-all duration-300 transform hover:-translate-y-1">
                        Atualizar Autor
                    </button>
                </div>
            </form>
        </div>

        <div class="lg:col-span-5 flex flex-col justify-center">
            <div class="sticky top-6">
                <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-4 border-b border-gray-700 pb-2">Pré-visualização</label>

                <div class="bg-gray-900 border border-gray-700 shadow-xl rounded-md overflow-hidden flex flex-col max-w-sm mx-auto h-[480px] transition-all duration-500 hover:shadow-2xl hover:border-gray-500 hover:-translate-y-2">

                    <div class="p-4 flex-1">
                        <div class="text-center mb-4">
                            @if($autor->foto)
                                <img src="{{ asset('storage/' . $autor->foto) }}" alt="{{ $autor->nome }}" class="w-20 h-20 rounded-full mx-auto mb-2 object-cover">
                            @else
                                <div class="w-20 h-20 bg-gray-700 rounded-full mx-auto mb-2 flex items-center justify-center">
                                    <i class="ph ph-user text-2xl text-gray-400"></i>
                                </div>
                            @endif
                            <h3 class="text-lg font-bold text-white" id="prev-nome">{{ $autor->nome }}</h3>
                            <p class="text-gray-400 text-sm" id="prev-nacionalidade">{{ $autor->nacionalidade ?? 'Nacionalidade' }}</p>
                        </div>

                        <div class="space-y-2 text-sm">
                            <div>
                                <span class="text-gray-500">Data de Nascimento:</span>
                                <span class="text-white" id="prev-data">{{ $autor->data_nascimento ? $autor->data_nascimento->format('d/m/Y') : 'Não informada' }}</span>
                            </div>
                        </div>

                        <div class="mt-4">
                            <p class="text-gray-300 text-sm" id="prev-biografia">{{ $autor->biografia ?: 'Biografia do autor aparecerá aqui...' }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>

    <script>
        function syncText(inputId, previewId, placeholder) {
            const input = document.getElementById(inputId);
            const preview = document.getElementById(previewId);

            input.addEventListener('input', () => {
                preview.textContent = input.value || placeholder;
            });

            // Initial sync
            preview.textContent = input.value || placeholder;
        }

        // Sync fields
        syncText('nome', 'prev-nome', 'Nome do Autor');
        syncText('nacionalidade', 'prev-nacionalidade', 'Nacionalidade');
        syncText('biografia', 'prev-biografia', 'Biografia do autor aparecerá aqui...');

        // Sync date
        document.getElementById('data_nascimento').addEventListener('input', (e) => {
            const date = e.target.value;
            document.getElementById('prev-data').textContent = date ? new Date(date).toLocaleDateString('pt-BR') : 'Não informada';
        });
    </script>
</x-app-layout>
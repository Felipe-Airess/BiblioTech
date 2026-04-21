<x-guest-layout>
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-12">

        <div class="lg:col-span-7 flex flex-col justify-center">

            <div class="mb-8 border-b border-gray-700 pb-4">
                <h2 class="text-3xl text-white tracking-tight uppercase font-black" style="font-family: 'Merriweather', serif;">
                    Editar <span class="text-[#F59E0B]">Autor</span>
                </h2>
                <p class="text-gray-500 text-[10px] font-bold uppercase tracking-[0.2em] mt-1">{{ $autor->nome }}</p>
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
                        <label for="nome" class="block text-xs font-bold text-gray-400 uppercase tracking-wider mb-2 group-focus-within:text-[#F59E0B] transition-colors">Nome do Autor</label>
                        <input id="nome" type="text" name="nome" value="{{ old('nome', $autor->nome) }}" required autofocus
                            class="block w-full bg-gray-900 border border-gray-700 text-white focus:border-[#F59E0B] focus:ring-1 focus:ring-[#F59E0B] rounded-md shadow-sm transition-all duration-300 hover:border-gray-500" />
                        <x-input-error :messages="$errors->get('nome')" class="mt-2" />
                    </div>

                    <div class="group">
                        <label for="nacionalidade" class="block text-xs font-bold text-gray-400 uppercase tracking-wider mb-2 group-focus-within:text-[#F59E0B] transition-colors">Nacionalidade</label>
                        <input id="nacionalidade" type="text" name="nacionalidade" value="{{ old('nacionalidade', $autor->nacionalidade) }}"
                            class="block w-full bg-gray-900 border border-gray-700 text-white focus:border-[#F59E0B] focus:ring-1 focus:ring-[#F59E0B] rounded-md shadow-sm transition-all duration-300 hover:border-gray-500" />
                    </div>
                </div>

                <div class="group">
                    <label for="data_nascimento" class="block text-xs font-bold text-gray-400 uppercase tracking-wider mb-2 group-focus-within:text-[#F59E0B] transition-colors">Data de Nascimento</label>
                    <input id="data_nascimento" type="date" name="data_nascimento" value="{{ old('data_nascimento', $autor->data_nascimento ? $autor->data_nascimento->format('Y-m-d') : '') }}"
                        class="block w-full bg-gray-900 border border-gray-700 text-white focus:border-[#F59E0B] focus:ring-1 focus:ring-[#F59E0B] rounded-md shadow-sm transition-all duration-300 hover:border-gray-500" />
                </div>

                <div class="group">
                    <label for="biografia" class="block text-xs font-bold text-gray-400 uppercase tracking-wider mb-2 group-focus-within:text-[#F59E0B] transition-colors">Biografia</label>
                    <textarea id="biografia" name="biografia" rows="4"
                        class="block w-full bg-gray-900 border border-gray-700 text-white focus:border-[#F59E0B] focus:ring-1 focus:ring-[#F59E0B] rounded-md shadow-sm transition-all duration-300 hover:border-gray-500">{{ old('biografia', $autor->biografia) }}</textarea>
                </div>

                <div class="bg-gray-900/50 p-4 border border-dashed border-gray-600 rounded-md hover:border-[#F59E0B] transition-colors duration-300">
                    <label for="foto" class="block text-xs font-bold text-gray-400 uppercase tracking-wider mb-3">Trocar Foto (Opcional)</label>
                    <input id="foto" type="file" name="foto" accept="image/*"
                        class="block w-full text-sm text-gray-400 file:mr-4 file:py-2 file:px-4 file:border-0 file:text-xs file:font-bold file:uppercase file:bg-[#1E3A8A] file:text-white hover:file:bg-[#2563EB] cursor-pointer rounded-md transition-colors" />
                </div>

                <div class="flex flex-col sm:flex-row items-center justify-end gap-4 pt-6 border-t border-gray-700">
                    <a href="{{ route('autores.index') }}"
                       class="w-full sm:w-auto text-center px-6 py-3 bg-gray-700 hover:bg-gray-600 border border-gray-600 font-bold text-xs text-white uppercase tracking-wider shadow-sm rounded-md transition-all duration-300">
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
</x-guest-layout>
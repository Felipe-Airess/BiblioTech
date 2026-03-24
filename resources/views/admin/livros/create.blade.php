<x-guest-layout>
    <div class="py-6">
        <div class="mb-8 text-center">
            <h2 class="font-merriweather text-3xl text-white tracking-tight uppercase font-black">
                Novo <span class="text-[#F59E0B]">Livro</span>
            </h2>
            <div class="mt-2 h-1 w-20 bg-[#F59E0B] mx-auto rounded-full"></div>
        </div>

        @if(session('sucesso'))
            <div class="mb-4 font-inter text-sm text-green-400 bg-green-900/30 p-3 rounded-lg border border-green-500/20">
                {{ session('sucesso') }}
            </div>
        @endif

        <form method="POST" action="{{ route('livros.store') }}" enctype="multipart/form-data" class="font-inter space-y-6">
            @csrf

            <div>
                <x-input-label for="titulo" value="Título do Livro" class="text-gray-300 font-semibold uppercase text-[10px] tracking-widest" />
                <x-text-input id="titulo" class="block mt-1 w-full bg-gray-900 border-gray-700 text-white focus:border-[#1E3A8A] focus:ring-[#1E3A8A] rounded-xl" type="text" name="titulo" :value="old('titulo')" required autofocus />
                <x-input-error :messages="$errors->get('titulo')" class="mt-2" />
            </div>

            <div>
                <x-input-label for="autor" value="Autor da Obra" class="text-gray-300 font-semibold uppercase text-[10px] tracking-widest" />
                <x-text-input id="autor" class="block mt-1 w-full bg-gray-900 border-gray-700 text-white focus:border-[#1E3A8A] focus:ring-[#1E3A8A] rounded-xl" type="text" name="autor" :value="old('autor')" required />
                <x-input-error :messages="$errors->get('autor')" class="mt-2" />
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
                    <x-input-label for="categoria" value="Categoria" class="text-gray-300 font-semibold uppercase text-[10px] tracking-widest" />
                    <x-text-input id="categoria" class="block mt-1 w-full bg-gray-900 border-gray-700 text-white focus:border-[#1E3A8A] focus:ring-[#1E3A8A] rounded-xl" type="text" name="categoria" :value="old('categoria')" required />
                </div>
                
                <div>
                    <x-input-label for="quantidade" value="Estoque" class="text-gray-300 font-semibold uppercase text-[10px] tracking-widest" />
                    <x-text-input id="quantidade" class="block mt-1 w-full bg-gray-900 border-gray-700 text-white focus:border-[#1E3A8A] focus:ring-[#1E3A8A] rounded-xl" type="number" name="quantidade" min="0" :value="old('quantidade')" required />
                </div>

                <div>
                    <x-input-label for="data_publicacao" value="Lançamento" class="text-gray-300 font-semibold uppercase text-[10px] tracking-widest" />
                    <x-text-input id="data_publicacao" class="block mt-1 w-full bg-gray-900 border-gray-700 text-white focus:border-[#1E3A8A] focus:ring-[#1E3A8A] rounded-xl" type="date" name="data_publicacao" :value="old('data_publicacao')" required />
                </div>
            </div>

            <div class="flex flex-col md:flex-row items-end gap-4">
                <div class="w-full md:w-2/3">
                    <x-input-label for="isbn" value="ISBN (Código Único)" class="text-gray-300 font-semibold uppercase text-[10px] tracking-widest" />
                    <x-text-input id="isbn" class="block mt-1 w-full bg-gray-900 border-gray-700 text-white focus:border-[#1E3A8A] focus:ring-[#1E3A8A] rounded-xl" type="text" name="isbn" :value="old('isbn')" required placeholder="000-00-000-0000-0" />
                </div>

                <div class="w-full md:w-1/3 pb-3 flex items-center bg-gray-900/50 p-3 rounded-xl border border-gray-800">
                    <input id="e_bestseller" type="checkbox" name="e_bestseller" value="1" class="rounded border-gray-700 bg-gray-900 text-[#1E3A8A] shadow-sm focus:ring-[#1E3A8A]">
                    <label for="e_bestseller" class="ms-2 text-[10px] font-black uppercase tracking-widest text-gray-400">É Bestseller?</label>
                </div>
            </div>

            <div>
                <x-input-label for="sinopse" value="Sinopse" class="text-gray-300 font-semibold uppercase text-[10px] tracking-widest" />
                <textarea id="sinopse" name="sinopse" rows="3" class="block mt-1 w-full bg-gray-900 border-gray-700 text-white focus:border-[#1E3A8A] focus:ring-[#1E3A8A] rounded-xl shadow-sm">{{ old('sinopse') }}</textarea>
            </div>

            <div class="bg-gray-900/50 p-4 rounded-xl border border-dashed border-gray-700">
                <x-input-label for="capa" value="Capa do Livro" class="text-gray-300 font-semibold uppercase text-[10px] tracking-widest mb-2" />
                <input id="capa" type="file" name="capa" accept="image/*" class="block w-full text-[10px] text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-[10px] file:font-black file:uppercase file:bg-[#1E3A8A] file:text-white hover:file:bg-[#2563EB] transition cursor-pointer" />
            </div>

            <div class="flex flex-col sm:flex-row items-center justify-end gap-4 pt-4">
                <a href="{{ route('dashboard') }}" 
                   class="w-full sm:w-auto text-center px-6 py-3 bg-gray-700 hover:bg-gray-600 border border-gray-500 rounded-xl font-bold text-xs text-white uppercase tracking-widest transition duration-150 shadow-sm">
                    Voltar
                </a>

                <button type="submit" 
                        class="w-full sm:w-auto px-8 py-3 bg-[#1E3A8A] hover:bg-[#2563EB] border border-transparent rounded-xl font-bold text-xs text-white uppercase tracking-widest transition duration-150 shadow-lg shadow-blue-900/20">
                    Salvar Livro
                </button>
            </div>
        </form>
    </div>

    <script src="https://unpkg.com/imask"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const isbnInput = document.getElementById('isbn');
            if (isbnInput) {
                IMask(isbnInput, { mask: '000-00-000-0000-0' });
            }
        });
    </script>
</x-guest-layout>
<x-guest-layout>
    
    @if(session('sucesso'))
        <div class="mb-4 font-medium text-sm text-green-600 dark:text-green-400">
            {{ session('sucesso') }}
        </div>
    @endif

    <div class="mb-6 text-center">
        <h2 class="text-xl font-bold text-gray-800 dark:text-white">Cadastrar Novo Livro</h2>
    </div>

    <form method="POST" action="{{ route('livros.update', $livro->id) }}" enctype="multipart/form-data" >
        @csrf
        @method('PUT')

        <div>
            <x-input-label for="titulo" value="Título do Livro" />
            <x-text-input id="titulo" class="block mt-1 w-full" type="text" name="titulo" :value="old('titulo', $livro->titulo)" required autofocus />
            <x-input-error :messages="$errors->get('titulo')" class="mt-2" />
        </div>

        <div class="mt-4">
            <x-input-label for="autor" value="Autor" />
            <x-text-input id="autor" class="block mt-1 w-full" type="text" name="autor" :value="old('autor', $livro->autor )" required />
            <x-input-error :messages="$errors->get('autor')" class="mt-2" />
        </div>

        <div class="flex items-center gap-4 mt-4">
            <div class="w-2/3">
                <x-input-label for="isbn" value="ISBN (Código Único)" />
                <x-text-input id="isbn" class="block mt-1 w-full" type="text" name="isbn" :value="old('isbn')" required />
                <x-input-error :messages="$errors->get('isbn')" class="mt-2" />
            </div>

            <div class="w-1/3 mt-6 flex items-center">
                <input id="e_bestseller" type="checkbox" name="e_bestseller" value="1" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500">
                <label for="e_bestseller" class="ms-2 text-sm text-gray-600 dark:text-gray-400">É Bestseller?</label>
            </div>
        </div>

        <div class="mt-4">
            <x-input-label for="capa" value="Capa do Livro (Opcional)" />
            <input id="capa" type="file" name="capa" accept="image/*" class="block mt-1 w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100" />
            <x-input-error :messages="$errors->get('capa')" class="mt-2" />
        </div>

        <div class="flex items-center justify-end mt-6">
            <x-primary-button class="ms-4">
                Salvar Livro
            </x-primary-button>
            <a href="{{ route('dashboard') }}" class="ms-4 text-sm text-gray-600 dark:text-gray-400 hover:underline">
                Voltar ao Dashboard
            </a>
        </div>
    </form>
</x-guest-layout>
    
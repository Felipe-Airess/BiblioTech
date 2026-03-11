<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            Meu Acervo de Livros
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6">

                <h3 class="text-lg font-bold mb-6 text-gray-900 dark:text-white">Últimos Cadastros</h3>

                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
                    
                    @foreach($livros as $livro)
                        <div class="border rounded-lg p-4 shadow-sm bg-gray-50 dark:bg-gray-700 dark:border-gray-600 flex flex-col">
                            
                            @if($livro->capa)
                                <img src="{{ asset('storage/' . $livro->capa) }}" alt="Capa" class="w-full h-48 object-cover mb-4 rounded shadow">
                            @else
                                <div class="w-full h-48 bg-gray-200 dark:bg-gray-600 flex items-center justify-center mb-4 rounded shadow">
                                    <span class="text-gray-500 dark:text-gray-400 text-sm">Sem capa</span>
                                </div>
                            @endif

                            <h4 class="font-bold text-md text-gray-900 dark:text-white">{{ $livro->titulo }}</h4>
                            <p class="text-sm text-gray-600 dark:text-gray-300">{{ $livro->autor }}</p>
                            <p class="text-xs text-gray-500 mt-2 dark:text-gray-400">ISBN: {{ $livro->isbn }}</p>

                            @if($livro->e_bestseller)
                                <div class="mt-auto pt-3">
                                    <span class="inline-block bg-yellow-400 text-yellow-900 text-xs font-bold px-2 py-1 rounded">
                                        ⭐ Bestseller
                                    </span>
                                </div>
                            @endif
                        </div>
                    @endforeach

                </div>

                @if($livros->isEmpty())
                    <p class="text-gray-500 dark:text-gray-400 text-center py-4">Nenhum livro no acervo ainda.</p>
                @endif

            </div>
        </div>
    </div>
</x-app-layout>
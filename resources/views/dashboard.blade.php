<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between w-full">
            <h2 class="font-bold text-xl text-blue-900 dark:text-blue-400 leading-tight">
                Meu Acervo de Livros
            </h2>
            
            <div class="flex gap-4">
                @if(auth()->user()->tipo_usuario === 'gerente')
                    <a href="{{ route('bibliotecarios.create') }}" class="px-4 py-2 bg-blue-900 dark:bg-blue-700 text-white rounded hover:bg-blue-800 dark:hover:bg-blue-600 text-sm font-bold transition shadow-sm">Cadastrar Bibliotecário</a>
                @endif
                
                @if(in_array(auth()->user()->tipo_usuario, ['gerente', 'bibliotecario']))
                    <a href="{{ route('livros.create') }}" class="px-4 py-2 bg-blue-900 dark:bg-blue-700 text-white rounded hover:bg-blue-800 dark:hover:bg-blue-600 text-sm font-bold transition shadow-sm">Cadastrar Novo Livro</a>
                @endif
            </div>
        </div>
    </x-slot>

    <div class="py-12 bg-gray-100 dark:bg-gray-900 min-h-screen transition-colors duration-200">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6 border border-transparent dark:border-gray-700 transition-colors duration-200">

                <h3 class="text-lg font-bold mb-6 text-gray-800 dark:text-gray-200">Últimos Cadastros</h3>

                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
                    
                    @foreach($livros as $livro)
                        <div class="border border-gray-200 dark:border-gray-700 rounded-lg p-4 shadow-sm bg-white dark:bg-gray-800 flex flex-col transition-colors duration-200">
                            
                            @if($livro->capa)
                                <img src="{{ asset('storage/' . $livro->capa) }}" alt="Capa" class="w-full h-48 object-cover mb-4 rounded shadow">
                            @else
                                <div class="w-full h-48 bg-gray-100 dark:bg-gray-700 flex items-center justify-center mb-4 rounded shadow">
                                    <span class="text-gray-400 dark:text-gray-500 text-sm font-medium">Sem capa</span>
                                </div>
                            @endif

                            <h4 class="font-bold text-md text-gray-800 dark:text-gray-200">{{ $livro->titulo }}</h4>
                            <p class="text-sm text-gray-600 dark:text-gray-400">{{ $livro->autor }}</p>
                            <p class="text-xs text-gray-400 dark:text-gray-500 mt-2">ISBN: {{ $livro->isbn }}</p>

                            @if($livro->e_bestseller)
                                <div class="mt-2 pt-1">
                                    <span class="inline-block bg-amber-500 dark:bg-amber-600 text-white text-xs font-bold px-2 py-1 rounded">
                                        ⭐ Bestseller
                                    </span>
                                </div>
                            @endif

                            @if(in_array(auth()->user()->tipo_usuario, ['gerente', 'bibliotecario']))
                                <div class="mt-auto pt-4 border-t border-gray-100 dark:border-gray-700 flex justify-between items-center">
                                    
                                    <a href="{{ route('livros.edit', $livro->id) }}" class="text-amber-500 dark:text-amber-400 hover:text-amber-600 dark:hover:text-amber-300 text-sm font-bold transition">Editar</a>
                                    
                                    <form action="{{ route('livros.destroy', $livro->id) }}" method="POST" onsubmit="confirmarExclusao(event, this)">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-500 dark:text-red-400 hover:text-red-700 dark:hover:text-red-300 text-sm font-bold transition">
                                            Excluir
                                        </button>
                                    </form>
                                </div>
                            @endif

                        </div>
                    @endforeach

                </div>

                @if($livros->isEmpty())
                    <p class="text-gray-500 dark:text-gray-400 text-center py-8">Nenhum livro no acervo ainda.</p>
                @endif

            </div>
        </div>
    </div>
</x-app-layout>
<x-guest-layout>
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-12">

        <div class="lg:col-span-7 flex flex-col justify-center">

            <div class="mb-8 border-b border-gray-700 pb-4">
                <h2 class="text-3xl text-white tracking-tight uppercase font-black"
                    style="font-family: 'Merriweather', serif;">
                    Gerenciar <span class="text-[#F59E0B]">Autores</span>
                </h2>
            </div>

            @if(session('sucesso'))
                <div
                    class="mb-6 text-sm text-green-400 bg-green-900/30 border border-green-500/30 p-4 rounded-md font-semibold">
                    {{ session('sucesso') }}
                </div>
            @endif

            <div class="space-y-4">
                @forelse($autores as $autor)
                    <div
                        class="bg-gray-900 border border-gray-700 rounded-md p-4 hover:border-[#F59E0B] transition-colors duration-300">
                        <div class="flex justify-between items-center">
                            <div>
                                <h3 class="text-lg font-bold text-white">{{ $autor->nome }}</h3>
                                <p class="text-gray-400 text-sm">
                                    {{ $autor->nacionalidade ?? 'Nacionalidade não informada' }}</p>
                            </div>
                            <div class="flex gap-2">
                                <a href="{{ route('autores.show', $autor->id) }}"
                                    class="px-3 py-1 bg-blue-600 hover:bg-blue-700 text-white text-xs font-bold uppercase rounded-md transition-colors">Ver</a>
                                <a href="{{ route('autores.edit', $autor->id) }}"
                                    class="px-3 py-1 bg-yellow-600 hover:bg-yellow-700 text-white text-xs font-bold uppercase rounded-md transition-colors">Editar</a>
                                <form method="POST" action="{{ route('autores.destroy', $autor->id) }}" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                        class="px-3 py-1 bg-red-600 hover:bg-red-700 text-white text-xs font-bold uppercase rounded-md transition-colors"
                                        onclick="return confirm('Tem certeza que deseja excluir este autor?')">Excluir</button>
                                </form>
                            </div>
                        </div>
                    </div>
                @empty
                    <p class="text-gray-400">Nenhum autor cadastrado.</p>
                @endforelse
            </div>
            <div class="flex-row items-center justify-end gap-4 pt-6 border-t border-gray-700">
                @if(auth()->check() && in_array(auth()->user()->tipo_usuario, ['gerente', 'bibliotecario']))
                <div class="mt-8">
                    <a href="{{ route('autores.create') }}"
                        class="px-6 py-3 bg-[#1E3A8A] hover:bg-[#2563EB] border border-transparent font-bold text-xs text-white uppercase tracking-wider shadow-md shadow-blue-900/20 rounded-md transition-all duration-300 transform hover:-translate-y-1">
                        Novo Autor
                    </a>
                </div>
            @endif
            <div class="mt-8">
                <a href="{{ route('dashboard') }}"
                    class="px-6 py-3 bg-[#1E3A8A] hover:bg-[#2563EB] border border-transparent font-bold text-xs text-white uppercase tracking-wider shadow-md shadow-blue-900/20 rounded-md transition-all duration-300 transform hover:-translate-y-1">
                    Voltar ao Dashboard
                </a>
                </a>
            </div>
        </div>
            </div>
            

        <div class="lg:col-span-5 flex flex-col justify-center">
            <div class="sticky top-6">
                <label
                    class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-4 border-b border-gray-700 pb-2">Informações</label>
                <div class="bg-gray-900 border border-gray-700 shadow-xl rounded-md overflow-hidden p-4">
                    <p class="text-gray-300 text-sm">
                        Aqui você pode gerenciar os autores dos livros. Cada autor pode ter múltiplos livros associados.
                    </p>
                </div>
            </div>
        </div>

    </div>
</x-guest-layout>
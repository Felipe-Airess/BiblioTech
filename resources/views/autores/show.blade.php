<x-guest-layout>
    <div class="max-w-4xl mx-auto py-12 px-4">

        <div class="bg-gray-900 border border-gray-700 rounded-md p-8 mb-8">
            <div class="text-center mb-6">
                @if($autor->foto)
                    <img src="{{ asset('storage/' . $autor->foto) }}" alt="{{ $autor->nome }}" class="w-32 h-32 rounded-full mx-auto mb-4 object-cover border-4 border-[#F59E0B]">
                @else
                    <div class="w-32 h-32 bg-gray-700 rounded-full mx-auto mb-4 flex items-center justify-center border-4 border-[#F59E0B]">
                        <i class="ph ph-user text-4xl text-gray-400"></i>
                    </div>
                @endif
                <h1 class="text-3xl font-bold text-white mb-2">{{ $autor->nome }}</h1>
                @if($autor->nacionalidade)
                    <p class="text-gray-400 text-lg">{{ $autor->nacionalidade }}</p>
                @endif
            </div>

            @if($autor->data_nascimento)
                <div class="text-center mb-4">
                    <span class="text-gray-500">Nascido em:</span>
                    <span class="text-white font-semibold">{{ $autor->data_nascimento->format('d/m/Y') }}</span>
                </div>
            @endif

            @if($autor->biografia)
                <div class="prose prose-invert max-w-none">
                    <h3 class="text-xl font-bold text-[#F59E0B] mb-4">Biografia</h3>
                    <p class="text-gray-300 leading-relaxed">{{ $autor->biografia }}</p>
                </div>
            @endif
        </div>

        <div class="bg-gray-900 border border-gray-700 rounded-md p-8">
            <h2 class="text-2xl font-bold text-white mb-6">Livros do Autor</h2>

            @if($autor->livros->count() > 0)
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach($autor->livros as $livro)
                        <div class="bg-gray-800 border border-gray-600 rounded-md p-4 hover:border-[#F59E0B] transition-colors duration-300">
                            @if($livro->capa)
                                <img src="{{ asset('storage/' . $livro->capa) }}" alt="{{ $livro->titulo }}" class="w-full h-48 object-cover rounded-md mb-4">
                            @else
                                <div class="w-full h-48 bg-gray-700 rounded-md mb-4 flex items-center justify-center">
                                    <i class="ph ph-book text-4xl text-gray-400"></i>
                                </div>
                            @endif
                            <h3 class="text-lg font-bold text-white mb-2">{{ $livro->titulo }}</h3>
                            <p class="text-gray-400 text-sm mb-2">{{ $livro->categoria }}</p>
                            @if($livro->sinopse)
                                <p class="text-gray-300 text-sm line-clamp-3">{{ Str::limit($livro->sinopse, 100) }}</p>
                            @endif
                            <div class="mt-4">
                                <a href="{{ route('livros.show', $livro->id) }}" class="inline-block px-4 py-2 bg-[#1E3A8A] hover:bg-[#2563EB] text-white text-sm font-bold uppercase rounded-md transition-colors">
                                    Ver Detalhes
                                </a>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <p class="text-gray-400">Este autor ainda não tem livros cadastrados.</p>
            @endif
        </div>

        <div class="text-center mt-8">
            <a href="{{ route('dashboard') }}" class="px-6 py-3 bg-gray-700 hover:bg-gray-600 border border-gray-600 font-bold text-xs text-white uppercase tracking-wider shadow-sm rounded-md transition-all duration-300">
                Voltar ao Dashboard
            </a>
        </div>

    </div>
</x-guest-layout>
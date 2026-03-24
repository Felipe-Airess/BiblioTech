<x-app-layout>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap');
        @import url('https://cdn.jsdelivr.net/npm/keen-slider@6.8.6/keen-slider.min.css');

        body { font-family: 'Inter', sans-serif !important; }

        .book-cover-container {
            width: 100%;
            height: 280px;
            position: relative;
            overflow: hidden;
            border-radius: 1.25rem 1.25rem 0 0;
            background: #0f172a;
        }
        .book-cover-img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.6s cubic-bezier(0.4, 0, 0.2, 1);
        }
        [x-cloak] { display: none !important; }
        
        /* Garantindo que o slider ocupe o espaço correto */
        .keen-slider { 
            padding-bottom: 20px; 
            overflow: visible !important; /* Permite que o shadow dos cards apareça */
        }
    </style>

    <x-slot name="header">
        <div class="flex flex-col md:flex-row items-start md:items-center justify-between w-full gap-4">
            <h2 class="font-black text-2xl text-[#1E3A8A] dark:text-blue-400 tracking-tighter uppercase">
                Acervo de Livros
            </h2>
            <div class="flex flex-wrap gap-2 w-full md:w-auto">
                @if(auth()->check() && auth()->user()->tipo_usuario === 'gerente')
                    <a href="{{ route('bibliotecarios.create') }}" class="flex-1 md:flex-none text-center px-4 py-2 bg-[#F59E0B] text-white rounded-lg text-[10px] font-black uppercase tracking-widest shadow-md hover:bg-[#d98a08] transition">
                        + Bibliotecário
                    </a>
                @endif
                @if(auth()->check() && in_array(auth()->user()->tipo_usuario, ['gerente', 'bibliotecario']))
                    <a href="{{ route('livros.create') }}" class="flex-1 md:flex-none text-center px-4 py-2 bg-[#1E3A8A] text-white rounded-lg text-[10px] font-black uppercase tracking-widest shadow-md hover:bg-[#162a63] transition">
                        + Novo Livro
                    </a>
                @endif
            </div>
        </div>
    </x-slot>

    <div class="py-6 md:py-12 bg-[#0f172a] min-h-screen">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-[#111827] overflow-hidden shadow-2xl rounded-3xl p-6 md:p-10 border border-gray-800">
                
                <div class="flex items-center justify-between mb-10">
                    <div>
                        <h3 class="text-[10px] font-black uppercase tracking-[0.3em] text-[#F59E0B] mb-2">Biblioteca Digital</h3>
                        <h2 class="text-3xl md:text-4xl font-black text-white tracking-tighter">Últimos Lançamentos</h2>
                        <div class="mt-3 h-1.5 w-20 bg-[#1E3A8A] rounded-full"></div>
                    </div>
                    <div class="flex gap-2 sm:gap-3">
                        <button id="prevBtn" class="p-3 sm:p-4 rounded-2xl bg-gray-800 text-gray-400 hover:bg-[#1E3A8A] hover:text-white transition border border-gray-700 shadow-xl">
                            <svg class="w-5 h-5 sm:w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-width="3" d="M15 19l-7-7 7-7" /></svg>
                        </button>
                        <button id="nextBtn" class="p-3 sm:p-4 rounded-2xl bg-gray-800 text-gray-400 hover:bg-[#1E3A8A] hover:text-white transition border border-gray-700 shadow-xl">
                            <svg class="w-5 h-5 sm:w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-width="3" d="M9 5l7 7-7 7" /></svg>
                        </button>
                    </div>
                </div>

                <div id="my-keen-slider" class="keen-slider">
                    @foreach($livros as $livro)
                        <div class="keen-slider__slide" x-data="{ open: false }">
                            <div class="group bg-[#1c2536] rounded-[2rem] overflow-hidden border border-gray-800 hover:border-[#1E3A8A] transition-all duration-300 flex flex-col h-full mx-1">
                                
                                <div class="book-cover-container">
                                    @if($livro->capa)
                                        <img src="{{ asset('storage/' . $livro->capa) }}" class="book-cover-img group-hover:scale-110">
                                    @else
                                        <div class="w-full h-full bg-gray-800 flex items-center justify-center font-black text-gray-600 text-[10px] uppercase tracking-widest">Sem Imagem</div>
                                    @endif

                                    <div class="absolute inset-0 bg-[#1E3A8A]/80 opacity-0 group-hover:opacity-100 transition-all duration-400 flex flex-col items-center justify-center gap-3">
                                        <button @click="open = true" class="p-4 bg-white text-[#1E3A8A] rounded-full hover:bg-[#F59E0B] hover:text-white transition transform hover:scale-110 shadow-2xl">
                                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-width="3" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0zM10 7v3m0 0v3m0-3h3m-3 0H7" />
                                            </svg>
                                        </button>
                                        <span class="text-white text-[10px] font-black uppercase tracking-[0.2em]">Detalhes</span>
                                    </div>
                                </div>

                                <div class="p-6 flex-grow flex flex-col">
                                    <div class="flex items-center gap-2 mb-3">
                                        <span class="text-[9px] font-black uppercase px-2 py-1 bg-blue-900/30 text-blue-400 rounded-md border border-blue-800/50">
                                            {{ $livro->categoria ?? 'Geral' }}
                                        </span>
                                        <span class="text-[9px] font-bold text-gray-500 uppercase">
                                            {{ \Carbon\Carbon::parse($livro->data_publicacao)->format('Y') }}
                                        </span>
                                    </div>

                                    <h4 class="text-white font-black text-base truncate tracking-tight mb-1">{{ $livro->titulo }}</h4>
                                    <p class="text-gray-500 text-xs font-bold truncate mb-4">{{ $livro->autor }}</p>
                                    
                                    @if(auth()->check() && in_array(auth()->user()->tipo_usuario, ['gerente', 'bibliotecario']))
                                        <div class="mt-auto pt-4 border-t border-gray-800 flex items-center justify-between">
                                            <a href="{{ route('livros.edit', $livro->id) }}" class="text-[10px] font-black text-[#F59E0B] uppercase tracking-widest hover:underline">Editar</a>
                                            <form action="{{ route('livros.destroy', $livro->id) }}" method="POST" onsubmit="return confirm('Excluir livro?')">
                                                @csrf @method('DELETE')
                                                <button type="submit" class="text-[10px] font-black text-red-500 uppercase tracking-widest hover:text-red-400">Excluir</button>
                                            </form>
                                        </div>
                                    @endif
                                </div>
                            </div>
                            
                            <template x-teleport="body">
                                <div x-show="open" x-cloak class="fixed inset-0 z-[100] flex items-center justify-center p-2 md:p-4 bg-black/95 backdrop-blur-md">
                                    <div @click.away="open = false" class="bg-[#111827] w-full max-w-4xl rounded-[2rem] md:rounded-[3rem] border border-gray-800 overflow-hidden shadow-2xl flex flex-col md:flex-row max-h-[95vh]">
                                        <div class="h-52 md:h-auto md:w-2/5 shrink-0 bg-gray-900">
                                            <img src="{{ asset('storage/' . $livro->capa) }}" class="w-full h-full object-cover">
                                        </div>
                                        <div class="p-6 md:p-12 md:w-3/5 overflow-y-auto relative flex flex-col">
                                            <button @click="open = false" class="absolute top-6 right-6 text-gray-500 hover:text-white transition-colors">
                                                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-width="3" d="M6 18L18 6M6 6l12 12"/></svg>
                                            </button>
                                            <span class="text-[#F59E0B] font-black uppercase text-[10px] tracking-[0.4em] mb-3">{{ $livro->categoria }}</span>
                                            <h2 class="text-3xl md:text-5xl font-black text-white mb-2 tracking-tighter leading-none">{{ $livro->titulo }}</h2>
                                            <p class="text-blue-400 font-bold text-sm md:text-lg mb-6">{{ $livro->autor }}</p>
                                            <div class="bg-[#1c2536] p-6 md:p-8 rounded-[2rem] border border-gray-800 mb-8">
                                                <h5 class="text-white font-black text-[10px] uppercase tracking-widest mb-4 flex items-center gap-2">
                                                    <span class="w-1.5 h-4 bg-[#F59E0B] rounded-full"></span>
                                                    Sinopse da Obra
                                                </h5>
                                                <p class="text-gray-400 leading-relaxed text-sm font-medium">{{ $livro->sinopse ?? 'A sinopse deste livro ainda não foi cadastrada.' }}</p>
                                            </div>
                                            <div class="mt-auto">
                                                @if(auth()->guard('membro')->check())
                                                    <form action="{{ route('livros.alugar', $livro->id) }}" method="POST">
                                                        @csrf
                                                        <button type="submit" class="w-full bg-[#1E3A8A] hover:bg-[#162a63] text-white py-5 rounded-2xl font-black transition uppercase tracking-[0.2em] text-xs shadow-2xl">
                                                            Confirmar Empréstimo
                                                        </button>
                                                    </form>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </template>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/keen-slider@6.8.6/keen-slider.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const slider = new KeenSlider("#my-keen-slider", {
                loop: true,
                slides: { perView: 1.2, spacing: 15 },
                breakpoints: {
                    "(min-width: 640px)": { slides: { perView: 2.2, spacing: 20 } },
                    "(min-width: 1024px)": { slides: { perView: 3, spacing: 24 } },
                    "(min-width: 1280px)": { slides: { perView: 4, spacing: 30 } },
                },
            });

            document.getElementById("prevBtn").onclick = () => slider.prev();
            document.getElementById("nextBtn").onclick = () => slider.next();
        });
    </script>
</x-app-layout>
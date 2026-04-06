<x-app-layout>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap');
        @import url('https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css');

        body { font-family: 'Inter', sans-serif !important; }

        /* Animação Dinâmica de Entrada */
        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .animate-fade-in-up {
            animation: fadeInUp 0.6s ease-out forwards;
            opacity: 0;
        }

        /* Efeito hover dinâmico no card */
        .card-hover-effect {
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }
        .card-hover-effect:hover {
            transform: translateY(-4px);
            box-shadow: 0 10px 25px -5px rgba(30, 58, 138, 0.15);
        }

        .book-cover-container {
            width: 100%;
            height: 280px;
            position: relative;
            overflow: hidden;
            border-radius: 0.125rem 0.125rem 0 0;
            background: #0f172a;
            border-bottom: 1px solid #1f2937;
        }
        
        .book-cover-img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.6s cubic-bezier(0.4, 0, 0.2, 1);
        }
        
        [x-cloak] { display: none !important; }
        
        /* Ajuste pro Swiper não cortar as sombras do hover */
        .swiper { 
            padding-bottom: 20px !important;
            padding-top: 10px !important;
            margin-top: -10px !important;
        }
        .swiper-slide {
            height: auto; 
        }
    </style>

    <script src="https://unpkg.com/@phosphor-icons/web"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <x-slot name="header">
        <div class="flex flex-col md:flex-row items-start md:items-center justify-between w-full gap-4 animate-fade-in-up" style="animation-delay: 0.1s;">
            <h2 class="font-bold text-xl md:text-2xl text-[#1E3A8A] dark:text-blue-400 tracking-tight uppercase flex items-center gap-2">
                <i class="ph ph-books text-2xl"></i>
                Acervo de Livros
            </h2>
            <div class="flex flex-wrap gap-2 w-full md:w-auto">
                @if(auth()->check() && auth()->user()->tipo_usuario === 'gerente')
                    <a href="{{ route('bibliotecarios.create') }}" class="flex-1 md:flex-none flex items-center justify-center gap-2 px-5 py-2.5 bg-[#F59E0B] text-white rounded-sm text-xs font-semibold tracking-wide hover:bg-[#d98a08] transition-all hover:scale-105 border border-[#F59E0B]">
                        <i class="ph ph-user-plus text-sm"></i> Bibliotecário
                    </a>
                @endif
                @if(auth()->check() && in_array(auth()->user()->tipo_usuario, ['gerente', 'bibliotecario']))
                    <a href="{{ route('livros.create') }}" class="flex-1 md:flex-none flex items-center justify-center gap-2 px-5 py-2.5 bg-[#1E3A8A] text-white rounded-sm text-xs font-semibold tracking-wide hover:bg-[#162a63] transition-all hover:scale-105 border border-[#1E3A8A]">
                        <i class="ph ph-book-bookmark text-sm"></i> Novo Livro
                    </a>
                @endif
            </div>
        </div>
    </x-slot>

    <div class="py-6 md:py-12 bg-[#0f172a] min-h-screen">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-[#111827] overflow-hidden rounded-sm p-6 md:p-10 border border-gray-800 animate-fade-in-up" style="animation-delay: 0.2s;">
                
                <div class="flex items-center justify-between mb-10 border-b border-gray-800 pb-6">
                    <div>
                        <h3 class="text-[11px] font-semibold uppercase tracking-widest text-[#F59E0B] mb-1 flex items-center gap-1">
                            <i class="ph-fill ph-star"></i> Biblioteca Digital
                        </h3>
                        <h2 class="text-2xl md:text-3xl font-bold text-white tracking-tight">Últimos Lançamentos</h2>
                        <div class="mt-4 h-1 w-16 bg-[#1E3A8A] transition-all duration-300 hover:w-24"></div>
                    </div>
                    <div class="flex gap-2">
                        <button id="swiper-prev" class="p-2 sm:p-3 rounded-sm bg-[#1c2536] text-gray-400 hover:bg-[#1E3A8A] hover:text-white transition-all hover:-translate-x-1 border border-gray-700 hover:border-[#1E3A8A]">
                            <i class="ph ph-caret-left text-lg"></i>
                        </button>
                        <button id="swiper-next" class="p-2 sm:p-3 rounded-sm bg-[#1c2536] text-gray-400 hover:bg-[#1E3A8A] hover:text-white transition-all hover:translate-x-1 border border-gray-700 hover:border-[#1E3A8A]">
                            <i class="ph ph-caret-right text-lg"></i>
                        </button>
                    </div>
                </div>

                <div class="swiper mySwiper">
                    <div class="swiper-wrapper">
                        @foreach($livros as $index => $livro)
                            <div class="swiper-slide flex flex-col h-full animate-fade-in-up" style="animation-delay: {{ 0.3 + ($index * 0.1) }}s;">
                                <div class="group card-hover-effect bg-[#1c2536] rounded-sm overflow-hidden border border-gray-800 hover:border-[#1E3A8A] transition-colors duration-300 flex flex-col h-full relative">
                                    
                                    <a href="{{ route('livros.show', $livro->id) }}" class="flex-grow flex flex-col cursor-pointer">
                                        <div class="book-cover-container">
                                            @if($livro->capa)
                                                <img src="{{ asset('storage/' . $livro->capa) }}" class="book-cover-img group-hover:scale-110 group-hover:rotate-1">
                                            @else
                                                <div class="w-full h-full bg-[#111827] flex items-center justify-center font-medium text-gray-600 text-[11px] uppercase tracking-widest gap-2">
                                                    <i class="ph ph-image-broken text-xl"></i> Sem Imagem
                                                </div>
                                            @endif

                                            <div class="absolute inset-0 bg-[#0f172a]/95 opacity-0 group-hover:opacity-100 transition-all duration-300 p-6 flex flex-col items-center justify-center text-center border-b border-[#1E3A8A]/50 translate-y-4 group-hover:translate-y-0 z-20">
                                                <span class="text-[#F59E0B] font-semibold uppercase text-[10px] tracking-widest mb-3 border-b border-[#F59E0B]/30 pb-1 flex items-center gap-1">
                                                    <i class="ph ph-text-align-left"></i> Sinopse
                                                </span>
                                                <p class="text-gray-300 text-xs font-normal leading-relaxed line-clamp-6 mb-4">
                                                    {{ $livro->sinopse ?? 'A sinopse deste livro não está disponível no momento.' }}
                                                </p>
                                                <span class="mt-auto px-4 py-2 bg-[#1E3A8A] text-white rounded-sm text-[11px] font-semibold uppercase tracking-wide border border-[#162a63] flex items-center gap-2 hover:bg-blue-800 transition-colors">
                                                    <i class="ph ph-book-open"></i> Acessar Obra
                                                </span>
                                            </div>
                                        </div>

                                        <div class="p-5 flex-grow flex flex-col">
                                            <div class="flex items-center justify-between mb-3">
                                                <span class="text-[10px] font-semibold uppercase px-2 py-1 bg-[#1E3A8A]/20 text-blue-400 rounded-sm border border-[#1E3A8A]/50 flex items-center gap-1">
                                                    <i class="ph ph-tag"></i> {{ $livro->categoria ?? 'Geral' }}
                                                </span>
                                                <span class="text-[10px] font-medium text-gray-500 flex items-center gap-1">
                                                    <i class="ph ph-calendar-blank"></i> {{ \Carbon\Carbon::parse($livro->data_publicacao)->format('Y') }}
                                                </span>
                                            </div>

                                            <h4 class="text-white font-semibold text-sm truncate tracking-tight mb-1 group-hover:text-blue-400 transition-colors">{{ $livro->titulo }}</h4>
                                            <p class="text-gray-400 text-xs truncate flex items-center gap-1">
                                                <i class="ph ph-pen-nib"></i> {{ $livro->autor }}
                                            </p>
                                        </div>
                                    </a>
                                    
                                    @if(auth()->check() && in_array(auth()->user()->tipo_usuario, ['gerente', 'bibliotecario']))
                                        <div class="px-5 pb-4 pt-0 mt-auto border-t border-gray-800/50 flex items-center justify-between z-10 relative bg-[#1c2536]">
                                            <a href="{{ route('livros.edit', $livro->id) }}" class="text-[11px] font-medium text-[#F59E0B] hover:text-[#d98a08] transition-colors mt-3 flex items-center gap-1">
                                                <i class="ph ph-pencil-simple"></i> Editar
                                            </a>
                                            <form action="{{ route('livros.destroy', $livro->id) }}" method="POST" class="form-delete mt-3">
                                                @csrf @method('DELETE')
                                                <button type="button" class="btn-delete text-[11px] font-medium text-red-500/80 hover:text-red-400 transition-colors flex items-center gap-1">
                                                    <i class="ph ph-trash"></i> Excluir
                                                </button>
                                            </form>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            
            // 1. Configuração do Swiper
            const swiper = new Swiper('.mySwiper', {
                loop: true,
                grabCursor: true,
                slidesPerView: 1.2,
                spaceBetween: 16,
                breakpoints: {
                    640: { slidesPerView: 2.2, spaceBetween: 20 },
                    1024: { slidesPerView: 3, spaceBetween: 24 },
                    1280: { slidesPerView: 4, spaceBetween: 24 },
                },
                autoplay: {
                    delay: 3000,
                    disableOnInteraction: false,
                    pauseOnMouseEnter: true,
                },
                navigation: {
                    nextEl: '#swiper-next',
                    prevEl: '#swiper-prev',
                },
            });

            // 2. Alerta de Confirmação de Exclusão (SweetAlert2)
            const deleteButtons = document.querySelectorAll('.btn-delete');
            
            deleteButtons.forEach(button => {
                button.addEventListener('click', function() {
                    const form = this.closest('.form-delete');
                    
                    Swal.fire({
                        title: 'Tem certeza?',
                        text: "Essa ação não poderá ser desfeita!",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#ef4444', // Vermelho para a ação perigosa
                        cancelButtonColor: '#1c2536', // Cor do card
                        confirmButtonText: 'Sim, excluir obra!',
                        cancelButtonText: 'Cancelar',
                        background: '#111827', // Fundo dark do painel
                        color: '#fff', // Texto branco
                        customClass: {
                            popup: 'border border-gray-800 rounded-sm'
                        }
                    }).then((result) => {
                        if (result.isConfirmed) {
                            form.submit(); // Se o usuário confirmar, envia o formulário pro Laravel
                        }
                    });
                });
            });
        });
    </script>

    @if(session('success'))
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                // Cria um alerta tipo "Toast" (flutuante no canto)
                const Toast = Swal.mixin({
                    toast: true,
                    position: 'top-end',
                    showConfirmButton: false,
                    timer: 3000,
                    timerProgressBar: true,
                    background: '#1c2536',
                    color: '#fff',
                    didOpen: (toast) => {
                        toast.onmouseenter = Swal.stopTimer;
                        toast.onmouseleave = Swal.resumeTimer;
                    }
                });

                Toast.fire({
                    icon: 'success',
                    title: "{{ session('success') }}" // A mensagem que você envia do seu Controller
                });
            });
        </script>
    @endif
</x-app-layout>
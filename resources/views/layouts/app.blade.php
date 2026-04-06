<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" x-data="{ sidebarOpen: false }">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'BiblioTech') }}</title>

        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
        
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />

        <script src="https://unpkg.com/@phosphor-icons/web"></script>

        @vite(['resources/css/app.css', 'resources/js/app.js'])

        <style>
            body { font-family: 'Inter', sans-serif !important; }
            [x-cloak] { display: none !important; }
            
            /* Scrollbar Dark */
            ::-webkit-scrollbar { width: 8px; }
            ::-webkit-scrollbar-track { background: #0f172a; }
            ::-webkit-scrollbar-thumb { background: #1e293b; border-radius: 0; }
            ::-webkit-scrollbar-thumb:hover { background: #334155; }
            
            /* Menu Ativo */
            .nav-item-active {
                background: rgba(30, 58, 138, 0.4); /* #1E3A8A com opacidade */
                border-left: 3px solid #F59E0B; /* Borda laranja */
                color: white;
            }
        </style>
    </head>
    <body class="antialiased bg-[#0f172a] text-gray-200 overflow-x-hidden">
        
        <div class="flex min-h-screen relative">
            
            @if(auth()->guard('web')->check() && in_array(auth()->guard('web')->user()->tipo_usuario, ['gerente', 'bibliotecario']))
                <div x-show="sidebarOpen" 
                     x-transition:enter="transition ease-out duration-300"
                     x-transition:enter-start="opacity-0"
                     x-transition:enter-end="opacity-100"
                     x-transition:leave="transition ease-in duration-200"
                     @click="sidebarOpen = false" 
                     class="fixed inset-0 bg-black/60 backdrop-blur-sm z-40 lg:hidden" x-cloak>
                </div>

                <aside 
                    :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full lg:translate-x-0'"
                    class="fixed inset-y-0 left-0 w-64 bg-[#111827] border-r border-gray-800 z-50 transform transition-transform duration-300 ease-in-out lg:static lg:block shadow-sm">
                    
                    <div class="h-full flex flex-col">
                        <div class="p-6 flex items-center justify-between border-b border-gray-800/50">
                            <h2 class="font-bold text-lg tracking-tight text-white uppercase">
                                PAINEL <span class="text-[#F59E0B]">GESTÃO</span>
                            </h2>
                            <button @click="sidebarOpen = false" class="lg:hidden text-gray-400 hover:text-[#F59E0B] transition-colors">
                                <i class="ph ph-x text-2xl"></i>
                            </button>
                        </div>
                        
                        <nav class="flex-1 py-6 space-y-1 overflow-y-auto">
                            <div class="px-6 mb-4">
                                <span class="text-[10px] font-bold uppercase tracking-[0.2em] text-gray-500">Biblioteca</span>
                            </div>

                            <a href="{{ route('dashboard') }}" 
                               class="flex items-center gap-3 px-6 py-3 transition-all hover:bg-gray-800 group {{ request()->routeIs('dashboard') ? 'nav-item-active' : 'text-gray-400 border-l-3 border-transparent' }}">
                                <i class="ph ph-squares-four text-xl group-hover:text-[#F59E0B] transition-colors {{ request()->routeIs('dashboard') ? 'text-[#F59E0B]' : '' }}"></i>
                                <span class="text-sm font-semibold">Dashboard</span>
                            </a>

                            <a href="{{ route('admin.emprestimos.index') }}" class="flex items-center gap-3 px-6 py-3 transition-all hover:bg-gray-800 group {{ request()->routeIs('admin.emprestimos.index') ? 'nav-item-active' : 'text-gray-400 border-l-3 border-transparent' }}">
                                <i class="ph ph-handshake text-xl group-hover:text-[#F59E0B] transition-colors {{ request()->routeIs('admin.emprestimos.index') ? 'text-[#F59E0B]' : '' }}"></i>
                                <span class="text-sm font-semibold">Controle de Empréstimos</span>
                            </a>

                            @if(auth()->guard('web')->check() && auth()->guard('web')->user()->tipo_usuario === 'gerente')
                            <a href="{{ route('bibliotecarios.create') }}" 
                               class="flex items-center gap-3 px-6 py-3 transition-all hover:bg-gray-800 group {{ request()->routeIs('bibliotecarios.*') ? 'nav-item-active' : 'text-gray-400 border-l-3 border-transparent' }}">
                                <i class="ph ph-user-plus text-xl group-hover:text-[#F59E0B] transition-colors {{ request()->routeIs('bibliotecarios.*') ? 'text-[#F59E0B]' : '' }}"></i>
                                <span class="text-sm font-semibold">Novo Bibliotecário</span>
                            </a>
                            @endif

                            <a href="{{ route('livros.create') }}" 
                               class="flex items-center gap-3 px-6 py-3 transition-all hover:bg-gray-800 group {{ request()->routeIs('livros.create') ? 'nav-item-active' : 'text-gray-400 border-l-3 border-transparent' }}">
                                <i class="ph ph-book-open-text text-xl group-hover:text-[#F59E0B] transition-colors {{ request()->routeIs('livros.create') ? 'text-[#F59E0B]' : '' }}"></i>
                                <span class="text-sm font-semibold">Cadastrar Livro</span>
                            </a>
                        </nav>
                        
                    </div>
                </aside>
            @endif

            <div class="flex-1 flex flex-col min-w-0 bg-[#0f172a]">
                
                <nav class="bg-[#111827] border-b border-gray-800 h-16 flex items-center px-4 sm:px-6 lg:px-8">
                    @if(auth()->check() && in_array(auth()->user()->tipo_usuario, ['gerente', 'bibliotecario']))
                        <button @click="sidebarOpen = !sidebarOpen" 
                                class="p-2 mr-4 rounded-sm bg-gray-800 text-[#F59E0B] hover:bg-[#1E3A8A] hover:text-white transition-all lg:hidden">
                            <i class="ph ph-list text-2xl"></i>
                        </button>
                    @endif

                    <div class="flex-1">
                        @include('layouts.navigation')
                    </div>
                </nav>

                @if (isset($header))
                    <header class="py-6 px-4 sm:px-6 lg:px-8 border-b border-gray-800/50">
                        <div class="max-w-7xl mx-auto">
                            {{ $header }}
                        </div>
                    </header>
                @endif

                <main class="p-4 sm:p-6 lg:p-8 flex-grow">
                    {{ $slot }}
                </main>
            </div>
        </div>

        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        
        <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>

        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

        <script>
            /* Modal Configurado com as cores do projeto */
            const darkSwal = Swal.mixin({
                customClass: {
                    popup: 'bg-[#111827] text-white border border-gray-800 rounded-md shadow-xl',
                    confirmButton: 'px-6 py-2 mx-2 bg-red-500 hover:bg-red-600 text-white rounded-md font-bold uppercase text-xs tracking-widest transition-colors',
                    cancelButton: 'px-6 py-2 mx-2 bg-[#1e293b] border border-gray-700 hover:bg-gray-800 text-white rounded-md font-bold uppercase text-xs tracking-widest transition-colors'
                },
                buttonsStyling: false
            });

            function confirmarExclusao(event, form) {
                event.preventDefault();
                darkSwal.fire({
                    title: 'Remover Registro?',
                    text: "Esta ação não pode ser desfeita.",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'EXCLUIR',
                    cancelButtonText: 'CANCELAR'
                }).then((result) => { if (result.isConfirmed) form.submit(); })
            }
        </script>
    </body>
</html>
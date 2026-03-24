<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" x-data="{ sidebarOpen: false }">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'BiblioTech') }}</title>

        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700;800;900&display=swap" rel="stylesheet">
        
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />

        @vite(['resources/css/app.css', 'resources/js/app.js'])

        <style>
            body { font-family: 'Inter', sans-serif !important; }
            [x-cloak] { display: none !important; }
            
            /* Scrollbar Dark */
            ::-webkit-scrollbar { width: 8px; }
            ::-webkit-scrollbar-track { background: #0f172a; }
            ::-webkit-scrollbar-thumb { background: #1e293b; border-radius: 10px; }
            
            .nav-item-active {
                background: rgba(30, 58, 138, 0.4);
                border-right: 4px solid #F59E0B;
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
                    class="fixed inset-y-0 left-0 w-64 bg-[#111827] border-r border-gray-800 z-50 transform transition-transform duration-300 ease-in-out lg:static lg:block shadow-2xl">
                    
                    <div class="h-full flex flex-col">
                        <div class="p-6 flex items-center justify-between border-b border-gray-800/50">
                            <h2 class="font-black text-lg tracking-tighter text-white uppercase">
                                PAINEL <span class="text-[#F59E0B]">GESTÃO</span>
                            </h2>
                            <button @click="sidebarOpen = false" class="lg:hidden text-gray-400">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/></svg>
                            </button>
                        </div>
                        
                        <nav class="flex-1 px-3 py-4 space-y-2 overflow-y-auto">
                            <div class="px-3 mb-2">
                                <span class="text-[10px] font-black uppercase tracking-[0.2em] text-gray-500">Biblioteca</span>
                            </div>

                            <a href="{{ route('dashboard') }}" 
                               class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all hover:bg-gray-800 group {{ request()->routeIs('dashboard') ? 'nav-item-active' : 'text-gray-400' }}">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/></svg>
                                <span class="text-sm font-bold">Dashboard</span>
                            </a>

                            <a href="{{ route('admin.emprestimos.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all hover:bg-gray-800 group {{ request()->routeIs('admin.emprestimos.index') ? 'nav-item-active' : 'text-gray-400' }}">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                <span class="text-sm font-bold">Controle de Empréstimos</span>
                            </a>

                            @if(auth()->guard('web')->check() && auth()->guard('web')->user()->tipo_usuario === 'gerente')
                            <a href="{{ route('bibliotecarios.create') }}" 
                               class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all hover:bg-gray-800 group {{ request()->routeIs('bibliotecarios.*') ? 'nav-item-active' : 'text-gray-400' }}">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/></svg>
                                <span class="text-sm font-bold">Novo Bibliotecário</span>
                            </a>
                            @endif

                            <a href="{{ route('livros.create') }}" 
                               class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all hover:bg-gray-800 group {{ request()->routeIs('livros.create') ? 'nav-item-active' : 'text-gray-400' }}">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253"/></svg>
                                <span class="text-sm font-bold">Cadastrar Livro</span>
                            </a>
                        </nav>
                        
                        
                    </div>
                </aside>
            @endif

            <div class="flex-1 flex flex-col min-w-0 bg-[#0f172a]">
                
                <nav class="bg-[#111827] border-b border-gray-800 h-16 flex items-center px-4 sm:px-6 lg:px-8">
                    @if(auth()->check() && in_array(auth()->user()->tipo_usuario, ['gerente', 'bibliotecario']))
                        <button @click="sidebarOpen = !sidebarOpen" 
                                class="p-2 mr-4 rounded-lg bg-gray-800 text-[#F59E0B] hover:bg-[#1E3A8A] transition-all lg:hidden">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-width="2.5" d="M4 6h16M4 12h16M4 18h16"/></svg>
                        </button>
                    @endif

                    <div class="flex-1">
                        @include('layouts.navigation')
                    </div>
                </nav>

                @if (isset($header))
                    <header class="py-6 px-4 sm:px-6 lg:px-8">
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
        <script>
            const darkSwal = Swal.mixin({
                customClass: {
                    popup: 'bg-[#111827] text-white border border-gray-800 rounded-3xl',
                    confirmButton: 'px-6 py-2 mx-2 rounded-xl font-bold uppercase text-xs tracking-widest',
                    cancelButton: 'px-6 py-2 mx-2 rounded-xl font-bold uppercase text-xs tracking-widest'
                },
                buttonsStyling: true
            });

            function confirmarExclusao(event, form) {
                event.preventDefault();
                darkSwal.fire({
                    title: 'Remover Registro?',
                    text: "Esta ação não pode ser desfeita.",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#EF4444',
                    cancelButtonColor: '#1e293b',
                    confirmButtonText: 'EXCLUIR',
                    cancelButtonText: 'CANCELAR'
                }).then((result) => { if (result.isConfirmed) form.submit(); })
            }
        </script>
    </body>
</html>
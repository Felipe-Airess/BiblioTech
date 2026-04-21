<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" x-data="{ sidebarOpen: false }">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'BiblioTech') }}</title>

        <link rel="icon" type="image/svg+xml" href="{{ asset('logo.svg') }}">

        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&family=Merriweather:wght@400;700;900&display=swap" rel="stylesheet">
        
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />

        <script src="https://unpkg.com/@phosphor-icons/web"></script>

        @vite(['resources/css/app.css', 'resources/js/app.js'])

        <style>
            body { font-family: 'Inter', sans-serif !important; }
            h1, h2, h3, h4, h5, h6, .font-serif, .titulo-mw { font-family: 'Merriweather', serif !important; }
            [x-cloak] { display: none !important; }
            /* Scrollbar Dark */
            ::-webkit-scrollbar { width: 8px; }
            ::-webkit-scrollbar-track { background: #0f172a; }
            ::-webkit-scrollbar-thumb { background: #1e293b; border-radius: 0; }
            ::-webkit-scrollbar-thumb:hover { background: #334155; }
            /* Menu Ativo */
            .nav-item-active {
                background: rgba(30, 58, 138, 0.4);
                border-left: 3px solid #F59E0B;
                color: white;
            }

            /* ── Header sticky com blur — vira opaco ao rolar ── */
            #app-header {
                position: sticky;
                top: 0;
                z-index: 30;
                background: rgba(8, 13, 20, 0.0);
                border-bottom: 1px solid transparent;
                transition: background .3s ease, border-color .3s ease, backdrop-filter .3s ease;
            }
            #app-header.scrolled {
                background: rgba(8, 13, 20, 0.92);
                border-bottom-color: rgba(255,255,255,.04);
                backdrop-filter: blur(14px);
                -webkit-backdrop-filter: blur(14px);
            }
        </style>
    </head>
    <body class="antialiased bg-[#0f172a] text-gray-200 overflow-x-hidden">
        
        <div class="flex min-h-screen relative">

            {{-- ── Drawer flutuante ── --}}
            <div x-data="{ open: false }">
                <button @click="open = true"
                        class="fixed bottom-6 right-6 z-50 w-14 h-14 shrink-0 rounded-full
                               bg-[#1E3A8A] text-white shadow-2xl shadow-blue-900/40
                               hover:bg-[#162a63] hover:scale-110 active:scale-95
                               transition-all flex items-center justify-center focus:outline-none">
                    <i class="ph ph-list text-2xl leading-none"></i>
                </button>

                <div x-show="open" x-cloak
                     x-transition:enter="transition ease-out duration-200"
                     x-transition:enter-start="opacity-0"
                     x-transition:enter-end="opacity-100"
                     x-transition:leave="transition ease-in duration-150"
                     x-transition:leave-start="opacity-100"
                     x-transition:leave-end="opacity-0"
                     class="fixed inset-0 z-40 flex">

                    <div @click="open = false" class="absolute inset-0 bg-black/60 backdrop-blur-sm"></div>

                    <aside x-show="open"
                           x-transition:enter="transition ease-out duration-200"
                           x-transition:enter-start="-translate-x-full"
                           x-transition:enter-end="translate-x-0"
                           x-transition:leave="transition ease-in duration-150"
                           x-transition:leave-start="translate-x-0"
                           x-transition:leave-end="-translate-x-full"
                           class="relative w-64 h-full bg-[#080d14] border-r border-white/5 shadow-2xl z-50 flex flex-col">

                        <div class="flex items-center justify-between px-5 py-4 border-b border-white/5">
                            <span class="font-black text-xl text-white tracking-tighter font-serif">
                                Biblio<span class="text-[#F59E0B]">Tech</span>
                            </span>
                            <button @click="open = false" class="w-8 h-8 rounded-lg flex items-center justify-center text-gray-500 hover:text-white hover:bg-white/5 transition">
                                <i class="ph ph-x"></i>
                            </button>
                        </div>

                        <nav class="flex flex-col gap-0.5 p-3 flex-1 overflow-y-auto">
                            <a href="{{ route('dashboard') }}"
                               class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-semibold
                                      {{ request()->routeIs('dashboard') ? 'text-white bg-[#1E3A8A]/40 border border-[#1E3A8A]/50' : 'text-gray-400 hover:text-white hover:bg-white/5 border border-transparent' }}">
                                <i class="ph-fill ph-squares-four text-blue-400 text-base shrink-0"></i>
                                Acervo
                            </a>

                            @auth
                                @if(auth()->user()->tipo_usuario === 'membro')
                                <a href="{{ route('emprestimos.historico') }}"
                                   class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium
                                          {{ request()->routeIs('emprestimos.historico') ? 'text-white bg-[#F59E0B]/20 border border-[#F59E0B]/40' : 'text-gray-400 hover:text-white hover:bg-white/5' }}">
                                    <i class="ph ph-clock-countdown text-amber-500/70 text-base shrink-0"></i>
                                    Meus Empréstimos
                                </a>
                                @endif

                                <a href="{{ route('profile.edit') }}"
                                   class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium
                                          {{ request()->routeIs('profile.edit') ? 'text-white bg-blue-900/30 border border-blue-400/40' : 'text-gray-400 hover:text-white hover:bg-white/5' }}">
                                    <i class="ph ph-user text-slate-500 text-base shrink-0"></i>
                                    Meu Perfil
                                </a>

                                @if(in_array(auth()->user()->tipo_usuario, ['gerente', 'bibliotecario']))
                                <div class="my-2 pt-2 border-t border-white/5">
                                    <p class="px-3 text-[10px] font-bold uppercase tracking-[0.15em] text-slate-600 mb-1">Admin</p>
                                </div>
                                <a href="{{ route('admin.emprestimos.index') }}"
                                   class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium
                                          {{ request()->routeIs('admin.emprestimos.index') ? 'text-amber-400 bg-amber-900/10 border border-amber-400/40' : 'text-gray-400 hover:text-amber-400 hover:bg-amber-900/10' }}">
                                    <i class="ph ph-handshake text-amber-500/60 text-base shrink-0"></i>
                                    Painel de Empréstimos
                                </a>
                                <a href="{{ route('livros.create') }}"
                                   class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium
                                          {{ request()->routeIs('livros.create') ? 'text-blue-400 bg-blue-900/10 border border-blue-400/40' : 'text-gray-400 hover:text-blue-400 hover:bg-blue-900/10' }}">
                                    <i class="ph ph-book-bookmark text-blue-400 text-base shrink-0"></i>
                                    Cadastrar Livro
                                </a>
                                <a href="{{ route('autores.create') }}"
                                   class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium
                                          {{ request()->routeIs('autores.create') ? 'text-blue-400 bg-blue-900/10 border border-blue-400/40' : 'text-gray-400 hover:text-blue-400 hover:bg-blue-900/10' }}">
                                    <i class="ph ph-user-plus text-blue-400 text-base shrink-0"></i>
                                    Cadastrar Autor
                                </a>
                                @endif
                                @if(auth()->user()->tipo_usuario === 'gerente')
                                <a href="{{ route('bibliotecarios.create') }}"
                                   class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium
                                          {{ request()->routeIs('bibliotecarios.create') ? 'text-amber-400 bg-amber-900/10 border border-amber-400/40' : 'text-gray-400 hover:text-amber-400 hover:bg-amber-900/10' }}">
                                    <i class="ph ph-user-gear text-amber-500/60 text-base shrink-0"></i>
                                    Adicionar Bibliotecário
                                </a>
                                @endif
                                <form method="POST" action="{{ route('logout') }}" class="mt-8">
                                    @csrf
                                    <button type="submit" class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-bold text-red-400 hover:text-white hover:bg-red-900/20 transition w-full">
                                        <i class="ph ph-sign-out"></i> Sair
                                    </button>
                                </form>
                            @else
                                <a href="{{ route('login') }}"
                                   class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium
                                          {{ request()->routeIs('login') ? 'text-white bg-blue-900/30 border border-blue-400/40' : 'text-gray-400 hover:text-white hover:bg-white/5' }}">
                                    <i class="ph ph-sign-in text-slate-500 text-base shrink-0"></i>
                                    Entrar
                                </a>
                            @endauth
                        </nav>
                    </aside>
                </div>
            </div>

            {{-- ── Área principal ── --}}
            <div class="flex-1 flex flex-col min-w-0 bg-[#080d14]">

                {{-- Header sticky transparente — fica sobre o hero --}}
                @if (isset($header))
                    <header id="app-header" class="px-4 sm:px-6 lg:px-8">
                        <div class="max-w-7xl mx-auto h-14 flex items-center">
                            {{ $header }}
                        </div>
                    </header>
                @endif

                <main class="flex-grow px-4 sm:px-6 lg:px-8 py-6 ">
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

            /* Header: fica opaco ao rolar */
            const appHeader = document.getElementById('app-header');
            if (appHeader) {
                const onScroll = () => {
                    appHeader.classList.toggle('scrolled', window.scrollY > 20);
                };
                window.addEventListener('scroll', onScroll, { passive: true });
            }
        </script>
    </body>
</html>
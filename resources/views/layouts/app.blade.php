<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased">
        <div class="min-h-screen bg-gray-100">
            @include('layouts.navigation')

            @if (isset($header))
    <header class="bg-white dark:bg-gray-800 border-b border-gray-200 dark:border-gray-700 transition-colors duration-200">
        <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
            {{ $header }}
        </div>
    </header>
@endif

            <main>
                {{ $slot }}
            </main>
        </div>
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

        <script>
            function confirmarExclusao(event, form) {
                event.preventDefault(); // Trava o envio imediato do formulário

                Swal.fire({
                    title: 'Tem certeza?',
                    text: "Esta ação não poderá ser desfeita!",
                    icon: 'warning',
                    showCancelButton: true,
                    // Usando as cores do nosso Guia de Estilos!
                    confirmButtonColor: '#EF4444', // Vermelho de Alerta
                    cancelButtonColor: '#1F2937', // Cinza Escuro para cancelar
                    confirmButtonText: 'Sim, excluir livro!',
                    cancelButtonText: 'Cancelar'
                }).then((result) => {
                    if (result.isConfirmed) {
                        // Se o usuário clicou em "Sim", a gente envia o formulário
                        form.submit(); 
                    }
                })
            }
            function confirmarEdicao(event, form) {
                event.preventDefault(); // Trava o envio do formulário

                Swal.fire({
                    title: 'Salvar alterações?',
                    text: "Deseja confirmar a atualização dos dados?",
                    icon: 'question', // Ícone de interrogação, pois não é destrutivo
                    showCancelButton: true,
                    confirmButtonColor: '#F59E0B', // Âmbar/Dourado do nosso Guia de Estilos
                    cancelButtonColor: '#1F2937', // Cinza Escuro
                    confirmButtonText: 'Sim, salvar!',
                    cancelButtonText: 'Cancelar'
                }).then((result) => {
                    if (result.isConfirmed) {
                        form.submit(); 
                    }
                })
            }
            

        
        

        
        </script>
    </body>
</html>

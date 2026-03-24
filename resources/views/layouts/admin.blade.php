<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Admin - Biblioteca</title>
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="bg-gray-100 dark:bg-gray-900">
        <div class="flex">
            <x-admin-sidebar />

            <div class="flex-1 h-screen overflow-y-auto">
                <header class="bg-white dark:bg-gray-800 shadow px-6 py-4 flex justify-between items-center">
                    <h1 class="text-xl font-bold dark:text-white">Painel Administrativo</h1>
                    <div class="text-sm dark:text-gray-300">
                        Logado como: <strong>{{ Auth::user()->name }}</strong>
                    </div>
                </header>

                <main class="p-6">
                    {{ $slot }}
                </main>
            </div>
        </div>
    </body>
</html>
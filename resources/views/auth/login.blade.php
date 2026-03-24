<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Login - BiblioTech</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700,800&display=swap" rel="stylesheet" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased text-gray-900 dark:text-gray-100 min-h-screen bg-gray-50 dark:bg-gray-900 transition-colors duration-200">

    <div class="flex min-h-screen">
        
        <div class="hidden lg:flex lg:w-1/2 bg-gradient-to-br from-blue-500 to-blue-900 dark:from-gray-800 dark:to-blue-900 flex-col justify-center items-center p-12 relative text-white transition-colors duration-200">
            
            <a href="/" class="absolute top-8 left-8 text-sm font-semibold flex items-center gap-2 hover:text-blue-200 transition">
                <span>&lsaquo;</span> Ir para o Site da Biblioteca
            </a>

            <div class="text-center">
                <h1 class="text-5xl font-extrabold tracking-tight mb-4 drop-shadow-md">
                    BiblioTech
                </h1>
                <p class="text-2xl font-light text-blue-100 dark:text-gray-300">
                    Bem-vindo à Biblioteca Virtual!
                </p>
                <div class="mt-8 w-16 h-1 bg-blue-300 opacity-50 mx-auto rounded"></div>
            </div>
            
            
        </div>

        <div class="w-full lg:w-1/2 flex justify-center items-center p-6 sm:p-12 relative bg-gray-100 dark:bg-gray-900 transition-colors duration-200">
            
            <div class="absolute top-8 left-0 w-full flex justify-center lg:hidden">
                <span class="font-extrabold text-3xl text-blue-900 dark:text-blue-400">BiblioTech</span>
            </div>

            <div class="w-full max-w-md bg-white dark:bg-gray-800 rounded-2xl shadow-2xl border border-gray-100 dark:border-gray-700 p-8 sm:p-10 z-10 transition-colors duration-200">
                
                <div class="mb-8">
                    <h2 class="text-3xl font-bold text-blue-600 dark:text-blue-400 mb-2">Login</h2>
                    <p class="text-gray-500 dark:text-gray-400 text-sm">Identifique-se para prosseguir</p>
                </div>

                <x-auth-session-status class="mb-4" :status="session('status')" />

                <form method="POST" action="{{ route('login') }}">
                    @csrf

                    @php
                        $inputClasses = "w-full border-gray-300 dark:border-gray-600 text-gray-900 dark:text-gray-200 bg-white dark:bg-gray-700 rounded-lg shadow-sm focus:border-blue-500 focus:ring-blue-500 px-4 py-3 transition-colors duration-200";
                        $labelClasses = "block font-semibold text-sm text-gray-700 dark:text-gray-300 mb-1";
                    @endphp

                    <div class="mb-5">
                        <label for="email" class="{{ $labelClasses }}">E-mail</label>
                        <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus autocomplete="username" class="{{ $inputClasses }}">
                        <x-input-error :messages="$errors->get('email')" class="mt-2 text-red-500 dark:text-red-400 text-sm" />
                    </div>

                    <div class="mb-5 relative">
                        <label for="password" class="{{ $labelClasses }}">Senha</label>
                        <input id="password" type="password" name="password" required autocomplete="current-password" class="{{ $inputClasses }}">
                        <x-input-error :messages="$errors->get('password')" class="mt-2 text-red-500 dark:text-red-400 text-sm" />
                    </div>

                    <div class="flex items-center justify-between mb-8">
                        <label for="remember_me" class="inline-flex items-center cursor-pointer">
                            <input id="remember_me" type="checkbox" name="remember" class="rounded border-gray-300 text-blue-600 shadow-sm focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600">
                            <span class="ms-2 text-sm text-gray-600 dark:text-gray-400 font-medium select-none">Lembre de mim</span>
                        </label>

                        @if (Route::has('password.request'))
                            <a class="text-sm font-semibold text-gray-600 dark:text-gray-400 hover:text-blue-600 dark:hover:text-blue-400 transition underline decoration-transparent hover:decoration-current" href="{{ route('password.request') }}">
                                Esqueci minha Senha
                            </a>
                        @endif
                    </div>

                    <div class="flex gap-4">
                        <button type="submit" class="w-1/2 py-3 bg-blue-500 hover:bg-blue-600 dark:bg-blue-600 dark:hover:bg-blue-700 text-white font-bold rounded-lg transition-colors shadow-md text-center">
                            Acessar
                        </button>
                        
                        <a href="{{ route('membros.create') }}" class="w-1/2 py-3 bg-white dark:bg-gray-800 border border-blue-500 dark:border-blue-400 text-blue-600 dark:text-blue-400 hover:bg-blue-50 dark:hover:bg-gray-700 font-bold rounded-lg transition-colors shadow-sm text-center flex items-center justify-center">
                            Cadastrar
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</body>
</html>
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>BiblioTech - Bem-vindo</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700,800&display=swap" rel="stylesheet" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <script src="https://unpkg.com/typed.js@2.1.0/dist/typed.umd.js"></script>

    <style>
        .float-anim {
            animation: float 6s ease-in-out infinite;
        }
        @keyframes float {
            0% { transform: translateY(0px); }
            50% { transform: translateY(-20px); }
            100% { transform: translateY(0px); }
        }
    </style>
</head>
<body class="font-sans antialiased text-gray-900 dark:text-gray-100 bg-gray-50 dark:bg-gray-900 transition-colors duration-200 min-h-screen flex flex-col relative overflow-hidden">

    <div class="absolute top-[-10%] left-[-10%] w-96 h-96 bg-blue-400/20 dark:bg-blue-900/20 rounded-full blur-3xl mix-blend-multiply dark:mix-blend-overlay"></div>
    <div class="absolute bottom-[-10%] right-[-10%] w-96 h-96 bg-indigo-400/20 dark:bg-indigo-900/20 rounded-full blur-3xl mix-blend-multiply dark:mix-blend-overlay"></div>

    <nav class="w-full p-6 flex justify-between items-center absolute top-0 z-10">
        <div class="font-extrabold text-2xl text-blue-900 dark:text-blue-400 tracking-wider flex items-center gap-2">
            BiblioTech
        </div>
        
    </nav>

    <main class="flex-grow flex flex-col justify-center items-center px-6 text-center z-10">
        
        <div class="text-8xl md:text-9xl mb-6 float-anim drop-shadow-2xl select-none">
            📖
        </div>

        <h1 class="text-6xl md:text-8xl font-extrabold tracking-tight text-transparent bg-clip-text bg-gradient-to-r from-blue-600 to-indigo-900 dark:from-blue-400 dark:to-blue-200 mb-6 drop-shadow-sm py-2">
            BiblioTech
        </h1>
        
        <div class="text-xl md:text-2xl font-light text-gray-600 dark:text-gray-400 h-12 mb-10">
            <span id="typed-text"></span>
        </div>

        <div class="flex flex-col sm:flex-row gap-4 mt-4">
            @auth
                <a href="{{ url('/dashboard') }}" class="px-8 py-4 text-lg font-bold bg-blue-600 hover:bg-blue-700 text-white rounded-full transition-all duration-300 shadow-lg hover:shadow-blue-500/50 hover:-translate-y-1">
                    Acessar Meu Acervo
                </a>
            @else
                <a href="{{ route('login') }}" class="px-8 py-4 text-lg font-bold bg-blue-600 hover:bg-blue-700 text-white rounded-full transition-all duration-300 shadow-lg hover:shadow-blue-500/50 hover:-translate-y-1">
                    Começar Agora
                </a>
            @endauth
        </div>
    </main>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            var typed = new Typed('#typed-text', {
                strings: [
                    'O futuro da gestão literária.', 
                    'Seu acervo organizado e acessível.', 
                    'A biblioteca virtual inteligente.',
                    'Simples, rápido e moderno.'
                ],
                typeSpeed: 50,
                backSpeed: 30,
                backDelay: 2000,
                loop: true,
                showCursor: true,
                cursorChar: '|' // Aquele cursor piscando estilo terminal
            });
        });
    </script>
</body>
</html>
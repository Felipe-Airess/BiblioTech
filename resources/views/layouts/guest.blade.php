<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Sistema') }}</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;900&family=Merriweather:wght@400;700;900&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(15px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .animate-fade-in {
            animation: fadeIn 0.6s ease-out forwards;
        }
        body { font-family: 'Inter', sans-serif; }
    </style>
</head>

<body class="text-gray-200 antialiased bg-gray-900 selection:bg-[#F59E0B] selection:text-white transition-colors duration-300">
    <div class="min-h-screen flex flex-col sm:justify-center items-center py-12 px-4 sm:px-6 lg:px-8">
        
        <div class="w-full max-w-6xl p-8 bg-gray-800 shadow-2xl shadow-black/50 sm:rounded-md border border-gray-700 animate-fade-in">
            {{ $slot }}
        </div>
        
    </div>
</body>

</html>
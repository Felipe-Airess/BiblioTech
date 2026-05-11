@props([
    'code' => 'Erro',
    'title' => 'Algo saiu do lugar',
    'message' => 'Não foi possível concluir essa ação agora.',
    'tone' => 'blue',
    'icon' => 'ph-warning-circle',
])

@php
    $toneClasses = [
        'blue' => 'border-blue-200 bg-blue-50 text-blue-800 dark:border-blue-500/30 dark:bg-blue-500/10 dark:text-blue-300',
        'amber' => 'border-amber-200 bg-amber-50 text-amber-800 dark:border-amber-500/30 dark:bg-amber-500/10 dark:text-amber-300',
        'red' => 'border-red-200 bg-red-50 text-red-700 dark:border-red-500/30 dark:bg-red-500/10 dark:text-red-300',
    ][$tone] ?? 'border-blue-200 bg-blue-50 text-blue-800 dark:border-blue-500/30 dark:bg-blue-500/10 dark:text-blue-300';

    $dashboardUrl = Route::has('dashboard') ? route('dashboard') : url('/');
    $loginUrl = Route::has('login') ? route('login') : url('/');
@endphp

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>{{ $code }} - BiblioTech</title>
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="min-h-screen bg-gradient-to-b from-slate-100 via-blue-50 to-slate-100 text-slate-900 antialiased dark:from-[#0f172a] dark:via-[#0f172a] dark:to-[#0b1120] dark:text-white">
        <main class="relative flex min-h-screen items-center justify-center overflow-hidden px-4 py-10">
            <div class="pointer-events-none fixed inset-0" aria-hidden="true">
                <svg class="absolute inset-0 h-full w-full" xmlns="http://www.w3.org/2000/svg">
                    <defs>
                        <pattern id="error-dots" width="28" height="28" patternUnits="userSpaceOnUse">
                            <circle cx="1" cy="1" r="1" fill="#1E3A8A" opacity="0.08"/>
                        </pattern>
                    </defs>
                    <rect width="100%" height="100%" fill="url(#error-dots)"/>
                </svg>
                <i class="ph ph-books absolute left-[8%] top-[16%] text-[42px] text-amber-500/20 dark:text-amber-300/10"></i>
                <i class="ph ph-lock-key absolute right-[10%] top-[20%] text-[38px] text-blue-800/10 dark:text-blue-300/10"></i>
                <i class="ph ph-library absolute right-[18%] bottom-[16%] text-[46px] text-amber-500/15 dark:text-amber-300/10"></i>
            </div>

            <section class="relative z-10 w-full max-w-xl rounded-md border border-slate-200 bg-white/95 p-6 text-center shadow-sm dark:border-white/10 dark:bg-[#0d1420]/95 sm:p-8">
                <a href="{{ $dashboardUrl }}" class="mx-auto flex w-fit flex-col items-center justify-center gap-1">
                    <i class="ph ph-library text-5xl text-[#1E3A8A] dark:text-blue-400"></i>
                    <div class="text-center text-[11px] font-black leading-tight tracking-tight">
                        <span class="text-[#1E3A8A] dark:text-blue-400">BIBLIO</span><br>
                        <span class="text-[#F59E0B]">TECH</span>
                    </div>
                </a>

                <div class="mx-auto mt-6 inline-flex items-center gap-2 rounded-md border px-3 py-1.5 text-[11px] font-black uppercase tracking-[.16em] {{ $toneClasses }}">
                    <i class="ph {{ $icon }}"></i>
                    {{ $code }}
                </div>

                <h1 class="mt-4 font-serif text-3xl font-black text-slate-950 dark:text-white sm:text-4xl">{{ $title }}</h1>
                <p class="mx-auto mt-3 max-w-md text-sm leading-relaxed text-slate-600 dark:text-slate-400">{{ $message }}</p>

                <div class="mt-6 flex flex-col justify-center gap-3 sm:flex-row">
                    <a href="{{ $dashboardUrl }}" class="inline-flex h-11 items-center justify-center gap-2 rounded-md bg-[#1E3A8A] px-5 text-[11px] font-black uppercase tracking-widest text-white transition hover:bg-blue-800">
                        <i class="ph ph-house"></i>
                        Ir ao painel
                    </a>
                    @guest('web')
                        @guest('membro')
                            <a href="{{ $loginUrl }}" class="inline-flex h-11 items-center justify-center gap-2 rounded-md border border-slate-200 bg-white px-5 text-[11px] font-black uppercase tracking-widest text-slate-700 transition hover:bg-slate-50 dark:border-white/10 dark:bg-white/5 dark:text-slate-300 dark:hover:bg-white/10">
                                <i class="ph ph-sign-in"></i>
                                Entrar
                            </a>
                        @endguest
                    @endguest
                </div>
            </section>
        </main>
    </body>
</html>

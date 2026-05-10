<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" x-data="{ dark: $persist(true) }" x-effect="document.documentElement.classList.toggle('dark', dark)">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'BiblioTech') }}</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700;900&family=Merriweather:wght@400;700;900&display=swap" rel="stylesheet">
    <script src="https://unpkg.com/@phosphor-icons/web"></script>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>

<body class="min-h-screen bg-gradient-to-b from-slate-100 via-blue-50 to-slate-100 font-sans text-slate-900 antialiased selection:bg-[#F59E0B] selection:text-slate-950 dark:from-[#0f172a] dark:via-[#0f172a] dark:to-[#0b1120] dark:text-slate-100">
    <div class="fixed inset-0 z-0 pointer-events-none overflow-hidden" aria-hidden="true">
        <svg class="absolute inset-0 h-full w-full" xmlns="http://www.w3.org/2000/svg">
            <defs>
                <pattern id="guest-dots" width="28" height="28" patternUnits="userSpaceOnUse">
                    <circle cx="1" cy="1" r="1" fill="#1E3A8A" opacity="0.08"/>
                </pattern>
            </defs>
            <rect width="100%" height="100%" fill="url(#guest-dots)"/>
        </svg>
        <i class="ph ph-book-open absolute left-[7%] top-[12%] text-[46px] text-amber-500/20 dark:text-amber-300/10"></i>
        <i class="ph ph-library absolute right-[10%] top-[18%] text-[44px] text-blue-800/10 dark:text-blue-300/10"></i>
        <i class="ph ph-bookmarks absolute right-[18%] bottom-[14%] text-[52px] text-amber-500/20 dark:text-amber-300/10"></i>
    </div>

    <main class="relative z-10 flex min-h-screen items-center justify-center px-4 py-10 sm:px-6 lg:px-8">
        <div class="w-full max-w-xl">
            <div class="mb-6 flex items-center justify-between gap-4">
                <a href="{{ url('/') }}" class="inline-flex items-center gap-3">
                    <span class="flex h-12 w-12 items-center justify-center rounded-md border border-blue-200 bg-white text-[#1E3A8A] shadow-sm dark:border-white/10 dark:bg-white/5 dark:text-blue-300">
                        <i class="ph ph-library text-2xl"></i>
                    </span>
                    <span class="text-[13px] font-black uppercase leading-tight tracking-tight">
                        <span class="text-[#1E3A8A] dark:text-blue-300">Biblio</span><br>
                        <span class="text-[#F59E0B]">Tech</span>
                    </span>
                </a>

                <button type="button" @click="dark = !dark" class="h-10 w-10 rounded-md border border-slate-200 bg-white text-slate-600 transition hover:bg-slate-50 dark:border-white/10 dark:bg-white/5 dark:text-slate-300 dark:hover:bg-white/10" aria-label="Alternar tema">
                    <i class="ph text-sm" :class="dark ? 'ph-sun' : 'ph-moon'"></i>
                </button>
            </div>

            <section class="rounded-md border border-slate-200 bg-white p-6 shadow-xl shadow-slate-950/10 dark:border-white/10 dark:bg-[#0d1420] dark:shadow-black/30 sm:p-8">
                {{ $slot }}
            </section>

            <a href="{{ route('login') }}" class="mt-4 inline-flex items-center gap-2 text-[11px] font-black uppercase tracking-widest text-slate-500 transition hover:text-blue-700 dark:text-slate-400 dark:hover:text-blue-300">
                <i class="ph ph-arrow-left"></i>
                Voltar ao login
            </a>
        </div>
    </main>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            if (typeof Swal === 'undefined') return;

            const toast = Swal.mixin({
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 5000,
                timerProgressBar: true,
                background: '#0d1420',
                color: '#fff'
            });

            const errors = {!! json_encode($errors->all()) !!};
            errors.forEach((message) => toast.fire({ icon: 'error', title: message }));

            @if(session('sucesso'))
                toast.fire({ icon: 'success', title: {!! json_encode(session('sucesso')) !!} });
            @endif

            @if(session('error'))
                toast.fire({ icon: 'error', title: {!! json_encode(session('error')) !!} });
            @endif

            @if(session('status'))
                toast.fire({ icon: 'info', title: {!! json_encode(session('status')) !!} });
            @endif
        });
    </script>
</body>

</html>

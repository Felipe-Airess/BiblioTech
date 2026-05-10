<x-app-layout>
    <x-slot name="header">
        <div class="flex w-full flex-wrap items-center justify-between gap-4">
            <div class="flex items-center gap-3">
                <a href="{{ route('dashboard') }}" class="flex shrink-0 flex-col items-center justify-center gap-1">
                    <i class="ph ph-library text-4xl text-[#1E3A8A] dark:text-blue-400"></i>
                    <div class="text-center text-[11px] font-black leading-tight tracking-tight">
                        <span class="text-[#1E3A8A] dark:text-blue-400">BIBLIO</span><br>
                        <span class="text-[#F59E0B]">TECH</span>
                    </div>
                </a>
                <div>
                    <p class="text-[10px] font-black uppercase tracking-[.18em] text-blue-700 dark:text-blue-300">Administração</p>
                    <h1 class="font-serif text-2xl font-black text-slate-950 dark:text-white">Editar Autor</h1>
                    <p class="text-xs text-slate-500 dark:text-slate-400">{{ $autor->nome }}</p>
                </div>
            </div>

            <button type="button" @click="dark = !dark" class="h-10 w-10 rounded-md border border-slate-200 bg-white text-slate-600 transition hover:bg-slate-50 dark:border-white/10 dark:bg-white/5 dark:text-slate-300 dark:hover:bg-white/10" aria-label="Alternar tema">
                <i class="ph text-sm" :class="dark ? 'ph-sun' : 'ph-moon'"></i>
            </button>
        </div>
    </x-slot>

    <div class="-mx-4 min-h-screen bg-gradient-to-b from-slate-100 via-blue-50 to-slate-100 px-4 py-8 dark:from-[#0f172a] dark:via-[#0f172a] dark:to-[#0b1120] sm:-mx-6 sm:px-6 lg:-mx-8 lg:px-8">
        <div class="fixed inset-0 z-0 pointer-events-none overflow-hidden" aria-hidden="true">
            <svg class="absolute inset-0 h-full w-full" xmlns="http://www.w3.org/2000/svg">
                <defs>
                    <pattern id="autor-edit-dots" width="28" height="28" patternUnits="userSpaceOnUse">
                        <circle cx="1" cy="1" r="1" fill="#1E3A8A" opacity="0.08"/>
                    </pattern>
                </defs>
                <rect width="100%" height="100%" fill="url(#autor-edit-dots)"/>
            </svg>
            <i class="ph ph-pencil-simple absolute left-[8%] top-[14%] text-[42px] text-amber-500/25 dark:text-amber-300/10"></i>
            <i class="ph ph-scroll absolute right-[12%] top-[22%] text-[44px] text-blue-800/10 dark:text-blue-300/10"></i>
        </div>

        <main class="relative z-10 mx-auto max-w-7xl">
            @include('admin.autores._form', [
                'action' => route('autores.update', $autor->id),
                'method' => 'PUT',
                'title' => 'Atualizar autor',
                'subtitle' => 'Ajuste a ficha pública e administrativa deste autor no catálogo.',
                'submitLabel' => 'Atualizar autor',
            ])
        </main>
    </div>
</x-app-layout>

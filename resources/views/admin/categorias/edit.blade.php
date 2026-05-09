<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between w-full gap-4">
            <div class="flex items-center gap-3">
                <a href="{{ route('dashboard') }}" class="flex flex-col items-center justify-center gap-1 shrink-0">
                    <i class="ph ph-library text-[#1E3A8A] dark:text-blue-400 text-4xl"></i>
                    <div class="text-[11px] font-black tracking-tight text-center leading-tight">
                        <span class="text-[#1E3A8A] dark:text-blue-400">BIBLIO</span><br>
                        <span class="text-[#F59E0B]">TECH</span>
                    </div>
                </a>
                <div>
                    <h1 class="text-lg font-black text-slate-900 dark:text-white">Editar Categoria</h1>
                    <p class="text-xs text-slate-500 dark:text-slate-400">{{ $categoria->nome }}</p>
                </div>
            </div>
            <a href="{{ route('categorias.index') }}" class="inline-flex items-center gap-2 px-4 py-2 rounded-md bg-slate-100 dark:bg-white/5 border border-slate-200 dark:border-white/10 text-slate-700 dark:text-gray-300 hover:text-slate-900 dark:hover:text-white text-[11px] font-bold uppercase tracking-widest transition">
                <i class="ph ph-arrow-left"></i>
                Voltar
            </a>
        </div>
    </x-slot>

    <div class="-mx-4 sm:-mx-6 lg:-mx-8 px-4 sm:px-6 lg:px-8 py-8 min-h-screen bg-slate-100 dark:bg-[#0f172a]">
        <div class="max-w-3xl mx-auto">
            <form method="POST" action="{{ route('categorias.update', $categoria) }}" class="bg-white dark:bg-[#111827] border border-slate-200 dark:border-[#1e293b] rounded-lg p-6 sm:p-8 space-y-5">
                @csrf
                @method('PUT')

                @if($errors->any())
                    <div class="rounded-md border border-red-500/30 bg-red-500/10 px-4 py-3 text-sm text-red-700 dark:text-red-300">
                        {{ $errors->first() }}
                    </div>
                @endif

                <div>
                    <label for="nome" class="block text-[11px] uppercase tracking-wider text-slate-500 dark:text-slate-400 font-black mb-1">Nome</label>
                    <input id="nome" name="nome" value="{{ old('nome', $categoria->nome) }}" required class="w-full rounded-md border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-950 text-slate-900 dark:text-white text-sm">
                    <p class="mt-1 text-xs text-slate-500 dark:text-slate-400">Ao renomear, os livros dessa categoria são atualizados junto.</p>
                </div>

                <div>
                    <label for="descricao" class="block text-[11px] uppercase tracking-wider text-slate-500 dark:text-slate-400 font-black mb-1">Descrição</label>
                    <textarea id="descricao" name="descricao" rows="4" class="w-full rounded-md border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-950 text-slate-900 dark:text-white text-sm">{{ old('descricao', $categoria->descricao) }}</textarea>
                </div>

                <div class="flex flex-col sm:flex-row gap-3">
                    <button class="inline-flex items-center justify-center gap-2 h-11 px-5 rounded-md bg-[#1E3A8A] text-white text-xs font-black uppercase tracking-widest hover:bg-blue-800 transition">
                        <i class="ph ph-floppy-disk"></i>
                        Salvar alterações
                    </button>
                    <a href="{{ route('categorias.index') }}" class="inline-flex items-center justify-center h-11 px-5 rounded-md bg-slate-100 dark:bg-white/5 border border-slate-200 dark:border-white/10 text-slate-700 dark:text-slate-300 text-xs font-black uppercase tracking-widest hover:bg-slate-200 dark:hover:bg-white/10 transition">
                        Cancelar
                    </a>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>

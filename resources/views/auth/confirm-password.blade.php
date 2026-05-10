<x-guest-layout>
    <div class="mb-7">
        <p class="text-[10px] font-black uppercase tracking-[.18em] text-amber-700 dark:text-amber-300">Área segura</p>
        <h1 class="mt-1 font-serif text-3xl font-black text-slate-950 dark:text-white">Confirme sua senha</h1>
        <p class="mt-2 text-sm leading-6 text-slate-500 dark:text-slate-400">
            Para continuar, confirme sua senha atual.
        </p>
    </div>

    <form method="POST" action="{{ route('password.confirm') }}" class="space-y-5">
        @csrf

        <div>
            <label for="password" class="mb-1 block text-[10px] font-black uppercase tracking-widest text-slate-500 dark:text-slate-400">Senha</label>
            <input id="password" type="password" name="password" required autocomplete="current-password" class="h-11 w-full rounded-md border border-slate-200 bg-white px-3 text-sm text-slate-900 outline-none transition focus:border-[#1E3A8A] focus:ring-2 focus:ring-[#1E3A8A]/20 dark:border-white/10 dark:bg-[#080d14] dark:text-slate-100">
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <button type="submit" class="inline-flex h-11 w-full items-center justify-center gap-2 rounded-md bg-[#1E3A8A] px-4 text-[11px] font-black uppercase tracking-widest text-white transition hover:bg-blue-800">
            <i class="ph ph-lock-key"></i>
            Confirmar
        </button>
    </form>
</x-guest-layout>

<x-guest-layout>
    <div class="mb-7">
        <p class="text-[10px] font-black uppercase tracking-[.18em] text-blue-700 dark:text-blue-300">Verificação</p>
        <h1 class="mt-1 font-serif text-3xl font-black text-slate-950 dark:text-white">Confirme seu e-mail</h1>
        <p class="mt-2 text-sm leading-6 text-slate-500 dark:text-slate-400">
            Enviamos um link de verificação para seu email. Use esse link para liberar o acesso completo.
        </p>
    </div>

    @if (session('status') == 'verification-link-sent')
        <div class="mb-5 rounded-md border border-emerald-200 bg-emerald-50 p-3 text-sm font-bold text-emerald-800 dark:border-emerald-500/30 dark:bg-emerald-500/10 dark:text-emerald-200">
            Um novo link de verificação foi enviado para o email cadastrado.
        </div>
    @endif

    <div class="flex flex-col gap-3 sm:flex-row">
        <form method="POST" action="{{ route('verification.send') }}" class="flex-1">
            @csrf
            <button type="submit" class="inline-flex h-11 w-full items-center justify-center gap-2 rounded-md bg-[#1E3A8A] px-4 text-[11px] font-black uppercase tracking-widest text-white transition hover:bg-blue-800">
                <i class="ph ph-paper-plane-tilt"></i>
                Reenviar email
            </button>
        </form>

        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="inline-flex h-11 w-full items-center justify-center gap-2 rounded-md border border-slate-200 bg-slate-50 px-4 text-[11px] font-black uppercase tracking-widest text-slate-700 transition hover:bg-slate-100 dark:border-white/10 dark:bg-white/5 dark:text-slate-300 dark:hover:bg-white/10 sm:w-auto">
                <i class="ph ph-sign-out"></i>
                Sair
            </button>
        </form>
    </div>
</x-guest-layout>

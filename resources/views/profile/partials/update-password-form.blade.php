<section>
    <header>
        <p class="text-[10px] font-black uppercase tracking-[.18em] text-amber-700 dark:text-amber-300">Segurança</p>
        <h2 class="mt-1 text-xl font-black text-slate-950 dark:text-white font-serif">Atualizar senha</h2>

        <p class="mt-2 text-sm text-slate-500 dark:text-slate-400">
            Certifique-se de que sua conta está usando uma senha longa e aleatória para permanecer segura.
        </p>
    </header>

    <form method="post" action="{{ route('password.update') }}" class="mt-6 space-y-6">
        @csrf
        @method('put')

        <div>
            <x-input-label for="update_password_current_password" :value="'Senha Atual'" />
            <x-text-input id="update_password_current_password" name="current_password" type="password" class="mt-2 block w-full bg-slate-50 text-slate-900 placeholder:text-slate-400 dark:bg-[#0b1120] dark:text-white" autocomplete="current-password" />
            <x-input-error :messages="$errors->updatePassword->get('current_password')" class="mt-2" />
        </div>

        <div>
            <x-input-label for="update_password_password" :value="'Nova Senha'" />
            <x-text-input id="update_password_password" name="password" type="password" class="mt-2 block w-full bg-slate-50 text-slate-900 placeholder:text-slate-400 dark:bg-[#0b1120] dark:text-white" autocomplete="new-password" />
            <x-input-error :messages="$errors->updatePassword->get('password')" class="mt-2" />
        </div>

        <div>
            <x-input-label for="update_password_password_confirmation" :value="'Confirmar Senha'" />
            <x-text-input id="update_password_password_confirmation" name="password_confirmation" type="password" class="mt-2 block w-full bg-slate-50 text-slate-900 placeholder:text-slate-400 dark:bg-[#0b1120] dark:text-white" autocomplete="new-password" />
            <x-input-error :messages="$errors->updatePassword->get('password_confirmation')" class="mt-2" />
        </div>

        <div class="flex items-center gap-4">
            <button type="submit" class="inline-flex h-11 items-center justify-center rounded-md bg-[#1E3A8A] px-5 text-[11px] font-black uppercase tracking-widest text-white transition hover:bg-blue-800 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 dark:focus:ring-offset-[#0d1420]">
                Salvar senha
            </button>

            @if (session('status') === 'password-updated')
                <p
                    x-data="{ show: true }"
                    x-show="show"
                    x-transition
                    x-init="setTimeout(() => show = false, 2000)"
                    class="text-sm font-semibold text-emerald-700 dark:text-emerald-300"
                >Salvo.</p>
            @endif
        </div>
    </form>
</section>

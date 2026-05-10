<x-guest-layout>
    <form method="POST" action="{{ route('register') }}">
        @csrf

        <!-- Nome -->
        <div>
            <x-input-label for="name" :value="'Nome'" />
            <x-text-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name')" required autofocus autocomplete="name" />
        </div>

        <!-- Endereço de e-mail -->
        <div class="mt-4">
            <x-input-label for="email" :value="'E-mail'" />
            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autocomplete="username" />
        </div>

        <!-- Senha -->
        <div class="mt-4">
            <x-input-label for="password" :value="'Senha'" />

            <x-text-input id="password" class="block mt-1 w-full"
                            type="password"
                            name="password"
                            required autocomplete="new-password" />
        </div>

        <!-- Confirmar senha -->
        <div class="mt-4">
            <x-input-label for="password_confirmation" :value="'Confirmar Senha'" />

            <x-text-input id="password_confirmation" class="block mt-1 w-full"
                            type="password"
                            name="password_confirmation" required autocomplete="new-password" />
        </div>

        <div class="flex items-center justify-end mt-4">
            <a class="underline text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-800" href="{{ route('login') }}">
                Já registrado?
            </a>

            <x-primary-button class="ms-4">
                Registrar
            </x-primary-button>
        </div>
    </form>
</x-guest-layout>

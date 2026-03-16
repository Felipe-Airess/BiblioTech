<x-guest-layout>
    
    @if(session('sucesso'))
        <div class="mb-4 font-medium text-sm text-green-600 dark:text-green-400">
            {{ session('sucesso') }}
        </div>
    @endif

    <form method="POST" action="{{ route('bibliotecarios.store') }}">
        @csrf

        @csrf

        <div>
            <x-input-label for="name" value="Nome Completo" />
            
            <x-text-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name')" required autofocus autocomplete="name" />
            
            <x-input-error :messages="$errors->get('name')" class="mt-2" />
        </div>

        <div class="mt-4">
            <x-input-label for="email" value="E-mail de Acesso" />
            
            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autocomplete="username" />
            
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <div class="mt-4">
            <x-input-label for="password" value="Senha Provisória" />

            <x-text-input id="password" class="block mt-1 w-full"
                            type="password"
                            name="password"
                            required autocomplete="new-password" />

            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <div class="mt-4">
            <x-input-label for="password_confirmation" value="Confirmar Senha" />

            <x-text-input id="password_confirmation" class="block mt-1 w-full"
                            type="password"
                            name="password_confirmation" required autocomplete="new-password" />

            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
        </div>

        <div class="flex items-center justify-end mt-4">
            <x-primary-button class="ms-4">
                Cadastrar Bibliotecário
            </x-primary-button>
            <a href="{{ route('dashboard') }}" class="ml-4 bg-gray-600 rounded-md py-2 px-2 text-sm text-gray-600 dark:text-gray-400 hover:bg-gray-900 dark:hover:text-white">
                Voltar ao Dashboard
            </a>
        </div>
    </form>
</x-guest-layout>
<x-guest-layout>
    <div class="py-6">
        <div class="mb-8 text-center">
            <h2 class="font-merriweather text-3xl text-white tracking-tight">
                Novo <span class="text-[#F59E0B]">Bibliotecário</span>
            </h2>
            <div class="mt-2 h-1 w-20 bg-[#F59E0B] mx-auto rounded-full"></div>
        </div>

        @if(session('sucesso'))
            <div class="mb-4 font-inter text-sm text-green-400 bg-green-900/30 p-3 rounded-lg border border-green-500/20">
                {{ session('sucesso') }}
            </div>
        @endif

        <form method="POST" action="{{ route('bibliotecarios.store') }}" class="font-inter space-y-6">
            @csrf

            <div>
                <x-input-label for="name" value="Nome Completo" class="text-gray-300 font-semibold" />
                <x-text-input id="name" class="block mt-1 w-full bg-gray-900 border-gray-700 text-white focus:border-[#1E3A8A] focus:ring-[#1E3A8A]" type="text" name="name" :value="old('name')" required autofocus />
                <x-input-error :messages="$errors->get('name')" class="mt-2" />
            </div>

            <div>
                <x-input-label for="email" value="E-mail de Acesso" class="text-gray-300 font-semibold" />
                <x-text-input id="email" class="block mt-1 w-full bg-gray-900 border-gray-700 text-white focus:border-[#1E3A8A] focus:ring-[#1E3A8A]" type="email" name="email" :value="old('email')" required />
                <x-input-error :messages="$errors->get('email')" class="mt-2" />
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <x-input-label for="password" value="Senha" class="text-gray-300 font-semibold" />
                    <x-text-input id="password" class="block mt-1 w-full bg-gray-900 border-gray-700 text-white focus:border-[#1E3A8A] focus:ring-[#1E3A8A]" type="password" name="password" required />
                </div>

                <div>
                    <x-input-label for="password_confirmation" value="Confirmar" class="text-gray-300 font-semibold" />
                    <x-text-input id="password_confirmation" class="block mt-1 w-full bg-gray-900 border-gray-700 text-white focus:border-[#1E3A8A] focus:ring-[#1E3A8A]" type="password" name="password_confirmation" required />
                </div>
            </div>
            <x-input-error :messages="$errors->get('password')" class="mt-2" />

            <div class="flex flex-col sm:flex-row items-center justify-end gap-4 pt-4">
                <a href="{{ route('dashboard') }}" 
                   class="w-full sm:w-auto text-center px-6 py-3 bg-gray-700 hover:bg-gray-600 border border-gray-500 rounded-xl font-bold text-xs text-white uppercase tracking-widest transition duration-150 shadow-sm">
                    Voltar
                </a>

                <button type="submit" 
                        class="w-full sm:w-auto px-8 py-3 bg-[#1E3A8A] hover:bg-[#2563EB] border border-transparent rounded-xl font-bold text-xs text-white uppercase tracking-widest transition duration-150 shadow-lg shadow-blue-900/20">
                    Cadastrar Bibliotecário
                </button>
            </div>
        </form>
    </div>
</x-guest-layout>
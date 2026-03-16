<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Cadastrar Membro - BiblioTech</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700&display=swap" rel="stylesheet" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-100 dark:bg-gray-900 text-gray-900 dark:text-gray-100 font-sans antialiased min-h-screen flex flex-col items-center justify-center py-10 transition-colors duration-200">

    <div class="w-full max-w-lg px-8 py-8 bg-white dark:bg-gray-800 shadow-xl rounded-xl border border-gray-200 dark:border-gray-700 transition-colors duration-200">
        
        <h2 class="text-2xl font-bold text-center text-blue-900 dark:text-blue-400 mb-6">Novo Membro</h2>

        @if(session('sucesso'))
            <div class="mb-6 font-bold text-sm text-green-700 dark:text-green-400 bg-green-100 dark:bg-green-900/50 border border-green-300 dark:border-green-800 p-3 rounded-md text-center">
                {{ session('sucesso') }}
            </div>
        @endif

        <form method="POST" action="{{ route('membros.store') }}">
            @csrf

            @php
                $inputClasses = "w-full border-gray-300 dark:border-gray-700 text-gray-900 dark:text-gray-300 bg-white dark:bg-gray-900 rounded-md shadow-sm focus:border-blue-900 dark:focus:border-blue-500 focus:ring-blue-900 dark:focus:ring-blue-500 px-3 py-2 border transition-colors duration-200";
                $labelClasses = "block font-bold text-sm text-gray-800 dark:text-gray-300 mb-1";
            @endphp

            <div class="mb-4">
                <label for="nome" class="{{ $labelClasses }}">Nome</label>
                <input id="nome" type="text" name="nome" value="{{ old('nome') }}" required autofocus class="{{ $inputClasses }}">
                @error('nome') <p class="text-sm text-red-600 dark:text-red-400 mt-1 font-semibold">{{ $message }}</p> @enderror
            </div>

            <div class="mb-4">
                <label for="email" class="{{ $labelClasses }}">E-mail de Contato</label>
                <input id="email" type="email" name="email" value="{{ old('email') }}" required class="{{ $inputClasses }}">
                @error('email') <p class="text-sm text-red-600 dark:text-red-400 mt-1 font-semibold">{{ $message }}</p> @enderror
            </div>

            <div class="mb-4">
                <label for="cpf" class="{{ $labelClasses }}">CPF</label>
                <input id="cpf" type="text" name="cpf" value="{{ old('cpf') }}" required class="{{ $inputClasses }}">
                @error('cpf') <p class="text-sm text-red-600 dark:text-red-400 mt-1 font-semibold">{{ $message }}</p> @enderror
            </div>
            
            <div class="mb-4">
                <label for="telefone" class="{{ $labelClasses }}">Telefone</label>
                <input id="telefone" type="text" name="telefone" value="{{ old('telefone') }}" required class="{{ $inputClasses }}">
                @error('telefone') <p class="text-sm text-red-600 dark:text-red-400 mt-1 font-semibold">{{ $message }}</p> @enderror
            </div>
            
            <div class="mb-4">
                <label for="endereco" class="{{ $labelClasses }}">Endereço</label>
                <input id="endereco" type="text" name="endereco" value="{{ old('endereco') }}" required class="{{ $inputClasses }}">
                @error('endereco') <p class="text-sm text-red-600 dark:text-red-400 mt-1 font-semibold">{{ $message }}</p> @enderror
            </div>

            <div class="mb-4">
                <label for="data_nascimento" class="{{ $labelClasses }}">Data de Nascimento</label>
                <input id="data_nascimento" type="date" name="data_nascimento" value="{{ old('data_nascimento') }}" required class="{{ $inputClasses }}">
                @error('data_nascimento') <p class="text-sm text-red-600 dark:text-red-400 mt-1 font-semibold">{{ $message }}</p> @enderror
            </div>

            <div class="mb-4">
                <label for="tipo_membro" class="{{ $labelClasses }}">Tipo de Membro</label>
                <select id="tipo_membro" name="tipo_membro" required class="{{ $inputClasses }}">
                    <option value="">Selecione...</option>
                    <option value="estudante" {{ old('tipo_membro') == 'estudante' ? 'selected' : '' }}>Estudante</option>
                    <option value="professor" {{ old('tipo_membro') == 'professor' ? 'selected' : '' }}>Professor</option>
                    <option value="comum" {{ old('tipo_membro') == 'comum' ? 'selected' : '' }}>Comum</option>
                </select>
                @error('tipo_membro') <p class="text-sm text-red-600 dark:text-red-400 mt-1 font-semibold">{{ $message }}</p> @enderror
            </div>

            <div class="mb-4">
                <label for="numero_carteirinha" class="{{ $labelClasses }}">Número da Carteirinha</label>
                <input id="numero_carteirinha" type="text" name="numero_carteirinha" value="{{ old('numero_carteirinha') }}" required class="{{ $inputClasses }}">
                @error('numero_carteirinha') <p class="text-sm text-red-600 dark:text-red-400 mt-1 font-semibold">{{ $message }}</p> @enderror
            </div>

            <div class="mb-4">
                <label for="password" class="{{ $labelClasses }}">Senha</label>
                <input id="password" type="password" name="password" required class="{{ $inputClasses }}">
                @error('password') <p class="text-sm text-red-600 dark:text-red-400 mt-1 font-semibold">{{ $message }}</p> @enderror
            </div>

            <div class="mb-6">
                <label for="password_confirmation" class="{{ $labelClasses }}">Confirmar Senha</label>
                <input id="password_confirmation" type="password" name="password_confirmation" required class="{{ $inputClasses }}">
            </div>

            <button type="submit" class="w-full py-3 bg-blue-900 dark:bg-blue-700 text-white font-bold rounded-lg hover:bg-blue-800 dark:hover:bg-blue-600 transition shadow-md text-lg">
                Cadastrar Membro
            </button>
            
            <a href="/" class="block mt-4 text-center text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-200 hover:underline transition-colors">
                Voltar
            </a>
        </form>
    </div>
    <script src="https://unpkg.com/imask"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            
            // Máscara para o CPF (000.000.000-00)
            const cpfInput = document.getElementById('cpf');
            if (cpfInput) {
                IMask(cpfInput, {
                    mask: '000.000.000-00'
                });
            }
        });
    </script>
</body>
</html>
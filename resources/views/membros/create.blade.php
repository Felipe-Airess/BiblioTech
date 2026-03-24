<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Cadastrar Membro - BiblioTech</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700,800&display=swap" rel="stylesheet" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased text-gray-900 dark:text-gray-100 min-h-screen bg-gray-50 dark:bg-gray-900 transition-colors duration-200">

    <div class="flex min-h-screen relative">
        
        <div class="hidden lg:flex lg:w-1/2 bg-gradient-to-br from-blue-500 to-blue-900 dark:from-gray-800 dark:to-blue-900 flex-col justify-center items-center p-12 text-white transition-colors duration-200 sticky top-0 h-screen">
            
            <a href="/" class="absolute top-8 left-8 text-sm font-semibold flex items-center gap-2 hover:text-blue-200 transition">
                <span>&lsaquo;</span> Voltar para o Login
            </a>

            <div class="text-center">
                <div class="mb-6 flex justify-center">
                    
                </div>
                <h1 class="text-5xl font-extrabold tracking-tight mb-4 drop-shadow-md">
                    BiblioTech
                </h1>
                <p class="text-2xl font-light text-blue-100 dark:text-gray-300">
                    Junte-se à nossa comunidade!
                </p>
                <div class="mt-8 w-16 h-1 bg-blue-300 opacity-50 mx-auto rounded"></div>
            </div>
            
            
        </div>

        <div class="w-full lg:w-1/2 flex justify-center items-center p-6 sm:p-12 min-h-screen bg-gray-100 dark:bg-gray-900 transition-colors duration-200">
            
            <div class="absolute top-8 left-0 w-full flex justify-center lg:hidden">
                <span class="font-extrabold text-3xl text-blue-900 dark:text-blue-400">📚 BiblioTech</span>
            </div>

            <div class="w-full max-w-xl bg-white dark:bg-gray-800 rounded-2xl shadow-2xl border border-gray-100 dark:border-gray-700 p-8 sm:p-10 mt-12 lg:mt-0 transition-colors duration-200">
                
                <div class="mb-8">
                    <h2 class="text-3xl font-bold text-blue-600 dark:text-blue-400 mb-2">Novo Membro</h2>
                    <p class="text-gray-500 dark:text-gray-400 text-sm">Preencha os dados abaixo para criar sua conta.</p>
                </div>

                @if(session('sucesso'))
                    <div class="mb-6 font-bold text-sm text-green-700 dark:text-green-400 bg-green-100 dark:bg-green-900/50 border border-green-300 dark:border-green-800 p-4 rounded-lg text-center shadow-sm">
                        {{ session('sucesso') }}
                    </div>
                @endif

                <form method="POST" action="{{ route('membros.store') }}">
                    @csrf

                    @php
                        $inputClasses = "w-full border-gray-300 dark:border-gray-600 text-gray-900 dark:text-gray-200 bg-white dark:bg-gray-700 rounded-lg shadow-sm focus:border-blue-500 focus:ring-blue-500 px-4 py-2.5 transition-colors duration-200";
                        $labelClasses = "block font-semibold text-sm text-gray-700 dark:text-gray-300 mb-1";
                    @endphp

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-5 mb-8">
                        
                        <div class="sm:col-span-2">
                            <label for="nome" class="{{ $labelClasses }}">Nome Completo</label>
                            <input id="nome" type="text" name="nome" value="{{ old('nome') }}" required autofocus class="{{ $inputClasses }}">
                            @error('nome') <p class="text-sm text-red-500 mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div class="sm:col-span-2">
                            <label for="email" class="{{ $labelClasses }}">E-mail</label>
                            <input id="email" type="email" name="email" value="{{ old('email') }}" required class="{{ $inputClasses }}">
                            @error('email') <p class="text-sm text-red-500 mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label for="cpf" class="{{ $labelClasses }}">CPF</label>
                            <input id="cpf" type="text" name="cpf" value="{{ old('cpf') }}" required class="{{ $inputClasses }}">
                            @error('cpf') <p class="text-sm text-red-500 mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label for="telefone" class="{{ $labelClasses }}">Telefone</label>
                            <input id="telefone" type="text" name="telefone" value="{{ old('telefone') }}" required class="{{ $inputClasses }}">
                            @error('telefone') <p class="text-sm text-red-500 mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div class="sm:col-span-2">
                            <label for="endereco" class="{{ $labelClasses }}">Endereço Completo</label>
                            <input id="endereco" type="text" name="endereco" value="{{ old('endereco') }}" required class="{{ $inputClasses }}">
                            @error('endereco') <p class="text-sm text-red-500 mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label for="data_nascimento" class="{{ $labelClasses }}">Data de Nascimento</label>
                            <input id="data_nascimento" type="date" name="data_nascimento" value="{{ old('data_nascimento') }}" required class="{{ $inputClasses }}">
                            @error('data_nascimento') <p class="text-sm text-red-500 mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label for="tipo_membro" class="{{ $labelClasses }}">Tipo de Vínculo</label>
                            <select id="tipo_membro" name="tipo_membro" required class="{{ $inputClasses }}">
                                <option value="">Selecione...</option>
                                <option value="estudante" {{ old('tipo_membro') == 'estudante' ? 'selected' : '' }}>Estudante</option>
                                <option value="professor" {{ old('tipo_membro') == 'professor' ? 'selected' : '' }}>Professor</option>
                                <option value="comum" {{ old('tipo_membro') == 'comum' ? 'selected' : '' }}>Comum</option>
                            </select>
                            @error('tipo_membro') <p class="text-sm text-red-500 mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div class="sm:col-span-2">
                            <label for="numero_carteirinha" placeholder="000.000-00" class="{{ $labelClasses }}">Número da Carteirinha</label>
                            <input id="numero_carteirinha" type="text" name="numero_carteirinha" value="{{ old('numero_carteirinha') }}" required class="{{ $inputClasses }}">
                            @error('numero_carteirinha') <p class="text-sm text-red-500 mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label for="password" class="{{ $labelClasses }}">Senha</label>
                            <input id="password" type="password" name="password" required class="{{ $inputClasses }}">
                            @error('password') <p class="text-sm text-red-500 mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label for="password_confirmation" class="{{ $labelClasses }}">Confirmar Senha</label>
                            <input id="password_confirmation" type="password" name="password_confirmation" required class="{{ $inputClasses }}">
                        </div>
                    </div>

                    <div class="flex flex-col sm:flex-row gap-4 mt-6">
                        <button type="submit" class="w-full sm:w-2/3 py-3 bg-blue-500 hover:bg-blue-600 dark:bg-blue-600 dark:hover:bg-blue-700 text-white font-bold rounded-lg transition-colors shadow-md text-center">
                            Concluir Cadastro
                        </button>
                        
                        <a href="/" class="w-full sm:w-1/3 py-3 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 font-bold rounded-lg transition-colors shadow-sm text-center flex items-center justify-center">
                            Cancelar
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="https://unpkg.com/imask"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const cpfInput = document.getElementById('cpf');
            if (cpfInput) {
                IMask(cpfInput, { mask: '000.000.000-00' });
            }

            const telefoneInput = document.getElementById('telefone');
            if (telefoneInput) {
                IMask(telefoneInput, { mask: '(00) 00000-0000' });
            }
        });
       
    document.getElementById('numero_carteirinha').addEventListener('input', function (e) {
        var x = e.target.value.replace(/\D/g, '').match(/(\d{0,3})(\d{0,3})(\d{0,2})/);
        e.target.value = !x[2] ? x[1] : x[1] + '.' + x[2] + (x[3] ? '-' + x[3] : '');
    });

    </script>
</body>
</html>
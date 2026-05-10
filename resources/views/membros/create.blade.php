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
            
            <a href="{{ route('dashboard') }}" class="absolute top-8 left-8 text-sm font-semibold flex items-center gap-2 hover:text-blue-200 transition">
                <span>&lsaquo;</span> Voltar para o Painel
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

                <form method="POST" action="{{ route('membros.store') }}" novalidate>
                    @csrf

                    @php
                        $inputClasses = "w-full border-gray-300 dark:border-gray-600 text-gray-900 dark:text-gray-200 bg-white dark:bg-gray-700 rounded-lg shadow-sm focus:border-blue-500 focus:ring-blue-500 px-4 py-2.5 transition-colors duration-200";
                        $labelClasses = "block font-semibold text-sm text-gray-700 dark:text-gray-300 mb-1";
                    @endphp

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-5 mb-8">
                        
                        <div class="sm:col-span-2">
                            <label for="nome" class="{{ $labelClasses }}">Nome Completo</label>
                            <input id="nome" type="text" name="nome" value="{{ old('nome') }}" required autofocus class="{{ $inputClasses }}">
                        </div>

                        <div class="sm:col-span-2">
                            <label for="email" class="{{ $labelClasses }}">E-mail</label>
                            <input id="email" type="email" name="email" value="{{ old('email') }}" required class="{{ $inputClasses }}">
                        </div>

                        <div>
                            <label for="cpf" class="{{ $labelClasses }}">CPF</label>
                            <input id="cpf" type="text" name="cpf" value="{{ old('cpf') }}" required class="{{ $inputClasses }}">
                        </div>

                        <div>
                            <label for="telefone" class="{{ $labelClasses }}">Telefone</label>
                            <input id="telefone" type="text" name="telefone" value="{{ old('telefone') }}" required class="{{ $inputClasses }}">
                        </div>

                        <div class="sm:col-span-2">
                            <label for="endereco" class="{{ $labelClasses }}">Endereço Completo</label>
                            <input id="endereco" type="text" name="endereco" value="{{ old('endereco') }}" required class="{{ $inputClasses }}">
                        </div>

                        <div>
                            <label for="data_nascimento" class="{{ $labelClasses }}">Data de Nascimento</label>
                            <input id="data_nascimento" type="date" name="data_nascimento" value="{{ old('data_nascimento') }}" required class="{{ $inputClasses }}">
                        </div>

                        <div>
                            <label for="tipo_membro" class="{{ $labelClasses }}">Tipo de Vínculo</label>
                            <select id="tipo_membro" name="tipo_membro" required class="{{ $inputClasses }}">
                                <option value="">Selecione...</option>
                                <option value="estudante" {{ old('tipo_membro') == 'estudante' ? 'selected' : '' }}>Estudante</option>
                                <option value="professor" {{ old('tipo_membro') == 'professor' ? 'selected' : '' }}>Professor</option>
                                <option value="comum" {{ old('tipo_membro') == 'comum' ? 'selected' : '' }}>Comum</option>
                            </select>
                        </div>

                        <div class="sm:col-span-2">
                            <label class="{{ $labelClasses }}">Número da Carteirinha</label>
                            <div class="w-full border border-dashed border-blue-300 dark:border-blue-700 bg-blue-50 dark:bg-blue-900/20 rounded-lg px-4 py-3 text-sm font-semibold text-blue-700 dark:text-blue-300">
                                Será gerada automaticamente ao salvar.
                            </div>
                        </div>

                        <div>
                            <label for="password" class="{{ $labelClasses }}">Senha</label>
                            <input id="password" type="password" name="password" required class="{{ $inputClasses }}">
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
                        
                        <a href="{{ route('dashboard') }}" class="w-full sm:w-1/3 py-3 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 font-bold rounded-lg transition-colors shadow-sm text-center flex items-center justify-center">
                            Voltar para o Painel
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            if (typeof Swal !== 'undefined') {
                const toast = Swal.mixin({
                    toast: true,
                    position: 'top-end',
                    showConfirmButton: false,
                    timer: 5000,
                    timerProgressBar: true,
                    background: '#0d1420',
                    color: '#fff'
                });

                const applyFieldError = (fieldName) => {
                    const safeName = String(fieldName).replace(/"/g, '\\"');
                    const selector = `[name="${safeName}"], [name="${safeName}[]"]`;
                    const field = document.querySelector(selector);
                    if (!field) return;

                    field.setAttribute('aria-invalid', 'true');
                    field.style.borderColor = '#ef4444';
                    field.style.boxShadow = '0 0 0 1px #ef4444';
                };

                const errors = {!! json_encode($errors->getMessages()) !!};
                Object.entries(errors).forEach(([field, messages]) => {
                    applyFieldError(field);
                    (messages || []).forEach((message) => toast.fire({ icon: 'error', title: message }));
                });

                @if(session('sucesso'))
                    toast.fire({ icon: 'success', title: {!! json_encode(session('sucesso')) !!} });
                @endif

                @if(session('error'))
                    toast.fire({ icon: 'error', title: {!! json_encode(session('error')) !!} });
                @endif

                @if($errors->any())
                    @foreach($errors->all() as $error)
                        toast.fire({ icon: 'error', title: {!! json_encode($error) !!} });
                    @endforeach
                @endif
            }

            const cpfInput = document.getElementById('cpf');
            if (cpfInput) {
                cpfInput.addEventListener('input', () => {
                    const digits = cpfInput.value.replace(/\D/g, '').slice(0, 11);
                    const parts = [];

                    if (digits.length > 0) parts.push(digits.slice(0, 3));
                    if (digits.length >= 4) parts.push(digits.slice(3, 6));
                    if (digits.length >= 7) parts.push(digits.slice(6, 9));

                    let formatted = parts.join('.');
                    if (digits.length >= 10) {
                        formatted = `${digits.slice(0, 3)}.${digits.slice(3, 6)}.${digits.slice(6, 9)}-${digits.slice(9, 11)}`;
                    } else if (digits.length >= 7) {
                        formatted = `${digits.slice(0, 3)}.${digits.slice(3, 6)}.${digits.slice(6, 9)}`;
                    } else if (digits.length >= 4) {
                        formatted = `${digits.slice(0, 3)}.${digits.slice(3, 6)}`;
                    } else if (digits.length >= 1) {
                        formatted = digits.slice(0, 3);
                    }

                    cpfInput.value = formatted;
                });
            }

            const telefoneInput = document.getElementById('telefone');
            if (telefoneInput) {
                telefoneInput.addEventListener('input', () => {
                    const digits = telefoneInput.value.replace(/\D/g, '').slice(0, 11);
                    let formatted = '';

                    if (digits.length > 0) {
                        formatted = `(${digits.slice(0, 2)}`;
                    }
                    if (digits.length >= 3) {
                        formatted = `(${digits.slice(0, 2)}) ${digits.slice(2, 7)}`;
                    }
                    if (digits.length >= 8) {
                        formatted = `(${digits.slice(0, 2)}) ${digits.slice(2, 7)}-${digits.slice(7, 11)}`;
                    }

                    telefoneInput.value = formatted;
                });
            }
        });

    </script>
</body>
</html>

<x-guest-layout>
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-12">
        
        <div class="lg:col-span-7 flex flex-col justify-center">
            
            <div class="mb-8 border-b border-gray-700 pb-4">
                <h2 class="text-3xl text-white tracking-tight uppercase font-black" style="font-family: 'Merriweather', serif;">
                    Novo <span class="text-[#F59E0B]">Bibliotecário</span>
                </h2>
                <p class="text-gray-500 text-[10px] font-bold uppercase tracking-[0.2em] mt-1">Cadastro de Acesso Administrativo</p>
            </div>

            @if(session('sucesso'))
                <div class="mb-6 text-sm text-green-400 bg-green-900/30 border border-green-500/30 p-4 rounded-md font-semibold animate-fade-in">
                    {{ session('sucesso') }}
                </div>
            @endif

            <form method="POST" action="{{ route('bibliotecarios.store') }}" class="space-y-6">
                @csrf

                <div class="group">
                    <label for="name" class="block text-xs font-bold text-gray-400 uppercase tracking-wider mb-2 group-focus-within:text-[#F59E0B] transition-colors">Nome Completo</label>
                    <input id="name" type="text" name="name" value="{{ old('name') }}" required autofocus
                        class="block w-full bg-gray-900 border border-gray-700 text-white focus:border-[#F59E0B] focus:ring-1 focus:ring-[#F59E0B] rounded-md shadow-sm transition-all duration-300 hover:border-gray-500" />
                    <x-input-error :messages="$errors->get('name')" class="mt-2" />
                </div>

                <div class="group">
                    <label for="email" class="block text-xs font-bold text-gray-400 uppercase tracking-wider mb-2 group-focus-within:text-[#F59E0B] transition-colors">E-mail de Acesso</label>
                    <input id="email" type="email" name="email" value="{{ old('email') }}" required
                        class="block w-full bg-gray-900 border border-gray-700 text-white focus:border-[#F59E0B] focus:ring-1 focus:ring-[#F59E0B] rounded-md shadow-sm transition-all duration-300 hover:border-gray-500" />
                    <x-input-error :messages="$errors->get('email')" class="mt-2" />
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 pt-2">
                    <div class="group">
                        <label for="password" class="block text-xs font-bold text-gray-400 uppercase tracking-wider mb-2 group-focus-within:text-[#F59E0B] transition-colors">Senha</label>
                        <input id="password" type="password" name="password" required
                            class="block w-full bg-gray-900 border border-gray-700 text-white focus:border-[#F59E0B] focus:ring-1 focus:ring-[#F59E0B] rounded-md shadow-sm transition-all duration-300 hover:border-gray-500" />
                    </div>

                    <div class="group">
                        <label for="password_confirmation" class="block text-xs font-bold text-gray-400 uppercase tracking-wider mb-2 group-focus-within:text-[#F59E0B] transition-colors">Confirmar Senha</label>
                        <input id="password_confirmation" type="password" name="password_confirmation" required
                            class="block w-full bg-gray-900 border border-gray-700 text-white focus:border-[#F59E0B] focus:ring-1 focus:ring-[#F59E0B] rounded-md shadow-sm transition-all duration-300 hover:border-gray-500" />
                    </div>
                </div>
                <x-input-error :messages="$errors->get('password')" class="mt-2" />

                <div class="flex flex-col sm:flex-row items-center justify-end gap-4 pt-8 border-t border-gray-700">
                    <a href="{{ route('dashboard') }}" 
                       class="w-full sm:w-auto text-center px-6 py-3 bg-gray-700 hover:bg-gray-600 border border-gray-600 font-bold text-xs text-white uppercase tracking-wider shadow-sm rounded-md transition-all duration-300">
                        Cancelar
                    </a>

                    <button type="submit" 
                            class="w-full sm:w-auto px-8 py-3 bg-[#1E3A8A] hover:bg-[#2563EB] border border-transparent font-bold text-xs text-white uppercase tracking-wider shadow-md shadow-blue-900/20 rounded-md transition-all duration-300 transform hover:-translate-y-1">
                        Cadastrar Bibliotecário
                    </button>
                </div>
            </form>
        </div>

        <div class="lg:col-span-5 flex flex-col justify-center">
            <div class="sticky top-6">
                <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-4 border-b border-gray-700 pb-2">Pré-visualização do Crachá</label>
                
                <div class="bg-gray-900 border border-gray-700 shadow-xl rounded-md overflow-hidden flex flex-col max-w-sm mx-auto transition-all duration-500 hover:shadow-2xl hover:border-gray-500 hover:-translate-y-2 relative">
                    
                    <div class="h-24 bg-[#1E3A8A] border-b border-[#2563EB]/30 relative overflow-hidden">
                        <div class="absolute inset-0 opacity-20" style="background-image: radial-gradient(circle at 2px 2px, white 1px, transparent 0); background-size: 16px 16px;"></div>
                    </div>
                    
                    <div class="absolute top-4 right-4 bg-green-500 text-white text-[10px] font-black px-2 py-1 uppercase tracking-wider rounded-md shadow-md flex items-center gap-1">
                        <span class="w-1.5 h-1.5 rounded-full bg-white animate-pulse"></span>
                        Ativo
                    </div>

                    <div class="flex justify-center -mt-12 relative z-10">
                        <div class="w-24 h-24 bg-gray-800 rounded-full border-4 border-gray-900 flex items-center justify-center shadow-lg text-gray-500 transition-transform duration-300 hover:scale-105">
                            <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                        </div>
                    </div>

                    <div class="p-6 pt-4 flex-1 flex flex-col text-center">
                        <span class="text-[10px] font-bold text-[#F59E0B] uppercase tracking-widest border border-[#F59E0B]/50 bg-[#F59E0B]/10 px-3 py-1 rounded-full inline-block mx-auto mb-3">Administração</span>
                        
                        <h3 id="prev-name" class="text-xl font-black text-white leading-tight mb-1 truncate" style="font-family: 'Merriweather', serif;">Nome do Usuário</h3>
                        <p id="prev-email" class="text-xs font-semibold text-gray-400 mb-6 truncate">email@biblioteca.com</p>
                        
                        <div class="mt-auto pt-6 border-t border-gray-800 grid grid-cols-2 gap-4">
                            <div class="text-left">
                                <div class="text-[9px] text-gray-500 font-bold uppercase tracking-wider mb-1">Nível de Acesso</div>
                                <div class="text-[11px] font-mono text-gray-300">Bibliotecário</div>
                            </div>
                            <div class="text-right">
                                <div class="text-[9px] text-gray-500 font-bold uppercase tracking-wider mb-1">Sistema</div>
                                <div class="text-[11px] font-mono text-[#F59E0B]">BiblioTech</div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>

    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const syncText = (inputId, prevId, fallback) => {
                const input = document.getElementById(inputId);
                const prev = document.getElementById(prevId);
                if(input && prev) {
                    input.addEventListener('input', e => prev.textContent = e.target.value.trim() || fallback);
                }
            };

            syncText('name', 'prev-name', 'Nome do Usuário');
            syncText('email', 'prev-email', 'email@biblioteca.com');
        });
    </script>
</x-guest-layout>
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            Painel de Controle: Empréstimos
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow-sm border-l-4 border-blue-500">
                    <div class="flex items-center">
                        <div class="p-3 bg-blue-100 dark:bg-blue-900/30 rounded-full">
                            <svg class="h-6 w-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-500 dark:text-gray-400 uppercase">Empréstimos Ativos</p>
                            <p class="text-2xl font-bold text-gray-800 dark:text-white">{{ $emprestimos->where('data_devolucao_real', null)->count() }}</p>
                        </div>
                    </div>
                </div>

                @php
                    $atrasadosCount = $emprestimos->where('data_devolucao_real', null)
                                      ->filter(fn($e) => \Carbon\Carbon::today()->greaterThan($e->data_devolucao_prevista))
                                      ->count();
                @endphp
                <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow-sm border-l-4 border-red-500">
                    <div class="flex items-center">
                        <div class="p-3 bg-red-100 dark:bg-red-900/30 rounded-full">
                            <svg class="h-6 w-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-500 dark:text-gray-400 uppercase">Atrasados</p>
                            <p class="text-2xl font-bold text-red-600">{{ $atrasadosCount }}</p>
                        </div>
                    </div>
                </div>

                <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow-sm border-l-4 border-green-500">
                    <div class="flex items-center">
                        <div class="p-3 bg-green-100 dark:bg-green-900/30 rounded-full">
                            <svg class="h-6 w-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-500 dark:text-gray-400 uppercase">Total em Multas</p>
                            <p class="text-2xl font-bold text-gray-800 dark:text-white text-green-600">
                                R$ {{ number_format($emprestimos->sum('valor_multa'), 2, ',', '.') }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg border border-gray-200 dark:border-gray-700">
                <div class="p-6">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="border-b dark:border-gray-700 bg-gray-50 dark:bg-gray-900 text-gray-700 dark:text-gray-300">
                                <th class="px-4 py-3 text-sm font-bold uppercase">Membro</th>
                                <th class="px-4 py-3 text-sm font-bold uppercase">Livro</th>
                                <th class="px-4 py-3 text-sm font-bold uppercase text-center">Prazo Devolução</th>
                                <th class="px-4 py-3 text-sm font-bold uppercase text-right">Ações</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y dark:divide-gray-700">
                            @forelse($emprestimos as $emprestimo)
                                @php
                                    $atrasado = !$emprestimo->data_devolucao_real && \Carbon\Carbon::today()->greaterThan($emprestimo->data_devolucao_prevista);
                                @endphp
                                <tr class="{{ $atrasado ? 'bg-red-50 dark:bg-red-900/10' : '' }} hover:bg-gray-50 dark:hover:bg-gray-800/50 transition">
                                    <td class="px-4 py-4 dark:text-gray-200">
    @if($emprestimo->membro)
        @if($emprestimo->membro->name)
            {{ $emprestimo->membro->name }}
        @else
            <span class="text-red-500">User não encontrado no ID {{ $emprestimo->membro->user_id }}</span>
        @endif
    @else
        <span class="text-orange-500">Membro não encontrado no ID {{ $emprestimo->membro_id }}</span>
    @endif
</td>
                                    <td class="px-4 py-4 dark:text-gray-300 italic">
                                        {{ $emprestimo->livro->titulo }}
                                    </td>
                                    <td class="px-4 py-4 text-center text-sm {{ $atrasado ? 'text-red-600 font-bold animate-pulse' : 'dark:text-gray-400' }}">
                                        {{ $emprestimo->data_devolucao_prevista->format('d/m/Y') }}
                                    </td>
                                    <td class="px-4 py-4 text-right">
                                        @if(!$emprestimo->data_devolucao_real)
                                            <form action="{{ route('admin.emprestimos.devolver', $emprestimo->id) }}" method="POST">
                                                @csrf
                                                <button type="submit" 
                                                        onclick="confirmarDevolucao(event, this.form)" 
                                                        class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded shadow text-xs font-bold uppercase transition">
                                                    Receber Livro
                                                </button>
                                            </form>
                                        @else
                                            <div class="text-xs text-green-600 font-bold">✓ Concluído</div>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="px-4 py-10 text-center text-gray-500">Nenhum registro de empréstimo.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
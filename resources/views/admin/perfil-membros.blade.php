@extends('layouts.admin')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-slate-900 dark:text-white">Perfil de Membros</h1>
        <p class="mt-2 text-slate-600 dark:text-gray-400">Gerencie e monitore o perfil de todos os membros da biblioteca</p>
    </div>

    {{-- Resumo geral --}}
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-8">
        <div class="p-4 rounded-lg bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800">
            <div class="text-sm font-medium text-blue-700 dark:text-blue-300">Total de Membros</div>
            <div class="text-3xl font-bold text-blue-900 dark:text-blue-100 mt-1">{{ $totalMembros }}</div>
        </div>
        <div class="p-4 rounded-lg bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800">
            <div class="text-sm font-medium text-green-700 dark:text-green-300">Bom Perfil</div>
            <div class="text-3xl font-bold text-green-900 dark:text-green-100 mt-1">{{ count($membrosBom) }}</div>
        </div>
        <div class="p-4 rounded-lg bg-amber-50 dark:bg-amber-900/20 border border-amber-200 dark:border-amber-800">
            <div class="text-sm font-medium text-amber-700 dark:text-amber-300">Devendo</div>
            <div class="text-3xl font-bold text-amber-900 dark:text-amber-100 mt-1">{{ count($membrosDevendo) }}</div>
        </div>
        <div class="p-4 rounded-lg bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800">
            <div class="text-sm font-medium text-red-700 dark:text-red-300">Com Multa</div>
            <div class="text-3xl font-bold text-red-900 dark:text-red-100 mt-1">{{ count($membrosComMulta) }}</div>
        </div>
    </div>

    {{-- Abas de status --}}
    <div class="space-y-8">
        {{-- Membros com Bom Perfil --}}
        <div class="bg-white dark:bg-[#0d1420] rounded-lg border border-slate-200 dark:border-white/10 overflow-hidden">
            <div class="px-6 py-4 bg-green-50 dark:bg-green-900/10 border-b border-slate-200 dark:border-white/10">
                <h2 class="text-lg font-bold text-green-900 dark:text-green-300">✓ Membros com Bom Perfil</h2>
                <p class="text-sm text-green-700 dark:text-green-400 mt-1">Sem atrasos ou dívidas pendentes</p>
            </div>
            <div class="divide-y dark:divide-white/5">
                @forelse($membrosBom as $item)
                    <div class="p-4 hover:bg-slate-50 dark:hover:bg-white/5 transition">
                        <div class="flex items-center justify-between">
                            <div class="flex-1">
                                <h3 class="font-semibold text-slate-900 dark:text-white">{{ $item['membro']->nome }}</h3>
                                <p class="text-sm text-slate-600 dark:text-gray-400 mt-1">
                                    <span class="inline-block">📧 {{ $item['membro']->email }}</span> | 
                                    <span class="inline-block">📞 {{ $item['membro']->telefone }}</span>
                                </p>
                                <p class="text-xs text-slate-500 dark:text-gray-500 mt-2">
                                    Carteirinha: <strong>{{ $item['membro']->numero_carteirinha }}</strong> | 
                                    CPF: <strong>{{ $item['membro']->cpf }}</strong>
                                </p>
                            </div>
                            <div class="text-right ml-4">
                                <div class="text-2xl font-bold text-green-600">{{ $item['emprestimosCompletados'] }}</div>
                                <p class="text-xs text-slate-500 dark:text-gray-500">Empréstimos completos</p>
                                <p class="text-xs text-slate-500 dark:text-gray-500 mt-2">Ativos: {{ $item['emprestimosAtivos'] }}</p>
                                <a href="{{ route('admin.membros.show', $item['membro']->id) }}" class="inline-block mt-3 px-3 py-1 text-xs font-bold bg-green-600 text-white rounded hover:bg-green-700 transition">Ver Detalhes</a>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="p-8 text-center text-slate-500 dark:text-gray-500">
                        Nenhum membro com bom perfil no momento
                    </div>
                @endforelse
            </div>
        </div>

        {{-- Membros Devendo --}}
        <div class="bg-white dark:bg-[#0d1420] rounded-lg border border-slate-200 dark:border-white/10 overflow-hidden">
            <div class="px-6 py-4 bg-amber-50 dark:bg-amber-900/10 border-b border-slate-200 dark:border-white/10">
                <h2 class="text-lg font-bold text-amber-900 dark:text-amber-300">⚠️ Membros Devendo</h2>
                <p class="text-sm text-amber-700 dark:text-amber-400 mt-1">Empréstimos com prazos vencidos não devolvidos</p>
            </div>
            <div class="divide-y dark:divide-white/5">
                @forelse($membrosDevendo as $item)
                    <div class="p-4 hover:bg-slate-50 dark:hover:bg-white/5 transition">
                        <div class="flex items-center justify-between">
                            <div class="flex-1">
                                <h3 class="font-semibold text-slate-900 dark:text-white">{{ $item['membro']->nome }}</h3>
                                <p class="text-sm text-slate-600 dark:text-gray-400 mt-1">
                                    <span class="inline-block">📧 {{ $item['membro']->email }}</span> | 
                                    <span class="inline-block">📞 {{ $item['membro']->telefone }}</span>
                                </p>
                                <p class="text-xs text-slate-500 dark:text-gray-500 mt-2">
                                    Carteirinha: <strong>{{ $item['membro']->numero_carteirinha }}</strong>
                                </p>
                                <div class="mt-3 p-3 bg-amber-50 dark:bg-amber-900/20 rounded border border-amber-200 dark:border-amber-800">
                                    <p class="text-sm font-semibold text-amber-900 dark:text-amber-200">
                                        🔴 {{ count($item['emprestimosAtrasados']) }} empréstimo(s) atrasado(s)
                                    </p>
                                </div>
                            </div>
                            <div class="text-right ml-4">
                                <div class="text-2xl font-bold text-amber-600">{{ count($item['emprestimosAtrasados']) }}</div>
                                <p class="text-xs text-slate-500 dark:text-gray-500">Livros atrasados</p>
                                <p class="text-xs text-slate-500 dark:text-gray-500 mt-2">Ativos: {{ $item['emprestimosAtivos'] }}</p>
                                <a href="{{ route('admin.membros.show', $item['membro']->id) }}" class="inline-block mt-3 px-3 py-1 text-xs font-bold bg-amber-600 text-white rounded hover:bg-amber-700 transition">Ver Detalhes</a>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="p-8 text-center text-slate-500 dark:text-gray-500">
                        Nenhum membro devendo no momento
                    </div>
                @endforelse
            </div>
        </div>

        {{-- Membros com Multa --}}
        <div class="bg-white dark:bg-[#0d1420] rounded-lg border border-slate-200 dark:border-white/10 overflow-hidden">
            <div class="px-6 py-4 bg-red-50 dark:bg-red-900/10 border-b border-slate-200 dark:border-white/10">
                <h2 class="text-lg font-bold text-red-900 dark:text-red-300">🚫 Membros com Multa Pendente</h2>
                <p class="text-sm text-red-700 dark:text-red-400 mt-1">Possuem multas não pagas</p>
            </div>
            <div class="divide-y dark:divide-white/5">
                @forelse($membrosComMulta as $item)
                    <div class="p-4 hover:bg-slate-50 dark:hover:bg-white/5 transition">
                        <div class="flex items-center justify-between">
                            <div class="flex-1">
                                <h3 class="font-semibold text-slate-900 dark:text-white">{{ $item['membro']->nome }}</h3>
                                <p class="text-sm text-slate-600 dark:text-gray-400 mt-1">
                                    <span class="inline-block">📧 {{ $item['membro']->email }}</span> | 
                                    <span class="inline-block">📞 {{ $item['membro']->telefone }}</span>
                                </p>
                                <p class="text-xs text-slate-500 dark:text-gray-500 mt-2">
                                    Carteirinha: <strong>{{ $item['membro']->numero_carteirinha }}</strong>
                                </p>
                                <div class="mt-3 p-3 bg-red-50 dark:bg-red-900/20 rounded border border-red-200 dark:border-red-800">
                                    <p class="text-sm font-semibold text-red-900 dark:text-red-200">
                                        💰 Multa: R$ {{ number_format($item['multasNaoPagas'], 2, ',', '.') }}
                                    </p>
                                </div>
                            </div>
                            <div class="text-right ml-4">
                                <div class="text-2xl font-bold text-red-600">R$ {{ number_format($item['multasNaoPagas'], 2, ',', '.') }}</div>
                                <p class="text-xs text-slate-500 dark:text-gray-500">Total de multa</p>
                                <p class="text-xs text-slate-500 dark:text-gray-500 mt-2">Ativos: {{ $item['emprestimosAtivos'] }}</p>
                                <a href="{{ route('admin.membros.show', $item['membro']->id) }}" class="inline-block mt-3 px-3 py-1 text-xs font-bold bg-red-600 text-white rounded hover:bg-red-700 transition">Ver Detalhes</a>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="p-8 text-center text-slate-500 dark:text-gray-500">
                        Nenhum membro com multa pendente no momento
                    </div>
                @endforelse
            </div>
        </div>
    </div>

</div>
@endsection

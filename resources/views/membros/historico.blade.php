<x-app-layout>
    <x-slot name="header">
        <h2 class="font-bold text-xl text-white uppercase tracking-tight flex items-center gap-2">
            <i class="ph ph-clock-countdown text-2xl text-[#F59E0B]"></i>
            Meus Empréstimos
        </h2>
    </x-slot>

    <div class="max-w-5xl mx-auto py-8 px-4 space-y-4">

        @if(session('sucesso'))
            <div class="text-sm text-green-400 bg-green-900/30 border border-green-500/30 p-4 rounded-md font-semibold">
                {{ session('sucesso') }}
            </div>
        @endif

        @php \Carbon\Carbon::setLocale('pt_BR'); @endphp
        @forelse($emprestimos as $emp)
            @php
                $atrasado = !$emp->data_devolucao_real &&
                    \Carbon\Carbon::today()->greaterThan($emp->data_devolucao_prevista);
                $ativo = !$emp->data_devolucao_real;
            @endphp

            <div class="bg-[#111827] border {{ $atrasado ? 'border-red-500/50' : ($ativo ? 'border-[#1E3A8A]/50' : 'border-gray-800') }} rounded-sm p-5 flex flex-col md:flex-row md:items-center justify-between gap-4">

                <div class="flex gap-4 items-start">
                    {{-- Capa --}}
                    <div class="w-12 h-16 bg-gray-800 rounded-sm overflow-hidden flex-shrink-0 border border-gray-700">
                        @if($emp->livro?->capa)
                            <img src="{{ asset('storage/' . $emp->livro->capa) }}" class="w-full h-full object-cover">
                        @else
                            <div class="w-full h-full flex items-center justify-center">
                                <i class="ph ph-book text-gray-500"></i>
                            </div>
                        @endif
                    </div>

                    <div>
                        <h3 class="text-white font-bold text-sm">{{ $emp->livro?->titulo ?? 'Livro removido' }}</h3>
                        <p class="text-gray-400 text-xs mt-0.5">{{ $emp->livro?->autor?->nome ?? '—' }}</p>

                        <div class="mt-2 flex flex-wrap gap-3 text-[11px] text-gray-500">
                            <span class="flex items-center gap-1">
                                <i class="ph ph-calendar-check"></i>
                                Retirado: {{ $emp->data_emprestimo->format('d/m/Y') }}
                                <span class="ml-1 text-gray-400">({{ $emp->data_emprestimo->diffForHumans() }})</span>
                            </span>
                            <span class="flex items-center gap-1 {{ $atrasado ? 'text-red-400 font-bold' : '' }}">
                                <i class="ph ph-calendar-x"></i>
                                Prazo: {{ $emp->data_devolucao_prevista->format('d/m/Y') }}
                                <span class="ml-1 text-gray-400">({{ $emp->data_devolucao_prevista->diffForHumans() }})</span>
                            </span>
                            @if($emp->data_devolucao_real)
                                <span class="flex items-center gap-1 text-green-400">
                                    <i class="ph ph-check-circle"></i>
                                    Devolvido: {{ $emp->data_devolucao_real->format('d/m/Y') }}
                                    <span class="ml-1 text-gray-400">({{ $emp->data_devolucao_real->diffForHumans() }})</span>
                                </span>
                            @endif
                        </div>

                        @if($emp->valor_multa > 0)
                            <p class="text-red-400 text-xs font-bold mt-1">
                                Multa: R$ {{ number_format($emp->valor_multa, 2, ',', '.') }}
                            </p>
                        @endif
                    </div>
                </div>

                <div class="flex flex-col items-end gap-2 flex-shrink-0">
                    {{-- Badge de status --}}
                    @if($atrasado)
                        <span class="text-[10px] font-black uppercase tracking-wider px-3 py-1 bg-red-500/20 text-red-400 border border-red-500/30 rounded-sm">Atrasado</span>
                    @elseif($ativo)
                        <span class="text-[10px] font-black uppercase tracking-wider px-3 py-1 bg-blue-500/20 text-blue-400 border border-blue-500/30 rounded-sm">Em andamento</span>
                    @else
                        <span class="text-[10px] font-black uppercase tracking-wider px-3 py-1 bg-green-500/20 text-green-400 border border-green-500/30 rounded-sm">Concluído</span>
                    @endif

                    {{-- Botão PDF --}}
                    <a href="{{ route('emprestimos.comprovante', $emp->id) }}"
                       target="_blank"
                       class="text-[11px] font-bold text-gray-400 hover:text-[#F59E0B] transition-colors flex items-center gap-1">
                        <i class="ph ph-file-pdf"></i> Comprovante
                    </a>
                </div>
            </div>
        @empty
            <div class="text-center py-20 text-gray-500">
                <i class="ph ph-books text-5xl mb-4 block"></i>
                Você ainda não realizou nenhum empréstimo.
            </div>
        @endforelse
    </div>
</x-app-layout>
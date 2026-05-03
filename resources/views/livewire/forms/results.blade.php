<div>
    <x-slot name="header">
        <div class="flex items-center gap-4">
            <a href="{{ route('forms.show', $form) }}" class="btn-ghost !p-2" wire:navigate>
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
            </a>
            <div class="flex-1">
                <h2 class="text-xl font-bold text-gray-900">Resultados</h2>
                <p class="text-sm text-gray-500 mt-0.5">{{ $form->title }}</p>
            </div>
            <div class="flex items-center gap-2">
                @if ($form->responses->isNotEmpty())
                    <a href="{{ route('forms.export', $form) }}" class="btn-secondary !text-xs !py-2">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                        Exportar CSV
                    </a>
                @endif
                <a href="{{ route('forms.public', $form->uuid) }}" target="_blank" class="btn-primary !text-xs !py-2">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                    Ver formulario
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            {{-- Tabs --}}
            <div class="flex items-center justify-center mb-8">
                <div class="inline-flex p-1 bg-gray-100 rounded-2xl">
                    <button wire:click="setTab('summary')"
                            class="px-6 py-2 text-sm font-bold rounded-xl transition-all duration-200 {{ $activeTab === 'summary' ? 'bg-white text-brand-600 shadow-sm' : 'text-gray-500 hover:text-gray-700' }}">
                        📊 Resumen
                    </button>
                    <button wire:click="setTab('individual')"
                            class="px-6 py-2 text-sm font-bold rounded-xl transition-all duration-200 {{ $activeTab === 'individual' ? 'bg-white text-brand-600 shadow-sm' : 'text-gray-500 hover:text-gray-700' }}">
                        📋 Individual
                    </button>
                </div>
            </div>

            @if ($form->responses->isEmpty())
                <div class="card animate-scale-in">
                    <div class="empty-state">
                        <span class="text-6xl mb-5">📊</span>
                        <h3 class="text-xl font-bold text-gray-900 mb-2">Sin respuestas aún</h3>
                        <p class="text-gray-500 mb-6 max-w-sm mx-auto">Comparte el enlace del formulario para empezar a recibir respuestas y ver las estadísticas.</p>
                        <div class="flex items-center gap-3">
                            <button @click="navigator.clipboard.writeText('{{ route('forms.public', $form->uuid) }}')" class="btn-secondary">
                                Copiar enlace
                            </button>
                            <a href="{{ route('forms.public', $form->uuid) }}" target="_blank" class="btn-primary">
                                Abrir formulario
                            </a>
                        </div>
                    </div>
                </div>
            @else
                {{-- Stats Bar --}}
                <div class="grid grid-cols-1 sm:grid-cols-4 gap-4 mb-8 animate-slide-up">
                    <div class="stat-card">
                        <div class="stat-icon bg-brand-100 text-brand-600">✉️</div>
                        <div>
                            <p class="text-2xl font-black text-gray-900">{{ $form->responses->count() }}</p>
                            <p class="text-xs font-bold text-gray-400 uppercase tracking-wider">Respuestas</p>
                        </div>
                    </div>
                    <div class="stat-card" style="animation-delay: 0.1s">
                        <div class="stat-icon bg-emerald-100 text-emerald-600">📅</div>
                        <div>
                            <p class="text-lg font-bold text-gray-900">{{ $form->responses->last()->created_at->format('d M') }}</p>
                            <p class="text-xs font-bold text-gray-400 uppercase tracking-wider">Última entrada</p>
                        </div>
                    </div>
                    <div class="stat-card" style="animation-delay: 0.2s">
                        <div class="stat-icon bg-amber-100 text-amber-600">📝</div>
                        <div>
                            <p class="text-2xl font-black text-gray-900">{{ $form->questions->count() }}</p>
                            <p class="text-xs font-bold text-gray-400 uppercase tracking-wider">Preguntas</p>
                        </div>
                    </div>
                    <div class="stat-card" style="animation-delay: 0.3s">
                        <div class="stat-icon bg-indigo-100 text-indigo-600">🚀</div>
                        <div>
                            <p class="text-lg font-bold text-gray-900">{{ $form->is_public ? 'Activo' : 'Privado' }}</p>
                            <p class="text-xs font-bold text-gray-400 uppercase tracking-wider">Estado</p>
                        </div>
                    </div>
                </div>

                @if ($activeTab === 'summary')
                    {{-- Summary View --}}
                    <div class="space-y-6">
                        @foreach ($form->questions as $question)
                            <div class="card animate-slide-up" style="animation-delay: {{ ($loop->index + 1) * 0.1 }}s">
                                <div class="card-body p-8">
                                    <div class="flex items-start gap-4 mb-8">
                                        <span class="w-8 h-8 rounded-xl bg-gray-100 flex items-center justify-center text-xs font-black text-gray-500 border border-gray-200 shadow-sm">
                                            {{ $loop->iteration }}
                                        </span>
                                        <h3 class="text-xl font-bold text-gray-900 leading-tight">
                                            {{ $question->question_text }}
                                        </h3>
                                    </div>

                                    @php
                                        $allAnswers = \App\Models\Answer::where('question_id', $question->id)->get();
                                        $totalAnswers = $allAnswers->count();
                                    @endphp

                                    @if (in_array($question->type, ['radio', 'checkbox', 'select']))
                                        <div class="space-y-5">
                                            @foreach ($question->options as $option)
                                                @php
                                                    $count = $allAnswers->where('option_id', $option->id)->count();
                                                    $percentage = $totalAnswers > 0 ? round(($count / $totalAnswers) * 100) : 0;
                                                @endphp
                                                <div class="relative">
                                                    <div class="flex items-center justify-between mb-2">
                                                        <span class="text-sm font-bold text-gray-700">{{ $option->option_text }}</span>
                                                        <span class="text-xs font-black text-brand-600 bg-brand-50 px-2 py-1 rounded-lg border border-brand-100">
                                                            {{ $count }} ({{ $percentage }}%)
                                                        </span>
                                                    </div>
                                                    <div class="h-3 w-full bg-gray-100 rounded-full overflow-hidden border border-gray-200/50 shadow-inner">
                                                        <div class="h-full bg-brand-500 rounded-full transition-all duration-1000 shadow-[0_0_8px_rgba(79,70,229,0.3)]"
                                                             style="width: {{ $percentage }}%"></div>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    @else
                                        {{-- Text answers list --}}
                                        <div class="space-y-3">
                                            <p class="text-xs font-black text-gray-400 uppercase tracking-widest mb-4">Respuestas recientes</p>
                                            @forelse ($allAnswers->take(10) as $answer)
                                                <div class="p-4 bg-gray-50/50 rounded-2xl border border-gray-100 text-sm text-gray-700 leading-relaxed italic">
                                                    "{{ $answer->answer_text }}"
                                                </div>
                                            @empty
                                                <p class="text-sm text-gray-400 italic">No hay respuestas escritas aún.</p>
                                            @endforelse
                                            
                                            @if ($allAnswers->count() > 10)
                                                <button wire:click="setTab('individual')" class="text-xs font-bold text-brand-600 hover:text-brand-700 mt-2">
                                                    Ver las {{ $allAnswers->count() - 10 }} respuestas restantes &rarr;
                                                </button>
                                            @endif
                                        </div>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    {{-- Individual View (Table) --}}
                    <div class="card overflow-hidden animate-slide-up">
                        <div class="overflow-x-auto scrollbar-thin">
                            <table class="min-w-full divide-y divide-gray-100">
                                <thead>
                                    <tr class="bg-gray-50/50">
                                        <th class="sticky left-0 z-10 bg-gray-50 pl-6 pr-3 py-4 text-left text-xs font-black text-gray-500 uppercase tracking-widest w-16">ID</th>
                                        @foreach ($form->questions as $question)
                                            <th class="px-6 py-4 text-left text-xs font-black text-gray-500 uppercase tracking-widest min-w-[200px]">
                                                {{ $question->question_text }}
                                            </th>
                                        @endforeach
                                        <th class="px-6 py-4 text-left text-xs font-black text-gray-500 uppercase tracking-widest whitespace-nowrap">Fecha de envío</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-100">
                                    @foreach ($form->responses as $response)
                                        <tr class="hover:bg-gray-50/30 transition-colors duration-150">
                                            <td class="sticky left-0 z-10 bg-white pl-6 pr-3 py-5">
                                                <span class="text-xs font-black text-gray-400">#{{ $response->id }}</span>
                                            </td>
                                            @foreach ($form->questions as $question)
                                                <td class="px-6 py-5">
                                                    @php
                                                        $answers = $response->answers->where('question_id', $question->id);
                                                    @endphp
                                                    <div class="flex flex-wrap gap-2">
                                                        @forelse ($answers as $answer)
                                                            @if ($answer->option)
                                                                <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-[10px] font-bold bg-brand-50 text-brand-700 border border-brand-100">
                                                                    {{ $answer->option->option_text }}
                                                                </span>
                                                            @else
                                                                <span class="text-sm text-gray-700 line-clamp-2" title="{{ $answer->answer_text }}">
                                                                    {{ $answer->answer_text }}
                                                                </span>
                                                            @endif
                                                        @empty
                                                            <span class="text-xs text-gray-300 font-medium">Sin respuesta</span>
                                                        @endforelse
                                                    </div>
                                                </td>
                                            @endforeach
                                            <td class="px-6 py-5 whitespace-nowrap">
                                                <span class="text-xs font-bold text-gray-500">{{ $response->created_at->diffForHumans() }}</span>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                @endif
            @endif
        </div>
    </div>
</div>

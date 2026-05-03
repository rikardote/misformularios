<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-xl font-bold text-gray-900 dark:text-white">Panel de Control</h2>
                <p class="text-sm text-gray-500 dark:text-gray-400 mt-0.5">Bienvenido, {{ auth()->user()->name }}</p>
            </div>
            <a href="{{ route('forms.create') }}" class="btn-primary">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                Nuevo formulario
            </a>
        </div>
    </x-slot>

    @php
        $user = auth()->user();
        $formsCount = $user->forms()->count();
        $responsesCount = \App\Models\Response::whereIn('form_id', $user->forms()->pluck('id'))->count();
        $questionsCount = $user->forms()->withCount('questions')->get()->sum('questions_count');
        $recentForms = $user->forms()->latest()->take(5)->withCount(['questions', 'responses'])->get();
    @endphp

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            {{-- Stats --}}
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-5 mb-8">
                <div class="stat-card">
                    <div class="stat-icon bg-brand-100 dark:bg-brand-900/30 text-brand-600 dark:text-brand-400">
                        📋
                    </div>
                    <div>
                        <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ $formsCount }}</p>
                        <p class="text-sm text-gray-500 dark:text-gray-400">Formularios</p>
                    </div>
                </div>
                <div class="stat-card" style="animation-delay: 0.1s">
                    <div class="stat-icon bg-emerald-100 dark:bg-emerald-900/30 text-emerald-600 dark:text-emerald-400">
                        ✉️
                    </div>
                    <div>
                        <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ $responsesCount }}</p>
                        <p class="text-sm text-gray-500 dark:text-gray-400">Respuestas totales</p>
                    </div>
                </div>
                <div class="stat-card" style="animation-delay: 0.2s">
                    <div class="stat-icon bg-amber-100 dark:bg-amber-900/30 text-amber-600 dark:text-amber-400">
                        📝
                    </div>
                    <div>
                        <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ $questionsCount }}</p>
                        <p class="text-sm text-gray-500 dark:text-gray-400">Preguntas creadas</p>
                    </div>
                </div>
            </div>

            {{-- Recent forms --}}
            <div class="card">
                <div class="card-header flex items-center justify-between">
                    <div>
                        <h3 class="section-title">Formularios recientes</h3>
                        <p class="section-subtitle">Tus últimos formularios creados</p>
                    </div>
                    <a href="{{ route('forms.index') }}" class="text-sm text-brand-600 hover:text-brand-700 font-medium">
                        Ver todos &rarr;
                    </a>
                </div>

                @if ($recentForms->isEmpty())
                    <div class="empty-state">
                        <span class="text-5xl mb-4">📋</span>
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-1">No tienes formularios</h3>
                        <p class="text-gray-500 dark:text-gray-400 mb-6 max-w-sm">Crea tu primer formulario y empieza a recolectar respuestas.</p>
                        <a href="{{ route('forms.create') }}" class="btn-primary">Crear primer formulario</a>
                    </div>
                @else
                    <div class="divide-y divide-gray-100 dark:divide-gray-800">
                        @foreach ($recentForms as $form)
                            <div class="px-6 py-4 flex items-center justify-between hover:bg-gray-50/50 dark:hover:bg-gray-800/30 transition-colors duration-150">
                                <div class="flex items-center gap-4 min-w-0">
                                    <div class="flex-shrink-0 w-10 h-10 rounded-xl bg-brand-100 dark:bg-brand-900/30 flex items-center justify-center text-brand-600 dark:text-brand-400 font-bold">
                                        {{ strtoupper(substr($form->title, 0, 2)) }}
                                    </div>
                                    <div class="min-w-0">
                                        <a href="{{ route('forms.show', $form) }}" class="text-sm font-semibold text-gray-900 dark:text-gray-100 hover:text-brand-600 dark:hover:text-brand-400 transition-colors truncate block">
                                            {{ $form->title }}
                                        </a>
                                        <div class="flex items-center gap-3 mt-1">
                                            <span class="text-xs text-gray-400 dark:text-gray-500">
                                                <span class="font-medium text-gray-500 dark:text-gray-400">{{ $form->questions_count }}</span> preguntas
                                            </span>
                                            <span class="text-gray-300 dark:text-gray-700">&middot;</span>
                                            <span class="text-xs text-gray-400 dark:text-gray-500">
                                                <span class="font-medium text-gray-500 dark:text-gray-400">{{ $form->responses_count }}</span> respuestas
                                            </span>
                                            <span class="text-gray-300 dark:text-gray-700">&middot;</span>
                                            @if ($form->is_public)
                                                <span class="badge-success text-[10px]">Público</span>
                                            @else
                                                <span class="badge-danger text-[10px]">Privado</span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                <div class="flex items-center gap-1 flex-shrink-0">
                                    <a href="{{ route('forms.builder', $form) }}" class="btn-ghost !text-xs !py-1.5 !px-3" title="Editar">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                    </a>
                                    <a href="{{ route('forms.public', $form->uuid) }}" target="_blank" class="btn-ghost !text-xs !py-1.5 !px-3" title="Ver">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                                    </a>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>

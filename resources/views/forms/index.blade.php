<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-xl font-bold text-gray-900 dark:text-white">{{ auth()->user()->isAdmin() ? 'Administración de Formularios' : 'Mis Formularios' }}</h2>
                <p class="text-sm text-gray-500 dark:text-gray-400 mt-0.5">{{ auth()->user()->isAdmin() ? 'Gestión global de reactivos' : 'Gestiona todos tus formularios' }}</p>
            </div>
            <a href="{{ route('forms.create') }}" class="btn-primary">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                Crear formulario
            </a>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if (session('status'))
                <div class="mb-6 flex items-center gap-3 p-4 bg-emerald-50 dark:bg-emerald-900/20 border border-emerald-200 dark:border-emerald-800 rounded-2xl animate-slide-up">
                    <span class="text-emerald-500 text-xl">✓</span>
                    <p class="text-sm font-medium text-emerald-700 dark:text-emerald-400">{{ session('status') }}</p>
                </div>
            @endif

            @if ($forms->isEmpty())
                <div class="card">
                    <div class="empty-state">
                        <span class="text-6xl mb-5">📋</span>
                        <h3 class="text-xl font-bold text-gray-900 dark:text-gray-100 mb-2">Sin formularios aun</h3>
                        <p class="text-gray-500 dark:text-gray-400 mb-8 max-w-md">Crea tu primer formulario y empieza a recolectar datos de forma sencilla.</p>
                        <a href="{{ route('forms.create') }}" class="btn-primary">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                            Crear primer formulario
                        </a>
                    </div>
                </div>
            @else
                <div class="card overflow-hidden">
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-100 dark:divide-gray-800">
                            <thead>
                                <tr class="bg-gray-50/50 dark:bg-gray-800/50">
                                    <th class="pl-6 pr-3 py-4 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Formulario</th>
                                    @if(auth()->user()->isAdmin())
                                        <th class="px-3 py-4 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Autor</th>
                                    @endif
                                    <th class="px-3 py-4 text-center text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Preguntas</th>
                                    <th class="px-3 py-4 text-center text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Respuestas</th>
                                    <th class="px-3 py-4 text-center text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Estado</th>
                                    <th class="px-3 py-4 text-center text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Creado</th>
                                    <th class="pl-3 pr-6 py-4 text-right text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Acciones</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100 dark:divide-gray-800">
                                @foreach ($forms as $form)
                                    <tr class="hover:bg-gray-50/30 dark:hover:bg-gray-800/30 transition-colors duration-150">
                                        <td class="pl-6 pr-3 py-4">
                                            <div class="flex items-center gap-3">
                                                <div class="flex-shrink-0 w-10 h-10 rounded-xl bg-brand-100 dark:bg-brand-900/30 flex items-center justify-center text-brand-600 dark:text-brand-400 font-bold text-sm">
                                                    {{ strtoupper(substr($form->title, 0, 2)) }}
                                                </div>
                                                <div class="min-w-0">
                                                    <a href="{{ route('forms.show', $form) }}" class="text-sm font-semibold text-gray-900 dark:text-gray-100 hover:text-brand-600 dark:hover:text-brand-400 transition-colors truncate block max-w-[200px]">
                                                        {{ $form->title }}
                                                    </a>
                                                    @if ($form->description)
                                                        <p class="text-xs text-gray-400 dark:text-gray-500 truncate max-w-[200px] mt-0.5">{{ $form->description }}</p>
                                                    @endif
                                                </div>
                                            </div>
                                        </td>
                                        @if(auth()->user()->isAdmin())
                                            <td class="px-3 py-4">
                                                <div class="flex flex-col">
                                                    <span class="text-sm font-medium text-gray-900 dark:text-gray-100">{{ $form->user->name }}</span>
                                                    <span class="text-xs text-gray-500 dark:text-gray-400 font-mono">{{ $form->user->email }}</span>
                                                </div>
                                            </td>
                                        @endif
                                        <td class="px-3 py-4 text-center">
                                            <span class="inline-flex items-center gap-1 text-sm font-medium text-gray-700 dark:text-gray-300 bg-gray-100 dark:bg-gray-800 rounded-lg px-2.5 py-1">
                                                {{ $form->questions_count ?? $form->questions->count() }}
                                            </span>
                                        </td>
                                        <td class="px-3 py-4 text-center">
                                            <span class="inline-flex items-center gap-1 text-sm font-medium text-gray-700 dark:text-gray-300 bg-gray-100 dark:bg-gray-800 rounded-lg px-2.5 py-1">
                                                {{ $form->responses_count ?? $form->responses->count() }}
                                            </span>
                                        </td>
                                        <td class="px-3 py-4 text-center">
                                            @if ($form->is_public)
                                                <span class="badge-success">Publico</span>
                                            @else
                                                <span class="badge-danger">Privado</span>
                                            @endif
                                        </td>
                                        <td class="px-3 py-4 text-center text-xs text-gray-400 dark:text-gray-500">
                                            {{ $form->created_at->format('d/m/Y') }}
                                        </td>
                                        <td class="pl-3 pr-6 py-4">
                                            <div class="flex items-center justify-end gap-1">
                                                <a href="{{ route('forms.builder', $form) }}" class="btn-ghost !text-xs !py-1.5 !px-3" title="Editar">
                                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                                    <span class="hidden md:inline">Editar</span>
                                                </a>
                                                <a href="{{ route('forms.results', $form) }}" class="btn-ghost !text-xs !py-1.5 !px-3" title="Resultados">
                                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
                                                    <span class="hidden md:inline">Resultados</span>
                                                </a>
                                                @if($form->responses_count > 0 || $form->responses->count() > 0)
                                                    <a href="{{ route('forms.export-pdf', $form) }}" class="btn-ghost !text-xs !py-1.5 !px-3 !text-red-500 hover:!bg-red-50 dark:hover:!bg-red-900/20" title="Exportar PDF">
                                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/></svg>
                                                    </a>
                                                @endif
                                                <a href="{{ route('forms.public', $form->uuid) }}" target="_blank" class="btn-ghost !text-xs !py-1.5 !px-3" title="Ver publico">
                                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                                                </a>
                                                <form action="{{ route('forms.destroy', $form) }}" method="POST" class="inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn-ghost !text-xs !py-1.5 !px-3 !text-red-500 hover:!text-red-700 hover:!bg-red-50" title="Eliminar" onclick="return confirm('Eliminar este formulario?')">
                                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    @if ($forms->hasPages())
                        <div class="card-footer">
                            {{ $forms->links() }}
                        </div>
                    @endif
                </div>
            @endif
        </div>
    </div>
</x-app-layout>

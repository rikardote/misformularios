<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-4">
            <a href="{{ route('forms.index') }}" class="btn-ghost !p-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
            </a>
            <div class="flex-1">
                <h2 class="text-xl font-bold text-gray-900">{{ $form->title }}</h2>
                <p class="text-sm text-gray-500 mt-0.5">
                    {{ $form->questions_count }} preguntas &middot; {{ $form->responses_count }} respuestas
                </p>
            </div>
            <div class="flex items-center gap-2">
                <a href="{{ route('forms.edit', $form) }}" class="btn-secondary !text-xs !py-2">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                    Editar
                </a>
                <a href="{{ route('forms.builder', $form) }}" class="btn-primary !text-xs !py-2">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/></svg>
                    Builder
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                {{-- Main --}}
                <div class="lg:col-span-2 space-y-6">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="section-title">Informacion general</h3>
                        </div>
                        <div class="card-body space-y-4">
                            @if ($form->description)
                                <div>
                                    <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-1">Descripcion</p>
                                    <p class="text-sm text-gray-700">{{ $form->description }}</p>
                                </div>
                            @endif
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-1">UUID</p>
                                    <code class="text-xs bg-gray-100 text-gray-700 px-3 py-1.5 rounded-lg font-mono break-all">{{ $form->uuid }}</code>
                                </div>
                                <div>
                                    <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-1">Estado</p>
                                    @if ($form->is_public)
                                        <span class="badge-success">Publico</span>
                                    @else
                                        <span class="badge-danger">Privado</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card">
                        <div class="card-header flex items-center justify-between">
                            <h3 class="section-title">Preguntas ({{ $form->questions_count }})</h3>
                        </div>
                        @if ($form->questions->isEmpty())
                            <div class="card-body text-center py-8">
                                <p class="text-gray-400 text-sm">Sin preguntas. Usa el Builder para agregarlas.</p>
                            </div>
                        @else
                            <div class="divide-y divide-gray-100">
                                @foreach ($form->questions as $question)
                                    <div class="px-6 py-3.5 flex items-center gap-3">
                                        <span class="flex-shrink-0 w-8 h-8 rounded-lg bg-gray-100 flex items-center justify-center text-xs font-bold text-gray-500">
                                            {{ $loop->iteration }}
                                        </span>
                                        <div class="flex-1 min-w-0">
                                            <p class="text-sm font-medium text-gray-900 truncate">{{ $question->question_text }}</p>
                                            <p class="text-xs text-gray-400">
                                                @php
                                                    $typeLabels = ['text' => 'Texto', 'radio' => 'Opcion multiple', 'checkbox' => 'Checkbox', 'select' => 'Selector'];
                                                @endphp
                                                {{ $typeLabels[$question->type] ?? $question->type }}
                                                @if ($question->is_required) &middot; <span class="text-red-400">Requerido</span> @endif
                                                @if ($question->options->isNotEmpty()) &middot; {{ $question->options->count() }} opciones @endif
                                            </p>
                                        </div>
                                        <span class="badge-neutral text-[10px]">{{ $typeLabels[$question->type] ?? $question->type }}</span>
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </div>
                </div>

                {{-- Sidebar --}}
                <div class="space-y-6">
                    <div class="card">
                        <div class="card-body text-center">
                            <p class="text-3xl font-bold text-gray-900">{{ $form->responses_count }}</p>
                            <p class="text-sm text-gray-500 mt-1">Respuestas totales</p>
                            <a href="{{ route('forms.results', $form) }}" class="btn-primary !text-xs mt-4 w-full justify-center">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
                                Ver resultados
                            </a>
                        </div>
                    </div>

                    <div class="card" x-data="{ copied: false }">
                        <div class="card-body space-y-4">
                            <h3 class="text-sm font-semibold text-gray-700">Enlace público</h3>
                            <div class="flex items-center gap-2 p-3 bg-gray-50 rounded-xl">
                                <code id="public-url" class="text-xs text-gray-600 break-all flex-1">{{ route('forms.public', $form->slug) }}</code>
                            </div>
                            <div class="grid grid-cols-2 gap-2" x-data="{ 
                                copied: false,
                                copyToClipboard() {
                                    const url = '{{ route('forms.public', $form->slug) }}';
                                    if (navigator.clipboard && window.isSecureContext) {
                                        navigator.clipboard.writeText(url).then(() => {
                                            this.copied = true;
                                            setTimeout(() => this.copied = false, 2000);
                                        });
                                    } else {
                                        // Fallback for non-HTTPS
                                        const textArea = document.createElement('textarea');
                                        textArea.value = url;
                                        document.body.appendChild(textArea);
                                        textArea.select();
                                        try {
                                            document.execCommand('copy');
                                            this.copied = true;
                                            setTimeout(() => this.copied = false, 2000);
                                        } catch (err) {
                                            console.error('Error al copiar', err);
                                        }
                                        document.body.removeChild(textArea);
                                    }
                                }
                            }">
                                <button type="button"
                                        @click="copyToClipboard"
                                        class="btn-secondary !text-xs w-full justify-center">
                                    <template x-if="!copied">
                                        <span class="flex items-center gap-1.5">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 5H6a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2v-1M8 5a2 2 0 002 2h2a2 2 0 002-2M8 5a2 2 0 012-2h2a2 2 0 012 2m0 0h2a2 2 0 012 2v3m2 4H10m0 0l3-3m-3 3l3 3"/></svg>
                                            Copiar enlace
                                        </span>
                                    </template>
                                    <template x-if="copied">
                                        <span class="flex items-center gap-1.5 text-emerald-600">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                            ¡Copiado!
                                        </span>
                                    </template>
                                </button>
                                <a href="{{ route('forms.public', $form->uuid) }}" target="_blank" class="btn-secondary !text-xs w-full justify-center">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                                    Abrir
                                </a>
                            </div>
                        </div>
                    </div>

                    <div class="card border-red-100">
                        <div class="card-body">
                            <h3 class="text-sm font-semibold text-red-700 mb-2">Zona de peligro</h3>
                            <p class="text-xs text-gray-500 mb-4">Eliminar este formulario tambien borrara todas las preguntas y respuestas asociadas.</p>
                            <form action="{{ route('forms.destroy', $form) }}" method="POST">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn-danger !text-xs w-full justify-center" 
                                        onclick="return confirm('¿CONFIRMAS LA ELIMINACIÓN? Se perderán todas las preguntas y registros de respuestas de este formulario. Esta acción no se puede deshacer.')">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                    Eliminar formulario
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

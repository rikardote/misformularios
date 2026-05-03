<div>
    <x-slot name="header">
        <div class="flex items-center gap-4">
            <a href="{{ route('forms.show', $form) }}" class="btn-ghost !p-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
            </a>
            <div class="flex-1">
                <h2 class="text-xl font-bold text-gray-900">Builder</h2>
                <p class="text-sm text-gray-500 mt-0.5">{{ $title }}</p>
            </div>
            <button type="button" wire:click="save" wire:loading.attr="disabled" wire:target="save" class="btn-primary">
                <span wire:loading.remove wire:target="save" class="flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4"/></svg>
                    Guardar
                </span>
                <span wire:loading wire:target="save" class="flex items-center gap-2">
                    <svg class="animate-spin w-4 h-4" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"/>
                    </svg>
                    Guardando...
                </span>
            </button>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-6xl mx-auto sm:px-6 lg:px-8">
            @if (session()->has('status'))
                <div class="mb-6 flex items-center gap-3 p-4 bg-emerald-50 border border-emerald-200 rounded-2xl animate-slide-up">
                    <span class="text-emerald-500 text-xl">✓</span>
                    <p class="text-sm font-medium text-emerald-700">{{ session('status') }}</p>
                </div>
            @endif

            {{-- Global validation errors --}}
            @if ($errors->any())
                <div class="mb-6 p-4 bg-red-50 border border-red-200 rounded-2xl animate-slide-up">
                    <div class="flex items-center gap-2 mb-2">
                        <svg class="w-5 h-5 text-red-500" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                        </svg>
                        <p class="text-sm font-semibold text-red-700">Corrige los siguientes errores:</p>
                    </div>
                    <ul class="list-disc list-inside text-sm text-red-600 space-y-1">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            {{-- Form Info Card --}}
            <div class="card mb-6">
                <div class="card-body space-y-4">
                    <div class="grid grid-cols-1 md:grid-cols-5 gap-4 items-end">
                        <div class="md:col-span-3">
                            <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1.5">Título</label>
                            <input type="text" wire:model="title" class="input-field text-base font-semibold @error('title') !border-red-400 !ring-red-200 @enderror" placeholder="Título del formulario">
                            @error('title')
                                <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1.5">Visibilidad</label>
                            <div class="flex items-center gap-2 p-2.5 rounded-xl border border-gray-200 bg-gray-50/50">
                                <input type="checkbox" wire:model="isPublic" id="isPublic" class="rounded-lg border-gray-300 text-brand-600 focus:ring-brand-500">
                                <label for="isPublic" class="text-sm text-gray-700 cursor-pointer select-none">Público</label>
                            </div>
                        </div>
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1.5">Descripción</label>
                        <textarea wire:model="description" rows="2" class="input-field" placeholder="Descripción opcional del formulario..."></textarea>
                    </div>
                </div>
            </div>

            {{-- Questions --}}
            <div class="space-y-4" id="questions-container">
                @foreach ($questions as $index => $question)
                    @php
                        $borderColors = [
                            'input' => 'border-l-indigo-400',
                            'text' => 'border-l-brand-400',
                            'radio' => 'border-l-amber-400',
                            'checkbox' => 'border-l-emerald-400',
                            'select' => 'border-l-blue-400',
                        ];
                        $borderClass = $borderColors[$question['type']] ?? 'border-l-brand-400';
                        $hasError = $errors->has("questions.{$index}.question_text") || $errors->has("questions.{$index}.options");
                    @endphp

                    <div class="card border-l-4 {{ $borderClass }} {{ $hasError ? 'ring-2 ring-red-200' : '' }} animate-scale-in" wire:key="question-{{ $index }}">
                        <div class="card-body">
                            <div class="flex items-start justify-between mb-4">
                                <div class="flex items-center gap-2">
                                    <span class="w-7 h-7 rounded-lg bg-gray-100 flex items-center justify-center text-xs font-bold text-gray-500">
                                        {{ $index + 1 }}
                                    </span>
                                    <span class="text-sm font-semibold text-gray-700">Pregunta {{ $index + 1 }}</span>
                                </div>
                                <button type="button"
                                        wire:click="removeQuestion({{ $index }})"
                                        wire:confirm="¿Eliminar esta pregunta? Esta acción no se puede deshacer."
                                        class="btn-ghost !text-xs !text-red-500 hover:!text-red-700 hover:!bg-red-50 !py-1.5 !px-3">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                    Eliminar
                                </button>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-4 gap-3 items-start">
                                <div class="md:col-span-3">
                                    <input type="text" wire:model="questions.{{ $index }}.question_text" class="input-field @error("questions.{$index}.question_text") !border-red-400 !ring-red-200 @enderror" placeholder="Escribe la pregunta...">
                                    @error("questions.{$index}.question_text")
                                        <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                                <div>
                                    <select wire:model.live="questions.{{ $index }}.type" class="input-field">
                                        <option value="input">✏️ Texto Corto</option>
                                        <option value="text">📝 Texto Largo</option>
                                        <option value="radio">🔘 Opción múltiple</option>
                                        <option value="checkbox">✅ Checkbox</option>
                                        <option value="select">📋 Selector</option>
                                    </select>
                                </div>
                            </div>

                            <div class="mt-3 flex items-center gap-4">
                                <label class="flex items-center gap-2 cursor-pointer select-none">
                                    <input type="checkbox" wire:model="questions.{{ $index }}.is_required" class="rounded-lg border-gray-300 text-brand-600 focus:ring-brand-500">
                                    <span class="text-xs text-gray-500">Requerido</span>
                                </label>
                            </div>

                            {{-- Options --}}
                            @if (in_array($question['type'], ['radio', 'checkbox', 'select']))
                                <div class="mt-4 pt-4 border-t border-gray-100">
                                    <div class="flex items-center gap-2 mb-3">
                                        <span class="text-xs font-semibold text-gray-400 uppercase tracking-wider flex items-center gap-1">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16"/></svg>
                                            Opciones
                                        </span>
                                        <span class="badge-neutral text-[10px]">{{ count($question['options']) }}</span>
                                    </div>
                                    @error("questions.{$index}.options")
                                        <p class="text-xs text-red-500 mb-2">{{ $message }}</p>
                                    @enderror
                                    <div class="space-y-2">
                                        @foreach ($question['options'] as $optIndex => $option)
                                            <div class="flex items-center gap-2 animate-scale-in" wire:key="option-{{ $index }}-{{ $optIndex }}">
                                                <span class="flex-shrink-0 w-6 h-6 rounded-full bg-gray-100 flex items-center justify-center text-[10px] text-gray-400 font-bold">
                                                    {{ $optIndex + 1 }}
                                                </span>
                                                <input type="text" wire:model="questions.{{ $index }}.options.{{ $optIndex }}.option_text" class="input-field flex-1" placeholder="Texto de la opción">
                                                <button type="button" wire:click="removeOption({{ $index }}, {{ $optIndex }})" class="flex-shrink-0 w-8 h-8 flex items-center justify-center rounded-lg text-gray-400 hover:text-red-500 hover:bg-red-50 transition-colors">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                                                </button>
                                            </div>
                                        @endforeach
                                    </div>
                                    <button type="button" wire:click="addOption({{ $index }})" class="btn-ghost !text-brand-600 hover:!text-brand-700 !text-xs mt-3">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                                        Agregar opción
                                    </button>
                                </div>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>

            {{-- Empty state --}}
            @if (empty($questions))
                <div class="card">
                    <div class="empty-state">
                        <span class="text-5xl mb-4">📝</span>
                        <h3 class="text-lg font-semibold text-gray-900 mb-2">Sin preguntas</h3>
                        <p class="text-gray-500 mb-6">Agrega preguntas para construir tu formulario.</p>
                        <button type="button" wire:click="addQuestion" class="btn-primary">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                            Agregar primera pregunta
                        </button>
                    </div>
                </div>
            @endif

            {{-- Add question button --}}
            @if (!empty($questions))
                <div class="mt-4 text-center">
                    <button type="button" wire:click="addQuestion" class="btn-secondary animate-slide-up">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                        Agregar pregunta
                    </button>
                </div>
            @endif

            {{-- Bottom action bar --}}
            <div class="mt-8 pb-8 flex items-center justify-between">
                <a href="{{ route('forms.show', $form) }}" class="btn-ghost">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                    Volver
                </a>
                <button type="button" wire:click="save" wire:loading.attr="disabled" wire:target="save" class="btn-primary">
                    <span wire:loading.remove wire:target="save" class="flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4"/></svg>
                        Guardar formulario
                    </span>
                    <span wire:loading wire:target="save" class="flex items-center gap-2">
                        <svg class="animate-spin w-4 h-4" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"/>
                        </svg>
                        Guardando...
                    </span>
                </button>
            </div>
        </div>
    </div>
</div>

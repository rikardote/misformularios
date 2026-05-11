<div class="pb-20">
    {{-- Floating Progress --}}
    @if ($formAvailable && !$successMessage)
        @php
            $totalQuestions = $form->questions->count();
            $answeredCount = collect($answers)->filter(fn($v) => !empty($v))->count();
            $progress = $totalQuestions > 0 ? ($answeredCount / $totalQuestions) * 100 : 0;
            $radius = 40;
            $circumference = 2 * pi() * $radius;
            $offset = $circumference - ($progress / 100) * $circumference;
        @endphp
        <div class="progress-container">
            <div class="progress-bar-fill" style="width: {{ $progress }}%"></div>
        </div>

        <div class="floating-progress animate-scale-in">
            <svg class="absolute inset-0 w-full h-full -rotate-90">
                <circle cx="48" cy="48" r="{{ $radius }}" stroke="currentColor" stroke-width="6" fill="transparent" class="text-gray-100" />
                <circle cx="48" cy="48" r="{{ $radius }}" stroke="currentColor" stroke-width="6" fill="transparent" 
                        class="text-brand-500 transition-all duration-700 ease-out"
                        style="stroke-dasharray: {{ $circumference }}; stroke-dashoffset: {{ $offset }}; stroke-linecap: round;" />
            </svg>
            <span class="floating-progress-percent">{{ round($progress) }}%</span>
            <span class="floating-progress-label">Listo</span>
        </div>
    @endif

    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 pt-8 sm:pt-12 text-center">
        <img src="{{ asset('60issste.png') }}" alt="Logo" class="h-20 w-auto mx-auto mb-8">
    </div>

    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
        @if (!$formAvailable)
            {{-- Form not available --}}
            <div class="card animate-scale-in">
                <div class="form-accent-bar bg-gray-400 !from-gray-400 !to-gray-600"></div>
                <div class="p-10 sm:p-16 text-center">
                    <div class="inline-flex items-center justify-center w-24 h-24 rounded-3xl bg-gray-100 mb-6 text-5xl">
                        🔒
                    </div>
                    <h2 class="text-3xl font-extrabold text-gray-900 mb-3">Formulario no disponible</h2>
                    <p class="text-lg text-gray-500 max-w-md mx-auto leading-relaxed">Este formulario no está disponible
                        actualmente. Puede que sea privado o que el enlace sea incorrecto.</p>
                    <a href="/" class="btn-primary mt-10 px-8 py-4 text-base" wire:navigate>
                        Volver al inicio
                    </a>
                </div>
            </div>
        @elseif ($successMessage)
            {{-- Success --}}
            <div class="card animate-scale-in">
                <div class="form-accent-bar !from-emerald-400 !to-emerald-600"></div>
                <div class="p-10 sm:p-20 text-center">
                    <div
                        class="inline-flex items-center justify-center w-28 h-28 rounded-full bg-emerald-50 mb-8 animate-bounce shadow-inner">
                        <svg class="w-16 h-16 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7" />
                        </svg>
                    </div>
                    <h2 class="text-4xl font-black text-gray-900 mb-4 tracking-tight">{{ $successMessage }}</h2>
                    <p class="text-xl text-gray-500 font-medium">¡Gracias por tu participación! Tus respuestas han sido registradas.</p>
                    
                    @if($verificationCode)
                        <div class="mt-8 p-6 bg-brand-50 border-2 border-brand-100 rounded-3xl animate-slide-up">
                            <p class="text-sm font-bold text-brand-600 uppercase tracking-widest mb-2">Código de Verificación</p>
                            <div class="flex items-center justify-center gap-3">
                                <span class="text-3xl font-black text-brand-900 tracking-widest font-mono">{{ $verificationCode }}</span>
                            </div>
                            <p class="text-xs text-brand-400 mt-2">Guarda este código para confirmar tu participación.</p>
                        </div>
                    @endif
                    <div class="mt-12 pt-10 border-t border-gray-100">
                        <p class="text-sm text-gray-400 mb-6">¿Quieres crear tu propio formulario?</p>
                        <a href="{{ route('register') }}" class="btn-primary px-8 py-4" wire:navigate>
                            Comenzar
                        </a>
                    </div>
                </div>
            </div>
        @else
            {{-- Header --}}
            <div class="card mb-8 animate-slide-up">
                <div class="form-accent-bar"></div>
                <div class="px-8 py-10 sm:px-12 sm:py-12">
                    <h1 class="text-3xl sm:text-4xl font-black text-gray-900 tracking-tight leading-tight">
                        {{ $form->title }}</h1>
                    @if ($form->description)
                        <div class="mt-4 prose prose-indigo max-w-none">
                            <p class="text-lg text-gray-500 leading-relaxed">{{ $form->description }}</p>
                        </div>
                    @endif
                    <div
                        class="mt-8 flex items-center gap-2 text-sm font-semibold text-gray-400 border-t border-gray-100 pt-6">
                        <span class="flex-shrink-0 w-2 h-2 rounded-full bg-emerald-500"></span>
                        Aceptando respuestas
                        <span class="mx-2 text-gray-200">|</span>
                        <span>{{ $form->questions->count() }} preguntas en total</span>
                    </div>
                </div>
            </div>

            <form wire:submit="submit" class="space-y-8">
                {{-- Honeypot --}}
                <div class="absolute" style="left: -9999px; opacity: 0; height: 0; width: 0; overflow: hidden;"
                    aria-hidden="true" tabindex="-1">
                    <label for="website_hp">Website</label>
                    <input type="text" wire:model="website" id="website_hp" name="website" autocomplete="off" tabindex="-1">
                </div>

                @foreach ($form->questions as $question)
                    <div class="card relative animate-slide-up" style="animation-delay: {{ ($loop->index + 1) * 0.1 }}s">
                        <div class="question-focus-indicator"></div>
                        <div class="px-8 py-10 sm:px-10 sm:py-10">
                            <div class="flex items-start gap-6">
                                <span
                                    class="flex-shrink-0 mt-0.5 w-10 h-10 rounded-2xl bg-indigo-50 flex items-center justify-center text-sm font-bold text-indigo-600 shadow-sm border border-indigo-100">
                                    {{ $loop->iteration }}
                                </span>
                                <div class="flex-1 min-w-0">
                                    <p class="text-xl sm:text-2xl font-bold text-gray-900 mb-8 leading-tight">
                                        {{ $question->question_text }}
                                        @if ($question->is_required)
                                            <span class="text-red-500 ml-1" title="Requerido">*</span>
                                        @endif
                                    </p>

                                    @if ($question->type === 'input')
                                        <div class="relative group">
                                            <input type="text" wire:model.live="answers.question_{{ $question->id }}"
                                                class="block w-full rounded-2xl border-2 border-gray-100 bg-gray-50/30 px-6 py-5 text-lg
                                                                         focus:border-brand-500 focus:ring-4 focus:ring-brand-500/5 focus:bg-white
                                                                         transition-all duration-300 placeholder:text-gray-400" placeholder="Escribe tu respuesta corta aquí...">
                                            <div
                                                class="absolute inset-y-0 right-6 flex items-center text-xs font-bold text-gray-300 pointer-events-none group-focus-within:text-brand-400">
                                                CORTO</div>
                                        </div>

                                    @elseif ($question->type === 'email')
                                        <div class="relative group">
                                            <input type="email" wire:model.live="answers.question_{{ $question->id }}"
                                                class="block w-full rounded-2xl border-2 border-gray-100 bg-gray-50/30 px-6 py-5 text-lg
                                                                         focus:border-brand-500 focus:ring-4 focus:ring-brand-500/5 focus:bg-white
                                                                         transition-all duration-300 placeholder:text-gray-400" placeholder="ejemplo@correo.com">
                                            <div
                                                class="absolute inset-y-0 right-6 flex items-center text-xs font-bold text-gray-300 pointer-events-none group-focus-within:text-brand-400">
                                                EMAIL</div>
                                        </div>
                                    @elseif ($question->type === 'text')
                                        <div class="relative group">
                                            <textarea wire:model.live="answers.question_{{ $question->id }}" rows="4"
                                                class="block w-full rounded-2xl border-2 border-gray-100 bg-gray-50/30 px-6 py-5 text-lg
                                                                         focus:border-brand-500 focus:ring-4 focus:ring-brand-500/5 focus:bg-white
                                                                         transition-all duration-300 placeholder:text-gray-400 resize-y" placeholder="Escribe tu respuesta larga aquí..."></textarea>
                                            <div
                                                class="absolute bottom-4 right-4 text-xs font-bold text-gray-300 pointer-events-none group-focus-within:text-brand-400">
                                                LARGO</div>
                                        </div>

                                    @elseif ($question->type === 'radio')
                                        <div class="grid grid-cols-1 gap-4">
                                            @foreach ($question->options as $option)
                                                @php $selected = (int) ($answers['question_' . $question->id] ?? 0) === $option->id; @endphp
                                                <div wire:click="$set('answers.question_{{ $question->id }}', {{ $option->id }})"
                                                    class="option-tile {{ $selected ? 'option-tile-on' : 'option-tile-off' }}">
                                                    <div
                                                        class="w-6 h-6 rounded-full border-2 flex items-center justify-center flex-shrink-0 transition-all duration-300 {{ $selected ? 'border-brand-500 bg-brand-500 shadow-[0_0_10px_rgba(79,70,229,0.4)]' : 'border-gray-300 bg-white' }}">
                                                        <div
                                                            class="w-2.5 h-2.5 rounded-full bg-white transition-transform duration-300 {{ $selected ? 'scale-100' : 'scale-0' }}">
                                                        </div>
                                                    </div>
                                                    <span class="text-lg font-semibold select-none">{{ $option->option_text }}</span>
                                                </div>
                                            @endforeach
                                        </div>

                                    @elseif ($question->type === 'checkbox')
                                        <div class="grid grid-cols-1 gap-4">
                                            @foreach ($question->options as $option)
                                                @php $checked = in_array($option->id, $answers['question_' . $question->id] ?? []); @endphp
                                                <div wire:click="toggleCheckbox({{ $question->id }}, {{ $option->id }})"
                                                    class="option-tile {{ $checked ? 'option-tile-on' : 'option-tile-off' }}">
                                                    <div
                                                        class="w-6 h-6 rounded-lg border-2 flex items-center justify-center flex-shrink-0 transition-all duration-300 {{ $checked ? 'bg-brand-500 border-brand-500 shadow-[0_0_10px_rgba(79,70,229,0.4)]' : 'border-gray-300 bg-white' }}">
                                                        <svg class="w-4 h-4 text-white transition-opacity duration-300 {{ $checked ? 'opacity-100' : 'opacity-0' }}"
                                                            fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3"
                                                                d="M5 13l4 4L19 7" />
                                                        </svg>
                                                    </div>
                                                    <span class="text-lg font-semibold select-none">{{ $option->option_text }}</span>
                                                </div>
                                            @endforeach
                                        </div>

                                    @elseif ($question->type === 'select')
                                        <div class="relative">
                                            <select wire:model.live="answers.question_{{ $question->id }}" class="block w-full rounded-2xl border-2 border-gray-100 bg-gray-50/30 px-6 py-5 text-lg appearance-none
                                                                       focus:border-brand-500 focus:ring-4 focus:ring-brand-500/5 focus:bg-white
                                                                       transition-all duration-300">
                                                <option value="">Seleccionar una opción...</option>
                                                @foreach ($question->options as $option)
                                                    <option value="{{ $option->id }}">{{ $option->option_text }}</option>
                                                @endforeach
                                            </select>
                                            <div
                                                class="absolute inset-y-0 right-6 flex items-center pointer-events-none text-gray-400">
                                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M19 9l-7 7-7-7" />
                                                </svg>
                                            </div>
                                        </div>
                                    @endif

                                    @error("answers.question_{$question->id}")
                                        <div
                                            class="mt-6 flex items-center gap-3 p-4 bg-red-50 text-red-700 rounded-2xl border border-red-100 animate-slide-up">
                                            <svg class="w-5 h-5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd"
                                                    d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z"
                                                    clip-rule="evenodd" />
                                            </svg>
                                            <span class="text-sm font-bold">{{ $message }}</span>
                                        </div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach

                <div class="flex flex-col items-center gap-6 pt-12">
                    <button type="submit" class="w-full sm:max-w-md inline-flex items-center justify-center gap-4 px-10 py-6
                                       bg-brand-600 hover:bg-brand-700 active:bg-brand-800
                                       text-white font-black text-xl tracking-wide rounded-2xl
                                       shadow-[0_20px_50px_rgba(79,70,229,0.3)] hover:shadow-[0_20px_50px_rgba(79,70,229,0.5)]
                                       active:scale-[0.98] focus:outline-none focus:ring-4 focus:ring-brand-400/20
                                       transition-all duration-300" wire:loading.attr="disabled">
                        <span wire:loading.remove wire:target="submit" class="flex items-center gap-4">
                            <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                    d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            Enviar formulario
                        </span>
                        <span wire:loading wire:target="submit" class="flex items-center gap-4">
                            <svg class="animate-spin w-7 h-7" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4" />
                                <path class="opacity-75" fill="currentColor"
                                    d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z" />
                            </svg>
                            Procesando envío...
                        </span>
                    </button>

                    @error('submit')
                        <div class="mt-4 p-4 bg-red-50 border border-red-200 rounded-2xl text-red-700 text-sm font-bold animate-shake">
                            ⚠️ {{ $message }}
                        </div>
                    @enderror

                    <p class="text-xs font-bold text-gray-300 uppercase tracking-widest">Tus respuestas se guardan de forma
                        segura</p>
                </div>
            </form>
        @endif


    </div>
</div>
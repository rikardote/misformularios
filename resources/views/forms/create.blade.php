<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-4">
            <a href="{{ route('forms.index') }}" class="btn-ghost !p-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
            </a>
            <div>
                <h2 class="text-xl font-bold text-gray-900">Crear Formulario</h2>
                <p class="text-sm text-gray-500 mt-0.5">Define el titulo y descripcion</p>
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="card">
                <div class="card-body">
                    <form action="{{ route('forms.store') }}" method="POST">
                        @csrf
                        <div class="space-y-5">
                            <div>
                                <x-input-label for="title" :value="__('Titulo')" class="!text-sm !font-semibold !text-gray-700 !mb-2" />
                                <x-text-input id="title" class="input-field" type="text" name="title" :value="old('title')" placeholder="Ej: Encuesta de satisfaccion" required autofocus />
                                <x-input-error :messages="$errors->get('title')" class="mt-2" />
                            </div>

                            <div>
                                <x-input-label for="description" :value="__('Descripcion (opcional)')" class="!text-sm !font-semibold !text-gray-700 !mb-2" />
                                <textarea id="description" name="description" rows="3" class="input-field" placeholder="Describe el proposito de este formulario...">{{ old('description') }}</textarea>
                            </div>
                        </div>

                        <div class="flex items-center justify-end gap-3 mt-8 pt-6 border-t border-gray-100">
                            <a href="{{ route('forms.index') }}" class="btn-secondary">Cancelar</a>
                            <button type="submit" class="btn-primary">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                                Crear y continuar
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

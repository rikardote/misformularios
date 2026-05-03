<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-xl font-bold text-gray-900">Gestión de Usuarios</h2>
                <p class="text-sm text-gray-500 mt-0.5">Control total de acceso al sistema</p>
            </div>
            <a href="{{ route('users.create') }}" class="btn-primary">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/></svg>
                Nuevo Usuario
            </a>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if (session('status'))
                <div class="mb-6 flex items-center gap-3 p-4 bg-emerald-50 border border-emerald-200 rounded-2xl">
                    <span class="text-emerald-500">✓</span>
                    <p class="text-sm font-medium text-emerald-700">{{ session('status') }}</p>
                </div>
            @endif

            <div class="card overflow-hidden">
                <table class="min-w-full divide-y divide-gray-100">
                    <thead>
                        <tr class="bg-gray-50/50">
                            <th class="pl-6 pr-3 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Usuario</th>
                            <th class="px-3 py-4 text-center text-xs font-semibold text-gray-500 uppercase tracking-wider">Rol</th>
                            <th class="px-3 py-4 text-center text-xs font-semibold text-gray-500 uppercase tracking-wider">Formularios</th>
                            <th class="px-3 py-4 text-center text-xs font-semibold text-gray-500 uppercase tracking-wider">Registro</th>
                            <th class="pl-3 pr-6 py-4 text-right text-xs font-semibold text-gray-500 uppercase tracking-wider">Acciones</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @foreach ($users as $user)
                            <tr class="hover:bg-gray-50/30 transition-colors duration-150">
                                <td class="pl-6 pr-3 py-4">
                                    <div class="flex items-center gap-3">
                                        <div class="w-9 h-9 rounded-full bg-brand-600 flex items-center justify-center text-white text-xs font-bold">
                                            {{ strtoupper(substr($user->name, 0, 1)) }}
                                        </div>
                                        <div>
                                            <p class="text-sm font-semibold text-gray-900">{{ $user->name }}</p>
                                            <p class="text-xs text-gray-400">{{ $user->email }}</p>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-3 py-4 text-center">
                                    @if ($user->isAdmin())
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-indigo-100 text-indigo-700 border border-indigo-200">
                                            🛡️ Administrador
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-600">
                                            Usuario
                                        </span>
                                    @endif
                                </td>
                                <td class="px-3 py-4 text-center">
                                    <span class="text-sm font-bold text-gray-700">{{ $user->forms_count }}</span>
                                </td>
                                <td class="px-3 py-4 text-center text-xs text-gray-400">
                                    {{ $user->created_at->format('d/m/Y') }}
                                </td>
                                <td class="pl-3 pr-6 py-4 text-right">
                                    <div class="flex justify-end gap-2">
                                        <a href="{{ route('users.edit', $user) }}" class="btn-ghost !py-1 !px-3 !text-xs">Editar</a>
                                        @if ($user->id !== auth()->id())
                                            <form action="{{ route('users.destroy', $user) }}" method="POST" class="inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn-ghost !py-1 !px-3 !text-xs !text-red-500 hover:!bg-red-50" onclick="return confirm('¿Eliminar usuario?')">Borrar</button>
                                            </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                <div class="p-4">
                    {{ $users->links() }}
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

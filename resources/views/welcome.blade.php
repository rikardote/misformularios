<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>{{ config('app.name', 'Formularios') }}</title>

        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700,800&display=swap" rel="stylesheet" />

        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased bg-gradient-to-br from-brand-50 via-white to-indigo-50">
        <div class="min-h-screen">
            {{-- Nav --}}
            <nav class="relative z-10 border-b border-gray-100/50 bg-white/80 backdrop-blur-md">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div class="flex justify-between items-center h-16">
                        <a href="/" class="flex items-center gap-3">
                            <img src="{{ asset('60issste.png') }}" alt="Logo" class="h-10 w-auto">
                            <span class="text-xl font-extrabold text-gray-900 tracking-tight">Formularios</span>
                        </a>
                        <div class="flex items-center gap-3">
                            @if (Route::has('login'))
                                @auth
                                    <a href="{{ route('dashboard') }}" class="btn-primary text-sm">Dashboard</a>
                                @else
                                    <a href="{{ route('login') }}" class="btn-ghost text-sm">Iniciar sesion</a>
                                    <a href="{{ route('register') }}" class="btn-primary text-sm">Crear cuenta</a>
                                @endauth
                            @endif
                        </div>
                    </div>
                </div>
            </nav>

            {{-- Hero --}}
            <section class="relative overflow-hidden">
                <div class="absolute inset-0 bg-gradient-to-br from-brand-600 via-brand-700 to-indigo-800"></div>
                <div class="absolute inset-0 bg-[url('data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iNjAiIGhlaWdodD0iNjAiIHZpZXdCb3g9IjAgMCA2MCA2MCIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIj48ZyBmaWxsPSJub25lIiBmaWxsLXJ1bGU9ImV2ZW5vZGQiPjxnIGZpbGw9IiNmZmZmZmYiIGZpbGwtb3BhY2l0eT0iMC4wNSI+PGNpcmNsZSBjeD0iMzAiIGN5PSIzMCIgcj0iMiIvPjwvZz48L2c+PC9zdmc+')] opacity-50"></div>
                <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-24 sm:py-32 lg:py-40">
                    <div class="max-w-3xl animate-slide-up">
                        <h1 class="text-4xl sm:text-5xl lg:text-6xl font-extrabold text-white tracking-tight leading-tight">
                            Crea formularios
                            <span class="block text-brand-200">de forma sencilla</span>
                        </h1>
                        <p class="mt-6 text-lg sm:text-xl text-brand-100/90 leading-relaxed max-w-2xl">
                            Diseña, comparte y analiza formularios dinámicos en segundos.
                            Una herramienta potente con una experiencia intuitiva.
                        </p>
                        <div class="mt-10 flex flex-wrap gap-4">
                            @auth
                                <a href="{{ route('dashboard') }}" class="btn-secondary !bg-white !text-brand-700 !border-white hover:!bg-brand-50 text-base px-8 py-3">
                                    Ir al Dashboard
                                </a>
                            @else
                                <a href="{{ route('register') }}" class="btn-secondary !bg-white !text-brand-700 !border-white hover:!bg-brand-50 text-base px-8 py-3">
                                    Comenzar gratis
                                </a>
                                <a href="{{ route('login') }}" class="inline-flex items-center gap-2 px-8 py-3 rounded-xl font-semibold text-base text-white border-2 border-white/30 hover:bg-white/10 transition-all duration-200">
                                    Iniciar sesion
                                </a>
                            @endauth
                        </div>
                    </div>
                </div>
                <div class="absolute bottom-0 inset-x-0 h-16 bg-gradient-to-t from-brand-50/10 to-transparent"></div>
            </section>

            {{-- Features --}}
            <section class="py-20 sm:py-28">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div class="text-center mb-16">
                        <h2 class="text-3xl sm:text-4xl font-extrabold text-gray-900 tracking-tight">
                            Todo lo que necesitas
                        </h2>
                        <p class="mt-4 text-lg text-gray-500 max-w-2xl mx-auto">
                            Herramientas potentes para crear formularios profesionales sin complicaciones.
                        </p>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                        <div class="card p-8 text-center animate-slide-up" style="animation-delay: 0.1s">
                            <div class="w-14 h-14 bg-brand-100 rounded-2xl flex items-center justify-center mx-auto mb-5 text-2xl">
                                🎨
                            </div>
                            <h3 class="text-lg font-bold text-gray-900 mb-2">Builder visual</h3>
                            <p class="text-gray-500 text-sm leading-relaxed">
                                Arrastra, edita y organiza preguntas con una interfaz visual intuitiva.
                            </p>
                        </div>

                        <div class="card p-8 text-center animate-slide-up" style="animation-delay: 0.2s">
                            <div class="w-14 h-14 bg-emerald-100 rounded-2xl flex items-center justify-center mx-auto mb-5 text-2xl">
                                🔗
                            </div>
                            <h3 class="text-lg font-bold text-gray-900 mb-2">Comparte al instante</h3>
                            <p class="text-gray-500 text-sm leading-relaxed">
                                Cada formulario tiene un enlace unico que puedes compartir con quien quieras.
                            </p>
                        </div>

                        <div class="card p-8 text-center animate-slide-up" style="animation-delay: 0.3s">
                            <div class="w-14 h-14 bg-amber-100 rounded-2xl flex items-center justify-center mx-auto mb-5 text-2xl">
                                📊
                            </div>
                            <h3 class="text-lg font-bold text-gray-900 mb-2">Analiza resultados</h3>
                            <p class="text-gray-500 text-sm leading-relaxed">
                                Visualiza todas las respuestas en tiempo real con tablas organizadas.
                            </p>
                        </div>
                    </div>
                </div>
            </section>

            {{-- CTA --}}
            <section class="py-20 bg-gradient-to-br from-brand-600 to-brand-800">
                <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
                    <h2 class="text-3xl sm:text-4xl font-extrabold text-white tracking-tight">
                        Empieza a crear formularios hoy
                    </h2>
                    <p class="mt-4 text-lg text-brand-100/80 max-w-xl mx-auto">
                        Sin configuraciones complicadas. Registrate y crea tu primer formulario en minutos.
                    </p>
                    <div class="mt-10">
                        @auth
                            <a href="{{ route('forms.create') }}" class="btn-secondary !bg-white !text-brand-700 !border-white hover:!bg-brand-50 text-base px-10 py-3.5">
                                Crear formulario
                            </a>
                        @else
                            <a href="{{ route('register') }}" class="btn-secondary !bg-white !text-brand-700 !border-white hover:!bg-brand-50 text-base px-10 py-3.5">
                                Registrarse gratis
                            </a>
                        @endauth
                    </div>
                </div>
            </section>

            {{-- Footer --}}
            <footer class="bg-white border-t border-gray-100 py-10">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
                    <p class="text-sm text-gray-400">
                        &copy; {{ date('Y') }} Formularios. Todos los derechos reservados.
                    </p>
                </div>
            </footer>
        </div>
    </body>
</html>

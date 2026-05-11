<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ $form->title ?? config('app.name', 'Formularios') }}</title>


        @vite(['resources/css/app.css', 'resources/js/app.js'])
        @livewireStyles

        <script>
            // Force light mode on this layout
            document.documentElement.classList.remove('dark');
        </script>
    </head>
    <body class="font-sans text-gray-900 antialiased bg-gray-50/50 transition-colors duration-300 min-h-screen flex flex-col">
        {{ $slot }}

        <footer class="py-12 mt-auto border-t border-gray-100/50">
            <div class="max-w-5xl mx-auto px-4 text-center">
                <p class="text-sm text-gray-400 font-medium tracking-wide">
                    &copy; {{ date('Y') }}
                    Creado por Hector Fuentes para ISSSTE Baja California
                </p>
            </div>
        </footer>

        @livewireScripts
    </body>
</html>

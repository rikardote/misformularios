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
    <body class="font-sans text-gray-900 antialiased bg-gray-50/50 transition-colors duration-300">
        {{ $slot }}

        @livewireScripts
    </body>
</html>

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ $form->title ?? config('app.name', 'Formularios') }}</title>

        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700,800&display=swap" rel="stylesheet" />

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

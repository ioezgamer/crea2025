@can('ver-home')
    {{-- Layout de invitado para páginas como login y registro --}}
    @include('layouts.guest')

@endcan
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'SistemaCREA') }}</title>

    {{-- Fonts y CSS --}}
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    {{-- Script para modo oscuro --}}
    <script>
        if (localStorage.getItem('themePreference') === 'dark' || (!('themePreference' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
            document.documentElement.classList.add('dark')
        } else {
            document.documentElement.classList.remove('dark')
        }
    </script>
</head>
<body class="font-sans antialiased text-gray-900 bg-gray-50 dark:bg-slate-900">
    {{-- Contenedor principal para centrar el contenido --}}
    <div class="flex items-center justify-center min-h-screen px-4 py-8 bg-white sm:px-6 lg:px-8">
        <div class="w-full max-w-lg">
            {{-- Slot para el contenido (formulario de login o registro) --}}
            {{ $slot }}
        </div>
    </div>
</body>
</html>

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
<body class="font-sans antialiased text-gray-900">
    <div class="min-h-screen bg-slate-100 dark:bg-slate-900">
        {{-- Contenedor principal que se convierte en grid en pantallas grandes --}}
        <div class="grid w-full min-h-screen grid-cols-1 lg:grid-cols-2">

            {{-- Columna Izquierda: Branding (visible solo en LG y más grandes) --}}
            @include('layouts.partials.auth-branding')

            {{-- Columna Derecha: Contenido del Formulario (ocupa toda la pantalla en móvil) --}}
            <div class="flex flex-col items-center justify-center w-full min-h-screen px-4 py-8 bg-gradient-to-br from-slate-50 via-purple-50 to-pink-50 dark:from-slate-800 dark:via-purple-900 dark:to-pink-900">
                {{-- Logo para vista móvil (oculto en LG y más grandes) --}}
                <div class="mb-6 lg:hidden">
                    <a href="/">
                        <x-application-logo class="w-20 h-20 text-indigo-500 fill-current" />
                    </a>
                </div>

                {{-- El slot donde se insertará el formulario de login o registro --}}
                {{ $slot }}
            </div>

        </div>
    </div>
</body>
</html>

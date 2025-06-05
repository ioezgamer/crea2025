{{-- resources/views/layouts/app.blade.php --}}
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class=""> {{-- La clase 'dark' se gestiona por JS --}}
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'SistemaCREA') }}</title>

    {{-- Fonts --}}
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

    {{-- Scripts de Vite (CSS principal) --}}
    @vite(['resources/css/app.css'])

    {{-- Script para inicializar el modo oscuro --}}
    <script>
        function applyTheme() {
            const themePreference = localStorage.getItem('themePreference');
            const prefersDarkScheme = window.matchMedia('(prefers-color-scheme: dark)').matches;
            if (themePreference === 'dark' || (themePreference === 'system' && prefersDarkScheme) || (!themePreference && prefersDarkScheme)) {
                document.documentElement.classList.add('dark');
            } else {
                document.documentElement.classList.remove('dark');
            }
        }
        applyTheme(); // Aplicar inmediatamente

        window.matchMedia('(prefers-color-scheme: dark)').addEventListener('change', event => {
            const currentPreference = localStorage.getItem('themePreference');
            if (currentPreference === 'system' || !currentPreference) {
                applyTheme();
                // Actualizar estado de Alpine.js si existe (para el botón de toggle de tema)
                const alpineComponent = document.querySelector('[x-data*="currentTheme"]');
                if (alpineComponent && alpineComponent.__x) {
                    alpineComponent.__x.$data.currentTheme = alpineComponent.__x.$data.getInitialTheme(); // Re-evaluar
                }
            }
        });
    </script>
</head>
<body class="font-sans antialiased text-gray-800 transition-colors duration-300 bg-gradient-to-br from-slate-50 via-purple-50 to-pink-50 dark:from-slate-800 dark:via-purple-900 dark:to-pink-900 dark:text-slate-200">
    <div class="flex flex-col min-h-screen">

        @include('layouts.navigation')

        {{-- Header Estándar y Consistente --}}
        @isset($header)
        <header class="sticky z-30 dark:from-slate-800 dark:via-purple-900 dark:to-pink-900 backdrop-blur-md ">
            <div class="flex flex-col w-full px-4 py-2 mx-auto gap-y-1 sm:flex-row sm:items-center sm:justify-between max-w-7xl sm:px-6 lg:px-8 sm:py-1">
                {{-- El contenido que definas en el slot 'header' de tus vistas se insertará aquí --}}
                {{ $header }}
            </div>
        </header>
        @endisset

        {{-- Contenido Principal --}}
        <main class="flex-grow">
            {{ $slot }}
        </main>

        @include('layouts.footer')
    </div>

    {{-- Alpine.js desde CDN --}}
    <script src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    {{-- Scripts específicos de página pusheados aquí --}}
    @stack('scripts')
</body>
</html>

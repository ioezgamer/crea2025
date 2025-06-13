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

    {{-- SweetAlert2 CSS --}}
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">

    {{-- Scripts de Vite (CSS principal) --}}
    @vite(['resources/css/app.css', 'resources/js/app.js']) {{-- Asegúrate de que app.js se compile aquí --}}

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
                const alpineComponent = document.querySelector('[x-data*="currentTheme"]');
                if (alpineComponent && alpineComponent.__x) {
                    alpineComponent.__x.$data.currentTheme = alpineComponent.__x.$data.getInitialTheme();
                }
            }
        });
    </script>
</head>
<body class="font-sans antialiased text-gray-800 transition-colors duration-300 bg-gradient-to-br from-slate-50 via-purple-50 to-pink-50 dark:from-slate-800 dark:via-purple-900 dark:to-pink-900 dark:text-slate-200">
    <div class="flex flex-col min-h-screen">

        @include('layouts.navigation')

        @isset($header)
        <header class="sticky top-0 z-40 shadow-sm bg-white/70 dark:bg-slate-800/80 backdrop-blur-md">
            <div class="flex flex-col w-full px-4 py-2 mx-auto gap-y-1 sm:flex-row sm:items-center sm:justify-between max-w-7xl sm:px-6 lg:px-8 sm:py-1">
                {{ $header }}
            </div>
        </header>
        @endisset

        <main class="flex-grow">
            {{ $slot }}
        </main>

        @include('layouts.footer')
    </div>

    {{-- Contenedor global para toasts --}}
    <div id="global_toast_container" class="fixed top-20 right-5 z-[100] w-full max-w-xs sm:max-w-sm space-y-3">
        {{-- Los toasts se añadirán aquí dinámicamente por notifications.js --}}
    </div>

    {{-- Div para pasar mensajes de sesión de Laravel a JavaScript --}}
    {{-- Este div será leído por resources/js/app.js para mostrar los toasts --}}
    <div id="sessionMessages" class="hidden"
         @if (session('success')) data-success-message="{{ session('success') }}" @endif
         @if (session('error')) data-error-message="{{ session('error') }}" @endif
         @if (session('warning')) data-warning-message="{{ session('warning') }}" @endif
         @if (session('info')) data-info-message="{{ session('info') }}" @endif
    ></div>

   
    {{-- SweetAlert2 JS --}}
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    {{-- Scripts específicos de página pusheados aquí --}}
    @stack('scripts')
    {{-- El @vite ya carga app.js, que debería manejar lógica global y de inicialización. --}}
</body>
</html>

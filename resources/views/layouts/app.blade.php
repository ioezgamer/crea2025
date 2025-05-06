<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Tu Proyecto') }}</title>

    <!-- Fuentes -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Estilos -->
    @vite(['resources/css/app.css'])
</head>
<body class="font-sans antialiased">
    <div class="min-h-screen bg-transparent">
        @include('layouts.navigation')
            {{-- Encabezado de Página --}}
            @isset($header)
                <header>
                    <div class="max-w-24 mx-auto py-3 px-2 sm:px-6 lg:px-8">
                        {{ $header }}
                    </div>
                </header>
            @endisset

            {{-- Contenido --}}
            <main class="p-4">
                {{ $slot }}
            </main>

            {{-- Pie de Página --}}
            <footer class="bg-white border-t border-gray-200 p-4 text-center text-gray-700 shadow-inner">
                {{-- Pie de Página 
                <div class="flex justify-center space-x-4">
                    <a href="#" class="text-gray-500 hover:text-gray-700 transition-colors duration-200">Acerca de</a>
                    <a href="#" class="text-gray-500 hover:text-gray-700 transition-colors duration-200">Soporte</a>
                    <a href="#" class="text-gray-500 hover:text-gray-700 transition-colors duration-200">Política de Privacidad</a>
                </div>
                --}}
                <p class="mt-2 text-sm">© {{ date('Y') }} SistemaCREA. Todos los derechos reservados.</p>
            </footer>
        </main>
    </div>

    <!-- Alpine.js -->
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
</body>
</html>

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name') }}</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://cdn.tailwindcss.com"></script>

</head>
<body class="text-gray-900 bg-gradient-to-r from-indigo-600 via-purple-600 to-pink-500">
    {{-- Contenedor principal de la página: Altura de pantalla completa, layout de columna flex --}}
    <div class="flex flex-col h-screen">

        <!-- Sección del Logo -->
        <header class="p-3 text-center shrink-0 sm:p-4"> {{-- Padding reducido ligeramente --}}
            <a href="/" class="inline-block">
                <x-application-logo class="text-gray-100 fill-current w-14 h-14 sm:w-16 sm:h-16" />
            </a>
        </header>

        <!-- Contenedor del Slot Principal (Tarjeta de Login): Crece para tomar el espacio restante, centra el contenido, y permite scroll interno si es necesario -->
        <main class="flex flex-col items-center justify-center flex-grow w-full p-2 overflow-y-hidden sm:p-3 md:p-4 ">
            {{ $slot }} {{-- Aquí se inserta la tarjeta de login --}}
        </main>

        <!-- Sección del Pie de Página -->
        @include('layouts.footer') {{-- Incluye el pie de página con derechos de autor y otra información relevante --}}
    </div>
</body>
</html>

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'SistemaCREA') }}</title>

    <!-- Fuentes -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Estilos -->
    @vite(['resources/css/app.css'])
</head>
<body class="font-sans antialiased">
    <div class="min-h-screen bg-gray-100">
        @include('layouts.navigation')

       <!-- Page Heading -->
@isset($header)
<header class="bg-white shadow">
    <div class="max-w-auto mx-auto py-6 px-4 sm:px-6 lg:px-8 text-center dark:bg-gray-800 dark:text-white">
        {{ $header }}
    </div>
</header>
@endisset

        <!-- Page Content -->
        <main>
            {{ $slot }}
        </main>

        {{-- Pie de Página --}}
        <footer class="bg-white border-t border-gray-200 p-6 text-center text-gray-700 shadow-inner">
            <p class="text-sm">© {{ date('Y') }} SistemaCREA. Todos los derechos reservados.</p>
        </footer>
    </div>

    <!-- Alpine.js -->
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
</body>
</html>
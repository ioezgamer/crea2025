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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

    <!-- Estilos -->
    @vite(['resources/css/app.css'])
</head>
<body class="font-sans antialiased">
     <div class="min-h-screen flex flex-col bg-gradient-to-br from-slate-50 via-purple-50 to-pink-50">
        @include('layouts.navigation')

       <!-- Page Heading -->
@isset($header)
    <header class="bg-transparent">
        <div class="max-w-screen mx-auto p-2 sm:px-6 lg:px-8 text-center ">
            {{ $header }}
        </div>
    </header>
@endisset

        <!-- Page Content -->
        <main>
            {{ $slot }}
        </main>

        {{-- Pie de Página --}}
        <footer class="  p-4 text-center text-gray-700 ">
            <p class="text-sm">© {{ date('Y') }} SistemaCREA. Todos los derechos reservados.</p>
        </footer>
    </div>

    <!-- Alpine.js -->
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
</body>
</html>
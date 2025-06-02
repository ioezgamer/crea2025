<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'SistemaCREA') }}</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

    @vite(['resources/css/app.css'])
</head>
<body class="font-sans antialiased">
     <div class="flex flex-col min-h-screen bg-gradient-to-br from-slate-50 via-purple-50 to-pink-50">
        @include('layouts.navigation')

        @isset($header)
            <header class="bg-transparent">
                {{-- Adjusted max-width for better consistency and responsive padding --}}
                <div class="px-4 py-4 mx-auto text-center max-w-7xl sm:px-6 lg:px-8">
                    {{ $header }}
                </div>
            </header>
        @endisset

        <main class="flex-grow"> {{-- Added flex-grow to ensure main content takes available space --}}
            {{ $slot }}
        </main>

         {{-- Pie de Página --}}
        @include('layouts.footer')
    </div>

    <script src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
</body>
</html>

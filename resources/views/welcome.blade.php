<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Sistema CREA - Bienvenido</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600,700&display=swap" rel="stylesheet" />
    <script src="https://cdn.tailwindcss.com"></script>
    <style type="text/tailwindcss">
        @layer utilities {
          .text-balance {
            text-wrap: balance;
          }
        }
        /* Additional custom font setup if needed, but Instrument Sans is loaded via Bunny Fonts */
        body {
            font-family: 'Instrument Sans', ui-sans-serif, system-ui, sans-serif, "Apple Color Emoji", "Segoe UI Emoji", "Segoe UI Symbol", "Noto Color Emoji";
        }
    </style>

    {{-- This section conditionally loads CSS/JS via Vite or uses inline Tailwind CSS as a fallback --}}
    {{-- Ensure your Tailwind config is set up for JIT mode to correctly process arbitrary classes like before: pseudo-elements --}}
    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @else
        {{-- Fallback inline Tailwind CSS (v4.0.7) - This is a large block, ensure it's present for fallback --}}
        {{-- The user's original extensive Tailwind v4.0.7 fallback CSS would be here.
             For brevity in this example, it's omitted, but it should be included if Vite isn't used.
             The cdn.tailwindcss.com script above provides Tailwind v3.x.
             If the original v4.0.7 fallback is critical, it should be re-inserted here.
             However, for modern development, relying on the CDN or Vite is preferred.
        --}}
    @endif
</head>
<body class="flex flex-col items-center justify-center min-h-screen p-4 antialiased bg-gradient-to-tr from-white to-blue-100/15 sm:p-6 lg:p-8">

    {{-- Logo visible en pantallas grandes --}}

    <div class="w-full max-w-lg p-6 text-center transition-all duration-500 ease-in-out transform border border-gray-200 shadow-2xl sm:max-w-xl sm:p-10 rounded-3xl hover:shadow-xl bg-gradient-to-br from-indigo-100/25 via-cyan-200/25 to-blue-300/45 ">

        <div class="flex items-center justify-center w-16 h-16 p-1 mx-auto mb-5 rounded-full shadow-lg sm:h-20 sm:w-20 bg-gradient-to-br from-indigo-500 to-purple-600">
            <div class="flex items-center justify-center w-full h-full bg-white rounded-full">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-8 h-8 text-indigo-600 sm:h-10 sm:w-10" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z" />
                </svg>
            </div>
        </div>

        <div class="mb-6 sm:mb-8">
            <h1 class="text-4xl font-bold tracking-tight text-transparent sm:text-5xl bg-clip-text bg-gradient-to-r from-indigo-600 via-purple-600 to-pink-500 text-balance">
                Sistema CREA
            </h1>
        </div>

        <h2 class="mb-3 text-xl font-semibold leading-tight sm:text-2xl text-slate-700 sm:mb-4 text-balance">
            Gestión de Participantes y Asistencias
        </h2>
        <p class="mb-8 text-sm sm:text-base text-slate-500 sm:mb-10 text-balance">
            Sistema de Registro y Control de Asistencias para los Programas de CREA.
        </p>

        @if (Route::has('login'))
        <nav class="flex flex-col items-center justify-center gap-3 sm:flex-row sm:gap-4">
            @auth
                <a href="{{ url('/dashboard') }}"
                   class="relative flex items-center justify-center w-full px-6 py-3 text-base font-semibold text-white transition-all duration-300 ease-in-out border-2 border-transparent rounded-full shadow-md group sm:w-auto bg-gradient-to-r from-indigo-600 to-purple-600 hover:shadow-lg hover:scale-105 focus:outline-none focus:ring-2 focus:ring-purple-500 focus:ring-offset-2">
                    <span>Ir al Dashboard</span>
                    <svg class="w-5 h-5 ml-2 transition-transform duration-300 transform group-hover:translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"></path></svg>
                </a>
            @else
                {{-- Botón Iniciar Sesión --}}
                <a href="{{ route('login') }}" class="w-full sm:w-auto">
                    <button class="relative flex items-center justify-center w-full px-6 py-3 text-base font-semibold text-white transition-all duration-300 ease-in-out border-2 border-transparent rounded-full shadow-md group bg-gradient-to-r from-indigo-500 to-purple-500 hover:shadow-lg hover:from-indigo-600 hover:to-purple-600 hover:scale-105 focus:outline-none focus:ring-2 focus:ring-purple-500 focus:ring-offset-2" aria-label="Iniciar Sesión">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 mr-2 transition-transform duration-300 group-hover:scale-110" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h5a3 3 0 013 3v1" />
                        </svg>
                        <span>Iniciar Sesión</span>
                    </button>
                </a>

                @if (Route::has('register'))
                    {{-- Botón Registrarse --}}
                    <a href="{{ route('register') }}" class="w-full sm:w-auto">
                        <button class="relative flex items-center justify-center w-full px-6 py-3 text-base font-semibold text-indigo-600 transition-all duration-300 ease-in-out bg-white border-2 border-indigo-500 rounded-full shadow-sm group hover:shadow-md hover:bg-indigo-50 hover:border-indigo-600 hover:scale-105 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-1" aria-label="Registrarse">
                             <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 mr-2 transition-transform duration-300 group-hover:scale-110" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2.25-4.125a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zM4 19.235v-.11a6.375 6.375 0 0112.75 0v.109A12.318 12.318 0 0110.374 21c-2.331 0-4.512-.645-6.374-1.766z" />
                            </svg>
                            <span>Registrarse</span>
                        </button>
                    </a>
                @endif
            @endauth
        </nav>
        @endif
    </div>

    <footer class="mt-8 text-center sm:mt-12">
        <p class="text-xs sm:text-sm text-slate-600">
            &copy; {{ date('Y') }} {{ config('app.name', 'Sistema CREA') }}. Todos los derechos reservados.
        </p>
    </footer>

</body>
</html>

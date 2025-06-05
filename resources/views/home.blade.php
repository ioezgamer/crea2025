<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between w-full px-4 py-6 mx-auto max-w-7xl sm:px-6 lg:px-8">
        {{-- El texto del header ahora tiene una variante dark --}}
                <h1 class="text-2xl font-bold text-transparent lg:text-3xl bg-clip-text bg-gradient-to-r from-indigo-600 via-purple-600 to-pink-500">

            {{ __('Home') }}
                </h1>
        </div>
    </x-slot>

    {{-- Main content area: el degradado de fondo puede necesitar ajuste o que el card interior sea opaco en modo oscuro --}}
    {{-- Para este ejemplo, el degradado se mantiene y el card interior se hace más opaco en modo oscuro --}}
    <div class="py-12 bg-gradient-to-br from-slate-50 via-purple-50 to-pink-50 dark:from-slate-800 dark:via-purple-900 dark:to-pink-900 min-h-[calc(80vh-2rem)] flex items-center justify-center px-4 sm:px-6 lg:px-8">
        <div class="w-full max-w-4xl mx-auto text-center">
            {{-- Main content card: adaptado para modo oscuro --}}
            <div class="p-6 transition-transform ease-in transform shadow-lg bg-white/80 dark:bg-slate-800/90 backdrop-blur-xl rounded-3xl sm:p-8 md:p-12 hover:shadow-xl duration-600">

                {{-- Icon container: el fondo del icono interior se adapta --}}
                <div class="flex items-center justify-center w-16 h-16 p-1 mx-auto mb-5 rounded-full shadow-lg sm:h-20 sm:w-20 bg-gradient-to-br from-indigo-500 to-purple-600">
                    <div class="flex items-center justify-center w-full h-full bg-white rounded-full dark:bg-slate-700 ">
                        {{-- SVG icon color no necesita cambio si el fondo del contenedor se ajusta bien --}}
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-8 h-8 text-indigo-600 sm:h-10 sm:w-10 " fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z" />
                        </svg>
                    </div>
                </div>

                {{-- Main heading: el texto degradado puede funcionar bien en ambos modos, pero se puede ajustar si es necesario --}}
                <h1 class="mb-4 text-4xl font-bold text-transparent md:text-5xl bg-clip-text bg-gradient-to-r from-indigo-600 via-purple-600 to-pink-500 text-balance">
                    ¡Bienvenido al <span class="block sm:inline">SistemaCREA!</span>
                </h1>

                {{-- Paragraphs: adaptados para modo oscuro --}}
                <p class="mb-6 text-lg text-slate-700 dark:text-slate-300 sm:mb-8 text-balance">
                    Hola, <span class="font-semibold">{{ Auth::user()->name }}</span>. Nos alegra tenerte de vuelta.
                </p>

                <p class="mb-8 text-md text-slate-500 dark:text-slate-400 sm:mb-10 text-balance">
                    Este es tu punto de partida para gestionar y acceder a toda la información relevante.
                    Navega por las diferentes secciones utilizando el menú principal.
                </p>

                {{-- Button container: los botones ya tienen estilos dark de la navegación, pero se revisan --}}
                <div class="flex flex-col items-center justify-center gap-3 mt-8 sm:flex-row sm:gap-4">
                    @can('manage-roles')
                    <a href="{{ route('dashboard') }}"
                       class="relative flex items-center justify-center w-full px-6 py-3 text-base font-semibold text-white transition-all duration-300 ease-in-out border-2 border-transparent rounded-full shadow-md group sm:w-auto bg-gradient-to-r from-indigo-600 to-purple-600 hover:shadow-lg hover:scale-105 focus:outline-none focus:ring-2 focus:ring-purple-500 focus:ring-offset-2 dark:focus:ring-offset-slate-900">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5 mr-2 transition-transform duration-300 group-hover:scale-110">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 6H5.25A2.25 2.25 0 0 0 3 8.25v10.5A2.25 2.25 0 0 0 5.25 21h10.5A2.25 2.25 0 0 0 18 18.75V10.5m-10.5 6L21 3m0 0h-5.25M21 3v5.25" />
                        </svg>
                        Ir al Panel de Control
                    </a>
                    @else
                    <a href="{{ route('profile.edit') }}"
                       class="relative flex items-center justify-center w-full px-6 py-3 text-base font-semibold text-indigo-600 transition-all duration-300 ease-in-out bg-white border-2 border-indigo-500 rounded-full shadow-sm group sm:w-auto dark:border-indigo-500 dark:text-indigo-300 dark:bg-slate-800 hover:shadow-md hover:bg-indigo-50 dark:hover:bg-slate-700 hover:scale-105 focus:outline-none focus:ring-2 focus:ring-indigo-500 dark:focus:ring-indigo-400 focus:ring-offset-1 dark:focus:ring-offset-slate-900">
                        <svg class="w-5 h-5 mr-2 transition-transform duration-300 group-hover:scale-110" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                            <path d="M10 8a3 3 0 100-6 3 3 0 000 6zM3.465 14.493a1.23 1.23 0 00.41 1.412A9.957 9.957 0 0010 18c2.31 0 4.438-.784 6.131-2.095a1.23 1.23 0 00.41-1.412A9.99 9.99 0 0010 12.75a9.99 9.99 0 00-6.535 1.743z" />
                        </svg>
                        Ver mi Perfil
                    </a>
                    @endcan

                    <form method="POST" action="{{ route('logout') }}" class="w-full sm:w-auto">
                        @csrf
                        <button type="submit"
                                class="relative flex items-center justify-center w-full px-6 py-3 text-base font-medium transition-all duration-300 ease-in-out border-2 border-transparent rounded-full group text-slate-700 dark:text-slate-300 bg-slate-200 dark:bg-slate-700 hover:bg-slate-300 dark:hover:bg-slate-600 hover:shadow-md hover:scale-105 focus:outline-none focus:ring-2 focus:ring-slate-500 dark:focus:ring-slate-400 focus:ring-offset-1 dark:focus:ring-offset-slate-900">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="h-5 w-5 mr-2 transition-transform duration-300 group-hover:rotate-[10deg]">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 9V5.25A2.25 2.25 0 0 1 10.5 3h6a2.25 2.25 0 0 1 2.25 2.25v13.5A2.25 2.25 0 0 1 16.5 21h-6a2.25 2.25 0 0 1-2.25-2.25V15m-3 0-3-3m0 0 3-3m-3 3H15" />
                            </svg>
                            Cerrar Sesión
                        </button>
                    </form>
                </div>
                {{-- El footer se incluye desde app.blade.php, no es necesario aquí --}}
            </div>
        </div>
    </div>
</x-app-layout>

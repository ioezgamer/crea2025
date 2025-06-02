<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800">
            {{ __('Inicio') }}
        </h2>
    </x-slot>

    {{-- Main content area with gradient background --}}
    {{-- Uses flexbox to center content vertically and horizontally, and responsive padding --}}
    <div class="py-12 bg-gradient-to-br from-slate-50 via-purple-50 to-pink-50 min-h-[calc(100vh-4rem)] flex items-center justify-center px-4 sm:px-6 lg:px-8">
        <div class="w-full max-w-4xl mx-auto text-center">
            {{-- Main content card with frosted glass effect and responsive padding --}}
            <div class="p-6 transition-transform ease-in transform shadow-lg bg-white/80 backdrop-blur-xl rounded-3xl sm:p-8 md:p-12 hover:shadow-xl duration-600">

                {{-- Icon container with responsive sizing --}}
                <div class="flex items-center justify-center w-16 h-16 p-1 mx-auto mb-5 rounded-full shadow-lg sm:h-20 sm:w-20 bg-gradient-to-br from-indigo-500 to-purple-600">
                    <div class="flex items-center justify-center w-full h-full bg-white rounded-full ">
                        {{-- SVG icon with responsive sizing --}}
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-8 h-8 text-indigo-600 sm:h-10 sm:w-10 " fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z" />
                        </svg>
                    </div>
                </div>

                {{-- Main heading with responsive text size and word breaking --}}
                <h1 class="mb-4 text-4xl font-bold text-transparent md:text-5xl bg-clip-text bg-gradient-to-r from-indigo-600 via-purple-600 to-pink-500 text-balance">
                    ¡Bienvenido al <span class="block sm:inline">SistemaCREA!</span> {{-- block sm:inline for responsive text flow --}}
                </h1>

                {{-- Paragraphs with responsive margins and text balancing --}}
                <p class="mb-6 text-lg text-slate-700 sm:mb-8 text-balance">
                    Hola, <span class="font-semibold">{{ Auth::user()->name }}</span>. Nos alegra tenerte de vuelta.
                </p>

                <p class="mb-8 text-md text-slate-500 sm:mb-10 text-balance">
                    Este es tu punto de partida para gestionar y acceder a toda la información relevante.
                    Navega por las diferentes secciones utilizando el menú principal.
                </p>

                {{-- Button container: stacks vertically on small screens, row on larger screens --}}
                <div class="flex flex-col items-center justify-center gap-3 mt-8 sm:flex-row sm:gap-4">
                    @can('manage-roles')
                    {{-- Primary Action Button: Full width on small screens, auto width on larger screens --}}
                    <a href="{{ route('dashboard') }}"
                       class="relative flex items-center justify-center w-full px-6 py-3 text-base font-semibold text-white transition-all duration-300 ease-in-out border-2 border-transparent rounded-full shadow-md group sm:w-auto bg-gradient-to-r from-indigo-600 to-purple-600 hover:shadow-lg hover:scale-105 focus:outline-none focus:ring-2 focus:ring-purple-500 focus:ring-offset-2 dark:focus:ring-offset-slate-800">
                        <svg class="w-5 h-5 mr-2 transition-transform duration-300 group-hover:scale-110" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M5.224 3.224A1 1 0 016.224 3h7.552a1 1 0 01.915.589l.464.872a9.002 9.002 0 013.622 3.622l.872.464A1 1 0 0120 9.776v.448a1 1 0 01-.224.633l-.872.464a9.002 9.002 0 01-3.622 3.622l-.464.872a1 1 0 01-.915.589H6.224a1 1 0 01-.915-.589l-.464-.872a9.002 9.002 0 01-3.622-3.622l-.872-.464A1 1 0 010 10.224v-.448a1 1 0 01.224-.633l.872-.464a9.002 9.002 0 013.622-3.622l.464-.872A1 1 0 016.224 3zM4.5 10a5.5 5.5 0 1111 0 5.5 5.5 0 01-11 0zM10 7a3 3 0 100 6 3 3 0 000-6z" clip-rule="evenodd" />
                        </svg>
                        Ir al Panel de Control
                    </a>
                    @else
                    {{-- Secondary Action Button: Full width on small screens, auto width on larger screens --}}
                    <a href="{{ route('profile.edit') }}"
                       class="relative flex items-center justify-center w-full px-6 py-3 text-base font-semibold text-indigo-600 transition-all duration-300 ease-in-out bg-white border-2 border-indigo-500 rounded-full shadow-sm group sm:w-auto dark:border-indigo-400 dark:text-indigo-300 dark:bg-slate-800 hover:shadow-md hover:bg-indigo-50 dark:hover:bg-slate-700 hover:scale-105 focus:outline-none focus:ring-2 focus:ring-indigo-500 dark:focus:ring-indigo-400 focus:ring-offset-1 dark:focus:ring-offset-slate-800">
                        <svg class="w-5 h-5 mr-2 transition-transform duration-300 group-hover:scale-110" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                            <path d="M10 8a3 3 0 100-6 3 3 0 000 6zM3.465 14.493a1.23 1.23 0 00.41 1.412A9.957 9.957 0 0010 18c2.31 0 4.438-.784 6.131-2.095a1.23 1.23 0 00.41-1.412A9.99 9.99 0 0010 12.75a9.99 9.99 0 00-6.535 1.743z" />
                        </svg>
                        Ver mi Perfil
                    </a>
                    @endcan

                    {{-- Logout Button Form: Full width on small screens, auto width on larger screens --}}
                    <form method="POST" action="{{ route('logout') }}" class="w-full sm:w-auto">
                        @csrf
                        <button type="submit"
                                class="relative flex items-center justify-center w-full px-6 py-3 text-base font-medium transition-all duration-300 ease-in-out border-2 border-transparent rounded-full group text-slate-700 dark:text-slate-300 bg-slate-200 dark:bg-slate-700 hover:bg-slate-300 dark:hover:bg-slate-600 hover:shadow-md hover:scale-105 focus:outline-none focus:ring-2 focus:ring-slate-500 dark:focus:ring-slate-400 focus:ring-offset-1 dark:focus:ring-offset-slate-800">
                            <svg class="h-5 w-5 mr-2 transition-transform duration-300 group-hover:rotate-[10deg]" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M3 3a1 1 0 00-1 1v12a1 1 0 102 0V4a1 1 0 00-1-1zm10.293 9.293a1 1 0 001.414 1.414l3-3a1 1 0 000-1.414l-3-3a1 1 0 10-1.414 1.414L14.586 9H7a1 1 0 100 2h7.586l-1.293 1.293z" clip-rule="evenodd" />
                            </svg>
                            Cerrar Sesión
                        </button>
                    </form>
                </div>


            </div>
        </div>
    </div>
</x-app-layout>

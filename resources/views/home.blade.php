<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800  leading-tight">
            {{ __('Inicio') }}
        </h2>
    </x-slot>

    {{-- Main content area with gradient background --}}
    <div class="py-12 bg-gradient-to-br from-slate-50 via-purple-50 to-pink-50  min-h-[calc(100vh-6rem)] flex items-center justify-center px-4 sm:px-6 lg:px-8">
        <div class="max-w-4xl mx-auto text-center w-full">
            {{-- Main content card with frosted glass effect --}}
            <div class="bg-white/80  backdrop-blur-xl shadow-2xl rounded-3xl p-6 sm:p-8 md:p-12 transform hover:scale-[1.02] transition-transform duration-300 ease-in-out">
                
                {{-- Icon (Consistent "Idea" icon) --}}
                <div class="mx-auto mb-5 h-16 w-16 sm:h-20 sm:w-20 flex items-center justify-center rounded-full bg-gradient-to-br from-indigo-500 to-purple-600 p-1 shadow-lg">
                    <div class="flex h-full w-full items-center justify-center rounded-full bg-white ">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 sm:h-10 sm:w-10 text-indigo-600 " fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z" />
                        </svg>
                    </div>
                </div>

                <h1 class="text-4xl md:text-5xl font-bold mb-4 text-transparent bg-clip-text bg-gradient-to-r from-indigo-600 via-purple-600 to-pink-500  text-balance">
                    ¡Bienvenido al <span class="block sm:inline">Sistema CREA!</span>
                </h1>
                
                <p class="text-lg text-slate-700  mb-6 sm:mb-8 text-balance">
                    Hola, <span class="font-semibold">{{ Auth::user()->name }}</span>. Nos alegra tenerte de vuelta.
                </p>
                
                <p class="text-md text-slate-500  mb-8 sm:mb-10 text-balance">
                    Este es tu punto de partida para gestionar y acceder a toda la información relevante.
                    Navega por las diferentes secciones utilizando el menú principal.
                </p>

                <div class="mt-8 flex flex-col sm:flex-row justify-center items-center gap-3 sm:gap-4">
                    @can('manage-roles')
                    {{-- Primary Action Button: Go to Dashboard --}}
                    <a href="{{ route('dashboard') }}"
                       class="group relative flex justify-center items-center w-full sm:w-auto text-base font-semibold px-6 py-3 border-2 border-transparent rounded-full transition-all duration-300 ease-in-out
                              bg-gradient-to-r from-indigo-600 to-purple-600 text-white shadow-md hover:shadow-lg hover:scale-105 focus:outline-none focus:ring-2 focus:ring-purple-500 focus:ring-offset-2 dark:focus:ring-offset-slate-800">
                        <svg class="h-5 w-5 mr-2 transition-transform duration-300 group-hover:scale-110" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M5.224 3.224A1 1 0 016.224 3h7.552a1 1 0 01.915.589l.464.872a9.002 9.002 0 013.622 3.622l.872.464A1 1 0 0120 9.776v.448a1 1 0 01-.224.633l-.872.464a9.002 9.002 0 01-3.622 3.622l-.464.872a1 1 0 01-.915.589H6.224a1 1 0 01-.915-.589l-.464-.872a9.002 9.002 0 01-3.622-3.622l-.872-.464A1 1 0 010 10.224v-.448a1 1 0 01.224-.633l.872-.464a9.002 9.002 0 013.622-3.622l.464-.872A1 1 0 016.224 3zM4.5 10a5.5 5.5 0 1111 0 5.5 5.5 0 01-11 0zM10 7a3 3 0 100 6 3 3 0 000-6z" clip-rule="evenodd" />
                        </svg>
                        Ir al Panel de Control
                    </a>
                    @else
                    {{-- Secondary Action Button: View Profile --}}
                    <a href="{{ route('profile.edit') }}"
                       class="group relative flex justify-center items-center w-full sm:w-auto text-base font-semibold px-6 py-3 border-2 border-indigo-500 dark:border-indigo-400 rounded-full transition-all duration-300 ease-in-out
                              text-indigo-600 dark:text-indigo-300 bg-white dark:bg-slate-800 shadow-sm hover:shadow-md hover:bg-indigo-50 dark:hover:bg-slate-700 hover:scale-105 focus:outline-none focus:ring-2 focus:ring-indigo-500 dark:focus:ring-indigo-400 focus:ring-offset-1 dark:focus:ring-offset-slate-800">
                        <svg class="h-5 w-5 mr-2 transition-transform duration-300 group-hover:scale-110" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                            <path d="M10 8a3 3 0 100-6 3 3 0 000 6zM3.465 14.493a1.23 1.23 0 00.41 1.412A9.957 9.957 0 0010 18c2.31 0 4.438-.784 6.131-2.095a1.23 1.23 0 00.41-1.412A9.99 9.99 0 0010 12.75a9.99 9.99 0 00-6.535 1.743z" />
                        </svg>
                        Ver mi Perfil
                    </a>
                    @endcan

                    {{-- Logout Button --}}
                    <form method="POST" action="{{ route('logout') }}" class="w-full sm:w-auto">
                        @csrf
                        <button type="submit"
                                class="group relative flex justify-center items-center w-full text-base font-medium px-6 py-3 border-2 border-transparent rounded-full transition-all duration-300 ease-in-out
                                       text-slate-700 dark:text-slate-300 bg-slate-200 dark:bg-slate-700 hover:bg-slate-300 dark:hover:bg-slate-600 hover:shadow-md hover:scale-105 focus:outline-none focus:ring-2 focus:ring-slate-500 dark:focus:ring-slate-400 focus:ring-offset-1 dark:focus:ring-offset-slate-800">
                            <svg class="h-5 w-5 mr-2 transition-transform duration-300 group-hover:rotate-[10deg]" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M3 3a1 1 0 00-1 1v12a1 1 0 102 0V4a1 1 0 00-1-1zm10.293 9.293a1 1 0 001.414 1.414l3-3a1 1 0 000-1.414l-3-3a1 1 0 10-1.414 1.414L14.586 9H7a1 1 0 100 2h7.586l-1.293 1.293z" clip-rule="evenodd" />
                            </svg>
                            Cerrar Sesión
                        </button>
                    </form>
                </div>
                
                <div class="mt-10 pt-6 border-t border-slate-200 dark:border-slate-700 text-xs text-slate-500 dark:text-slate-400">
                    <p>&copy; {{ date('Y') }} Sistema CREA. Todos los derechos reservados.</p>
                    <p>Managua, Nicaragua.</p>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

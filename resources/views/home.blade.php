<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Inicio') }}
        </h2>
    </x-slot>

    <div class="py-12 bg-gradient-to-br from-indigo-50 via-purple-50 to-pink-50 dark:from-gray-800 dark:via-gray-900 dark:to-black min-h-[calc(100vh-6rem)] flex items-center justify-center">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8 text-center">
            <div class="bg-white dark:bg-gray-800/80 backdrop-blur-md shadow-2xl rounded-xl p-8 md:p-12 transform hover:scale-105 transition-transform duration-300">
                
                {{-- Logo o Icono (Opcional) --}}
                <div class="mb-6">
                    <svg class="mx-auto h-16 w-16 text-indigo-600 dark:text-indigo-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 21v-8.25M15.75 21v-8.25M8.25 21v-8.25M3 9l9-6 9 6m-1.5 12V10.332A48.36 48.36 0 0012 9.75c-2.551 0-5.056.2-7.5.582V21M3 21h18M12 6.75h.008v.008H12V6.75z" />
                    </svg>
                </div>

                <h1 class="text-4xl md:text-5xl font-bold text-indigo-700 dark:text-indigo-300 mb-4">
                    ¡Bienvenido al <span class="block sm:inline">Sistema CREA!</span>
                </h1>
                
                <p class="text-lg text-gray-600 dark:text-gray-300 mb-8">
                    Hola, <span class="font-semibold">{{ Auth::user()->name }}</span>. Nos alegra tenerte de vuelta.
                </p>
                
                <p class="text-md text-gray-500 dark:text-gray-400 mb-8">
                    Este es tu punto de partida para gestionar y acceder a toda la información relevante.
                    Navega por las diferentes secciones utilizando el menú principal.
                </p>

                <div class="mt-8 flex flex-col sm:flex-row justify-center items-center gap-4">
                    @can('manage-roles')
                    <a href="{{ route('dashboard') }}"
                       class="w-full sm:w-auto inline-flex items-center justify-center px-6 py-3 border border-transparent text-base font-medium rounded-lg text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-800 transition-colors">
                        <svg class="h-5 w-5 mr-2" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                          <path fill-rule="evenodd" d="M5.224 3.224A1 1 0 016.224 3h7.552a1 1 0 01.915.589l.464.872a9.002 9.002 0 013.622 3.622l.872.464A1 1 0 0120 9.776v.448a1 1 0 01-.224.633l-.872.464a9.002 9.002 0 01-3.622 3.622l-.464.872a1 1 0 01-.915.589H6.224a1 1 0 01-.915-.589l-.464-.872a9.002 9.002 0 01-3.622-3.622l-.872-.464A1 1 0 010 10.224v-.448a1 1 0 01.224-.633l.872-.464a9.002 9.002 0 013.622-3.622l.464-.872A1 1 0 016.224 3zM4.5 10a5.5 5.5 0 1111 0 5.5 5.5 0 01-11 0zM10 7a3 3 0 100 6 3 3 0 000-6z" clip-rule="evenodd" />
                        </svg>
                        Ir al Panel de Control
                    </a>
                    @else
                     <a href="{{ route('profile.edit') }}"
                       class="w-full sm:w-auto inline-flex items-center justify-center px-6 py-3 border border-indigo-600 text-base font-medium rounded-lg text-indigo-600 bg-white hover:bg-indigo-50 dark:bg-gray-700 dark:text-indigo-300 dark:hover:bg-gray-600 dark:border-indigo-500 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-800 transition-colors">
                        <svg class="h-5 w-5 mr-2" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                          <path d="M10 8a3 3 0 100-6 3 3 0 000 6zM3.465 14.493a1.23 1.23 0 00.41 1.412A9.957 9.957 0 0010 18c2.31 0 4.438-.784 6.131-2.095a1.23 1.23 0 00.41-1.412A9.99 9.99 0 0010 12.75a9.99 9.99 0 00-6.535 1.743z" />
                        </svg>
                        Ver mi Perfil
                    </a>
                    @endcan

                    <form method="POST" action="{{ route('logout') }}" class="w-full sm:w-auto">
                        @csrf
                        <button type="submit"
                                class="w-full inline-flex items-center justify-center px-6 py-3 border border-transparent text-base font-medium rounded-lg text-indigo-700 bg-indigo-100 hover:bg-indigo-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:text-indigo-300 dark:bg-gray-700 dark:hover:bg-gray-600 dark:focus:ring-offset-gray-800 transition-colors">
                           <svg class="h-5 w-5 mr-2" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                              <path fill-rule="evenodd" d="M3 3a1 1 0 00-1 1v12a1 1 0 102 0V4a1 1 0 00-1-1zm10.293 9.293a1 1 0 001.414 1.414l3-3a1 1 0 000-1.414l-3-3a1 1 0 10-1.414 1.414L14.586 9H7a1 1 0 100 2h7.586l-1.293 1.293z" clip-rule="evenodd" />
                            </svg>
                            Cerrar Sesión
                        </button>
                    </form>
                </div>
                
                <div class="mt-10 text-xs text-gray-400 dark:text-gray-500">
                    <p>&copy; {{ date('Y') }} Sistema CREA. Todos los derechos reservados.</p>
                    <p>Managua, Nicaragua.</p>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

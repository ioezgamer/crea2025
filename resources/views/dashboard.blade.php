<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between w-full px-4 py-6 mx-auto max-w-7xl sm:px-6 lg:px-8">
           <h1 class="text-2xl font-bold text-transparent lg:text-3xl bg-clip-text bg-gradient-to-r from-indigo-600 via-purple-600 to-pink-500">
            {{ __('Dashboard') }}
            </h1>
        </div>
    </x-slot>

    {{-- MEJORA: Se elimina el script que creaba variables globales.
         Los datos se pasarán directamente a cada canvas mediante atributos data-*. --}}

    {{-- Main content area --}}
    <div class="min-h-screen py-8 bg-gradient-to-br from-slate-50 via-purple-50 to-pink-50 dark:from-slate-800 dark:via-purple-900 dark:to-pink-900">
        <div class="px-4 mx-auto max-w-7xl sm:px-6 lg:px-8">

            {{-- Welcome Banner --}}
            <div class="mb-8">
                <div class="p-6 shadow-xl bg-white/70 dark:bg-slate-800/80 backdrop-blur-lg rounded-2xl">
                    <h3 class="text-xs font-bold text-transparent lg:text-3xl bg-clip-text bg-gradient-to-r from-indigo-600 via-purple-600 to-pink-500">
                        ¡Bienvenido, <span class="font-bold">{{ Auth::user()->name }}</span>!
                    </h3>
                    <p class="text-sm text-slate-600 dark:text-slate-300">
                        Aquí tienes un resumen general de la actividad en el Sistema CREA.
                    </p>
                </div>
            </div>

            {{-- User Statistics Section --}}
            <h3 class="mb-4 ml-1 text-xl font-semibold text-slate-700 dark:text-slate-200">Estadísticas de Usuarios</h3>
            <div class="grid grid-cols-1 gap-6 mb-8 md:grid-cols-2 lg:grid-cols-4">
                {{-- Stat Card: Total Users --}}
                <div class="flex items-center p-6 transition-shadow duration-300 shadow-lg bg-white/70 dark:bg-slate-800/80 backdrop-blur-lg rounded-2xl hover:shadow-xl">
                    <div class="flex-shrink-0 bg-gradient-to-br from-indigo-500 to-purple-600 rounded-xl p-3.5 mr-4 shadow-md">
                        <svg class="w-8 h-8 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                        </svg>
                    </div>
                    <div>
                        <h4 class="text-sm font-medium text-slate-500 dark:text-slate-400">Usuarios Totales</h4>
                        <p class="text-3xl font-bold text-slate-800 dark:text-slate-100">{{ $totalUsers ?? 0 }}</p>
                    </div>
                </div>

                {{-- Stat Card: Approved Users --}}
                <div class="flex items-center p-6 transition-shadow duration-300 shadow-lg bg-white/70 dark:bg-slate-800/80 backdrop-blur-lg rounded-2xl hover:shadow-xl">
                    <div class="flex-shrink-0 bg-gradient-to-br from-green-500 to-emerald-600 rounded-xl p-3.5 mr-4 shadow-md">
                         <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    </div>
                    <div>
                        <h4 class="text-sm font-medium text-slate-500 dark:text-slate-400">Usuarios Aprobados</h4>
                        <p class="text-3xl font-bold text-slate-800 dark:text-slate-100">{{ $approvedUsers ?? 0 }}</p>
                        @can('manage-roles')
                        <a href="{{ route('roles.index', ['approval_status' => 'approved']) }}" class="block mt-1 text-xs text-indigo-600 dark:text-indigo-400 hover:underline">Ver aprobados</a>
                        @endcan
                    </div>
                </div>

                {{-- Stat Card: Pending Users --}}
                <div class="flex items-center p-6 transition-shadow duration-300 shadow-lg bg-white/70 dark:bg-slate-800/80 backdrop-blur-lg rounded-2xl hover:shadow-xl">
                    <div class="flex-shrink-0 bg-gradient-to-br from-amber-500 to-orange-600 rounded-xl p-3.5 mr-4 shadow-md">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <div>
                        <h4 class="text-sm font-medium text-slate-500 dark:text-slate-400">Usuarios Pendientes</h4>
                        <p class="text-3xl font-bold text-slate-800 dark:text-slate-100">{{ $pendingUsers ?? 0 }}</p>
                         @can('manage-roles')
                        <a href="{{ route('roles.index', ['approval_status' => 'pending']) }}" class="block mt-1 text-xs text-indigo-600 dark:text-indigo-400 hover:underline">Gestionar pendientes</a>
                        @endcan
                    </div>
                </div>

                {{-- Stat Card: Users by Role --}}
                <div class="p-6 transition-shadow duration-300 shadow-lg bg-white/70 dark:bg-slate-800/80 backdrop-blur-lg rounded-2xl hover:shadow-xl">
                    <div class="flex items-center mb-3">
                        <div class="flex-shrink-0 bg-gradient-to-br from-purple-500 to-fuchsia-600 rounded-xl p-3.5 mr-4 shadow-md">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-8 h-8 text-white">
                            <path d="M11.584 2.376a.75.75 0 0 1 .832 0l9 6a.75.75 0 1 1-.832 1.248L12 3.901 3.416 9.624a.75.75 0 0 1-.832-1.248l9-6Z" />
                            <path fill-rule="evenodd" d="M20.25 10.332v9.918H21a.75.75 0 0 1 0 1.5H3a.75.75 0 0 1 0-1.5h.75v-9.918a.75.75 0 0 1 .634-.74A49.109 49.109 0 0 1 12 9c2.59 0 5.134.202 7.616.592a.75.75 0 0 1 .634.74Zm-7.5 2.418a.75.75 0 0 0-1.5 0v6.75a.75.75 0 0 0 1.5 0v-6.75Zm3-.75a.75.75 0 0 1 .75.75v6.75a.75.75 0 0 1-1.5 0v-6.75a.75.75 0 0 1 .75-.75ZM9 12.75a.75.75 0 0 0-1.5 0v6.75a.75.75 0 0 0 1.5 0v-6.75Z" clip-rule="evenodd" />
                            <path d="M12 7.875a1.125 1.125 0 1 0 0-2.25 1.125 1.125 0 0 0 0 2.25Z" />
                            </svg>

                        </div>
                        <div class="text-xs text-slate-500 dark:text-slate-400 ml-1 space-y-0.5">
                        <h4 class="text-sm font-medium text-slate-500 dark:text-slate-400">Roles</h4>

                        <p>Administradores: {{ $adminUsers ?? 0 }}</p>
                        <p>Coordinadores: {{ $coordinadorUsers ?? 0 }}</p>
                        <p>Facilitadores: {{ $facilitadorUsers ?? 0 }}</p>
                        <p>Invitados: {{ $invitadoUsers ?? 0 }}</p>

                        @can('gestionar usuarios y roles')
                            <a href="{{ route('roles.index') }}" class="block mt-2 text-xs text-indigo-600 dark:text-indigo-400 hover:underline">Gestionar Roles</a>
                        @endcan
                    </div>
                    </div>

                </div>
            </div>

            {{-- Participant Statistics Section --}}
            <h3 class="mb-4 ml-1 text-xl font-semibold text-slate-700 dark:text-slate-200">Estadísticas de Participantes</h3>
            <div class="grid grid-cols-1 gap-6 mb-8 md:grid-cols-2 lg:grid-cols-4">
                <div class="flex items-center p-6 transition-shadow duration-300 shadow-lg bg-white/70 dark:bg-slate-800/80 backdrop-blur-lg rounded-2xl hover:shadow-xl">
                    <div class="flex-shrink-0 bg-gradient-to-br from-sky-500 to-cyan-600 rounded-xl p-3.5 mr-4 shadow-md">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                        </svg>
                    </div>
                    <div>
                        <h4 class="text-sm font-medium text-slate-500 dark:text-slate-400">Total Inscritos</h4>
                        <p class="text-3xl font-bold text-slate-800 dark:text-slate-100">{{ $totalParticipants ?? 0 }}</p>
                        <a href="{{ route('participante.index') }}" class="block mt-1 text-xs text-sky-600 dark:text-sky-400 hover:underline">Ver Participantes</a>
                    </div>
                </div>

                {{-- New Stat Card: New Enrollments This Month --}}
                <div class="flex items-center p-6 transition-shadow duration-300 shadow-lg bg-white/70 dark:bg-slate-800/80 backdrop-blur-lg rounded-2xl hover:shadow-xl">
                    <div class="flex-shrink-0 bg-gradient-to-br from-pink-500 to-rose-600 rounded-xl p-3.5 mr-4 shadow-md">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                    </div>
                    <div>
                        <h4 class="text-sm font-medium text-slate-500 dark:text-slate-400">Nuevas inscripciones (este mes)</h4>
                        <p class="text-3xl font-bold text-slate-800 dark:text-slate-100">{{ $newParticipantsThisMonth ?? 0 }}</p>
                    </div>
                </div>

                <div class="flex items-center p-6 transition-shadow duration-300 shadow-lg bg-white/70 dark:bg-slate-800/80 backdrop-blur-lg rounded-2xl hover:shadow-xl">
                    <div class="flex-shrink-0 bg-gradient-to-br from-teal-500 to-emerald-600 rounded-xl p-3.5 mr-4 shadow-md">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                        </svg>
                    </div>
                    <div>
                        <h4 class="text-sm font-medium text-slate-500 dark:text-slate-400">Programas</h4>
                        <p class="text-3xl font-bold text-slate-800 dark:text-slate-100">{{ count($participantsByProgramData ?? []) }}</p>
                        <a href="{{ route('programas') }}" class="block mt-1 text-xs text-teal-600 dark:text-teal-400 hover:underline">Ver programas</a>
                    </div>
                </div>

                <div class="flex items-center p-6 transition-shadow duration-300 shadow-lg bg-white/70 dark:bg-slate-800/80 backdrop-blur-lg rounded-2xl hover:shadow-xl">
                     <div class="flex-shrink-0 bg-gradient-to-br from-orange-500 to-red-600 rounded-xl p-3.5 mr-4 shadow-md">
                         <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                    </div>
                    <div>
                        <h4 class="text-sm font-medium text-slate-500 dark:text-slate-400">Tutores registrados</h4>
                        <p class="text-3xl font-bold text-slate-800 dark:text-slate-100">{{ $tutorsCount ?? 0 }}</p>
                        <a href="{{ route('tutores') }}" class="block mt-1 text-xs text-orange-600 dark:text-orange-400 hover:underline">Ver Tutores</a>
                    </div>
                </div>
            </div>

            {{-- Charts Section --}}
            <div class="grid grid-cols-1 gap-6 mb-8 lg:grid-cols-2">
                <div class="p-6 transition-shadow duration-300 shadow-lg bg-white/70 dark:bg-slate-800/80 backdrop-blur-lg rounded-2xl hover:shadow-xl">
                    <h3 class="mb-4 text-lg font-medium text-slate-700 dark:text-slate-200">Participantes por programa</h3>
                    <div class="h-[300px] sm:h-[350px]">
                        {{-- MEJORA: Se añade el atributo data-chart-data con los datos del gráfico --}}
                        <canvas id="participantsByProgramChart" data-chart-data="{{ json_encode($participantsByProgramData ?? []) }}"></canvas>
                    </div>
                </div>

                <div class="p-6 transition-shadow duration-300 shadow-lg bg-white/70 dark:bg-slate-800/80 backdrop-blur-lg rounded-2xl hover:shadow-xl">
                    <h3 class="mb-4 text-lg font-medium text-slate-700 dark:text-slate-200">Participantes por Lugar de encuentro</h3>
                    <div class="h-[300px] sm:h-[350px]">
                        {{-- MEJORA: Se añade el atributo data-chart-data con los datos del gráfico --}}
                        <canvas id="participantsByPlaceChart" data-chart-data="{{ json_encode($participantsByPlaceData ?? []) }}"></canvas>
                    </div>
                </div>
            </div>

            {{-- New Chart Section for New Participants by Month --}}
            <div class="mb-8">
                <div class="p-6 transition-shadow duration-300 shadow-lg bg-white/70 dark:bg-slate-800/80 backdrop-blur-lg rounded-2xl hover:shadow-xl">
                    <h3 class="mb-4 text-lg font-medium text-slate-700 dark:text-slate-200">Participantes por mes (año actual <span id="anio-actual"></span>)</h3>
                    <div class="h-[300px] sm:h-[350px]">
                        {{-- MEJORA: Se añade el atributo data-chart-data con los datos del gráfico --}}
                        <canvas id="newParticipantsByMonthChart" data-chart-data="{{ json_encode($newParticipantsByMonth ?? []) }}"></canvas>
                    </div>
                </div>
            </div>


            {{-- Tables Section --}}
            <div class="grid grid-cols-1 gap-6 mb-8 lg:grid-cols-2">
                <div class="p-6 transition-shadow duration-300 shadow-lg bg-white/70 dark:bg-slate-800/80 backdrop-blur-lg rounded-2xl hover:shadow-xl">
                    <h3 class="mb-4 font-semibold text-md text-slate-700 dark:text-slate-200">Participantes por programas</h3>
                    <div class="overflow-x-auto">
                        <table class="min-w-full text-sm divide-y divide-slate-200 dark:divide-slate-700">
                            <thead class="bg-slate-100 dark:bg-slate-700/50">
                                <tr class="text-xs font-semibold tracking-wider uppercase text-slate-600 dark:text-slate-300">
                                    <th class="px-4 py-3 text-left">Programa</th>
                                    <th class="px-4 py-3 text-right">Total inscritos</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y dark:bg-slate-800 divide-slate-200 dark:divide-slate-700">
                                @forelse ($participantsByProgramForTable ?? [] as $program => $count)
                                    <tr class="hover:bg-slate-50 dark:hover:bg-slate-700/30">
                                        <td class="px-4 py-3 text-slate-800 dark:text-slate-200">
                                            <a href="{{ route('participante.index', ['search_programa' => $program]) }}" class="text-indigo-600 dark:text-indigo-400 hover:text-indigo-800 dark:hover:text-indigo-300 hover:underline">
                                                {{ $program }}
                                            </a>
                                        </td>
                                        <td class="px-4 py-3 text-right text-slate-600 dark:text-slate-300">{{ $count }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="2" class="px-4 py-3 text-center text-slate-500 dark:text-slate-400">No hay datos disponibles</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="p-6 transition-shadow duration-300 shadow-lg bg-white/70 dark:bg-slate-800/80 backdrop-blur-lg rounded-2xl hover:shadow-xl">
                    <h3 class="mb-4 font-semibold text-md text-slate-700 dark:text-slate-200">Participantes por Lugar de encuentro</h3>
                    <div class="overflow-x-auto">
                        <table class="min-w-full text-sm divide-y divide-slate-200 dark:divide-slate-700">
                            <thead class="bg-slate-100 dark:bg-slate-700/50">
                                <tr class="text-xs font-semibold tracking-wider uppercase text-slate-600 dark:text-slate-300">
                                    <th class="px-4 py-3 text-left">Lugar</th>
                                    <th class="px-4 py-3 text-right">Total inscritos</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y dark:bg-slate-800 divide-slate-200 dark:divide-slate-700">
                                @forelse ($participantsByPlaceForTable ?? [] as $place => $count)
                                    <tr class="hover:bg-slate-50 dark:hover:bg-slate-700/30">
                                        <td class="px-4 py-3 text-slate-800 dark:text-slate-200">
                                            <a href="{{ route('participante.index', ['search_lugar' => $place]) }}" class="text-indigo-600 dark:text-indigo-400 hover:text-indigo-800 dark:hover:text-indigo-300 hover:underline">
                                                {{ $place }}
                                            </a>
                                        </td>
                                        <td class="px-4 py-3 text-right text-slate-600 dark:text-slate-300">{{ $count }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="2" class="px-4 py-3 text-center text-slate-500 dark:text-slate-400">No hay datos disponibles</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            {{-- Quick Actions Section --}}
            <h3 class="mt-10 mb-4 ml-1 text-xl font-semibold text-center border-b text-slate-700 dark:text-slate-200-">Acciones rápidas</h3>
            <div class="grid items-center justify-center grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-3 lg:gap-6 lg:justify-items-center">
                @can('create-user')
                <a href="{{ route('roles.user.create') }}"
                class="group inline-flex items-center justify-center h-10 pl-3 pr-5 size-64
                        gap-2.5 rounded-full font-semibold relative cursor-pointer
                        transition-all duration-300 ease-in-out
                        shadow-[5px_5px_10px_rgba(0,0,0,0.116)]
                        active:scale-[0.97] active:duration-200
                        text-white bg-gradient-to-r from-indigo-600 to-purple-600 hover:from-indigo-700 hover:to-purple-700">
                    <!-- Icono UserPlus (Heroicons) -->
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="h-[25px] w-[25px] transition-transform duration-[1000ms] ease-in-out group-hover:rotate-[250deg]">
            <path d="M5.25 6.375a4.125 4.125 0 1 1 8.25 0 4.125 4.125 0 0 1-8.25 0ZM2.25 19.125a7.125 7.125 0 0 1 14.25 0v.003l-.001.119a.75.75 0 0 1-.363.63 13.067 13.067 0 0 1-6.761 1.873c-2.472 0-4.786-.684-6.76-1.873a.75.75 0 0 1-.364-.63l-.001-.122ZM18.75 7.5a.75.75 0 0 0-1.5 0v2.25H15a.75.75 0 0 0 0 1.5h2.25v2.25a.75.75 0 0 0 1.5 0v-2.25H21a.75.75 0 0 0 0-1.5h-2.25V7.5Z" />
            </svg>

                    <span>Crear Usuario</span>
                </a>
                @endcan

                <a href="{{ route('participante.create') }}"
                class="group inline-flex items-center justify-center h-10 pl-3 pr-5 size-64
                        gap-2.5 rounded-full font-semibold relative cursor-pointer
                        transition-all duration-300 ease-in-out
                        shadow-[5px_5px_10px_rgba(0,0,0,0.116)]
                        active:scale-[0.97] active:duration-200
                        text-white bg-gradient-to-r from-green-500 to-emerald-600 hover:from-green-600 hover:to-emerald-700">
                    <!-- Icono Users (Heroicons) -->
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="h-[25px] w-[25px] transition-transform duration-[1000ms] ease-in-out group-hover:rotate-[250deg]">
            <path fill-rule="evenodd" d="M18.685 19.097A9.723 9.723 0 0 0 21.75 12c0-5.385-4.365-9.75-9.75-9.75S2.25 6.615 2.25 12a9.723 9.723 0 0 0 3.065 7.097A9.716 9.716 0 0 0 12 21.75a9.716 9.716 0 0 0 6.685-2.653Zm-12.54-1.285A7.486 7.486 0 0 1 12 15a7.486 7.486 0 0 1 5.855 2.812A8.224 8.224 0 0 1 12 20.25a8.224 8.224 0 0 1-5.855-2.438ZM15.75 9a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0Z" clip-rule="evenodd" />
            </svg>

                    <span>Registrar Participante</span>
                </a>

                <a href="{{ route('asistencia.create') }}"
                class="group inline-flex items-center justify-center h-10 pl-3 pr-5 size-64
                        gap-2.5 rounded-full font-semibold relative cursor-pointer
                        transition-all duration-300 ease-in-out
                        shadow-[5px_5px_10px_rgba(0,0,0,0.116)]
                        active:scale-[0.97] active:duration-200
                        text-white bg-gradient-to-r from-sky-500 to-cyan-600 hover:from-sky-600 hover:to-cyan-700">
                    <!-- Icono ClipboardDocumentCheck (Heroicons) -->
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="h-[25px] w-[25px] transition-transform duration-[1000ms] ease-in-out group-hover:rotate-[250deg]">
            <path fill-rule="evenodd" d="M1.5 5.625c0-1.036.84-1.875 1.875-1.875h17.25c1.035 0 1.875.84 1.875 1.875v12.75c0 1.035-.84 1.875-1.875 1.875H3.375A1.875 1.875 0 0 1 1.5 18.375V5.625ZM21 9.375A.375.375 0 0 0 20.625 9h-7.5a.375.375 0 0 0-.375.375v1.5c0 .207.168.375.375.375h7.5a.375.375 0 0 0 .375-.375v-1.5Zm0 3.75a.375.375 0 0 0-.375-.375h-7.5a.375.375 0 0 0-.375.375v1.5c0 .207.168.375.375.375h7.5a.375.375 0 0 0 .375-.375v-1.5Zm0 3.75a.375.375 0 0 0-.375-.375h-7.5a.375.375 0 0 0-.375.375v1.5c0 .207.168.375.375.375h7.5a.375.375 0 0 0 .375-.375v-1.5ZM10.875 18.75a.375.375 0 0 0 .375-.375v-1.5a.375.375 0 0 0-.375-.375h-7.5a.375.375 0 0 0-.375.375v1.5c0 .207.168.375.375.375h7.5ZM3.375 15h7.5a.375.375 0 0 0 .375-.375v-1.5a.375.375 0 0 0-.375-.375h-7.5a.375.375 0 0 0-.375.375v1.5c0 .207.168.375.375.375Zm0-3.75h7.5a.375.375 0 0 0 .375-.375v-1.5A.375.375 0 0 0 10.875 9h-7.5A.375.375 0 0 0 3 9.375v1.5c0 .207.168.375.375.375Z" clip-rule="evenodd" />
            </svg>

                    <span>Registrar Asistencia</span>
                </a>

            </div>
        </div>
    </div>


@push('scripts')
        {{-- Asegúrate que tu archivo dashboard.js refactorizado se esté cargando --}}
        @vite(['resources/js/pages/dashboard.js'])
    @endpush
</x-app-layout>

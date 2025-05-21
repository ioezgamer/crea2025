<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Panel de Control') }}
        </h2>
    </x-slot>

    <div class="py-8 bg-gray-50 dark:bg-gray-900 min-h-screen">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="mb-6">
                <div class="bg-white dark:bg-gray-800 shadow-sm rounded-lg p-6">
                    <p class="text-gray-900 dark:text-gray-100 text-sm">¡Bienvenido! Aquí tienes un resumen general.</p>
                </div>
            </div>

            {{-- Sección de Estadísticas de Usuarios --}}
            <h3 class="text-xl font-semibold text-gray-700 dark:text-gray-300 mb-4 ml-1">Estadísticas de Usuarios</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                <div class="bg-white dark:bg-gray-800 shadow-md rounded-xl p-6 flex items-center hover:shadow-lg transition-shadow duration-300">
                    <div class="flex-shrink-0 bg-indigo-500 rounded-lg p-3 mr-4">
                        <svg class="h-8 w-8 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400">Usuarios Totales</h3>
                        <p class="text-3xl font-bold text-gray-900 dark:text-white">{{ $totalUsers ?? 0 }}</p>
                    </div>
                </div>

                <div class="bg-white dark:bg-gray-800 shadow-md rounded-xl p-6 flex items-center hover:shadow-lg transition-shadow duration-300">
                    <div class="flex-shrink-0 bg-green-500 rounded-lg p-3 mr-4">
                         <svg class="h-8 w-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    </div>
                    <div>
                        <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400">Usuarios Aprobados</h3>
                        <p class="text-3xl font-bold text-gray-900 dark:text-white">{{ $approvedUsers ?? 0 }}</p>
                        @can('manage-roles')
                        <a href="{{ route('roles.index', ['approval_status' => 'approved']) }}" class="text-xs text-indigo-600 dark:text-indigo-400 hover:underline mt-1 block">Ver aprobados</a>
                        @endcan
                    </div>
                </div>

                <div class="bg-white dark:bg-gray-800 shadow-md rounded-xl p-6 flex items-center hover:shadow-lg transition-shadow duration-300">
                    <div class="flex-shrink-0 bg-yellow-500 rounded-lg p-3 mr-4">
                        <svg class="h-8 w-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    </div>
                    <div>
                        <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400">Usuarios Pendientes</h3>
                        <p class="text-3xl font-bold text-gray-900 dark:text-white">{{ $pendingUsers ?? 0 }}</p>
                        @can('manage-roles')
                        <a href="{{ route('roles.index', ['approval_status' => 'pending']) }}" class="text-xs text-indigo-600 dark:text-indigo-400 hover:underline mt-1 block">Gestionar pendientes</a>
                        @endcan
                    </div>
                </div>

                <div class="bg-white dark:bg-gray-800 shadow-md rounded-xl p-6 hover:shadow-lg transition-shadow duration-300">
                    <div class="flex items-center mb-2">
                        <div class="flex-shrink-0 bg-purple-500 rounded-lg p-3 mr-4">
                            <svg class="h-8 w-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 11c0-1.104-.896-2-2-2s-2 .896-2 2c0 .738.402 1.376 1 1.723V15a1 1 0 001 1h2a1 1 0 001-1v-2.277c.598-.347 1-.985 1-1.723zm8 0c0-1.104-.896-2-2-2s-2 .896-2 2c0 .738.402 1.376 1 1.723V15a1 1 0 001 1h2a1 1 0 001-1v-2.277c.598-.347 1-.985 1-1.723zM6 3a3 3 0 00-3 3v12a3 3 0 003 3h12a3 3 0 003-3V6a3 3 0 00-3-3H6z"></path></svg>
                        </div>
                        <div>
                            <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400">Usuarios por Rol</h3>
                             <p class="text-xl font-semibold text-gray-900 dark:text-white">Admins: {{ $adminUsers ?? 0 }}</p>
                        </div>
                    </div>
                    <div class="text-xs text-gray-500 dark:text-gray-400 ml-1">
                        <p>Editores: {{ $editorUsers ?? 0 }}</p>
                        <p>Gestores: {{ $gestorUsers ?? 0 }}</p>
                        <p>Usuarios: {{ $standardUsers ?? 0 }}</p>
                    </div>
                    @can('manage-roles')
                    <a href="{{ route('roles.index') }}" class="text-xs text-indigo-600 dark:text-indigo-400 hover:underline mt-2 block">Gestionar Roles</a>
                    @endcan
                </div>
            </div>

            {{-- Sección de Estadísticas de Participantes (código existente) --}}
            <h3 class="text-xl font-semibold text-gray-700 dark:text-gray-300 mb-4 ml-1">Estadísticas de Participantes</h3>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                <div class="bg-white dark:bg-gray-800 shadow-md rounded-xl p-6 flex items-center hover:shadow-lg transition-shadow duration-300">
                    <div class="flex-shrink-0 bg-sky-500 rounded-lg p-3 mr-4">
                        <svg class="h-8 w-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400">Total Inscritos</h3>
                        <p class="text-3xl font-bold text-gray-900 dark:text-white">{{ $totalParticipants ?? 0 }}</p>
                         <a href="{{ route('participante.index') }}" class="text-xs text-sky-600 dark:text-sky-400 hover:underline mt-1 block">Ver Participantes</a>
                    </div>
                </div>

                <div class="bg-white dark:bg-gray-800 shadow-md rounded-xl p-6 flex items-center hover:shadow-lg transition-shadow duration-300">
                    <div class="flex-shrink-0 bg-teal-500 rounded-lg p-3 mr-4">
                        <svg class="h-8 w-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400">Programas Activos</h3>
                        <p class="text-3xl font-bold text-gray-900 dark:text-white">{{ count($participantsByProgramData ?? []) }}</p>
                        <a href="{{ route('programas') }}" class="text-xs text-teal-600 dark:text-teal-400 hover:underline mt-1 block">Ver Programas</a>
                    </div>
                </div>

                <div class="bg-white dark:bg-gray-800 shadow-md rounded-xl p-6 flex items-center hover:shadow-lg transition-shadow duration-300">
                    <div class="flex-shrink-0 bg-orange-500 rounded-lg p-3 mr-4">
                         <svg class="h-8 w-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                    </div>
                    <div>
                        <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400">Tutores Registrados</h3>
                        <p class="text-3xl font-bold text-gray-900 dark:text-white">{{ $tutorsCount ?? 0 }}</p>
                        <a href="{{ route('tutores') }}" class="text-xs text-orange-600 dark:text-orange-400 hover:underline mt-1 block">Ver Tutores</a>
                    </div>
                </div>
            </div>


            {{-- Sección de Gráficos (código existente) --}}
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
                <div class="bg-white dark:bg-gray-800 shadow-sm border border-gray-200 dark:border-gray-700 rounded-2xl p-6">
                    <h3 class="text-lg font-medium text-gray-700 dark:text-gray-300 mb-4">Participantes por programa</h3>
                    <div style="height: 300px;">
                        <canvas id="participantsByProgramChart"></canvas>
                    </div>
                </div>

                <div class="bg-white dark:bg-gray-800 shadow-sm border border-gray-200 dark:border-gray-700 rounded-2xl p-6">
                    <h3 class="text-lg font-medium text-gray-700 dark:text-gray-300 mb-4">Participantes por lugar de encuentro</h3>
                    <div style="height: 300px;">
                        <canvas id="participantsByPlaceChart"></canvas>
                    </div>
                </div>
            </div>

            {{-- Sección de Tablas con Enlaces (código existente) --}}
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
                {{-- Tabla: Participantes por Programa --}}
                <div class="bg-white dark:bg-gray-800 shadow-sm rounded-lg p-6">
                    <h3 class="text-sm font-medium text-gray-600 dark:text-gray-400 mb-4">Tabla: Participantes por Programa</h3>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700 text-sm">
                            <thead class="bg-gray-100 dark:bg-gray-700">
                                <tr class="text-xs font-medium text-gray-600 dark:text-gray-400 uppercase">
                                    <th class="px-4 py-3 text-left">Programa</th>
                                    <th class="px-4 py-3 text-right">Inscritos</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                                @forelse ($participantsByProgramForTable ?? [] as $program => $count)
                                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50">
                                        <td class="px-4 py-3 text-gray-900 dark:text-gray-100">
                                            <a href="{{ route('participante.index', ['search_programa' => $program]) }}" class="text-indigo-600 dark:text-indigo-400 hover:text-indigo-800 dark:hover:text-indigo-300 hover:underline">
                                                {{ $program }}
                                            </a>
                                        </td>
                                        <td class="px-4 py-3 text-right text-gray-600 dark:text-gray-300">{{ $count }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="2" class="px-4 py-3 text-gray-500 dark:text-gray-400 text-center">No hay datos disponibles</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                {{-- Tabla: Participantes por Lugar de Encuentro --}}
                <div class="bg-white dark:bg-gray-800 shadow-sm rounded-lg p-6">
                    <h3 class="text-sm font-medium text-gray-600 dark:text-gray-400 mb-4">Tabla: Participantes por Lugar de Encuentro</h3>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700 text-sm">
                            <thead class="bg-gray-100 dark:bg-gray-700">
                                <tr class="text-xs font-medium text-gray-600 dark:text-gray-400 uppercase">
                                    <th class="px-4 py-3 text-left">Lugar</th>
                                    <th class="px-4 py-3 text-right">Inscritos</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                                @forelse ($participantsByPlaceForTable ?? [] as $place => $count)
                                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50">
                                        <td class="px-4 py-3 text-gray-900 dark:text-gray-100">
                                            <a href="{{ route('participante.index', ['search_lugar' => $place]) }}" class="text-indigo-600 dark:text-indigo-400 hover:text-indigo-800 dark:hover:text-indigo-300 hover:underline">
                                                {{ $place }}
                                            </a>
                                        </td>
                                        <td class="px-4 py-3 text-right text-gray-600 dark:text-gray-300">{{ $count }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="2" class="px-4 py-3 text-gray-500 dark:text-gray-400 text-center">No hay datos disponibles</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

             {{-- Quick Actions Section (Opcional, ajusta según tus rutas y permisos) --}}
            <h3 class="text-xl font-semibold text-gray-700 dark:text-gray-300 mb-4 ml-1 mt-10">Acciones Rápidas</h3>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                @can('create-user')
                <a href="{{ route('roles.user.create') }}" class="bg-indigo-600 hover:bg-indigo-700 text-white font-medium py-3 px-4 rounded-lg text-center transition duration-150 ease-in-out block">
                    Crear Usuario
                </a>
                @endcan
                <a href="{{ route('participante.create') }}" class="bg-green-600 hover:bg-green-700 text-white font-medium py-3 px-4 rounded-lg text-center transition duration-150 ease-in-out block">
                    Registrar Participante
                </a>
                <a href="{{ route('asistencia.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white font-medium py-3 px-4 rounded-lg text-center transition duration-150 ease-in-out block">
                    Registrar Asistencia
                </a>
                 <a href="{{ route('asistencia.reporte') }}" class="bg-yellow-600 hover:bg-yellow-700 text-white font-medium py-3 px-4 rounded-lg text-center transition duration-150 ease-in-out block">
                    Reportes Asistencia
                </a>
            </div>

        </div>
    </div>

    {{-- Incluir Chart.js desde CDN --}}
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Datos para el gráfico de Participantes por Programa
            const programData = @json($participantsByProgramData ?? []);
            const programLabels = Object.keys(programData);
            const programCounts = Object.values(programData);

            const programCtx = document.getElementById('participantsByProgramChart');
            if (programCtx && programLabels.length > 0) { 
                new Chart(programCtx, {
                    type: 'bar', // Cambiado a 'bar' para mejor visualización de múltiples programas
                    data: {
                        labels: programLabels,
                        datasets: [{
                            label: 'Nº de Participantes',
                            data: programCounts,
                            backgroundColor: Chart.helpers.color('rgba(54, 162, 235, 0.6)').alpha(0.6).rgbString(), // Color base azul
                            borderColor: 'rgba(54, 162, 235, 1)',
                            borderWidth: 1
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        scales: {
                            y: { 
                                beginAtZero: true, 
                                ticks: { precision: 0 } 
                            }
                        },
                        plugins: {
                            legend: { display: false }, // Leyenda puede ser redundante para un solo dataset
                            title: { display: false }
                        }
                    }
                });
            }

            // Datos para el gráfico de Participantes por Lugar de Encuentro
            const placeData = @json($participantsByPlaceData ?? []);
            const placeLabels = Object.keys(placeData);
            const placeCounts = Object.values(placeData);

            const placeCtx = document.getElementById('participantsByPlaceChart');
            if (placeCtx && placeLabels.length > 0) { 
                new Chart(placeCtx, {
                    type: 'doughnut',
                    data: {
                        labels: placeLabels,
                        datasets: [{
                            label: 'Nº de Participantes',
                            data: placeCounts,
                            backgroundColor: [ // Paleta de colores variada
                                'rgba(255, 99, 132, 0.7)', 'rgba(54, 162, 235, 0.7)',
                                'rgba(255, 206, 86, 0.7)', 'rgba(75, 192, 192, 0.7)',
                                'rgba(153, 102, 255, 0.7)', 'rgba(255, 159, 64, 0.7)',
                                'rgba(201, 203, 207, 0.7)', 'rgba(120, 180, 90, 0.7)' 
                            ],
                            hoverOffset: 4
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: { 
                                position: 'top',
                                labels: {
                                    boxWidth: 12,
                                    padding: 15
                                }
                            },
                            title: { display: false }
                        }
                    }
                });
            }
        });
    </script>
</x-app-layout>

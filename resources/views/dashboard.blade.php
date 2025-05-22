<x-app-layout>
    <x-slot name="header">
        <h2 class="text-2xl lg:text-3xl font-bold text-transparent bg-clip-text bg-gradient-to-r from-indigo-600 via-purple-600 to-pink-500">
            {{ __('Panel de Control') }}
        </h2>
    </x-slot>

    {{-- Main content area with gradient background --}}
    <div class="py-8 bg-gradient-to-br from-slate-50 via-purple-50 to-pink-50 min-h-screen">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            
            {{-- Welcome Banner --}}
            <div class="mb-8">
                <div class="bg-white/70 backdrop-blur-lg shadow-xl rounded-2xl p-6">
                    <h3 class="text-2xl font-semibold text-transparent bg-clip-text bg-gradient-to-r from-indigo-600 via-purple-600 to-pink-500 mb-2">
                        ¡Bienvenido, <span class="font-bold">{{ Auth::user()->name }}</span>!
                    </h3>
                    <p class="text-slate-600 text-sm">
                        Aquí tienes un resumen general de la actividad en el Sistema CREA.
                    </p>
                </div>
            </div>

            {{-- User Statistics Section --}}
            <h3 class="text-xl font-semibold text-slate-700 mb-4 ml-1">Estadísticas de Usuarios</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                {{-- Stat Card: Total Users --}}
                <div class="bg-white/70 backdrop-blur-lg shadow-lg rounded-2xl p-6 flex items-center hover:shadow-xl transition-shadow duration-300">
                    <div class="flex-shrink-0 bg-gradient-to-br from-indigo-500 to-purple-600 rounded-xl p-3.5 mr-4 shadow-md">
                        <svg class="h-8 w-8 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                        </svg>
                    </div>
                    <div>
                        <h4 class="text-sm font-medium text-slate-500">Usuarios Totales</h4>
                        <p class="text-3xl font-bold text-slate-800">{{ $totalUsers ?? 0 }}</p>
                    </div>
                </div>

                {{-- Stat Card: Approved Users --}}
                <div class="bg-white/70 backdrop-blur-lg shadow-lg rounded-2xl p-6 flex items-center hover:shadow-xl transition-shadow duration-300">
                    <div class="flex-shrink-0 bg-gradient-to-br from-green-500 to-emerald-600 rounded-xl p-3.5 mr-4 shadow-md">
                         <svg class="h-8 w-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    </div>
                    <div>
                        <h4 class="text-sm font-medium text-slate-500">Usuarios Aprobados</h4>
                        <p class="text-3xl font-bold text-slate-800">{{ $approvedUsers ?? 0 }}</p>
                        @can('manage-roles')
                        <a href="{{ route('roles.index', ['approval_status' => 'approved']) }}" class="text-xs text-indigo-600 hover:underline mt-1 block">Ver aprobados</a>
                        @endcan
                    </div>
                </div>

                {{-- Stat Card: Pending Users --}}
                <div class="bg-white/70 backdrop-blur-lg shadow-lg rounded-2xl p-6 flex items-center hover:shadow-xl transition-shadow duration-300">
                    <div class="flex-shrink-0 bg-gradient-to-br from-amber-500 to-orange-600 rounded-xl p-3.5 mr-4 shadow-md">
                        <svg class="h-8 w-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    </div>
                    <div>
                        <h4 class="text-sm font-medium text-slate-500">Usuarios Pendientes</h4>
                        <p class="text-3xl font-bold text-slate-800">{{ $pendingUsers ?? 0 }}</p>
                         @can('manage-roles')
                        <a href="{{ route('roles.index', ['approval_status' => 'pending']) }}" class="text-xs text-indigo-600 hover:underline mt-1 block">Gestionar pendientes</a>
                        @endcan
                    </div>
                </div>

                {{-- Stat Card: Users by Role --}}
                <div class="bg-white/70 backdrop-blur-lg shadow-lg rounded-2xl p-6 hover:shadow-xl transition-shadow duration-300">
                    <div class="flex items-center mb-3">
                        <div class="flex-shrink-0 bg-gradient-to-br from-purple-500 to-fuchsia-600 rounded-xl p-3.5 mr-4 shadow-md">
                            <svg class="h-8 w-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 11c0-1.104-.896-2-2-2s-2 .896-2 2c0 .738.402 1.376 1 1.723V15a1 1 0 001 1h2a1 1 0 001-1v-2.277c.598-.347 1-.985 1-1.723zm8 0c0-1.104-.896-2-2-2s-2 .896-2 2c0 .738.402 1.376 1 1.723V15a1 1 0 001 1h2a1 1 0 001-1v-2.277c.598-.347 1-.985 1-1.723zM6 3a3 3 0 00-3 3v12a3 3 0 003 3h12a3 3 0 003-3V6a3 3 0 00-3-3H6z"></path></svg>
                        </div>
                        <div>
                            <h4 class="text-sm font-medium text-slate-500">Usuarios por Rol</h4>
                             <p class="text-xl font-semibold text-slate-800">Admins: {{ $adminUsers ?? 0 }}</p>
                        </div>
                    </div>
                    <div class="text-xs text-slate-500 ml-1 space-y-0.5">
                        <p>Editores: {{ $editorUsers ?? 0 }}</p>
                        <p>Gestores: {{ $gestorUsers ?? 0 }}</p>
                        <p>Usuarios: {{ $standardUsers ?? 0 }}</p>
                    </div>
                    @can('manage-roles')
                    <a href="{{ route('roles.index') }}" class="text-xs text-indigo-600 hover:underline mt-2 block">Gestionar Roles</a>
                    @endcan
                </div>
            </div>

            {{-- Participant Statistics Section --}}
            <h3 class="text-xl font-semibold text-slate-700 mb-4 ml-1">Estadísticas de Participantes</h3>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                {{-- Stat Card: Total Enrolled --}}
                <div class="bg-white/70 backdrop-blur-lg shadow-lg rounded-2xl p-6 flex items-center hover:shadow-xl transition-shadow duration-300">
                    <div class="flex-shrink-0 bg-gradient-to-br from-sky-500 to-cyan-600 rounded-xl p-3.5 mr-4 shadow-md">
                        <svg class="h-8 w-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                        </svg>
                    </div>
                    <div>
                        <h4 class="text-sm font-medium text-slate-500">Total Inscritos</h4>
                        <p class="text-3xl font-bold text-slate-800">{{ $totalParticipants ?? 0 }}</p>
                        <a href="{{ route('participante.index') }}" class="text-xs text-sky-600 hover:underline mt-1 block">Ver Participantes</a>
                    </div>
                </div>

                {{-- Stat Card: Active Programs --}}
                <div class="bg-white/70 backdrop-blur-lg shadow-lg rounded-2xl p-6 flex items-center hover:shadow-xl transition-shadow duration-300">
                    <div class="flex-shrink-0 bg-gradient-to-br from-teal-500 to-emerald-600 rounded-xl p-3.5 mr-4 shadow-md">
                        <svg class="h-8 w-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                        </svg>
                    </div>
                    <div>
                        <h4 class="text-sm font-medium text-slate-500">Programas Activos</h4>
                        <p class="text-3xl font-bold text-slate-800">{{ count($participantsByProgramData ?? []) }}</p>
                        <a href="{{ route('programas') }}" class="text-xs text-teal-600 hover:underline mt-1 block">Ver Programas</a>
                    </div>
                </div>

                {{-- Stat Card: Registered Tutors --}}
                <div class="bg-white/70 backdrop-blur-lg shadow-lg rounded-2xl p-6 flex items-center hover:shadow-xl transition-shadow duration-300">
                     <div class="flex-shrink-0 bg-gradient-to-br from-orange-500 to-red-600 rounded-xl p-3.5 mr-4 shadow-md">
                         <svg class="h-8 w-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                    </div>
                    <div>
                        <h4 class="text-sm font-medium text-slate-500">Tutores Registrados</h4>
                        <p class="text-3xl font-bold text-slate-800">{{ $tutorsCount ?? 0 }}</p>
                        <a href="{{ route('tutores') }}" class="text-xs text-orange-600 hover:underline mt-1 block">Ver Tutores</a>
                    </div>
                </div>
            </div>

            {{-- Charts Section --}}
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
                <div class="bg-white/70 backdrop-blur-lg shadow-lg rounded-2xl p-6 hover:shadow-xl transition-shadow duration-300">
                    <h3 class="text-lg font-medium text-slate-700 mb-4">Participantes por Programa</h3>
                    <div class="h-[300px] sm:h-[350px]">
                        <canvas id="participantsByProgramChart"></canvas>
                    </div>
                </div>

                <div class="bg-white/70 backdrop-blur-lg shadow-lg rounded-2xl p-6 hover:shadow-xl transition-shadow duration-300">
                    <h3 class="text-lg font-medium text-slate-700 mb-4">Participantes por Lugar de Encuentro</h3>
                    <div class="h-[300px] sm:h-[350px]">
                        <canvas id="participantsByPlaceChart"></canvas>
                    </div>
                </div>
            </div>

            {{-- Tables Section --}}
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
                {{-- Table: Participants by Program --}}
                <div class="bg-white/70 backdrop-blur-lg shadow-lg rounded-2xl p-6 hover:shadow-xl transition-shadow duration-300">
                    <h3 class="text-md font-semibold text-slate-700 mb-4">Tabla: Participantes por Programa</h3>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-slate-200 text-sm">
                            <thead class="bg-slate-100">
                                <tr class="text-xs font-semibold text-slate-600 uppercase tracking-wider">
                                    <th class="px-4 py-3 text-left">Programa</th>
                                    <th class="px-4 py-3 text-right">Inscritos</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-200">
                                @forelse ($participantsByProgramForTable ?? [] as $program => $count)
                                    <tr class="hover:bg-slate-50">
                                        <td class="px-4 py-3 text-slate-800">
                                            <a href="{{ route('participante.index', ['search_programa' => $program]) }}" class="text-indigo-600 hover:text-indigo-800 hover:underline">
                                                {{ $program }}
                                            </a>
                                        </td>
                                        <td class="px-4 py-3 text-right text-slate-600">{{ $count }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="2" class="px-4 py-3 text-slate-500 text-center">No hay datos disponibles</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                {{-- Table: Participants by Meeting Place --}}
                <div class="bg-white/70 backdrop-blur-lg shadow-lg rounded-2xl p-6 hover:shadow-xl transition-shadow duration-300">
                    <h3 class="text-md font-semibold text-slate-700 mb-4">Tabla: Participantes por Lugar de Encuentro</h3>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-slate-200 text-sm">
                            <thead class="bg-slate-100">
                                <tr class="text-xs font-semibold text-slate-600 uppercase tracking-wider">
                                    <th class="px-4 py-3 text-left">Lugar</th>
                                    <th class="px-4 py-3 text-right">Inscritos</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-200">
                                @forelse ($participantsByPlaceForTable ?? [] as $place => $count)
                                    <tr class="hover:bg-slate-50">
                                        <td class="px-4 py-3 text-slate-800">
                                            <a href="{{ route('participante.index', ['search_lugar' => $place]) }}" class="text-indigo-600 hover:text-indigo-800 hover:underline">
                                                {{ $place }}
                                            </a>
                                        </td>
                                        <td class="px-4 py-3 text-right text-slate-600">{{ $count }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="2" class="px-4 py-3 text-slate-500 text-center">No hay datos disponibles</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            {{-- Quick Actions Section --}}
            <h3 class="text-xl font-semibold text-slate-700 mb-4 ml-1 mt-10">Acciones Rápidas</h3>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                @can('create-user')
                <a href="{{ route('roles.user.create') }}" class="block text-center bg-gradient-to-r from-indigo-600 to-purple-600 hover:from-indigo-700 hover:to-purple-700 text-white font-semibold py-3 px-4 rounded-xl shadow-md hover:shadow-lg transition duration-150 ease-in-out">
                    Crear Usuario
                </a>
                @endcan
                <a href="{{ route('participante.create') }}" class="block text-center bg-gradient-to-r from-green-500 to-emerald-600 hover:from-green-600 hover:to-emerald-700 text-white font-semibold py-3 px-4 rounded-xl shadow-md hover:shadow-lg transition duration-150 ease-in-out">
                    Registrar Participante
                </a>
                <a href="{{ route('asistencia.create') }}" class="block text-center bg-gradient-to-r from-sky-500 to-cyan-600 hover:from-sky-600 hover:to-cyan-700 text-white font-semibold py-3 px-4 rounded-xl shadow-md hover:shadow-lg transition duration-150 ease-in-out">
                    Registrar Asistencia
                </a>
                <a href="{{ route('asistencia.reporte') }}" class="block text-center bg-gradient-to-r from-amber-500 to-orange-600 hover:from-amber-600 hover:to-orange-700 text-white font-semibold py-3 px-4 rounded-xl shadow-md hover:shadow-lg transition duration-150 ease-in-out">
                    Reportes Asistencia
                </a>
            </div>
        </div>
    </div>

    {{-- Include Chart.js from CDN --}}
    <script src="https://cdn.jsdelivr.net/npm/chart.js@3.9.1/dist/chart.min.js"></script> {{-- Using a specific Chart.js version for stability --}}
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Helper function to generate a color palette
            const getChartColors = (numColors) => {
                const baseColors = [
                    'rgba(75, 59, 255, 0.7)',  // Indigo
                    'rgba(168, 85, 247, 0.7)', // Purple
                    'rgba(236, 72, 153, 0.7)', // Pink
                    'rgba(59, 130, 246, 0.7)', // Blue
                    'rgba(16, 185, 129, 0.7)', // Emerald
                    'rgba(245, 158, 11, 0.7)', // Amber
                    'rgba(239, 68, 68, 0.7)',  // Red
                    'rgba(34, 197, 94, 0.7)',  // Green
                ];
                let colors = [];
                for (let i = 0; i < numColors; i++) {
                    colors.push(baseColors[i % baseColors.length]);
                }
                return colors;
            };
            
            const chartFontColor = '#374151'; // slate-700
            const gridLineColor = 'rgba(203, 213, 225, 0.5)'; // slate-300 with opacity

            // Chart: Participants by Program
            const programData = @json($participantsByProgramData ?? []);
            const programLabels = Object.keys(programData);
            const programCounts = Object.values(programData);
            const programCtx = document.getElementById('participantsByProgramChart');

            if (programCtx && programLabels.length > 0) { 
                new Chart(programCtx, {
                    type: 'bar',
                    data: {
                        labels: programLabels,
                        datasets: [{
                            label: 'Nº de Participantes',
                            data: programCounts,
                            backgroundColor: getChartColors(programLabels.length),
                            borderColor: getChartColors(programLabels.length).map(color => color.replace('0.7', '1')), // Darker border
                            borderWidth: 1,
                            borderRadius: 6, // Rounded bars
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        scales: {
                            y: { 
                                beginAtZero: true, 
                                ticks: { precision: 0, color: chartFontColor },
                                grid: { color: gridLineColor }
                            },
                            x: {
                                ticks: { color: chartFontColor },
                                grid: { display: false } // Hide x-axis grid lines for cleaner look
                            }
                        },
                        plugins: {
                            legend: { display: false },
                            title: { display: false },
                            tooltip: {
                                backgroundColor: 'rgba(255,255,255,0.9)',
                                titleColor: '#334155', // slate-700
                                bodyColor: '#475569',  // slate-600
                                borderColor: '#e2e8f0', // slate-200
                                borderWidth: 1,
                                padding: 10,
                                cornerRadius: 6,
                            }
                        }
                    }
                });
            }

            // Chart: Participants by Meeting Place
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
                            backgroundColor: getChartColors(placeLabels.length),
                            hoverOffset: 8, // Increased hover offset
                            borderColor: '#fff', // White border for segments
                            borderWidth: 2,
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: { 
                                position: 'bottom',
                                labels: {
                                    boxWidth: 12,
                                    padding: 20,
                                    color: chartFontColor,
                                    font: { size: 11 }
                                }
                            },
                            title: { display: false },
                            tooltip: {
                                backgroundColor: 'rgba(255,255,255,0.9)',
                                titleColor: '#334155',
                                bodyColor: '#475569',
                                borderColor: '#e2e8f0',
                                borderWidth: 1,
                                padding: 10,
                                cornerRadius: 6,
                            }
                        }
                    }
                });
            }
        });
    </script>
</x-app-layout>

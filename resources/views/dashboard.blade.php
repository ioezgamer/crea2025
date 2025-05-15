<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-8 bg-gray-50 min-h-screen">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="mb-6">
                <div class="bg-white shadow-sm rounded-lg p-6">
                    <p class="text-gray-900 text-sm">¡Bienvenido! Aquí tienes un resumen de los datos de inscripción.</p>
                </div>
            </div>

            {{-- Sección de Totales --}}
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                <div class="bg-white shadow-sm rounded-lg p-6 flex items-center">
                    <div class="flex-shrink-0">
                        <svg class="h-10 w-10 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <h3 class="text-sm font-medium text-gray-600">Total Inscritos</h3>
                        <p class="text-2xl font-semibold text-gray-900">{{ $totalParticipants ?? 0 }}</p>
                    </div>
                </div>

                <div class="bg-white shadow-sm rounded-lg p-6 flex items-center">
                    <div class="flex-shrink-0">
                        <svg class="h-10 w-10 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <h3 class="text-sm font-medium text-gray-600">Programas Distintos</h3>
                        <p class="text-2xl font-semibold text-gray-900">{{ count($participantsByProgramData ?? []) }}</p>
                    </div>
                </div>

                <div class="bg-white shadow-sm rounded-lg p-6 flex items-center">
                    <div class="flex-shrink-0">
                        <svg class="h-10 w-10 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <h3 class="text-sm font-medium text-gray-600">Lugares de Encuentro</h3>
                        <p class="text-2xl font-semibold text-gray-900">{{ count($participantsByPlaceData ?? []) }}</p>
                    </div>
                </div>
            </div>

            {{-- Sección de Gráficos --}}
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
                <div class="bg-white shadow-sm rounded-lg p-6">
                    <h3 class="text-lg font-medium text-gray-700 mb-4">Participantes por Programa</h3>
                    <div style="height: 300px;">
                        <canvas id="participantsByProgramChart"></canvas>
                    </div>
                </div>

                <div class="bg-white shadow-sm rounded-lg p-6">
                    <h3 class="text-lg font-medium text-gray-700 mb-4">Participantes por Lugar de Encuentro</h3>
                    <div style="height: 300px;">
                        <canvas id="participantsByPlaceChart"></canvas>
                    </div>
                </div>
            </div>

            {{-- Sección de Tablas con Enlaces --}}
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                {{-- Tabla: Participantes por Programa --}}
                <div class="bg-white shadow-sm rounded-lg p-6">
                    <h3 class="text-sm font-medium text-gray-600 mb-4">Tabla: Participantes por Programa</h3>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200 text-sm">
                            <thead class="bg-gray-100">
                                <tr class="text-xs font-medium text-gray-600 uppercase">
                                    <th class="px-4 py-3 text-left">Programa</th>
                                    <th class="px-4 py-3 text-right">Inscritos</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200">
                                @forelse ($participantsByProgramForTable ?? [] as $program => $count)
                                    <tr class="hover:bg-gray-50">
                                        <td class="px-4 py-3 text-gray-900">
                                            {{-- Enlace para filtrar por programa --}}
                                            <a href="{{ route('participante.index', ['search_programa' => $program]) }}" class="text-indigo-600 hover:text-indigo-800 hover:underline">
                                                {{ $program }}
                                            </a>
                                        </td>
                                        <td class="px-4 py-3 text-right text-gray-600">{{ $count }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="2" class="px-4 py-3 text-gray-500 text-center">No hay datos disponibles</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                {{-- Tabla: Participantes por Lugar de Encuentro --}}
                <div class="bg-white shadow-sm rounded-lg p-6">
                    <h3 class="text-sm font-medium text-gray-600 mb-4">Tabla: Participantes por Lugar de Encuentro</h3>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200 text-sm">
                            <thead class="bg-gray-100">
                                <tr class="text-xs font-medium text-gray-600 uppercase">
                                    <th class="px-4 py-3 text-left">Lugar</th>
                                    <th class="px-4 py-3 text-right">Inscritos</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200">
                                @forelse ($participantsByPlaceForTable ?? [] as $place => $count)
                                    <tr class="hover:bg-gray-50">
                                        <td class="px-4 py-3 text-gray-900">
                                            {{-- Enlace para filtrar por lugar de encuentro --}}
                                            <a href="{{ route('participante.index', ['search_lugar' => $place]) }}" class="text-indigo-600 hover:text-indigo-800 hover:underline">
                                                {{ $place }}
                                            </a>
                                        </td>
                                        <td class="px-4 py-3 text-right text-gray-600">{{ $count }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="2" class="px-4 py-3 text-gray-500 text-center">No hay datos disponibles</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
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
            if (programCtx && programLabels.length > 0) { // Asegurarse que hay datos para el gráfico
                new Chart(programCtx, {
                    type: 'bar',
                    data: {
                        labels: programLabels,
                        datasets: [{
                            label: 'Nº de Participantes',
                            data: programCounts,
                            backgroundColor: [
                                'rgba(54, 162, 235, 0.6)', 'rgba(75, 192, 192, 0.6)',
                                'rgba(255, 206, 86, 0.6)', 'rgba(153, 102, 255, 0.6)',
                                'rgba(255, 159, 64, 0.6)', 'rgba(255, 99, 132, 0.6)',
                                'rgba(201, 203, 207, 0.6)'
                            ],
                            borderColor: [
                                'rgba(54, 162, 235, 1)', 'rgba(75, 192, 192, 1)',
                                'rgba(255, 206, 86, 1)', 'rgba(153, 102, 255, 1)',
                                'rgba(255, 159, 64, 1)', 'rgba(255, 99, 132, 1)',
                                'rgba(201, 203, 207, 1)'
                            ],
                            borderWidth: 1
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        scales: {
                            y: { beginAtZero: true, ticks: { precision: 0 } }
                        },
                        plugins: {
                            legend: { display: true, position: 'top' },
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
            if (placeCtx && placeLabels.length > 0) { // Asegurarse que hay datos para el gráfico
                new Chart(placeCtx, {
                    type: 'doughnut',
                    data: {
                        labels: placeLabels,
                        datasets: [{
                            label: 'Nº de Participantes',
                            data: placeCounts,
                            backgroundColor: [
                                'rgba(255, 99, 132, 0.7)', 'rgba(54, 162, 235, 0.7)',
                                'rgba(255, 206, 86, 0.7)', 'rgba(75, 192, 192, 0.7)',
                                'rgba(153, 102, 255, 0.7)', 'rgba(255, 159, 64, 0.7)',
                                'rgba(100, 100, 100, 0.7)', 'rgba(120, 180, 90, 0.7)'
                            ],
                            hoverOffset: 4
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: { position: 'top' },
                            title: { display: false }
                        }
                    }
                });
            }
        });
    </script>
</x-app-layout>

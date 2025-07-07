<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between w-full px-4 py-6 mx-auto max-w-7xl sm:px-6 lg:px-8">
            <x-back-link :href="route('tutores')" text="Dashboard de Tutores" />

        </div>

    </x-slot>

    <div class="min-h-screen py-8 bg-white dark:from-slate-900 dark:via-purple-900/20 dark:to-pink-900/20">
        <div class="px-4 mx-auto max-w-7xl sm:px-6 lg:px-8">
            {{-- Filter and Summary Banner --}}
            <div class="mb-8">
                <div class="p-6 shadow-md bg-white/70 hover:shadow-lg transition-shadow duration-500 rounded-3xl">
                    <div class="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">
                        <div>
                            <h3 class="mb-1 text-lg font-semibold text-slate-800 dark:text-slate-100">
                                {{ ($selectedProgram ?? null) || ($selectedPlace ?? null) ? 'Métricas para tutores' . (($selectedProgram ?? null) ? " del programa: " . e($selectedProgram) : '') . (($selectedPlace ?? null) ? " en el lugar: " . e($selectedPlace) : '') : 'Resumen General de Métricas de Tutores' }}
                            </h3>
                            <p class="text-sm text-slate-600 dark:text-slate-400">
                                Analiza las estadísticas clave relacionadas con los tutores.
                            </p>
                        </div>
                        {{-- Filter Controls --}}
                        <div class="flex flex-col w-full gap-3 sm:flex-row sm:items-end sm:space-x-3 lg:w-auto sm:gap-0 relative z-10">
                                                    {{-- Custom Select for Programa --}}
                                <div class="flex-grow sm:flex-grow-0">
                                    <label for="programa_filter" class="block mb-1 text-xs font-medium text-slate-700 dark:text-slate-300">Programa:</label>
                                    <div x-data="{ open: false, selected: '{{ $selectedProgram ?? '' }}' }" class="relative" style="min-width: 180px;">
                                        <button @click="open = !open" type="button" class="relative w-full py-2.5 pl-3 pr-10 text-left bg-white border border-slate-300 dark:border-slate-600 dark:bg-slate-700 dark:text-slate-300 rounded-xl shadow-sm cursor-default focus:outline-none focus:ring-2 focus:ring-indigo-500 sm:text-sm">
                                            <span class="block truncate" x-text="selected || 'Todos'"></span>
                                            <span class="absolute inset-y-0 right-0 flex items-center pr-2 pointer-events-none">
                                                <svg class="w-5 h-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M10 3a.75.75 0 01.53.22l3.5 3.5a.75.75 0 01-1.06 1.06L10 4.81 6.53 8.28a.75.75 0 01-1.06-1.06l3.5-3.5A.75.75 0 0110 3zm-3.72 9.53a.75.75 0 011.06 0L10 15.19l3.47-3.47a.75.75 0 111.06 1.06l-4 4a.75.75 0 01-1.06 0l-4-4a.75.75 0 010-1.06z" clip-rule="evenodd" /></svg>
                                            </span>
                                        </button>
                                        <div x-show="open" @click.away="open = false" x-transition class="absolute z-20 w-full mt-1 overflow-auto text-base bg-white rounded-md shadow-lg dark:bg-slate-800 max-h-60 ring-1 ring-black ring-opacity-5 focus:outline-none sm:text-sm" style="display: none;">
                                            <div @click="selected = ''; open = false;" :class="{'bg-indigo-600 text-white': selected === ''}" class="relative py-2 pl-3 pr-9 cursor-default select-none hover:bg-indigo-600 hover:text-white text-gray-900 dark:text-white">
                                                <span class="block truncate" :class="{'font-semibold': selected === ''}">Todos</span>
                                            </div>
                                            @foreach ($programOptions ?? [] as $program)
                                                <div @click="selected = '{{ $program }}'; open = false;" :class="{'bg-indigo-600 text-white': selected === '{{ $program }}'}" class="relative py-2 pl-3 pr-9 cursor-default select-none hover:bg-indigo-600 hover:text-white text-gray-900 dark:text-white">
                                                    <span class="block truncate" :class="{'font-semibold': selected === '{{ $program }}'}">{{ $program }}</span>
                                                </div>
                                            @endforeach
                                        </div>
                                        <select id="programa_filter" name="programa_filter" x-model="selected" class="hidden">
                                            <option value="">Todos</option>
                                            @foreach ($programOptions ?? [] as $program)
                                                <option value="{{ $program }}">{{ $program }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                {{-- Custom Select for Lugar --}}
                                <div class="flex-grow sm:flex-grow-0">
                                    <label for="lugar_filter" class="block mb-1 text-xs font-medium text-slate-700 dark:text-slate-300">Lugar:</label>
                                    <div x-data="{ open: false, selected: '{{ $selectedPlace ?? '' }}' }" class="relative" style="min-width: 180px;">
                                        <button @click="open = !open" type="button" class="relative w-full py-2.5 pl-3 pr-10 text-left bg-white border border-slate-300 dark:border-slate-600 dark:bg-slate-700 dark:text-slate-300 rounded-xl shadow-sm cursor-default focus:outline-none focus:ring-2 focus:ring-indigo-500 sm:text-sm">
                                            <span class="block truncate" x-text="selected || 'Todos'"></span>
                                            <span class="absolute inset-y-0 right-0 flex items-center pr-2 pointer-events-none">
                                                <svg class="w-5 h-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M10 3a.75.75 0 01.53.22l3.5 3.5a.75.75 0 01-1.06 1.06L10 4.81 6.53 8.28a.75.75 0 01-1.06-1.06l3.5-3.5A.75.75 0 0110 3zm-3.72 9.53a.75.75 0 011.06 0L10 15.19l3.47-3.47a.75.75 0 111.06 1.06l-4 4a.75.75 0 01-1.06 0l-4-4a.75.75 0 010-1.06z" clip-rule="evenodd" /></svg>
                                            </span>
                                        </button>
                                        <div x-show="open" @click.away="open = false" x-transition class="absolute z-10 w-full mt-1 overflow-auto text-base bg-white rounded-md shadow-lg dark:bg-slate-800 max-h-60 ring-1 ring-black ring-opacity-5 focus:outline-none sm:text-sm" style="display: none;">
                                            <div @click="selected = ''; open = false;" :class="{'bg-indigo-600 text-white': selected === ''}" class="relative py-2 pl-3 pr-9 cursor-default select-none hover:bg-indigo-600 hover:text-white text-gray-900 dark:text-white">
                                                <span class="block truncate" :class="{'font-semibold': selected === ''}">Todos</span>
                                            </div>
                                            @foreach ($placeOptions ?? [] as $place)
                                                <div @click="selected = '{{ $place }}'; open = false;" :class="{'bg-indigo-600 text-white': selected === '{{ $place }}'}" class="relative py-2 pl-3 pr-9 cursor-default select-none hover:bg-indigo-600 hover:text-white text-gray-900 dark:text-white">
                                                    <span class="block truncate" :class="{'font-semibold': selected === '{{ $place }}'}">{{ $place }}</span>
                                                </div>
                                            @endforeach
                                        </div>
                                        <select id="lugar_filter" name="lugar_filter" x-model="selected" class="hidden">
                                            <option value="">Todos</option>
                                            @foreach ($placeOptions ?? [] as $place)
                                                <option value="{{ $place }}">{{ $place }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            <div class="w-full pt-2 sm:w-auto sm:pt-0">
                                <button id="applyFilters" class="w-full justify-center inline-flex items-center px-5 py-2.5 bg-gradient-to-r from-indigo-600 to-purple-600 border border-transparent rounded-xl font-semibold text-xs text-white uppercase tracking-widest hover:from-indigo-700 hover:to-purple-700 focus:outline-none focus:ring-2 focus:ring-purple-500 focus:ring-offset-2 dark:focus:ring-offset-slate-900 transition ease-in-out duration-150 shadow-md hover:shadow-lg">
                                    <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                                    Aplicar
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Key Metrics Section --}}
            <div class="grid grid-cols-1 gap-6 mb-8 sm:grid-cols-2 lg:grid-cols-3">
                {{-- Card: Total Tutores --}}
                <div class="flex items-center p-6 transition-shadow duration-300 shadow-lg bg-white/70 dark:bg-slate-800/80 backdrop-blur-lg rounded-2xl hover:shadow-xl">
                    <div class="flex-shrink-0 bg-gradient-to-br from-indigo-500 to-purple-600 rounded-xl p-3.5 mr-4 shadow-md">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                    </div>
                    <div>
                        <h4 class="text-sm font-medium text-slate-500 dark:text-slate-400">Total Tutores Únicos</h4>
                        <p class="text-3xl font-bold text-slate-800 dark:text-slate-100">{{ $totalTutors ?? 0 }}</p>
                    </div>
                </div>
                {{-- Card: Programas con Tutores --}}
                <div class="flex items-center p-6 transition-shadow duration-300 shadow-lg bg-white/70 dark:bg-slate-800/80 backdrop-blur-lg rounded-2xl hover:shadow-xl">
                   <div class="flex-shrink-0 bg-gradient-to-br from-green-500 to-emerald-600 rounded-xl p-3.5 mr-4 shadow-md">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                    </div>
                    <div>
                        <h4 class="text-sm font-medium text-slate-500 dark:text-slate-400">Programas con Tutores</h4>
                        <p class="text-3xl font-bold text-slate-800 dark:text-slate-100">{{ count($tutorsByProgram ?? []) }}</p>
                    </div>
                </div>
                {{-- Card: Promedio Participantes --}}
                <div class="flex items-center p-6 transition-shadow duration-300 shadow-lg bg-white/70 dark:bg-slate-800/80 backdrop-blur-lg rounded-2xl hover:shadow-xl">
                    <div class="flex-shrink-0 bg-gradient-to-br from-sky-500 to-cyan-600 rounded-xl p-3.5 mr-4 shadow-md">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9.5a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0zM9 9.5a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0zM12 15a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0zM12 15a2.5 2.5 0 115 0 2.5 2.5 0 01-5 0z"></path></svg>
                    </div>
                    <div>
                        <h4 class="text-sm font-medium text-slate-500 dark:text-slate-400">Prom. Participantes/Tutor</h4>
                        <p class="text-3xl font-bold text-slate-800 dark:text-slate-100">{{ number_format($averageParticipantsPerTutor ?? 0, 1) }}</p>
                    </div>
                </div>
            </div>

            {{-- Charts Section --}}
            <div class="grid grid-cols-1 gap-6 mb-8 lg:grid-cols-5">
                {{-- Bar Chart --}}
                <div class="p-6 transition-shadow duration-300 shadow-lg lg:col-span-3 bg-white/70 dark:bg-slate-800/80 backdrop-blur-lg rounded-2xl hover:shadow-xl">
                    <h3 class="mb-4 text-lg font-semibold text-slate-800 dark:text-slate-100">Tutores por Programa</h3>
                    <div class="h-80"><canvas id="tutorsByProgramChart"></canvas></div>
                </div>
                {{-- Doughnut Chart --}}
                <div class="p-6 transition-shadow duration-300 shadow-lg lg:col-span-2 bg-white/70 dark:bg-slate-800/80 backdrop-blur-lg rounded-2xl hover:shadow-xl">
                    <h3 class="mb-4 text-lg font-semibold text-slate-800 dark:text-slate-100">Tutores por Sector Económico</h3>
                    <div class="h-80"><canvas id="tutorsBySectorChart"></canvas></div>
                </div>
            </div>

            {{-- Detailed Tables Section --}}
            <div class="grid grid-cols-1 gap-6 lg:grid-cols-2">
                {{-- Top Tutors Table --}}
                <div class="p-6 transition-shadow duration-300 shadow-lg bg-white/70 backdrop-blur-lg rounded-3xl hover:shadow-xl">
                    <h3 class="mb-4 text-lg font-semibold text-slate-800 dark:text-slate-100">Top 5 Tutores (por número de participantes)</h3>
                    <div class="overflow-x-auto">
                        <table class="min-w-full text-sm divide-y divide-slate-200">
                            <thead class="bg-slate-100">
                                <tr class="text-xs font-semibold uppercase text-slate-600 ">
                                    <th class="px-4 py-3 text-left">Tutor</th>
                                    <th class="px-4 py-3 text-left">Cédula</th>
                                    <th class="px-4 py-3 text-left"># Participantes</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-slate-200 ">
                                @forelse ($topTutors ?? [] as $tutor)
                                    <tr class="hover:bg-sky-50/70 ">
                                        <td class="px-1.5 py-3 text-slate-800">
                                            {{ is_object($tutor) ? $tutor->nombres_y_apellidos_tutor_principal : '' }}
                                        </td>
                                        <td class="px-4 py-3 text-slate-600 ">
                                            {{ is_object($tutor) ? $tutor->numero_de_cedula_tutor : '' }}
                                        </td>
                                        <td class="px-2 py-3 text-right text-indigo-600">
                                            {{ is_object($tutor) ? $tutor->participant_count : '' }}
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="3" class="px-1.5 py-8 text-center text-slate-500 ">No hay datos para la selección actual.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                {{-- Education Level Table --}}
                <div class="p-6 transition-shadow duration-300 shadow-lg bg-white/70 dark:bg-slate-800/80 backdrop-blur-lg rounded-3xl hover:shadow-xl">
                    <h3 class="mb-4 text-lg font-semibold text-slate-800 dark:text-slate-100">Tutores por nivel educativo</h3>
                    <div class="overflow-x-auto ">
                        <table class="min-w-full text-sm divide-y divide-slate-200 dark:divide-slate-700">
                             <thead class="sticky top-0 bg-slate-100 dark:bg-slate-700/50">
                                <tr class="text-xs font-semibold tracking-wider uppercase text-slate-600 dark:text-slate-300">
                                    <th class="px-4 py-3 text-left">Nivel de Educación</th>
                                    <th class="px-4 py-3 text-right">Tutores</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y dark:bg-slate-800 divide-slate-200 dark:divide-slate-700">
                                @forelse ($tutorsByEducationLevel ?? [] as $level => $count)
                                    <tr class="hover:bg-sky-50/70 dark:hover:bg-slate-700/30">
                                        <td class="px-4 py-3 text-slate-800 dark:text-slate-200">{{ $level ?: 'No especificado' }}</td>
                                        <td class="px-4 py-3 font-medium text-right text-slate-700 dark:text-slate-200">{{ $count }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="2" class="px-4 py-8 text-center text-slate-500 dark:text-slate-400">No hay datos disponibles.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Scripts --}}
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Filter Logic
            const programaSelect = document.getElementById('programa_filter');
            const lugarSelect = document.getElementById('lugar_filter');
            const applyFiltersButton = document.getElementById('applyFilters');
            const initialSelectedLugar = "{{ $selectedPlace ?? '' }}";
            const initialSelectedPrograma = "{{ $selectedProgram ?? '' }}";

            function cargarLugares(selectedPrograma, preselectLugar) {
                lugarSelect.innerHTML = '<option value="">Cargando...</option>';
                lugarSelect.disabled = true;

                let fetchUrl = `{{ route('tutores.lugaresPorPrograma') }}?programa=${encodeURIComponent(selectedPrograma || '')}`;

                fetch(fetchUrl)
                    .then(response => response.ok ? response.json() : Promise.reject('Network response was not ok.'))
                    .then(data => {
                        lugarSelect.innerHTML = '<option value="">Todos los lugares</option>';
                        if (Array.isArray(data)) {
                            data.forEach(lugar => {
                                const option = document.createElement('option');
                                option.value = lugar;
                                option.textContent = lugar;
                                if (lugar === preselectLugar) {
                                    option.selected = true;
                                }
                                lugarSelect.appendChild(option);
                            });
                        }
                        lugarSelect.disabled = false;
                    })
                    .catch(error => {
                        console.error('Error fetching places:', error);
                        lugarSelect.innerHTML = '<option value="">Error al cargar</option>';
                    });
            }

            if (initialSelectedPrograma || initialSelectedLugar) {
                 cargarLugares(initialSelectedPrograma, initialSelectedLugar);
            } else {
                 cargarLugares(null, null);
            }

            programaSelect.addEventListener('change', function () {
                lugarSelect.value = "";
                cargarLugares(this.value, "");
            });

            applyFiltersButton.addEventListener('click', function() {
                const params = new URLSearchParams();
                if (programaSelect.value) params.set('programa', programaSelect.value);
                if (lugarSelect.value) params.set('lugar', lugarSelect.value);
                window.location.href = `{{ route('tutores') }}?${params.toString()}`;
            });

            // Chart Logic
            const isDarkMode = document.documentElement.classList.contains('dark');
            const textColor = isDarkMode ? 'rgba(226, 232, 240, 0.8)' : 'rgba(51, 65, 85, 0.8)';
            const gridColor = isDarkMode ? 'rgba(255, 255, 255, 0.1)' : 'rgba(0, 0, 0, 0.1)';

            // Chart 1: Tutors by Program
            const tutorsByProgramCtx = document.getElementById('tutorsByProgramChart');
            if (tutorsByProgramCtx) {
                const tutorsByProgramData = @json($tutorsByProgram ?? []);
                new Chart(tutorsByProgramCtx, {
                    type: 'bar',
                    data: {
                        labels: Object.keys(tutorsByProgramData),
                        datasets: [{
                            label: 'Nº de Tutores',
                            data: Object.values(tutorsByProgramData),
                            backgroundColor: 'rgba(99, 102, 241, 0.7)',
                            borderColor: 'rgba(99, 102, 241, 1)',
                            borderWidth: 1,
                            borderRadius: 5,
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: { legend: { display: false } },
                        scales: {
                            y: { beginAtZero: true, ticks: { color: textColor }, grid: { color: gridColor } },
                            x: { ticks: { color: textColor }, grid: { display: false } }
                        }
                    }
                });
            }

            // Chart 2: Tutors by Economic Sector
            const tutorsBySectorCtx = document.getElementById('tutorsBySectorChart');
            if (tutorsBySectorCtx) {
                const tutorsBySectorData = @json($tutorsBySector ?? []);
                new Chart(tutorsBySectorCtx, {
                    type: 'doughnut',
                    data: {
                        labels: Object.keys(tutorsBySectorData),
                        datasets: [{
                            label: 'Tutores',
                            data: Object.values(tutorsBySectorData),
                            backgroundColor: [
                                'rgba(52, 211, 153, 0.7)', 'rgba(251, 146, 60, 0.7)', 'rgba(239, 68, 68, 0.7)',
                                'rgba(168, 85, 247, 0.7)', 'rgba(59, 130, 246, 0.7)', 'rgba(236, 72, 153, 0.7)'
                            ],
                            borderColor: isDarkMode ? 'rgba(30, 41, 59, 1)' : 'rgba(255, 255, 255, 1)',
                            borderWidth: 2,
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: { position: 'bottom', labels: { color: textColor } }
                        }
                    }
                });
            }
        });
    </script>
     <style>
        /* Custom scrollbar for Webkit browsers */
        .overflow-x-auto::-webkit-scrollbar { height: 8px; }
        .overflow-x-auto::-webkit-scrollbar-track { background: #f1f5f9; @apply dark:bg-slate-700/50; border-radius: 10px; }
        .overflow-x-auto::-webkit-scrollbar-thumb { background: #cbd5e1; @apply dark:bg-slate-600; border-radius: 10px; }
        .overflow-x-auto::-webkit-scrollbar-thumb:hover { background: #94a3b8; @apply dark:bg-slate-500; }
    </style>
</x-app-layout>

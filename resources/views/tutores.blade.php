<x-app-layout>
    <x-slot name="header">

        <div class="flex items-center justify-between w-full px-4 py-6 mx-auto max-w-7xl sm:px-6 lg:px-8">
            {{-- El texto del header ahora tiene una variante dark --}}
            <h2 class="text-2xl font-bold text-transparent lg:text-3xl bg-clip-text bg-gradient-to-r from-indigo-600 via-purple-600 to-pink-500">
            {{ __('Estadísticas de Tutores') }}
        </h2>
        </div>
    </x-slot>

    <div class="min-h-screen py-8 bg-gradient-to-br from-slate-50 via-purple-50 to-pink-50 dark:from-slate-800 dark:via-purple-900 dark:to-pink-900">
        <div class="px-4 mx-auto max-w-7xl sm:px-6 lg:px-8">
            {{-- Filter and Summary Banner --}}
            <div class="mb-8">
                <div class="p-6 shadow-xl bg-white/70 dark:bg-slate-800/80 backdrop-blur-lg rounded-2xl">
                    <div class="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">
                        <div>
                            <h3 class="mb-1 text-lg font-semibold text-slate-800 dark:text-slate-100">
                                {{ ($selectedProgram ?? null) || ($selectedPlace ?? null) ? 'Métricas para tutores' . (($selectedProgram ?? null) ? " del programa: " . e($selectedProgram) : '') . (($selectedPlace ?? null) ? " en el lugar: " . e($selectedPlace) : '') : 'Resumen General de Métricas de Tutores' }}
                            </h3>
                            <p class="text-sm text-slate-600 dark:text-slate-400">
                                Analiza las estadísticas clave relacionadas con los tutores.
                            </p>
                        </div>
                        <div class="flex flex-col w-full gap-3 sm:flex-row sm:items-end sm:space-x-3 lg:w-auto sm:gap-0">
                            <div class="flex-grow sm:flex-grow-0">
                                <label for="programa_filter" class="block mb-1 text-xs font-medium text-slate-700 dark:text-slate-300">Programa:</label>
                                <select id="programa_filter" name="programa_filter" class="block w-full border-slate-300 dark:border-slate-600 dark:bg-slate-700 dark:text-slate-300 rounded-xl shadow-sm focus:ring-indigo-500 dark:focus:ring-indigo-600 focus:border-indigo-500 dark:focus:border-indigo-600 text-sm py-2.5 pl-3 pr-8 transition duration-150 ease-in-out" style="min-width: 180px;">
                                    <option value="">Todos</option>
                                    @foreach ($programOptions ?? [] as $program)
                                        <option value="{{ $program }}" {{ ($selectedProgram ?? '') === $program ? 'selected' : '' }}>{{ $program }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="flex-grow sm:flex-grow-0">
                                <label for="lugar_filter" class="block mb-1 text-xs font-medium text-slate-700 dark:text-slate-300">Lugar:</label>
                                <select id="lugar_filter" name="lugar_filter" class="block w-full border-slate-300 dark:border-slate-600 dark:bg-slate-700 dark:text-slate-300 rounded-xl shadow-sm focus:ring-indigo-500 dark:focus:ring-indigo-600 focus:border-indigo-500 dark:focus:border-indigo-600 text-sm py-2.5 pl-3 pr-8 transition duration-150 ease-in-out" style="min-width: 180px;">
                                    <option value="">Todos</option>
                                    @foreach ($placeOptions ?? [] as $place)
                                        <option value="{{ $place }}" {{ ($selectedPlace ?? '') === $place ? 'selected' : '' }}>{{ $place }}</option>
                                    @endforeach
                                </select>
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
            <div class="grid grid-cols-1 gap-6 mb-8 md:grid-cols-3">
                <div class="flex items-center p-6 transition-shadow duration-300 shadow-lg bg-white/70 dark:bg-slate-800/80 backdrop-blur-lg rounded-2xl hover:shadow-xl">
                    <div class="flex-shrink-0 bg-gradient-to-br from-indigo-500 to-purple-600 rounded-xl p-3.5 mr-4 shadow-md">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                    </div>
                    <div class="ml-1">
                        <h4 class="text-sm font-medium text-slate-500 dark:text-slate-400">Total Tutores Únicos</h4>
                        <p class="text-3xl font-bold text-slate-800 dark:text-slate-100">{{ $totalTutors ?? 0 }}</p>
                    </div>
                </div>
                <div class="flex items-center p-6 transition-shadow duration-300 shadow-lg bg-white/70 dark:bg-slate-800/80 backdrop-blur-lg rounded-2xl hover:shadow-xl">
                   <div class="flex-shrink-0 bg-gradient-to-br from-green-500 to-emerald-600 rounded-xl p-3.5 mr-4 shadow-md">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                    </div>
                    <div class="ml-1">
                        <h4 class="text-sm font-medium text-slate-500 dark:text-slate-400">Programas con Tutores</h4>
                        <p class="text-3xl font-bold text-slate-800 dark:text-slate-100">{{ count($tutorsByProgram ?? []) }}</p>
                    </div>
                </div>
                <div class="flex items-center p-6 transition-shadow duration-300 shadow-lg bg-white/70 dark:bg-slate-800/80 backdrop-blur-lg rounded-2xl hover:shadow-xl">
                    <div class="flex-shrink-0 bg-gradient-to-br from-sky-500 to-cyan-600 rounded-xl p-3.5 mr-4 shadow-md">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path></svg>
                    </div>
                    <div class="ml-1">
                        <h4 class="text-sm font-medium text-slate-500 dark:text-slate-400">Prom. Participantes/Tutor</h4>
                        <p class="text-3xl font-bold text-slate-800 dark:text-slate-100">{{ number_format($averageParticipantsPerTutor ?? 0, 1) }}</p>
                    </div>
                </div>
            </div>

            {{-- Detailed Tables Section --}}
            <div class="grid grid-cols-1 gap-6 lg:grid-cols-2">
                <div class="p-6 transition-shadow duration-300 shadow-lg bg-white/70 dark:bg-slate-800/80 backdrop-blur-lg rounded-2xl hover:shadow-xl">
                    <h3 class="mb-4 text-lg font-semibold text-slate-800 dark:text-slate-100">Tutores por Programa</h3>
                    <div class="overflow-x-auto">
                        <table class="min-w-full text-sm divide-y divide-slate-200 dark:divide-slate-700">
                            <thead class="bg-slate-100 dark:bg-slate-700/50">
                                <tr class="text-xs font-semibold tracking-wider uppercase text-slate-600 dark:text-slate-300">
                                    <th class="px-4 py-3 text-left">Programa</th>
                                    <th class="px-4 py-3 text-right">Tutores Únicos</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y dark:bg-slate-800 divide-slate-200 dark:divide-slate-700">
                                @forelse ($tutorsByProgram ?? [] as $program => $count)
                                    <tr class="hover:bg-sky-50/70 dark:hover:bg-slate-700/30">
                                        <td class="px-4 py-3 text-slate-800 dark:text-slate-200">{{ $program }}</td>
                                        <td class="px-4 py-3 font-medium text-right text-slate-700 dark:text-slate-200">{{ $count }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="2" class="px-4 py-8 text-center text-slate-500 dark:text-slate-400">No hay datos para la selección actual.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="p-6 transition-shadow duration-300 shadow-lg bg-white/70 dark:bg-slate-800/80 backdrop-blur-lg rounded-2xl hover:shadow-xl">
                    <h3 class="mb-4 text-lg font-semibold text-slate-800 dark:text-slate-100">Tutores por Sector Económico</h3>
                    <div class="overflow-x-auto">
                        <table class="min-w-full text-sm divide-y divide-slate-200 dark:divide-slate-700">
                            <thead class="bg-slate-100 dark:bg-slate-700/50">
                                <tr class="text-xs font-semibold tracking-wider uppercase text-slate-600 dark:text-slate-300">
                                    <th class="px-4 py-3 text-left">Sector Económico</th>
                                    <th class="px-4 py-3 text-right">Tutores</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y dark:bg-slate-800 divide-slate-200 dark:divide-slate-700">
                                @forelse ($tutorsBySector ?? [] as $sector => $count)
                                    <tr class="hover:bg-sky-50/70 dark:hover:bg-slate-700/30">
                                        <td class="px-4 py-3 text-slate-800 dark:text-slate-200">{{ $sector ?: 'No especificado' }}</td>
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

                <div class="p-6 transition-shadow duration-300 shadow-lg bg-white/70 dark:bg-slate-800/80 backdrop-blur-lg rounded-2xl hover:shadow-xl">
                    <h3 class="mb-4 text-lg font-semibold text-slate-800 dark:text-slate-100">Tutores por Nivel de Educación</h3>
                    <div class="overflow-x-auto">
                        <table class="min-w-full text-sm divide-y divide-slate-200 dark:divide-slate-700">
                            <thead class="bg-slate-100 dark:bg-slate-700/50">
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

                <div class="p-6 transition-shadow duration-300 shadow-lg bg-white/70 dark:bg-slate-800/80 backdrop-blur-lg rounded-2xl hover:shadow-xl">
                    <h3 class="mb-4 text-lg font-semibold text-slate-800 dark:text-slate-100">Tutores por Comunidad</h3>
                    <div class="overflow-x-auto">
                        <table class="min-w-full text-sm divide-y divide-slate-200 dark:divide-slate-700">
                            <thead class="bg-slate-100 dark:bg-slate-700/50">
                                <tr class="text-xs font-semibold tracking-wider uppercase text-slate-600 dark:text-slate-300">
                                    <th class="px-4 py-3 text-left">Comunidad</th>
                                    <th class="px-4 py-3 text-right">Tutores</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y dark:bg-slate-800 divide-slate-200 dark:divide-slate-700">
                                @forelse ($tutorsByCommunity ?? [] as $community => $count)
                                    <tr class="hover:bg-sky-50/70 dark:hover:bg-slate-700/30">
                                        <td class="px-4 py-3 text-slate-800 dark:text-slate-200">{{ $community ?: 'No especificado' }}</td>
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

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const programaSelect = document.getElementById('programa_filter');
            const lugarSelect = document.getElementById('lugar_filter');
            const applyFiltersButton = document.getElementById('applyFilters');
            const initialSelectedLugar = "{{ $selectedPlace ?? '' }}";
            const initialSelectedPrograma = "{{ $selectedProgram ?? '' }}";

            function cargarLugares(selectedPrograma, preselectLugar) {
                lugarSelect.innerHTML = '<option value="">Cargando lugares...</option>';
                lugarSelect.disabled = true;

                let fetchUrl = `{{ route('tutores.lugaresPorPrograma') }}`;
                if (selectedPrograma) {
                    fetchUrl += `?programa=${encodeURIComponent(selectedPrograma)}`;
                }

                fetch(fetchUrl)
                    .then(response => {
                        if (!response.ok) throw new Error('Error de red al cargar lugares: ' + response.statusText);
                        return response.json();
                    })
                    .then(data => {
                        lugarSelect.innerHTML = '<option value="">Todos los lugares</option>';
                        if (data.error) {
                            console.error('Error del servidor al cargar lugares:', data.error);
                        } else if (Array.isArray(data) && data.length === 0 && selectedPrograma) {
                            // No specific message needed if "Todos los lugares" is the default
                        } else if (Array.isArray(data)) {
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
                        console.error('Error en fetch para lugares:', error);
                        lugarSelect.innerHTML = '<option value="">Error al cargar</option>';
                    });
            }

            if (initialSelectedPrograma) {
                cargarLugares(initialSelectedPrograma, initialSelectedLugar);
            } else {
                cargarLugares(null, initialSelectedLugar);
            }

            programaSelect.addEventListener('change', function () {
                lugarSelect.value = "";
                cargarLugares(this.value, "");
            });

            applyFiltersButton.addEventListener('click', function() {
                updatePageWithFilters(programaSelect.value, lugarSelect.value);
            });

            function updatePageWithFilters(programa, lugar) {
                const params = new URLSearchParams();

                if (programa) {
                    params.set('programa', programa);
                }
                if (lugar) {
                    params.set('lugar', lugar);
                }
                // Ensure the route name 'tutores.stats' or similar is correct for this page
                // If the current page is already the target route, just update query string.
                // Otherwise, use the correct route name.
                let targetRoute = "{{ route('tutores') }}"; // Assuming this is the correct route name for this page
                window.location.href = `${targetRoute}?${params.toString()}`;
            }
        });
    </script>
     <style>
        .text-xxs { font-size: 0.65rem; line-height: 0.85rem; }
        /* Custom scrollbar for Webkit browsers */
        .overflow-x-auto::-webkit-scrollbar { height: 8px; }
        .overflow-x-auto::-webkit-scrollbar-track { background: #f1f5f9; @apply dark:bg-slate-700; border-radius: 10px; }
        .overflow-x-auto::-webkit-scrollbar-thumb { background: #cbd5e1; @apply dark:bg-slate-600; border-radius: 10px; }
        .overflow-x-auto::-webkit-scrollbar-thumb:hover { background: #94a3b8; @apply dark:bg-slate-500; }

        /* Styling for Laravel Pagination to match the theme */
        .pagination span, .pagination a {
            padding: 0.5rem 0.75rem; margin: 0 0.125rem;
            border-radius: 0.5rem; /* rounded-lg */
            font-size: 0.875rem; /* text-sm */
            transition: all 0.15s ease-in-out;
        }
        .pagination span[aria-current="page"] span {
            background-image: linear-gradient(to right, #4f46e5, #8b5cf6); /* default indigo-600 to purple-500 */
            color: white; font-weight: 600; border: 1px solid transparent;
        }
        .dark .pagination span[aria-current="page"] span {
            background-image: linear-gradient(to right, #6366f1, #a78bfa); /* dark: indigo-500 to purple-400 */
        }
        .pagination a[rel="prev"], .pagination a[rel="next"] {
            color: #4b5563; /* slate-600 */
        }
        .dark .pagination a[rel="prev"], .dark .pagination a[rel="next"] {
            color: #9ca3af; /* dark:slate-400 */
        }
        .pagination a:hover {
            background-color: #e0e7ff; /* indigo-100 */
            color: #4338ca; /* indigo-700 */
        }
        .dark .pagination a:hover {
            background-color: #374151; /* dark:slate-700 */
            color: #d1d5db; /* dark:slate-300 */
        }
        .pagination span[aria-disabled="true"] span {
            color: #9ca3af; /* slate-400 */
            background-color: #f3f4f6; /* slate-100 */
        }
        .dark .pagination span[aria-disabled="true"] span {
            color: #6b7280; /* dark:slate-500 */
            background-color: #374151; /* dark:slate-700 */
        }
    </style>
</x-app-layout>

<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-transparent bg-clip-text bg-gradient-to-r from-indigo-600 via-purple-600 to-pink-500 leading-tight">
            {{ __('Estadísticas de Tutores') }}
        </h2>
    </x-slot>

    <div class="py-8 bg-gradient-to-br from-slate-50 via-purple-50 to-pink-50 min-h-screen">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            {{-- Filter and Summary Banner --}}
            <div class="mb-8">
                <div class="bg-white/70 backdrop-blur-lg shadow-xl rounded-2xl p-6">
                    <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
                        <div>
                            <h3 class="text-lg font-semibold text-slate-800 mb-1">
                                {{ ($selectedProgram ?? null) || ($selectedPlace ?? null) ? 'Métricas para tutores' . (($selectedProgram ?? null) ? " del programa: " . e($selectedProgram) : '') . (($selectedPlace ?? null) ? " en el lugar: " . e($selectedPlace) : '') : 'Resumen General de Métricas de Tutores' }}
                            </h3>
                            <p class="text-slate-600 text-sm">
                                Analiza las estadísticas clave relacionadas con los tutores.
                            </p>
                        </div>
                        <div class="flex flex-col sm:flex-row sm:items-end sm:space-x-3 w-full lg:w-auto gap-3 sm:gap-0">
                            <div class="flex-grow sm:flex-grow-0">
                                <label for="programa_filter" class="block text-xs font-medium text-slate-700 mb-1">Programa:</label>
                                <select id="programa_filter" name="programa_filter" class="block w-full border-slate-300 rounded-xl shadow-sm focus:ring-indigo-500 focus:border-indigo-500 text-sm py-2.5 pl-3 pr-8 transition duration-150 ease-in-out" style="min-width: 180px;">
                                    <option value="">Todos</option>
                                    @foreach ($programOptions ?? [] as $program)
                                        <option value="{{ $program }}" {{ ($selectedProgram ?? '') === $program ? 'selected' : '' }}>{{ $program }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="flex-grow sm:flex-grow-0">
                                <label for="lugar_filter" class="block text-xs font-medium text-slate-700 mb-1">Lugar:</label>
                                <select id="lugar_filter" name="lugar_filter" class="block w-full border-slate-300 rounded-xl shadow-sm focus:ring-indigo-500 focus:border-indigo-500 text-sm py-2.5 pl-3 pr-8 transition duration-150 ease-in-out" style="min-width: 180px;">
                                    <option value="">Todos</option>
                                    @foreach ($placeOptions ?? [] as $place)
                                        <option value="{{ $place }}" {{ ($selectedPlace ?? '') === $place ? 'selected' : '' }}>{{ $place }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="w-full sm:w-auto pt-2 sm:pt-0">
                                <button id="applyFilters" class="w-full justify-center inline-flex items-center px-5 py-2.5 bg-gradient-to-r from-indigo-600 to-purple-600 border border-transparent rounded-xl font-semibold text-xs text-white uppercase tracking-widest hover:from-indigo-700 hover:to-purple-700 focus:outline-none focus:ring-2 focus:ring-purple-500 focus:ring-offset-2 transition ease-in-out duration-150 shadow-md hover:shadow-lg">
                                    <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                                    Aplicar
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Key Metrics Section --}}
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                <div class="bg-white/70 backdrop-blur-lg shadow-lg rounded-2xl p-6 flex items-center hover:shadow-xl transition-shadow duration-300">
                    <div class="flex-shrink-0 bg-gradient-to-br from-indigo-500 to-purple-600 rounded-xl p-3.5 mr-4 shadow-md">
                        <svg class="h-8 w-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                    </div>
                    <div class="ml-1">
                        <h4 class="text-sm font-medium text-slate-500">Total Tutores Únicos</h4>
                        <p class="text-3xl font-bold text-slate-800">{{ $totalTutors ?? 0 }}</p>
                    </div>
                </div>
                <div class="bg-white/70 backdrop-blur-lg shadow-lg rounded-2xl p-6 flex items-center hover:shadow-xl transition-shadow duration-300">
                   <div class="flex-shrink-0 bg-gradient-to-br from-green-500 to-emerald-600 rounded-xl p-3.5 mr-4 shadow-md">
                        <svg class="h-8 w-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                    </div>
                    <div class="ml-1">
                        <h4 class="text-sm font-medium text-slate-500">Programas con Tutores</h4>
                        <p class="text-3xl font-bold text-slate-800">{{ count($tutorsByProgram ?? []) }}</p>
                    </div>
                </div>
                <div class="bg-white/70 backdrop-blur-lg shadow-lg rounded-2xl p-6 flex items-center hover:shadow-xl transition-shadow duration-300">
                    <div class="flex-shrink-0 bg-gradient-to-br from-sky-500 to-cyan-600 rounded-xl p-3.5 mr-4 shadow-md">
                        <svg class="h-8 w-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path></svg>
                    </div>
                    <div class="ml-1">
                        <h4 class="text-sm font-medium text-slate-500">Prom. Participantes/Tutor</h4>
                        <p class="text-3xl font-bold text-slate-800">{{ number_format($averageParticipantsPerTutor ?? 0, 1) }}</p>
                    </div>
                </div>
            </div>

            {{-- Detailed Tables Section --}}
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <div class="bg-white/70 backdrop-blur-lg shadow-lg rounded-2xl p-6 hover:shadow-xl transition-shadow duration-300">
                    <h3 class="text-lg font-semibold text-slate-800 mb-4">Tutores por Programa</h3>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-slate-200 text-sm">
                            <thead class="bg-slate-100">
                                <tr class="text-xs font-semibold text-slate-600 uppercase tracking-wider">
                                    <th class="px-4 py-3 text-left">Programa</th>
                                    <th class="px-4 py-3 text-right">Tutores Únicos</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-slate-200">
                                @forelse ($tutorsByProgram ?? [] as $program => $count)
                                    <tr class="hover:bg-sky-50/70">
                                        <td class="px-4 py-3 text-slate-800">{{ $program }}</td>
                                        <td class="px-4 py-3 text-right text-slate-700 font-medium">{{ $count }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="2" class="px-4 py-8 text-center text-slate-500">No hay datos para la selección actual.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="bg-white/70 backdrop-blur-lg shadow-lg rounded-2xl p-6 hover:shadow-xl transition-shadow duration-300">
                    <h3 class="text-lg font-semibold text-slate-800 mb-4">Tutores por Sector Económico</h3>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-slate-200 text-sm">
                            <thead class="bg-slate-100">
                                <tr class="text-xs font-semibold text-slate-600 uppercase tracking-wider">
                                    <th class="px-4 py-3 text-left">Sector Económico</th>
                                    <th class="px-4 py-3 text-right">Tutores</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-slate-200">
                                @forelse ($tutorsBySector ?? [] as $sector => $count)
                                    <tr class="hover:bg-sky-50/70">
                                        <td class="px-4 py-3 text-slate-800">{{ $sector ?: 'No especificado' }}</td>
                                        <td class="px-4 py-3 text-right text-slate-700 font-medium">{{ $count }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="2" class="px-4 py-8 text-center text-slate-500">No hay datos disponibles.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="bg-white/70 backdrop-blur-lg shadow-lg rounded-2xl p-6 hover:shadow-xl transition-shadow duration-300">
                    <h3 class="text-lg font-semibold text-slate-800 mb-4">Tutores por Nivel de Educación</h3>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-slate-200 text-sm">
                            <thead class="bg-slate-100">
                                <tr class="text-xs font-semibold text-slate-600 uppercase tracking-wider">
                                    <th class="px-4 py-3 text-left">Nivel de Educación</th>
                                    <th class="px-4 py-3 text-right">Tutores</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-slate-200">
                                @forelse ($tutorsByEducationLevel ?? [] as $level => $count)
                                    <tr class="hover:bg-sky-50/70">
                                        <td class="px-4 py-3 text-slate-800">{{ $level ?: 'No especificado' }}</td>
                                        <td class="px-4 py-3 text-right text-slate-700 font-medium">{{ $count }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="2" class="px-4 py-8 text-center text-slate-500">No hay datos disponibles.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="bg-white/70 backdrop-blur-lg shadow-lg rounded-2xl p-6 hover:shadow-xl transition-shadow duration-300">
                    <h3 class="text-lg font-semibold text-slate-800 mb-4">Tutores por Comunidad</h3>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-slate-200 text-sm">
                            <thead class="bg-slate-100">
                                <tr class="text-xs font-semibold text-slate-600 uppercase tracking-wider">
                                    <th class="px-4 py-3 text-left">Comunidad</th>
                                    <th class="px-4 py-3 text-right">Tutores</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-slate-200">
                                @forelse ($tutorsByCommunity ?? [] as $community => $count)
                                    <tr class="hover:bg-sky-50/70">
                                        <td class="px-4 py-3 text-slate-800">{{ $community ?: 'No especificado' }}</td>
                                        <td class="px-4 py-3 text-right text-slate-700 font-medium">{{ $count }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="2" class="px-4 py-8 text-center text-slate-500">No hay datos disponibles.</td>
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
                lugarSelect.disabled = true; // Disable while loading

                let fetchUrl = `{{ route('tutores.lugaresPorPrograma') }}`; // Make sure this route exists and is correct
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
                                if (lugar === preselectLugar) { // Use preselectLugar for initial selection
                                    option.selected = true;
                                }
                                lugarSelect.appendChild(option);
                            });
                        }
                        lugarSelect.disabled = false; // Re-enable after loading
                    })
                    .catch(error => {
                        console.error('Error en fetch para lugares:', error);
                        lugarSelect.innerHTML = '<option value="">Error al cargar</option>';
                        // Keep it disabled on error or decide on other UX
                    });
            }

            // Load places if a program is initially selected
            if (initialSelectedPrograma) {
                cargarLugares(initialSelectedPrograma, initialSelectedLugar);
            } else {
                 // If no program is selected, load all places or keep "Todos los lugares"
                cargarLugares(null, initialSelectedLugar); // Pass null to fetch all, or handle in backend
            }

            programaSelect.addEventListener('change', function () {
                // When program changes, reset lugar to "Todos" and reload lugares for the new program
                lugarSelect.value = ""; 
                cargarLugares(this.value, ""); // Pass empty string for preselectLugar
            });

            applyFiltersButton.addEventListener('click', function() {
                updatePageWithFilters(programaSelect.value, lugarSelect.value);
            });

            function updatePageWithFilters(programa, lugar) {
                const params = new URLSearchParams(); // Start with fresh params for clarity
                
                if (programa) {
                    params.set('programa', programa);
                }
                if (lugar) {
                    params.set('lugar', lugar);
                }
                // Ensure the route name 'tutores.stats' or similar is correct for this page
                window.location.href = `{{ route('tutores') }}?${params.toString()}`; 
            }
        });
    </script>
     <style>
        /* Styling for Laravel Pagination to match the theme (copied from previous examples) */
        .pagination span, .pagination a {
            padding: 0.5rem 0.75rem;
            margin: 0 0.125rem;
            border-radius: 0.5rem; /* rounded-lg */
            font-size: 0.875rem; /* text-sm */
            transition: all 0.15s ease-in-out;
        }
        .pagination span[aria-current="page"] span {
            background-image: linear-gradient(to right, #4f46e5, #8b5cf6);
            color: white;
            font-weight: 600;
            border: 1px solid transparent;
        }
        .pagination a[rel="prev"], .pagination a[rel="next"] {
            color: #4b5563; /* slate-600 */
        }
        .pagination a:hover {
            background-color: #e0e7ff; /* indigo-100 */
            color: #4338ca; /* indigo-700 */
        }
        .pagination span[aria-disabled="true"] span {
            color: #9ca3af; /* slate-400 */
            background-color: #f3f4f6; /* slate-100 */
        }
         /* Custom scrollbar for Webkit browsers (Chrome, Safari, Edge) */
        .overflow-x-auto::-webkit-scrollbar {
            height: 8px;
        }
        .overflow-x-auto::-webkit-scrollbar-track {
            background: #f1f5f9; /* slate-100 */
            border-radius: 10px;
        }
        .overflow-x-auto::-webkit-scrollbar-thumb {
            background: #cbd5e1; /* slate-300 */
            border-radius: 10px;
        }
        .overflow-x-auto::-webkit-scrollbar-thumb:hover {
            background: #94a3b8; /* slate-500 */
        }
    </style>
</x-app-layout>

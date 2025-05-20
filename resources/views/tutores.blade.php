<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Estadísticas de Tutores') }}
        </h2>
    </x-slot>

    <div class="py-8 bg-gray-50 min-h-screen">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="mb-6">
                <div class="bg-white shadow-sm rounded-lg p-6">
                    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
                        <p class="text-gray-900 text-sm mb-4 sm:mb-0">
                            {{ ($selectedProgram ?? null) || ($selectedPlace ?? null) ? 'Métricas para tutores' . (($selectedProgram ?? null) ? " del programa: " . e($selectedProgram) : '') . (($selectedPlace ?? null) ? " en el lugar: " . e($selectedPlace) : '') : 'Resumen general de métricas de tutores' }}
                        </p>
                        <div class="flex flex-col sm:flex-row sm:items-center sm:space-x-0 sm:space-y-4 md:space-y-0 md:space-x-4">
                            <div class="flex items-center w-full md:w-auto mb-2 md:mb-0">
                                <label for="programa_filter" class="mr-2 text-sm text-gray-600 whitespace-nowrap">Filtrar por programa:</label>
                                <select id="programa_filter" name="programa_filter" class="border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm w-full">
                                    <option value="">Todos los programas</option>
                                    {{-- Cambio aquí: usar $programOptions en lugar de $programs --}}
                                    @foreach ($programOptions ?? [] as $program)
                                        <option value="{{ $program }}" {{ ($selectedProgram ?? '') === $program ? 'selected' : '' }}>{{ $program }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="flex items-center w-full md:w-auto">
                                <label for="lugar_filter" class="mr-2 text-sm text-gray-600 whitespace-nowrap">Filtrar por lugar:</label>
                                <select id="lugar_filter" name="lugar_filter" class="border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm w-full">
                                    <option value="">Todos los lugares</option>
                                    @foreach ($placeOptions ?? [] as $place) {{-- Asumiendo que el controlador pasa placeOptions --}}
                                        <option value="{{ $place }}" {{ ($selectedPlace ?? '') === $place ? 'selected' : '' }}>{{ $place }}</option>
                                    @endforeach
                                </select>
                            </div>
                             <div class="w-full md:w-auto">
                                <button id="applyFilters" class="w-full justify-center inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:border-indigo-900 focus:ring ring-indigo-300 disabled:opacity-25 transition ease-in-out duration-150">
                                    Aplicar Filtros
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                <div class="bg-white shadow-sm rounded-lg p-6 flex items-center">
                    <div class="flex-shrink-0 bg-indigo-100 p-3 rounded-full">
                        <svg class="h-6 w-6 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                    </div>
                    <div class="ml-4">
                        <h3 class="text-sm font-medium text-gray-600">Total Tutores Únicos</h3>
                        <p class="text-2xl font-semibold text-gray-900">{{ $totalTutors ?? 0 }}</p>
                    </div>
                </div>
                <div class="bg-white shadow-sm rounded-lg p-6 flex items-center">
                     <div class="flex-shrink-0 bg-green-100 p-3 rounded-full">
                        <svg class="h-6 w-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                    </div>
                    <div class="ml-4">
                        <h3 class="text-sm font-medium text-gray-600">Programas con Tutores</h3>
                        <p class="text-2xl font-semibold text-gray-900">{{ count($tutorsByProgram ?? []) }}</p>
                    </div>
                </div>
                <div class="bg-white shadow-sm rounded-lg p-6 flex items-center">
                    <div class="flex-shrink-0 bg-blue-100 p-3 rounded-full">
                        <svg class="h-6 w-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path></svg>
                    </div>
                    <div class="ml-4">
                        <h3 class="text-sm font-medium text-gray-600">Promedio Participantes/Tutor</h3>
                        <p class="text-2xl font-semibold text-gray-900">{{ number_format($averageParticipantsPerTutor ?? 0, 1) }}</p>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <div class="bg-white shadow-sm rounded-lg p-6">
                    <h3 class="text-lg font-medium text-gray-800 mb-4">Tutores por Programa</h3>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200 text-sm">
                            <thead class="bg-gray-100">
                                <tr class="text-xs font-medium text-gray-600 uppercase">
                                    <th class="px-4 py-3 text-left">Programa</th>
                                    <th class="px-4 py-3 text-right">Tutores Únicos</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200">
                                @forelse ($tutorsByProgram ?? [] as $program => $count)
                                    <tr class="hover:bg-gray-50">
                                        <td class="px-4 py-3 text-gray-900">{{ $program }}</td>
                                        <td class="px-4 py-3 text-right text-gray-600 font-medium">{{ $count }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="2" class="px-4 py-3 text-gray-500 text-center">No hay datos disponibles para la selección actual.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="bg-white shadow-sm rounded-lg p-6">
                    <h3 class="text-lg font-medium text-gray-800 mb-4">Tutores por Sector Económico</h3>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200 text-sm">
                            <thead class="bg-gray-100">
                                <tr class="text-xs font-medium text-gray-600 uppercase">
                                    <th class="px-4 py-3 text-left">Sector Económico</th>
                                    <th class="px-4 py-3 text-right">Tutores</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200">
                                @forelse ($tutorsBySector ?? [] as $sector => $count)
                                    <tr class="hover:bg-gray-50">
                                        <td class="px-4 py-3 text-gray-900">{{ $sector ?: 'No especificado' }}</td>
                                        <td class="px-4 py-3 text-right text-gray-600 font-medium">{{ $count }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="2" class="px-4 py-3 text-gray-500 text-center">No hay datos disponibles.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="bg-white shadow-sm rounded-lg p-6">
                    <h3 class="text-lg font-medium text-gray-800 mb-4">Tutores por Nivel de Educación</h3>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200 text-sm">
                            <thead class="bg-gray-100">
                                <tr class="text-xs font-medium text-gray-600 uppercase">
                                    <th class="px-4 py-3 text-left">Nivel de Educación</th>
                                    <th class="px-4 py-3 text-right">Tutores</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200">
                                @forelse ($tutorsByEducationLevel ?? [] as $level => $count)
                                    <tr class="hover:bg-gray-50">
                                        <td class="px-4 py-3 text-gray-900">{{ $level ?: 'No especificado' }}</td>
                                        <td class="px-4 py-3 text-right text-gray-600 font-medium">{{ $count }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="2" class="px-4 py-3 text-gray-500 text-center">No hay datos disponibles.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="bg-white shadow-sm rounded-lg p-6">
                    <h3 class="text-lg font-medium text-gray-800 mb-4">Tutores por Comunidad</h3>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200 text-sm">
                            <thead class="bg-gray-100">
                                <tr class="text-xs font-medium text-gray-600 uppercase">
                                    <th class="px-4 py-3 text-left">Comunidad</th>
                                    <th class="px-4 py-3 text-right">Tutores</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200">
                                @forelse ($tutorsByCommunity ?? [] as $community => $count)
                                    <tr class="hover:bg-gray-50">
                                        <td class="px-4 py-3 text-gray-900">{{ $community ?: 'No especificado' }}</td>
                                        <td class="px-4 py-3 text-right text-gray-600 font-medium">{{ $count }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="2" class="px-4 py-3 text-gray-500 text-center">No hay datos disponibles.</td>
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

            // Función para cargar lugares
            function cargarLugares(selectedPrograma) {
                lugarSelect.innerHTML = '<option value=\"\">Cargando lugares...</option>'; // Feedback visual
                let fetchUrl = `{{ route('tutores.lugaresPorPrograma') }}`;
                if (selectedPrograma) {
                    fetchUrl += `?programa=${encodeURIComponent(selectedPrograma)}`;
                }

                fetch(fetchUrl)
                    .then(response => {
                        if (!response.ok) {
                            throw new Error('Error de red al cargar lugares: ' + response.statusText);
                        }
                        return response.json();
                    })
                    .then(data => {
                        lugarSelect.innerHTML = '<option value=\"\">Todos los lugares</option>'; // Opción por defecto
                        if (data.error) {
                            console.error('Error del servidor al cargar lugares:', data.error);
                        } else if (Array.isArray(data) && data.length === 0 && selectedPrograma) {
                            const option = document.createElement('option');
                            option.value = "";
                            option.textContent = "No hay lugares para este programa";
                            // option.disabled = true; // Puede ser mejor no deshabilitarlo para permitir "Todos los lugares"
                            lugarSelect.appendChild(option);
                        } else if (Array.isArray(data)) {
                            data.forEach(lugar => {
                                const option = document.createElement('option');
                                option.value = lugar;
                                option.textContent = lugar;
                                // Si el programa actual es el que estaba seleccionado inicialmente Y el lugar actual es el que estaba seleccionado inicialmente
                                if (selectedPrograma === initialSelectedPrograma && lugar === initialSelectedLugar) {
                                   option.selected = true;
                                }
                                lugarSelect.appendChild(option);
                            });
                             // Si después de cargar, el programa seleccionado es el inicial, y el lugar seleccionado es el inicial,
                             // y no se encontró una coincidencia exacta (por si acaso), se re-selecciona.
                             if (selectedPrograma === initialSelectedPrograma && initialSelectedLugar && !lugarSelect.value) {
                                 lugarSelect.value = initialSelectedLugar;
                             }
                        }
                    })
                    .catch(error => {
                        console.error('Error en fetch para lugares:', error);
                        lugarSelect.innerHTML = '<option value=\"\">Error al cargar lugares</option>';
                    });
            }

            // Cargar lugares al inicio si hay un programa seleccionado
            if (initialSelectedPrograma) {
                cargarLugares(initialSelectedPrograma);
            } else {
                 // Si no hay programa seleccionado inicialmente, igual poblamos los lugares con "Todos los lugares"
                 // y si el controlador devuelve todos los lugares cuando no hay programa, se cargarán.
                 // Esto asume que el controlador devuelve todos los lugares si no se pasa `programa`.
                 cargarLugares(null);
            }


            programaSelect.addEventListener('change', function () {
                cargarLugares(this.value);
            });

            applyFiltersButton.addEventListener('click', function() {
                updatePageWithFilters(programaSelect.value, lugarSelect.value);
            });

            function updatePageWithFilters(programa, lugar) {
                const params = new URLSearchParams(window.location.search); // Preservar parámetros existentes
                
                if (programa) {
                    params.set('programa', programa);
                } else {
                    params.delete('programa');
                }

                if (lugar) {
                    params.set('lugar', lugar);
                } else {
                    params.delete('lugar');
                }
                window.location.href = `{{ route('tutores') }}?${params.toString()}`;
            }
        });
    </script>
</x-app-layout>

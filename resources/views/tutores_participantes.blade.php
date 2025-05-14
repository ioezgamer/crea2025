<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Tutores y Participantes') }}
        </h2>
    </x-slot>

    <div class="py-8 bg-gray-50 min-h-screen">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="mb-6">
                <div class="bg-white shadow-sm rounded-lg p-6">
                    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
                        <p class="text-gray-900 text-sm mb-4 sm:mb-0">
                            {{ ($selectedProgram ?? null) || ($selectedPlace ?? null) ? 'Tutores y participantes' . (($selectedProgram ?? null) ? " del programa: " . ($selectedProgram ?? '') : '') . (($selectedPlace ?? null) ? " en el lugar: " . ($selectedPlace ?? '') : '') : 'Lista de tutores y sus participantes' }}
                        </p>
                        <div class="flex flex-col sm:flex-row sm:items-center sm:space-x-4 mt-4 sm:mt-0">
                            <div class="flex items-center mb-2 sm:mb-0">
                                <label for="programa_filter" class="mr-2 text-sm text-gray-600">Programa:</label>
                                <select id="programa_filter" name="programa" onchange="updateFilters(this.value, document.getElementById('lugar_filter').value)" class="border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" style="min-width: 150px;">
                                    <option value="">Todos</option>
                                    {{-- Asumiendo que $programOptions se pasa desde el controlador --}}
                                    @foreach ($programOptions ?? [] as $programOption)
                                        <option value="{{ $programOption }}" {{ ($selectedProgram ?? null) === $programOption ? 'selected' : '' }}>{{ $programOption }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="flex items-center">
                                <label for="lugar_filter" class="mr-2 text-sm text-gray-600">Lugar:</label>
                                <select id="lugar_filter" name="lugar" onchange="updateFilters(document.getElementById('programa_filter').value, this.value)" class="border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" style="min-width: 150px;">
                                    <option value="">Todos</option>
                                     {{-- Asumiendo que $placeOptions se pasa desde el controlador --}}
                                    @foreach ($placeOptions ?? [] as $placeOption)
                                        <option value="{{ $placeOption }}" {{ ($selectedPlace ?? null) === $placeOption ? 'selected' : '' }}>{{ $placeOption }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="bg-white shadow-sm rounded-lg p-6">
                <h3 class="text-sm font-medium text-gray-600 mb-4">Lista de Tutores y Participantes Asociados</h3>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 text-sm">
                        <thead class="bg-gray-100">
                            <tr class="text-xs font-medium text-gray-600 uppercase">
                                <th class="px-4 py-3 text-left">Relación del Tutor</th>
                                <th class="px-4 py-3 text-left">Nombres y Apellidos del Tutor</th>
                                <th class="px-4 py-3 text-left">Programas (de Participantes)</th>
                                <th class="px-4 py-3 text-left">Participantes (Nombre y Grado)</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            @forelse ($tutors ?? [] as $tutor)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-4 py-3 text-gray-600">
                                        {{-- Usar la clave 'tipos_tutor_str' que se preparó en el controlador --}}
                                        {{ $tutor['tipos_tutor_str'] ?? 'No especificado' }}
                                    </td>
                                    <td class="px-4 py-3 text-gray-900">{{ $tutor['nombres_y_apellidos_tutor_principal'] ?? $tutor['identificador_tutor'] }}</td>
                                    <td class="px-4 py-3 text-gray-600">
                                        {{-- Usar la clave 'programas_asociados_str' que se preparó en el controlador --}}
                                        {{ $tutor['programas_asociados_str'] ?? 'N/A' }}
                                    </td>
                                    <td class="px-4 py-3 text-gray-600">
                                        @if (!empty($tutor['participantes']))
                                            <ul class="list-disc pl-5">
                                                @foreach ($tutor['participantes'] as $participante)
                                                    <li>{{ $participante['nombre_completo'] ?? 'N/A' }} ({{ $participante['grado_p'] ?? 'N/A' }})</li>
                                                @endforeach
                                            </ul>
                                        @else
                                            <span>No hay participantes asociados.</span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="px-4 py-3 text-gray-500 text-center">No hay tutores disponibles para los filtros seleccionados.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <script>
        function updateFilters(programa, lugar) {
            const params = new URLSearchParams(window.location.search); // Mantener otros parámetros existentes si los hubiera
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
            // Redirigir a la URL base sin parámetros si ambos filtros están vacíos,
            // o con los parámetros si alguno está activo.
            const queryString = params.toString();
            window.location.href = window.location.pathname + (queryString ? '?' + queryString : '');
        }
    </script>
</x-app-layout>

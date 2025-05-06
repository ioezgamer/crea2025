<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Tutores y Participantes') }}
        </h2>
    </x-slot>

    <div class="py-8 bg-gray-50 min-h-screen">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Filters -->
            <div class="mb-6">
                <div class="bg-white shadow-sm rounded-lg p-6">
                    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
                        <p class="text-gray-900 text-sm mb-4 sm:mb-0">
                            {{ $selectedProgram || $selectedPlace ? 'Tutores y participantes' . ($selectedProgram ? " del programa: $selectedProgram" : '') . ($selectedPlace ? " en el lugar: $selectedPlace" : '') : 'Lista de tutores y sus participantes' }}
                        </p>
                        <div class="flex flex-col sm:flex-row sm:items-center sm:space-x-4">
                            <div class="flex items-center mb-4 sm:mb-0">
                                <label for="programa" class="mr-2 text-sm text-gray-600">Filtrar por programa:</label>
                                <select id="programa" name="programa" onchange="updateFilters(this.value, document.getElementById('lugar').value)" class="border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                    <option value="">Todos los programas</option>
                                    @foreach ($programs ?? [] as $program)
                                        <option value="{{ $program }}" {{ $selectedProgram === $program ? 'selected' : '' }}>{{ $program }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="flex items-center">
                                <label for="lugar" class="mr-2 text-sm text-gray-600">Filtrar por lugar:</label>
                                <select id="lugar" name="lugar" onchange="updateFilters(document.getElementById('programa').value, this.value)" class="border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                    <option value="">Todos los lugares</option>
                                    @foreach ($places ?? [] as $place)
                                        <option value="{{ $place }}" {{ $selectedPlace === $place ? 'selected' : '' }}>{{ $place }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Tutors List -->
            <div class="bg-white shadow-sm rounded-lg p-6">
                <h3 class="text-sm font-medium text-gray-600 mb-4">Lista de Tutores y Participantes</h3>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 text-sm">
                        <thead class="bg-gray-100">
                            <tr class="text-xs font-medium text-gray-600 uppercase">
                                <th class="px-4 py-3 text-left">Tutor</th>
                                <th class="px-4 py-3 text-left">Nombres y apellidos</th>
                                <th class="px-4 py-3 text-left">Programa</th>
                                <th class="px-4 py-3 text-left">Participantes</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            @forelse ($tutors ?? [] as $tutor)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-4 py-3 text-gray-600">
                                        {{ !empty($tutor['tutor_principal']) ? implode(' / ', $tutor['tutor_principal']) : 'No especificado' }}
                                    </td>
                                    <td class="px-4 py-3 text-gray-900">{{ $tutor['nombres_y_apellidos_tutor_principal'] }}</td>
                                    <td class="px-4 py-3 text-gray-600">{{ $tutor['programa'] }}</td>
                                    <td class="px-4 py-3 text-gray-600">
                                        <ul class="list-disc pl-5">
                                            @foreach ($tutor['participantes'] as $participante)
                                                <li>{{ $participante['primer_nombre_p'] }} {{ $participante['primer_apellido_p'] }} ({{ $participante['grado_p'] }})</li>
                                            @endforeach
                                        </ul>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="px-4 py-3 text-gray-500 text-center">No hay tutores disponibles</td>
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
            const params = new URLSearchParams();
            if (programa) params.set('programa', programa);
            if (lugar) params.set('lugar', lugar);
            window.location.href = '?' + params.toString();
        }
    </script>
</x-app-layout>
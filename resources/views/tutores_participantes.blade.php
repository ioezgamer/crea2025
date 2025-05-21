<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-transparent bg-clip-text bg-gradient-to-r from-indigo-600 via-purple-600 to-pink-500 leading-tight">
            {{ __('Tutores y Participantes Asignados') }}
        </h2>
    </x-slot>

    <div class="py-8 bg-gradient-to-br from-slate-50 via-purple-50 to-pink-50 min-h-screen">
        <div class="max-w-full mx-auto px-4 sm:px-6 lg:px-8">
            {{-- Filter and Summary Banner --}}
            <div class="mb-8">
                <div class="bg-white/70 backdrop-blur-lg shadow-xl rounded-2xl p-6">
                    <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
                        <div>
                            <h3 class="text-lg font-semibold text-slate-800 mb-1">
                                {{ ($selectedProgram ?? null) || ($selectedPlace ?? null) ? 'Filtrando Tutores y Participantes' : 'Lista de Tutores y Participantes Asignados' }}
                            </h3>
                            <p class="text-slate-600 text-sm">
                                @if($selectedProgram)
                                    Programa: <span class="font-medium text-indigo-700">{{ $selectedProgram }}</span>
                                @endif
                                @if($selectedProgram && $selectedPlace)
                                    <span class="mx-1">|</span>
                                @endif
                                @if($selectedPlace)
                                    Lugar: <span class="font-medium text-indigo-700">{{ $selectedPlace }}</span>
                                @endif
                                @if(!($selectedProgram || $selectedPlace))
                                    Visualiza todos los tutores y los participantes que tienen asignados.
                                @endif
                            </p>
                        </div>
                        <div class="flex flex-col sm:flex-row sm:items-center sm:space-x-3 w-full lg:w-auto">
                            <div class="flex items-center w-full sm:w-auto mb-2 sm:mb-0">
                                <label for="programa_filter" class="mr-2 text-sm font-medium text-slate-700 whitespace-nowrap">Programa:</label>
                                <select id="programa_filter" name="programa" onchange="updateFilters(this.value, document.getElementById('lugar_filter').value)" 
                                        class="block w-full border-slate-300 rounded-xl shadow-sm focus:ring-indigo-500 focus:border-indigo-500 text-sm py-2.5 pl-3 pr-8 transition duration-150 ease-in-out" style="min-width: 180px;">
                                    <option value="">Todos</option>
                                    @foreach ($programOptions ?? [] as $programOption)
                                        <option value="{{ $programOption }}" {{ ($selectedProgram ?? null) === $programOption ? 'selected' : '' }}>{{ $programOption }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="flex items-center w-full sm:w-auto">
                                <label for="lugar_filter" class="mr-2 text-sm font-medium text-slate-700">Lugar:</label>
                                <select id="lugar_filter" name="lugar" onchange="updateFilters(document.getElementById('programa_filter').value, this.value)" 
                                        class="block w-full border-slate-300 rounded-xl shadow-sm focus:ring-indigo-500 focus:border-indigo-500 text-sm py-2.5 pl-3 pr-8 transition duration-150 ease-in-out" style="min-width: 180px;">
                                    <option value="">Todos</option>
                                    @foreach ($placeOptions ?? [] as $placeOption)
                                        <option value="{{ $placeOption }}" {{ ($selectedPlace ?? null) === $placeOption ? 'selected' : '' }}>{{ $placeOption }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Tutors and Participants Table --}}
            <div class="bg-white/70 backdrop-blur-lg shadow-xl rounded-2xl overflow-hidden">
                <div class="px-4 sm:px-6 py-5 border-b border-slate-200">
                     <h3 class="text-lg font-semibold text-slate-800">Detalles de Asignación</h3>
                </div>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-slate-200 text-sm">
                        <thead class="bg-slate-100">
                            <tr class="text-xs font-semibold text-slate-600 uppercase tracking-wider">
                                <th class="px-4 py-3 text-left">Relación del Tutor</th>
                                <th class="px-4 py-3 text-left">Nombres y Apellidos del Tutor</th>
                                <th class="px-4 py-3 text-left">Programas (de Participantes)</th>
                                <th class="px-4 py-3 text-left">Participantes (Nombre y Grado)</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-slate-200">
                            @forelse ($tutors ?? [] as $tutor)
                                <tr class="hover:bg-sky-50/70 transition-colors duration-150">
                                    <td class="px-4 py-3 text-slate-600 align-top">
                                        {{ $tutor['tipos_tutor_str'] ?? 'No especificado' }}
                                    </td>
                                    <td class="px-4 py-3 text-slate-900 font-medium align-top">{{ $tutor['nombres_y_apellidos_tutor_principal'] ?? $tutor['identificador_tutor'] }}</td>
                                    <td class="px-4 py-3 text-slate-600 align-top">
                                        @if(!empty($tutor['programas_asociados_str']))
                                            @php $programasTutor = explode(',', $tutor['programas_asociados_str']); @endphp
                                            <div class="flex flex-col space-y-1">
                                                @foreach($programasTutor as $progTutor)
                                                    <span class="px-2 py-0.5 bg-blue-100 text-blue-700 rounded-full text-xxs font-semibold inline-block">{{ trim($progTutor) }}</span>
                                                @endforeach
                                            </div>
                                        @else
                                            N/A
                                        @endif
                                    </td>
                                    <td class="px-4 py-3 text-slate-600 align-top">
                                        @if (!empty($tutor['participantes']))
                                            <ul class="space-y-1.5">
                                                @foreach ($tutor['participantes'] as $participante)
                                                    <li class="text-xs">
                                                        <span class="font-medium text-slate-800">{{ $participante['nombre_completo'] ?? 'N/A' }}</span>
                                                        <span class="text-slate-500">({{ $participante['grado_p'] ?? 'N/A' }})</span>
                                                    </li>
                                                @endforeach
                                            </ul>
                                        @else
                                            <span class="text-xs text-slate-400 italic">No hay participantes asociados.</span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="px-6 py-12 text-center">
                                        <div class="flex flex-col items-center">
                                            <svg class="w-12 h-12 text-slate-300 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                            <p class="text-sm text-slate-500">No hay tutores disponibles para los filtros seleccionados.</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                 @if (isset($tutors) && $tutors instanceof \Illuminate\Pagination\LengthAwarePaginator && $tutors->hasPages())
                    <div class="px-6 py-4 bg-slate-50 border-t border-slate-200 rounded-b-xl">
                        {{ $tutors->appends(request()->query())->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>

    <script>
        function updateFilters(programa, lugar) {
            const params = new URLSearchParams(window.location.search);
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
            const queryString = params.toString();
            window.location.href = window.location.pathname + (queryString ? '?' + queryString : '');
        }
    </script>
    <style>
        .text-xxs { font-size: 0.65rem; line-height: 0.85rem; }
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
         /* Styling for Laravel Pagination to match the theme (copied from roles_users_page_crea_v1) */
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
    </style>
</x-app-layout>

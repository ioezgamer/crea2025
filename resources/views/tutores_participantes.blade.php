<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between w-full px-4 py-6 mx-auto max-w-7xl sm:px-6 lg:px-8">
            <h2 class="text-2xl font-bold text-transparent lg:text-3xl bg-clip-text bg-gradient-to-r from-indigo-600 via-purple-600 to-pink-500">
            {{ __('Tutores y Participantes Asignados') }}
        </h2>
        </div>
    </x-slot>

    <div class="min-h-screen py-8 bg-gradient-to-br from-slate-50 via-purple-50 to-pink-50 dark:from-slate-800 dark:via-purple-900 dark:to-pink-900">
        <div class="max-w-full px-4 mx-auto sm:px-6 lg:px-8">
            {{-- Filter and Summary Banner --}}
            <div class="mb-8">
                <div class="p-6 shadow-xl bg-white/70 dark:bg-slate-800/80 backdrop-blur-lg rounded-2xl">
                    <div class="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">
                        <div>
                            <h3 class="mb-1 text-lg font-semibold text-slate-800 dark:text-slate-100">
                                {{ ($selectedProgram ?? null) || ($selectedPlace ?? null) ? 'Filtrando Tutores y Participantes' : 'Lista de Tutores y Participantes Asignados' }}
                            </h3>
                            <p class="text-sm text-slate-600 dark:text-slate-400">
                                @if($selectedProgram)
                                    Programa: <span class="font-medium text-indigo-700 dark:text-indigo-400">{{ $selectedProgram }}</span>
                                @endif
                                @if($selectedProgram && $selectedPlace)
                                    <span class="mx-1">|</span>
                                @endif
                                @if($selectedPlace)
                                    Lugar: <span class="font-medium text-indigo-700 dark:text-indigo-400">{{ $selectedPlace }}</span>
                                @endif
                                @if(!($selectedProgram || $selectedPlace))
                                    Visualiza todos los tutores y los participantes que tienen asignados.
                                @endif
                            </p>
                        </div>
                        <div class="flex flex-col w-full sm:flex-row sm:items-center sm:space-x-3 lg:w-auto">
                            <div class="flex items-center w-full mb-2 sm:w-auto sm:mb-0">
                                <label for="programa_filter" class="mr-2 text-sm font-medium text-slate-700 dark:text-slate-300 whitespace-nowrap">Programa:</label>
                                <select id="programa_filter" name="programa" onchange="updateFilters(this.value, document.getElementById('lugar_filter').value)"
                                        class="block w-full border-slate-300 dark:border-slate-600 dark:bg-slate-700 dark:text-slate-300 rounded-xl shadow-sm focus:ring-indigo-500 dark:focus:ring-indigo-600 focus:border-indigo-500 dark:focus:border-indigo-600 text-sm py-2.5 pl-3 pr-8 transition duration-150 ease-in-out" style="min-width: 180px;">
                                    <option value="">Todos</option>
                                    @foreach ($programOptions ?? [] as $programOption)
                                        <option value="{{ $programOption }}" {{ ($selectedProgram ?? null) === $programOption ? 'selected' : '' }}>{{ $programOption }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="flex items-center w-full sm:w-auto">
                                <label for="lugar_filter" class="mr-2 text-sm font-medium text-slate-700 dark:text-slate-300">Lugar:</label>
                                <select id="lugar_filter" name="lugar" onchange="updateFilters(document.getElementById('programa_filter').value, this.value)"
                                        class="block w-full border-slate-300 dark:border-slate-600 dark:bg-slate-700 dark:text-slate-300 rounded-xl shadow-sm focus:ring-indigo-500 dark:focus:ring-indigo-600 focus:border-indigo-500 dark:focus:border-indigo-600 text-sm py-2.5 pl-3 pr-8 transition duration-150 ease-in-out" style="min-width: 180px;">
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
            <div class="overflow-hidden shadow-xl bg-white/70 dark:bg-slate-800/80 backdrop-blur-lg rounded-2xl">
                <div class="px-4 py-5 border-b sm:px-6 border-slate-200 dark:border-slate-700">
                       <h3 class="text-lg font-semibold text-slate-800 dark:text-slate-100">Detalles de Asignación</h3>
                </div>
                <div class="overflow-x-auto">
                    <table class="min-w-full text-sm divide-y divide-slate-200 dark:divide-slate-700">
                        <thead class="bg-slate-100 dark:bg-slate-700/50">
                            <tr class="text-xs font-semibold tracking-wider uppercase text-slate-600 dark:text-slate-300">
                                <th class="px-4 py-3 text-left">Relación del Tutor</th>
                                <th class="px-4 py-3 text-left">Nombres y Apellidos del Tutor</th>
                                <th class="px-4 py-3 text-left">Programas (de Participantes)</th>
                                <th class="px-4 py-3 text-left">Participantes (Nombre y Grado)</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y dark:bg-slate-800 divide-slate-200 dark:divide-slate-700">
                            @forelse ($tutors ?? [] as $tutor)
                                <tr class="transition-colors duration-150 hover:bg-sky-50/70 dark:hover:bg-slate-700/30">
                                    <td class="px-4 py-3 align-top text-slate-600 dark:text-slate-300">
                                        {{ $tutor['tipos_tutor_str'] ?? 'No especificado' }}
                                    </td>
                                    <td class="px-4 py-3 font-medium align-top text-slate-900 dark:text-slate-100">{{ $tutor['nombres_y_apellidos_tutor_principal'] ?? $tutor['identificador_tutor'] }}</td>
                                    <td class="px-4 py-3 align-top text-slate-600 dark:text-slate-300">
                                        @if(!empty($tutor['programas_asociados_str']))
                                            @php $programasTutor = explode(',', $tutor['programas_asociados_str']); @endphp
                                            <div class="flex flex-col space-y-1">
                                                @foreach($programasTutor as $progTutor)
                                                    <span class="px-2 py-0.5 bg-blue-100 dark:bg-blue-700/50 text-blue-700 dark:text-blue-200 rounded-full text-xxs font-semibold inline-block">{{ trim($progTutor) }}</span>
                                                @endforeach
                                            </div>
                                        @else
                                            N/A
                                        @endif
                                    </td>
                                    <td class="px-4 py-3 align-top text-slate-600 dark:text-slate-300">
                                        @if (!empty($tutor['participantes']))
                                            <ul class="space-y-1.5">
                                                @foreach ($tutor['participantes'] as $participante)
                                                    <li class="text-xs">
                                                        <span class="font-medium text-slate-800 dark:text-slate-200">{{ $participante['nombre_completo'] ?? 'N/A' }}</span>
                                                        <span class="text-slate-500 dark:text-slate-400">({{ $participante['grado_p'] ?? 'N/A' }})</span>
                                                    </li>
                                                @endforeach
                                            </ul>
                                        @else
                                            <span class="text-xs italic text-slate-400 dark:text-slate-500">No hay participantes asociados.</span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="px-6 py-12 text-center">
                                        <div class="flex flex-col items-center">
                                            <svg class="w-12 h-12 mb-3 text-slate-300 dark:text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                            <p class="text-sm text-slate-500 dark:text-slate-400">No hay tutores disponibles para los filtros seleccionados.</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                 @if (isset($tutors) && $tutors instanceof \Illuminate\Pagination\LengthAwarePaginator && $tutors->hasPages())
                    <div class="px-6 py-4 border-t bg-slate-50 dark:bg-slate-700/50 border-slate-200 dark:border-slate-700 rounded-b-xl">
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
        /* Custom scrollbar for Webkit browsers */
        .overflow-x-auto::-webkit-scrollbar { height: 8px; }
        .overflow-x-auto::-webkit-scrollbar-track { background: #f1f5f9; border-radius: 10px; }
        .dark .overflow-x-auto::-webkit-scrollbar-track { background: #334155; } /* slate-700 */
        .overflow-x-auto::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 10px; }
        .dark .overflow-x-auto::-webkit-scrollbar-thumb { background: #475569; } /* slate-600 */
        .overflow-x-auto::-webkit-scrollbar-thumb:hover { background: #94a3b8; }
        .dark .overflow-x-auto::-webkit-scrollbar-thumb:hover { background: #64748b; } /* slate-500 */

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

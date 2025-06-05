<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between w-full px-4 py-6 mx-auto max-w-7xl sm:px-6 lg:px-8">
        <h1 class="text-2xl font-bold text-transparent lg:text-3xl bg-clip-text bg-gradient-to-r from-indigo-600 via-purple-600 to-pink-500">
            {{ __('Programas y Métricas') }}
        </h1>
        </div>
    </x-slot>

    {{-- Main content area with gradient background --}}
    <div class="min-h-screen py-8 bg-gradient-to-br from-slate-50 via-purple-50 to-pink-50 dark:from-slate-800 dark:via-purple-900 dark:to-pink-900">
        <div class="px-4 mx-auto max-w-7xl sm:px-6 lg:px-8">

            {{-- Filter and Summary Banner --}}
            <div class="mb-8">
                <div class="p-6 shadow-xl bg-white/70 dark:bg-slate-800/80 backdrop-blur-lg rounded-2xl">
                    <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                        <div>
                            <h3 class="mb-1 text-xl font-semibold text-transparent bg-clip-text bg-gradient-to-r from-indigo-600 via-purple-600 to-pink-500">
                                {{ $selectedProgramFilter ? "Métricas para: " . $selectedProgramFilter : 'Resumen de Métricas de Programas' }}
                            </h3>
                            <p class="text-sm text-slate-600 dark:text-slate-300">
                                Analiza el rendimiento y los datos demográficos de los participantes.
                            </p>
                        </div>
                        <div class="flex items-center">
                            <label for="programa_filter_select" class="mr-2 text-sm font-medium text-slate-700 dark:text-slate-200 whitespace-nowrap">Filtrar por programa:</label>
                            <select id="programa_filter_select" name="programa"
                                    onchange="window.location.href='?programa='+encodeURIComponent(this.value)"
                                    class="block w-full sm:w-auto border-slate-300 dark:border-slate-600 dark:bg-slate-700 dark:text-slate-300 rounded-xl shadow-sm focus:ring-indigo-500 dark:focus:ring-indigo-600 focus:border-indigo-500 dark:focus:border-indigo-500 text-sm py-2.5 pl-3 pr-8 transition duration-150 ease-in-out">
                                <option value="">Todos los programas</option>
                                @foreach ($programOptions ?? [] as $programOption)
                                    <option value="{{ $programOption }}" {{ ($selectedProgramFilter ?? null) === $programOption ? 'selected' : '' }}>
                                        {{ $programOption }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Key Metrics Section --}}
            <div class="grid grid-cols-1 gap-6 mb-8 md:grid-cols-3">
                {{-- Metric Card: Total Active/Selected Programs --}}
                <div class="flex items-center p-6 transition-shadow duration-300 shadow-lg bg-white/70 dark:bg-slate-800/80 backdrop-blur-lg rounded-2xl hover:shadow-xl">
                    <div class="flex-shrink-0 bg-gradient-to-br from-indigo-500 to-purple-600 rounded-xl p-3.5 mr-4 shadow-md">
                        <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                             <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                        </svg>
                    </div>
                    <div class="ml-1">
                        <h4 class="text-sm font-medium text-slate-500 dark:text-slate-400">Programas Activos/Filtro</h4>
                        <p class="text-2xl font-bold text-slate-800 dark:text-slate-100">{{ $selectedProgramFilter ? 1 : count($programOptions ?? []) }}</p>
                    </div>
                </div>

                {{-- Metric Card: Total Enrolled in Filter --}}
                <div class="flex items-center p-6 transition-shadow duration-300 shadow-lg bg-white/70 dark:bg-slate-800/80 backdrop-blur-lg rounded-2xl hover:shadow-xl">
                    <div class="flex-shrink-0 bg-gradient-to-br from-green-500 to-emerald-600 rounded-xl p-3.5 mr-4 shadow-md">
                        <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                        </svg>
                    </div>
                    <div class="ml-1">
                        <h4 class="text-sm font-medium text-slate-500 dark:text-slate-400">Total Inscritos (Filtro)</h4>
                        <p class="text-2xl font-bold text-slate-800 dark:text-slate-100">{{ $totalParticipantsInFilter ?? 0 }}</p>
                    </div>
                </div>

                {{-- Metric Card: Average Age in Filter --}}
                <div class="flex items-center p-6 transition-shadow duration-300 shadow-lg bg-white/70 dark:bg-slate-800/80 backdrop-blur-lg rounded-2xl hover:shadow-xl">
                    <div class="flex-shrink-0 bg-gradient-to-br from-sky-500 to-cyan-600 rounded-xl p-3.5 mr-4 shadow-md">
                        <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                             <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                    </div>
                    <div class="ml-1">
                        <h4 class="text-sm font-medium text-slate-500 dark:text-slate-400">Edad Promedio (Filtro)</h4>
                        <p class="text-2xl font-bold text-slate-800 dark:text-slate-100">{{ number_format($averageAge ?? 0, 1) }} años</p>
                    </div>
                </div>
            </div>

            {{-- Detailed Tables Section --}}
            <div class="grid grid-cols-1 gap-6 lg:grid-cols-2">
                {{-- Table: Participants by Grade --}}
                <div class="p-6 transition-shadow duration-300 shadow-lg bg-white/70 dark:bg-slate-800/80 backdrop-blur-lg rounded-2xl hover:shadow-xl">
                    <h3 class="mb-4 font-semibold text-md text-slate-700 dark:text-slate-200">Participantes por Grado (Filtro Actual)</h3>
                    <div class="overflow-x-auto">
                        <table class="min-w-full text-sm divide-y divide-slate-200 dark:divide-slate-700">
                            <thead class="bg-slate-100 dark:bg-slate-700/50">
                                <tr class="text-xs font-semibold tracking-wider uppercase text-slate-600 dark:text-slate-300">
                                    <th class="px-4 py-3 text-left">Grado</th>
                                    <th class="px-4 py-3 text-right">Inscritos</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-200 dark:divide-slate-700">
                                @forelse ($participantsByGrade ?? [] as $grade => $count)
                                    <tr class="hover:bg-slate-50 dark:hover:bg-slate-700/30">
                                        <td class="px-4 py-3 text-slate-800 dark:text-slate-200">
                                            <a href="{{ route('participante.indexByGrade', ['grado' => urlencode($grade), 'search_programa' => $selectedProgramFilter ?? '']) }}"
                                               class="text-indigo-600 dark:text-indigo-400 hover:text-indigo-800 dark:hover:text-indigo-300 hover:underline">
                                                {{ $grade }}
                                            </a>
                                        </td>
                                        <td class="px-4 py-3 text-right text-slate-600 dark:text-slate-300">{{ $count }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="2" class="px-4 py-3 text-center text-slate-500 dark:text-slate-400">No hay datos disponibles para este filtro.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                {{-- Table: Participants by Gender --}}
                <div class="p-6 transition-shadow duration-300 shadow-lg bg-white/70 dark:bg-slate-800/80 backdrop-blur-lg rounded-2xl hover:shadow-xl">
                    <h3 class="mb-4 font-semibold text-md text-slate-700 dark:text-slate-200">Participantes por Género (Filtro Actual)</h3>
                    <div class="overflow-x-auto">
                        <table class="min-w-full text-sm divide-y divide-slate-200 dark:divide-slate-700">
                            <thead class="bg-slate-100 dark:bg-slate-700/50">
                                <tr class="text-xs font-semibold tracking-wider uppercase text-slate-600 dark:text-slate-300">
                                    <th class="px-4 py-3 text-left">Género</th>
                                    <th class="px-4 py-3 text-right">Inscritos</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-200 dark:divide-slate-700">
                                @forelse ($participantsByGender ?? [] as $gender => $count)
                                    <tr class="hover:bg-slate-50 dark:hover:bg-slate-700/30">
                                        <td class="px-4 py-3 text-slate-800 dark:text-slate-200">{{ $gender }}</td>
                                        <td class="px-4 py-3 text-right text-slate-600 dark:text-slate-300">{{ $count }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="2" class="px-4 py-3 text-center text-slate-500 dark:text-slate-400">No hay datos disponibles para este filtro.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                {{-- Table: Participants by Age Group --}}
                <div class="p-6 transition-shadow duration-300 shadow-lg bg-white/70 dark:bg-slate-800/80 backdrop-blur-lg rounded-2xl hover:shadow-xl">
                    <h3 class="mb-4 font-semibold text-md text-slate-700 dark:text-slate-200">Participantes por Grupo de Edad (Filtro Actual)</h3>
                    <div class="overflow-x-auto">
                        <table class="min-w-full text-sm divide-y divide-slate-200 dark:divide-slate-700">
                            <thead class="bg-slate-100 dark:bg-slate-700/50">
                                <tr class="text-xs font-semibold tracking-wider uppercase text-slate-600 dark:text-slate-300">
                                    <th class="px-4 py-3 text-left">Rango de Edad</th>
                                    <th class="px-4 py-3 text-right">Inscritos</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-200 dark:divide-slate-700">
                                @forelse ($participantsByAgeGroup ?? [] as $ageGroup => $count)
                                    <tr class="hover:bg-slate-50 dark:hover:bg-slate-700/30">
                                        <td class="px-4 py-3 text-slate-800 dark:text-slate-200">{{ $ageGroup }}</td>
                                        <td class="px-4 py-3 text-right text-slate-600 dark:text-slate-300">{{ $count }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="2" class="px-4 py-3 text-center text-slate-500 dark:text-slate-400">No hay datos disponibles para este filtro.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                {{-- Table: Participants by Sub-Program/Code (if applicable) --}}
                @if(isset($participantsBySubProgram) && !empty($participantsBySubProgram))
                <div class="p-6 transition-shadow duration-300 shadow-lg bg-white/70 dark:bg-slate-800/80 backdrop-blur-lg rounded-2xl hover:shadow-xl">
                    <h3 class="mb-4 font-semibold text-md text-slate-700 dark:text-slate-200">Detalle por Código/Sub-Programa (Filtro Actual)</h3>
                    <div class="overflow-x-auto">
                        <table class="min-w-full text-sm divide-y divide-slate-200 dark:divide-slate-700">
                            <thead class="bg-slate-100 dark:bg-slate-700/50">
                                <tr class="text-xs font-semibold tracking-wider uppercase text-slate-600 dark:text-slate-300">
                                    <th class="px-4 py-3 text-left">Código/Sub-Programa</th>
                                    <th class="px-4 py-3 text-right">Inscritos</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-200 dark:divide-slate-700">
                                @forelse ($participantsBySubProgram as $subProgram => $count)
                                    <tr class="hover:bg-slate-50 dark:hover:bg-slate-700/30">
                                        <td class="px-4 py-3 text-slate-800 dark:text-slate-200">{{ $subProgram }}</td>
                                        <td class="px-4 py-3 text-right text-slate-600 dark:text-slate-300">{{ $count }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="2" class="px-4 py-3 text-center text-slate-500 dark:text-slate-400">No hay datos de sub-programas disponibles para este filtro.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>

<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-slate-800 leading-tight">
            {{ __('Programas y Métricas') }}
        </h2>
    </x-slot>

    {{-- Main content area with gradient background --}}
    <div class="py-8 bg-gradient-to-br from-slate-50 via-purple-50 to-pink-50 min-h-screen">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            
            {{-- Filter and Summary Banner --}}
            <div class="mb-8">
                <div class="bg-white/70 backdrop-blur-lg shadow-xl rounded-2xl p-6">
                    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                        <div>
                            <h3 class="text-xl font-semibold text-transparent bg-clip-text bg-gradient-to-r from-indigo-600 via-purple-600 to-pink-500 mb-1">
                                {{ $selectedProgramFilter ? "Métricas para: " . $selectedProgramFilter : 'Resumen de Métricas de Programas' }}
                            </h3>
                            <p class="text-slate-600 text-sm">
                                Analiza el rendimiento y los datos demográficos de los participantes.
                            </p>
                        </div>
                        <div class="flex items-center">
                            <label for="programa_filter_select" class="mr-2 text-sm font-medium text-slate-700 whitespace-nowrap">Filtrar por programa:</label>
                            <select id="programa_filter_select" name="programa" 
                                    onchange="window.location.href='?programa='+encodeURIComponent(this.value)" 
                                    class="block w-full sm:w-auto border-slate-300 rounded-xl shadow-sm focus:ring-indigo-500 focus:border-indigo-500 text-sm py-2.5 pl-3 pr-8 transition duration-150 ease-in-out">
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
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                {{-- Metric Card: Total Active/Selected Programs --}}
                <div class="bg-white/70 backdrop-blur-lg shadow-lg rounded-2xl p-6 flex items-center hover:shadow-xl transition-shadow duration-300">
                    <div class="flex-shrink-0 bg-gradient-to-br from-indigo-500 to-purple-600 rounded-xl p-3.5 mr-4 shadow-md">
                        <svg class="h-10 w-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                             <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                        </svg>
                    </div>
                    <div class="ml-1">
                        <h4 class="text-sm font-medium text-slate-500">Programas Activos/Filtro</h4>
                        <p class="text-2xl font-bold text-slate-800">{{ $selectedProgramFilter ? 1 : count($programOptions ?? []) }}</p>
                    </div>
                </div>

                {{-- Metric Card: Total Enrolled in Filter --}}
                <div class="bg-white/70 backdrop-blur-lg shadow-lg rounded-2xl p-6 flex items-center hover:shadow-xl transition-shadow duration-300">
                    <div class="flex-shrink-0 bg-gradient-to-br from-green-500 to-emerald-600 rounded-xl p-3.5 mr-4 shadow-md">
                        <svg class="h-10 w-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                        </svg>
                    </div>
                    <div class="ml-1">
                        <h4 class="text-sm font-medium text-slate-500">Total Inscritos (Filtro)</h4>
                        <p class="text-2xl font-bold text-slate-800">{{ $totalParticipantsInFilter ?? 0 }}</p>
                    </div>
                </div>

                {{-- Metric Card: Average Age in Filter --}}
                <div class="bg-white/70 backdrop-blur-lg shadow-lg rounded-2xl p-6 flex items-center hover:shadow-xl transition-shadow duration-300">
                    <div class="flex-shrink-0 bg-gradient-to-br from-sky-500 to-cyan-600 rounded-xl p-3.5 mr-4 shadow-md">
                        <svg class="h-10 w-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                             <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                    </div>
                    <div class="ml-1">
                        <h4 class="text-sm font-medium text-slate-500">Edad Promedio (Filtro)</h4>
                        <p class="text-2xl font-bold text-slate-800">{{ number_format($averageAge ?? 0, 1) }} años</p>
                    </div>
                </div>
            </div>

            {{-- Detailed Tables Section --}}
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                {{-- Table: Participants by Grade --}}
                <div class="bg-white/70 backdrop-blur-lg shadow-lg rounded-2xl p-6 hover:shadow-xl transition-shadow duration-300">
                    <h3 class="text-md font-semibold text-slate-700 mb-4">Participantes por Grado (Filtro Actual)</h3>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-slate-200 text-sm">
                            <thead class="bg-slate-100">
                                <tr class="text-xs font-semibold text-slate-600 uppercase tracking-wider">
                                    <th class="px-4 py-3 text-left">Grado</th>
                                    <th class="px-4 py-3 text-right">Inscritos</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-200">
                                @forelse ($participantsByGrade ?? [] as $grade => $count)
                                    <tr class="hover:bg-slate-50">
                                        <td class="px-4 py-3 text-slate-800">
                                            <a href="{{ route('participante.indexByGrade', ['grado' => urlencode($grade), 'search_programa' => $selectedProgramFilter ?? '']) }}"
                                               class="text-indigo-600 hover:text-indigo-800 hover:underline">
                                                {{ $grade }}
                                            </a>
                                        </td>
                                        <td class="px-4 py-3 text-right text-slate-600">{{ $count }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="2" class="px-4 py-3 text-slate-500 text-center">No hay datos disponibles para este filtro.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                {{-- Table: Participants by Gender --}}
                <div class="bg-white/70 backdrop-blur-lg shadow-lg rounded-2xl p-6 hover:shadow-xl transition-shadow duration-300">
                    <h3 class="text-md font-semibold text-slate-700 mb-4">Participantes por Género (Filtro Actual)</h3>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-slate-200 text-sm">
                            <thead class="bg-slate-100">
                                <tr class="text-xs font-semibold text-slate-600 uppercase tracking-wider">
                                    <th class="px-4 py-3 text-left">Género</th>
                                    <th class="px-4 py-3 text-right">Inscritos</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-200">
                                @forelse ($participantsByGender ?? [] as $gender => $count)
                                    <tr class="hover:bg-slate-50">
                                        <td class="px-4 py-3 text-slate-800">{{ $gender }}</td>
                                        <td class="px-4 py-3 text-right text-slate-600">{{ $count }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="2" class="px-4 py-3 text-slate-500 text-center">No hay datos disponibles para este filtro.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                {{-- Table: Participants by Age Group --}}
                <div class="bg-white/70 backdrop-blur-lg shadow-lg rounded-2xl p-6 hover:shadow-xl transition-shadow duration-300">
                    <h3 class="text-md font-semibold text-slate-700 mb-4">Participantes por Grupo de Edad (Filtro Actual)</h3>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-slate-200 text-sm">
                            <thead class="bg-slate-100">
                                <tr class="text-xs font-semibold text-slate-600 uppercase tracking-wider">
                                    <th class="px-4 py-3 text-left">Rango de Edad</th>
                                    <th class="px-4 py-3 text-right">Inscritos</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-200">
                                @forelse ($participantsByAgeGroup ?? [] as $ageGroup => $count)
                                    <tr class="hover:bg-slate-50">
                                        <td class="px-4 py-3 text-slate-800">{{ $ageGroup }}</td>
                                        <td class="px-4 py-3 text-right text-slate-600">{{ $count }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="2" class="px-4 py-3 text-slate-500 text-center">No hay datos disponibles para este filtro.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                {{-- Table: Participants by Sub-Program/Code (if applicable) --}}
                @if(isset($participantsBySubProgram) && !empty($participantsBySubProgram))
                <div class="bg-white/70 backdrop-blur-lg shadow-lg rounded-2xl p-6 hover:shadow-xl transition-shadow duration-300">
                    <h3 class="text-md font-semibold text-slate-700 mb-4">Detalle por Código/Sub-Programa (Filtro Actual)</h3>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-slate-200 text-sm">
                            <thead class="bg-slate-100">
                                <tr class="text-xs font-semibold text-slate-600 uppercase tracking-wider">
                                    <th class="px-4 py-3 text-left">Código/Sub-Programa</th>
                                    <th class="px-4 py-3 text-right">Inscritos</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-200">
                                @forelse ($participantsBySubProgram as $subProgram => $count)
                                    <tr class="hover:bg-slate-50">
                                        <td class="px-4 py-3 text-slate-800">{{ $subProgram }}</td>
                                        <td class="px-4 py-3 text-right text-slate-600">{{ $count }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="2" class="px-4 py-3 text-slate-500 text-center">No hay datos de sub-programas disponibles para este filtro.</td>
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

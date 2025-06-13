<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-transparent lg:text-3xl bg-clip-text bg-gradient-to-r from-indigo-600 via-purple-600 to-pink-500">
                    {{ __('Métricas de Programas') }}
                </h1>
                <p class="mt-1 text-xs text-slate-600 dark:text-slate-400 sm:text-sm">
                    Visualiza y analiza los datos de los participantes por programa y lugar.
                </p>
            </div>
        </div>
    </x-slot>

    {{-- Scripts para pasar datos del backend al JS de forma segura --}}
    <script id="genderChartData" type="application/json">@json($participantsByGender)</script>
    <script id="gradeChartData" type="application/json">@json($participantsByGrade)</script>
    <script id="ageGroupChartData" type="application/json">@json($participantsByAgeGroup)</script>
    <script id="subProgramChartData" type="application/json">@json($participantsBySubProgram)</script>

    <div class="py-8">
        <div class="px-4 mx-auto max-w-7xl sm:px-6 lg:px-8">

            {{-- Filtros --}}
            <div class="mb-8">
                <div class="p-6 shadow-xl bg-white/70 dark:bg-slate-800/80 backdrop-blur-lg rounded-2xl">
                    <form id="filtersForm" method="GET" action="{{ route('programas') }}" class="grid items-end grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-4">
                        <div>
                            <label for="programa_filter" class="block text-sm font-medium text-slate-700 dark:text-slate-300">Programa</label>
                            <select name="programa" id="programa_filter" class="block w-full mt-1 text-sm border-gray-300 shadow-sm rounded-xl dark:border-slate-600 dark:bg-slate-900/50 dark:text-slate-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600">
                                <option value="">Todos los programas</option>
                                @foreach($programOptions as $option)
                                    <option value="{{ $option }}" {{ $selectedProgram == $option ? 'selected' : '' }}>{{ $option }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label for="lugar_filter" class="block text-sm font-medium text-slate-700 dark:text-slate-300">Lugar de Encuentro</label>
                            <select name="lugar" id="lugar_filter" class="block w-full mt-1 text-sm border-gray-300 shadow-sm rounded-xl dark:border-slate-600 dark:bg-slate-900/50 dark:text-slate-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600"
                                    data-ruta-lugares="{{ route('programas.lugares') }}" {{ !$selectedProgram ? 'disabled' : '' }}>
                                <option value="">{{ !$selectedProgram ? 'Seleccione un programa' : 'Todos los lugares' }}</option>
                                @foreach($lugarOptions as $option)
                                    <option value="{{ $option }}" {{ $selectedLugar == $option ? 'selected' : '' }}>{{ $option }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="flex space-x-2 lg:col-span-2">
                            <x-primary-button type="submit" class="w-full"><svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>Filtrar</x-primary-button>
                            <x-secondary-button class="w-full" onclick="window.location.href='{{ route('programas') }}'"><svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>Limpiar</x-secondary-button>
                        </div>
                    </form>
                </div>
            </div>

            {{-- Tarjetas de Métricas Clave --}}
            <div class="grid grid-cols-1 gap-6 mb-8 md:grid-cols-2 lg:grid-cols-3">
                 <div class="flex items-center p-6 transition-shadow duration-300 shadow-lg bg-white/70 dark:bg-slate-800/80 backdrop-blur-lg rounded-2xl hover:shadow-xl">
                    <div class="flex-shrink-0 p-4 mr-4 shadow-md rounded-xl bg-gradient-to-br from-green-500 to-emerald-600">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                    </div>
                    <div>
                        <h4 class="text-sm font-medium text-slate-500 dark:text-slate-400">Total Inscritos (Filtro)</h4>
                        <p class="text-3xl font-bold text-slate-800 dark:text-slate-100">{{ $totalParticipantsInFilter }}</p>
                    </div>
                </div>
                <div class="flex items-center p-6 transition-shadow duration-300 shadow-lg bg-white/70 dark:bg-slate-800/80 backdrop-blur-lg rounded-2xl hover:shadow-xl">
                    <div class="flex-shrink-0 p-4 mr-4 shadow-md rounded-xl bg-gradient-to-br from-sky-500 to-cyan-600">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                    </div>
                    <div>
                        <h4 class="text-sm font-medium text-slate-500 dark:text-slate-400">Edad Promedio (Filtro)</h4>
                        <p class="text-3xl font-bold text-slate-800 dark:text-slate-100">{{ number_format($averageAge, 1) }} años</p>
                    </div>
                </div>
                 <div class="flex items-center p-6 transition-shadow duration-300 shadow-lg bg-white/70 dark:bg-slate-800/80 backdrop-blur-lg rounded-2xl hover:shadow-xl">
                    <div class="flex-shrink-0 p-4 mr-4 shadow-md rounded-xl bg-gradient-to-br from-indigo-500 to-purple-600">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
                    </div>
                    <div>
                        <h4 class="text-sm font-medium text-slate-500 dark:text-slate-400">Programa Activo</h4>
                        <p class="text-2xl font-bold truncate text-slate-800 dark:text-slate-100">{{ $selectedProgram ?: 'Todos' }}</p>
                    </div>
                </div>
            </div>

            {{-- Gráfico y Tabla por Género --}}
            @if($participantsByGender->isNotEmpty())
            <div class="p-6 mt-6 shadow-lg bg-white/60 dark:bg-slate-800/70 backdrop-blur-lg rounded-2xl">
                <h3 class="mb-4 text-lg font-semibold text-slate-700 dark:text-slate-200">Distribución por Género</h3>
                <div class="grid grid-cols-1 gap-6 md:grid-cols-3">
                    <div class="relative h-56 md:col-span-1">
                        <canvas id="genderChart"></canvas>
                    </div>
                    <div class="md:col-span-2">
                        @include('programas.partials.stats_table', ['data' => $participantsByGender, 'title' => 'Género'])
                    </div>
                </div>
            </div>
            @endif

            {{-- Gráficos y Tablas de Demografía --}}
            <div class="grid grid-cols-1 gap-6 mt-6 lg:grid-cols-2">
                {{-- Grado --}}
                @if($participantsByGrade->isNotEmpty())
                <div class="p-6 shadow-lg bg-white/60 dark:bg-slate-800/70 backdrop-blur-lg rounded-2xl">
                    <h3 class="mb-4 text-lg font-semibold text-slate-700 dark:text-slate-200">Participantes por Grado</h3>
                    <div class="relative h-64">
                        <canvas id="gradeChart"></canvas>
                    </div>
                </div>
                @endif
                {{-- Edad --}}
                @if($participantsByAgeGroup->isNotEmpty())
                <div class="p-6 shadow-lg bg-white/60 dark:bg-slate-800/70 backdrop-blur-lg rounded-2xl">
                    <h3 class="mb-4 text-lg font-semibold text-slate-700 dark:text-slate-200">Participantes por Grupo de Edad</h3>
                    <div class="relative h-64">
                        <canvas id="ageGroupChart"></canvas>
                    </div>
                </div>
                @endif
            </div>

             {{-- Gráfico de Sub-Programas --}}
            @if($participantsBySubProgram->isNotEmpty())
            <div class="p-6 mt-6 shadow-lg bg-white/60 dark:bg-slate-800/70 backdrop-blur-lg rounded-2xl">
                <h3 class="mb-4 text-lg font-semibold text-slate-700 dark:text-slate-200">Participantes por Programa</h3>
                <div class="relative h-80">
                    <canvas id="subProgramChart"></canvas>
                </div>
            </div>
            @endif
        </div>
    </div>
    @push('scripts')
        <script type="module" src="{{ Vite::asset('resources/js/pages/estadisticas-programas.js') }}"></script>
    @endpush
</x-app-layout>

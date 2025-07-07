<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between w-full px-4 py-6 mx-auto max-w-7xl sm:px-6 lg:px-8">
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
                <div class="p-6 transition-all duration-300 shadow-md bg-white/70 dark:bg-slate-800/80 rounded-3xl hover:shadow-lg">
                    <form id="filtersForm" method="GET" action="{{ route('programas') }}" class="grid items-end grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-4">

                        {{-- Custom Select for Programa --}}
                        <div>
                            <label for="programa_filter" class="block text-sm font-medium text-slate-700 dark:text-slate-300">Programa</label>
                            <div x-data="{ open: false, selected: '{{ $selectedProgram ?? '' }}' }" class="relative mt-1">
                                <button @click="open = !open" type="button" class="relative w-full py-2 pl-3 pr-10 text-left bg-white border border-gray-300 shadow-sm dark:bg-slate-900/50 dark:border-slate-600 rounded-3xl focus:outline-none focus:ring-2 focus:ring-indigo-500 sm:text-sm">
                                    <span class="flex items-center"><span class="block truncate" x-text="selected || 'Todos los programas'"></span></span>
                                    <span class="absolute inset-y-0 right-0 flex items-center pr-2 pointer-events-none"><svg class="w-5 h-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M10 3a.75.75 0 01.53.22l3.5 3.5a.75.75 0 01-1.06 1.06L10 4.81 6.53 8.28a.75.75 0 01-1.06-1.06l3.5-3.5A.75.75 0 0110 3zm-3.72 9.53a.75.75 0 011.06 0L10 15.19l3.47-3.47a.75.75 0 111.06 1.06l-4 4a.75.75 0 01-1.06 0l-4-4a.75.75 0 010-1.06z" clip-rule="evenodd" /></svg></span>
                                </button>
                                <div x-show="open" @click.away="open = false" x-transition class="absolute z-10 w-full mt-1 overflow-auto text-base bg-white shadow-lg rounded-3xl dark:bg-slate-800 max-h-60 ring-1 ring-black ring-opacity-5 focus:outline-none sm:text-sm" style="display: none;">
                                    <div @click="selected = ''; open = false; $nextTick(() => $refs.select.dispatchEvent(new Event('change')))" class="relative py-2 pl-3 text-gray-900 cursor-default select-none pr-9 hover:bg-indigo-600 hover:text-white dark:text-white"><span class="block truncate">Todos los programas</span></div>
                                    @foreach($programOptions as $option)
                                        <div @click="selected = '{{ $option }}'; open = false; $nextTick(() => $refs.select.dispatchEvent(new Event('change')))" :class="{'bg-indigo-600 text-white': selected === '{{ $option }}'}" class="relative py-2 pl-3 text-gray-900 cursor-default select-none pr-9 hover:bg-indigo-600 hover:text-white dark:text-white">
                                            <span class="block truncate" :class="{'font-semibold': selected === '{{ $option }}'}">{{ $option }}</span>
                                            <span x-show="selected === '{{ $option }}'" class="absolute inset-y-0 right-0 flex items-center pr-4 text-white"><svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M16.704 4.153a.75.75 0 01.143 1.052l-8 10.5a.75.75 0 01-1.127.075l-4.5-4.5a.75.75 0 011.06-1.06l3.894 3.893 7.48-9.817a.75.75 0 011.052-.143z" clip-rule="evenodd" /></svg></span>
                                        </div>
                                    @endforeach
                                </div>
                                <select x-ref="select" name="programa" id="programa_filter" x-model="selected" class="hidden">
                                     <option value="">Todos los programas</option>
                                    @foreach($programOptions as $option)
                                        <option value="{{ $option }}">{{ $option }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        {{-- Custom Select for Lugar --}}
                        <div >
                            <label for="lugar_filter" class="block text-sm font-medium text-slate-700 dark:text-slate-300">Lugar de encuentro</label>
                            <div x-data="{ open: false, selected: '{{ $selectedLugar ?? '' }}', disabled: true, options: [] }" x-init="
                                const selectEl = $refs.select;
                                const syncState = () => {
                                    disabled = selectEl.disabled;
                                    options = Array.from(selectEl.options).map(o => ({ value: o.value, text: o.text }));
                                    if (selected !== selectEl.value) {
                                        selected = selectEl.value;
                                    }
                                };
                                const observer = new MutationObserver(syncState);
                                observer.observe(selectEl, { attributes: true, childList: true, subtree: true });
                                syncState();
                            " class="relative mt-1">
                                <button @click="open = !open" type="button" :disabled="disabled" class="relative w-full py-2 pl-3 pr-10 text-left bg-white border border-gray-300 shadow-sm dark:bg-slate-900/50 dark:border-slate-600 rounded-3xl focus:outline-none focus:ring-2 focus:ring-indigo-500 sm:text-sm disabled:bg-slate-100 dark:disabled:bg-slate-800 disabled:cursor-not-allowed">
                                    <span class="flex items-center"><span class="block truncate" x-text="selected || (disabled ? 'Seleccione un programa' : 'Todos los lugares')"></span></span>
                                    <span class="absolute inset-y-0 right-0 flex items-center pr-2 pointer-events-none"><svg class="w-5 h-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M10 3a.75.75 0 01.53.22l3.5 3.5a.75.75 0 01-1.06 1.06L10 4.81 6.53 8.28a.75.75 0 01-1.06-1.06l3.5-3.5A.75.75 0 0110 3zm-3.72 9.53a.75.75 0 011.06 0L10 15.19l3.47-3.47a.75.75 0 111.06 1.06l-4 4a.75.75 0 01-1.06 0l-4-4a.75.75 0 010-1.06z" clip-rule="evenodd" /></svg></span>
                                </button>
                                <div x-show="open" @click.away="open = false" x-transition class="absolute z-10 w-full mt-1 overflow-auto text-base bg-white shadow-lg rounded-3xl dark:bg-slate-800 max-h-60 ring-1 ring-black ring-opacity-5 focus:outline-none sm:text-sm" style="display: none;">
                                    <template x-for="option in options" :key="option.value">
                                        <div @click="selected = option.value; open = false;" :class="{'bg-indigo-600 text-white': selected === option.value}" class="relative py-2 pl-3 text-gray-900 cursor-default select-none pr-9 hover:bg-indigo-600 hover:text-white dark:text-white">
                                            <span class="block truncate" x-text="option.text" :class="{'font-semibold': selected === option.value}"></span>
                                            <span x-show="selected === option.value" class="absolute inset-y-0 right-0 flex items-center pr-4 text-white"><svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M16.704 4.153a.75.75 0 01.143 1.052l-8 10.5a.75.75 0 01-1.127.075l-4.5-4.5a.75.75 0 011.06-1.06l3.894 3.893 7.48-9.817a.75.75 0 011.052-.143z" clip-rule="evenodd" /></svg></span>
                                        </div>
                                    </template>
                                </div>
                                <select x-ref="select" name="lugar" id="lugar_filter" x-model="selected" class="hidden" data-ruta-lugares="{{ route('programas.lugares') }}" {{ !$selectedProgram ? 'disabled' : '' }}>
                                    <option value="">{{ !$selectedProgram ? 'Seleccione un programa' : 'Todos los lugares' }}</option>
                                    @foreach($lugarOptions as $option)
                                        <option value="{{ $option }}" {{ $selectedLugar == $option ? 'selected' : '' }}>{{ $option }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="flex items-center justify-between space-x-2 lg:col-span-2">
                            <x-primary-button type="submit" class="w-full py-2 pl-3 pr-10 text-center shadow-sm rounded-3xl focus:outline-none focus:ring-2 focus:ring-indigo-500 sm:text-sm "><svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>Filtrar</x-primary-button>
                            <x-secondary-button class="w-full py-2 pl-3 pr-10 text-center shadow-sm rounded-3xl focus:outline-none focus:ring-2 focus:ring-indigo-500 sm:text-sm " onclick="window.location.href='{{ route('programas') }}'"><svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>Limpiar</x-secondary-button>
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

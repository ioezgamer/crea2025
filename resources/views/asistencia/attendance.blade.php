<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between w-full px-4 py-6 mx-auto max-w-7xl sm:px-6 lg:px-8">
            <h2 class="text-2xl font-bold text-transparent lg:text-3xl bg-clip-text bg-gradient-to-r from-indigo-600 via-purple-600 to-pink-500">
                {{ __('Registro de asistencia') }}
            </h2>
            {{--<x-boton-regresar onclick="window.location.href='{{ route('dashboard') }}'" />--}}
        </div>
    </x-slot>

    <div class="min-h-screen py-4 bg-gradient-to-br from-slate-50 via-purple-50 to-pink-50 dark:from-slate-800 dark:via-purple-900 dark:to-pink-900">
        <div class="max-w-full px-2 mx-auto sm:px-2 lg:px-1.5">
            {{-- Filter Section Card --}}
            <div class="relative z-10 p-4 mb-4 shadow-sm bg-gradient-to-r from-indigo-600/5 via-purple-600/5 to-pink-500/5 dark:bg-slate-800/80 backdrop-blur-sm rounded-3xl ">
                <div class="grid items-end grid-cols-1 gap-4 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-5">

                    {{-- Custom Select for Programa --}}
                    <div>
                        <label for="filtro_programa" class="block mb-1 text-xs font-medium text-slate-700 dark:text-slate-300">Programa <span class="text-red-500">*</span></label>
                        <div x-data="{ open: false, selected: '{{ $selectedPrograma ?? '' }}' }" class="relative">
                            <button @click="open = !open" type="button" class="relative w-full py-2.5 pl-3 pr-10 text-left bg-white dark:bg-slate-700 border border-slate-300 dark:border-slate-600 rounded-3xl shadow-sm cursor-default focus:outline-none focus:ring-2 focus:ring-indigo-500 sm:text-sm">
                                <span class="flex items-center">
                                    <span class="block truncate" x-text="selected || 'Seleccione programa...'"></span>
                                </span>
                                <span class="absolute inset-y-0 right-0 flex items-center pr-2 pointer-events-none">
                                    <svg class="w-5 h-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M10 3a.75.75 0 01.53.22l3.5 3.5a.75.75 0 01-1.06 1.06L10 4.81 6.53 8.28a.75.75 0 01-1.06-1.06l3.5-3.5A.75.75 0 0110 3zm-3.72 9.53a.75.75 0 011.06 0L10 15.19l3.47-3.47a.75.75 0 111.06 1.06l-4 4a.75.75 0 01-1.06 0l-4-4a.75.75 0 010-1.06z" clip-rule="evenodd" /></svg>
                                </span>
                            </button>
                            <div x-show="open" @click.away="open = false" x-transition class="absolute z-50 w-full mt-1 overflow-auto text-base bg-white rounded-md shadow-lg dark:bg-slate-800 max-h-60 ring-1 ring-black ring-opacity-5 focus:outline-none sm:text-sm" style="display: none;">
                                @foreach ($programOptions as $prog)
                                    <div @click="selected = '{{ $prog }}'; open = false; $nextTick(() => $refs.select.dispatchEvent(new Event('change')))" :class="{'bg-indigo-600 text-white': selected === '{{ $prog }}', 'text-gray-900 dark:text-white': !(selected === '{{ $prog }}')}" class="relative py-2 pl-3 cursor-default select-none pr-9 hover:bg-indigo-600 hover:text-white">
                                        <span class="block truncate" :class="{'font-semibold': selected === '{{ $prog }}'}">{{ $prog }}</span>
                                        <span x-show="selected === '{{ $prog }}'" class="absolute inset-y-0 right-0 flex items-center pr-4 text-white"><svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M16.704 4.153a.75.75 0 01.143 1.052l-8 10.5a.75.75 0 01-1.127.075l-4.5-4.5a.75.75 0 011.06-1.06l3.894 3.893 7.48-9.817a.75.75 0 011.052-.143z" clip-rule="evenodd" /></svg></span>
                                    </div>
                                @endforeach
                            </div>
                            <select x-ref="select" name="programa" id="filtro_programa" x-model="selected" class="hidden">
                                <option value="">Seleccione programa...</option>
                                @foreach ($programOptions as $prog) <option value="{{ $prog }}">{{ $prog }}</option> @endforeach
                            </select>
                        </div>
                    </div>

                    {{-- Custom Select for Lugar --}}
                    <div>
                        <label for="filtro_lugar" class="block mb-1 text-xs font-medium text-slate-700 dark:text-slate-300">Lugar <span class="text-red-500">*</span></label>
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
                        " class="relative">
                            <button @click="open = !open" type="button" :disabled="disabled" class="relative w-full py-2.5 pl-3 pr-10 text-left bg-white dark:bg-slate-700 border border-slate-300 dark:border-slate-600 rounded-3xl shadow-sm cursor-default focus:outline-none focus:ring-2 focus:ring-indigo-500 sm:text-sm disabled:bg-slate-100 dark:disabled:bg-slate-800 disabled:cursor-not-allowed">
                                <span class="flex items-center"><span class="block truncate" x-text="selected || 'Seleccione Lugar...'"></span></span>
                                <span class="absolute inset-y-0 right-0 flex items-center pr-2 pointer-events-none"><svg class="w-5 h-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M10 3a.75.75 0 01.53.22l3.5 3.5a.75.75 0 01-1.06 1.06L10 4.81 6.53 8.28a.75.75 0 01-1.06-1.06l3.5-3.5A.75.75 0 0110 3zm-3.72 9.53a.75.75 0 011.06 0L10 15.19l3.47-3.47a.75.75 0 111.06 1.06l-4 4a.75.75 0 01-1.06 0l-4-4a.75.75 0 010-1.06z" clip-rule="evenodd" /></svg></span>
                            </button>
                            <div x-show="open" @click.away="open = false" x-transition class="absolute z-40 w-full mt-1 overflow-auto text-base bg-white rounded-md shadow-lg dark:bg-slate-800 max-h-60 ring-1 ring-black ring-opacity-5 focus:outline-none sm:text-sm" style="display: none;">
                                <template x-for="option in options" :key="option.value">
                                    <div @click="selected = option.value; open = false; $nextTick(() => $refs.select.dispatchEvent(new Event('change')))" :class="{'bg-indigo-600 text-white': selected === option.value, 'text-gray-900 dark:text-white': !(selected === option.value)}" class="relative py-2 pl-3 cursor-default select-none pr-9 hover:bg-indigo-600 hover:text-white">
                                        <span class="block truncate" x-text="option.text" :class="{'font-semibold': selected === option.value}"></span>
                                        <span x-show="selected === option.value" class="absolute inset-y-0 right-0 flex items-center pr-4 text-white"><svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M16.704 4.153a.75.75 0 01.143 1.052l-8 10.5a.75.75 0 01-1.127.075l-4.5-4.5a.75.75 0 011.06-1.06l3.894 3.893 7.48-9.817a.75.75 0 011.052-.143z" clip-rule="evenodd" /></svg></span>
                                    </div>
                                </template>
                            </div>
                            <select x-ref="select" name="lugar_de_encuentro_del_programa" id="filtro_lugar" x-model="selected" class="hidden" disabled>
                                <option value="">Seleccione Lugar...</option>
                                @foreach ($lugarOptions as $lugar) <option value="{{ $lugar }}">{{ $lugar }}</option> @endforeach
                            </select>
                        </div>
                    </div>

                    {{-- Custom Select for Grado --}}
                    <div>
                        <label for="filtro_grado" class="block mb-1 text-xs font-medium text-slate-700 dark:text-slate-300">Grado <span class="text-red-500">*</span></label>
                        <div x-data="{ open: false, selected: '{{ $selectedGrado ?? '' }}', disabled: true, options: [] }" x-init="
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
                        " class="relative">
                            <button @click="open = !open" type="button" :disabled="disabled" class="relative w-full py-2.5 pl-3 pr-10 text-left bg-white dark:bg-slate-700 border border-slate-300 dark:border-slate-600 rounded-3xl shadow-sm cursor-default focus:outline-none focus:ring-2 focus:ring-indigo-500 sm:text-sm disabled:bg-slate-100 dark:disabled:bg-slate-800 disabled:cursor-not-allowed">
                                <span class="flex items-center"><span class="block truncate" x-text="selected || 'Seleccione Grado...'"></span></span>
                                <span class="absolute inset-y-0 right-0 flex items-center pr-2 pointer-events-none"><svg class="w-5 h-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M10 3a.75.75 0 01.53.22l3.5 3.5a.75.75 0 01-1.06 1.06L10 4.81 6.53 8.28a.75.75 0 01-1.06-1.06l3.5-3.5A.75.75 0 0110 3zm-3.72 9.53a.75.75 0 011.06 0L10 15.19l3.47-3.47a.75.75 0 111.06 1.06l-4 4a.75.75 0 01-1.06 0l-4-4a.75.75 0 010-1.06z" clip-rule="evenodd" /></svg></span>
                            </button>
                            <div x-show="open" @click.away="open = false" x-transition class="absolute z-30 w-full mt-1 overflow-auto text-base bg-white rounded-md shadow-lg dark:bg-slate-800 max-h-60 ring-1 ring-black ring-opacity-5 focus:outline-none sm:text-sm" style="display: none;">
                                <template x-for="option in options" :key="option.value">
                                    <div @click="selected = option.value; open = false; $nextTick(() => $refs.select.dispatchEvent(new Event('change')))" :class="{'bg-indigo-600 text-white': selected === option.value, 'text-gray-900 dark:text-white': !(selected === option.value)}" class="relative py-2 pl-3 cursor-default select-none pr-9 hover:bg-indigo-600 hover:text-white">
                                        <span class="block truncate" x-text="option.text" :class="{'font-semibold': selected === option.value}"></span>
                                        <span x-show="selected === option.value" class="absolute inset-y-0 right-0 flex items-center pr-4 text-white"><svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M16.704 4.153a.75.75 0 01.143 1.052l-8 10.5a.75.75 0 01-1.127.075l-4.5-4.5a.75.75 0 011.06-1.06l3.894 3.893 7.48-9.817a.75.75 0 011.052-.143z" clip-rule="evenodd" /></svg></span>
                                    </div>
                                </template>
                            </div>
                             <select x-ref="select" name="grado_p" id="filtro_grado" x-model="selected" class="hidden" disabled>
                                <option value="">Seleccione Grado...</option>
                                @foreach ($gradoOptions as $grado) <option value="{{ $grado }}">{{ $grado }}</option> @endforeach
                            </select>
                        </div>
                    </div>

                    {{-- Custom Select for Tipo Asistencia --}}
                    <div>
                        <label for="filtro_tipo_asistencia" class="block mb-1 text-xs font-medium text-slate-700 dark:text-slate-300">Tipo Asistencia <span class="text-red-500">*</span></label>
                        <div x-data="{ open: false, selected: '{{ $selectedTipoAsistencia ?? 'semanal' }}' }" class="relative">
                            <button @click="open = !open" type="button" class="relative w-full py-2.5 pl-3 pr-10 text-left bg-white dark:bg-slate-700 border border-slate-300 dark:border-slate-600 rounded-3xl shadow-sm cursor-default focus:outline-none focus:ring-2 focus:ring-indigo-500 sm:text-sm">
                                <span class="flex items-center"><span class="block truncate" x-text="selected.charAt(0).toUpperCase() + selected.slice(1)"></span></span>
                                <span class="absolute inset-y-0 right-0 flex items-center pr-2 pointer-events-none"><svg class="w-5 h-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M10 3a.75.75 0 01.53.22l3.5 3.5a.75.75 0 01-1.06 1.06L10 4.81 6.53 8.28a.75.75 0 01-1.06-1.06l3.5-3.5A.75.75 0 0110 3zm-3.72 9.53a.75.75 0 011.06 0L10 15.19l3.47-3.47a.75.75 0 111.06 1.06l-4 4a.75.75 0 01-1.06 0l-4-4a.75.75 0 010-1.06z" clip-rule="evenodd" /></svg></span>
                            </button>
                            <div x-show="open" @click.away="open = false" x-transition class="absolute z-20 w-full mt-1 overflow-auto text-base bg-white rounded-md shadow-lg dark:bg-slate-800 max-h-60 ring-1 ring-black ring-opacity-5 focus:outline-none sm:text-sm" style="display: none;">
                                <div @click="selected = 'semanal'; open = false; $nextTick(() => $refs.select.dispatchEvent(new Event('change')))" :class="{'bg-indigo-600 text-white': selected === 'semanal', 'text-gray-900 dark:text-white': !(selected === 'semanal')}" class="relative py-2 pl-3 cursor-default select-none pr-9 hover:bg-indigo-600 hover:text-white">
                                    <span class="block truncate" :class="{'font-semibold': selected === 'semanal'}">Semanal</span>
                                    <span x-show="selected === 'semanal'" class="absolute inset-y-0 right-0 flex items-center pr-4 text-white"><svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M16.704 4.153a.75.75 0 01.143 1.052l-8 10.5a.75.75 0 01-1.127.075l-4.5-4.5a.75.75 0 011.06-1.06l3.894 3.893 7.48-9.817a.75.75 0 011.052-.143z" clip-rule="evenodd" /></svg></span>
                                </div>
                                <div @click="selected = 'diaria'; open = false; $nextTick(() => $refs.select.dispatchEvent(new Event('change')))" :class="{'bg-indigo-600 text-white': selected === 'diaria', 'text-gray-900 dark:text-white': !(selected === 'diaria')}" class="relative py-2 pl-3 cursor-default select-none pr-9 hover:bg-indigo-600 hover:text-white">
                                    <span class="block truncate" :class="{'font-semibold': selected === 'diaria'}">Diaria</span>
                                    <span x-show="selected === 'diaria'" class="absolute inset-y-0 right-0 flex items-center pr-4 text-white"><svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M16.704 4.153a.75.75 0 01.143 1.052l-8 10.5a.75.75 0 01-1.127.075l-4.5-4.5a.75.75 0 011.06-1.06l3.894 3.893 7.48-9.817a.75.75 0 011.052-.143z" clip-rule="evenodd" /></svg></span>
                                </div>
                                <div @click="selected = 'mensual'; open = false; $nextTick(() => $refs.select.dispatchEvent(new Event('change')))" :class="{'bg-indigo-600 text-white': selected === 'mensual', 'text-gray-900 dark:text-white': !(selected === 'mensual')}" class="relative py-2 pl-3 cursor-default select-none pr-9 hover:bg-indigo-600 hover:text-white">
                                    <span class="block truncate" :class="{'font-semibold': selected === 'mensual'}">Mensual</span>
                                    <span x-show="selected === 'mensual'" class="absolute inset-y-0 right-0 flex items-center pr-4 text-white"><svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M16.704 4.153a.75.75 0 01.143 1.052l-8 10.5a.75.75 0 01-1.127.075l-4.5-4.5a.75.75 0 011.06-1.06l3.894 3.893 7.48-9.817a.75.75 0 011.052-.143z" clip-rule="evenodd" /></svg></span>
                                </div>
                            </div>
                            <select x-ref="select" name="tipo_asistencia" id="filtro_tipo_asistencia" x-model="selected" class="hidden">
                                <option value="semanal">Semanal</option>
                                <option value="diaria">Diaria</option>
                                <option value="mensual">Mensual</option>
                            </select>
                        </div>
                    </div>

                    {{-- Original Date Input --}}
                    <div>
                        <label for="filtro_fecha" class="block mb-1 text-xs font-medium text-slate-700 dark:text-slate-300"><span id="label_fecha">Semana (Lunes)</span> <span class="text-red-500">*</span></label>
                        <input type="date" name="fecha" id="filtro_fecha" value="{{ $fechaInput ?? now()->startOfWeek()->format('Y-m-d') }}" class="mt-1 block w-full rounded-3xl border-slate-300 dark:border-slate-600 dark:bg-slate-700 dark:text-slate-300 text-sm shadow-sm focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-2 focus:ring-indigo-500 dark:focus:ring-indigo-600 py-2.5 placeholder-slate-400 dark:placeholder-slate-500 dark:[color-scheme:dark]">
                    </div>
                </div>
                <div class="mt-4 text-right">
                    <button type="button" id="btn_cargar_participantes" class="inline-flex items-center px-5 py-2.5 bg-gradient-to-r from-indigo-600 to-purple-600 border border-transparent rounded-3xl font-semibold text-xs text-white uppercase tracking-widest hover:from-indigo-700 hover:to-purple-700 focus:outline-none focus:ring-2 focus:ring-purple-500 focus:ring-offset-2 dark:focus:ring-offset-slate-900 transition ease-in-out duration-150 shadow-md hover:shadow-lg disabled:opacity-50 disabled:cursor-not-allowed" disabled>
                        <svg id="spinner_cargar" class="hidden w-4 h-4 mr-2 -ml-1 text-white animate-spin" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        Cargar Participantes
                    </button>
                </div>
            </div>

            {{-- Feedback Messages Area --}}
            <div id="global_feedback_messages" class="mb-4"></div>
            @if ($errors->any())
                <div class="p-4 mb-6 text-xs text-red-700 border-l-4 border-red-500 shadow-md bg-red-50/70 dark:bg-red-700/30 backdrop-blur-md dark:border-red-600 dark:text-red-200 rounded-xl">
                    <p class="font-semibold text-red-800 dark:text-red-100">Por favor corrige los siguientes errores:</p>
                    <ul class="list-disc ml-5 mt-1 space-y-0.5">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            @if (session('success'))
                <div class="p-4 mb-6 text-xs text-green-700 border-l-4 border-green-500 shadow-md bg-green-50/70 dark:bg-green-700/30 backdrop-blur-md dark:border-green-600 dark:text-green-200 rounded-xl">
                    {{ session('success') }}
                </div>
            @endif

            {{-- Attendance Table Container --}}
            <div id="tabla_asistencia_container" class="relative z-0">
                @if ($participantes->isNotEmpty())
                    @include('asistencia.partials.tabla_asistencia', [
                        'participantes' => $participantes,
                        'diasSemana' => $diasSemana,
                        'asistencias' => $asistencias,
                        'selectedPrograma' => $selectedPrograma,
                        'fechaInput' => $fechaInput,
                        'selectedLugar' => $selectedLugar,
                        'selectedGrado' => $selectedGrado,
                        'selectedTipoAsistencia' => $selectedTipoAsistencia ?? 'semanal'
                    ])
                @else
                    <div class="relative p-8 mt-12 text-center shadow-sm bg-white/70 backdrop-blur-lg rounded-3xl">
                        <svg class="w-12 h-12 mx-auto mb-3 text-slate-400 dark:text-slate-500" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true">
                           <path stroke-linecap="round" stroke-linejoin="round" d="M11.25 11.25l.041-.02a.75.75 0 011.063.852l-.708 2.836a.75.75 0 001.063.853l.041-.021M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-9-3.75h.008v.008H12V8.25z" />
                        </svg>
                        <p class="text-sm text-slate-600 dark:text-slate-400">
                            Seleccione todos los filtros (Programa, Lugar, Grado, Tipo Asistencia y Fecha) y presione "Cargar Participantes" para mostrar la tabla de asistencia.
                        </p>
                    </div>
                @endif
            </div>

            {{-- Generate Report Button Container --}}
            <div id="report_button_container" class="mt-6 text-center {{ !$selectedPrograma || $participantes->isEmpty() ? 'hidden' : '' }}">
                 <a href="#" id="link_generar_reporte" class="inline-flex items-center justify-center py-2.5 px-6 bg-gradient-to-r from-green-500 to-emerald-600 border border-transparent rounded-xl font-semibold text-sm text-white shadow-md hover:shadow-lg hover:from-green-600 hover:to-emerald-700 focus:outline-none focus:ring-2 focus:ring-offset-2 dark:focus:ring-offset-slate-900 focus:ring-emerald-500 transition ease-in-out duration-150">
                    <svg class="w-5 h-5 mr-2 -ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                    Generar Reporte de Asistencia
                </a>
            </div>
        </div>
    </div>

{{-- ... tu contenido de la vista ... --}}

<div id="asistenciaConfig" class="hidden"
     data-opciones-lugares-url="{{ route('asistencia.opciones.lugares') }}"
     data-opciones-grados-url="{{ route('asistencia.opciones.grados') }}"
     data-opciones-participantes-url="{{ route('asistencia.opciones.participantes') }}"
     data-store-individual-url="{{ route('asistencia.storeIndividual') }}"
     data-reporte-url-base="{{ route('asistencia.reporte') }}"
     data-csrf-token="{{ csrf_token() }}"
     data-initial-selected-programa="{{ $selectedPrograma ?? '' }}"
     data-initial-selected-lugar="{{ $selectedLugar ?? '' }}"
     data-initial-selected-grado="{{ $selectedGrado ?? '' }}"
></div>


<style>
    .text-xxs { font-size: 0.65rem; line-height: 0.85rem; }
    .sticky.left-0 {
        position: -webkit-sticky; /* For Safari */
        position: sticky;
        left: 0;
        z-index: 0;
    }
    thead th.sticky.left-0 {
        z-index: 20 !important; /* Ensure header is above body cells */
    }
    /* Custom scrollbar for Webkit browsers */
    .overflow-x-auto::-webkit-scrollbar { height: 8px; }
    .overflow-x-auto::-webkit-scrollbar-track { background: #f1f5f9; border-radius: 10px; }
    .dark .overflow-x-auto::-webkit-scrollbar-track { background: #334155; } /* slate-700 */
    .overflow-x-auto::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 10px; }
    .dark .overflow-x-auto::-webkit-scrollbar-thumb { background: #475569; } /* slate-600 */
    .overflow-x-auto::-webkit-scrollbar-thumb:hover { background: #94a3b8; }
    .dark .overflow-x-auto::-webkit-scrollbar-thumb:hover { background: #64748b; } /* slate-500 */

    /* Specific style for date input in dark mode to ensure text is visible */
    .dark input[type="date"]::-webkit-calendar-picker-indicator {
        filter: invert(1);
    }
</style>

    @push('scripts')
        @vite(['resources/js/pages/asistencia-attendance.js'])
    @endpush

</x-app-layout>

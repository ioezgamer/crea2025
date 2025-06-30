<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between w-full px-4 py-6 mx-auto max-w-7xl sm:px-6 lg:px-8">
            <h2 class="text-2xl font-bold text-transparent lg:text-3xl bg-clip-text bg-gradient-to-r from-indigo-600 via-purple-600 to-pink-500">
                {{ __('Registro de asistencia') }}
            </h2>
            <x-boton-regresar onclick="window.location.href='{{ route('dashboard') }}'" />
        </div>
    </x-slot>

    <div class="min-h-screen py-8 bg-gradient-to-br from-slate-50 via-purple-50 to-pink-50 dark:from-slate-800 dark:via-purple-900 dark:to-pink-900">
        <div class="max-w-full px-2 mx-auto sm:px-4 lg:px-6">
            {{-- Filter Section Card --}}
            <div class="p-4 mb-6 border-transparent shadow-lg bg-gradient-to-r from-indigo-600/5 via-purple-600/5 to-pink-500/5 dark:bg-slate-800/80 backdrop-blur-sm rounded-3xl sm:p-6">
                <div class="grid items-end grid-cols-1 gap-4 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-5">
                    <div>
                        <label for="filtro_programa" class="block mb-1 text-xs font-medium text-slate-700 dark:text-slate-300">Programa <span class="text-red-500">*</span></label>
                        <select name="programa" id="filtro_programa" class="mt-1 block w-full rounded-3xl border-slate-300 dark:border-slate-600 dark:bg-slate-700 dark:text-slate-300 text-sm shadow-sm focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-2 focus:ring-indigo-500 dark:focus:ring-indigo-600 py-2.5 pl-3 pr-8 transition duration-150 ease-in-out">
                            <option value="">Seleccione Programa...</option>
                            @foreach ($programOptions as $prog)
                                <option value="{{ $prog }}" {{ $selectedPrograma == $prog ? 'selected' : '' }}>{{ $prog }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label for="filtro_lugar" class="block mb-1 text-xs font-medium text-slate-700 dark:text-slate-300">Lugar <span class="text-red-500">*</span></label>
                        <select name="lugar_de_encuentro_del_programa" id="filtro_lugar" class="mt-1 block w-full rounded-3xl border-slate-300 dark:border-slate-600 dark:bg-slate-700 dark:text-slate-300 text-sm shadow-sm focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-2 focus:ring-indigo-500 dark:focus:ring-indigo-600 py-2.5 pl-3 pr-8 transition duration-150 ease-in-out" disabled>
                            <option value="">Seleccione Lugar...</option>
                            @foreach ($lugarOptions as $lugar) {{-- Populated by JS --}}
                                <option value="{{ $lugar }}" {{ $selectedLugar == $lugar ? 'selected' : '' }}>{{ $lugar }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label for="filtro_grado" class="block mb-1 text-xs font-medium text-slate-700 dark:text-slate-300">Grado <span class="text-red-500">*</span></label>
                        <select name="grado_p" id="filtro_grado" class="mt-1 block w-full rounded-3xl border-slate-300 dark:border-slate-600 dark:bg-slate-700 dark:text-slate-300 text-sm shadow-sm focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-2 focus:ring-indigo-500 dark:focus:ring-indigo-600 py-2.5 pl-3 pr-8 transition duration-150 ease-in-out" disabled>
                            <option value="">Seleccione Grado...</option>
                             @foreach ($gradoOptions as $grado) {{-- Populated by JS --}}
                                <option value="{{ $grado }}" {{ $selectedGrado == $grado ? 'selected' : '' }}>{{ $grado }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label for="filtro_tipo_asistencia" class="block mb-1 text-xs font-medium text-slate-700 dark:text-slate-300">Tipo Asistencia <span class="text-red-500">*</span></label>
                        <select name="tipo_asistencia" id="filtro_tipo_asistencia" class="mt-1 block w-full rounded-3xl border-slate-300 dark:border-slate-600 dark:bg-slate-700 dark:text-slate-300 text-sm shadow-sm focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-2 focus:ring-indigo-500 dark:focus:ring-indigo-600 py-2.5 pl-3 pr-8 transition duration-150 ease-in-out">
                            <option value="semanal" {{ ($selectedTipoAsistencia ?? 'semanal') == 'semanal' ? 'selected' : '' }}>Semanal</option>
                            <option value="diaria" {{ ($selectedTipoAsistencia ?? 'semanal') == 'diaria' ? 'selected' : '' }}>Diaria</option>
                        </select>
                    </div>
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
            <div id="tabla_asistencia_container">
                @if ($participantes->isNotEmpty())
                    {{-- IMPORTANTE: El partial 'asistencia.partials.tabla_asistencia' también necesita ser adaptado para el modo oscuro. --}}
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
                    <div class="p-8 mt-6 text-center shadow-lg bg-white/70 dark:bg-slate-800/80 backdrop-blur-lg rounded-2xl">
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
     data-opciones-lugares-url="{{ route('asistencia.ajax.lugares') }}"
     data-opciones-grados-url="{{ route('asistencia.ajax.grados') }}"
     data-opciones-participantes-url="{{ route('asistencia.ajax.participantes') }}"
     
     data-reporte-url-base="{{ route('asistencia.reporte') }}"
     data-csrf-token="{{ csrf_token() }}"
     data-initial-selected-programa="{{ $selectedPrograma ?? '' }}"
     data-initial-selected-lugar="{{ $selectedLugar ?? '' }}"
     data-initial-selected-grado="{{ $selectedGrado ?? '' }}"
     data-store-individual-url="{{ route('asistencia.ajax.storeIndividual') }}"


></div>



<style>
    .text-xxs { font-size: 0.65rem; line-height: 0.85rem; }
    .sticky.left-0 {
        position: -webkit-sticky; /* For Safari */
        position: sticky;
        left: 0;
        z-index: 10;
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

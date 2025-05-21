<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row items-center justify-between gap-4">
            <h2 class="font-semibold text-xl text-transparent bg-clip-text bg-gradient-to-r from-indigo-600 via-purple-600 to-pink-500 leading-tight">
                {{ __('Registro de Asistencia Interactivo') }}
            </h2>
            {{-- Assuming x-boton-regresar can be styled or is already consistent.
                 If it's a simple link, it might look like this:
            <a href="{{ route('dashboard') }}" class="inline-flex items-center px-4 py-2 bg-slate-200 border border-transparent rounded-xl font-semibold text-xs text-slate-700 uppercase tracking-widest hover:bg-slate-300 focus:outline-none focus:ring-2 focus:ring-slate-400 focus:ring-offset-2 transition ease-in-out duration-150 shadow-sm hover:shadow-md">
                <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                Regresar
            </a>
            --}}
            <x-boton-regresar onclick="window.location.href='{{ route('dashboard') }}'" />
        </div>
    </x-slot>

    <div class="py-8 bg-gradient-to-br from-slate-50 via-purple-50 to-pink-50 min-h-screen">
        <div class="max-w-full mx-auto px-2 sm:px-4 lg:px-6">
            {{-- Filter Section Card --}}
            <div class="bg-white/70 backdrop-blur-lg shadow-xl rounded-2xl p-4 sm:p-6 mb-6">
                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-4 items-end">
                    <div>
                        <label for="filtro_programa" class="block text-xs font-medium text-slate-700 mb-1">Programa <span class="text-red-500">*</span></label>
                        <select name="programa" id="filtro_programa" class="mt-1 block w-full rounded-xl border-slate-300 text-sm shadow-sm focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500 py-2.5 pl-3 pr-8 transition duration-150 ease-in-out">
                            <option value="">Seleccione Programa...</option>
                            @foreach ($programOptions as $prog)
                                <option value="{{ $prog }}" {{ $selectedPrograma == $prog ? 'selected' : '' }}>{{ $prog }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label for="filtro_lugar" class="block text-xs font-medium text-slate-700 mb-1">Lugar <span class="text-red-500">*</span></label>
                        <select name="lugar_de_encuentro_del_programa" id="filtro_lugar" class="mt-1 block w-full rounded-xl border-slate-300 text-sm shadow-sm focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500 py-2.5 pl-3 pr-8 transition duration-150 ease-in-out" disabled>
                            <option value="">Seleccione Lugar...</option>
                            @foreach ($lugarOptions as $lugar) {{-- Populated by JS --}}
                                <option value="{{ $lugar }}" {{ $selectedLugar == $lugar ? 'selected' : '' }}>{{ $lugar }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label for="filtro_grado" class="block text-xs font-medium text-slate-700 mb-1">Grado <span class="text-red-500">*</span></label>
                        <select name="grado_p" id="filtro_grado" class="mt-1 block w-full rounded-xl border-slate-300 text-sm shadow-sm focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500 py-2.5 pl-3 pr-8 transition duration-150 ease-in-out" disabled>
                            <option value="">Seleccione Grado...</option>
                             @foreach ($gradoOptions as $grado) {{-- Populated by JS --}}
                                <option value="{{ $grado }}" {{ $selectedGrado == $grado ? 'selected' : '' }}>{{ $grado }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label for="filtro_tipo_asistencia" class="block text-xs font-medium text-slate-700 mb-1">Tipo Asistencia <span class="text-red-500">*</span></label>
                        <select name="tipo_asistencia" id="filtro_tipo_asistencia" class="mt-1 block w-full rounded-xl border-slate-300 text-sm shadow-sm focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500 py-2.5 pl-3 pr-8 transition duration-150 ease-in-out">
                            <option value="semanal" {{ ($selectedTipoAsistencia ?? 'semanal') == 'semanal' ? 'selected' : '' }}>Semanal</option>
                            <option value="diaria" {{ ($selectedTipoAsistencia ?? 'semanal') == 'diaria' ? 'selected' : '' }}>Diaria</option>
                        </select>
                    </div>
                    <div>
                        <label for="filtro_fecha" class="block text-xs font-medium text-slate-700 mb-1"><span id="label_fecha">Semana (Lunes)</span> <span class="text-red-500">*</span></label>
                        <input type="date" name="fecha" id="filtro_fecha" value="{{ $fechaInput ?? now()->startOfWeek()->format('Y-m-d') }}" class="mt-1 block w-full rounded-xl border-slate-300 text-sm shadow-sm focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500 py-2 placeholder-slate-400">
                    </div>
                </div>
                <div class="mt-4 text-right">
                    <button type="button" id="btn_cargar_participantes" class="inline-flex items-center px-5 py-2.5 bg-gradient-to-r from-indigo-600 to-purple-600 border border-transparent rounded-xl font-semibold text-xs text-white uppercase tracking-widest hover:from-indigo-700 hover:to-purple-700 focus:outline-none focus:ring-2 focus:ring-purple-500 focus:ring-offset-2 transition ease-in-out duration-150 shadow-md hover:shadow-lg disabled:opacity-50 disabled:cursor-not-allowed" disabled>
                        <svg id="spinner_cargar" class="animate-spin -ml-1 mr-2 h-4 w-4 text-white hidden" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
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
                <div class="bg-red-50/70 backdrop-blur-md border-l-4 border-red-500 text-red-700 p-4 mb-6 rounded-xl shadow-md text-xs">
                    <p class="font-semibold text-red-800">Por favor corrige los siguientes errores:</p>
                    <ul class="list-disc ml-5 mt-1 space-y-0.5">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            @if (session('success'))
                <div class="bg-green-50/70 backdrop-blur-md border-l-4 border-green-500 text-green-700 p-4 mb-6 rounded-xl shadow-md text-xs">
                    {{ session('success') }}
                </div>
            @endif

            {{-- Attendance Table Container --}}
            <div id="tabla_asistencia_container">
                @if ($participantes->isNotEmpty())
                    {{-- Ensure the partial is styled for the light theme --}}
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
                    <div class="mt-6 bg-white/70 backdrop-blur-lg shadow-lg rounded-2xl p-8 text-center">
                        <svg class="mx-auto h-12 w-12 text-slate-400 mb-3" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true">
                           <path stroke-linecap="round" stroke-linejoin="round" d="M11.25 11.25l.041-.02a.75.75 0 011.063.852l-.708 2.836a.75.75 0 001.063.853l.041-.021M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-9-3.75h.008v.008H12V8.25z" />
                        </svg>
                        <p class="text-sm text-slate-600">
                            Seleccione todos los filtros (Programa, Lugar, Grado, Tipo Asistencia y Fecha) y presione "Cargar Participantes" para mostrar la tabla de asistencia.
                        </p>
                    </div>
                @endif
            </div>
            
            {{-- Generate Report Button Container --}}
            <div id="report_button_container" class="mt-6 text-center {{ !$selectedPrograma || $participantes->isEmpty() ? 'hidden' : '' }}">
                 <a href="#" id="link_generar_reporte" class="inline-flex items-center justify-center py-2.5 px-6 bg-gradient-to-r from-green-500 to-emerald-600 border border-transparent rounded-xl font-semibold text-sm text-white shadow-md hover:shadow-lg hover:from-green-600 hover:to-emerald-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-emerald-500 transition ease-in-out duration-150">
                    <svg class="w-5 h-5 mr-2 -ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                    Generar Reporte de Asistencia
                </a>
            </div>
        </div>
    </div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const filtroPrograma = document.getElementById('filtro_programa');
    const filtroLugar = document.getElementById('filtro_lugar');
    const filtroGrado = document.getElementById('filtro_grado');
    const filtroTipoAsistencia = document.getElementById('filtro_tipo_asistencia');
    const filtroFecha = document.getElementById('filtro_fecha');
    const labelFecha = document.getElementById('label_fecha');
    const btnCargarParticipantes = document.getElementById('btn_cargar_participantes');
    const tablaAsistenciaContainer = document.getElementById('tabla_asistencia_container');
    const spinnerCargar = document.getElementById('spinner_cargar');
    const globalFeedback = document.getElementById('global_feedback_messages');
    const reportButtonContainer = document.getElementById('report_button_container');
    const linkGenerarReporte = document.getElementById('link_generar_reporte');

    const initialSelectedPrograma = "{{ $selectedPrograma ?? '' }}";
    const initialSelectedLugar = "{{ $selectedLugar ?? '' }}";
    const initialSelectedGrado = "{{ $selectedGrado ?? '' }}";

    function updateFechaLabel() {
        if (filtroTipoAsistencia.value === 'diaria') {
            labelFecha.textContent = 'Fecha';
        } else {
            labelFecha.textContent = 'Semana (Lunes)';
        }
    }

    function checkFiltersAndEnableButton() {
        const programaSelected = filtroPrograma.value !== "";
        const lugarSelected = filtroLugar.value !== "";
        const gradoSelected = filtroGrado.value !== "";
        const fechaSelected = filtroFecha.value !== "";
        const tipoAsistenciaSelected = filtroTipoAsistencia.value !== "";
        
        const allFiltersSelected = programaSelected && lugarSelected && gradoSelected && fechaSelected && tipoAsistenciaSelected;
        btnCargarParticipantes.disabled = !allFiltersSelected;
        
        if (allFiltersSelected && tablaAsistenciaContainer.querySelector('table')) { // Show report button only if table is loaded
            reportButtonContainer.classList.remove('hidden');
            const reporteUrl = `{{ route('asistencia.reporte') }}?programa=${encodeURIComponent(filtroPrograma.value)}&lugar_de_encuentro_del_programa=${encodeURIComponent(filtroLugar.value)}&grado_p=${encodeURIComponent(filtroGrado.value)}&fecha=${encodeURIComponent(filtroFecha.value)}&tipo_asistencia=${encodeURIComponent(filtroTipoAsistencia.value)}`;
            linkGenerarReporte.href = reporteUrl;
        } else {
            reportButtonContainer.classList.add('hidden');
        }
    }

    function populateSelect(selectElement, options, selectedValue = "", placeholder = "Seleccione...") {
        selectElement.innerHTML = `<option value="">${placeholder}</option>`;
        if (Array.isArray(options) && options.length > 0) {
            options.forEach(optionValue => {
                const option = document.createElement('option');
                option.value = optionValue;
                option.textContent = optionValue;
                if (optionValue === selectedValue) {
                    option.selected = true;
                }
                selectElement.appendChild(option);
            });
            selectElement.disabled = false;
        } else {
             selectElement.innerHTML = `<option value="">${options.length === 0 && selectElement.id !== 'filtro_programa' ? 'No hay opciones' : placeholder}</option>`;
            selectElement.disabled = true;
        }
    }
    
    async function fetchOptions(url, selectElement, dependantSelectElement = null, selectedValue = "", placeholder = "Seleccione...") {
        selectElement.innerHTML = `<option value="">Cargando...</option>`;
        selectElement.disabled = true;
        if(dependantSelectElement) {
            dependantSelectElement.innerHTML = `<option value="">${dependantSelectElement.id === 'filtro_lugar' ? 'Seleccione Programa' : 'Seleccione Lugar'}</option>`;
            dependantSelectElement.disabled = true;
        }

        try {
            const response = await fetch(url);
            if (!response.ok) throw new Error(`Error HTTP ${response.status}`);
            const data = await response.json();
            populateSelect(selectElement, data, selectedValue, placeholder);
        } catch (error) {
            console.error(`Error cargando opciones para ${selectElement.id}:`, error);
            selectElement.innerHTML = `<option value="">Error al cargar</option>`;
        } finally {
            // Re-enable if it has options or a selected value, otherwise keep disabled if it's not the primary filter
            selectElement.disabled = selectElement.options.length <= 1 && !selectElement.value && selectElement.id !== 'filtro_programa';
             if(dependantSelectElement && selectElement.value){ // If current select got a value, trigger change for dependant
                selectElement.dispatchEvent(new Event('change'));
            }
            checkFiltersAndEnableButton();
        }
    }

    filtroPrograma.addEventListener('change', function () {
        const programa = this.value;
        fetchOptions(`{{ route('asistencia.opciones.lugares') }}?programa=${encodeURIComponent(programa)}`, filtroLugar, filtroGrado, initialSelectedLugar && this.value === initialSelectedPrograma ? initialSelectedLugar : "", 'Seleccione Lugar...');
    });

    filtroLugar.addEventListener('change', function () {
        const programa = filtroPrograma.value;
        const lugar = this.value;
        fetchOptions(`{{ route('asistencia.opciones.grados') }}?programa=${encodeURIComponent(programa)}&lugar_de_encuentro_del_programa=${encodeURIComponent(lugar)}`, filtroGrado, null, initialSelectedGrado && this.value === initialSelectedLugar && filtroPrograma.value === initialSelectedPrograma ? initialSelectedGrado : "", 'Seleccione Grado...');
    });
    
    filtroGrado.addEventListener('change', checkFiltersAndEnableButton);
    filtroFecha.addEventListener('change', checkFiltersAndEnableButton);
    filtroTipoAsistencia.addEventListener('change', function() {
        updateFechaLabel();
        checkFiltersAndEnableButton();
    });

    btnCargarParticipantes.addEventListener('click', function () {
        // ... (rest of the click handler remains the same, ensure showGlobalFeedback is styled)
        const programa = filtroPrograma.value;
        const lugar = filtroLugar.value;
        const grado = filtroGrado.value;
        const fecha = filtroFecha.value;
        const tipoAsistencia = filtroTipoAsistencia.value;

        if (!programa || !lugar || !grado || !fecha || !tipoAsistencia) {
            showGlobalFeedback('Por favor, complete todos los filtros.', 'error');
            return;
        }
        
        spinnerCargar.classList.remove('hidden');
        this.disabled = true;
        tablaAsistenciaContainer.innerHTML = '<div class="text-center py-10"><p class="text-slate-500">Cargando participantes...</p></div>';
        globalFeedback.innerHTML = '';

        const params = new URLSearchParams({ 
            programa, 
            lugar_de_encuentro_del_programa: lugar, 
            grado_p: grado, 
            fecha: fecha,
            tipo_asistencia: tipoAsistencia
        });

        fetch(`{{ route('asistencia.opciones.participantes') }}?${params.toString()}`)
            .then(response => {
                if (!response.ok) {
                    return response.json().then(err => { throw new Error(err.error || `Error ${response.status}`) });
                }
                return response.json();
            })
            .then(data => {
                if (data.html) {
                    tablaAsistenciaContainer.innerHTML = data.html;
                    initializeAsistenciaSelects(); // Re-initialize selects in the new table
                } else if (data.error) {
                    showGlobalFeedback(data.error, 'error');
                    tablaAsistenciaContainer.innerHTML = `<div class="mt-6 bg-white/70 backdrop-blur-lg shadow-lg rounded-2xl p-6 text-sm text-red-600">${data.error}</div>`;
                } else {
                    tablaAsistenciaContainer.innerHTML = `<div class="mt-6 bg-white/70 backdrop-blur-lg shadow-lg rounded-2xl p-8 text-center"><svg class="mx-auto h-12 w-12 text-slate-400 mb-3" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M11.25 11.25l.041-.02a.75.75 0 011.063.852l-.708 2.836a.75.75 0 001.063.853l.041-.021M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-9-3.75h.008v.008H12V8.25z" /></svg><p class="text-sm text-slate-600">No se encontraron participantes con los filtros seleccionados.</p></div>`;
                }
            })
            .catch(error => {
                console.error('Error al cargar participantes:', error);
                showGlobalFeedback(`Error al cargar participantes: ${error.message}`, 'error');
                tablaAsistenciaContainer.innerHTML = `<div class="mt-6 bg-white/70 backdrop-blur-lg shadow-lg rounded-2xl p-6 text-sm text-red-600">Error al cargar participantes. Intente de nuevo.</div>`;
            })
            .finally(() => {
                spinnerCargar.classList.add('hidden');
                this.disabled = false; 
                checkFiltersAndEnableButton();
            });
    });

    function showGlobalFeedback(message, type = 'success') {
        const toastId = 'toast-' + Date.now();
        const toast = document.createElement('div');
        let bgColor, textColor, borderColor, iconSvg;

        if (type === 'success') {
            bgColor = 'bg-green-50/80 backdrop-blur-md'; textColor = 'text-green-700'; borderColor = 'border-green-500';
            iconSvg = `<svg class="w-5 h-5 mr-2 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg>`;
        } else { // error
            bgColor = 'bg-red-50/80 backdrop-blur-md'; textColor = 'text-red-700'; borderColor = 'border-red-500';
            iconSvg = `<svg class="w-5 h-5 mr-2 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-13a1 1 0 10-2 0v4a1 1 0 102 0V5zm-1 9a1 1 0 100-2 1 1 0 000 2z" clip-rule="evenodd"></path></svg>`;
        }
        toast.className = `flex items-center p-4 rounded-xl shadow-lg border-l-4 ${bgColor} ${textColor} ${borderColor} text-sm font-medium mb-3 transition-all duration-500 ease-out transform translate-x-full opacity-0`;
        toast.innerHTML = `${iconSvg}<span>${message}</span>`;
        
        globalFeedback.appendChild(toast);
        requestAnimationFrame(() => {
            toast.classList.remove('translate-x-full', 'opacity-0');
            toast.classList.add('translate-x-0', 'opacity-100');
        });
        setTimeout(() => {
            toast.classList.add('opacity-0');
            toast.addEventListener('transitionend', () => toast.remove(), { once: true });
        }, 5000);
    }
    
    function showRowFeedback(row, message, type = 'success') {
        const feedbackEl = row.querySelector('.save-feedback');
        if (!feedbackEl) return;

        feedbackEl.textContent = message;
        feedbackEl.className = 'save-feedback ml-2 text-xs transition-opacity duration-300 ease-in-out'; // Base classes
        if (type === 'success') {
            feedbackEl.classList.add('text-green-600');
        } else {
            feedbackEl.classList.add('text-red-600');
        }
        feedbackEl.classList.remove('opacity-0'); // Make visible
        
        const originalBg = row.style.backgroundColor;
        row.style.backgroundColor = type === 'success' ? 'rgba(209, 250, 229, 0.7)' : 'rgba(254, 226, 226, 0.7)'; // green-100/70 or red-100/70

        setTimeout(() => {
            feedbackEl.classList.add('opacity-0');
            row.style.backgroundColor = originalBg;
        }, 3000);
    }

    function initializeAsistenciaSelects() {
        // ... (rest of the initializeAsistenciaSelects function remains the same)
        document.querySelectorAll('.asistencia-select').forEach(select => {
            select.addEventListener('change', function () {
                const participanteId = this.dataset.participanteId;
                const fechaAsistencia = this.dataset.fechaAsistencia;
                const estado = this.value;
                const row = this.closest('tr');

                fetch('{{ route('asistencia.storeIndividual') }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({
                        participante_id: participanteId,
                        fecha_asistencia: fechaAsistencia,
                        estado: estado
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        showRowFeedback(row, 'Guardado!', 'success');
                    } else {
                        showRowFeedback(row, data.message || 'Error', 'error');
                    }
                })
                .catch(error => {
                    console.error('Error guardando asistencia:', error);
                    showRowFeedback(row, 'Error red', 'error');
                });
                updateTotalsForParticipant(participanteId);
            });
        });
        document.querySelectorAll('.total-asistido').forEach(el => {
            if(el.dataset.participanteId) { 
                updateTotalsForParticipant(el.dataset.participanteId);
            }
        });
    }
    
    function updateTotalsForParticipant(participanteId) {
        // ... (rest of the updateTotalsForParticipant function remains the same)
        const fila = document.querySelector(`#fila-participante-${participanteId}`);
        if (!fila) return;

        const selectsInRow = fila.querySelectorAll(`.asistencia-select`);
        let presentes = 0;
        selectsInRow.forEach(sel => {
            if (sel.value === 'Presente') {
                presentes++;
            }
        });
        const totalDiasProgramados = selectsInRow.length;
        const porcentaje = totalDiasProgramados > 0 ? Math.round((presentes / totalDiasProgramados) * 100) : 0;

        const totalEl = fila.querySelector(`.total-asistido`);
        const porcentajeEl = fila.querySelector(`.porcentaje-asistencia`);
        if(totalEl) totalEl.textContent = presentes;
        if(porcentajeEl) porcentajeEl.textContent = `${porcentaje}%`;
    }

    // Initializations
    updateFechaLabel();
    if (initialSelectedPrograma) {
        fetchOptions(`{{ route('asistencia.opciones.lugares') }}?programa=${encodeURIComponent(initialSelectedPrograma)}`, filtroLugar, filtroGrado, initialSelectedLugar, 'Seleccione Lugar...');
    } else {
         populateSelect(filtroLugar, [], '', 'Seleccione Programa...');
         populateSelect(filtroGrado, [], '', 'Seleccione Lugar...');
    }
    
    checkFiltersAndEnableButton(); // Check button state on load
    if (document.querySelectorAll('.asistencia-select').length > 0) { // If table loaded initially
        initializeAsistenciaSelects();
    }
});
</script>
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
    .overflow-x-auto::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 10px; }
    .overflow-x-auto::-webkit-scrollbar-thumb:hover { background: #94a3b8; }
</style>
</x-app-layout>

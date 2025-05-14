<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="text-lg font-semibold text-gray-800">Registro de Asistencia Interactivo</h2>
            <x-boton-regresar onclick="window.location.href='{{ route('dashboard') }}'" />
        </div>
    </x-slot>

    <div class="py-8 bg-gray-50 min-h-screen">
        <div class="max-w-full mx-auto px-2 sm:px-4 lg:px-6"> {{-- max-w-full y padding reducido --}}
            <div class="bg-white shadow-sm rounded-lg p-4 mb-6"> {{-- padding reducido --}}
                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-3"> {{-- gap reducido --}}
                    <div>
                        <label for="filtro_programa" class="block text-xs font-medium text-gray-700">Programa <span class="text-red-500">*</span></label>
                        <select name="programa" id="filtro_programa" class="mt-1 block w-full rounded-md border-gray-300 text-sm focus:border-indigo-500 focus:ring-indigo-500">
                            <option value="">Seleccione Programa...</option>
                            @foreach ($programOptions as $prog)
                                <option value="{{ $prog }}" {{ $selectedPrograma == $prog ? 'selected' : '' }}>{{ $prog }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label for="filtro_lugar" class="block text-xs font-medium text-gray-700">Lugar <span class="text-red-500">*</span></label>
                        <select name="lugar_de_encuentro_del_programa" id="filtro_lugar" class="mt-1 block w-full rounded-md border-gray-300 text-sm focus:border-indigo-500 focus:ring-indigo-500" disabled>
                            <option value="">Seleccione Lugar...</option>
                            @foreach ($lugarOptions as $lugar) {{-- Para carga inicial si aplica --}}
                                <option value="{{ $lugar }}" {{ $selectedLugar == $lugar ? 'selected' : '' }}>{{ $lugar }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label for="filtro_grado" class="block text-xs font-medium text-gray-700">Grado <span class="text-red-500">*</span></label>
                        <select name="grado_p" id="filtro_grado" class="mt-1 block w-full rounded-md border-gray-300 text-sm focus:border-indigo-500 focus:ring-indigo-500" disabled>
                            <option value="">Seleccione Grado...</option>
                             @foreach ($gradoOptions as $grado) {{-- Para carga inicial si aplica --}}
                                <option value="{{ $grado }}" {{ $selectedGrado == $grado ? 'selected' : '' }}>{{ $grado }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label for="filtro_fecha_inicio" class="block text-xs font-medium text-gray-700">Semana (Lunes) <span class="text-red-500">*</span></label>
                        <input type="date" name="fecha_inicio" id="filtro_fecha_inicio" value="{{ $fechaInicio ?? now()->startOfWeek()->format('Y-m-d') }}" class="mt-1 block w-full rounded-md border-gray-300 text-sm focus:border-indigo-500 focus:ring-indigo-500">
                    </div>
                </div>
                <div class="mt-3 text-right">
                    <button type="button" id="btn_cargar_participantes" class="inline-flex items-center px-3 py-1.5 border border-transparent text-xs font-medium rounded-md shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500" disabled>
                        <svg id="spinner_cargar" class="animate-spin -ml-0.5 mr-2 h-4 w-4 text-white hidden" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        Cargar Participantes
                    </button>
                </div>
            </div>

            <div id="global_feedback_messages" class="mb-4"></div>
            @if ($errors->any())
                <div class="bg-red-50 border-l-4 border-red-400 text-red-700 p-4 mb-4 rounded-md text-xs">
                    <p class="font-bold">Por favor corrige los siguientes errores:</p>
                    <ul class="list-disc ml-5 mt-1">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
             @if (session('success'))
                <div class="bg-green-50 border-l-4 border-green-400 text-green-700 p-4 mb-6 rounded-md text-xs">
                    {{ session('success') }}
                </div>
            @endif


            <div id="tabla_asistencia_container">
                 {{-- Incluir la tabla parcial si hay datos iniciales (opcional, o cargar siempre con AJAX) --}}
                 @if ($participantes->isNotEmpty())
                    @include('asistencia.partials.tabla_asistencia', [
                        'participantes' => $participantes,
                        'diasSemana' => $diasSemana,
                        'asistencias' => $asistencias,
                        'selectedPrograma' => $selectedPrograma,
                        'fechaInicioInput' => $fechaInicio, // o $fechaInicioInput si lo pasas así
                        'selectedLugar' => $selectedLugar,
                        'selectedGrado' => $selectedGrado
                    ])
                @else
                    <div class="mt-6 bg-white shadow-sm rounded-lg p-6 text-sm text-gray-500">
                        Seleccione todos los filtros (Programa, Lugar, Grado y Semana) y presione "Cargar Participantes".
                    </div>
                @endif
            </div>
            
            <div id="report_button_container" class="mt-6 text-center {{ !$selectedPrograma ? 'hidden' : '' }}">
                 <a href="#" id="link_generar_reporte" class="inline-flex items-center justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
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
    const filtroFechaInicio = document.getElementById('filtro_fecha_inicio');
    const btnCargarParticipantes = document.getElementById('btn_cargar_participantes');
    const tablaAsistenciaContainer = document.getElementById('tabla_asistencia_container');
    const spinnerCargar = document.getElementById('spinner_cargar');
    const globalFeedback = document.getElementById('global_feedback_messages');
    const reportButtonContainer = document.getElementById('report_button_container');
    const linkGenerarReporte = document.getElementById('link_generar_reporte');

    function checkFiltersAndEnableButton() {
        const programaSelected = filtroPrograma.value !== "";
        const lugarSelected = filtroLugar.value !== "";
        const gradoSelected = filtroGrado.value !== "";
        const fechaSelected = filtroFechaInicio.value !== "";
        
        btnCargarParticipantes.disabled = !(programaSelected && lugarSelected && gradoSelected && fechaSelected);
        if (programaSelected && lugarSelected && gradoSelected && fechaSelected) {
            reportButtonContainer.classList.remove('hidden');
            const reporteUrl = `{{ route('asistencia.reporte') }}?programa=${encodeURIComponent(filtroPrograma.value)}&lugar_de_encuentro_del_programa=${encodeURIComponent(filtroLugar.value)}&grado_p=${encodeURIComponent(filtroGrado.value)}&fecha_inicio=${encodeURIComponent(filtroFechaInicio.value)}`;
            linkGenerarReporte.href = reporteUrl;
        } else {
            reportButtonContainer.classList.add('hidden');
        }
    }

    function populateSelect(selectElement, options, selectedValue = "", placeholder = "Seleccione...") {
        selectElement.innerHTML = `<option value="">${placeholder}</option>`; // Limpiar y añadir placeholder
        options.forEach(optionValue => {
            const option = document.createElement('option');
            option.value = optionValue;
            option.textContent = optionValue;
            if (optionValue === selectedValue) {
                option.selected = true;
            }
            selectElement.appendChild(option);
        });
        selectElement.disabled = options.length === 0;
    }

    filtroPrograma.addEventListener('change', function () {
        const programa = this.value;
        filtroLugar.innerHTML = '<option value="">Cargando lugares...</option>';
        filtroLugar.disabled = true;
        filtroGrado.innerHTML = '<option value="">Seleccione Lugar primero...</option>';
        filtroGrado.disabled = true;
        checkFiltersAndEnableButton();

        if (programa) {
            fetch(`{{ route('asistencia.opciones.lugares') }}?programa=${encodeURIComponent(programa)}`)
                .then(response => response.json())
                .then(data => {
                    populateSelect(filtroLugar, data, '{{ $selectedLugar ?? '' }}', 'Seleccione Lugar...');
                    // Si había un lugar seleccionado previamente y existe en las nuevas opciones, disparar change
                    if (filtroLugar.value) filtroLugar.dispatchEvent(new Event('change')); 
                })
                .catch(error => {
                    console.error('Error cargando lugares:', error);
                    filtroLugar.innerHTML = '<option value="">Error al cargar</option>';
                });
        } else {
            populateSelect(filtroLugar, [], '', 'Seleccione Programa primero...');
            populateSelect(filtroGrado, [], '', 'Seleccione Programa primero...');
        }
    });

    filtroLugar.addEventListener('change', function () {
        const programa = filtroPrograma.value;
        const lugar = this.value;
        filtroGrado.innerHTML = '<option value="">Cargando grados...</option>';
        filtroGrado.disabled = true;
        checkFiltersAndEnableButton();

        if (programa && lugar) {
            fetch(`{{ route('asistencia.opciones.grados') }}?programa=${encodeURIComponent(programa)}&lugar_de_encuentro_del_programa=${encodeURIComponent(lugar)}`)
                .then(response => response.json())
                .then(data => {
                    populateSelect(filtroGrado, data, '{{ $selectedGrado ?? '' }}', 'Seleccione Grado...');
                })
                .catch(error => {
                    console.error('Error cargando grados:', error);
                    filtroGrado.innerHTML = '<option value="">Error al cargar</option>';
                });
        } else {
             populateSelect(filtroGrado, [], '', 'Seleccione Lugar primero...');
        }
    });
    
    filtroGrado.addEventListener('change', checkFiltersAndEnableButton);
    filtroFechaInicio.addEventListener('change', checkFiltersAndEnableButton);

    // Cargar participantes con AJAX
    btnCargarParticipantes.addEventListener('click', function () {
        const programa = filtroPrograma.value;
        const lugar = filtroLugar.value;
        const grado = filtroGrado.value;
        const fechaInicio = filtroFechaInicio.value;

        if (!programa || !lugar || !grado || !fechaInicio) {
            showGlobalFeedback('Por favor, complete todos los filtros.', 'error');
            return;
        }
        
        spinnerCargar.classList.remove('hidden');
        this.disabled = true;
        tablaAsistenciaContainer.innerHTML = '<div class="text-center py-10"><p class="text-gray-500">Cargando participantes...</p></div>';
        globalFeedback.innerHTML = '';

        const params = new URLSearchParams({ programa, lugar_de_encuentro_del_programa: lugar, grado_p: grado, fecha_inicio: fechaInicio });
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
                    initializeAsistenciaSelects(); // Re-inicializar listeners para los nuevos selects
                } else if (data.error) {
                     showGlobalFeedback(data.error, 'error');
                     tablaAsistenciaContainer.innerHTML = `<div class="mt-6 bg-white shadow-sm rounded-lg p-6 text-sm text-red-500">${data.error}</div>`;
                }
            })
            .catch(error => {
                console.error('Error al cargar participantes:', error);
                showGlobalFeedback(`Error al cargar participantes: ${error.message}`, 'error');
                tablaAsistenciaContainer.innerHTML = `<div class="mt-6 bg-white shadow-sm rounded-lg p-6 text-sm text-red-500">Error al cargar participantes. Intente de nuevo.</div>`;
            })
            .finally(() => {
                spinnerCargar.classList.add('hidden');
                this.disabled = false;
                checkFiltersAndEnableButton(); // Actualizar estado del botón de reporte
            });
    });

    function showGlobalFeedback(message, type = 'success') {
        const bgColor = type === 'success' ? 'bg-green-100 border-green-400 text-green-700' : 'bg-red-100 border-red-400 text-red-700';
        globalFeedback.innerHTML = `<div class="${bgColor} border-l-4 p-3 rounded-md text-xs mb-3">${message}</div>`;
        setTimeout(() => { globalFeedback.innerHTML = ''; }, 5000);
    }
    
    function showRowFeedback(row, message, type = 'success') {
        const feedbackEl = row.querySelector('.save-feedback');
        if (!feedbackEl) return;

        feedbackEl.textContent = message;
        feedbackEl.className = 'save-feedback ml-2 text-xs'; // Reset classes
        if (type === 'success') {
            feedbackEl.classList.add('text-green-600');
        } else {
            feedbackEl.classList.add('text-red-600');
        }
        feedbackEl.classList.remove('hidden');
        
        // Efecto visual en la fila
        const originalBg = row.style.backgroundColor;
        row.style.backgroundColor = type === 'success' ? '#D1FAE5' : '#FEE2E2'; // Tailwind green-100 or red-100

        setTimeout(() => {
            feedbackEl.classList.add('hidden');
            feedbackEl.textContent = '';
            row.style.backgroundColor = originalBg;
        }, 3000);
    }

    // Guardado de asistencia individual y actualización de totales
    function initializeAsistenciaSelects() {
        document.querySelectorAll('.asistencia-select').forEach(select => {
            select.addEventListener('change', function () {
                const participanteId = this.dataset.participanteId;
                const fechaAsistencia = this.dataset.fechaAsistencia;
                const estado = this.value;
                const row = this.closest('tr');

                // Guardar asistencia vía AJAX
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
                        // Opcional: revertir el select al valor anterior si falla el guardado
                        // this.value = valorAnterior; (necesitarías guardar el valor anterior)
                    }
                })
                .catch(error => {
                    console.error('Error guardando asistencia:', error);
                    showRowFeedback(row, 'Error red', 'error');
                });

                // Actualizar totales en la fila (cliente)
                updateTotalsForParticipant(participanteId);
            });
        });
        // Calcular totales iniciales para la tabla recién cargada
        document.querySelectorAll('.total-asistido').forEach(el => {
            updateTotalsForParticipant(el.dataset.participanteId);
        });
    }
    
    function updateTotalsForParticipant(participanteId) {
        const selectsInRow = document.querySelectorAll(`#fila-participante-${participanteId} .asistencia-select`);
        let presentes = 0;
        selectsInRow.forEach(sel => {
            if (sel.value === 'Presente') {
                presentes++;
            }
        });
        const totalDias = selectsInRow.length;
        const porcentaje = totalDias > 0 ? Math.round((presentes / totalDias) * 100) : 0;

        const totalEl = document.querySelector(`#fila-participante-${participanteId} .total-asistido`);
        const porcentajeEl = document.querySelector(`#fila-participante-${participanteId} .porcentaje-asistencia`);
        if(totalEl) totalEl.textContent = presentes;
        if(porcentajeEl) porcentajeEl.textContent = `${porcentaje}%`;
    }

    // Inicializar filtros y listeners si hay datos iniciales
    if (filtroPrograma.value) {
        filtroPrograma.dispatchEvent(new Event('change')); // Para cargar lugares si hay programa seleccionado
    }
    checkFiltersAndEnableButton(); // Estado inicial del botón de cargar y reporte
    initializeAsistenciaSelects(); // Para la tabla cargada inicialmente (si la hay)

});
</script>
<style>
    .text-xxs { font-size: 0.65rem; line-height: 0.85rem; }
    /* Estilo para la columna fija de nombres */
    .sticky.left-0 {
        position: -webkit-sticky; /* Para Safari */
        position: sticky;
        left: 0;
        z-index: 10; /* Asegurar que esté por encima de otras celdas */
    }
    /* Para que el fondo del thead también se superponga correctamente */
     thead th.sticky.left-0 {
        z-index: 20 !important;
    }
</style>
</x-app-layout>
document.addEventListener('DOMContentLoaded', function () {
    const configElement = document.getElementById('participanteIndexConfig');
    if (!configElement) {
        console.error('Elemento de configuración para participante/index no encontrado.');
        return;
    }

    const searchProgramaSelect = document.getElementById('search_programa');
    const searchLugarSelect = document.getElementById('search_lugar');
    const searchGradoSelect = document.getElementById('search_grado'); // Selector para Grado
    const feedbackToastContainer = document.getElementById('global_feedback_toast');

    const RUTA_LUGARES_POR_PROGRAMA = configElement.dataset.rutaLugaresPorPrograma;
    const RUTA_GRADOS_URL = configElement.dataset.rutaGradosUrl; // URL para obtener grados
    const CSRF_TOKEN = configElement.dataset.csrfToken;
    const RUTA_TOGGLE_ACTIVO = configElement.dataset.rutaToggleActivo;
    const initialLugarValue = configElement.dataset.initialSearchLugar;
    const initialGradoValue = configElement.dataset.initialSearchGrado; // Valor inicial para grado

    /**
     * Popula un elemento select con opciones.
     * @param {HTMLSelectElement} selectElement - El elemento select a poblar.
     * @param {Array<string>} options - Un array de strings para las opciones.
     * @param {string} selectedValue - El valor que debe estar seleccionado por defecto.
     * @param {string} placeholder - Texto para la opción por defecto si no hay selección/opciones.
     * @param {string} defaultOptionText - Texto para la opción "Todos" (ej. "Todos los lugares").
     */
    function populateSelect(selectElement, options, selectedValue = "", placeholder = "Seleccione...", defaultOptionText = "Todos") {
        if (!selectElement) return;

        let placeholderText = `${defaultOptionText} ${placeholder.toLowerCase().replace('todos los ', '').replace('seleccione ', '')}`;
        if (placeholder.toLowerCase().startsWith('seleccione') || placeholder.toLowerCase().startsWith('cargando') || placeholder.toLowerCase().startsWith('error al cargar')) {
            placeholderText = placeholder;
        } else if (options && options.length === 0) {
             if (selectElement.id === 'search_lugar' && searchProgramaSelect && searchProgramaSelect.value) {
                 placeholderText = 'No hay lugares para este programa';
            } else if (selectElement.id === 'search_grado') {
                if (searchLugarSelect && searchLugarSelect.value) {
                    placeholderText = 'No hay grados para este lugar';
                } else if (searchProgramaSelect && searchProgramaSelect.value) {
                    placeholderText = 'No hay grados para este programa';
                } else {
                    placeholderText = 'Seleccione programa/lugar';
                }
            }
        }


        selectElement.innerHTML = `<option value="">${placeholderText}</option>`;

        let hasSelectedValueInOptions = false;
        if (Array.isArray(options) && options.length > 0) {
            options.forEach(optionValue => {
                if(optionValue === null || optionValue === undefined) return; // Saltar valores nulos
                const option = document.createElement('option');
                option.value = String(optionValue).trim();
                option.textContent = String(optionValue).trim();
                if (String(optionValue).trim() === String(selectedValue).trim()) {
                    option.selected = true;
                    hasSelectedValueInOptions = true;
                }
                selectElement.appendChild(option);
            });
            selectElement.disabled = false;
        } else {
            selectElement.disabled = true;
        }

        if (selectedValue && !hasSelectedValueInOptions) {
            selectElement.value = ""; // Deseleccionar si el valor preseleccionado no está en las nuevas opciones
        }
    }

    async function cargarGradosParaParticipantes(programa, lugar, gradoASeleccionar = "") {
        if (!searchGradoSelect || !RUTA_GRADOS_URL) return;
        populateSelect(searchGradoSelect, [], '', 'Cargando Grados...', 'Todos los');
        searchGradoSelect.disabled = true;

        if (programa) { // Grados dependen al menos del programa
            let url = `${RUTA_GRADOS_URL}?programa=${encodeURIComponent(programa)}`;
            if (lugar) { // Si hay lugar, añadirlo para ser más específico
                url += `&lugar_de_encuentro_del_programa=${encodeURIComponent(lugar)}`;
            }
            try {
                const response = await fetch(url);
                if (!response.ok) throw new Error(`HTTP ${response.status} al cargar grados`);
                const data = await response.json();
                populateSelect(searchGradoSelect, data, gradoASeleccionar, 'Grados', 'Todos los');
            } catch (error) {
                console.error('Error en fetch para cargar grados:', error);
                populateSelect(searchGradoSelect, [], '', 'Error al cargar grados', 'Error');
                showToast(`Error al cargar grados: ${error.message}`, 'error');
            }
        } else {
            populateSelect(searchGradoSelect, [], '', 'Seleccione programa/lugar', 'Todos los');
        }
    }

    async function cargarLugaresParaParticipantes(programaSeleccionado, lugarASeleccionar = "", gradoASeleccionarParaDespues = "") {
        if (!searchLugarSelect || !RUTA_LUGARES_POR_PROGRAMA) return;
        populateSelect(searchLugarSelect, [], '', 'Cargando Lugares...', 'Todos los');
        searchLugarSelect.disabled = true;

        // Limpiar y deshabilitar grados al cargar lugares
        populateSelect(searchGradoSelect, [], '', 'Seleccione Lugar...', 'Todos los');
        if(searchGradoSelect) searchGradoSelect.disabled = true;

        if (programaSeleccionado) {
            try {
                const response = await fetch(`${RUTA_LUGARES_POR_PROGRAMA}?programa=${encodeURIComponent(programaSeleccionado)}`);
                if (!response.ok) throw new Error(`HTTP ${response.status} al cargar lugares`);
                const data = await response.json();
                populateSelect(searchLugarSelect, data.lugares || data, lugarASeleccionar, 'Lugar de Encuentro', 'Todos los');

                // Después de poblar lugares, decidir si cargar grados
                const lugarActualSeleccionado = searchLugarSelect.value;
                if (lugarActualSeleccionado) { // Si un lugar se seleccionó (ya sea el inicial o el primero de la lista)
                    await cargarGradosParaParticipantes(programaSeleccionado, lugarActualSeleccionado, (lugarActualSeleccionado === lugarASeleccionar ? gradoASeleccionarParaDespues : ""));
                } else if (!lugarASeleccionar) { // Si no había un lugar inicial para seleccionar y no se seleccionó ninguno automáticamente
                    await cargarGradosParaParticipantes(programaSeleccionado, "", gradoASeleccionarParaDespues); // Cargar grados solo por programa
                }

            } catch (error) {
                console.error('Error en fetch para cargar lugares:', error);
                populateSelect(searchLugarSelect, [], '', 'Error al cargar lugares', 'Error');
                showToast(`Error al cargar lugares: ${error.message}`, 'error');
            }
        } else { // Si no hay programa seleccionado
            populateSelect(searchLugarSelect, [], '', 'Seleccione programa primero', 'Todos los');
            populateSelect(searchGradoSelect, [], '', 'Seleccione programa primero', 'Todos los');
        }
    }

    // --- Event Listeners ---
    if (searchProgramaSelect) {
        searchProgramaSelect.addEventListener('change', async function () {
            // Al cambiar programa, cargar lugares. Grados se cargarán en cascada o si no hay lugar.
            await cargarLugaresParaParticipantes(this.value, "", ""); // No preseleccionar lugar ni grado
        });
    }

    if (searchLugarSelect) {
        searchLugarSelect.addEventListener('change', async function() {
            const programa = searchProgramaSelect ? searchProgramaSelect.value : "";
            // Al cambiar el lugar, cargar los grados correspondientes.
            await cargarGradosParaParticipantes(programa, this.value, ""); // No preseleccionar grado
        });
    }
    // El filtro de grado no tiene dependientes, así que no necesita un listener 'change' para cargar otros selects.

    // --- Inicialización de la Página ---
    async function inicializarPaginaParticipantes() {
        const currentSelectedPrograma = searchProgramaSelect ? searchProgramaSelect.value : "";
        if (currentSelectedPrograma) {
            // Cargar lugares y, en cascada, grados, usando los valores iniciales si existen
            await cargarLugaresParaParticipantes(currentSelectedPrograma, initialLugarValue, initialGradoValue);
        } else {
            // Si no hay programa inicial, los selectores de lugar y grado deben estar con su placeholder y deshabilitados
            populateSelect(searchLugarSelect, [], '', 'Seleccione programa primero', 'Todos los');
            populateSelect(searchGradoSelect, [], '', 'Seleccione programa primero', 'Todos los');
        }
    }

    inicializarPaginaParticipantes();

    // --- Funcionalidad de Toast (Notificaciones) ---
    function showToast(message, type = 'success') {
        if (!feedbackToastContainer) {
            console.warn('Contenedor de toasts (global_feedback_toast) no encontrado.');
            // Podrías crear uno dinámicamente si es crucial, o simplemente no mostrar el toast.
            // return;
        }
        const toastId = 'toast-' + Date.now();
        const toast = document.createElement('div');
        let bgColor, textColor, borderColor, iconSvg;

        // Clases para modo claro y oscuro
        if (type === 'success') {
            bgColor = 'bg-green-50 dark:bg-green-700/30';
            textColor = 'text-green-700 dark:text-green-200';
            borderColor = 'border-green-400 dark:border-green-600';
            iconSvg = `<svg class="w-5 h-5 mr-2 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg>`;
        } else { // Asumir error si no es success
            bgColor = 'bg-red-50 dark:bg-red-700/30';
            textColor = 'text-red-700 dark:text-red-200';
            borderColor = 'border-red-400 dark:border-red-600';
            iconSvg = `<svg class="w-5 h-5 mr-2 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-13a1 1 0 10-2 0v4a1 1 0 102 0V5zm-1 9a1 1 0 100-2 1 1 0 000 2z" clip-rule="evenodd"></path></svg>`;
        }
        toast.className = `flex items-center p-4 rounded-xl shadow-lg border-l-4 ${bgColor} ${textColor} ${borderColor} text-sm font-medium mb-3 transition-all duration-500 ease-out transform translate-x-full opacity-0`;
        toast.innerHTML = `${iconSvg}<span>${message}</span>`;

        if (feedbackToastContainer) {
            feedbackToastContainer.appendChild(toast);
        } else { // Fallback si el contenedor no existe, aunque debería estar
            document.body.appendChild(toast);
             toast.style.position = 'fixed';
             toast.style.top = '5rem'; // Ajustar según sea necesario
             toast.style.right = '1.25rem'; // Ajustar según sea necesario
             toast.style.zIndex = '100';
        }

        requestAnimationFrame(() => {
            toast.classList.remove('translate-x-full', 'opacity-0');
            toast.classList.add('translate-x-0', 'opacity-100');
        });

        setTimeout(() => {
            toast.classList.add('opacity-0', 'translate-x-full');
            toast.addEventListener('transitionend', () => toast.remove(), { once: true });
        }, 4000);
    }

    // --- Funcionalidad de Toggle Activo ---
    const toggles = document.querySelectorAll('.toggle-activo');
    toggles.forEach(toggle => {
        toggle.addEventListener('change', function () {
            const participanteId = parseInt(this.getAttribute('data-participante-id'), 10);
            const activo = this.checked;
            const originalState = !activo;
            this.disabled = true;

            fetch(RUTA_TOGGLE_ACTIVO, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': CSRF_TOKEN,
                    'Accept': 'application/json'
                },
                body: JSON.stringify({ participante_id: participanteId, activo: activo })
            })
            .then(response => {
                if (!response.ok) {
                    return response.json().then(errData => {
                        throw { status: response.status, data: errData, response: response };
                    }).catch(() => {
                        return response.text().then(text => {
                           throw { status: response.status, message: `Respuesta no JSON: ${text.substring(0,100)}...`, response: response };
                        });
                    });
                }
                return response.json();
            })
            .then(data => {
                if (data.success) {
                    showToast(data.message || 'Estado actualizado con éxito.', 'success');
                } else {
                    showToast(data.message || 'Error al actualizar el estado.', 'error');
                    this.checked = originalState;
                }
            })
            .catch(error => {
                console.error('Error en toggleActivo:', error);
                let errorMessage = 'Error de conexión o respuesta inesperada del servidor.';
                if (error.message) {
                    errorMessage = error.message;
                } else if (error.data && error.data.message) {
                    errorMessage = error.data.message;
                }
                showToast(errorMessage, 'error');
                this.checked = originalState;
            })
            .finally(() => {
                this.disabled = false;
            });
        });
    });
});

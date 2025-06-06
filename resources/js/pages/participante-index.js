// Al inicio de participante-index.js
import { showToast } from '../utils/notifications.js'; // Para toasts dinámicos específicos de esta página
import { confirmAction } from '../utils/helpers.js';   // Para confirmaciones

document.addEventListener('DOMContentLoaded', function () {
    const configElement = document.getElementById('participanteIndexConfig');
    if (!configElement) {
        console.error('Elemento de configuración para participante/index no encontrado.');
        return;
    }

    const searchProgramaSelect = document.getElementById('search_programa');
    const searchLugarSelect = document.getElementById('search_lugar');
    const searchGradoSelect = document.getElementById('search_grado');

    const RUTA_LUGARES_POR_PROGRAMA = configElement.dataset.rutaLugaresPorPrograma;
    const RUTA_GRADOS_URL = configElement.dataset.rutaGradosUrl;
    const CSRF_TOKEN = configElement.dataset.csrfToken;
    const RUTA_TOGGLE_ACTIVO = configElement.dataset.rutaToggleActivo;
    const initialLugarValue = configElement.dataset.initialSearchLugar;
    const initialGradoValue = configElement.dataset.initialSearchGrado;

    // ... (resto de tu lógica para populateSelect, cargarLugaresParaParticipantes, cargarGradosParaParticipantes, etc. se mantiene igual) ...
    // Asegúrate de que cualquier llamada a showToast dentro de esta lógica (ej. en los catch de errores de fetch)
    // siga usando la función importada showToast.

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

        if (programa) {
            let url = `${RUTA_GRADOS_URL}?programa=${encodeURIComponent(programa)}`;
            if (lugar) {
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

        populateSelect(searchGradoSelect, [], '', 'Seleccione Lugar...', 'Todos los');
        if(searchGradoSelect) searchGradoSelect.disabled = true;

        if (programaSeleccionado) {
            try {
                const response = await fetch(`${RUTA_LUGARES_POR_PROGRAMA}?programa=${encodeURIComponent(programaSeleccionado)}`);
                if (!response.ok) throw new Error(`HTTP ${response.status} al cargar lugares`);
                const data = await response.json();
                populateSelect(searchLugarSelect, data.lugares || data, lugarASeleccionar, 'Lugar de Encuentro', 'Todos los');

                const lugarActualSeleccionado = searchLugarSelect.value;
                if (lugarActualSeleccionado) {
                    await cargarGradosParaParticipantes(programaSeleccionado, lugarActualSeleccionado, (lugarActualSeleccionado === lugarASeleccionar ? gradoASeleccionarParaDespues : ""));
                } else if (!lugarASeleccionar) {
                    await cargarGradosParaParticipantes(programaSeleccionado, "", gradoASeleccionarParaDespues);
                }

            } catch (error) {
                console.error('Error en fetch para cargar lugares:', error);
                populateSelect(searchLugarSelect, [], '', 'Error al cargar lugares', 'Error');
                showToast(`Error al cargar lugares: ${error.message}`, 'error');
            }
        } else {
            populateSelect(searchLugarSelect, [], '', 'Seleccione programa primero', 'Todos los');
            populateSelect(searchGradoSelect, [], '', 'Seleccione programa primero', 'Todos los');
        }
    }
     // --- Event Listeners ---
    if (searchProgramaSelect) {
        searchProgramaSelect.addEventListener('change', async function () {
            await cargarLugaresParaParticipantes(this.value, "", "");
        });
    }

    if (searchLugarSelect) {
        searchLugarSelect.addEventListener('change', async function() {
            const programa = searchProgramaSelect ? searchProgramaSelect.value : "";
            await cargarGradosParaParticipantes(programa, this.value, "");
        });
    }

    // --- Inicialización de la Página ---
    async function inicializarPaginaParticipantes() {
        const currentSelectedPrograma = searchProgramaSelect ? searchProgramaSelect.value : "";
        if (currentSelectedPrograma) {
            await cargarLugaresParaParticipantes(currentSelectedPrograma, initialLugarValue, initialGradoValue);
        } else {
            populateSelect(searchLugarSelect, [], '', 'Seleccione programa primero', 'Todos los');
            populateSelect(searchGradoSelect, [], '', 'Seleccione programa primero', 'Todos los');
        }
    }

    inicializarPaginaParticipantes();


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

    // --- Funcionalidad de Confirmación de Eliminación (usa confirmAction de helpers.js) ---
    document.querySelectorAll('.form-delete-participante').forEach(form => {
        form.addEventListener('submit', function(event) {
            const participanteNombre = this.dataset.participanteNombre || 'este participante';
            confirmAction(event,
                'Eliminar Participante',
                `¿Estás seguro de eliminar a ${participanteNombre}? Esta acción no se puede deshacer.`,
                'Sí, eliminar',
                'Cancelar',
                'red'
            );
        });
    });

    // La lógica para procesar mensajes de sesión de Laravel se ha movido a app.js
    // y ya no es necesaria aquí.
});

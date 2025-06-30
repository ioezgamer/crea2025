document.addEventListener("DOMContentLoaded", function () {
    // --- ELEMENTOS DEL DOM Y CONFIGURACIÓN ---
    const configElement = document.getElementById("asistenciaConfig");
    if (!configElement) {
        console.error(
            "Elemento de configuración para asistencia no encontrado."
        );
        return;
    }

    const {
        opcionesLugaresUrl,
        opcionesGradosUrl,
        opcionesParticipantesUrl,
        storeIndividualUrl,
        reporteUrlBase,
        csrfToken,
        initialSelectedPrograma,
        initialSelectedLugar,
        initialSelectedGrado,
    } = configElement.dataset;

    const filtroPrograma = document.getElementById("filtro_programa");
    const filtroLugar = document.getElementById("filtro_lugar");
    const filtroGrado = document.getElementById("filtro_grado");
    const filtroTipoAsistencia = document.getElementById(
        "filtro_tipo_asistencia"
    );
    const filtroFecha = document.getElementById("filtro_fecha");
    const labelFecha = document.getElementById("label_fecha");
    const btnCargarParticipantes = document.getElementById(
        "btn_cargar_participantes"
    );
    const tablaAsistenciaContainer = document.getElementById(
        "tabla_asistencia_container"
    );
    const spinnerCargar = document.getElementById("spinner_cargar");
    const globalFeedback = document.getElementById("global_feedback_messages");
    const reportButtonContainer = document.getElementById(
        "report_button_container"
    );
    const linkGenerarReporte = document.getElementById("link_generar_reporte");

    // --- FUNCIONES AUXILIARES ---

    /**
     * Actualiza el color de fondo y borde de un select de asistencia según su valor.
     * @param {HTMLSelectElement} selectElement El elemento select a estilizar.
     */
    function actualizarColorSelect(selectElement) {
        selectElement.classList.remove(
            "bg-green-100",
            "text-green-800",
            "border-green-400",
            "bg-red-100",
            "text-red-800",
            "border-red-400",
            "bg-yellow-100",
            "text-yellow-800",
            "border-yellow-400"
        );
        switch (selectElement.value) {
            case "Presente":
                selectElement.classList.add(
                    "bg-green-100",
                    "text-green-800",
                    "border-green-400"
                );
                break;
            case "Ausente":
                selectElement.classList.add(
                    "bg-red-100",
                    "text-red-800",
                    "border-red-400"
                );
                break;
            case "Justificado":
                selectElement.classList.add(
                    "bg-yellow-100",
                    "text-yellow-800",
                    "border-yellow-400"
                );
                break;
        }
    }

    /**
     * Actualiza las columnas de totales para un participante específico.
     * @param {string} participanteId El ID del participante.
     */
    function updateTotalsForParticipant(participanteId) {
        const fila = document.querySelector(
            `#fila-participante-${participanteId}`
        );
        if (!fila) return;

        const selectsInRow = fila.querySelectorAll(".asistencia-select");
        let presentes = 0;
        selectsInRow.forEach((sel) => {
            if (sel.value === "Presente") {
                presentes++;
            }
        });

        const totalDiasProgramados = selectsInRow.length;
        const porcentaje =
            totalDiasProgramados > 0
                ? Math.round((presentes / totalDiasProgramados) * 100)
                : 0;

        const totalEl = fila.querySelector(".total-asistido");
        const porcentajeEl = fila.querySelector(".porcentaje-asistencia");
        if (totalEl) totalEl.textContent = presentes;
        if (porcentajeEl) porcentajeEl.textContent = `${porcentaje}%`;
    }

    /**
     * Muestra un mensaje de feedback temporal en la fila de un participante.
     * @param {HTMLTableRowElement} row La fila donde mostrar el mensaje.
     * @param {string} message El mensaje a mostrar.
     * @param {'success'|'error'} type El tipo de mensaje.
     */
    function showRowFeedback(row, message, type = "success") {
        const feedbackEl = row.querySelector(".save-feedback");
        if (!feedbackEl) return;

        feedbackEl.textContent = message;
        feedbackEl.className =
            "ml-2 text-xs transition-opacity duration-300 ease-in-out save-feedback"; // Reset classes
        feedbackEl.classList.add(
            type === "success" ? "text-green-600" : "text-red-600"
        );

        feedbackEl.classList.remove("opacity-0");

        const originalBg = row.style.backgroundColor;
        row.style.backgroundColor =
            type === "success"
                ? "rgba(209, 250, 229, 0.7)"
                : "rgba(254, 226, 226, 0.7)";

        setTimeout(() => {
            feedbackEl.classList.add("opacity-0");
            row.style.backgroundColor = originalBg;
        }, 3000);
    }

    /**
     * Inicializa todos los listeners y estados para la tabla de asistencia recién cargada.
     */
    function initializeAsistenciaTableLogic() {
        const selects = document.querySelectorAll(".asistencia-select");

        selects.forEach((select) => {
            // 1. Establecer color inicial
            actualizarColorSelect(select);

            // 2. Agregar listener para cambios
            select.addEventListener("change", function () {
                const participanteId = this.dataset.participanteId;
                const fechaAsistencia = this.dataset.fechaAsistencia;
                const estado = this.value;
                const row = this.closest("tr");

                // Actualizar color inmediatamente
                actualizarColorSelect(this);

                // Guardar el cambio en el servidor
                fetch(storeIndividualUrl, {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json",
                        "X-CSRF-TOKEN": csrfToken,
                        Accept: "application/json",
                    },
                    body: JSON.stringify({
                        participante_id: participanteId,
                        fecha_asistencia: fechaAsistencia,
                        estado: estado,
                    }),
                })
                    .then((response) => response.json())
                    .then((data) => {
                        if (data.success) {
                            showRowFeedback(row, "Guardado", "success");
                        } else {
                            showRowFeedback(
                                row,
                                data.message || "Error",
                                "error"
                            );
                        }
                    })
                    .catch((error) => {
                        console.error("Error guardando asistencia:", error);
                        showRowFeedback(row, "Error de red", "error");
                    });

                // Actualizar totales de la fila
                updateTotalsForParticipant(participanteId);
            });
        });

        // Calcular totales iniciales para todos los participantes en la tabla
        const participantIds = new Set(
            Array.from(selects).map((s) => s.dataset.participanteId)
        );
        participantIds.forEach((id) => updateTotalsForParticipant(id));
    }

    /**
     * Carga opciones para un <select> desde una URL.
     * @param {string} url La URL para hacer fetch.
     * @param {HTMLSelectElement} selectElement El elemento select a poblar.
     * @param {HTMLSelectElement|null} dependantSelectElement Un select dependiente para limpiar.
     * @param {string} selectedValue El valor que debe estar seleccionado.
     * @param {string} placeholder El texto del placeholder.
     */
    async function fetchOptions(
        url,
        selectElement,
        dependantSelectElement = null,
        selectedValue = "",
        placeholder = "Seleccione..."
    ) {
        selectElement.innerHTML = `<option value="">Cargando...</option>`;
        selectElement.disabled = true;
        if (dependantSelectElement) {
            dependantSelectElement.innerHTML = `<option value="">${
                dependantSelectElement.id === "filtro_lugar"
                    ? "Seleccione Programa"
                    : "Seleccione Lugar"
            }</option>`;
            dependantSelectElement.disabled = true;
        }

        try {
            const response = await fetch(url);
            if (!response.ok) throw new Error(`Error HTTP ${response.status}`);
            const data = await response.json();
            populateSelect(selectElement, data, selectedValue, placeholder);
        } catch (error) {
            console.error(
                `Error cargando opciones para ${selectElement.id}:`,
                error
            );
            selectElement.innerHTML = `<option value="">Error al cargar</option>`;
        } finally {
            selectElement.disabled = selectElement.options.length <= 1;
            if (dependantSelectElement && selectElement.value) {
                selectElement.dispatchEvent(new Event("change"));
            }
            checkFiltersAndEnableButton();
        }
    }

    /**
     * Puebla un elemento <select> con opciones.
     * @param {HTMLSelectElement} selectElement
     * @param {string[]} options
     * @param {string} selectedValue
     * @param {string} placeholder
     */
    function populateSelect(
        selectElement,
        options,
        selectedValue = "",
        placeholder = "Seleccione..."
    ) {
        selectElement.innerHTML = `<option value="">${placeholder}</option>`;
        if (Array.isArray(options) && options.length > 0) {
            options.forEach((optionValue) => {
                const option = document.createElement("option");
                option.value = optionValue;
                option.textContent = optionValue;
                if (optionValue === selectedValue) {
                    option.selected = true;
                }
                selectElement.appendChild(option);
            });
            selectElement.disabled = false;
        } else {
            selectElement.innerHTML = `<option value="">${
                options.length === 0 && selectElement.id !== "filtro_programa"
                    ? "No hay opciones"
                    : placeholder
            }</option>`;
            selectElement.disabled = true;
        }
    }

    /**
     * Comprueba si todos los filtros están seleccionados para habilitar el botón de carga.
     */
    function checkFiltersAndEnableButton() {
        const allFiltersSelected =
            filtroPrograma.value &&
            filtroLugar.value &&
            filtroGrado.value &&
            filtroFecha.value &&
            filtroTipoAsistencia.value;
        btnCargarParticipantes.disabled = !allFiltersSelected;

        if (
            allFiltersSelected &&
            tablaAsistenciaContainer.querySelector("table")
        ) {
            reportButtonContainer.classList.remove("hidden");
            const reporteUrl = `${reporteUrlBase}?programa=${encodeURIComponent(
                filtroPrograma.value
            )}&lugar_de_encuentro_del_programa=${encodeURIComponent(
                filtroLugar.value
            )}&grado_p=${encodeURIComponent(
                filtroGrado.value
            )}&fecha=${encodeURIComponent(
                filtroFecha.value
            )}&tipo_asistencia=${encodeURIComponent(
                filtroTipoAsistencia.value
            )}`;
            linkGenerarReporte.href = reporteUrl;
        } else {
            reportButtonContainer.classList.add("hidden");
        }
    }

    function updateFechaLabel() {
        labelFecha.textContent =
            filtroTipoAsistencia.value === "diaria"
                ? "Fecha"
                : "Semana (Lunes)";
    }

    // --- MANEJADORES DE EVENTOS ---

    filtroPrograma.addEventListener("change", function () {
        const programa = this.value;
        const selectedLugar =
            this.value === initialSelectedPrograma ? initialSelectedLugar : "";
        fetchOptions(
            `${opcionesLugaresUrl}?programa=${encodeURIComponent(programa)}`,
            filtroLugar,
            filtroGrado,
            selectedLugar,
            "Seleccione Lugar..."
        );
    });

    filtroLugar.addEventListener("change", function () {
        const programa = filtroPrograma.value;
        const lugar = this.value;
        const selectedGrado =
            filtroPrograma.value === initialSelectedPrograma &&
            this.value === initialSelectedLugar
                ? initialSelectedGrado
                : "";
        fetchOptions(
            `${opcionesGradosUrl}?programa=${encodeURIComponent(
                programa
            )}&lugar_de_encuentro_del_programa=${encodeURIComponent(lugar)}`,
            filtroGrado,
            null,
            selectedGrado,
            "Seleccione Grado..."
        );
    });

    [filtroGrado, filtroFecha].forEach((el) =>
        el.addEventListener("change", checkFiltersAndEnableButton)
    );

    filtroTipoAsistencia.addEventListener("change", function () {
        updateFechaLabel();
        checkFiltersAndEnableButton();
    });

    btnCargarParticipantes.addEventListener("click", function () {
        spinnerCargar.classList.remove("hidden");
        this.disabled = true;
        tablaAsistenciaContainer.innerHTML =
            '<div class="text-center py-10"><p class="text-slate-500">Cargando participantes...</p></div>';

        const params = new URLSearchParams({
            programa: filtroPrograma.value,
            lugar_de_encuentro_del_programa: filtroLugar.value,
            grado_p: filtroGrado.value,
            fecha: filtroFecha.value,
            tipo_asistencia: filtroTipoAsistencia.value,
        });

        fetch(`${opcionesParticipantesUrl}?${params.toString()}`)
            .then((response) => {
                if (!response.ok)
                    return response.json().then((err) => {
                        throw new Error(
                            err.error || `Error ${response.status}`
                        );
                    });
                return response.json();
            })
            .then((data) => {
                if (data.html) {
                    tablaAsistenciaContainer.innerHTML = data.html;
                    initializeAsistenciaTableLogic(); // <-- CLAVE: Inicializar la lógica en la nueva tabla
                } else {
                    tablaAsistenciaContainer.innerHTML = `<div class="mt-6 bg-white/70 backdrop-blur-lg shadow-lg rounded-2xl p-8 text-center"><svg class="mx-auto h-12 w-12 text-slate-400 mb-3" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M11.25 11.25l.041-.02a.75.75 0 011.063.852l-.708 2.836a.75.75 0 001.063.853l.041-.021M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-9-3.75h.008v.008H12V8.25z" /></svg><p class="text-sm text-slate-600">${
                        data.error ||
                        "No se encontraron participantes con los filtros seleccionados."
                    }</p></div>`;
                }
            })
            .catch((error) => {
                console.error("Error al cargar participantes:", error);
                tablaAsistenciaContainer.innerHTML = `<div class="mt-6 bg-white/70 backdrop-blur-lg shadow-lg rounded-2xl p-6 text-sm text-red-600">Error al cargar participantes: ${error.message}. Intente de nuevo.</div>`;
            })
            .finally(() => {
                spinnerCargar.classList.add("hidden");
                this.disabled = false;
                checkFiltersAndEnableButton();
            });
    });

    // --- INICIALIZACIÓN ---
    updateFechaLabel();
    if (initialSelectedPrograma) {
        filtroPrograma.dispatchEvent(new Event("change"));
    } else {
        populateSelect(filtroLugar, [], "", "Seleccione Programa...");
        populateSelect(filtroGrado, [], "", "Seleccione Lugar...");
    }
    checkFiltersAndEnableButton();

    // Si la tabla ya viene precargada por el controlador PHP
    if (tablaAsistenciaContainer.querySelector("table")) {
        initializeAsistenciaTableLogic();
    }
});

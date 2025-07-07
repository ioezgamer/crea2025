// Al inicio de asistencia-attendance.js
import { showToast } from "../utils/notifications.js"; // Ajusta la ruta si es necesario
document.addEventListener("DOMContentLoaded", function () {
    const configElement = document.getElementById("asistenciaConfig");
    if (!configElement) {
        console.error(
            "Elemento de configuración para asistencia no encontrado."
        );
        return;
    }

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

    // Obtener datos desde el elemento de configuración
    const OPCIONES_LUGARES_URL = configElement.dataset.opcionesLugaresUrl;
    const OPCIONES_GRADOS_URL = configElement.dataset.opcionesGradosUrl;
    const OPCIONES_PARTICIPANTES_URL =
        configElement.dataset.opcionesParticipantesUrl;
    const STORE_INDIVIDUAL_URL = configElement.dataset.storeIndividualUrl;
    const REPORTE_URL_BASE = configElement.dataset.reporteUrlBase;
    const CSRF_TOKEN = configElement.dataset.csrfToken;
    const initialSelectedPrograma =
        configElement.dataset.initialSelectedPrograma;
    const initialSelectedLugar = configElement.dataset.initialSelectedLugar;
    const initialSelectedGrado = configElement.dataset.initialSelectedGrado;

    function updateFechaLabel() {
        if (filtroTipoAsistencia.value === "diaria") {
            labelFecha.textContent = "Fecha";
        } else if (filtroTipoAsistencia.value === "semanal") {
            labelFecha.textContent = "Semana (Lunes)";
        } else {
            // mensual
            labelFecha.textContent = "Mes";
        }
    }

    function checkFiltersAndEnableButton() {
        const programaSelected = filtroPrograma.value !== "";
        const lugarSelected = filtroLugar.value !== "";
        const gradoSelected = filtroGrado.value !== "";
        const fechaSelected = filtroFecha.value !== "";
        const tipoAsistenciaSelected = filtroTipoAsistencia.value !== "";

        const allFiltersSelected =
            programaSelected &&
            lugarSelected &&
            gradoSelected &&
            fechaSelected &&
            tipoAsistenciaSelected;
        btnCargarParticipantes.disabled = !allFiltersSelected;

        if (
            allFiltersSelected &&
            tablaAsistenciaContainer.querySelector("table")
        ) {
            reportButtonContainer.classList.remove("hidden");
            const reporteUrl = `${REPORTE_URL_BASE}?programa=${encodeURIComponent(
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
            selectElement.disabled =
                selectElement.options.length <= 1 &&
                !selectElement.value &&
                selectElement.id !== "filtro_programa";
            if (dependantSelectElement && selectElement.value) {
                selectElement.dispatchEvent(new Event("change"));
            }
            checkFiltersAndEnableButton();
        }
    }

    filtroPrograma.addEventListener("change", function () {
        const programa = this.value;
        fetchOptions(
            `${OPCIONES_LUGARES_URL}?programa=${encodeURIComponent(programa)}`,
            filtroLugar,
            filtroGrado,
            initialSelectedLugar && this.value === initialSelectedPrograma
                ? initialSelectedLugar
                : "",
            "Seleccione Lugar..."
        );
    });

    filtroLugar.addEventListener("change", function () {
        const programa = filtroPrograma.value;
        const lugar = this.value;
        fetchOptions(
            `${OPCIONES_GRADOS_URL}?programa=${encodeURIComponent(
                programa
            )}&lugar_de_encuentro_del_programa=${encodeURIComponent(lugar)}`,
            filtroGrado,
            null,
            initialSelectedGrado &&
                this.value === initialSelectedLugar &&
                filtroPrograma.value === initialSelectedPrograma
                ? initialSelectedGrado
                : "",
            "Seleccione Grado..."
        );
    });

    filtroGrado.addEventListener("change", checkFiltersAndEnableButton);
    filtroFecha.addEventListener("change", checkFiltersAndEnableButton);
    filtroTipoAsistencia.addEventListener("change", function () {
        updateFechaLabel();
        checkFiltersAndEnableButton();
    });

    btnCargarParticipantes.addEventListener("click", function () {
        const programa = filtroPrograma.value;
        const lugar = filtroLugar.value;
        const grado = filtroGrado.value;
        const fecha = filtroFecha.value;
        const tipoAsistencia = filtroTipoAsistencia.value;

        if (!programa || !lugar || !grado || !fecha || !tipoAsistencia) {
            showGlobalFeedback(
                "Por favor, complete todos los filtros.",
                "error"
            );
            return;
        }

        spinnerCargar.classList.remove("hidden");
        this.disabled = true;
        tablaAsistenciaContainer.innerHTML =
            '<div class="text-center py-10"><p class="text-slate-500">Cargando participantes...</p></div>';
        globalFeedback.innerHTML = "";

        const params = new URLSearchParams({
            programa,
            lugar_de_encuentro_del_programa: lugar,
            grado_p: grado,
            fecha: fecha,
            tipo_asistencia: tipoAsistencia,
        });

        fetch(`${OPCIONES_PARTICIPANTES_URL}?${params.toString()}`)
            .then((response) => {
                if (!response.ok) {
                    return response.json().then((err) => {
                        throw new Error(
                            err.error || `Error ${response.status}`
                        );
                    });
                }
                return response.json();
            })
            .then((data) => {
                if (data.html) {
                    tablaAsistenciaContainer.innerHTML = data.html;
                    initializeAsistenciaSelects();
                } else if (data.error) {
                    showGlobalFeedback(data.error, "error");
                    tablaAsistenciaContainer.innerHTML = `<div class="mt-6 bg-white/70 backdrop-blur-lg shadow-lg rounded-2xl p-6 text-sm text-red-600">${data.error}</div>`;
                } else {
                    tablaAsistenciaContainer.innerHTML = `<div class="mt-6 bg-white/70 backdrop-blur-lg shadow-lg rounded-2xl p-8 text-center"><svg class="mx-auto h-12 w-12 text-slate-400 mb-3" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M11.25 11.25l.041-.02a.75.75 0 011.063.852l-.708 2.836a.75.75 0 001.063.853l.041-.021M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-9-3.75h.008v.008H12V8.25z" /></svg><p class="text-sm text-slate-600">No se encontraron participantes con los filtros seleccionados.</p></div>`;
                }
            })
            .catch((error) => {
                console.error("Error al cargar participantes:", error);
                showGlobalFeedback(
                    `Error al cargar participantes: ${error.message}`,
                    "error"
                );
                tablaAsistenciaContainer.innerHTML = `<div class="mt-6 bg-white/70 backdrop-blur-lg shadow-lg rounded-2xl p-6 text-sm text-red-600">Error al cargar participantes. Intente de nuevo.</div>`;
            })
            .finally(() => {
                spinnerCargar.classList.add("hidden");
                this.disabled = false;
                checkFiltersAndEnableButton();
            });
    });

    function showGlobalFeedback(message, type = "success") {
        const toastId = "toast-" + Date.now();
        const toast = document.createElement("div");
        let bgColor, textColor, borderColor, iconSvg;

        if (type === "success") {
            bgColor = "bg-green-50/80 backdrop-blur-md";
            textColor = "text-green-700";
            borderColor = "border-green-500";
            iconSvg = `<svg class="w-5 h-5 mr-2 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg>`;
        } else {
            bgColor = "bg-red-50/80 backdrop-blur-md";
            textColor = "text-red-700";
            borderColor = "border-red-500";
            iconSvg = `<svg class="w-5 h-5 mr-2 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-13a1 1 0 10-2 0v4a1 1 0 102 0V5zm-1 9a1 1 0 100-2 1 1 0 000 2z" clip-rule="evenodd"></path></svg>`;
        }
        toast.className = `flex items-center p-4 rounded-xl shadow-lg border-l-4 ${bgColor} ${textColor} ${borderColor} text-sm font-medium mb-3 transition-all duration-500 ease-out transform translate-x-full opacity-0`;
        toast.innerHTML = `${iconSvg}<span>${message}</span>`;

        globalFeedback.appendChild(toast);
        requestAnimationFrame(() => {
            toast.classList.remove("translate-x-full", "opacity-0");
            toast.classList.add("translate-x-0", "opacity-100");
        });
        setTimeout(() => {
            toast.classList.add("opacity-0");
            toast.addEventListener("transitionend", () => toast.remove(), {
                once: true,
            });
        }, 5000);
    }

    function showRowFeedback(row, message, type = "success") {
        const feedbackEl = row.querySelector(".save-feedback");
        if (!feedbackEl) return;

        feedbackEl.textContent = message;
        feedbackEl.className =
            "ml-2 text-xs transition-opacity duration-300 ease-in-out save-feedback";
        if (type === "success") {
            feedbackEl.classList.add("text-green-600");
        } else {
            feedbackEl.classList.add("text-red-600");
        }
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

    function initializeAsistenciaSelects() {
        document.querySelectorAll(".asistencia-select").forEach((select) => {
            select.addEventListener("change", function () {
                const participanteId = this.dataset.participanteId;
                const fechaAsistencia = this.dataset.fechaAsistencia;
                const estado = this.value;
                const row = this.closest("tr");

                fetch(STORE_INDIVIDUAL_URL, {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json",
                        "X-CSRF-TOKEN": CSRF_TOKEN,
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
                            showRowFeedback(row, "Guardado!", "success");
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
                        showRowFeedback(row, "Error red", "error");
                    });
                updateTotalsForParticipant(participanteId);
            });
        });
        document.querySelectorAll(".total-asistido").forEach((el) => {
            if (el.dataset.participanteId) {
                updateTotalsForParticipant(el.dataset.participanteId);
            }
        });
    }

    function updateTotalsForParticipant(participanteId) {
        const fila = document.querySelector(
            `#fila-participante-${participanteId}`
        );
        if (!fila) return;

        const selectsInRow = fila.querySelectorAll(`.asistencia-select`);
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

        const totalEl = fila.querySelector(`.total-asistido`);
        const porcentajeEl = fila.querySelector(`.porcentaje-asistencia`);
        if (totalEl) totalEl.textContent = presentes;
        if (porcentajeEl) porcentajeEl.textContent = `${porcentaje}%`;
    }

    // Initializations
    updateFechaLabel();
    if (initialSelectedPrograma) {
        fetchOptions(
            `${OPCIONES_LUGARES_URL}?programa=${encodeURIComponent(
                initialSelectedPrograma
            )}`,
            filtroLugar,
            filtroGrado,
            initialSelectedLugar,
            "Seleccione Lugar..."
        );
    } else {
        populateSelect(filtroLugar, [], "", "Seleccione Programa...");
        populateSelect(filtroGrado, [], "", "Seleccione Lugar...");
    }

    checkFiltersAndEnableButton();
    if (document.querySelectorAll(".asistencia-select").length > 0) {
        initializeAsistenciaSelects();
    }
});

document.querySelectorAll(".asistencia-select").forEach((select) => {
    const actualizarColor = (el) => {
        el.classList.remove("bg-green-200", "bg-red-200", "bg-yellow-200");
        switch (el.value) {
            case "Presente":
                el.classList.add("bg-green-200");
                break;
            case "Ausente":
                el.classList.add("bg-red-200");
                break;
            case "Justificado":
                el.classList.add("bg-yellow-200");
                break;
        }
    };

    // Aplicar color al cargar
    actualizarColor(select);

    // Escuchar cambios
    select.addEventListener("change", () => actualizarColor(select));
});

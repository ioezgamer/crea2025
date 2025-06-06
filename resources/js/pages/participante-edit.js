// resources/js/pages/participante-edit.js

/**
 * Capitaliza la primera letra de cada palabra en una cadena.
 * @param {string} str La cadena a capitalizar.
 * @returns {string} La cadena con la primera letra de cada palabra en mayúscula.
 */
function capitalizeWords(str) {
    if (!str) return '';
    return str.toLowerCase().replace(/\b\w/g, char => char.toUpperCase());
}

/**
 * Manejador de eventos para capitalizar el valor de un input.
 * @param {Event} event El evento del input.
 */
function handleInputCapitalization(event) {
    const input = event.target;
    const originalSelectionStart = input.selectionStart;
    const originalSelectionEnd = input.selectionEnd;
    input.value = capitalizeWords(input.value);
    input.setSelectionRange(originalSelectionStart, originalSelectionEnd);
}

/**
 * Calcula y establece el año de inscripción basado en la fecha de inscripción.
 */
function calcularAnoInscripcion() {
    const fechaInscripcionInput = document.getElementById('fecha_de_inscripcion');
    const anoInscripcionInput = document.getElementById('ano_de_inscripcion');
    if (fechaInscripcionInput && anoInscripcionInput) {
        if (fechaInscripcionInput.value) {
            const fechaParts = fechaInscripcionInput.value.split('-');
            const fechaInscripcion = new Date(parseInt(fechaParts[0]), parseInt(fechaParts[1]) - 1, parseInt(fechaParts[2]));
            anoInscripcionInput.value = fechaInscripcion.getFullYear();
        } else {
            anoInscripcionInput.value = '';
        }
    }
}

/**
 * Calcula y establece la edad del participante basado en la fecha de nacimiento.
 */
function calcularEdad() {
    const fechaNacimientoInput = document.getElementById('fecha_de_nacimiento_p');
    const edadInput = document.getElementById('edad_p');
    if (fechaNacimientoInput && edadInput) {
        if (fechaNacimientoInput.value) {
            const birthDateParts = fechaNacimientoInput.value.split('-');
            const birthDate = new Date(parseInt(birthDateParts[0]), parseInt(birthDateParts[1]) - 1, parseInt(birthDateParts[2]));
            const today = new Date();
            let age = today.getFullYear() - birthDate.getFullYear();
            const monthDiff = today.getMonth() - birthDate.getMonth();
            if (monthDiff < 0 || (monthDiff === 0 && today.getDate() < birthDate.getDate())) {
                age--;
            }
            edadInput.value = age >= 0 ? age : '';
        } else {
            edadInput.value = '';
        }
    }
}

/**
 * Actualiza el contador de días de asistencia seleccionados.
 */
function updateTotalDiasAsistencia() {
    const diasAsistenciaCountInput = document.getElementById('dias_de_asistencia_al_programa_count_input');
    const totalDiasAsistenciaSpan = document.getElementById('total-dias-asistencia');
    if (diasAsistenciaCountInput && totalDiasAsistenciaSpan) {
        const checkedCount = document.querySelectorAll('.dias-asistencia:checked').length;
        diasAsistenciaCountInput.value = checkedCount;
        totalDiasAsistenciaSpan.textContent = checkedCount;
    }
}

/**
 * Muestra u oculta la sección de detalles de "otros programas"
 * y limpia los campos si se oculta.
 */
function toggleOtrosProgramasVisibility() {
    const asisteOtrosRadios = document.querySelectorAll('.asiste-otros-radio');
    const otrosProgramasSection = document.getElementById('otros-programas-detalles-section'); // Corregido ID si es necesario, o mantener 'otros-programas-section'
    const diasOtrosSection = document.getElementById('dias-otros-section'); // Asumiendo que este ID existe en edit.blade.php
    const otrosProgramasInput = document.getElementById('otros_programas');
    const diasAsisteOtrosProgramasInput = document.getElementById('dias_asiste_a_otros_programas_input');

    let show = false;
    asisteOtrosRadios.forEach(radio => {
        if (radio.checked && radio.value === '1') {
            show = true;
        }
    });

    const targetSection = otrosProgramasSection || document.getElementById('otros-programas-detalles-section'); // Fallback

    if (show) {
        if(targetSection) targetSection.classList.remove('hidden');
        if(diasOtrosSection) diasOtrosSection.classList.remove('hidden'); // Si también aplica a edit
    } else {
        if(targetSection) targetSection.classList.add('hidden');
        if(diasOtrosSection) diasOtrosSection.classList.add('hidden'); // Si también aplica a edit
        if(otrosProgramasInput) otrosProgramasInput.value = '';
        // Para 'dias_asiste_a_otros_programas', si es un input numérico, limpiar a 0 o '',
        // si son checkboxes, desmarcarlos. Asumimos que en edit.blade.php es un input numérico.
        if(diasAsisteOtrosProgramasInput && diasAsisteOtrosProgramasInput.type === 'number') diasAsisteOtrosProgramasInput.value = '0';
        else if (diasAsisteOtrosProgramasInput) { // Si fuera un campo de texto o checkboxes agrupados
             // Si es un campo de texto para los días (ej. "Lunes, Martes")
             // diasAsisteOtrosProgramasInput.value = '';
             // Si son checkboxes, necesitarías seleccionarlos y desmarcarlos
             // document.querySelectorAll('input[name="dias_asiste_a_otros_programas[]"]').forEach(cb => cb.checked = false);
        }
    }
}

/**
 * Muestra u oculta el campo de texto para "otro nivel de participante"
 * basado en la selección del dropdown. (Añadido para edit.blade.php)
 */
function toggleParticipanteOtroEdit() {
    const participanteSelect = document.getElementById('participante_select'); // Asegúrate que el ID es 'participante_select' en edit.blade.php
    const participanteOtroInput = document.getElementById('participante_otro_input'); // Y este ID también

    if (participanteSelect && participanteOtroInput) {
        if (participanteSelect.value === 'Otro') {
            participanteOtroInput.classList.remove('hidden');
            participanteOtroInput.setAttribute('required', 'required');
        } else {
            participanteOtroInput.classList.add('hidden');
            participanteOtroInput.removeAttribute('required');
            if (document.activeElement !== participanteOtroInput) {
                participanteOtroInput.value = '';
            }
        }
    }
}


// Event Listeners
document.addEventListener('DOMContentLoaded', function() {
    const fechaInscripcionInput = document.getElementById('fecha_de_inscripcion');
    const fechaNacimientoInput = document.getElementById('fecha_de_nacimiento_p');
    const diasAsistenciaCheckboxes = document.querySelectorAll('.dias-asistencia');
    const asisteOtrosRadios = document.querySelectorAll('.asiste-otros-radio');
    const participanteSelectEdit = document.getElementById('participante_select'); // ID para el select en edit

    // Event listeners para cálculo de año y edad
    if (fechaInscripcionInput) {
        fechaInscripcionInput.addEventListener('change', calcularAnoInscripcion);
        if (fechaInscripcionInput.value) calcularAnoInscripcion();
    }
    if (fechaNacimientoInput) {
        fechaNacimientoInput.addEventListener('change', calcularEdad);
        if (fechaNacimientoInput.value) calcularEdad();
    }

    if (diasAsistenciaCheckboxes.length > 0) {
        diasAsistenciaCheckboxes.forEach(checkbox => {
            checkbox.addEventListener('change', updateTotalDiasAsistencia);
        });
        updateTotalDiasAsistencia();
    }

    if (asisteOtrosRadios.length > 0) {
        asisteOtrosRadios.forEach(radio => {
            radio.addEventListener('change', toggleOtrosProgramasVisibility);
        });
        // Para el formulario de edición, los valores `old` o del modelo ya controlan el estado inicial,
        // pero llamamos a la función para asegurar que la UI esté correcta.
        toggleOtrosProgramasVisibility();
    }

    // Para el select de "Nivel del participante" en el formulario de edición
    if (participanteSelectEdit) {
        participanteSelectEdit.addEventListener('change', toggleParticipanteOtroEdit);
        // Llamar una vez al cargar para establecer el estado inicial correcto del campo "Otro"
        toggleParticipanteOtroEdit();
    }


    // Aplicar capitalización a los campos de nombres y apellidos
    const fieldsToCapitalizeEdit = [
        'primer_nombre_p', 'segundo_nombre_p', 'primer_apellido_p', 'segundo_apellido_p',
        'nombres_y_apellidos_tutor_principal', 'nombres_y_apellidos_tutor_secundario'
        // Añade aquí cualquier otro campo de texto de nombre/apellido que tengas en edit.blade.php
    ];

    fieldsToCapitalizeEdit.forEach(fieldId => {
        const inputField = document.getElementById(fieldId);
        if (inputField) {
            inputField.addEventListener('input', handleInputCapitalization);
            // Opcional: capitalizar al cargar la página si ya hay valor (desde old() o el modelo)
            // inputField.value = capitalizeWords(inputField.value);
        }
    });
});

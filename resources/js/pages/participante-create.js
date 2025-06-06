// resources/js/pages/participante-create.js

/**
 * Capitaliza la primera letra de cada palabra en una cadena, respetando caracteres especiales.
 * @param {string} str La cadena a capitalizar.
 * @returns {string} La cadena con la primera letra de cada palabra en mayúscula.
 */
function capitalizeWords(str) {
    if (!str) return '';
    // Convertir a minúsculas y luego capitalizar la primera letra de cada palabra.
    // Esto maneja mejor los caracteres especiales como 'ñ' y acentos.
    return str.toLowerCase().split(' ').map(word => {
        if (word.length === 0) return '';
        // Manejar casos especiales como "de", "la", "los" si se desea que queden en minúscula (excepto al inicio)
        // Por ahora, capitaliza la primera letra de todas las palabras.
        return word.charAt(0).toUpperCase() + word.slice(1);
    }).join(' ');
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
    // Restaurar la posición del cursor
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
            anoInscripcionInput.value = new Date().getFullYear();
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
 * Muestra u oculta la sección de detalles de "otros programas"
 * basado en la selección del radio button.
 */
function toggleOtrosProgramas() {
    const radiosAsisteOtros = document.querySelectorAll('.asiste-otros-radio');
    const otrosProgramasDetallesSection = document.getElementById('otros-programas-detalles-section');
    const otrosProgramasInput = document.getElementById('otros_programas');
    const checkboxesDiasOtros = document.querySelectorAll('input[name="dias_asiste_a_otros_programas[]"]');

    if (!radiosAsisteOtros.length || !otrosProgramasDetallesSection || !otrosProgramasInput) {
        return;
    }

    let show = false;
    radiosAsisteOtros.forEach(radio => {
        if (radio.checked && radio.value === '1') {
            show = true;
        }
    });

    if (show) {
        otrosProgramasDetallesSection.classList.remove('hidden');
    } else {
        otrosProgramasDetallesSection.classList.add('hidden');
        otrosProgramasInput.value = '';
        checkboxesDiasOtros.forEach(checkbox => checkbox.checked = false);
    }
}

/**
 * Muestra u oculta el campo de texto para "otro nivel de participante"
 * basado en la selección del dropdown.
 */
function toggleParticipanteOtro() {
    const participanteSelect = document.getElementById('participante_select');
    const participanteOtroInput = document.getElementById('participante_otro_input');
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

/**
 * Inicializa los valores del formulario.
 */
function inicializarValoresFormulario(isReset = false) {
    const fechaInscripcionInput = document.getElementById('fecha_de_inscripcion');
    if (fechaInscripcionInput && (isReset || !fechaInscripcionInput.value)) {
         fechaInscripcionInput.value = new Date().toISOString().split('T')[0];
    }
    calcularAnoInscripcion();
    calcularEdad();
    toggleOtrosProgramas();
    toggleParticipanteOtro();

    if (!isReset) {
        const participanteSelect = document.getElementById('participante_select');
        // `_oldParticipante` y `_oldParticipanteOtro` son definidos en el Blade
        if (typeof _oldParticipante !== 'undefined' && _oldParticipante !== null && participanteSelect) {
            participanteSelect.value = _oldParticipante;
            toggleParticipanteOtro();
             if (_oldParticipante === 'Otro' && typeof _oldParticipanteOtro !== 'undefined' && _oldParticipanteOtro !== null) {
                const participanteOtroInput = document.getElementById('participante_otro_input');
                if(participanteOtroInput) participanteOtroInput.value = _oldParticipanteOtro;
            }
        }
    } else {
        const activoToggle = document.getElementById('activo_toggle');
        if (activoToggle) activoToggle.checked = true;
        const participanteSelect = document.getElementById('participante_select');
        if(participanteSelect) participanteSelect.value = "";
        toggleParticipanteOtro();
    }
}

/**
 * Muestra una confirmación y limpia el formulario si el usuario acepta.
 */
function limpiarFormularioConConfirmacion() {
    if (typeof Swal !== 'undefined') {
        Swal.fire({
            title: '¿Limpiar Formulario?',
            text: "Todos los datos no guardados se perderán.",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Sí, limpiar',
            cancelButtonText: 'Cancelar'
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById('inscripcionForm').reset();
                inicializarValoresFormulario(true);
            }
        });
    } else {
        if (confirm('¿Está seguro de que desea limpiar el formulario? Todos los datos no guardados se perderán.')) {
            document.getElementById('inscripcionForm').reset();
            inicializarValoresFormulario(true);
        }
    }
}

document.addEventListener('DOMContentLoaded', function () {
    inicializarValoresFormulario(false);

    const fechaInscripcionInput = document.getElementById('fecha_de_inscripcion');
    const fechaNacimientoInput = document.getElementById('fecha_de_nacimiento_p');
    const radiosAsisteOtros = document.querySelectorAll('.asiste-otros-radio');
    const participanteSelect = document.getElementById('participante_select');
    const botonLimpiar = document.querySelector('button[onclick*="limpiarFormularioConConfirmacion"]');

    if(fechaInscripcionInput) fechaInscripcionInput.addEventListener('change', calcularAnoInscripcion);
    if(fechaNacimientoInput) fechaNacimientoInput.addEventListener('change', calcularEdad);
    radiosAsisteOtros.forEach(radio => radio.addEventListener('change', toggleOtrosProgramas));
    if (participanteSelect) participanteSelect.addEventListener('change', toggleParticipanteOtro);

    if (botonLimpiar) {
        botonLimpiar.onclick = null;
        botonLimpiar.addEventListener('click', limpiarFormularioConConfirmacion);
    }

    const fieldsToCapitalize = [
        'primer_nombre_p', 'segundo_nombre_p', 'primer_apellido_p', 'segundo_apellido_p',
        'nombres_y_apellidos_tutor_principal', 'nombres_y_apellidos_tutor_secundario',
        'ciudad_p', 'departamento_p', 'escuela_p' // Añadido otros campos que podrían beneficiarse
    ];

    fieldsToCapitalize.forEach(fieldId => {
        const inputField = document.getElementById(fieldId);
        if (inputField) {
            inputField.addEventListener('input', handleInputCapitalization);
        }
    });
});

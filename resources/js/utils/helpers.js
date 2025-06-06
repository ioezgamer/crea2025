// resources/js/utils/helpers.js

/**
 * Muestra un diálogo de confirmación usando SweetAlert2.
 * @param {Event} event - El evento original (usualmente de un submit de formulario).
 * @param {string} title - Título del diálogo.
 * @param {string} text - Texto descriptivo del diálogo.
 * @param {string} confirmButtonText - Texto para el botón de confirmación.
 * @param {string} cancelButtonText - Texto para el botón de cancelar.
 * @param {string} confirmButtonColor - Clase base para el color del botón (ej. 'indigo', 'red').
 */
export function confirmAction(event, title, text, confirmButtonText = 'Sí, continuar', cancelButtonText = 'Cancelar', confirmButtonColor = 'indigo') {
    event.preventDefault(); // Prevenir la acción por defecto (ej. submit del form)
    const formElement = event.target.closest('form'); // Encontrar el formulario más cercano
    if (!formElement) {
        console.error('confirmAction: No se encontró el formulario asociado.');
        return;
    }

    const isDark = document.documentElement.classList.contains('dark');

    Swal.fire({
        title: title,
        text: text,
        icon: (confirmButtonColor === 'red') ? 'warning' : 'question', // 'warning' para acciones destructivas
        showCancelButton: true,
        confirmButtonText: confirmButtonText,
        cancelButtonText: cancelButtonText,
        customClass: {
            popup: `rounded-xl shadow-2xl text-sm ${isDark ? 'bg-slate-800 text-slate-200 backdrop-blur-sm bg-opacity-90' : 'bg-white'}`,
            title: `text-lg font-semibold ${isDark ? 'text-slate-100' : 'text-slate-800'}`,
            htmlContainer: `mt-8 mb-4${isDark ? 'text-slate-300' : 'text-slate-600'}`,
            actions: 'space-x-6 justify-center',
            confirmButton: `btn-sweetalert btn-${confirmButtonColor}`, // Tus clases CSS definidas en app.css
            cancelButton: `btn-sweetalert btn-slate`,        },
        buttonsStyling: false, // Importante para que tus customClass funcionen bien
        reverseButtons: true // Poner el botón de confirmación a la derecha (más común)
    }).then((result) => {
        if (result.isConfirmed) {
            formElement.submit(); // Enviar el formulario si se confirma
        }
    });
}

// Opcional: Si quieres que esté disponible globalmente para usar en onsubmit="confirmAction(...)" directamente
// window.confirmAction = confirmAction;
// Sin embargo, es mejor práctica adjuntar event listeners en JS.

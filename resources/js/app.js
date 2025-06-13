// Importar Bootstrap (si lo usas, y su configuración está en bootstrap.js)
import './bootstrap';

// Importar y configurar Alpine.js
import Alpine from 'alpinejs';
window.Alpine = Alpine;
import flatpickr from "flatpickr"; // Importar flatpickr
import { Spanish } from "flatpickr/dist/l10n/es.js"; // Importar el idioma español

// Asignar el idioma y hacer flatpickr accesible globalmente o para Alpine
flatpickr.localize(Spanish);
window.flatpickr = flatpickr;
Alpine.start();

// Importar la función para mostrar toasts
import { showToast } from './utils/notifications.js'; // Asegúrate de que la ruta sea correcta

// Esperar a que el DOM esté completamente cargado
document.addEventListener('DOMContentLoaded', function () {

    // Lógica para mostrar mensajes flash de sesión como toasts
    const sessionMessagesEl = document.getElementById('sessionMessages');
    if (sessionMessagesEl) {
        const successMessage = sessionMessagesEl.dataset.successMessage;
        const errorMessage = sessionMessagesEl.dataset.errorMessage;
        const warningMessage = sessionMessagesEl.dataset.warningMessage;
        const infoMessage = sessionMessagesEl.dataset.infoMessage;

        if (successMessage) {
            showToast(successMessage, 'success');
        }
        if (errorMessage) {
            showToast(errorMessage, 'error');
        }
        if (warningMessage) {
            showToast(warningMessage, 'warning');
        }
        if (infoMessage) {
            showToast(infoMessage, 'info');
        }
        // Opcional: remover el elemento después de procesar para limpiar el DOM,
        // aunque al ser 'hidden' no molesta visualmente.
        // sessionMessagesEl.remove();
    }

    // Puedes añadir otra lógica JavaScript global aquí si es necesario
    // console.log('App.js cargado, Alpine iniciado y notificaciones configuradas.');

});

// Para que la función showToast esté disponible globalmente si necesitas llamarla desde
// scripts en línea o desde la consola (generalmente no recomendado para código de producción,
// pero puede ser útil para depuración o casos muy específicos).
// Es mejor importar `showToast` en los módulos JS donde la necesites.
// window.showToast = showToast;

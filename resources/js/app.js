// Importar Bootstrap (si lo usas, y su configuración está en bootstrap.js)
import './bootstrap';

// Importar Alpine.js
import Alpine from 'alpinejs';

// Importar Flatpickr y el idioma
import flatpickr from "flatpickr";
import { Spanish } from "flatpickr/dist/l10n/es.js";

// === INICIO DE LA LÓGICA DE GRÁFICOS ===
// 1. Importar el componente de dashboard que creamos
import dashboardCharts from './components/alpine-dashboard.js';

// 2. Registrar el componente para que esté disponible globalmente como 'dashboardCharts'
Alpine.data('dashboardCharts', dashboardCharts);
// === FIN DE LA LÓGICA DE GRÁFICOS ===

// Configurar Flatpickr
flatpickr.localize(Spanish);
window.flatpickr = flatpickr;

// Hacer Alpine global e iniciarlo (MUY IMPORTANTE: Iniciar después de registrar los componentes)
window.Alpine = Alpine;
Alpine.start();

// Importar tu sistema de notificaciones
import { showToast } from './utils/notifications.js';

// Lógica para mostrar mensajes flash de sesión como toasts
document.addEventListener('DOMContentLoaded', function () {
    const sessionMessagesEl = document.getElementById('sessionMessages');
    if (sessionMessagesEl) {
        const successMessage = sessionMessagesEl.dataset.successMessage;
        const errorMessage = sessionMessagesEl.dataset.errorMessage;

        if (successMessage) showToast(successMessage, 'success');
        if (errorMessage) showToast(errorMessage, 'error');
    }
});

/**
 * @file Contiene utilidades para mostrar notificaciones (toasts) en la interfaz de usuario.
 * @version 2.0.0
 * @author Tu Nombre
 * @see {@link https://github.com/tu-usuario/tu-proyecto|Repositorio del Proyecto}
 */

// --- Configuración Centralizada de Estilos para los Toasts ---
// Almacena las propiedades de cada tipo de toast (ícono y clases de estilo).
// Facilita la adición de nuevos tipos y la modificación de los existentes sin alterar la lógica principal.
const TOAST_CONFIG = {
    success: {
        icon: `<svg class="w-5 h-5 mr-2.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg>`,
        light: 'bg-green-50/90 text-green-700 border-green-500',
        dark: 'bg-green-700/30 text-green-200 border-green-600',
    },
    error: {
        icon: `<svg class="w-5 h-5 mr-2.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 101.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path></svg>`,
        light: 'bg-red-50/90 text-red-700 border-red-500',
        dark: 'bg-red-700/30 text-red-200 border-red-600',
    },
    warning: {
        icon: `<svg class="w-5 h-5 mr-2.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 3.001-1.742 3.001H4.42c-1.53 0-2.493-1.667-1.743-3.001l5.58-9.92zM10 13a1 1 0 110-2 1 1 0 010 2zm-1-8a1 1 0 011-1h.01a1 1 0 110 2H10a1 1 0 01-1-1z" clip-rule="evenodd"></path></svg>`,
        light: 'bg-amber-50/90 text-amber-700 border-amber-400',
        dark: 'bg-amber-600/30 text-amber-200 border-amber-500',
    },
    info: {
        icon: `<svg class="w-5 h-5 mr-2.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path></svg>`,
        light: 'bg-sky-50/90 text-sky-700 border-sky-500',
        dark: 'bg-sky-700/30 text-sky-200 border-sky-600',
    },
};

/**
 * Crea y gestiona el contenedor global para los toasts si no existe.
 * @returns {HTMLElement} El elemento contenedor de los toasts.
 */
function getOrCreateToastContainer() {
    const containerId = 'global_toast_container';
    let container = document.getElementById(containerId);

    if (!container) {
        container = document.createElement('div');
        container.id = containerId;
        // Clases de Tailwind CSS para posicionar y estilizar el contenedor.
        container.className = 'fixed top-20 right-5 z-[100] w-full max-w-xs sm:max-w-sm space-y-3';
        document.body.appendChild(container);
    }
    return container;
}

/**
 * Muestra una notificación (toast) en la pantalla.
 * @param {string} message - El mensaje a mostrar en el toast.
 * @param {'success'|'error'|'warning'|'info'} [type='info'] - El tipo de toast. Determina el color y el ícono.
 * @param {number} [duration=5000] - La duración en milisegundos que el toast permanecerá visible.
 */
export function showToast(message, type = 'info', duration = 5000) {
    const toastContainer = getOrCreateToastContainer();

    // Determina si el modo oscuro está activo.
    const isDark = document.documentElement.classList.contains('dark');

    // Selecciona la configuración del toast. Usa 'info' como valor por defecto si el tipo no es válido.
    const config = TOAST_CONFIG[type] || TOAST_CONFIG.info;
    const styleClasses = config[isDark ? 'dark' : 'light'];
    const buttonHoverClasses = isDark ?
        'text-slate-400 hover:text-slate-200' :
        'text-slate-500 hover:text-slate-700';

    // Creación del elemento toast
    const toast = document.createElement('div');
    toast.className = `flex items-center p-4 rounded-xl shadow-2xl border-l-4 text-sm font-medium mb-3 transition-all duration-500 ease-out transform translate-x-full opacity-0 backdrop-blur-md ${styleClasses}`;
    toast.setAttribute('role', 'alert');

    // Estructura interna del toast usando template literals para mayor claridad.
    toast.innerHTML = `
        ${config.icon}
        <span class="flex-grow">${message}</span>
        <button type="button" class="ml-3 -mx-1.5 -my-1.5 p-1.5 inline-flex items-center justify-center h-8 w-8 rounded-lg focus:ring-2 focus:ring-slate-400 hover:bg-slate-500/10 transition-colors ${buttonHoverClasses}" aria-label="Cerrar">
            <svg class="w-3 h-3" aria-hidden="true" fill="none" viewBox="0 0 14 14">
                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6"/>
            </svg>
        </button>
    `;

    toastContainer.appendChild(toast);

    // --- Animación de Entrada ---
    // Usamos requestAnimationFrame para asegurar que la transición ocurra después de que el elemento se haya renderizado.
    requestAnimationFrame(() => {
        toast.classList.remove('translate-x-full', 'opacity-0');
        toast.classList.add('translate-x-0', 'opacity-100');
    });

    // --- Lógica para eliminar el toast ---
    const removeToast = () => {
        // Inicia la animación de salida.
        toast.classList.remove('translate-x-0', 'opacity-100');
        toast.classList.add('opacity-0'); // Se puede añadir 'translate-x-full' para un efecto de deslizamiento.

        // Elimina el elemento del DOM una vez que la transición de opacidad ha terminado.
        toast.addEventListener('transitionend', () => toast.remove(), { once: true });
    };

    // Temporizador para auto-eliminar el toast.
    const timerId = setTimeout(removeToast, duration);

    // Event listener para el botón de cerrar.
    // Busca el botón dentro del toast recién creado.
    const closeButton = toast.querySelector('button');
    if (closeButton) {
        closeButton.addEventListener('click', () => {
            clearTimeout(timerId); // Cancela el temporizador si se cierra manualmente.
            removeToast();
        });
    }
}

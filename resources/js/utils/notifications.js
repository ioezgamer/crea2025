// resources/js/utils/notifications.js
export function showToast(message, type = 'success', duration = 5000) {
    const toastContainer = document.getElementById('global_toast_container') || createGlobalToastContainer();
    if (!toastContainer) {
        console.error('No se pudo crear o encontrar el contenedor de toasts.');
        return;
    }

    const toastId = 'toast-' + Date.now();
    const toast = document.createElement('div');

    let bgColor, textColor, borderColor, iconSvg;
    const isDark = document.documentElement.classList.contains('dark');

    switch (type) {
        case 'success':
            bgColor = isDark ? 'bg-green-700/30' : 'bg-green-50/90';
            textColor = isDark ? 'text-green-200' : 'text-green-700';
            borderColor = isDark ? 'border-green-600' : 'border-green-500';
            iconSvg = `<svg class="w-5 h-5 mr-2.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg>`;
            break;
        case 'error':
            bgColor = isDark ? 'bg-red-700/30' : 'bg-red-50/90';
            textColor = isDark ? 'text-red-200' : 'text-red-700';
            borderColor = isDark ? 'border-red-600' : 'border-red-500';
            iconSvg = `<svg class="w-5 h-5 mr-2.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 101.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path></svg>`;
            break;
        case 'warning':
            bgColor = isDark ? 'bg-amber-600/30' : 'bg-amber-50/90';
            textColor = isDark ? 'text-amber-200' : 'text-amber-700';
            borderColor = isDark ? 'border-amber-500' : 'border-amber-400';
            iconSvg = `<svg class="w-5 h-5 mr-2.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 3.001-1.742 3.001H4.42c-1.53 0-2.493-1.667-1.743-3.001l5.58-9.92zM10 13a1 1 0 110-2 1 1 0 010 2zm-1-8a1 1 0 011-1h.01a1 1 0 110 2H10a1 1 0 01-1-1z" clip-rule="evenodd"></path></svg>`;
            break;
        default: // info y otros
            bgColor = isDark ? 'bg-sky-700/30' : 'bg-sky-50/90';
            textColor = isDark ? 'text-sky-200' : 'text-sky-700';
            borderColor = isDark ? 'border-sky-600' : 'border-sky-500';
            iconSvg = `<svg class="w-5 h-5 mr-2.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path></svg>`;
    }

    toast.className = `flex items-center p-4 rounded-xl shadow-2xl border-l-4 ${bgColor} ${textColor} ${borderColor} text-sm font-medium mb-3 transition-all duration-500 ease-out transform translate-x-full opacity-0 backdrop-blur-md`;
    toast.setAttribute('role', 'alert');

    // CORRECCIÓN AQUÍ: Usar Template Literals correctamente para interpolar las variables.
    toast.innerHTML = `
        ${iconSvg}
        <span class="flex-grow">${message}</span>
        <button type="button" class="ml-3 -mx-1.5 -my-1.5 p-1.5 inline-flex items-center justify-center h-8 w-8 ${isDark ? 'text-slate-400 hover:text-slate-200' : 'text-slate-500 hover:text-slate-700'} rounded-lg focus:ring-2 focus:ring-slate-400 hover:bg-slate-500/10 transition-colors" aria-label="Cerrar">
            <svg class="w-3 h-3" aria-hidden="true" fill="currentColor" viewBox="0 0 14 14">
                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6"/>
            </svg>
        </button>
    `;
    // FIN DE LA CORRECCIÓN

    toastContainer.appendChild(toast);

    requestAnimationFrame(() => {
        toast.classList.remove('translate-x-full', 'opacity-0');
        toast.classList.add('translate-x-0', 'opacity-100');
    });

    const removeToast = () => {
        toast.classList.add('opacity-0');
        // Para animar la salida si se desea (ej. slide out)
        // toast.classList.add('-translate-x-full'); // o 'translate-x-full' dependiendo de la dirección
        toast.classList.remove('translate-x-0');
        toast.addEventListener('transitionend', () => toast.remove(), { once: true });
    };

    const timerId = setTimeout(removeToast, duration);

    // Asegurarse de que el botón de cerrar exista antes de añadir el event listener
    const closeButton = toast.querySelector('button');
    if (closeButton) {
        closeButton.addEventListener('click', () => {
            clearTimeout(timerId);
            removeToast();
        });
    }
}

function createGlobalToastContainer() {
    let container = document.getElementById('global_toast_container');
    if (!container) {
        container = document.createElement('div');
        container.id = 'global_toast_container';
        // Estilos para el contenedor de toasts
        container.className = 'fixed top-20 right-5 z-[100] w-full max-w-xs sm:max-w-sm space-y-3';
        document.body.appendChild(container);
    }
    return container;
}

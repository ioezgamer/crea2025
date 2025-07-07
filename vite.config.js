import { defineConfig, loadEnv } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig(({ mode }) => {
    // Carga las variables de entorno de tu archivo .env
    const env = loadEnv(mode, process.cwd(), '');

    return {
        server: {
            // Habilita CORS para permitir conexiones desde cualquier origen.
            // Esto es crucial para que ngrok (u otros proxies) puedan acceder a los assets de Vite.
            cors: true,
        },
        plugins: [
            laravel({
                input: [
                    'resources/css/app.css',
                    'resources/js/app.js',
                    'resources/js/components/alpine-dashboard.js',
                    'resources/js/pages/asistencia-attendance.js',
                    'resources/js/pages/participante-index.js',
                    'resources/js/pages/estadisticas-programas.js',
                    'resources/js/pages/dashboard.js',
                    'resources/js/pages/participante-create.js',
                    'resources/js/pages/participante-edit.js',

                ],
                refresh: true, // Puedes configurarlo a un array de rutas si necesitas más control sobre la recarga
            }),
        ],
    };
});

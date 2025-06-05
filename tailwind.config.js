// Asegúrate de que tienes `tailwindcss` y otras dependencias necesarias instaladas.
// npm install -D tailwindcss postcss autoprefixer
// npx tailwindcss init -p

/** @type {import('tailwindcss').Config} */
export default {
  content: [
    "./resources/**/*.blade.php",
    "./resources/**/*.js",
    "./resources/**/*.vue",
    "./app/View/Components/**/*.php", // Asegúrate de incluir tus componentes de Blade
    "./storage/framework/views/*.php",
  ],
  darkMode: 'class', // Habilita la estrategia de clases para el modo oscuro
  theme: {
    extend: {
      fontFamily: {
        // Reemplaza 'Figtree' con la fuente principal de tu aplicación si es diferente
        sans: ['Figtree', ...require('tailwindcss/defaultTheme').fontFamily.sans],
      },
      // Puedes extender tus colores aquí para definir variantes específicas para el modo oscuro si es necesario,
      // aunque Tailwind maneja esto bien con los prefijos `dark:`.
      // Ejemplo:
      // colors: {
      //   'custom-bg-light': '#FFFFFF',
      //   'custom-bg-dark': '#1a202c',
      //   'custom-text-light': '#1f2937',
      //   'custom-text-dark': '#a0aec0',
      // },
    },
  },
  plugins: [
    require('@tailwindcss/forms'), // Si usas formularios con estilo de Tailwind
    // Otros plugins que puedas estar usando
  ],
};

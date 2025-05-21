{{-- Assuming this is a Blade component, x-guest-layout provides the basic page structure --}}
<x-guest-layout>
    {{-- Centering container for the registration card --}}
    

        {{-- Registration Card --}}
        <div class=" max-w-full min-h-9xl xl:max-w-full p-2 lg:p-8 bg-white/80 backdrop-blur-xl rounded-3xl shadow-xl transform transition-all duration-300 ease-in hover:shadow-4xl my-6 lg:my-8">
            <div class="text-center">
                {{-- Icono de Foco/Idea --}}
                <div class="mx-auto mb-5 h-16 w-16 sm:h-20 sm:w-20 flex items-center justify-center rounded-full bg-gradient-to-br from-indigo-500 to-purple-600 p-1 shadow-lg">
                    <div class="flex h-full w-full items-center justify-center rounded-full bg-white">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 sm:h-10 sm:w-10 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z" />
                        </svg>
                    </div>
                </div>

                <h2 class="text-2xl sm:text-3xl font-bold text-transparent bg-clip-text bg-gradient-to-r from-indigo-600 via-purple-600 to-pink-500 tracking-tight text-balance">
                    Crear una Cuenta en CREA
                </h2>
                <p class="mt-2 text-sm text-slate-600 text-balance">
                    Completa el formulario para unirte a nuestra comunidad creativa.
                </p>
            </div>

            <form method="POST" action="{{ route('register') }}" class="mt-8 space-y-6">
                @csrf

                {{-- Name Field --}}
                <div>
                    <label for="name" class="block text-sm font-medium text-slate-700 mb-1">Nombre completo</label>
                    <input id="name" type="text" name="name" value="{{ old('name') }}" required autofocus autocomplete="name"
                           class="block w-full px-4 py-3 rounded-xl border-slate-300 shadow-sm placeholder-slate-400 
                                  focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 
                                  transition duration-150 ease-in-out text-sm sm:text-base"
                           placeholder="Tu nombre completo">
                    <x-input-error :messages="$errors->get('name')" class="mt-2 text-xs text-red-600" />
                </div>

                {{-- Email Field --}}
                <div>
                    <label for="email" class="block text-sm font-medium text-slate-700 mb-1">Correo electrónico</label>
                    <input id="email" type="email" name="email" value="{{ old('email') }}" required autocomplete="username"
                           class="block w-full px-4 py-3 rounded-xl border-slate-300 shadow-sm placeholder-slate-400
                                  focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 
                                  transition duration-150 ease-in-out text-sm sm:text-base"
                           placeholder="tu@correo.com">
                    <x-input-error :messages="$errors->get('email')" class="mt-2 text-xs text-red-600" />
                </div>

                {{-- Password Field --}}
                <div>
                    <label for="password" class="block text-sm font-medium text-slate-700 mb-1">Contraseña</label>
                    <input id="password" type="password" name="password" required autocomplete="new-password"
                           class="block w-full px-4 py-3 rounded-xl border-slate-300 shadow-sm placeholder-slate-400
                                  focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 
                                  transition duration-150 ease-in-out text-sm sm:text-base"
                           placeholder="Mínimo 8 caracteres">
                    <x-input-error :messages="$errors->get('password')" class="mt-2 text-xs text-red-600" />
                </div>

                {{-- Confirm Password Field --}}
                <div>
                    <label for="password_confirmation" class="block text-sm font-medium text-slate-700 mb-1">Confirmar Contraseña</label>
                    <input id="password_confirmation" type="password" name="password_confirmation" required autocomplete="new-password"
                           class="block w-full px-4 py-3 rounded-xl border-slate-300 shadow-sm placeholder-slate-400
                                  focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 
                                  transition duration-150 ease-in-out text-sm sm:text-base"
                           placeholder="Repite tu contraseña">
                    <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2 text-xs text-red-600" />
                </div>

                <div class="flex flex-col sm:flex-row items-center justify-between mt-8 gap-4">
                    <a class="text-sm text-indigo-600 hover:text-indigo-500 hover:underline font-medium transition duration-150 ease-in-out" href="{{ route('login') }}">
                        ¿Ya estás registrado? Inicia sesión
                    </a>

                    <button type="submit"
                            class="group relative flex justify-center items-center w-full sm:w-auto text-base font-semibold px-6 py-3 border-2 border-transparent rounded-full transition-all duration-300 ease-in-out
                                   bg-gradient-to-r from-indigo-600 to-purple-600 text-white shadow-md hover:shadow-lg hover:scale-105 focus:outline-none focus:ring-2 focus:ring-purple-500 focus:ring-offset-2">
                        <span>{{ __('Registrar') }}</span>
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 ml-2 transition-transform duration-300 group-hover:translate-x-1" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2.25-4.125a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zM4 19.235v-.11a6.375 6.375 0 0112.75 0v.109A12.318 12.318 0 0110.374 21c-2.331 0-4.512-.645-6.374-1.766z" />
                        </svg>
                    </button>
                </div>
            </form>
        </div>
        <footer class="mt-8 sm:mt-12 text-center">
            <p class="text-xs sm:text-sm text-slate-600">
                &copy; {{ date('Y') }} Sistema CREA. Todos los derechos reservados.
            </p>
        </footer>
   
</x-guest-layout>

{{-- Ensure you have Tailwind CSS available in your project. 
If you are not using Vite or a similar build process, you might need to include the Tailwind CDN link in your main layout file.
Example for Tailwind CDN (place in <head>):
<script src="https://cdn.tailwindcss.com"></script>
<style type="text/tailwindcss">
    @layer utilities {
      .text-balance {
        text-wrap: balance;
      }
    }
    body {
        font-family: 'Instrument Sans', ui-sans-serif, system-ui, sans-serif, "Apple Color Emoji", "Segoe UI Emoji", "Segoe UI Symbol", "Noto Color Emoji";
        /* Ensure Instrument Sans is loaded, e.g., via Google Fonts or Bunny Fonts in your main layout */
    }
</style>
--}}

{{-- 
    Note on Blade Components (x-input-label, x-text-input, x-input-error, x-primary-button):
    The original code uses Blade components. I've replaced them with standard HTML and Tailwind for broader compatibility
    and to directly show the styling. If you prefer to keep using your Blade components, you would need to ensure
    those components are styled with Tailwind CSS classes internally, or pass the classes to them.
    For example, your <x-input-label> would need to accept a `class` attribute or have Tailwind classes defined within its own Blade file.
    The classes I've used on the standard HTML elements (label, input) are examples of how you might style them.
    The <x-input-error> component is assumed to handle displaying error messages; I've added a suggestion for its styling.
    The primary button styling from the landing page has been applied to the submit button.
--}}

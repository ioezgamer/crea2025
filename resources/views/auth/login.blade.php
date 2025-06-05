<x-guest-layout>
    <!-- Tarjeta Principal: Sin max-h. La altura es determinada por el contenido y limitada por el contenedor <main> en guest.blade.php -->
    <!-- my-auto ayuda a centrar verticalmente si <main> en guest.blade.php tuviera espacio extra (no debería con flex-grow, pero es seguro) -->
    <div class="flex flex-col w-full max-w-[95vw] sm:max-w-lg md:max-w-2xl lg:max-w-4xl xl:max-w-5xl mx-auto my-auto  bg-white/80 backdrop-blur-xl rounded-2xl overflow-x-auto xl:flex-row">

        <!-- Sección de la Imagen: Oculta en pantallas < xl, visible en xl+ -->
        <!-- El div de la imagen se estirará a la altura de la tarjeta. -->
        <div class="hidden bg-center bg-no-repeat xl:block xl:w-1/2"
             style="background-image: url('https://images.unsplash.com/photo-1506744038136-46273834b3fb?ixlib=rb-1.2.1&ixid=MnwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8&auto=format&fit=crop&w=1000&q=80'); background-size: cover; background-position: center;">
            {{-- Contenido dentro de la imagen para asegurar que el div tenga dimensiones si la imagen de fondo falla o para superponer texto --}}
            <div class="flex items-center justify-center w-full h-full p-6 text-center bg-black bg-opacity-25">
                <div>
                    <h2 class="mb-3 text-2xl font-bold text-white xl:text-3xl text-balance">Bienvenido a CREA</h2>
                    <p class="text-base text-slate-100 xl:text-lg text-balance">Donde tus ideas toman forma y tus proyectos alcanzan el éxito.</p>
                </div>
            </div>
        </div>

        <!-- Sección del Formulario: Ancho completo en < xl, mitad de ancho en xl+ -->
        <!-- Si el contenido del formulario es muy largo, se podría añadir overflow-y-auto y una altura máxima a este div. -->
        <!-- Por ahora, se asume que el contenido del formulario cabe. El overflow-hidden en la tarjeta lo cortaría si no. -->
        <div class="flex flex-col justify-center w-full p-6 xl:w-1/2 sm:p-8 md:p-10">
            <div class="w-full max-w-xs mx-auto sm:max-w-sm"> {{-- Ajustado max-w para el formulario --}}
                <div class="mb-5 text-center sm:mb-6">
                    <a href="/" class="inline-block mb-3 sm:mb-4">
                        <div class="flex items-center justify-center w-12 h-12 p-1 mx-auto rounded-full shadow-lg sm:w-14 sm:h-14 bg-gradient-to-br from-indigo-500 to-purple-600">
                            <div class="flex items-center justify-center w-full h-full bg-white rounded-full">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-indigo-600 sm:w-7 sm:h-7" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z" />
                                </svg>
                            </div>
                        </div>
                    </a>
                    <h2 class="text-xl font-bold tracking-tight text-transparent sm:text-2xl md:text-3xl bg-clip-text bg-gradient-to-r from-indigo-600 via-purple-600 to-pink-500 text-balance">
                        Sistema CREA
                    </h2>
                    <p class="mt-1 text-sm text-slate-600 sm:mt-2 sm:text-base text-balance">
                        ¡Bienvenido de nuevo! Por favor, inicia sesión.
                    </p>
                </div>

                <x-auth-session-status class="mb-3 text-sm sm:mb-4" :status="session('status')" />

                <form method="POST" action="{{ route('login') }}" class="space-y-3 sm:space-y-4">
                    @csrf
                    <div>
                        <label for="email" class="block mb-1 text-xs font-medium sm:text-sm text-slate-700">Correo electrónico</label>
                        <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus autocomplete="username"
                               class="block w-full px-3 py-2 text-sm transition duration-150 ease-in-out rounded-lg shadow-sm sm:py-2.5 border-slate-300 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"
                               placeholder="tu@correo.com">
                        <x-input-error :messages="$errors->get('email')" class="mt-1 text-xs sm:mt-1.5 text-red-500" />
                    </div>

                    <div>
                        <div class="flex items-center justify-between mb-1">
                            <label for="password" class="block text-xs font-medium sm:text-sm text-slate-700">Contraseña</label>
                            @if (Route::has('password.request'))
                                <a class="text-xs font-medium text-indigo-600 transition duration-150 ease-in-out sm:text-sm hover:text-indigo-500 hover:underline" href="{{ route('password.request') }}">
                                    ¿Olvidaste tu contraseña?
                                </a>
                            @endif
                        </div>
                        <input id="password" type="password" name="password" required autocomplete="current-password"
                               class="block w-full px-3 py-2 text-sm transition duration-150 ease-in-out rounded-lg shadow-sm sm:py-2.5 border-slate-300 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"
                               placeholder="Tu contraseña">
                        <x-input-error :messages="$errors->get('password')" class="mt-1 text-xs sm:mt-1.5 text-red-500" />
                    </div>

                    <div class="flex items-center justify-between">
                        <label for="remember_me" class="inline-flex items-center">
                            <input id="remember_me" type="checkbox" class="w-3 h-3 text-indigo-600 transition duration-150 ease-in-out rounded shadow-sm sm:w-4 sm:h-4 border-slate-300 focus:ring-indigo-500 focus:ring-offset-0" name="remember">
                            <span class="ml-2 text-xs sm:text-sm text-slate-600">Recordarme</span>
                        </label>
                    </div>

                    <div class="pt-1 sm:pt-2">
                        <button type="submit"
                                class="relative flex items-center justify-center w-full px-4 py-2 text-sm font-semibold text-white transition-all duration-300 ease-in-out border-2 border-transparent rounded-full shadow-md sm:px-5 sm:py-2.5 sm:text-base group bg-gradient-to-r from-indigo-600 to-purple-600 hover:shadow-lg hover:from-indigo-700 hover:to-purple-700 hover:scale-105 focus:outline-none focus:ring-2 focus:ring-purple-500 focus:ring-offset-2">
                            <span>{{ __('Iniciar sesión') }}</span>
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 ml-2 transition-transform duration-300 sm:w-5 sm:h-5 group-hover:translate-x-1" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h5a3 3 0 013 3v1" />
                            </svg>
                        </button>
                    </div>
                </form>

                @if (Route::has('register'))
                    <div class="pt-4 mt-4 border-t sm:pt-5 sm:mt-5 border-slate-200">
                        <p class="text-xs text-center sm:text-sm text-slate-600">
                            ¿No tienes una cuenta?
                            <a href="{{ route('register') }}" class="font-medium text-indigo-600 transition duration-150 ease-in-out hover:text-indigo-500 hover:underline">
                                Regístrate aquí
                            </a>
                        </p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-guest-layout>
